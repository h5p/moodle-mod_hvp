<?php

/**
 * The mod_hvp content user data.
 *
 * @package    mod_hvp
 * @since      Moodle 2.7
 * @copyright  2017 Joubel AS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_hvp;

/**
 * Class xapi_result handles xapi results and corresponding db operations.
 *
 * @package mod_hvp
 */
class xapi_result {

    /**
     * Handle xapi results endpoint
     */
    public static function handle_ajax() {

        // Validate token
        if (!self::validate_token()) {
            $core = framework::instance();
            \H5PCore::ajaxError($core->h5pF->t('Invalid security token.'),
                'INVALID_TOKEN');
            return;
        }

        $contentId = required_param('contentId', PARAM_INT);
        $xAPIResult = required_param('xAPIResult', PARAM_RAW);

        $xAPIJson = json_decode($xAPIResult);
        if (!$xAPIJson) {
            \H5PCore::ajaxError('Invalid json in xAPI data.');
            return;
        }

        if (!self::validate_xAPI_data($xAPIJson)) {
            \H5PCore::ajaxError('Invalid xAPI data.');
            return;
        }

        // Delete any old results
        self::remove_xAPI_data($contentId);

        // Store results
        self::store_xAPI_data($contentId, $xAPIJson);

        // Successfully inserted xAPI result
        \H5PCore::ajaxSuccess();
    }

    /**
     * Validate xAPI results token
     *
     * @return bool True if token was valid
     */
    private static function validate_token() {
        $token = required_param('token', PARAM_ALPHANUM);
        return \H5PCore::validToken('xapiresult', $token);

    }

    /**
     * Validate xAPI data
     *
     * @param object $xAPIData xAPI data
     *
     * @return bool True if valid data
     */
    private static function validate_xAPI_data($xAPIData) {
        $xAPIData = new \H5PReportXAPIData($xAPIData);
        return $xAPIData->validateData();
    }

    /**
     * Store xAPI result(s)
     *
     * @param int $contentId Content id
     * @param object $xAPIData xAPI data
     * @param int $parentId Parent id
     */
    private static function store_xAPI_data($contentId, $xAPIData, $parentId=NULL) {
        global $DB, $USER;

        $xAPIData = new \H5PReportXAPIData($xAPIData, $parentId);
        $insertedId = $DB->insert_record('hvp_xapi_results', (object) array(
            'content_id' => $contentId,
            'user_id' => $USER->id,
            'parent_id' => $xAPIData->getParentID(),
            'interaction_type' => $xAPIData->getInteractionType(),
            'description' => $xAPIData->getDescription(),
            'correct_responses_pattern' => $xAPIData->getCorrectResponsesPattern(),
            'response' => $xAPIData->getResponse(),
            'additionals' => $xAPIData->getAdditionals()
        ));

        // Save sub content statements data
        if ($xAPIData->isCompound()) {
            foreach ($xAPIData->getChildren($contentId) as $child) {
                self::store_xAPI_data($contentId, $child, $insertedId);
            }
        }
    }

    /**
     * Remove xAPI result(s)
     *
     * @param int $contentId Content id
     */
    private static function remove_xAPI_data($contentId) {
        global $DB, $USER;

        $DB->delete_records('hvp_xapi_results', array(
            'content_id' => $contentId,
            'user_id' => $USER->id
        ));
    }
}
