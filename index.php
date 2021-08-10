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
 * Main index file for my plugin
 *
 * @package   tool_odeialba
 * @copyright 2021, Odei Alba <odeialba@odeialba.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use tool_odeialba\event\record_viewed;
use tool_odeialba\output\records_table;
use tool_odeialba\tool_odeialba_manager;

require_once(__DIR__ . '/../../../config.php');

$courseid = required_param('id', PARAM_INT);

require_login($courseid);

$context = context_course::instance($courseid);
require_capability('tool/odeialba:view', $context);

$url = tool_odeialba_manager::get_index_url_by_courseid($courseid);
$title = get_string('pluginname', 'tool_odeialba');
$heading = get_string('pluginheading', 'tool_odeialba');

$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->set_pagelayout('report');
$PAGE->set_heading($title);
$PAGE->set_title($heading);

$event = record_viewed::create(['context' => $context, 'courseid' => $courseid]);

$outputpage = new records_table();
$output = $PAGE->get_renderer('tool_odeialba');
echo $output->header();
echo $output->render($outputpage);
echo $output->footer();

$event->trigger();
