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
 * Class for creating forms
 *
 * @package   tool_odeialba
 * @copyright 2021, Odei Alba <odeialba@odeialba.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_odeialba;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

/**
 * Class tool_odeialba_table
 *
 * @package tool_odeialba
 */
class tool_odeialba_form extends \moodleform {
    /**
     * Definition of edit form
     *
     * @throws \coding_exception
     */
    public function definition() {
        global $COURSE;
        $mform = $this->_form;

        $mform->addElement('text', 'name', get_string('name', 'tool_odeialba'));
        $mform->addElement('checkbox', 'completed', get_string('completed', 'tool_odeialba'));
        $mform->setType('name', PARAM_NOTAGS);
        $mform->addElement('hidden', 'courseid', $COURSE->id);
        $mform->setType('courseid', PARAM_NOTAGS);
        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_NOTAGS);

        $this->add_action_buttons(true, get_string('save'));
    }

    /**
     * Data validator for form
     *
     * @param array $data
     * @param array $files
     * @return array
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function validation($data, $files) {
        global $DB;

        $errors = parent::validation($data, $files);

        $data = (object)$data;
        if ((int) $data->id === 0 && $DB->record_exists('tool_odeialba', ['courseid' => $data->courseid, 'name' => $data->name])) {
            $errors[] = "existingnameerror";
        }

        return $errors;
    }
}
