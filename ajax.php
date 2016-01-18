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
 * Responsible for handling AJAX requests related to H5P.
 *
 * @package    mod_hvp
 * @copyright  2016 Joubel AS <contact@joubel.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('AJAX_SCRIPT', true);
require(__DIR__ . '/../../config.php');

$action = required_param('action', PARAM_ALPHA);
switch($action) {
    case 'contentsuserdata':
        \mod_hvp\content_user_data::handle_ajax();
        break;

    case 'restrictlibrary':
        require_once ($CFG->dirroot . '/mod/hvp/locallib.php');

        // TODO - check permissions
        $library_id = required_param('library_id', PARAM_INT);
        $restrict = required_param('restrict', PARAM_INT);
        $token = required_param('token', PARAM_ALPHANUMEXT);

        if (hvp_verify_token('library_' . $library_id, $token)) {
            hvp_restrict_library($library_id, $restrict);
            // TODO - need to check access - using e.g. tokens  + permissions!
            echo json_encode(array(
                'url' => (new moodle_url('/mod/hvp/ajax.php', array(
                    'action' => 'restrict_library',
                    'token' => hvp_get_token('library_' . $library_id),
                    'restrict' => ($restrict === '1' ? 0 : 1),
                    'library_id' => $library_id
                )))->out(false)));
            die;
        }
        else {
            http_response_code(403);
        }

    case 'setFinished':
        \mod_hvp\user_grades::handle_ajax();
        break;

    default:
        throw new coding_exception('Unhandled AJAX');
        break;
}
