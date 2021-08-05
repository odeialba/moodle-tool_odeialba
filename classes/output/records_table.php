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
 * tool_odeialba prepare data for renderer.
 *
 * @package    tool_odeialba
 * @copyright  2021 Odei Alba
 * @author     Odei Alba <odeialba@odeialba.com>
 * @link       https://www.odeialba.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_odeialba\output;

use context_course;
use html_writer;
use renderable;
use renderer_base;
use stdClass;
use templatable;
use tool_odeialba\tool_odeialba_manager;
use tool_odeialba\tool_odeialba_table;

defined('MOODLE_INTERNAL') || die();

/**
 * Class to prepare data for index renderer
 */
class records_table implements renderable, templatable {
    /**
     * Export the page data for the mustache template.
     *
     * @param renderer_base $output
     * @return stdClass
     */
    public function export_for_template(renderer_base $output) {
        global $COURSE;
        $context = context_course::instance($COURSE->id);
        $data = new stdClass();

        ob_start();
        $mytable = new tool_odeialba_table();
        $mytable->get_by_courseid($COURSE->id);
        $mytable->out(100, false);
        $table = ob_get_clean();

        if (has_capability('tool/odeialba:edit', $context)) {
            $inserturl = tool_odeialba_manager::get_insert_url_by_courseid($COURSE->id);
            $data->addnewlink = html_writer::link($inserturl, get_string('newrow', 'tool_odeialba'));
        }

        $data->header = $output->heading(get_string('currentcoursename', 'tool_odeialba', $COURSE->fullname), 2);
        $data->table = $table;

        return $data;
    }
}
