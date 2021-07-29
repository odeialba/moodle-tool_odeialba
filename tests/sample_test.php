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

        $id = tool_odeialba_manager::insert_record($course->id, 'Test name', 1);
        $record = tool_odeialba_manager::get_record_by_id($id);

        $this->assertEquals($record->name, 'Test name');
    }

    public function test_update() {
        $this->resetAfterTest(true);
        $course = $this->getDataGenerator()->create_course();

        $id = tool_odeialba_manager::insert_record($course->id, 'Test name', 1);
        tool_odeialba_manager::update_record($id, 'Test name new', 1);
        $record = tool_odeialba_manager::get_record_by_id($id);

        $this->assertEquals($record->name, 'Test name new');
    }

    public function test_delete() {
        $this->resetAfterTest(true);
        $course = $this->getDataGenerator()->create_course();

        $id = tool_odeialba_manager::insert_record($course->id, 'Test name', 1);
        tool_odeialba_manager::delete_record_by_id($id);
        $record = tool_odeialba_manager::get_record_by_id($id);

        $this->assertNull($record);
    }

    public function test_all() {
        $this->resetAfterTest(true);
        $course = $this->getDataGenerator()->create_course();

        $id = tool_odeialba_manager::insert_record($course->id, 'Test name', 1);
        $record = tool_odeialba_manager::get_record_by_id($id);

        $this->assertEquals($record->name, 'Test name');

        tool_odeialba_manager::update_record($id, 'Test name new', 1);
        $recordnew = tool_odeialba_manager::get_record_by_id($id);

        $this->assertEquals($recordnew->name, 'Test name new');

        tool_odeialba_manager::delete_record_by_id($id);
        $recorddeleted = tool_odeialba_manager::get_record_by_id($id);

        $this->assertNull($recorddeleted);
    }
}
