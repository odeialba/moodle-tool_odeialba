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
 * Web service to return a table with records.
 *
 * @package    tool_odeialba
 * @copyright  2021 Odei Alba
 * @author     Odei Alba <odeialba@odeialba.com>
 * @link       https://www.odeialba.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_odeialba\external;

use context_course;
use external_api;
use external_function_parameters;
use external_single_structure;
use external_value;
use invalid_parameter_exception;
use tool_odeialba\output\records_table;
use tool_odeialba\tool_odeialba_manager;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . "/externallib.php");

/**
 * Class for web service to show table with records.
 */
class display_table extends external_api {

    /**
     * Returns description of method parameters
     * execute_parameters() which describes the parameters of the functions
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters(
            [
                'courseid' => new external_value(PARAM_INT, 'ID of the record to delete')
            ]
        );
    }

    /**
     * Return a table with records of given course.
     *
     * @param int $courseid
     * @return array
     * @throws \dml_exception
     * @throws \required_capability_exception
     * @throws \restricted_context_exception
     * @throws invalid_parameter_exception
     */
    public static function execute(int $courseid) {
        global $PAGE;
        $params = self::validate_parameters(self::execute_parameters(), ['courseid' => $courseid]);
        $courseid = (int) $params['courseid'];
        $course = get_course($courseid);

        if ($course === null) {
            throw new invalid_parameter_exception('Course with given id does not exist.');
        }

        $context = context_course::instance($courseid);
        self::validate_context($context);
        require_capability('tool/odeialba:view', $context);

        $outputpage = new records_table();
        $output = $PAGE->get_renderer('tool_odeialba');
        $templatevalues = $outputpage->export_for_template($output);

        return ['success' => true, 'htmltable' => $templatevalues->table];
    }

    /**
     * Returns description of method result value
     * execute_returns() which describes the return value
     * @return external_single_structure
     */
    public static function execute_returns() {
        return new external_single_structure(
            [
                'success' => new external_value(PARAM_BOOL, 'Success or fail of deletion of record.'),
                'htmltable' => new external_value(PARAM_RAW, 'HTML table.')
            ]
        );
    }



}
