# Change Log
All notable changes to this project will be documented in this file.

## [5.3.1] - 2022-01-20 (BETA)
### Added
- Adopted by new maintainer - Michael Milette, TNG Consulting Inc.
- Support for filters in emails.
- Full support for multi-language sites.
- Font size is now inherited from theme.
- File: LICENSE.txt
- File: .gitignore
- File: composer.json
- File: CONTRIBUTING.md

### Updated
- Modernized look of user interface (look and feel).
- Fixed many bug.
- Now compatible with Moodle 3.7, 3.8, 3.9, 3.10 and 3.11.
- Compatible with PHP 7.1, 7.2, 7.3 and 7.4.
- Compatibility with most Bootstrap 4 - Boost-based themes.
- Many accessibility improvements.
- Corrected typos in language strings.
- Fixed HTML title on several pages.
- Fixed breadcrumbs on several pages.
- Commented some ambiguous source code.
- Applied Moodle coding standards in some places.
- Refactored some code to reduce duplication.
- Replaced some JavaScript with HTML.
- Corrected some HTML5 issues.
- Fixed some formatting issues in documentation on Moodle 3.9 and 3.10 Docs.
- Fixed field sorting issue in preview.
- Font size is now inherited from theme.
- Fixed issues with displaying radio buttons in forms.
- Updated README.md documentation
- Renamed and updated CHANGELOG.md
- Removed ZIP files of old version of code.
- Copyright notice to include 2022.
- Removed ability to delete active form.
- Fixed field sorting issue in preview.
- Fixed issues with displaying radio buttons in forms.
- Fixed deprecated functions.
- Fixed typos in language file.
- Fixed display of categories dropdown.
- Buttons/links css now compatible with bootstrap.
- Transparent background applied to list folder icons, compatible with all background colours.
- Fixed installation Warning: time() expected exactly 0 parameters, 1 given

## [5.1] 2018-06
- Fixed buttons on review request page, were right aligning instead of bottom.
- Updated version.php issue.
- Updated DB schema issue for new moodle.

- Origin issue corrected during request process
- cmanager home select all option cross browser issue fixed during bulk request actions
- removed old code from cmanager_config.php
- removed old $_POST/$_GET references from root folder.
- Updated version requirement for Moodle & cmanager version
- formlib/preview.php types set for each field.
- additional cleaning on ajax function.php and parameters.
- Delete icons link fixed in formbuilder.
- Additional lang strings added to settings.
- cmanager_admin lang strings added.
- Blank search prevented for admin.
- Minor changes to "open details" card.
- Events/logging added into the course approve process, course delete, new course request.

### 2018-07
- Modified table layout / removed old P tags for view_summary.php
- jquery 1.7.2 updated to  3.3.1
- jquery-ui 1.8 updated to 1.12.1 / removed not needed refs.
- new links to config in block settings when in edit mode to config block. Updated lang strings.
- warning message for searching without a string in admin console.
- modified user and admin comment layout to utilise more screen space.
- Event logging added into the course approval process.
- Event logging added to the course deleting process.

  With thanks to LTS.ie
  Fixed XMLDB PATH format.
- Prevent request inserts from users without permissions.
- Additional lang strings added.
- lib/coursecatlib.php include added.
- GDPR Privacy API support added.

### Date: 2018-08
- Minor bug on radio buttons during form creation fixed. (page2.php)
- Bootstrap modals added to replace older JavaScript pop ups (cmanager_admin)
- module_manager / cmanager_admin_arch modals added.
- cmanager_admin_arch removed tab refs and added in modals.
- adminsettings.php modals added.
- cmanager_config.php modals added.
- modals added to page1.php / removed leave page warnings.
- cmanager_admin search alignment in IE fixed.
- DE lang strings removed as are outdated, moving to AMOS.
- modals added to page2.php and redirects removed.
- page2 now preventing blank form titles being created.
- JS modified in cmanager_admin to remove warnings.
- page2.php form editor element positioning bug fixed.

## [4.3] 2015-05-25
- Fixed permissions in access.php.
- Updated readme.txt with quick guide on permissions
- Changed block_cmanager to work on permissions rather than admin privs
- Deleted duplicate cmanager folder
- Updated icon set
- Display database record ID for each request rather than counter
- Course Creator and Manager roles (system) can now manage approval process
- Fix for course categories not displaying for users on page 1 of form
- Minor fixes and improvements
- Tested with 2.8 & 2.9

See Github for full log!

## [4.2] 2014-08-05
- Question marks not accepted during request process. Question marks are now allowed.
- Editing issue during course request resolved.
- Bulk approve updated

See Github for full log!

## [4.1] 2014-04-11
- Enrollment keys are optional
- Safari form1.php saving issue solved.
- Various small bug fixes

## [4.0] 2014-04-01
- Updated license information
- Checked on Moodle 2.6
- Default form blank value error resolved.

## [3.9] 2013-11-25
- New Layout for requests
- Issue with escaping characters resolved
- New German Language pack added (Thanks to Alexander Kiy )
- New layout for comment system
- New pre-defined deny reason capability added
- Error when creating comment resolved
- Improvements to layout of pages
- various bug fixes

## [3.5] 2013-09-20
Self enroll error has been resolved, due to a new field appearing
in the moodle 2.5 enroll database table.

## [3.3] 2013-09-10
- Long drop down menu on cmanager_admin is show shortened until clicked
- New Icons added to block
- Escaping values issue corrected

## [3.1]
### Changes
- Dropdown deleting error has been resolved. Dropdowns can be deleted without any error. (Error: No Id added)
- Echo added to review_request.php
- Ordering issue in course_new.php and displayLists.php updated to ASC
- Postgree SQL error on module_manager.php removed
- Category list updated on request page, using the default method of displaying categories in lists.
- Additional fields added into the naming conventions to include years.

## [3.0] 2013-03-12
### Changes
- Debug errors showing up in Moodle 2.4 these have been removed.
- Clear history function has been added to allow the admin to remove all archived requests and also every request in the system.
- Mod mode was missing from some summaries, this has been added
- Search function has been added to current requests and also existing requests, allowing the admin to search by author name, code and title for specific requests.
- Unused Jquery references have been removed. All Jquery references are to the local code, and no external connections are now made.
- Unused CSS references have been removed
- New function added to allow the admin to allow the person making the request to categorise their request using the Moodle categories.
- New function added allowing the admin to quickly change the category of a request to allow for quicker approvals.
- All course default settings are now pulled from the moodle installation, and not replicated in the CRM.
- Dead links when using My Moodle have been fixed.
- A counter has been added at a block level to show the number of requests currently pending.
- All references to mdl_ have been removed.
- Archived requests has now become a separate page.
- Misc Config / E-mail settings have been broken down into two separate pages.
- Layout has been improved to prevent border lines overlapping on requests.
- New Bulk approve function added.
- Layout of comments have been updated to improve readability.
- New Quick Approval function allowing admin to add course in one click.
- New function added to allow fields on form page 2 to become optional or required.
- jQuery Tabs removed to improve any cross browser issues.
