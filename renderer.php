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
 * Defines the renderer for the hvp (H5P) module.
 *
 * @package     mod_hvp
 * @copyright   2016 onward Eiz Edddin Al Katrib <eiz@barasoft.co.uk>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * The renderer for the hvp module.
 *
 * @copyright   2016 onward Eiz Edddin Al Katrib <eiz@barasoft.co.uk>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_hvp_renderer extends plugin_renderer_base {

    /**
    * Return additional asset files to the libraries.
    *
    * @param array $libraries Array of libraries indexed by the library's machineName
    * @return array
    */
    public function hvp_additional_asset_files($libraries) {
        return array (
            'styles' => $this->hvp_add_styles($libraries),
            'scripts' => $this->hvp_add_scripts($libraries)
        );
    }

    /**
    * Return additional style files to the libraries.
    *
    * @param array $libraries Array of libraries indexed by the library's machineName
    * @return array Array of objects with properties path and version.
    */
    public function hvp_add_styles($libraries) {
        return array();
    }

    /**
    * Return additional script files to the libraries.
    *
    * @param array $libraries Array of libraries indexed by the library's machineName
    * @return array Array of objects with properties path and version.
    */
    public function hvp_add_scripts($libraries) {
        return array();
    }

}