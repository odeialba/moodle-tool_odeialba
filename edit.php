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
 * Edit form for my plugin
 *
 * @package   tool_odeialba
 * @copyright 2021, Odei Alba <odeialba@odeialba.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use tool_odeialba\tool_odeialba_form;
use tool_odeialba\tool_odeialba_manager;

require_once(__DIR__ . '/../../../config.php');

$id = optional_param('id', 0, PARAM_INT);
$courseid = $record = null;

if ($id === 0) {
    $courseid = required_param('courseid', PARAM_INT);
    $url = tool_odeialba_manager::get_insert_url_by_courseid($courseid);
} else {
    $record = tool_odeialba_manager::get_record_by_id($id);
    $courseid = $record->courseid;
    $url = tool_odeialba_manager::get_edit_url_by_id($id);
}

require_login($courseid);

$indexurl = tool_odeialba_manager::get_index_url_by_courseid($courseid);
$context = context_course::instance($courseid);
require_capability('tool/odeialba:edit', $context);

$title = get_string('pluginname', 'tool_odeialba');
$heading = get_string('pluginheadingform', 'tool_odeialba');

$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->set_pagelayout('report');
$PAGE->set_heading($title);
$PAGE->set_title($heading);

$myform = new tool_odeialba_form();

if ($record !== null) {
    $descriptionoptions = [
            'trusttext' => true,
            'subdirs' => true,
            'maxfiles' => -1,
            'maxbytes' => 0,
            'context' => $context
    ];
    $record = file_prepare_standard_editor(
            $record,
            'description',
            $descriptionoptions,
            $context,
            'tool_odeialba',
            'file',
            $record->id
    );

    $myform->set_data($record);
}

$errors = [];
if ($myform->is_cancelled()) {
    redirect($indexurl);
} else if ($myform->is_submitted()) {
    $errors = $myform->validation((array) $myform->get_submitted_data(), []);
}

if (count($errors) === 0) {
    $formdata = $myform->get_data();
    if ($formdata) {
        tool_odeialba_manager::save_record($id, $formdata, $context);

        redirect($indexurl);
    }
}

echo $OUTPUT->header();
echo $OUTPUT->heading($heading);

foreach ($errors as $error) {
    echo html_writer::div(get_string($error, 'tool_odeialba'));
}

$myform->set_data($myform);
$myform->display();

echo $OUTPUT->footer();
