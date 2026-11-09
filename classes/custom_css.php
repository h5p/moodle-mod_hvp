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
 * Provides admin-configured custom CSS applied to H5P content.
 *
 * @package    mod_hvp
 * @copyright  2026 ISB Bayern
 * @author     Philipp Memmel
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_hvp;

/**
 * Provides admin-configured custom CSS applied to H5P content.
 *
 * The stylesheet is added by {@see \mod_hvp_renderer::hvp_alter_styles()} regardless of the
 * embed type of the content: for 'div' embeds it is loaded directly on the host Moodle page,
 * for 'iframe' embeds it is loaded inside the iframe. This mirrors how core_h5p's own
 * 'h5pcustomcss' setting behaves (see \core_h5p\output\renderer::h5p_alter_styles()), so a
 * single setting reliably styles H5P content no matter how a given content type chooses to
 * embed itself.
 *
 * @package    mod_hvp
 * @copyright  2026 ISB Bayern
 * @author     Philipp Memmel
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class custom_css {
    /**
     * Read the configured custom CSS.
     *
     * @return string CSS to apply to H5P content, empty string if none configured
     */
    public function get_css(): string {
        $css = get_config('mod_hvp', 'customcss');
        return empty($css) ? '' : $css;
    }

    /**
     * Returns a content hash of the configured CSS.
     *
     * Used as the cache-busting version appended to the stylesheet URL (see
     * {@see \mod_hvp_renderer::hvp_alter_styles()}): whenever the CSS content changes, the
     * hash - and therefore the URL - changes too, which safely busts any client-side cache.
     * The controller serving that URL (see
     * {@see \mod_hvp\route\controller\custom_css_controller}) compares the requested
     * version against this hash before allowing a long-lived, immutable cache entry, so that
     * a URL is never pinned to a body that belongs to a different revision.
     *
     * @return string content hash of the configured CSS
     */
    public function get_hash(): string {
        return sha1($this->get_css());
    }
}
