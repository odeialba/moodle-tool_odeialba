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
 * Web service to remove a record.
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
use tool_odeialba\tool_odeialba_manager;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . "/externallib.php");

/**
 * Class for web service to remove a record.
 */
class delete_record extends external_api {
    /**
     * Returns description of method parameters
     * execute_parameters() which describes the parameters of the functions
     * @return external_function_parameters
     */
    public static function execute_parameters() {
        return new external_function_parameters(
            [
                'id' => new external_value(PARAM_INT, 'ID of the record to delete')
            ]
        );
    }

    /**
     * Remove record with given id
     *
     * @param int $id
     * @return array
     * @throws \required_capability_exception
     * @throws \restricted_context_exception
     * @throws invalid_parameter_exception
     */
    public static function execute(int $id) {
        $params = self::validate_parameters(self::execute_parameters(), ['id' => $id]);
        $recordid = (int) $params['id'];
        $record = tool_odeialba_manager::get_record_by_id($recordid);
        $courseid = $record->courseid;

        if ($record === null) {
            throw new invalid_parameter_exception('Record with given id does not exist.');
        }

        $context = context_course::instance($record->courseid);
        self::validate_context($context);
        require_capability('tool/odeialba:edit', $context);

        $deleted = tool_odeialba_manager::delete_record_by_id($recordid);

        return ['success' => $deleted, 'courseid' => $courseid];
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
                'courseid' => new external_value(PARAM_INT, 'Course id of current course.')
            ]
        );
    }



}
