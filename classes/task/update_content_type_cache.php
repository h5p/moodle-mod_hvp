<?php
/**
 * Updates the content type cache
 *
 * @package    mod_hvp
 * @copyright  2017 Joubel AS <contact@joubel.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_hvp\task;

defined('MOODLE_INTERNAL') || die();

/**
 * Updates content type cache
 *
 * @package    mod_hvp
 * @copyright  2017 Joubel AS <contact@joubel.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class update_content_type_cache extends \core\task\scheduled_task {
    public function get_name() {
        return get_string('ctcachetaskname', 'mod_hvp');
    }

    public function execute() {
        global $DB;
        // Update content type cache
        require_once(__DIR__ . '/../../locallib.php');
        \hvp_update_content_type_cache();
    }
}
