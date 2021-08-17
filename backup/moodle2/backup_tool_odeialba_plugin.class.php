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
 * File to handle the backup of the plugin
 *
 * @package    tool_odeialba
 * @copyright  2021 Odei Alba
 * @author     Odei Alba <odeialba@odeialba.com>
 * @link       https://www.odeialba.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/backup/moodle2/backup_tool_plugin.class.php');

/**
 * Class to handle the backup of the plugin.
 */
class backup_tool_odeialba_plugin extends backup_tool_plugin {
    /**
     * Function to define everything that needs to be backed up
     *
     * @return backup_plugin_element
     * @throws base_element_struct_exception
     */
    protected function define_course_plugin_structure() {
        $tabledata = new backup_nested_element(
            'tool_odeialba',
            ['id'],
            [
                'courseid',
                'name',
                'completed',
                'priority',
                'timecreated',
                'timemodified',
                'description',
                'descriptionformat'
            ]
        );

        $tabledata->set_source_table('tool_odeialba', ['courseid' => backup::VAR_COURSEID]);
        $tabledata->annotate_files('tool_odeialba', 'file', null);

        $plugin = $this->get_plugin_element();
        $plugin->add_child($tabledata);

        return $plugin;
    }

}
