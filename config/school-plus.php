<?php
defined('SITEADMIN_ID') or define('SITEADMIN_ID', 1);

//login
defined('LOGNAME_LOGIN') or define('LOGNAME_LOGIN','login');

//alumni
defined('LOGNAME_ADD_ALUMNI') or define('LOGNAME_ADD_ALUMNI','addalumni');
defined('LOGNAME_EDIT_ALUMNI') or define('LOGNAME_EDIT_ALUMNI','editalumni');
defined('LOGNAME_EDIT_ALUMNI_CONTACT') or define('LOGNAME_EDIT_ALUMNI_CONTACT','editalumnicontact');
defined('LOGNAME_EDIT_ALUMNI_SOCIAL') or define('LOGNAME_EDIT_ALUMNI_SOCIAL','editalumnisocial');
defined('LOGNAME_EDIT_ALUMNI_EDUCATION') or define('LOGNAME_EDIT_ALUMNI_EDUCATION','editalumnieducation');

//academic year
defined('LOGNAME_ADD_ACADEMIC_YEAR') or define('LOGNAME_ADD_ACADEMIC_YEAR','addacademicyear');
defined('LOGNAME_EDIT_ACADEMIC_YEAR') or define('LOGNAME_EDIT_ACADEMIC_YEAR','editacademicyear');
defined('LOGNAME_UPDATE_STATUS_ACADEMIC_YEAR') or define('LOGNAME_UPDATE_STATUS_ACADEMIC_YEAR','updateacademicyearstatus');
defined('LOGNAME_DELETE_ACADEMIC_YEAR') or define('LOGNAME_DELETE_ACADEMIC_YEAR','deleteacademicyear');

//holiday
defined('LOGNAME_ADD_HOLIDAY') or define('LOGNAME_ADD_HOLIDAY','addholiday');
defined('LOGNAME_EDIT_HOLIDAY') or define('LOGNAME_EDIT_HOLIDAY','editholiday');

//church detail
defined('LOGNAME_ADD_SCHOOL_DETAIL') or define('LOGNAME_ADD_SCHOOL_DETAIL','addschooldetail');
defined('LOGNAME_EDIT_SCHOOL_DETAIL') or define('LOGNAME_EDIT_SCHOOL_DETAIL','editschooldetail');

//standard
defined('LOGNAME_ADD_STANDARD_SETUP') or define('LOGNAME_ADD_STANDARD_SETUP','addstandardsetup');
defined('LOGNAME_ADD_STANDARD') or define('LOGNAME_ADD_STANDARD','addstandard');
defined('LOGNAME_UPDATE_STATUS_STANDARD') or define('LOGNAME_UPDATE_STATUS_STANDARD','updatestandard');
defined('LOGNAME_DELETE_STANDARD') or define('LOGNAME_DELETE_STANDARD','deletestandard');

//section
defined('LOGNAME_ADD_SECTION') or define('LOGNAME_ADD_SECTION','addsection');
defined('LOGNAME_UPDATE_STATUS_SECTION') or define('LOGNAME_UPDATE_STATUS_SECTION','updatesection');
defined('LOGNAME_DELETE_SECTION') or define('LOGNAME_DELETE_SECTION','deletesection');

//standard
defined('LOGNAME_ADD_STANDARD_DETAIL') or define('LOGNAME_ADD_STANDARD_DETAIL','addstandarddetail');
defined('LOGNAME_EDIT_STANDARD_DETAIL') or define('LOGNAME_EDIT_STANDARD_DETAIL','editstandarddetail');
defined('LOGNAME_UPDATE_STATUS_STANDARD_DETAIL') or define('LOGNAME_UPDATE_STATUS_STANDARD_DETAIL','updatestandarddetail');
defined('LOGNAME_DELETE_STANDARD_DETAIL') or define('LOGNAME_DELETE_STANDARD_DETAIL','deletestandarddetail');

//parent
defined('LOGNAME_ADD_PARENT') or define('LOGNAME_ADD_PARENT','addparent');
defined('LOGNAME_EDIT_PARENT') or define('LOGNAME_EDIT_PARENT','editparent');
defined('LOGNAME_DELETE_PARENT') or define('LOGNAME_DELETE_PARENT','deleteparent');

//teacher
defined('LOGNAME_ADD_TEACHER') or define('LOGNAME_ADD_TEACHER','addteacher');
defined('LOGNAME_EDIT_TEACHER') or define('LOGNAME_EDIT_TEACHER','editteacher');
defined('LOGNAME_DELETE_TEACHER') or define('LOGNAME_DELETE_TEACHER','deleteteacher');

//student
defined('LOGNAME_ADD_STUDENT') or define('LOGNAME_ADD_STUDENT','addstudent');
defined('LOGNAME_EDIT_STUDENT') or define('LOGNAME_EDIT_STUDENT','editstudent');
defined('LOGNAME_DELETE_STUDENT') or define('LOGNAME_DELETE_STUDENT','deletestudent');
defined('LOGNAME_EDIT_STUDENT_MEDICAL_HISTORY') or define('LOGNAME_EDIT_STUDENT_MEDICAL_HISTORY','editstudentmedicalhistory');
defined('LOGNAME_EXIT_MEMBER') or define('LOGNAME_EXIT_MEMBER','exitstudent');
defined('LOGNAME_MEMBER_STATUS') or define('LOGNAME_MEMBER_STATUS','changeuserstatus');

defined('LOGNAME_SEND_MESSAGE') or define('LOGNAME_SEND_MESSAGE','sendmessage');
defined('LOGNAME_RESET_PASSWORD') or define('LOGNAME_RESET_PASSWORD','userpasswordreset');
defined('LOGNAME_EMAIL_VERIFICATION') or define('LOGNAME_EMAIL_VERIFICATION','useremailverification');

//documents
defined('LOGNAME_ADD_DOCUMENT') or define('LOGNAME_ADD_DOCUMENT','adddocument');
defined('LOGNAME_EDIT_DOCUMENT') or define('LOGNAME_EDIT_DOCUMENT','editdocument');
defined('LOGNAME_DELETE_DOCUMENT') or define('LOGNAME_DELETE_DOCUMENT','deletedocument');

//admin
defined('LOGNAME_CHANGE_AVATAR') or define('LOGNAME_CHANGE_AVATAR','changeavatar');
defined('LOGNAME_CHANGE_PASSWORD') or define('LOGNAME_CHANGE_PASSWORD','changepassword');
defined('LOGNAME_EDIT_USERPROFILE') or define('LOGNAME_EDIT_USERPROFILE','edituserprofile');

//bulletin
defined('LOGNAME_ADD_MAGAZINE') or define('LOGNAME_ADD_MAGAZINE','addmagazine');
defined('LOGNAME_DELETE_MAGAZINE') or define('LOGNAME_DELETE_MAGAZINE','deletemagazine');
defined('LOGNAME_DOWNLOAD_MAGAZINE') or define('LOGNAME_DOWNLOAD_MAGAZINE','downloadmagazine');

//event
defined('LOGNAME_ADD_EVENT') or define('LOGNAME_ADD_EVENT','addevent');
defined('LOGNAME_EDIT_EVENT') or define('LOGNAME_EDIT_EVENT','editevent');
defined('LOGNAME_DELETE_EVENT') or define('LOGNAME_DELETE_EVENT','deleteevent');
defined('LOGNAME_EVENT_PHOTO') or define('LOGNAME_EVENT_PHOTO','eventphoto');

//homework
defined('LOGNAME_ADD_HOMEWORK') or define('LOGNAME_ADD_HOMEWORK','addhomework');
defined('LOGNAME_EDIT_HOMEWORK') or define('LOGNAME_EDIT_HOMEWORK','edithomework');
defined('LOGNAME_DELETE_HOMEWORK') or define('LOGNAME_DELETE_HOMEWORK','deletehomework');
defined('LOGNAME_ADD_HOMEWORK_ATTACHMENT') or define('LOGNAME_ADD_HOMEWORK_ATTACHMENT','addhomeworkattachment');
defined('LOGNAME_APPROVE_HOMEWORK') or define('LOGNAME_APPROVE_HOMEWORK','approvehomework');
defined('LOGNAME_REJECT_HOMEWORK') or define('LOGNAME_REJECT_HOMEWORK','rejecthomework');

//student homework
defined('LOGNAME_ADD_STUDENT_HOMEWORK') or define('LOGNAME_ADD_STUDENT_HOMEWORK','addstudenthomework');
defined('LOGNAME_DELETE_STUDENT_HOMEWORK') or define('LOGNAME_DELETE_STUDENT_HOMEWORK','deletestudenthomework');
defined('LOGNAME_CHECK_HOMEWORK') or define('LOGNAME_CHECK_HOMEWORK','checkstudenthomework');

//subject
defined('LOGNAME_ADD_SUBJECT') or define('LOGNAME_ADD_SUBJECT','addsubject');
defined('LOGNAME_EDIT_SUBJECT') or define('LOGNAME_EDIT_SUBJECT','editsubject');
defined('LOGNAME_DELETE_SUBJECT') or define('LOGNAME_DELETE_SUBJECT','deletesubject');

//noticeboard
defined('LOGNAME_ADD_NOTICE') or define('LOGNAME_ADD_NOTICE','addnotice');
defined('LOGNAME_EDIT_NOTICE') or define('LOGNAME_EDIT_NOTICE','editnotice');
defined('LOGNAME_DELETE_NOTICE') or define('LOGNAME_DELETE_NOTICE','deletenotice');

//fee
defined('LOGNAME_ADD_FEE') or define('LOGNAME_ADD_FEE','addfee');
defined('LOGNAME_EDIT_FEE') or define('LOGNAME_EDIT_FEE','editfee');
defined('LOGNAME_DELETE_FEE') or define('LOGNAME_DELETE_FEE','deletefee');

//feepayment
defined('LOGNAME_ADD_FEEPAYMENTDETAIL') or define('LOGNAME_ADD_FEEPAYMENTDETAIL','addfeepaymentdetail');
defined('LOGNAME_EDIT_FEEPAYMENTDETAIL') or define('LOGNAME_EDIT_FEEPAYMENTDETAIL','editfeepaymentdetail');
defined('LOGNAME_SEND_FEEPAYMENT_REMINDER') or define('LOGNAME_SEND_FEEPAYMENT_REMINDER','sendfeepaymentreminder');

//assignment
defined('LOGNAME_ADD_ASSIGNMENT') or define('LOGNAME_ADD_ASSIGNMENT','addassignment');
defined('LOGNAME_EDIT_ASSIGNMENT') or define('LOGNAME_EDIT_ASSIGNMENT','editassignment');
defined('LOGNAME_DELETE_ASSIGNMENT') or define('LOGNAME_DELETE_ASSIGNMENT','deleteassignment');
defined('LOGNAME_APPROVE_ASSIGNMENT') or define('LOGNAME_APPROVE_ASSIGNMENT','approveassignment');
defined('LOGNAME_REJECT_ASSIGNMENT') or define('LOGNAME_REJECT_ASSIGNMENT','rejectassignment');

//student assignment
defined('LOGNAME_ADD_STUDENT_ASSIGNMENT') or define('LOGNAME_ADD_STUDENT_ASSIGNMENT','addstudentassignment');
defined('LOGNAME_UPDATE_STUDENT_ASSIGNMENT') or define('LOGNAME_UPDATE_STUDENT_ASSIGNMENT','editstudentassignment');
defined('LOGNAME_DELETE_STUDENT_ASSIGNMENT') or define('LOGNAME_DELETE_STUDENT_ASSIGNMENT','deletestudentassignment');

//lesson-plan
defined('LOGNAME_ADD_LESSON_PLAN_1') or define('LOGNAME_ADD_LESSON_PLAN_1','addlessonplanstep1');
defined('LOGNAME_ADD_LESSON_PLAN_2') or define('LOGNAME_ADD_LESSON_PLAN_2','addlessonplanstep2');
defined('LOGNAME_ADD_LESSON_PLAN_3') or define('LOGNAME_ADD_LESSON_PLAN_3','addlessonplanstep3');
defined('LOGNAME_ADD_LESSON_PLAN_4') or define('LOGNAME_ADD_LESSON_PLAN_4','addlessonplanstep4');

defined('LOGNAME_EDIT_LESSON_PLAN_1') or define('LOGNAME_EDIT_LESSON_PLAN_1','editlessonplanstep1');
defined('LOGNAME_EDIT_LESSON_PLAN_2') or define('LOGNAME_EDIT_LESSON_PLAN_2','editlessonplanstep2');
defined('LOGNAME_EDIT_LESSON_PLAN_3') or define('LOGNAME_EDIT_LESSON_PLAN_3','editlessonplanstep3');
defined('LOGNAME_EDIT_LESSON_PLAN_4') or define('LOGNAME_EDIT_LESSON_PLAN_4','editlessonplanstep4');

defined('LOGNAME_PRINT_LESSON_PLAN') or define('LOGNAME_PRINT_LESSON_PLAN','printlessonplan');
defined('LOGNAME_DELETE_LESSON_PLAN') or define('LOGNAME_DELETE_LESSON_PLAN','deletelessonplan');
defined('LOGNAME_APPROVE_LESSON_PLAN') or define('LOGNAME_APPROVE_LESSON_PLAN','approvelessonplan');
defined('LOGNAME_REJECT_LESSON_PLAN') or define('LOGNAME_REJECT_LESSON_PLAN','rejectlessonplan');

//exam

defined('LOGNAME_ADD_EXAM') or define('LOGNAME_ADD_EXAM','addexam');
defined('LOGNAME_EDIT_EXAM') or define('LOGNAME_EDIT_EXAM','editexam');
defined('LOGNAME_DELETE_EXAM') or define('LOGNAME_DELETE_EXAM','deleteexam');

//exam-schedule

defined('LOGNAME_ADD_EXAM_SCHEDULE') or define('LOGNAME_ADD_EXAM_SCHEDULE','addexamschedule');

//timetable
defined('LOGNAME_ADD_TIMETABLE') or define('LOGNAME_ADD_TIMETABLE','addtimetable');
defined('LOGNAME_EDIT_TIMETABLE') or define('LOGNAME_EDIT_TIMETABLE','edittimetable');

//attendance
defined('LOGNAME_ADD_ATTENDANCE') or define('LOGNAME_ADD_ATTENDANCE','addattendance');
defined('LOGNAME_EXPORT_STUDENT_ATTENDANCE') or define('LOGNAME_EXPORT_STUDENT_ATTENDANCE','exportstudentattendance');

//notes
defined('LOGNAME_ADD_NOTE') or define('LOGNAME_ADD_NOTE','addnote');
defined('LOGNAME_DELETE_NOTE') or define('LOGNAME_DELETE_NOTE','deletenote');

//leavetypes
defined('LOGNAME_ADD_LEAVETYPE') or define('LOGNAME_ADD_LEAVETYPE','addleavetype');
defined('LOGNAME_EDIT_LEAVETYPE') or define('LOGNAME_EDIT_LEAVETYPE','editleavetype');
defined('LOGNAME_DELETE_LEAVETYPE') or define('LOGNAME_DELETE_LEAVETYPE','deleteleavetype');

//leave application
defined('LOGNAME_ADD_LEAVEAPPLICATION') or define('LOGNAME_ADD_LEAVEAPPLICATION','addleaveapplication');
defined('LOGNAME_EDIT_LEAVEAPPLICATION') or define('LOGNAME_EDIT_LEAVEAPPLICATION','editleaveapplication');
defined('LOGNAME_APPROVE_LEAVEAPPLICATION') or define('LOGNAME_APPROVE_LEAVEAPPLICATION','approveleaveapplication');
defined('LOGNAME_REJECT_LEAVEAPPLICATION') or define('LOGNAME_REJECT_LEAVEAPPLICATION','rejectleaveapplication');
defined('LOGNAME_DELETE_LEAVEAPPLICATION') or define('LOGNAME_DELETE_LEAVEAPPLICATION','deleteleaveapplication');

//import & export student
defined('LOGNAME_EXPORT_STUDENT') or define('LOGNAME_EXPORT_STUDENT','exportstudent');
defined('LOGNAME_IMPORT_STUDENT') or define('LOGNAME_IMPORT_STUDENT','importstudent');
defined('LOGNAME_DOWNLOAD_SAMPLE_FORMAT') or define('LOGNAME_DOWNLOAD_SAMPLE_FORMAT','downloadsampleformat');

//export teacher
defined('LOGNAME_EXPORT_TEACHER') or define('LOGNAME_EXPORT_TEACHER','exportteacher');
defined('LOGNAME_IMPORT_TEACHER') or define('LOGNAME_IMPORT_TEACHER','importteacher');
defined('LOGNAME_DOWNLOAD_SAMPLE_FORMAT_TEACHER') or define('LOGNAME_DOWNLOAD_SAMPLE_FORMAT_TEACHER','downloadsampleformatteacher');

//import& export mark
defined('LOGNAME_DOWNLOAD_SAMPLE_MARK') or define('LOGNAME_DOWNLOAD_SAMPLE_MARK','samplemarkformat');
defined('LOGNAME_IMPORT_MARK') or define('LOGNAME_IMPORT_MARK','importmark');
defined('LOGNAME_EXPORT_MARK') or define('LOGNAME_EXPORT_MARK','exportmark');

//file & video
defined('LOGNAME_ADD_FILE') or define('LOGNAME_ADD_FILE','addfile');
defined('LOGNAME_EDIT_FILE') or define('LOGNAME_EDIT_FILE','editfile');
defined('LOGNAME_ADD_VIDEO') or define('LOGNAME_ADD_VIDEO','addvideo');
defined('LOGNAME_DOWNLOAD_FILE') or define('LOGNAME_DOWNLOAD_FILE','downloadimage');
defined('LOGNAME_DELETE_FILE') or define('LOGNAME_DELETE_FILE','deletefile');

//dashboard
defined('LOGNAME_BIRTHDAY_REMINDER') or define('LOGNAME_BIRTHDAY_REMINDER','birthdayreminder'); 

//payment
defined('LOGNAME_PAYMENT') or define('LOGNAME_PAYMENT','paymentsuccessfull');

//discipline
defined('LOGNAME_ADD_DISCIPLINE') or define('LOGNAME_ADD_DISCIPLINE','adddiscipline');
defined('LOGNAME_EDIT_DISCIPLINE') or define('LOGNAME_EDIT_DISCIPLINE','editdiscipline');
defined('LOGNAME_DELETE_DISCIPLINE') or define('LOGNAME_DELETE_DISCIPLINE','deletediscipline');
defined('LOGNAME_EXPORT_DISCIPLINE') or define('LOGNAME_EXPORT_DISCIPLINE','exportdiscipline');

//report
defined('LOGNAME_EXPORT_FEE_PAYMENT') or define('LOGNAME_EXPORT_FEE_PAYMENT','feepaymentdetailsexported');
defined('LOGNAME_EXPORT_BIRTHDAY_STUDENT') or define('LOGNAME_EXPORT_BIRTHDAY_STUDENT','studentbirthdaydetailsexported');
defined('LOGNAME_EXPORT_BIRTHDAY_TEACHER') or define('LOGNAME_EXPORT_BIRTHDAY_TEACHER','teacherbirthdaydetailsexported');
defined('LOGNAME_IMPORT_HOLIDAY') or define('LOGNAME_IMPORT_HOLIDAY','importholiday');
defined('LOGNAME_EXPORT_HOLIDAY') or define('LOGNAME_EXPORT_HOLIDAY','exportholiday');
defined('LOGNAME_EXPORT_ACTIVE_STUDENT') or define('LOGNAME_EXPORT_ACTIVE_STUDENT','activestudentdetailsexported');
defined('LOGNAME_EXPORT_EXIT_STUDENT') or define('LOGNAME_EXPORT_EXIT_STUDENT','exitstudentdetailsexported');
defined('LOGNAME_EXPORT_SUSPENDED_STUDENT') or define('LOGNAME_EXPORT_SUSPENDED_STUDENT','suspendedstudentdetailsexported');
defined('LOGNAME_EXPORT_PARENT') or define('LOGNAME_EXPORT_PARENT','parentdetailsexported');
defined('LOGNAME_DOWNLOAD_HOLIDAY_SAMPLE_FORMAT') or define('LOGNAME_DOWNLOAD_HOLIDAY_SAMPLE_FORMAT','downloadholidaysampleformat');

//promotion
defined('LOGNAME_EXPORT_STUDENT_PROMOTION') or define('LOGNAME_EXPORT_STUDENT_PROMOTION','exportstudentpromotiondetails');
defined('LOGNAME_IMPORT_STUDENT_PROMOTION') or define('LOGNAME_IMPORT_STUDENT_PROMOTION','importstudentpromotiondetails');

//task
defined('LOGNAME_ADD_TASK') or define('LOGNAME_ADD_TASK','addtask');
defined('LOGNAME_EDIT_TASK') or define('LOGNAME_EDIT_TASK','edittask');
defined('LOGNAME_SNOOZE_TASK') or define('LOGNAME_SNOOZE_TASK','snoozetask');
defined('LOGNAME_MARK_TASK_COMPLETE') or define('LOGNAME_MARK_TASK_COMPLETE','marktaskcomplete');
defined('LOGNAME_DELETE_TASK') or define('LOGNAME_DELETE_TASK','deletetask');

//visitorlog
defined('LOGNAME_ADD_VISITOR_LOG') or define('LOGNAME_ADD_VISITOR_LOG','addvisitorlog');
defined('LOGNAME_EDIT_VISITOR_LOG') or define('LOGNAME_EDIT_VISITOR_LOG','editvisitorlog');
defined('LOGNAME_DELETE_VISITOR_LOG') or define('LOGNAME_DELETE_VISITOR_LOG','deletevisitorlog');
//calllog
defined('LOGNAME_ADD_CALL_LOG') or define('LOGNAME_ADD_CALL_LOG','addcalllog');
defined('LOGNAME_EDIT_CALL_LOG') or define('LOGNAME_EDIT_CALL_LOG','editcalllog');
defined('LOGNAME_DELETE_CALL_LOG') or define('LOGNAME_DELETE_CALL_LOG','deletecalllog');
//postalrecord
defined('LOGNAME_ADD_POSTAL_RECORD') or define('LOGNAME_ADD_POSTAL_RECORD','addpostalrecord');
defined('LOGNAME_EDIT_POSTAL_RECORD') or define('LOGNAME_EDIT_POSTAL_RECORD','editpostalrecord');
defined('LOGNAME_DELETE_POSTAL_RECORD') or define('LOGNAME_DELETE_POSTAL_RECORD','deletepostalrecord');
//library

//category
defined('LOGNAME_ADD_BOOK_CATEGORY') or define('LOGNAME_ADD_BOOK_CATEGORY', 'addbookcategory');
defined('LOGNAME_EDIT_BOOK_CATEGORY') or define('LOGNAME_EDIT_BOOK_CATEGORY','editbookcategory');
defined('LOGNAME_DELETE_BOOK_CATEGORY') or define('LOGNAME_DELETE_BOOK_CATEGORY','deletebookcategory');
//books
defined('LOGNAME_ADD_BOOK') or define('LOGNAME_ADD_BOOK','addbook');
defined('LOGNAME_EDIT_BOOK') or define('LOGNAME_EDIT_BOOK','editbook');
defined('LOGNAME_DELETE_BOOK') or define('LOGNAME_DELETE_BOOK','deletebook');

//book lending
defined('LOGNAME_ADD_BOOK_LENDING') or define('LOGNAME_ADD_BOOK_LENDING','addbooklending');
defined('LOGNAME_EDIT_BOOKLENDING') or define('LOGNAME_EDIT_BOOKLENDING','editbooklending');

//creating room
defined('LOGNAME_ADD_ROOM') or define('LOGNAME_ADD_ROOM','addroom');
defined('LOGNAME_EDIT_ROOM') or define('LOGNAME_EDIT_ROOM','editroom');
defined('LOGNAME_DELETE_ROOM') or define('LOGNAME_DELETE_ROOM','deleteroom');


defined('LOGNAME_ADD_MEMBER_TO_GROUP') or define('LOGNAME_ADD_MEMBER_TO_GROUP','addroommember');
defined('LOGNAME_REMOVE_GROUP_MEMBER') or define('LOGNAME_REMOVE_GROUP_MEMBER','deleteroommember');

//video room
defined('LOGNAME_CREATE_VIDEO_ROOM') or define('LOGNAME_CREATE_VIDEO_ROOM','createvideoroom');
defined('LOGNAME_EDIT_VIDEO_ROOM') or define('LOGNAME_EDIT_VIDEO_ROOM','editvideoroom');
defined('LOGNAME_DELETE_VIDEO_ROOM') or define('LOGNAME_DELETE_VIDEO_ROOM','deletevideoroom');

//student quiz
defined('LOGNAME_ADD_QUIZ_TEST_ANSWER') or define('LOGNAME_ADD_QUIZ_TEST_ANSWER','addquiztestanswer');
defined('LOGNAME_ADD_STUDENT_QUIZ_TEST') or define('LOGNAME_ADD_STUDENT_QUIZ_TEST','addstudentquiztest');

//teacher quiz
defined('LOGNAME_ADD_QUIZ_PARTICIPANT') or define('LOGNAME_ADD_QUIZ_PARTICIPANT','addquizparticipant');
defined('LOGNAME_DELETE_QUIZ_PARTICIPANT') or define('LOGNAME_DELETE_QUIZ_PARTICIPANT','deletequizparticipant');
defined('LOGNAME_ADD_QUIZ_TOPIC') or define('LOGNAME_ADD_QUIZ_TOPIC','addquiztopic');
defined('LOGNAME_EDIT_QUIZ_TOPIC') or define('LOGNAME_EDIT_QUIZ_TOPIC','editquiztopic');
defined('LOGNAME_DELETE_QUIZ_TOPIC') or define('LOGNAME_DELETE_QUIZ_TOPIC','deletequiztopic');
defined('LOGNAME_APPROVE_QUIZ') or define('LOGNAME_APPROVE_QUIZ','approvequiztopic');
defined('LOGNAME_REJECT_QUIZ') or define('LOGNAME_REJECT_QUIZ','rejectquiztopic');

defined('LOGNAME_ADD_QUIZ_OPTION') or define('LOGNAME_ADD_QUIZ_OPTION','addquizoption');
defined('LOGNAME_ADD_QUIZ_QUESTION') or define('LOGNAME_ADD_QUIZ_QUESTION','addquizquestion');
defined('LOGNAME_EDIT_QUIZ_OPTION') or define('LOGNAME_EDIT_QUIZ_OPTION','editquizoption');
defined('LOGNAME_EDIT_QUIZ_QUESTION') or define('LOGNAME_EDIT_QUIZ_QUESTION','editquizquestion');

//category
defined('LOGNAME_ADD_CATEGORY') or define('LOGNAME_ADD_CATEGORY','addcategory');
defined('LOGNAME_EDIT_CATEGORY') or define('LOGNAME_EDIT_CATEGORY','editcategory');
defined('LOGNAME_DELETE_CATEGORY') or define('LOGNAME_DELETE_CATEGORY','deletecategory');

//category
defined('LOGNAME_ADD_SUB_CATEGORY') or define('LOGNAME_ADD_SUB_CATEGORY','addsubcategory');
defined('LOGNAME_EDIT_SUB_CATEGORY') or define('LOGNAME_EDIT_SUB_CATEGORY','editsubcategory');
defined('LOGNAME_DELETE_SUB_CATEGORY') or define('LOGNAME_DELETE_SUB_CATEGORY','deletesubcategory');

//location
defined('LOGNAME_ADD_LOCATION') or define('LOGNAME_ADD_LOCATION','addlocation');
defined('LOGNAME_EDIT_LOCATION') or define('LOGNAME_EDIT_LOCATION','editlocation');
defined('LOGNAME_DELETE_LOCATION') or define('LOGNAME_DELETE_LOCATION','deletelocation');

//location product
defined('LOGNAME_ADD_PRODUCT') or define('LOGNAME_ADD_PRODUCT','addproduct');
defined('LOGNAME_EDIT_PRODUCT') or define('LOGNAME_EDIT_PRODUCT','editproduct');
defined('LOGNAME_DELETE_PRODUCT') or define('LOGNAME_DELETE_PRODUCT','deleteproduct');

//location ownership
defined('LOGNAME_ADD_PRODUCT_OWNERSHIP') or define('LOGNAME_ADD_PRODUCT_OWNERSHIP','addproductownership');
defined('LOGNAME_EDIT_PRODUCT_OWNERSHIP') or define('LOGNAME_EDIT_PRODUCT_OWNERSHIP','editproductownership');
defined('LOGNAME_DELETE_PRODUCT_OWNERSHIP') or define('LOGNAME_DELETE_PRODUCT_OWNERSHIP','deleteproductownership');

//product code
defined('LOGNAME_ADD_PRODUCT_CODE') or define('LOGNAME_ADD_PRODUCT_CODE','addproductcode');

//location product
defined('LOGNAME_ADD_LOCATION_PRODUCT') or define('LOGNAME_ADD_LOCATION_PRODUCT','addlocationproduct');
defined('LOGNAME_EDIT_LOCATION_PRODUCT') or define('LOGNAME_EDIT_LOCATION_PRODUCT','editlocationproduct');
defined('LOGNAME_DELETE_LOCATION_PRODUCT') or define('LOGNAME_DELETE_LOCATION_PRODUCT','deletelocationproduct');

//location product
defined('LOGNAME_ADD_PRODUCT_CONDITION') or define('LOGNAME_ADD_PRODUCT_CONDITION','addproductcondition');
defined('LOGNAME_EDIT_PRODUCT_CONDITION') or define('LOGNAME_EDIT_PRODUCT_CONDITION','editproductcondition');
defined('LOGNAME_DELETE_PRODUCT_CONDITION') or define('LOGNAME_DELETE_PRODUCT_CONDITION','deleteproductcondition');

//vendor
defined('LOGNAME_ADD_VENDOR') or define('LOGNAME_ADD_VENDOR','addvendor');
defined('LOGNAME_EDIT_VENDOR') or define('LOGNAME_EDIT_VENDOR','editvendor');
defined('LOGNAME_DELETE_VENDOR') or define('LOGNAME_DELETE_VENDOR','deletevendor');
 
//vendor category
defined('LOGNAME_ADD_CATEGORY_VENDOR') or define('LOGNAME_ADD_CATEGORY_VENDOR','addcategoryvendor');
defined('LOGNAME_DELETE_CATEGORY_VENDOR') or define('LOGNAME_DELETE_CATEGORY_VENDOR','deletecategoryvendor');

//page category
defined('LOGNAME_ADD_PAGE_CATEGORY') or define('LOGNAME_ADD_PAGE_CATEGORY', 'addpagecategory');

//page
defined('LOGNAME_ADD_PAGE') or define('LOGNAME_ADD_PAGE','addpage');
defined('LOGNAME_EDIT_PAGE') or define('LOGNAME_EDIT_PAGE','editpage');
defined('LOGNAME_DELETE_PAGE') or define('LOGNAME_DELETE_PAGE','deletepage');

defined('LOGNAME_ADD_PAGE_ATTACHMENT') or define('LOGNAME_ADD_PAGE_ATTACHMENT','addpageattachment');
defined('LOGNAME_DELETE_PAGE_ATTACHMENT') or define('LOGNAME_DELETE_PAGE_ATTACHMENT','deletepageattachment');

defined('LOGNAME_FOLLOW_PAGE') or define('LOGNAME_FOLLOW_PAGE','followpage');
defined('LOGNAME_LIKE_PAGE') or define('LOGNAME_LIKE_PAGE','likepage');
defined('LOGNAME_UNLIKE_PAGE') or define('LOGNAME_UNLIKE_PAGE','unlikepage');

//post
defined('LOGNAME_ADD_POST') or define('LOGNAME_ADD_POST','addpost');
defined('LOGNAME_EDIT_POST') or define('LOGNAME_EDIT_POST','editpost');
defined('LOGNAME_DELETE_POST') or define('LOGNAME_DELETE_POST','deletepost');
defined('LOGNAME_LIKE_POST') or define('LOGNAME_LIKE_POST','likepost');
defined('LOGNAME_UNLIKE_POST') or define('LOGNAME_UNLIKE_POST','unlikepost');
defined('LOGNAME_SAVE_POST') or define('LOGNAME_SAVE_POST','savepost');
defined('LOGNAME_UNSAVE_POST') or define('LOGNAME_UNSAVE_POST','unsavepost');

defined('LOGNAME_ADD_COMMENT') or define('LOGNAME_ADD_COMMENT','addcomment');
defined('LOGNAME_EDIT_COMMENT') or define('LOGNAME_EDIT_COMMENT','editcomment');
defined('LOGNAME_DELETE_COMMENT') or define('LOGNAME_DELETE_COMMENT','deletecomment');
defined('LOGNAME_LIKE_COMMENT') or define('LOGNAME_LIKE_COMMENT','likecomment');
defined('LOGNAME_UNLIKE_COMMENT') or define('LOGNAME_UNLIKE_COMMENT','unlikecomment');

defined('LOGNAME_ADD_REPLY_COMMENT') or define('LOGNAME_ADD_REPLY_COMMENT','addreplycomment');
defined('LOGNAME_EDIT_REPLY_COMMENT') or define('LOGNAME_EDIT_REPLY_COMMENT','editreplycomment');
defined('LOGNAME_DELETE_REPLY_COMMENT') or define('LOGNAME_DELETE_REPLY_COMMENT','deletereplycomment');
defined('LOGNAME_LIKE_REPLY_COMMENT') or define('LOGNAME_LIKE_REPLY_COMMENT','likereplycomment');
defined('LOGNAME_UNLIKE_REPLY_COMMENT') or define('LOGNAME_UNLIKE_REPLY_COMMENT','unlikereplycomment');

defined('LOGNAME_ADD_REPLY_COMMENT') or define('LOGNAME_ADD_REPLY_COMMENT','addreplycomment');
defined('LOGNAME_EDIT_REPLY_COMMENT') or define('LOGNAME_EDIT_REPLY_COMMENT','editreplycomment');
defined('LOGNAME_DELETE_REPLY_COMMENT') or define('LOGNAME_DELETE_REPLY_COMMENT','deletereplycomment');
defined('LOGNAME_LIKE_REPLY_COMMENT') or define('LOGNAME_LIKE_REPLY_COMMENT','likereplycomment');
defined('LOGNAME_UNLIKE_REPLY_COMMENT') or define('LOGNAME_UNLIKE_REPLY_COMMENT','unlikereplycomment');

defined('LOGNAME_UPDATE_FEEDBACK_STATUS') or define('LOGNAME_UPDATE_FEEDBACK_STATUS','editfeedbackstatus');

defined('LOGNAME_CHANGE_CREDENTIALS') or define('LOGNAME_CHANGE_CREDENTIALS','User Credentials Changed');

defined('LOGNAME_REPLY_STUDENT_HOMEWORK') or define('LOGNAME_REPLY_STUDENT_HOMEWORK','ReplyHomeworkComment');

defined('LOGNAME_UPDATE_EXAM_GRADE') or define('LOGNAME_UPDATE_EXAM_GRADE','UpdateExamGrade');

defined('LOGNAME_ADD_FEEDBACK') or define('LOGNAME_ADD_FEEDBACK','FeedBack');

//new
defined('LOGNAME_ADD_ADMISSION_FORM') or define('LOGNAME_ADD_ADMISSION_FORM','AddAdmission');

defined('LOGNAME_PRINT_VISITOR_LOG') or define('LOGNAME_PRINT_VISITOR_LOG','printVisitorLog');

defined('LOGNAME_ADD_FEE_GROUP') or define('LOGNAME_ADD_FEE_GROUP','addFeeGroup');

defined('LOGNAME_ASSIGN_FEEPAYMENT') or define('LOGNAME_ASSIGN_FEEPAYMENT','assignedFeePayment');

defined('LOGNAME_UPDATE_FEEPAYMENT_AMOUNT') or define('LOGNAME_UPDATE_FEEPAYMENT_AMOUNT','updatedfeepaymentamount');

defined('LOGNAME_ADD_FEEPAYMENT_AMOUNT') or define('LOGNAME_ADD_FEEPAYMENT_AMOUNT','addfeepaymentamount');

defined('LOGNAME_EXPORT_CURRENT_STOCK') or define('LOGNAME_EXPORT_CURRENT_STOCK','exportCurrentStock');

defined('LOGNAME_EXPORT_MONTHLY_PURCHASE') or define('LOGNAME_EXPORT_MONTHLY_PURCHASE','exportMonthlyStock');

defined('LOGNAME_EXPORT_MONTHLY_SALES') or define('LOGNAME_EXPORT_MONTHLY_SALES','exportMonthlySale');

defined('LOGNAME_RESET_FEEPAYMENT') or define('LOGNAME_RESET_FEEPAYMENT','resetFeePayment');

defined('LOGNAME_ADD_telephonedirectory') or define('LOGNAME_ADD_telephonedirectory','addtelephonedirectory');

defined('LOGNAME_EDIT_telephonedirectory') or define('LOGNAME_EDIT_telephonedirectory','updatetelephonedirectory');

defined('LOGNAME_DELETE_telephonedirectory') or define('LOGNAME_DELETE_telephonedirectory','deletetelephonedirectory');

defined('LOGNAME_UPDATE_ADMISSION_FORM') or define('LOGNAME_UPDATE_ADMISSION_FORM','updateAdmissionform');

defined('CURRENCY_SYMBOL') or define('CURRENCY_SYMBOL','₹');

defined('LOGNAME_DOWNLOAD_SAMPLE_FORMAT_LIBRARYCARD') or define('LOGNAME_DOWNLOAD_SAMPLE_FORMAT_LIBRARYCARD','downloadsampleformatlibrary');

defined('LOGNAME_DELETE_HOLIDAY') or define('LOGNAME_DELETE_HOLIDAY','deleteholiday');






