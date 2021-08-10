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
 * Unit test for my plugins events
 *
 * @package   tool_odeialba
 * @copyright 2021, Odei Alba <odeialba@odeialba.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Class tool_odeialba_events_testcase
 *
 * @package tool_odeialba
 */
class tool_odeialba_events_testcase extends advanced_testcase {
    public function test_record_created_event() {
        $this->resetAfterTest();
        $course = $this->getDataGenerator()->create_course();
        $context = context_course::instance($course->id);

        $sink = $this->redirectEvents();

        $recordtoinsert = (object) [
                'courseid' => $course->id,
                'name' => 'Test name',
                'completed' => '1',
        ];

        $id = tool_odeialba_manager::insert_record($recordtoinsert, $context);
        $record = tool_odeialba_manager::get_record_by_id($id);
        $events = $sink->get_events();
        $this->assertCount(1, $events);
        $event = reset($events);

        $this->assertInstanceOf('\tool_odeialba\event\record_created', $event);
        $this->assertEquals($context, $event->get_context());
        $this->assertEquals($record->id, $event->objectid);
        $url = new moodle_url('/admin/tool/odeialba/index.php', array('id' => $event->courseid));
        $this->assertEquals($url, $event->get_url());
    }

    public function test_record_updated_event() {
        $this->resetAfterTest(true);
        $course = $this->getDataGenerator()->create_course();
        $context = context_course::instance($course->id);
        $recordtouse = (object) [
                'courseid' => $course->id,
                'name' => 'Test name',
                'completed' => '1',
        ];

        $id = tool_odeialba_manager::insert_record($recordtouse, $context);
        $recordold = tool_odeialba_manager::get_record_by_id($id);

        $sink = $this->redirectEvents();

        $recordtouse->id = $id;
        $recordtouse->name = 'Test name new';
        tool_odeialba_manager::update_record($id, $recordtouse, $context);
        $record = tool_odeialba_manager::get_record_by_id($id);

        $events = $sink->get_events();
        $this->assertCount(1, $events);
        $event = reset($events);

        $this->assertInstanceOf('\tool_odeialba\event\record_updated', $event);
        $this->assertEquals($recordold, $event->get_record_snapshot('tool_odeialba', $id));
        $this->assertEquals($context, $event->get_context());
        $this->assertEquals($record->id, $event->objectid);
        $url = new moodle_url('/admin/tool/odeialba/index.php', array('id' => $event->courseid));
        $this->assertEquals($url, $event->get_url());
    }

    public function test_record_deleted_event() {
        $this->resetAfterTest(true);
        $course = $this->getDataGenerator()->create_course();
        $context = context_course::instance($course->id);
        $recordtouse = (object) [
                'courseid' => $course->id,
                'name' => 'Test name',
                'completed' => '1',
        ];

        $id = tool_odeialba_manager::insert_record($recordtouse, $context);
        $recordold = tool_odeialba_manager::get_record_by_id($id);

        $sink = $this->redirectEvents();
        tool_odeialba_manager::delete_record_by_id($id);

        $events = $sink->get_events();
        $this->assertCount(1, $events);
        $event = reset($events);

        $this->assertInstanceOf('\tool_odeialba\event\record_deleted', $event);
        $this->assertEquals($recordold, $event->get_record_snapshot('tool_odeialba', $id));
        $this->assertEquals($context, $event->get_context());
        $url = new moodle_url('/admin/tool/odeialba/index.php', array('id' => $event->courseid));
        $this->assertEquals($url, $event->get_url());
    }
}
