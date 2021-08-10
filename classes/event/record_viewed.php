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
 * Event for record viewed
 *
 * @package    tool_odeialba
 * @copyright  2021 Odei Alba
 * @author     Odei Alba <odeialba@odeialba.com>
 * @link       https://www.odeialba.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_odeialba\event;

defined('MOODLE_INTERNAL') || die();


/**
 * record_viewed
 *
 * Class for event to be triggered when a record is viewed.
 *
 * @since      Moodle 3.11
 * @package     tool_odeialba
 * @copyright   2021 Odei Alba
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class record_viewed extends record_base {
    /**
     * Initialise the event.
     */
    protected function init() {
        parent::init();
        $this->data['crud'] = 'r';
        $this->data['edulevel'] = self::LEVEL_PARTICIPATING;
    }

    /**
     * Returns event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('event_record_viewed', 'tool_odeialba');
    }

    /**
     * Get the event description.
     *
     * @return string
     */
    public function get_description() {
        return "The user with id '{$this->userid}' viewed all records in the plugin ".
                "related to course with id '{$this->courseid}'.";
    }
}
