<?php
// ---------------------------------------------------------
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
// Copyright 2012-2018 - Institute of Technology Blanchardstown.
// ---------------------------------------------------------
/**
 * COURSE REQUEST MANAGER
  *
 * @package    block_cmanager
 * @copyright  2018 Kyle Goslin, Daniel McSweeney
 * @copyright  2021 Michael Milette (TNG Consulting Inc.), Daniel Keaman
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['pluginname'] = 'Course Request Manager';
$string['plugindesc'] = 'Course Request Manager';

//block links
$string['block_admin'] = 'Request queue';
$string['block_config'] = 'Configuration';
$string['block_request'] = 'Make a request';
$string['block_manage'] = 'Manage your requests';

//basic Navigation
$string['back'] = 'Back	';
$string['SaveChanges'] = 'Save changes';
$string['ChangesSaved'] = 'Changes saved';
$string['SaveAll'] = 'Save all';
$string['SaveEMail'] = 'Add e-mail';
$string['Continue'] = 'Continue';
$string['Enabled'] = 'Enabled';
$string['Disabled'] = 'Disabled';
$string['clickhere'] = 'Click here';
$string['update'] = 'Update';
$string['Request'] = 'Request';

//block_cmanager_config.php
$string['administratorConfig'] = 'Other settings';
$string['emailConfig'] = 'E-mail config';
$string['emailConfigContents'] = 'Configure communication e-mails';
$string['cmanagerStats'] = 'CManager statistics';
$string['emailConfigInfo'] = 'This section contains e-mail addresses of administrators who will be notified whenever any course requests have been recorded.';
$string['emailConfigSectionHeader'] = 'Configure e-mail';
$string['emailConfigSectionContents'] = 'Configure e-mail contents';
$string['statsConfigInfo'] = 'This section contains statistics on the current number of requests which have been made since the Course Request Manager module has been in use on this website.';
$string['totalRequests'] = 'Total number of requests';
$string['config_addemail'] = 'E-mail address';
$string['namingConvetion'] = 'Course naming convention';
$string['namingConvetionInstruction'] = 'Course Request Manager will set up your courses using a selected naming convention.';
$string['namingConvetion_option1'] = 'Full name only';
$string['namingConvetion_option2'] = 'Short name - full name';
$string['namingConvetion_option3'] = 'Full name (short name)';
$string['namingConvetion_option4'] = 'Short name - full name (year)';
$string['namingConvetion_option5'] = 'Full name (year)';


$string['snamingConvetion'] = 'Short name format';
$string['snamingConvetionInstruction'] = 'Please choose a short name format for newly created courses';
$string['snamingConvetion_option1'] = 'Short name only';
$string['snamingConvetion_option2'] = 'Short name - mode';
$string['configure_EnrolmentKey'] = 'Enrolment key';
$string['configure_EnrolmentKeyInstruction'] = 'Course Request Manager can generate an automatic enrolment key or you may choose to prompt the user for an enrolment key of their choice. If you choose to prompt the user, a field for entering the enrolment key will be added to the first page of the request form.';
$string['configure_defaultStartDate'] = 'Start date';
$string['configure_defaultStartDateInstructions'] = 'Please select a default start date for new courses.';
$string['configure_delete'] = 'Are you sure you want to delete this request?';
$string['configure_deleteMail'] = 'Are you sure you want to delete this admin e-mail address?';
$string['configure_leaveblankmail'] = 'Leave this field blank to avoid a mail being sent.';
$string['configure_instruction1'] = 'Course Request Manager settings allows you to set administrator emails for communication, set default emails, set course naming conventions and default course settings such as start date and enrolment key policy.';
$string['configure_instruction2'] = 'The request form is split over two pages. Page one will prompt the user for the shortname and long name of the course to be created. This link will allow you to name those fields and also enable an optional course mode setting.';
$string['configure_instruction3'] = 'This allows you to configure the 2nd page of the request. On this page you can create form elements that must be completed by the user as part of the request process. While this information is not used by Course Request Manager, it will allow administrators to gather structured information on the request and aid in the decision making process.';



//email configuration strings
$string['emailConfigHeader'] = 'Here you can make changes to the e-mails which are sent to the users as notification when the status of their module has changed.';
$string['email_courseCode'] = 'Course code';
$string['email_courseName'] = 'Course name';
$string['email_progCode'] = 'Programme code';
$string['email_progName'] = 'Programme name';
$string['email_enrolmentKey'] = 'Enrolment key';
$string['email_fullURL'] = 'Full URL of new course';
$string['email_sumLink'] = 'Full Course Request Manager request summary link';
$string['email_newCourseApproved'] = 'New course approved';
$string['email_UserMail'] = 'User e-mail';
$string['email_AdminMail'] = 'Admin e-mail';
$string['email_currentOwner'] = 'Current owner e-mail';
$string['email_requestNewModule'] = 'Request new course';
$string['email_commentNotification'] = 'Comment notification e-mail';
$string['email_requestDenied'] = 'Request denied e-mail';
$string['email_handover'] = 'Handover request';
$string['email_noReply'] = 'Communications e-mail address';
$string['email_noReplyInstructions'] = 'This utility sends email requests to administrators and end users as course requests are processed. Please enter the email address that these emails will appear to be sent from. It is recommended to use a no-reply email address. e.g. noreply@example.com';

//email subjects and contents
$string['emailSubj_userApproved'] = "Moodle request approved!";
$string['emailSubj_adminApproved'] = "Moodle user request approved!";
$string['emailSubj_adminNewRequest'] = "New Moodle request";
$string['emailSubj_userNewRequest'] = "New Moodle request";
$string['emailSubj_userNewComment'] = "New comment";
$string['emailSubj_adminNewComment'] = "New comment";
$string['emailSubj_adminDeny'] = "Request denied";
$string['emailSubj_userDeny'] = "Request denied";
$string['emailSubj_teacherHandover'] = "Request for control";
$string['emailSubj_From'] = "From";
$string['emailSubj_Comment'] = "Comment";
$string['emailSubj_pleasecontact'] = "Please contact";
$string['emailSubj_mailSent1'] = "An email has been sent to";
$string['emailSubj_mailSent2'] = "on your behalf.";
$string['emailSubj_requester'] = "Requester e-mail";

//course request
$string['request_rule1'] = 'Please enter a value in this field.';
$string['request_rule2'] = 'Please select a value.';
$string['request_rule3'] = 'Please enter an enrolment key.';
$string['request_requestControl'] = 'Request control of this course';
$string['request_requestnewBlank'] = 'Request a new blank course and the removal of this course';
$string['request_pleaseSelect'] = 'Please select course mode.';
$string['request_complete'] = 'Course request completed.';
$string['request_addModule'] = 'Add course';

$string['requestForm_category'] = 'Category';

$string['changeshavebeensaved'] = 'Changes saved.';
//form builder
$string['formBuilder_name'] = 'Configure Request Form - Page 2';
$string['formBuilder_instructions'] = 'The course request facility has been developed to allow users to enter additional information on course requests. This form can be configured to allow you to gather as much or as little information from the end user. If you require information from your users to assist you in creating a course, then configure the form here. Typical uses have included:';
$string['formBuilder_instructions1'] = 'Programme codes and titles';
$string['formBuilder_instructions2'] = 'Desired start and end dates';
$string['formBuilder_instructions3'] = 'Desired enrolment keys';
$string['formBuilder_instructions4'] = 'Additional staff and permissions';
$string['formBuilder_instructions5'] = 'Course format';
$string['formBuilder_instructions6'] = 'Many others...';
$string['formBuilder_currentActiveForm'] = 'Current active form';
$string['formBuilder_currentActiveFormInstructions'] = 'Please select the form to use from the dropdown below. You can change the current active form at any time or create a new one and select it. ';
$string['formBuilder_selectDescription'] = 'Select form to use for requests';
$string['formBuilder_selectOption'] = 'Select form..';
$string['formBuilder_manageFormsText'] = 'Manage forms';
$string['formBuilder_createNewText'] = 'Create a new form';
$string['formBuilder_selectAny'] = 'Select any of the forms below to begin editing.';
$string['formBuilder_p2_error'] = 'Error: No ID added.';
$string['formBuilder_p2_header'] = 'Additional information form editor';
$string['formBuilder_p2_addNewField'] = 'Add new field';
$string['formBuilder_p2_dropdown1'] = 'Add new...';
$string['formBuilder_p2_dropdown2'] = 'Text field';
$string['formBuilder_p2_dropdown3'] = 'Text area';
$string['formBuilder_p2_dropdown4'] = 'Radio button group';
$string['formBuilder_p2_dropdown5'] = 'Drop down menu';
$string['formBuilder_p2_dropdown6']  = 'Text';
$string['formBuilder_p2_instructions'] = 'This form can be configured to allow you to gather as much or as little information needed from the end user. If you require information from your users to assist you in creating a course, then configure the form here. Simply add or remove fields which the user will complete during a course request. This information can then assist you when dealing with requests.';
$string['formBuilder_about'] = 'About';
$string['formBuilder_editForm'] = 'Edit form';
$string['formBuilder_deleteForm'] = 'Delete form';
$string['formBuilder_previewForm'] = 'Preview form';
$string['formBuilder_confirmDelete'] = 'Do you want to delete this form?';
$string['formBuilder_returntoCM'] = 'Return to Course Request Manager';
$string['formBuilder_dropdownTxt'] = 'Drop down menu';
$string['formBuilder_radioTxt'] = 'Radio buttons';
$string['formBuilder_textAreaTxt'] = 'Text area';
$string['formBuilder_textFieldTxt'] = 'Text field';
$string['formBuilder_addUserTxt']  = 'Text';
$string['formBuilder_leftTxt'] = 'Name';
$string['formBuilder_saveTxt'] = 'Save';
$string['formBuilder_addedItemsTxt'] = 'The following items are listed in this component';
$string['formBuilder_addItemBtnTxt'] = 'Add new item';
$string['formBuilder_editingForm']  = 'Editing form';
$string['formBuilder_shownbelow']  = 'is shown below';
$string['formBuilder_nooptionsadded']  = 'No fields have been added.';

//preview form page
$string['formBuilder_previewHeader'] = 'Preview form';
$string['formBuilder_previewInstructions1'] = 'Please complete this form as accurately as possible.';
$string['formBuilder_previewInstructions2'] = 'Please consult local guidelines for making course requests';
$string['formBuilder_step2'] = 'Step 2: Other details';

//Configure Course Search Form Fields
$string['formfieldsHeader'] = 'Configure Request Form - Page 1';
$string['entryFields_instruction1'] = 'Configure the first page of the course request form. The first page of the request form is used to accept values from the user for the course short name and the course full name as required by Moodle. These may be described differently by your organisation. For example you may use a course code (short name) and a course name (full name) to describe your courses.';
$string['entryFields_instruction2'] = 'For each of the two fields below, you may change the name of the field as it appears to the user and also the help text that is associated with each field.';
$string['entryFields_TextfieldOne'] = 'Configure field for course short name';
$string['entryFields_TextfieldTwo'] = 'Configure field for course full name';
$string['entryFields_Name'] = 'Name';
$string['entryFields_Description'] = 'Description';
$string['entryFields_status'] = 'Status';
$string['entryFields_Dropdown'] = 'Optional dropdown field';
$string['entryFields_DropdownDescription'] = ' You may wish to add an optional drop down list with some values that will help you categorise the new course. For example your organisation may offer courses in full time mode, part time mode, distance education mode, online only mode etc. You can add these options to the optional dropdown list and allow users to select one when making a new course request.';
$string['entryFields_AddNewItem'] = 'Add new item';
$string['entryFields_values'] = 'Values: ';

//module_manager
$string['cmanager'] = 'CRManager';
$string['cmanagerDisplay'] = 'Course Request Manager';
$string['cmanagerDisplaySearchForm'] = 'Configure Request Form - Page 1';
$string['cmanagerWelcome'] = 'Welcome to Course Request Manager. Before requesting a new course, please check your local guidelines.';
$string['cmanagerRequestBtn'] = 'Request a new course setup';
$string['cmanagerExstingTab'] = 'Existing requests';
$string['cmanagerHistoryTab'] = 'Request history';
$string['cmanagerActions'] = 'Actions';
$string['cmanagerConfirmCancel'] = 'Are you sure you want to cancel this request?';
$string['cmanagernonePending'] = 'Sorry, nothing pending!';
$string['cmanagerEnrolmentInstruction'] = 'Course Request Manager can generate an automatic enrolment key or you may choose to prompt the user for an enrolment key of their choice.';
$string['cmanagerEnrolmentOption1'] = 'Automatically generated key';
$string['cmanagerEnrolmentOption2'] = 'Prompt user for key';
$string['cmanagerEnrolmentOption3'] = 'Do not ask for key';

$string['deleteAllRequests'] = 'Delete all current and archived requests';
$string['deleteOnlyArch'] = 'Delete only archived requests';
$string['clearHistoryTitle'] = 'Clear history';
$string['allowSelfCategorization'] = 'Allow user to select category';
$string['allowSelfCategorization_desc'] = 'When enabled, the user will be prompted to select a location in the Moodle catalogue to place their course';
$string['selfCatOn'] = 'Self-categorization On';
$string['selfCatOff'] = 'Self-categorization Off';

$string['sureDeleteAll'] = 'Are you sure you want to delete ALL history?';
$string['sureOnlyArch'] = 'Are you sure you want to delete only archived records?';
$string['yesDeleteRecords'] = 'Yes, delete';
$string['recordsHaveBeenDeleted'] = 'Records have been deleted';
$string['clickHereToReturn'] = 'Click here to return';

$string['selectedcategory'] = 'Category';
$string['yes'] = 'Yes';

//request details
$string['requestReview_Summary'] = 'Request summary';
$string['requestReview_intro1'] = 'Please review the following information carefully before submitting your request.';
$string['requestReview_intro2'] = 'Your request will be dealt with as soon as possible.';
$string['requestReview_status'] = 'STATUS';
$string['requestReview_COMPLETE'] = 'COMPLETE';
$string['requestReview_PENDING'] = 'PENDING';
$string['requestReview_REQUEST_DENIED'] = 'REQUEST DENIED';
$string['requestReview_NULL'] = 'NULL';

$string['requestReview_requestType'] = 'Request type';
$string['requestReview_moduleCode'] = 'Course code';
$string['requestReview_moduleName'] = 'Course name';
$string['requestReview_originator'] = 'Originator';


$string['requestReview_ccdne'] = 'Course currently does not exist';
$string['reviewLocation'] = 'Location';

$string['requestReview_SubmitRequest'] = 'Submit request';
$string['requestReview_AlterRequest'] = 'Alter request';
$string['requestReview_CancelRequest'] = 'Cancel request';
$string['requestReview_creationDate'] = 'Creation date';
$string['requestReview_requestType'] = 'Request type';

$string['requestReview_OpenDetails'] = 'Open details';
$string['requestReview_ApproveRequest'] = 'Approve request';


$string['requestReview_courseName'] = 'Course name';
$string['requestReview_courseCode'] = 'Course code';



//comments
$string['comments_date'] = 'Date / Time';
$string['comments_message'] = 'Message';
$string['comments_from'] = 'From';
$string['comments_Header'] = 'Add / View comments';
$string['comments_Forward'] = 'All comments will automatically be forwarded by email also';
$string['comments_PostComment'] = 'Post comment';
$string['comments_comment'] = 'Comment';
$string['comments_author'] = 'Author';

//deny request
$string['denyrequest_Title'] = 'Course Request Facility - Deny request';
$string['denyrequest_Instructions'] = 'Outline below why the request has been denied';
$string['denyrequest_Btn'] = 'Deny request';
$string['denyrequest_reason'] = 'Outline below why the request has been denied (max 280 chars)';

//approve request
$string['approverequest_Title'] = 'Course Request Facility - Approve request';
$string['approverequest_New'] = 'New course has been created';
$string['approverequest_Process'] = 'Handover process has begun';

$string['approve_course_no_id'] = 'No course ID set';

//misc
$string['alert'] = 'Alert';
$string['noPending'] = 'Sorry, nothing pending!';
$string['status'] = 'STATUS';
$string['creationdate'] = 'Creation date';
$string['requesttype'] = 'Request type';
$string['originator'] = 'Originator';
$string['comments'] = 'Comments';
$string['bulkactions'] = 'Bulk actions';
$string['withselectedrequests'] = 'with selected requests';
$string['existingrequests'] = 'Existing requests';
$string['actions'] = 'Actions';
$string['currentrequests'] = 'Current requests';
$string['archivedrequests'] = 'Archived requests';
$string['myarchivedrequests'] = 'My archived requests';
$string['allarchivedrequests'] = 'All archived requests';


$string['configure'] = 'Configure Course Request Manager';
$string['courserequestline1'] = 'Please refer to in-house guidelines for naming courses.';
$string['courserequestadmin'] = 'Course request administration';
$string['configureHeader'] = 'Course request facility - CManager configuration';
$string['approve'] = 'Approve';
$string['deny'] = 'Deny';
$string['edit'] = 'Edit';
$string['cancel'] = 'Cancel';
$string['delete'] = 'Delete';
$string['view'] = 'View';
$string['viewmore'] = 'View more';
$string['addviewcomments'] = 'Add / View comments';
$string['configurecoursemanagersettings'] = ' Configure Course Request Manager settings';
$string['configurecourseformfields'] = '  Configure Request Form - Page 1';
$string['informationform'] = ' Configure Request Form - Page 2';
$string['modrequestfacility'] = 'Course request facility';
$string['step1text'] = 'Step 1: Course request details';
$string['modexists'] = 'It looks like the course you are requesting may already exists on this site.';
$string['modcode'] = 'Course code';
$string['modname'] = 'Course name';
$string['catlocation'] = 'Catalogue location';
$string['lecturingstaff'] = 'Lecturing staff';
$string['actions'] = 'Actions';
$string['noneofthese'] = 'None of these? Continue making a new course';
$string['sendrequestforcontrol'] = 'Send request for control';
$string['sendrequestemail'] = 'Send request e-mail';
$string['emailswillbesent'] = 'E-mails will be sent to the owner of the course. Once you send a request, Please wait for a response.';

// View summary.php
$string['viewsummary'] = 'View summary';

// Comment.php
$string['addviewcomments'] = 'Add / View comments';

// Approve_course.php
$string['approvecourse'] = 'Approve course';



// deny_course.php
$string['denycourse'] = 'Deny course request';

// Bulk Deny
$string['bulkdeny'] = 'Bulk deny';

// Bulk Approve
$string['bulkapprove'] = 'Bulk approve';
$string['approvingcourses'] = 'Approving courses...';

// block_cmanager_config.php
$string['managersettings'] = 'Manager settings';


// Form Page 1 & Page2
$string['formpage1'] = 'Form page 1';
$string['formpage2'] = 'Form page 2';
$string['formpage2builder'] = 'Form page 2 builder';

$string['formpage1_textfield'] = 'Text field';
$string['formpage1_textarea'] = 'Text area';
$string['formpage1_rbg'] = 'Radio button group';
$string['formpage1_cbg'] = 'Check box group';

// Preview form
$string['previewform'] = 'Preview form';
$string['preview_modmode'] = 'Please select module mode';

// course_exists.php
$string['courseexists'] = 'Course exists';

// Request control.php
$string['requestcontrol'] = 'Request control';


// History record management
$string['historynav'] = 'History';


// Search Feature
$string['searchAuthor'] = 'Author';
$string['search_side_text'] = 'Search';
$string['searchbuttontext'] = 'Search!';
$string['clearsearch'] = 'Clear search';
// Quick approve
$string['quickapprove'] = 'Quick approve';
$string['quickapprove_desc'] = 'Quick approve this course?';

// Email and other settings

$string['configureemailsettings'] = 'Configure e-mail settings';
$string['configureemailsettings_desc'] = 'This section allows you to configure the e-mail settings for this tool';


$string['configureadminsettings'] = 'Admin settings';
$string['configureadminsettings_desc'] = 'Additional settings for Course Request Manager';

$string['required_field'] = 'Required field';
$string['optional_field'] = 'Optional field';

$string['cmanager:myaddinstance'] = 'Add instance';
$string['cmanager:addinstance'] = 'Add instance';

// displayLists
$string['displayListWarningTitle'] = 'WARNING';
$string['displayListWarningSideText'] = 'This shortname already exists in the Moodle database. Admin attention required. This request is excluded from bulk actions.';

$string['nocatselected'] = 'Sorry no category has been selected for this course';
$string['noneselected'] = 'None selected';

$string['customdeny'] = 'Denial text templates';
$string['customdenydesc'] = 'Administrators may deny course requests for a number of reasons. Outlining the reason for a denial in an email can be time consuming. This feature lets you create up to five reasons which can be quickly selected during the denial process. Max 250 chars';
$string['customdenyfiller'] = 'You may enter a denial reason here (max 250 chars)';
$string['denytext1'] = 'Reason 1';
$string['denytext2'] = 'Reason 2';
$string['denytext3'] = 'Reason 3';
$string['denytext4'] = 'Reason 4';
$string['denytext5'] = 'Reason 5';


// Error messages
$string['cannotrequestcourse'] = ' Sorry your account does not have sufficient privileges to request a course. You need to be assigned to a system role with sufficient privileges.';
$string['cannotviewrecords'] = ' Sorry your account does not have sufficient privileges to view records. You need to be assigned to a system role with sufficient privileges.';
$string['cannotapproverecord'] = ' Sorry your account does not have sufficient privileges to approve records. You need to be assigned to a system role with sufficient privileges.';
$string['cannoteditrequest'] = ' Sorry your account does not have sufficient privileges to edit a record. You need to be assigned to a system role with sufficient privileges.';
$string['cannotcomment'] = ' Sorry yyour account does not have sufficient privileges to comment. You need to be assigned to a system role with sufficient privileges.';
$string['cannotdelete'] = ' Sorry your account does not have sufficient privileges to delete a record. You need to be assigned to a system role with sufficient privileges.';
$string['cannotdenyrecord'] = ' Sorry your account does not have sufficient privileges to deny a record. You need to be assigned to a system role with sufficient privileges.';
$string['cannotviewconfig'] = ' Sorry your account does not have sufficient privileges to view the config. You need to be assigned to a system role with sufficient privileges.';

$string['cmanager:addcomment'] = 'Add comment';
$string['cmanager:addrecord'] = 'Add record';
$string['cmanager:approverecord'] = 'Approve record';
$string['cmanager:deleterecord'] = 'Delete record';
$string['cmanager:denyrecord'] = 'Deny record';
$string['cmanager:editrecord'] = 'Edit record';
$string['cmanager:viewrecord'] = 'View record';
$string['cmanager:viewconfig'] = 'View config';

$string['ok'] = 'Ok';
$string['lib_error_invalid_c'] = 'Invalid course ID!';

$string['course_new_mod_create'] = 'New module creation';


// Admin messages
$string['cmanager_admin_enterstring'] = 'Please enter a search string';

// events
$string['startingcoursecreation'] = 'starting course creation';
$string['createdsuccess'] = 'created a new course successfully';
$string['courserecdeleted'] = 'Course request has been deleted';
$string['requestprocessing'] = 'Request procesing';
$string['failedtoenrolcreator'] = 'Failed to enrol course creator';
$string['enrolledcrator'] = 'Enrolled creator';
$string['stepnumber'] = 'Step';

$string['keyaddfail'] = 'add enrollment key fail';
$string['keyaddsuccess'] = 'add enrollment key success';
$string['updatingrecstatus'] = 'updating record status';
$string['courserecorddeleted'] = 'Course record deleted';
$string['deletecourserequest'] = 'Delete course request';


// GDPR Privacy API
$string['comments'] = 'Comments';
$string['privacy:metadata:db:block_cmanager_records:modname'] = 'The requested course full name';
$string['privacy:metadata:db:block_cmanager_records:modcode'] = 'The requested course short name';
$string['privacy:metadata:db:block_cmanager_records:createdbyid'] = 'The user ID submitting the request';
$string['privacy:metadata:db:block_cmanager_records:createdate'] = 'Time created';
$string['privacy:metadata:db:block_cmanager_records'] = 'Stores requests for the course request manager block';
$string['privacy:metadata:db:block_cmanager_comments:instanceid'] = 'The request ID that the comment refers to';
$string['privacy:metadata:db:block_cmanager_comments:createdbyid'] = 'The user ID submitting the comment';
$string['privacy:metadata:db:block_cmanager_comments:dt'] = 'Time created';
$string['privacy:metadata:db:block_cmanager_comments:message'] = 'The comment message';
$string['privacy:metadata:db:block_cmanager_comments'] = 'Stores comments regarding courses requests for the course request manager block';
