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
 * Unit tests for the custom H5P CSS route controller.
 *
 * @package    mod_hvp
 * @copyright  2026 ISB Bayern
 * @author     Philipp Memmel
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_hvp\route\controller;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use mod_hvp\custom_css;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Psr\Http\Message\ResponseInterface;

/**
 * Unit tests for the custom H5P CSS route controller.
 *
 * @package    mod_hvp
 * @copyright  2026 ISB Bayern
 * @author     Philipp Memmel
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
#[CoversClass(custom_css_controller::class)]
final class custom_css_controller_test extends \advanced_testcase {
    /** @var string CSS used as the "current" configuration in the tests. */
    private const CSS_A = '.h5p-content { color: red; }';

    /** @var string CSS used as the "other" configuration in the tests. */
    private const CSS_B = '.h5p-content { color: blue; }';

    /**
     * Serve a request for the given revision and return the response.
     *
     * @param ?string $revision The value of the 'v' query parameter, null to omit it entirely.
     * @return ResponseInterface The response of the controller.
     */
    private function serve(?string $revision): ResponseInterface {
        $request = new ServerRequest('GET', '/mod_hvp/custom.css');
        if ($revision !== null) {
            $request = $request->withQueryParams(['v' => $revision]);
        }

        $controller = new custom_css_controller(new custom_css());

        return $controller->serve($request, new Response());
    }

    /**
     * The response is only cacheable as immutable if it is requested under its own content hash.
     *
     * A stale or missing revision means the URL and the body do not belong together, so pinning the
     * response in the client cache would poison that URL: the configuration could later be reverted,
     * which makes the renderer emit that very URL again while the client still holds the wrong body.
     *
     * @param string $revisiontype Which revision the client asks for: current, outdated or missing.
     * @param bool $expectimmutable Whether the response may be cached long-term and immutable.
     */
    #[DataProvider('serve_cache_control_provider')]
    public function test_serve_cache_control(string $revisiontype, bool $expectimmutable): void {
        $this->resetAfterTest();
        set_config('customcss', self::CSS_A, 'mod_hvp');

        $revision = match ($revisiontype) {
            'current' => sha1(self::CSS_A),
            'outdated' => sha1(self::CSS_B),
            'missing' => null,
        };

        $response = $this->serve($revision);

        $expected = $expectimmutable
            ? 'public, max-age=' . custom_css_controller::CACHE_LIFETIME . ', immutable'
            : 'public, no-cache';
        $this->assertSame($expected, $response->getHeaderLine('Cache-Control'));
    }

    /**
     * Data provider for {@see self::test_serve_cache_control()}.
     *
     * @return array[]
     */
    public static function serve_cache_control_provider(): array {
        return [
            'current_revision' => [
                'revisiontype' => 'current',
                'expectimmutable' => true,
            ],
            'outdated_revision' => [
                'revisiontype' => 'outdated',
                'expectimmutable' => false,
            ],
            'missing_revision' => [
                'revisiontype' => 'missing',
                'expectimmutable' => false,
            ],
        ];
    }

    /**
     * The currently configured CSS is served.
     */
    public function test_serve_returns_current_css(): void {
        $this->resetAfterTest();
        set_config('customcss', self::CSS_A, 'mod_hvp');

        $response = $this->serve(sha1(self::CSS_A));

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(self::CSS_A, (string) $response->getBody());
        $this->assertSame('text/css; charset=utf-8', $response->getHeaderLine('Content-Type'));
        $this->assertSame('nosniff', $response->getHeaderLine('X-Content-Type-Options'));
    }

    /**
     * An outdated revision still receives the current CSS, so that styling does not break while a
     * configuration change propagates through already rendered pages.
     */
    public function test_serve_outdated_revision_returns_current_css(): void {
        $this->resetAfterTest();
        set_config('customcss', self::CSS_A, 'mod_hvp');

        $response = $this->serve(sha1(self::CSS_B));

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(self::CSS_A, (string) $response->getBody());
    }

    /**
     * Regression test for the A->B->A cache poisoning scenario.
     *
     * A request for revision A which arrives while configuration B is active must never be stored as
     * immutable, because after switching back to A the client would keep serving B from its cache
     * under the URL of A without ever re-validating.
     */
    public function test_serve_does_not_pin_mismatched_revision(): void {
        $this->resetAfterTest();

        // The page was rendered while A was configured, but the admin has switched to B in the meantime.
        set_config('customcss', self::CSS_B, 'mod_hvp');
        $response = $this->serve(sha1(self::CSS_A));

        $this->assertStringNotContainsString('immutable', $response->getHeaderLine('Cache-Control'));
        $this->assertSame(self::CSS_B, (string) $response->getBody());

        // After switching back to A, the very same URL is requested again and must now serve A.
        set_config('customcss', self::CSS_A, 'mod_hvp');
        $response = $this->serve(sha1(self::CSS_A));

        $this->assertStringContainsString('immutable', $response->getHeaderLine('Cache-Control'));
        $this->assertSame(self::CSS_A, (string) $response->getBody());
    }
}
