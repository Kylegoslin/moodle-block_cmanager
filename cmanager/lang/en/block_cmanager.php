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
// Copyright 2012-2014 - Institute of Technology Blanchardstown.
// --------------------------------------------------------- 
/**
 * COURSE REQUEST MANAGER
  *
 * @package    block_cmanager
 * @copyright  2014 Kyle Goslin, Daniel McSweeney
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['pluginname'] = 'Course Request Manager';
$string['plugindesc'] = 'Course Request Manager';

//block links
$string['block_admin'] = 'Request Queue';
$string['block_config'] = 'Configuration';
$string['block_request'] = 'Make a request';
$string['block_manage'] = 'Manage your requests';

//basic Navigation
$string['back'] = 'Back	';
$string['SaveChanges'] = 'Save Changes';
$string['ChangesSaved'] = 'Changes Saved';
$string['SaveAll'] = 'Save All';
$string['SaveEMail'] = 'Add E-Mail';
$string['Continue'] = 'Continue';
$string['Enabled'] = 'Enabled';
$string['Disabled'] = 'Disabled';
$string['clickhere'] = 'Click here';
$string['update'] = 'Update';
$string['Request'] = 'Request';

//block_cmanager_config.php
$string['administratorConfig'] = 'Other Settings';
$string['emailConfig'] = 'E-mail Config';
$string['emailConfigContents'] = 'Configure Communication E-Mails';
$string['cmanagerStats'] = 'CManager Statistics';
$string['emailConfigInfo'] = 'This section contains E-Mail addresses of administators who will be notified whenever any course requests have been recorded.';
$string['emailConfigSectionHeader'] = 'Configure E-Mail';
$string['emailConfigSectionContents'] = 'Configure E-Mail Contents';
$string['statsConfigInfo'] = 'This section contains statistics on the current number of requests which have been made since the Course Request Manager module has been in use on this server.';
$string['totalRequests'] = 'Total number of Requests';
$string['config_addemail'] = 'E-Mail Address';
$string['namingConvetion'] = 'Course Naming Convention';
$string['namingConvetionInstruction'] = 'Course Request Manager will set up your courses using a selected naming convention.';
$string['namingConvetion_option1'] = 'Full Name Only';
$string['namingConvetion_option2'] = 'Short Name - Full Name';
$string['namingConvetion_option3'] = 'Full Name (Short Name)';
$string['namingConvetion_option4'] = 'Short Name - Full Name (Year)';
$string['namingConvetion_option5'] = 'Full Name (Year)';


$string['snamingConvetion'] = 'Short Name Format';
$string['snamingConvetionInstruction'] = 'Please choose a short name format for newly created courses';
$string['snamingConvetion_option1'] = 'Short Name Only';
$string['snamingConvetion_option2'] = 'Short Name - Mode';
$string['configure_EnrolmentKey'] = 'Enrolment Key';
$string['configure_EnrolmentKeyInstruction'] = 'Course Request Manager can generate an automatic enrolment key or you may choose to prompt the user for an enrolment key of their choice. If you choose to prompt the user, a field for entering the enrolment key will be added to the first page of the request form.';
$string['configure_defaultStartDate'] = 'Start Date';
$string['configure_defaultStartDateInstructions'] = 'Please select a default start date for new courses.';
$string['configure_delete'] = 'Are you sure you want to delete this request?';
$string['configure_deleteMail'] = 'Are you sure you want to delete this admin e-mail address?';
$string['configure_leaveblankmail'] = 'Leave this field blank to avoid a mail being sent.';
$string['configure_instruction1'] = 'Course Request Manager settings allows you to set administrator emails for communication, set default emails, set course naming conventions and default course settings such as start date and enrolment key policy.';
$string['configure_instruction2'] = 'The request form is split over two pages. Page one will prompt the user for the shortname and long name of the course to be created. This link will allow you to name those fields and also enable an optional course mode setting.';
$string['configure_instruction3'] = 'This allows you to configure the 2nd page of the request. On this page you can create form elements that must be completed by the user as part of the request process. While this information is not used by Course Request Manager, it will allow administrators to gather structured information on the request and aid in the decision making process.';



//email configuration strings
$string['emailConfigHeader'] = 'Here you can make changes to the E-mails which are sent to the users as notification when the status of their module has changed.';
$string['email_courseCode'] = 'Course code';
$string['email_courseName'] = 'Course name';
$string['email_progCode'] = 'Programme code';
$string['email_progName'] = 'Proramme name';
$string['email_enrolmentKey'] = 'Enrolment key';
$string['email_fullURL'] = 'Full URL to new course';
$string['email_sumLink'] = 'Full Course Request Manager request summary link';
$string['email_newCourseApproved'] = 'New Course Approved';
$string['email_UserMail'] = 'User E-Mail';
$string['email_AdminMail'] = 'Admin E-Mail';
$string['email_currentOwner'] = 'Current Owner E-Mail';
$string['email_requestNewModule'] = 'Request New Course';
$string['email_commentNotification'] = 'Comment Notification E-Mail';
$string['email_requestDenied'] = 'Request Denied E-Mail';
$string['email_handover'] = 'Handover Request';
$string['email_noReply'] = 'Communications Email Address';
$string['email_noReplyInstructions'] = 'This utility sends email requests to adminsitrators and end users as course requests are processed. Please enter the email address that these mails will appear to be sent from. It is recommended to use a NOREPLY email address. e.g. noreply@yourmoodlebox.com';

//email subjects and contents
$string['emailSubj_userApproved'] = "Moodle request approved!";
$string['emailSubj_adminApproved'] = "Moodle user request approved!";
$string['emailSubj_adminNewRequest'] = "New moodle request";
$string['emailSubj_userNewRequest'] = "New moodle request";
$string['emailSubj_userNewComment'] = "New Comment";
$string['emailSubj_adminNewComment'] = "New Comment";
$string['emailSubj_adminDeny'] = "Request Denied";
$string['emailSubj_userDeny'] = "Request Denied";
$string['emailSubj_teacherHandover'] = "Request for Control";
$string['emailSubj_From'] = "From";
$string['emailSubj_Comment'] = "Comment";
$string['emailSubj_pleasecontact'] = "Please Contact";
$string['emailSubj_mailSent1'] = "An email has been sent to";
$string['emailSubj_mailSent2'] = "on your behalf.";
$string['emailSubj_requester'] = "Requester E-mail";

//course request
$string['request_rule1'] = 'Please enter a value in this field.';
$string['request_rule2'] = 'Please select a value.';
$string['request_rule3'] = 'Please enter an enrolment key.';
$string['request_requestControl'] = 'Request Contol of this module';
$string['request_requestnewBlank'] = 'Request a new blank Module and the removal of this module';
$string['request_pleaseSelect'] = 'Please select course mode.';
$string['request_complete'] = 'Course Request Complete.';
$string['request_addModule'] = 'Add Module';

$string['changeshavebeensaved'] = 'Changes Saved!';
//form builder
$string['formBuilder_name'] = 'Configure Request Form - Page 2';
$string['formBuilder_instructions'] = 'The course request facility has been developed to allow users to enter additional information on course requests. This form can be configured to allow you to gather as much or as little information from the end user. If you require information from your users to assist you in creating a course, then configure the form here. Typical uses have included:';
$string['formBuilder_instructions1'] = 'Programme codes and titles';
$string['formBuilder_instructions2'] = 'Desired start and end dates';
$string['formBuilder_instructions3'] = 'Desired Enrolment keys';
$string['formBuilder_instructions4'] = 'Additional staff and permisions';
$string['formBuilder_instructions5'] = 'Course Format';
$string['formBuilder_instructions6'] = 'Many others...';
$string['formBuilder_currentActiveForm'] = 'Curent Active Form';
$string['formBuilder_currentActiveFormInstructions'] = 'Please select the form to use from the dropdown below. You can change the current active form at any time or create a new one and select it. ';
$string['formBuilder_selectDescription'] = 'Select form to use for requests';
$string['formBuilder_selectOption'] = 'Select form..';
$string['formBuilder_manageFormsText'] = 'Manage Forms';
$string['formBuilder_createNewText'] = 'Create a new form';
$string['formBuilder_selectAny'] = 'Select any of the forms below to begin editing.';
$string['formBuilder_p2_error'] = 'Error: No Id added.';
$string['formBuilder_p2_header'] = 'Additional Information Form Editor';
$string['formBuilder_p2_addNewField'] = 'Add new field';
$string['formBuilder_p2_dropdown1'] = 'Add new..';
$string['formBuilder_p2_dropdown2'] = 'Text Field';
$string['formBuilder_p2_dropdown3'] = 'Text Area';
$string['formBuilder_p2_dropdown4'] = 'Radio Button Group';
$string['formBuilder_p2_dropdown5'] = 'Drop Down Menu';
$string['formBuilder_p2_dropdown6']  = 'Text';
$string['formBuilder_p2_instructions'] = 'This form can be configured to allow you to gather as much or as little information from the end user. If you require information from your users to assit you in creating a course, then conigure the form here.Simply add or remove fields which the user will complete during a course request. This information can then assist you when dealing with requests.';
$string['formBuilder_about'] = 'About';
$string['formBuilder_editForm'] = 'Edit Form';
$string['formBuilder_deleteForm'] = 'Delete Form';
$string['formBuilder_previewForm'] = 'Preview Form';
$string['formBuilder_confirmDelete'] = 'Do you want to delete this form?';
$string['formBuilder_returntoCM'] = 'Return Course Request Manager';
$string['formBuilder_dropdownTxt'] = 'DropDown Menu';
$string['formBuilder_radioTxt'] = 'Radio Buttons';
$string['formBuilder_textAreaTxt'] = 'Text Area';
$string['formBuilder_textFieldTxt'] = 'Text Field';
$string['formBuilder_addUserTxt']  = 'Text';
$string['formBuilder_leftTxt'] = 'Name';
$string['formBuilder_saveTxt'] = 'Save';
$string['formBuilder_addedItemsTxt'] = 'The following items are listed in this component';
$string['formBuilder_addItemBtnTxt'] = 'Add new item';
$string['formBuilder_editingForm']  = 'Editing Form';
$string['formBuilder_shownbelow']  = 'is shown below';




//preview form page
$string['formBuilder_previewHeader'] = 'Preview Form';
$string['formBuilder_previewInstructions1'] = 'Please complete this form as accurately as possible.';
$string['formBuilder_previewInstructions2'] = 'Please consult local guidelines for making course requests';
$string['formBuilder_step2'] = 'Step 2: Other Details';

//Configure Course Search Form Fields
$string['formfieldsHeader'] = 'Configure Request Form - Page 1';
$string['entryFields_instruction1'] = 'Configure the first page of the course request form. The first page of the request form is used to accept values from the user for the course short name and the course full name as required by moodle. These may be described differently by your orgaisation. For example you may use a course code (shortname) and a course name (full name) to describe your courses.';
$string['entryFields_instruction2'] = 'For each of the two fields below, you may change the name of the field as it appears to the user and also the help text that is associated with each field.';
$string['entryFields_TextfieldOne'] = 'Configure field for course short name';
$string['entryFields_TextfieldTwo'] = 'Configure field for course full name';
$string['entryFields_Name'] = 'Name';
$string['entryFields_Description'] = 'Description';
$string['entryFields_status'] = 'Status';
$string['entryFields_Dropdown'] = 'Optional Dropdown Field';
$string['entryFields_DropdownDescription'] = ' You may wish to add an optional drop down list with some values that will help you categorise the new course. For example your organisation may offer courses in full time mode, part time mode, distance education mode, online only mode etc. You can add these options to the optional dropdown list and allow users to select one when making a new course request.';
$string['entryFields_AddNewItem'] = 'Add New Item';

//module_manager
$string['cmanager'] = 'CRManager';
$string['cmanagerDisplay'] = 'Course Request Manager';
$string['cmanagerDisplaySearchForm'] = 'Configure Request Form - Page 1';
$string['cmanagerWelcome'] = 'Welcome to moodle Course Request Manager. Before requesting a new course, please check your local guidelines.';
$string['cmanagerRequestBtn'] = 'Request a new course setup';
$string['cmanagerExstingTab'] = 'Existing Requests';
$string['cmanagerHistoryTab'] = 'Request History';
$string['cmanagerActions'] = 'Actions';
$string['cmanagerConfirmCancel'] = 'Are you sure you want to cancel this request?';
$string['cmanagernonePending'] = 'Sorry, nothing pending!';
$string['cmanagerEnrolmentInstruction'] = 'Course Request Manager can generate an automatic enrolment key or you may choose to prompt the user for an enrolment key of their choice.';
$string['cmanagerEnrolmentOption1'] = 'Automatically generated key';
$string['cmanagerEnrolmentOption2'] = 'Prompt user for key';
$string['cmanagerEnrolmentOption3'] = 'Do not ask for key';

$string['deleteAllRequests'] = 'Delete All Current and Archived Requests';
$string['deleteOnlyArch'] = 'Delete Only Archived Requests';
$string['clearHistoryTitle'] = 'Clear History';
$string['allowSelfCategorization'] = 'Allow User to Select Category';
$string['allowSelfCategorization_desc'] = 'When enabled, the user will be prompted to select a location in the Moodle catalogue to place their course';
$string['selfCatOn'] = 'Self Categorization On';
$string['selfCatOff'] = 'Self Categorization Off';

$string['sureDeleteAll'] = 'Are you sure you want to delete ALL history?';
$string['sureOnlyArch'] = 'Are you sure you want to delete only archived records?';
$string['yesDeleteRecords'] = 'Yes Delete';
$string['recordsHaveBeenDeleted'] = 'Records have been deleted';
$string['clickHereToReturn'] = 'Click here to return';

$string['selectedcategory'] = 'Category';


//request details
$string['requestReview_Summary'] = 'Request Summary';
$string['requestReview_intro1'] = 'Please review the following information carefully before submitting your request.';
$string['requestReview_intro2'] = 'Your request will be dealt with as soon as possible.';
$string['requestReview_status'] = 'STATUS';

$string['requestReview_requestType'] = 'Request Type';
$string['requestReview_moduleCode'] = 'Course Code';
$string['requestReview_moduleName'] = 'Course Name';
$string['requestReview_originator'] = 'Originator';



$string['requestReview_SubmitRequest'] = 'Submit Request';
$string['requestReview_AlterRequest'] = 'Alter Request';
$string['requestReview_CancelRequest'] = 'Cancel Request';
$string['requestReview_creationDate'] = 'Creation Date';
$string['requestReview_requestType'] = 'Request Type';

$string['requestReview_OpenDetails'] = 'Open Details';
$string['requestReview_ApproveRequest'] = 'Approve Request';
$string['requestReview_ApproveRequest'] = 'Approve Request';

$string['requestReview_courseName'] = 'Course Name';
$string['requestReview_courseCode'] = 'Course Code';



//comments
$string['comments_date'] = 'Date / Time';
$string['comments_message'] = 'Message';
$string['comments_from'] = 'From';
$string['comments_Header'] = 'Add / View Comments';
$string['comments_Forward'] = 'All comments will automatically be forwarded by email also';
$string['comments_PostComment'] = 'Post Comment';


//deny request
$string['denyrequest_Title'] = 'Course Request Facility - Deny Request';
$string['denyrequest_Instructions'] = 'Outline below why the request has been denied';
$string['denyrequest_Btn'] = 'Deny Request';
$string['denyrequest_reason'] = 'Outline below why the request has been denied (max 280 chars)';

//approve request
$string['approverequest_Title'] = 'Course Request Facility - Approve Request';
$string['approverequest_New'] = 'New course has been created';
$string['approverequest_Process'] = 'Handover process has begun';


//misc
$string['noPending'] = 'Sorry, nothing pending!';
$string['status'] = 'STATUS';
$string['creationdate'] = 'Creation Date';
$string['requesttype'] = 'Request Type';
$string['originator'] = 'Originator';
$string['comments'] = 'Comments';
$string['bulkactions'] = 'Bulk Actions';
$string['withselectedrequests'] = 'with selected requests';
$string['existingrequests'] = 'Existing Requests';
$string['actions'] = 'Actions';
$string['currentrequests'] = 'Current Requests';
$string['archivedrequests'] = 'Archived Requests';
$string['myarchivedrequests'] = 'My Archived Requests';
$string['allarchivedrequests'] = 'All Archived Requests';


$string['configure'] = 'Configure Course Request Manager';
$string['courserequestline1'] = 'Please refer to in-house guidelines for naming courses.';
$string['courserequestadmin'] = 'Course Request Administration';
$string['configureHeader'] = 'Course Request Facility - CManager Configuration';
$string['approve'] = 'Approve';
$string['deny'] = 'Deny';
$string['edit'] = 'Edit';
$string['cancel'] = 'Cancel';
$string['delete'] = 'Delete';
$string['view'] = 'View';
$string['viewmore'] = 'View More';
$string['addviewcomments'] = 'Add / View Comments';
$string['configurecoursemanagersettings'] = ' Configure Course Request Manager Settings';
$string['configurecourseformfields'] = '  Configure Request Form - Page 1';
$string['informationform'] = ' Configure Request Form - Page 2';
$string['modrequestfacility'] = 'Course Request Facility';
$string['step1text'] = 'Step 1: Course Request Details';
$string['modexists'] = 'It looks like the course you are requesting already exists on the server.';
$string['modcode'] = 'Course Code';
$string['modname'] = 'Course Name';
$string['catlocation'] = 'Catalogue Location';
$string['lecturingstaff'] = 'Lecturing Staff';
$string['actions'] = 'Actions';
$string['noneofthese'] = 'None of these? Continue making a new course';
$string['sendrequestforcontrol'] = 'Send request for control';
$string['sendrequestemail'] = 'Send Request E-Mail';
$string['emailswillbesent'] = 'E-mails will be sent to the owner of the course. Once you send a request, Please wait for a response.';

// View summary.php
$string['viewsummary'] = 'View Summary';

// Comment.php
$string['addviewcomments'] = 'Add / View Comments';

// Approve_course.php
$string['approvecourse'] = 'Approve Course';



// deny_course.php
$string['denycourse'] = 'Deny Course Request';

// Bulk Deny
$string['bulkdeny'] = 'Bulk Deny';

// Bulk Approve
$string['bulkapprove'] = 'Bulk Approve';
$string['approvingcourses'] = 'Approving Courses....';

// block_cmanager_config.php
$string['managersettings'] = 'Manager Settings';


// Form Page 1 & Page2
$string['formpage1'] = 'Form Page 1';
$string['formpage2'] = 'Form Page 2';
$string['formpage2builder'] = 'Form Page 2 Builder';


// Preview form
$string['previewform'] = 'Preview Form';

// course_exists.php
$string['courseexists'] = 'Course Exists';

// Request control.php
$string['requestcontrol'] = 'Request Control';


// History record management
$string['historynav'] = 'History';


// Search Feature
$string['searchAuthor'] = 'Author';
$string['search_side_text'] = 'Search';
$string['searchbuttontext'] = 'Search!';

// Quick approve
$string['quickapprove'] = 'Quick Approve';
$string['quickapprove_desc'] = 'Quick Approve this course?';

// Email and other settings

$string['configureemailsettings'] = 'Configure E-Mail Settings';
$string['configureemailsettings_desc'] = 'This section allows you to configure the e-mail settings for this tool';


$string['configureadminsettings'] = 'Admin Settings';
$string['configureadminsettings_desc'] = 'Addition additional settings for Course Request Manager';

$string['required_field'] = 'Required Field';
$string['optional_field'] = 'Optional Field';

$string['cmanager:myaddinstance'] = 'Add Instance';
$string['cmanager:addinstance'] = 'Add Instance';

// displayLists
$string['displayListWarningTitle'] = 'WARNING';
$string['displayListWarningSideText'] = 'This shortname already exists in the moodle database. Admin attention required. This request is excluded from bulk actions.';

$string['nocatselected'] = 'Sorry no catgory has been selected for this course';

$string['customdeny'] = 'Denial Text Templates';
$string['customdenydesc'] = 'Administrators may deny course requests for a number of reasons. Outlining the reason for a denial in an email can be time consuming. This feature lets you create up to five reasons which can be quickly selected during the denial process. Max 250 chars';
$string['customdenyfiller'] = 'You may enter a denial reason here (max 250 chars)';
$string['denytext1'] = 'Reason 1';
$string['denytext2'] = 'Reason 2';
$string['denytext3'] = 'Reason 3';
$string['denytext4'] = 'Reason 4';
$string['denytext5'] = 'Reason 5';


// Error messages
$string['cannotrequestcourse'] = ' Sorry your account does not have sufficient privelages to request a course. You need to be assigned to a system role with sufficient privileges.';
$string['cannotviewrecords'] = ' Sorry your account does not have sufficient privelages to view records. You need to be assigned to a system role with sufficient privileges.';
$string['cannotapproverecord'] = ' Sorry your account does not have sufficient privelages to approve records. You need to be assigned to a system role with sufficient privileges.';
$string['cannoteditrequest'] = ' Sorry your account does not have sufficient privelages to edit a record. You need to be assigned to a system role with sufficient privileges.';
$string['cannotcomment'] = ' Sorry yyour account does not have sufficient privelages to comment. You need to be assigned to a system role with sufficient privileges.';
$string['cannotdelete'] = ' Sorry your account does not have sufficient privelages to delete a record. You need to be assigned to a system role with sufficient privileges.';
$stirng['cannotdenyrecord'] = ' Sorry your account does not have sufficient privelages to deny a record. You need to be assigned to a system role with sufficient privileges.';
$string['cannotviewconfig'] = ' Sorry your account does not have sufficient privelages to view the config. You need to be assigned to a system role with sufficient privileges.';

$string['cmanager:addcomment'] = 'Add comment';
$string['cmanager:addrecord'] = 'Add Record';
$string['cmanager:approverecord'] = 'Approve Record';
$string['cmanager:deleterecord'] = 'Delete Record';
$string['cmanager:denyrecord'] = 'Deny Record';
$string['cmanager:editrecord'] = 'Edit Record';
$string['cmanager:viewrecord'] = 'View Record';
$string['cmanager:viewconfig'] = 'View Config';



?>