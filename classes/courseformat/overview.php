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

namespace mod_hvp\courseformat;

use core\output\action_link;
use core\output\local\properties\button;
use core\output\local\properties\text_align;
use core\url;
use core_courseformat\local\overview\overviewitem;
use mod_hvp\manager;

/**
 * H5P overview integration (for Moodle 5.1+)
 *
 * @package   mod_hvp
 * @copyright 2025 Luca Bösch <luca.boesch@bfh.ch>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class overview extends \core_courseformat\activityoverviewbase {
    /**
     * @var manager the ratingallocate manager.
     */
    private manager $manager;

    /**
     * Constructor.
     *
     * @param \cm_info $cm the course module instance.
     * @param \core\output\renderer_helper $rendererhelper the renderer helper.
     */
    public function __construct(
        \cm_info $cm,
        /** @var \core\output\renderer_helper $rendererhelper the renderer helper */
        protected readonly \core\output\renderer_helper $rendererhelper,
    ) {
        parent::__construct($cm);
        $this->manager = manager::create_from_coursemodule($cm);
    }

    #[\Override]
    public function get_actions_overview(): ?overviewitem {
        $url = new url(
            '/mod/hvp/view.php',
            ['id' => $this->cm->id],
        );

        if (
            class_exists(button::class) &&
            (new \ReflectionClass(button::class))->hasConstant('BODY_OUTLINE')
        ) {
            $bodyoutline = button::BODY_OUTLINE;
            $buttonclass = $bodyoutline->classes();
        } else {
            $buttonclass = "btn btn-outline-secondary";
        }

        $text = get_string('view');
        $content = new action_link(
            url: $url,
            text: $text,
            attributes: ['class' => $buttonclass],
        );

        return new overviewitem(
            name: get_string('actions'),
            value: $text,
            content: $content,
            textalign: text_align::CENTER,
        );
    }

    #[\Override]
    public function get_extra_overview_items(): array {
        return [
            'hvptype' => $this->get_extra_hvp_type(),
        ];
    }

    private function get_extra_hvp_type(): ?overviewitem {
        $hvptype = $this->manager->get_hvp_type();
        if ($hvptype === null) {
            return null;
        }

        return new overviewitem(
            name: get_string('contenttype', 'hvp'),
            value: $hvptype,
            content: $hvptype,
            textalign: text_align::CENTER,
        );
    }
}
