

// block_cmanager is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// block_cmanager is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
//
// COURSE REQUEST MANAGER BLOCK FOR MOODLE
// by Kyle Goslin & Daniel McSweeney
// Copyright 2013-2014 - Institute of Technology Blanchardstown.


------------------------------------------------
Course Request Manager for Moodle

by Kyle Goslin & Daniel McSweeney

------------------------------------------------



  CRM Current Version 4.3
  For Moodle: 2.5+
  Tested on: Moodle 2.5, 2.6, 2.7, 2.8 & 2.9


------------------------------------------------


Description:

This block is allows administrators to streamline the course creation
process at the start of the semester by allowing educators to make
requests for courses to be created.

Once requested, the Moodle administrator is responsible for accepting or 
denying requests for courses. If the administrator is not happy, a comment 
feature allows a conversation to take place between them and the educator about
the pending request.

During the process of creating a course request, additional information 
about the course can be collected from the educator such as course title, 
course codes and semester information. This block also offers additional 
customizable fields to allow the administrator to collect additional course 
information.


== Installation ==

To install this block, simply drop the entire cmanager folder into your
moodle/blocks folder. Go to Site Administration >> Notifications to install the plugin.

Once this has been done, navigate back to your site frontpage and add an instance
of the block. 
NOTE: You can add the block elsewhere but we recommend the site frontpage

Once you have done this, a short script will run setting up cmanager's environment
variables. This script will notify you when it is finished.

Then make sure you enter the config settings for the block (visible from the block) and 
configure the request block for new users.

You will also ned to set permissions for accessing the block and making requests etc.
See the section on permissions for more information on this.

For more assistance please check out the plugin page on moodle.org



==Permissions==

We’re making use of the Moodle permissions system. The block assumes that the following
user roles are in existence


teacher
coursecreator
editingteacher
manager
student
guest

When the bock is installed, users groups have the following permissions

Admin: Complete control over the block including config and managing of requests etc
Manager: Managing of requests - cannot update the config of the block
Course Creator: Managing of requests - cannot update the config of the block
Teacher: Those with a site level Teacher role can request courses. By default the teacher role is not a system role.
Student: Can view the block but cannot access any functions (this may be disabled in the block permissions)
Guest: Can view the block but cannot access any functions (this may be disabled in the block permissions)


There are two approaches

Allocate users who you want to be able to request courses the ‘Teacher’ role at the site level *some sites do this - we dont*

OR

Most sites will
1. Create a new site role called “Course Requestor” e.g. Site Administration >> Users >> Permissions >> Define Roles >> Add new role
2. Base the role on Authenticated User
3. Allow the role to be assigned at System level (or block level)
4. Grant the following permissions for Block: Course Request Manager
	Add comment (block/cmanager:addcomment)
	Add Record (Add Record)
	Delete Record (block/cmanager:deleterecord)
	Edit Record (block/cmanager:editrecord)
	View Record (block/cmanager:viewrecord)
5. Assign some users to this new role.

Thats it. You should be good to go.

You can also remove or add permissions to the manager and course creator roles using define permissions.



== Upgrade ==
To upgrade, simply copy the entire cmanager folder into your
moodle/blocks folder. 

Note: We have had some reports of occasional upgrade issues. If that happens its best to remove the block, uninstall and reinstall.
Just make sure all requests have been approved and the request history isnt that valuable etc (as the old tables are dropped)

== Documentation ==

Documentation for this block can be found in the "Documentation" folder.

== Bug Tracking ==

If you discover any bugs, please do not hesitate to share it with us at:
https://github.com/Kylegoslin/Course-Request-Manager/issues

== Latest Release ==

The latest release of our block can be found at:
https://github.com/Kylegoslin/Course-Request-Manager
