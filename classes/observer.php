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
 * Observer to course content
 *
 * @package    tool_odeialba
 * @copyright  2021 Odei Alba
 * @author     Odei Alba <odeialba@odeialba.com>
 * @link       https://www.odeialba.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

use core\event\course_content_deleted;
use tool_odeialba\tool_odeialba_manager;

/**
 * Event observer for course content.
 */
class tool_odeialba_observer {

    /**
     * Triggered when 'course_content_deleted' event is triggered.
     *
     * @param course_content_deleted $event
     */
    public static function course_content_deleted(course_content_deleted $event) {
        $courseid = (int) $event->courseid;

        tool_odeialba_manager::delete_records_by_course_id($courseid);
    }
}

