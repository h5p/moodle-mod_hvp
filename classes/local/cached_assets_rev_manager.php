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

namespace mod_hvp\local;

use core\clock;

/**
 * Helper to manage the global cached-assets revision.
 *
 * @package    mod_hvp
 * @copyright  2026 ISB Bayern
 * @author     Philipp Memmel
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class cached_assets_rev_manager {
    /**
     * Constructor.
     */
    public function __construct(
        /** @var clock $clock default clock implementation. */
        private readonly clock $clock
    ) {
    }

    /**
     * Invalidate the global cached-assets revision used for cache busting.
     */
    public function invalidate_global_cached_assets_rev(): void {
        set_config('cachedassetsrev', $this->clock->now()->getTimestamp(), 'mod_hvp');
    }

    /**
     * Get the global cached-assets revision used for cache busting.
     *
     * @return int
     */
    public function get_global_cached_assets_rev(): int {
        $rev = (int) get_config('mod_hvp', 'cachedassetsrev');
        if ($rev <= 0) {
            $this->invalidate_global_cached_assets_rev();
            $rev = (int) get_config('mod_hvp', 'cachedassetsrev');
        }

        return $rev;
    }

    /**
     * Get query string for cached assets cache busting.
     *
     * @return string
     */
    public function get_global_cached_assets_buster(): string {
        return '?rev=' . $this->get_global_cached_assets_rev();
    }
}
