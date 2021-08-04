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
 * Library of the new plugin
 *
 * @package   tool_odeialba
 * @copyright 2021, Odei Alba <odeialba@odeialba.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Adds the plugin to the course breadcrumbs
 *
 * @param navigation_node $navigation
 * @param stdClass $course
 * @param context_course $context
 * @throws coding_exception
 * @throws moodle_exception
 */
function tool_odeialba_extend_navigation_course(navigation_node $navigation, stdClass $course, context_course $context) {
    if ($context->contextlevel === CONTEXT_COURSE && ! has_capability('tool/odeialba:view', $context)) {
        return;
    }

    $pluginname = get_string('pluginname', 'tool_odeialba');
    $url = new moodle_url('/admin/tool/odeialba/index.php', ['id' => $course->id]);
    $icon = new pix_icon('icon', '', 'tool_odeialba');
    $thingnode = $navigation->add(
            $pluginname,
            $url,
            navigation_node::TYPE_SETTING,
            $pluginname,
            'odeialba',
            $icon
    );
    $thingnode->make_active();
}

/**
 * Serve the files from the tool_odeialba file areas
 *
 * @param stdClass $course the course object
 * @param stdClass $cm the course module object
 * @param stdClass $context the context
 * @param string $filearea the name of the file area
 * @param array $args extra arguments (itemid, path)
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool false if the file not found, just send the file otherwise and do not return anything
 */
function tool_odeialba_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options=array()) {
    // Check the contextlevel is as expected - if your plugin is a block, this becomes CONTEXT_BLOCK, etc.
    if ($context->contextlevel != CONTEXT_COURSE) {
        return false;
    }

    // Make sure the filearea is one of those used by the plugin.
    if ($filearea !== 'file') {
        return false;
    }

    // Make sure the user is logged in and has access to the module.
    require_login($course);

    // Check the relevant capabilities - these may vary depending on the filearea being accessed.
    if (!has_capability('tool/odeialba:view', $context)) {
        return false;
    }

    // Leave this line out if you set the itemid to null in make_pluginfile_url (set $itemid to 0 instead).
    $itemid = array_shift($args); // The first item in the $args array.

    // Use the itemid to retrieve any relevant data records and perform any security checks to see if the
    // user really does have access to the file in question.

    // Extract the filename / filepath from the $args array.
    $filename = array_pop($args); // The last item in the $args array.
    if (!$args) {
        $filepath = '/'; // Variable $args is empty => the path is '/'.
    } else {
        $filepath = '/'.implode('/', $args).'/'; // Variable $args contains elements of the filepath.
    }

    // Retrieve the file from the Files API.
    $fs = get_file_storage();
    $file = $fs->get_file($context->id, 'tool_odeialba', $filearea, $itemid, $filepath, $filename);
    if (!$file) {
        return false; // The file does not exist.
    }

    // We can now send the file back to the browser - in this case with a cache lifetime of 1 day and no filtering.
    send_stored_file($file, 86400, 0, $forcedownload, $options);
}
