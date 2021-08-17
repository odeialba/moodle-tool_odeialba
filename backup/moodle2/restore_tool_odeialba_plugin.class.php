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
 * File to handle the restore of the plugin.
 *
 * @package    tool_odeialba
 * @copyright  2021 Odei Alba
 * @author     Odei Alba <odeialba@odeialba.com>
 * @link       https://www.odeialba.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/backup/moodle2/restore_tool_plugin.class.php');

/**
 * Class to handle the restore of the plugin.
 */
class restore_tool_odeialba_plugin extends restore_tool_plugin {
    /**
     * Declares the path for the restore of the plugin element.
     *
     * @return array
     */
    protected function define_course_plugin_structure() {
        $paths = [];

        $paths[] = new restore_path_element('tool_odeialba', '/course/tool_odeialba');

        return $paths;
    }

    /**
     * Processes the restore of the plugin data.
     *
     * @param array|stdClass $data
     */
    protected function process_tool_odeialba($data) {
        global $DB;

        $data = (object) $data;

        $oldid = $data->id;
        $data->courseid = $this->task->get_courseid();

        $data->id = $DB->insert_record('tool_odeialba', $data);

        $this->set_mapping('tool_odeialba', $oldid, $data->id, true);
    }

    /**
     *  Processes the restore of the files related to the plugin.
     */
    protected function after_restore_course() {
        $this->add_related_files('tool_odeialba', 'file', 'tool_odeialba');
    }

}
