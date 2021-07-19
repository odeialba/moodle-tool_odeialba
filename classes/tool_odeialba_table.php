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
    /** @var string Name for the table */
    private const TABLENAME = '{tool_odeialba}';

    /**
     * tool_odeialba_table constructor.
     *
     * @param string $baseurl
     */
    public function __construct(string $baseurl) {
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
        $this->define_baseurl($baseurl);
        $this->sortable(true, 'id', SORT_ASC);
    }

    /**
     * Function to get value of column name
     *
     * @param \stdClass $row
     * @return string
     */
    public function col_name(\stdClass $row): string {
        return format_string($row->name);
    }

    /**
     * Function to get value of column completed
     *
     * @param \stdClass $row
     * @return string
     * @throws \coding_exception
     */
    public function col_completed(\stdClass $row): string {
        return $row->completed ? get_string('yes') : get_string('no');
    }

    /**
     * Function to get value of column timecreated
     *
     * @param \stdClass $row
     * @return string
     */
    public function col_timecreated(\stdClass $row): string {
        return userdate($row->timecreated);
    }

    /**
     * Function to get value of column timemodified
     *
     * @param \stdClass $row
     * @return string
     */
    public function col_timemodified(\stdClass $row): string {
        return $row->timemodified !== null ? userdate($row->timemodified) : '';
    }

    /**
     * Function to call set_count_sql dynamically
     *
     * @param string $where
     * @param array $params
     */
    private function create_and_set_count_sql(string $where, array $params = []): void {
        $this->set_count_sql("SELECT COUNT(*) FROM " . self::TABLENAME . " WHERE " . $where, $params);
    }

    /**
     * Function to get all values of the table
     *
     * @param array|string[] $columns
     * @param string $where
     */
    public function get_all_values(array $columns = ['*'], string $where = '1 = 1'): void {
        $this->set_sql(implode(', ', $columns), self::TABLENAME, $where);
        $this->create_and_set_count_sql("1=1");
    }

    /**
     * Function to get value using id
     *
     * @param int $id
     * @param array|string[] $columns
     */
    public function get_by_id(int $id, array $columns = ['*']): void {
        $this->set_sql(implode(', ', $columns), self::TABLENAME, 'id = ?', [$id]);
        $this->create_and_set_count_sql("id = ?", [$id]);
    }
}
