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
 * Mobile implementation for H5P.
 *
 * @package    mod_hvp
 * @copyright  2018 Joubel AS <contact@joubel.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_hvp\output;

use context_module;
use mod_hvp;

/**
 * Mobile output class for the Moodle App.
 *
 * @package   mod_hvp
 * @copyright 2018 Joubel AS <contact@joubel.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */class mobile {
    /**
     * Mobile course module view
     *
     * @param array $args Incoming app args.
     * @return array
     */
    public static function mobile_course_view($args) {
        global $DB, $CFG, $OUTPUT, $USER;

        $cmid = $args['cmid'];
        if (!$CFG->allowframembedding) {
            $context = \context_system::instance();
            if (has_capability('moodle/site:config', $context)) {
                $template = 'mod_hvp/iframe_embedding_disabled';
            } else {
                $template = 'mod_hvp/contact_site_administrator';
            }
            return [
                'templates' => [
                    [
                        'id' => 'noiframeembedding',
                        'html' => $OUTPUT->render_from_template($template, []),
                    ],
                ],
            ];
        }

        // Verify course context.
        $cm = get_coursemodule_from_id('hvp', $cmid);
        if (!$cm) {
            throw new moodle_exception('invalidcoursemodule');
        }
        $course = $DB->get_record('course', ['id' => $cm->course]);
        if (!$course) {
            throw new moodle_exception('coursemisconf');
        }
        require_course_login($course, false, $cm, true, true);
        $context = context_module::instance($cm->id);
        require_capability('mod/hvp:view', $context);

        [$token, $secret] = mod_hvp\mobile_auth::create_embed_auth_token();

        // Store secret in database.
        $auth             = $DB->get_record('hvp_auth', [
            'user_id' => $USER->id,
        ]);
        $currenttimestamp = time();
        if ($auth) {
            $DB->update_record('hvp_auth', [
                'id'         => $auth->id,
                'secret'     => $token,
                'created_at' => $currenttimestamp,
            ]);
        } else {
            $DB->insert_record('hvp_auth', [
                'user_id'    => $USER->id,
                'secret'     => $token,
                'created_at' => $currenttimestamp,
            ]);
        }

        $data = [
            'cmid'    => $cmid,
            'wwwroot' => $CFG->wwwroot,
            'user_id' => $USER->id,
            'secret'  => urlencode($secret),
        ];

        return [
            'templates'  => [
                [
                    'id'   => 'main',
                    'html' => $OUTPUT->render_from_template('mod_hvp/mobile_view_page', $data),
                ],
            ],
            'javascript' => file_get_contents($CFG->dirroot . '/mod/hvp/library/js/h5p-resizer.js'),
        ];
    }
}
