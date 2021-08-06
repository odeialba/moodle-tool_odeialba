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
    const deletelink = document.querySelector('[data-action="delete"]');

    if (deletelink) {
        deletelink.addEventListener('click', (e) => {
            e.preventDefault();
            const anchor = e.target.closest('a');

            if (anchor) {
                deleteNotification(anchor.getAttribute('href'));
            }
        });
    }
};

/**
 * Display confirmation dialogue
 *
 * @param {String} href
 */
const deleteNotification = (href) => {
    require(['core/str', 'core/notification'], function(str, notification) {
        str.get_strings([
            {'key' : 'delete'},
            {'key' : 'confirmdelete', component : 'tool_odeialba'},
            {'key' : 'yes'},
            {'key' : 'no'},
        ]).done(function(s) {
            notification.confirm(s[0], s[1], s[2], s[3], function() {
                window.location.href = href;
            });
        }).fail(notification.exception);
    });
};
