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
 * The mod_hvp file storage
 *
 * @package    mod_hvp
 * @copyright  2016 Joubel AS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_hvp;
defined('MOODLE_INTERNAL') || die();

/**
 * The mod_hvp file storage class.
 *
 * @package    mod_hvp
 * @since      Moodle 2.7
 * @copyright  2016 Joubel AS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class results {

    // Type specific inputs
    protected $content_id;

    // Generic result inputs
    protected $offset, $limit, $orderBy, $orderDir, $filters;

    /**
     * Start handling results by filtering input parameters.
     */
    function __construct() {
        $this->filter_input();
    }

    /**
     * Filter and load input parameters
     *
     * @throws \coding_exception
     */
    protected function filter_input() {
        // Type specifc
        $this->content_id = optional_param('content_id', 0, PARAM_INT);

        // Used to handle pagination
        $this->offset = optional_param('offset', 0, PARAM_INT);

        // Max number of items to display on one page
        $this->limit = optional_param('limit', 20, PARAM_INT);
        if ($this->limit > 100) {
            // Avoid wrong usage
            throw new \coding_exception('limit to high');
        }

        // Field to order by
        $this->orderBy = optional_param('sortBy', 0, PARAM_INT);

        // Direction to order in
        $this->orderDir = optional_param('sortDir', 0, PARAM_INT);

        // List of fields to filter results on
        $this->filters = optional_param_array('filters', array(), PARAM_RAW_TRIMMED);
    }

    /**
     * Print results data
     */
    public function print_results() {
        $results = $this->get_results();

        // Make data readable for humans
        $rows = array();
        foreach ($results as $result)  {
            $rows[] = array(
                "{$result->firstname} {$result->lastname}",
                (int) $result->rawgrade,
                (int) $result->rawgrademax,
                userdate($result->timemodified)
            );
        }

        // Print
        header('Cache-Control: no-cache');
        header('Content-type: application/json');
        print json_encode(array(
            'num' => $this->get_results_num(),
            'rows' => $rows
        ));
    }

    /**
     * Builds the SQL query required to retrieve results for the given
     * interactive content.
     *
     * @throws \coding_exception
     * @return array
     */
    protected function get_results() {
        // Add extra fields, joins and where for the different result lists
        if ($this->content_id !== 0) {
            list($fields, $join, $where, $order, $args) = $this->get_content_sql();
        }
        else {
            throw new \coding_exception('missing content_id');
        }

        // Build where statement
        $where[] = "i.itemtype = 'mod'";
        $where[] = "i.itemmodule = 'hvp'";
        $where = 'WHERE ' . implode(' AND ', $where);

        // Order results by the select column and direction
        $order[] = 'g.rawgrade';
        $order[] = 'g.rawgrademax';
        $order[] = 'g.timemodified';
        $order_by = $this->get_order_sql($order);

        // Get from statement
        $from = $this->get_from_sql();

        // Execute query and get results
        return $this->get_sql_results("
                SELECT {$fields}
                       g.rawgrade,
                       g.rawgrademax,
                       g.timemodified
                  {$from}
                  {$join}
                  {$where}
                  {$order_by}
                  LIMIT {$this->offset}, {$this->limit}
                ", $args);
    }

    /**
     * Build and execute the query needed to tell the number of total results.
     * This is used to create pagination.
     *
     * @return int
     */
    protected function get_results_num() {
        global $DB;

        list($fields, $join, $where, $order, $args) = $this->get_content_sql();
        $where[] = "i.itemtype = 'mod'";
        $where[] = "i.itemmodule = 'hvp'";
        $where = 'WHERE ' . implode(' AND ', $where);
        $from = $this->get_from_sql();

        return (int) $DB->get_field_sql("SELECT COUNT(i.id) {$from} {$where}", $args);
    }

    /**
     * Builds the order part of the SQL query.
     *
     * @param array $fields Fields allowed to order by
     * @throws \coding_exception
     * @return string
     */
    protected function get_order_sql($fields) {
        // Make sure selected order field is valid
        if (!isset($fields[$this->orderBy])) {
            throw new \coding_exception('invalid order field');
        }

        // Find selected sortable field
        $field = $fields[$this->orderBy];

        if (is_object($field)) {
            // Some fields are reverse sorted by default, e.g. text fields.
            // This feels more natural for the humans.
            if (!empty($field->reverse)) {
                $this->orderDir = !$this->orderDir;
            }

            $field = $field->name;
        }

        $dir = ($this->orderDir ? 'ASC' : 'DESC');
        if ($field === 'u.firstname') {
            // Order by extra field for name
            $field .= " {$dir}, u.lastname";
        }
        return "ORDER BY {$field} {$dir}";
    }

    /**
     * Get from part of the SQL query.
     *
     * @return string
     */
    protected function get_from_sql() {
        return " FROM {grade_items} i JOIN {grade_grades} g ON i.id = g.itemid";
    }

    /**
     * Get the different parts needed to create the SQL for getting results
     * belonging to a specifc content.
     * (An alternative to this could be getting all the results for a
     * specified user.)
     *
     * @return array $fields, $join, $where, $order, $args
     */
    protected function get_content_sql() {
        global $DB;

        $fields = " u.id AS user_id, u.firstname, u.lastname,";
        $join = " LEFT JOIN {user} u ON u.id = g.userid";
        $where = array("i.iteminstance = ?");
        $args = array($this->content_id);
        if (isset($this->filters[0])) {
            $where[] = '(' . $DB->sql_like('u.firstname', '?', false) . ' OR ' .
                             $DB->sql_like('u.lastname', '?', false) . ')';
            $args[] = '%' . $this->filters[0] . '%';
            $args[] = '%' . $this->filters[0] . '%';
        }
        $order = array((object) array(
            'name' => 'u.firstname',
            'reverse' => TRUE
        ));

        return array($fields, $join, $where, $order, $args);
    }

    /**
     * Execute given query and return any results
     *
     * @param string $query
     * @param array $args Used for placeholders
     * @return array
     */
    protected function get_sql_results($query, $args) {
        global $DB;
        return $DB->get_records_sql($query, $args);
    }
}
