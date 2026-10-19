<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Route controller serving the admin-configured custom H5P CSS.
 *
 * @package    mod_hvp
 * @copyright  2026 ISB Bayern
 * @author     Philipp Memmel
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_hvp\route\controller;

use mod_hvp\custom_css;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Serves the admin-configured custom H5P CSS.
 *
 * The stylesheet is referenced from {@see \mod_hvp_renderer::hvp_alter_styles()} with a
 * content-hash appended as a cache-busting 'v' query parameter. Only if that revision matches
 * the hash of the currently configured CSS the response may be cached long-term and
 * "immutable": in that case the URL is a true content address, so the body served under it can
 * never change and the client never needs to re-validate. Any content change produces a new URL
 * and is therefore picked up immediately.
 *
 * Requests carrying a missing or outdated revision are still answered with the current CSS (so
 * styling never breaks while a configuration change propagates), but must not be cached
 * long-term: the same URL would otherwise be pinned to a body that does not belong to it. If the
 * admin later reverts the setting, the renderer emits that very URL again and the client would
 * keep serving the poisoned, immutable copy from its cache for months. Such responses are
 * therefore marked 'no-cache', which still allows storing them but forces revalidation.
 *
 * No ETag is sent and conditional requests are not evaluated. Revalidation only ever happens for
 * the rare mismatched revision above, because a matching one is never re-requested at all, so
 * supporting a bodyless 304 would add entity tag comparison logic to save a few kilobytes in a
 * transitional case.
 *
 * The route is deliberately unauthenticated and session-less (cookies: false): the CSS is
 * site-wide, admin-configured presentation data which contains no user specific or otherwise
 * restricted information, and it must also be loadable from within the sandboxed H5P iframe,
 * which does not carry the Moodle session.
 *
 * @package    mod_hvp
 * @copyright  2026 ISB Bayern
 * @author     Philipp Memmel
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class custom_css_controller {
    use \core\router\route_controller;

    /** @var int Number of seconds to let clients cache the response for (90 days). */
    const CACHE_LIFETIME = 90 * DAYSECS;

    /**
     * Constructor.
     *
     * @param custom_css $customcss Provider of the admin-configured custom CSS.
     */
    public function __construct(
        /** @var custom_css Provider of the admin-configured custom CSS. */
        private readonly custom_css $customcss,
    ) {
    }

    /**
     * Serve the configured CSS with revision-checked client-side caching.
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    #[\core\router\route(
        path: '/custom.css',
        method: ['GET'],
        title: 'Serve custom H5P CSS',
        description: 'Serves the admin-configured custom CSS applied to H5P content.',
        cookies: false,
        queryparams: [
            new \core\router\schema\parameters\query_parameter(
                name: 'v',
                type: \core\param::ALPHANUM,
                description: 'Content hash of the requested CSS revision.',
                default: '',
            ),
        ],
    )]
    public function serve(
        ServerRequestInterface $request,
        ResponseInterface $response,
    ): ResponseInterface {
        $currentrevision = $this->customcss->get_hash();
        $requestedrevision = $request->getQueryParams()['v'] ?? '';

        // Only a URL which addresses the revision we are actually serving is a content address
        // and may be pinned in the client cache forever. A missing or outdated revision means
        // the URL and the body do not belong together, so the response must stay revalidatable.
        $cachecontrol = $requestedrevision === $currentrevision
            ? 'public, max-age=' . self::CACHE_LIFETIME . ', immutable'
            : 'public, no-cache';

        $response = $response
            ->withHeader('Content-Type', 'text/css; charset=utf-8')
            ->withHeader('X-Content-Type-Options', 'nosniff')
            ->withHeader('Accept-Ranges', 'none')
            ->withHeader('Cache-Control', $cachecontrol);

        $response->getBody()->write($this->customcss->get_css());

        return $response;
    }
}
