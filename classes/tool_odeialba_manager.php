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
 * Class for all the actions related to the table of the plugin
 *
 * @package   tool_odeialba
 * @copyright 2021, Odei Alba <odeialba@odeialba.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_odeialba;

use context_course;
use moodle_url;
use stdClass;

defined('MOODLE_INTERNAL') || die();

/**
 * Class tool_odeialba_manager for all the actions in the plugin
 *
 * @package tool_odeialba
 */
class tool_odeialba_manager {
    /**
     * Insert or update record with given data
     *
     * @param int $id
     * @param stdClass $formdata
     * @param context_course $context
     * @return int
     */
    public static function save_record(int $id, stdClass $formdata, context_course $context): int {
        if ($id === 0) {
            return self::insert_record(
                    $formdata,
                    $context
            );
        }

        self::update_record(
                $id,
                $formdata,
                $context
        );

        return $id;
    }

    /**
     * Insert new record with given data
     *
     * @param stdClass $formdata
     * @param context_course $context
     * @return int
     */
    public static function insert_record(stdClass $formdata, context_course $context): int {
        global $DB;

        $formdata->id = $DB->insert_record('tool_odeialba', [
                'courseid' => (int) $formdata->courseid,
                'name' => $formdata->name,
                'completed' => isset($formdata->completed) ? (int) $formdata->completed : 0,
                'timecreated' => time(),
        ]);

        self::update_description($formdata->id, $formdata, $context);

        return $formdata->id;
    }

    /**
     * Update record with given id
     *
     * @param int $id
     * @param stdClass $formdata
     * @param context_course $context
     * @return bool
     */
    public static function update_record(int $id, stdClass $formdata, context_course $context): bool {
        global $DB;

        self::update_description($id, $formdata, $context);

        $params = [
                'id' => $id,
                'name' => $formdata->name,
                'completed' => isset($formdata->completed) ? (int) $formdata->completed : 0,
                'timemodified' => time(),
        ];

        return $DB->update_record('tool_odeialba', (object) $params);
    }

    /**
     * Update record with given id
     *
     * @param int $id
     * @param stdClass $formdata
     * @param context_course $context
     * @return bool
     */
    public static function update_description(int $id, stdClass $formdata, context_course $context): bool {
        if (! isset($formdata->description_editor)) {
            return false;
        }

        global $DB;

        $descriptionoptions = [
                'trusttext' => true,
                'subdirs' => true,
                'maxfiles' => -1,
                'maxbytes' => 0,
                'context' => $context
        ];

        $formdata = file_postupdate_standard_editor(
                $formdata,
                'description',
                $descriptionoptions,
                $context,
                'tool_odeialba',
                'file',
                $id
        );

        $params = [
                'id' => $id,
                'description' => $formdata->description,
                'descriptionformat' => (int) $formdata->descriptionformat,
        ];

        return $DB->update_record('tool_odeialba', (object) $params);
    }

    /**
     * Get record by given id. Return null if none found.
     *
     * @param int $id
     * @return stdClass|null
     */
    public static function get_record_by_id(int $id): ?stdClass {
        global $DB;
        return  $DB->get_record('tool_odeialba', ['id' => $id]) ?: null;
    }

    /**
     * Delete given record
     *
     * @param int $id
     * @return bool
     */
    public static function delete_record_by_id(int $id): bool {
        global $DB;
        return $DB->delete_records('tool_odeialba', ['id' => $id]);
    }

    /**
     * Return the index url for given course
     *
     * @param int $courseid
     * @return moodle_url
     */
    public static function get_index_url_by_courseid(int $courseid): moodle_url {
         return new moodle_url('/admin/tool/odeialba/index.php', ['id' => $courseid]);
    }

    /**
     * Return the insert url for given course
     *
     * @param int $courseid
     * @return moodle_url
     */
    public static function get_insert_url_by_courseid(int $courseid): moodle_url {
         return new moodle_url('/admin/tool/odeialba/edit.php', ['courseid' => $courseid]);
    }

    /**
     * Return the edit url for given record
     *
     * @param int $id
     * @return moodle_url
     */
    public static function get_edit_url_by_id(int $id): moodle_url {
         return new moodle_url('/admin/tool/odeialba/edit.php', ['id' => $id]);
    }

    /**
     * Return the delete url for given record
     *
     * @param int $id
     * @return moodle_url
     */
    public static function get_delete_url_by_id(int $id): moodle_url {
         return new moodle_url('/admin/tool/odeialba/delete.php', ['id' => $id, 'sesskey' => sesskey()]);
    }
}
