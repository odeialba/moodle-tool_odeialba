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
 * List of web services.
 *
 * @package    tool_odeialba
 * @copyright  2021 Odei Alba
 * @author     Odei Alba <odeialba@odeialba.com>
 * @link       https://www.odeialba.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$functions = [
        'tool_odeialba_delete_record' => [
                'classname' => 'tool_odeialba\external\delete_record',
                'description' => 'Deletes record with given id.',
                'type' => 'write',
                'ajax' => true,
                'capabilities' => 'tool/odeialba:edit',
        ],
        'tool_odeialba_display_table' => [
                'classname' => 'tool_odeialba\external\display_table',
                'description' => 'Returns view params.',
                'type' => 'read',
                'ajax' => true,
                'capabilities' => 'tool/odeialba:view',
        ],
];
