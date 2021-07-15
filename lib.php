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
