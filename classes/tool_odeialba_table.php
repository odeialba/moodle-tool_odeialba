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

use context_course;

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
     */
    public function __construct() {
        parent::__construct('tool_odeialba');
        global $PAGE;

        $columns = [
                'id' => get_string('id', 'tool_odeialba'),
                'courseid' => get_string('courseid', 'tool_odeialba'),
                'name' => get_string('name', 'tool_odeialba'),
                'completed' => get_string('completed', 'tool_odeialba'),
                'priority' => get_string('priority', 'tool_odeialba'),
                'timecreated' => get_string('timecreated', 'tool_odeialba'),
                'timemodified' => get_string('timemodified', 'tool_odeialba'),
                'description' => get_string('description', 'tool_odeialba'),
        ];

        if (has_capability('tool/odeialba:edit', context_course::instance($PAGE->course->id))) {
            $columns['actions'] = get_string('actions');
        }

        $this->define_columns(array_keys($columns));
        $this->define_headers(array_values($columns));
        $this->define_baseurl($PAGE->url);
        $this->sortable(true, 'id', SORT_ASC);
        $this->set_attribute('id', 'tool_odeialba_table');
    }

    /**
     * Function to get value of column name
     *
     * @param \stdClass $row
     * @return string
     */
    protected function col_name(\stdClass $row): string {
        return format_string($row->name);
    }

    /**
     * Function to get value of column completed
     *
     * @param \stdClass $row
     * @return string
     * @throws \coding_exception
     */
    protected function col_completed(\stdClass $row): string {
        return $row->completed ? get_string('yes') : get_string('no');
    }

    /**
     * Function to get value of column timecreated
     *
     * @param \stdClass $row
     * @return string
     */
    protected function col_timecreated(\stdClass $row): string {
        return userdate($row->timecreated);
    }

    /**
     * Function to get value of column timemodified
     *
     * @param \stdClass $row
     * @return string
     */
    protected function col_timemodified(\stdClass $row): string {
        return $row->timemodified !== null ? userdate($row->timemodified) : '';
    }

    /**
     * Function to get value of column description
     *
     * @param \stdClass $row
     * @return string
     */
    protected function col_description(\stdClass $row): string {
        $context = context_course::instance($row->courseid);
        $options = [
                'trusttext' => true,
                'noclean' => true,
        ];
        $description = file_rewrite_pluginfile_urls(
                $row->description,
                'pluginfile.php',
                $context->id,
                'tool_odeialba',
                'file',
                $row->id
        );

        return format_text($description, $row->descriptionformat, $options);
    }

    /**
     * Function to get value of column actions
     *
     * @param \stdClass $row
     * @return string
     */
    protected function col_actions(\stdClass $row): string {
        $editurl = tool_odeialba_manager::get_edit_url_by_id((int) $row->id);
        $deleteurl = tool_odeialba_manager::get_delete_url_by_id((int) $row->id);

        return \html_writer::div(
                \html_writer::link($editurl, get_string('edit'), [
                        'title' => get_string(
                                'editentrytitle',
                                'tool_odeialba',
                                format_string($row->name)
                        )
                ])
                . ' '
                . \html_writer::link(
                        $deleteurl,
                        get_string('delete'),
                        ['data-action' => 'delete', 'data-id' => (int) $row->id]
                )
        );
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

    /**
     * Function to get value using id
     *
     * @param int $id
     * @param array|string[] $columns
     */
    public function get_by_courseid(int $id, array $columns = ['*']): void {
        $this->set_sql(implode(', ', $columns), self::TABLENAME, 'courseid = ?', [$id]);
        $this->create_and_set_count_sql("courseid = ?", [$id]);
    }
}
