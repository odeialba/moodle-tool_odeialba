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
use tool_odeialba\tool_odeialba_manager;

/**
 * Unit test for my plugin
 *
 * @package   tool_odeialba
 * @copyright 2021, Odei Alba <odeialba@odeialba.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Class tool_odeialba_sample_testcase
 *
 * @package tool_odeialba
 */
class tool_odeialba_sample_testcase extends advanced_testcase {
    public function test_insert() {
        $this->resetAfterTest(true);
        $course = $this->getDataGenerator()->create_course();
        $context = context_course::instance($course->id);
        $recordtoinsert = (object) [
                'courseid' => $course->id,
                'name' => 'Test name',
                'completed' => '1',
        ];

        $id = tool_odeialba_manager::insert_record($recordtoinsert, $context);
        $record = tool_odeialba_manager::get_record_by_id($id);

        $this->assertEquals('Test name', $record->name);
    }

    public function test_update() {
        $this->resetAfterTest(true);
        $course = $this->getDataGenerator()->create_course();
        $context = context_course::instance($course->id);
        $recordtouse = (object) [
                'courseid' => $course->id,
                'name' => 'Test name',
                'completed' => '1',
        ];

        $id = tool_odeialba_manager::insert_record($recordtouse, $context);

        $recordtouse->id = $id;
        $recordtouse->name = 'Test name new';
        tool_odeialba_manager::update_record($id, $recordtouse, $context);
        $record = tool_odeialba_manager::get_record_by_id($id);

        $this->assertEquals('Test name new', $record->name);
    }

    public function test_delete() {
        $this->resetAfterTest(true);
        $course = $this->getDataGenerator()->create_course();
        $context = context_course::instance($course->id);
        $recordtouse = (object) [
                'courseid' => $course->id,
                'name' => 'Test name',
                'completed' => '1',
        ];

        $id = tool_odeialba_manager::insert_record($recordtouse, $context);
        tool_odeialba_manager::delete_record_by_id($id);
        $record = tool_odeialba_manager::get_record_by_id($id);

        $this->assertNull($record);
    }

    public function test_all() {

        $this->resetAfterTest(true);
        $course = $this->getDataGenerator()->create_course();
        $context = context_course::instance($course->id);
        $recordtouse = (object) [
                'courseid' => $course->id,
                'name' => 'Test name',
                'completed' => '1',
        ];

        $id = tool_odeialba_manager::insert_record($recordtouse, $context);
        $record = tool_odeialba_manager::get_record_by_id($id);

        $this->assertEquals('Test name', $record->name);

        $recordtouse->id = $id;
        $recordtouse->name = 'Test name new';
        tool_odeialba_manager::update_record($id, $recordtouse, $context);
        $recordnew = tool_odeialba_manager::get_record_by_id($id);

        $this->assertEquals('Test name new', $recordnew->name);

        tool_odeialba_manager::delete_record_by_id($id);
        $recorddeleted = tool_odeialba_manager::get_record_by_id($id);

        $this->assertNull($recorddeleted);
    }

    public function test_new_universal_function() {

        $this->resetAfterTest(true);
        $course = $this->getDataGenerator()->create_course();
        $context = context_course::instance($course->id);
        $recordtouse = (object) [
                'courseid' => $course->id,
                'name' => 'Test name',
                'completed' => '1',
                'description_editor' => [
                        'text' => 'This is description',
                        'format' => '1',
                ]
        ];

        $id = tool_odeialba_manager::save_record(0, $recordtouse, $context);
        $record = tool_odeialba_manager::get_record_by_id($id);

        $this->assertEquals('Test name', $record->name);
        $this->assertEquals('This is description', $record->description);

        $recordtouse->id = $id;
        $recordtouse->name = 'Test name new';
        tool_odeialba_manager::save_record($id, $recordtouse, $context);
        $recordnew = tool_odeialba_manager::get_record_by_id($id);

        $this->assertEquals('Test name new', $recordnew->name);

        tool_odeialba_manager::delete_record_by_id($id);
        $recorddeleted = tool_odeialba_manager::get_record_by_id($id);

        $this->assertNull($recorddeleted);
    }
}
