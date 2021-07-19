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
 * Class for queries to database
 *
 * @package   tool_odeialba
 * @copyright 2021, Odei Alba <odeialba@odeialba.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_odeialba;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/tablelib.php');

/**
 * Class tool_odeialba_table
 *
 * @package tool_odeialba
 */
class tool_odeialba_table extends \table_sql {
    /**
     * tool_odeialba_table constructor.
     */
    public function __construct() {
        parent::__construct('tool_odeialba');

        $columns = [
                'id' => get_string('id', 'tool_odeialba'),
                'courseid' => get_string('courseid', 'tool_odeialba'),
                'name' => get_string('name', 'tool_odeialba'),
                'completed' => get_string('completed', 'tool_odeialba'),
                'priority' => get_string('priority', 'tool_odeialba'),
                'timecreated' => get_string('timecreated', 'tool_odeialba'),
                'timemodified' => get_string('timemodified', 'tool_odeialba'),
        ];
        $this->define_columns(array_keys($columns));
        $this->define_headers(array_values($columns));
    }

}
