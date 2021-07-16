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

require_once(__DIR__ . '/../../../config.php');

$courseid = required_param('id', PARAM_INT);

require_login($courseid);

$url = new moodle_url('/admin/tool/odeialba/index.php', ['id' => $courseid]);
$title = get_string('pluginname', 'tool_odeialba');
$heading = get_string('pluginheading', 'tool_odeialba');

$PAGE->set_context(context_system::instance());
$PAGE->set_url($url);
$PAGE->set_pagelayout('report');
$PAGE->set_heading($heading);
$PAGE->set_title($title);

$coutusers = $DB->count_records_sql("SELECT COUNT(id) FROM {user}");
$currentcourse = $DB->get_record_sql("SELECT * FROM {course} WHERE id = ?", [$courseid]);
$allusers = $DB->get_records('user');
$userstable = new html_table();
$userstable->head = [
    'ID',
    'Username',
];

foreach ($allusers as $user) {
    $oneuser = [$user->id, $user->username];
    $userstable->data[] = $oneuser;
}

echo $OUTPUT->header();
echo $OUTPUT->heading($title);

echo html_writer::div(get_string('helloworld', 'tool_odeialba'));
echo html_writer::div(get_string('currentcourseid', 'tool_odeialba', $courseid));
echo html_writer::table($userstable);
echo html_writer::div(get_string('currentcoursename', 'tool_odeialba', $currentcourse->fullname));

echo $OUTPUT->footer();
