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
 * Database upgrade for my first plugin
 *
 * @package   tool_odeialba
 * @copyright 2021, Odei Alba <odeialba@odeialba.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Function to upgrade my plugin
 *
 * @param int $oldversion
 * @return bool result
 * @throws ddl_change_structure_exception
 * @throws ddl_exception
 * @throws ddl_table_missing_exception
 * @throws downgrade_exception
 * @throws upgrade_exception
 */
function xmldb_tool_odeialba_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2021071601) {

        // Define table tool_odeialba to be created.
        $table = new xmldb_table('tool_odeialba');

        // Adding fields to table tool_odeialba.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('completed', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('priority', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '1');
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        // Adding keys to table tool_odeialba.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);

        // Conditionally launch create table for tool_odeialba.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Odeialba savepoint reached.
        upgrade_plugin_savepoint(true, 2021071601, 'tool', 'odeialba');
    }

    if ($oldversion < 2021071603) {

        // Define index index_courseid_name (unique) to be added to tool_odeialba.
        $table = new xmldb_table('tool_odeialba');
        $key = new xmldb_key('fk_courseid_course_id', XMLDB_KEY_FOREIGN, ['courseid'], 'course', ['id']);
        $index = new xmldb_index('index_courseid_name', XMLDB_INDEX_UNIQUE, ['courseid', 'name']);

        // Launch add key fk_courseid_course_id.
        $dbman->add_key($table, $key);

        // Conditionally launch add index index_courseid_name.
        if (!$dbman->index_exists($table, $index)) {
            $dbman->add_index($table, $index);
        }

        // Odeialba savepoint reached.
        upgrade_plugin_savepoint(true, 2021071603, 'tool', 'odeialba');
    }

    if ($oldversion < 2021080300) {
        // Define field description to be added to tool_odeialba.
        $table = new xmldb_table('tool_odeialba');
        $field = new xmldb_field('description', XMLDB_TYPE_TEXT, null, null, null, null, null, 'timemodified');
        $fieldformat = new xmldb_field('descriptionformat', XMLDB_TYPE_INTEGER, '10', null, null, null, null, 'description');

        // Conditionally launch add field description.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Conditionally launch add field descriptionformat.
        if (!$dbman->field_exists($table, $fieldformat)) {
            $dbman->add_field($table, $fieldformat);
        }

        // Odeialba savepoint reached.
        upgrade_plugin_savepoint(true, 2021080300, 'tool', 'odeialba');
    }

    return true;
}
