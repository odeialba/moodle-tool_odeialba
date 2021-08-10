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
 * Base event manager for plugin.
 *
 * @package    tool_odeialba
 * @copyright  2021 Odei Alba
 * @author     Odei Alba <odeialba@odeialba.com>
 * @link       https://www.odeialba.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


namespace tool_odeialba\event;

use core\event\base;

defined('MOODLE_INTERNAL') || die();

/**
 * Base class for record events.
 *
 * @package     tool_odeialba
 * @copyright   2021 Odei Alba
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class record_base extends base {

    /**
     * Initialise the event.
     */
    protected function init() {
        $this->data['edulevel'] = self::LEVEL_TEACHING;
    }

    /**
     * Get URL related to the action.
     *
     * @return \moodle_url
     */
    public function get_url() {
        return new \moodle_url('/admin/tool/odeialba/index.php', ['id' => $this->courseid]);
    }

    /**
     * Get the object ID mapping.
     *
     * @return array
     */
    public static function get_objectid_mapping() {
        return array('db' => 'tool_odeialba', 'restore' => \core\event\base::NOT_MAPPED);
    }

    /**
     * No mapping required for this event because this event is not backed up.
     *
     * @return bool
     */
    public static function get_other_mapping() {
        return false;
    }
}
