<?php
// This file is part of the COURSE REQUEST MANAGER plugin
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
 * Defines {@link \block_cmanager\privacy\provider} class.
 *
 * @package    block_cmanager
 * @category   privacy
 * @copyright  2018 LTS
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_cmanager\privacy;

defined('MOODLE_INTERNAL') || die();

use core_privacy\local\metadata\collection;
use core_privacy\local\request\approved_contextlist;
use core_privacy\local\request\contextlist;
use core_privacy\local\request\helper;
use core_privacy\local\request\transform;
use core_privacy\local\request\writer;

/**
 * Privacy API implementation for the COURSE REQUEST MANAGER plugin.
 *
 * @copyright  2018 Karen Holland <karen@lts.ie>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class provider implements 
    \core_privacy\local\metadata\provider,
    \core_privacy\local\request\plugin\provider {

    /**
     * Describe all the places where the COURSE REQUEST MANAGER plugin stores some personal data.
     *
     * @param collection $collection Collection of items to add metadata to.
     * @return collection Collection with our added items.
     */
    public static function get_metadata(collection $collection) : collection {

        $collection->add_database_table('block_cmanager_records', [
           'modname' => 'privacy:metadata:db:block_cmanager_records:modname',
           'modcode' => 'privacy:metadata:db:block_cmanager_records:modcode',
           'createdbyid' => 'privacy:metadata:db:block_cmanager_records:createdbyid',
           'createdate' => 'privacy:metadata:db:block_cmanager_records:createdate',
        ], 'privacy:metadata:db:block_cmanager_records');

        $collection->add_database_table('block_cmanager_comments', [
           'instanceid' => 'privacy:metadata:db:block_cmanager_comments:instanceid',
           'createdbyid' => 'privacy:metadata:db:block_cmanager_comments:createdbyid',
           'dt' => 'privacy:metadata:db:block_cmanager_comments:dt',
           'message' => 'privacy:metadata:db:block_cmanager_comments:message',
        ], 'privacy:metadata:db:block_cmanager_comments');

        return $collection;
    }

    /**
     * Get the list of contexts that contain personal data for the specified user.
     *
     * @param int $userid ID of the user.
     * @return contextlist List of contexts containing the user's personal data.
     */
    public static function get_contexts_for_userid(int $userid) : contextlist {
        $contextlist = new contextlist();
        $contextlist->add_system_context();
        return $contextlist;
    }

    /**
     * Export personal data stored in the given contexts.
     *
     * @param approved_contextlist $contextlist List of contexts approved for export.
     */
    public static function export_user_data(approved_contextlist $contextlist) {
        global $DB;

        if (!count($contextlist)) {
            return;
        }

        $syscontextapproved = false;
        foreach ($contextlist->get_contexts() as $context) {
            if ($context->id == SYSCONTEXTID) {
                $syscontextapproved = true;
                break;
            }
        }

        if (!$syscontextapproved) {
            return;
        }

        $user = $contextlist->get_user();
        $writer = writer::with_context(\context_system::instance());
        $subcontext = [get_string('pluginname', 'block_cmanager')];


        $query = $DB->get_records('block_cmanager_records', ['createdbyid' => $user->id], '', 
            'id, createdbyid as userid, modname, modcode, modmode, status, createdate');

        if ($query) {
            $writer->export_data($subcontext, (object) ['requests' => array_values(array_map(function($record) {
                unset($record->id);
                return $record;
            }, $query))]);
            unset($query);
        }


        $subcontext = [get_string('pluginname', 'block_cmanager').' '.get_string('comments', 'block_cmanager')];
        $query = $DB->get_records('block_cmanager_comments', ['createdbyid' => $user->id], '',
            'id, instanceid as requestid, createdbyid as userid, dt as createdate, message');

        if ($query) {
            $writer->export_data($subcontext, (object) ['comments' => array_values(array_map(function($record) {
                unset($record->id);
                return $record;
            }, $query))]);
            unset($query);
        }
    }

    /**
     * Delete personal data for all users in the context.
     *
     * @param context $context Context to delete personal data from.
     */
    public static function delete_data_for_all_users_in_context(\context $context) {
        global $DB;
        // Not implemented yet.
    }

    /**
     * Delete personal data for the user in a list of contexts.
     *
     * @param approved_contextlist $contextlist List of contexts to delete data from.
     */
    public static function delete_data_for_user(approved_contextlist $contextlist) {
        global $DB;
        // Not implemented yet.
    }

}
