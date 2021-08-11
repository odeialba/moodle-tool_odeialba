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
 * tool_odeialba prepare data for renderer.
 *
 * @package    tool_odeialba
 * @module     tool_odeialba/confirmation
 * @copyright  2021 Odei Alba
 * @author     Odei Alba <odeialba@odeialba.com>
 * @link       https://www.odeialba.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
export const init = () => {
    addListeners();
};

const addListeners = () => {
    const deletelinks = document.querySelectorAll('[data-action="delete"]');

    if (deletelinks) {
        deletelinks.forEach(deletelink => {
            deletelink.addEventListener('click', (e) => {
                e.preventDefault();
                const anchor = e.target.closest('a');

                if (anchor) {
                    deleteNotification(anchor.dataset.id);
                }
            });
        });
    }
};

/**
 * Display confirmation dialogue
 *
 * @param {int} id
 */
const deleteNotification = (id) => {
    require(['core/str', 'core/notification', 'core/ajax'], function(str, notification, ajax) {
        str.get_strings([
            {'key' : 'delete'},
            {'key' : 'confirmdelete', component : 'tool_odeialba'},
            {'key' : 'yes'},
            {'key' : 'no'},
        ]).done(function(s) {
            notification.confirm(s[0], s[1], s[2], s[3], function() {
                ajax.call([{
                    methodname: 'tool_odeialba_delete_record',
                    args: {id: id},
                    done: function (data) {
                        if (data.success) {
                            ajax.call([{
                                methodname: 'tool_odeialba_display_table',
                                args: {courseid: data.courseid},
                                done: function (data) {
                                    if (data.success) {
                                        refreshTable(data.htmltable);
                                    }
                                },
                                fail: notification.exception
                            }]);
                        }
                    },
                    fail: notification.exception
                }]);
            });
        }).fail(notification.exception);
    });
};

const refreshTable = (htmltable) => {
    var table = document.getElementById('tool_odeialba_table').parentNode;
    table.outerHTML = htmltable;
    addListeners();
};
