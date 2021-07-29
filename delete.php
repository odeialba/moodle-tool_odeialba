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
 * Delete file for my plugin
 *
 * @package   tool_odeialba
 * @copyright 2021, Odei Alba <odeialba@odeialba.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use tool_odeialba\tool_odeialba_manager;

require_once(__DIR__ . '/../../../config.php');

require_sesskey();
$id = required_param('id', PARAM_INT);

$record = tool_odeialba_manager::get_record_by_id($id);
$courseid = $record->courseid;

require_login($courseid);

$context = context_course::instance($courseid);
require_capability('tool/odeialba:edit', $context);

tool_odeialba_manager::delete_record_by_id($id);

$url = tool_odeialba_manager::get_index_url_by_courseid($courseid);
redirect($url);
