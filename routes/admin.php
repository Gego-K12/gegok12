<?php

include ('addon.php');


Route::get( '/dashboard', 'DashboardController@index' )->name( 'dashboard' );
Route::get( '/dashboard/event', 'DashboardController@event' );
Route::get( '/dashboard/structuralList', 'DashboardController@structuralList' );
Route::post( '/dashboard/structuralList', 'DashboardController@showStructuralList' );
Route::get( '/dashboard/unpaidList/{fee_id}', 'DashboardController@unpaidList' );
Route::get( '/dashboard/feeslist/{fee_id}', 'DashboardController@feeslist' );
Route::get( '/dashboard/unpaidfees/{fee_id}', 'DashboardController@show' );
Route::post( '/dashboard/send/reminder/{fee_id}', 'DashboardController@sendReminder' );
Route::get( '/dashboard/tasklist/{task_flag}','DashboardController@list' );
Route::get( '/dashboard/task/count','DashboardController@listCount' );

//admission
Route::get(	'/admissionlist','AdmissionController@admissionlist' );
Route::get(	'/admissions','AdmissionController@index' );
Route::get( '/admission/show/{id}', 'AdmissionController@show' );
Route::get( '/admission/edit/{id}', 'AdmissionController@edit' );
Route::post( '/admission/update/{id}', 'AdmissionController@update' );
Route::get( '/admission/delete/{id}', 'AdmissionController@destroy' );
Route::post( '/admission/import-approved', 'AdmissionController@importApproved' );



//navigation drop-down
Route::get('/list/academicyear','NavigationController@list');
Route::post('/academicyear/index','NavigationController@index');

//birthday
Route::get( '/dashboard/birthdayUser', 'BirthdayController@birthdayUser' );
Route::get( '/dashboard/showBirthday', 'BirthdayController@showBirthday' );
Route::get( '/dashboard/birthday', 'BirthdayController@birthday' );
Route::post( '/dashboard/birthday', 'BirthdayController@birthdayMessage' );
Route::get( '/dashboard/birthdayTeacher', 'BirthdayController@birthdayTeacher' );
Route::get( '/dashboard/showBirthdayTeacher', 'BirthdayController@showBirthdayTeacher' );
Route::get( '/dashboard/birthday/teacher', 'BirthdayController@birthdayCreate' );
Route::post( '/dashboard/birthday/teacher', 'BirthdayController@birthdayMessageTeacher' );
Route::get( '/dashboard/showWorkAnniversary', 'BirthdayController@showWorkAnniversary' );
Route::get( '/dashboard/workAnniversary/list', 'BirthdayController@workAnniversary' );
Route::get( '/dashboard/workAnniversary', 'BirthdayController@workAnniversaryCreate' );
Route::post( '/dashboard/workAnniversary', 'BirthdayController@workAnniversaryMessage' );

//school details
Route::get( '/schooldetails', 'SchoolDetailsController@index' );
//Route::get( '/schooldetails/list', 'SchoolDetailsController@list' );
//Route::get( '/schooldetails/create', 'SchoolDetailsController@create' );
//Route::post( '/schooldetails/create/validationStore', 'SchoolDetailsController@validationStore' );
//Route::post( '/schooldetails/create', 'SchoolDetailsController@store' );
Route::get( '/schooldetails/edit/{school_id}', 'SchoolDetailsController@edit' );
Route::get( '/schooldetails/editdetail/{school_id}', 'SchoolDetailsController@editdetail' );
Route::post( '/schooldetails/update/validationUpdate/{school_id}', 'SchoolDetailsController@update' );
Route::post( '/schooldetails/update/{school_id}', 'SchoolDetailsController@update' );

//academics
	//index
	Route::get( '/academics', 'AcademicYearController@index' );
	Route::get( '/academic/list', 'AcademicYearController@list' );

	//add
	Route::get( '/academic/add/list', 'AcademicYearController@createList' );
	Route::get( '/academic/add', 'AcademicYearController@create' );
	Route::post( '/academic/add', 'AcademicYearController@store' );

	//show
	Route::get( '/academic/show/{id}', 'AcademicYearController@show' );

	//edit
	Route::get( '/academic/edit/list/{id}', 'AcademicYearController@editList' );
	Route::get( '/academic/edit/{id}', 'AcademicYearController@edit' );
	Route::post( '/academic/edit/{id}', 'AcademicYearController@update' );
	Route::post( '/academic/updateStatus', 'AcademicYearController@updateStatus' );

	//delete
	Route::get( '/academic/delete/{id}', 'AcademicYearController@destroy' );

//holidays
	//index
	Route::get( '/holidays/list', 'HolidaysController@list' );
	Route::get( '/holidays', 'HolidaysController@index' );
	//add
	Route::get( '/holiday/add/list', 'HolidaysController@createList' );
	Route::get( '/holiday/add', 'HolidaysController@create' );
	Route::post( '/holiday/add', 'HolidaysController@store' );
	//edit
	Route::get( '/holiday/edit/{id}', 'HolidaysController@edit' );
	Route::post( '/holiday/edit/{id}', 'HolidaysController@update' );
	//delete
	Route::get( '/holiday/delete/{id}', 'HolidaysController@destroy' );

//standardlinks
	//index
	Route::get( '/standardlinks', 'StandardsLinkController@index' );
	Route::get( '/getStandard', 'StandardsLinkController@getStandard' );
	//add
	Route::get( '/standardLink/list', 'StandardsLinkController@list' );
	Route::get( '/standardLink/add', 'StandardsLinkController@create' );
	Route::post( '/standardLink/add', 'StandardsLinkController@store' );
	//show
	Route::get( '/standardLink/show/{id}', 'StandardsLinkDetailsController@show' );
	Route::get( '/standardLink/show/timetable/{id}', 'StandardsLinkDetailsController@showTimetable' );
	Route::get( '/standardLink/show/teachers/{id}', 'StandardsLinkDetailsController@showTeachers' );
	Route::get( '/standardLink/show/students/{id}', 'StandardsLinkDetailsController@showStudents' );
	Route::get( '/standardLink/show/student/attendances/{id}', 'StandardsLinkDetailsController@getStudentAttendance' );
	Route::get( '/standardLink/show/attendances/{id}', 'StandardsLinkDetailsController@getAttendance' );
	Route::post( '/standardLink/show/attendances/{id}', 'StandardsLinkDetailsController@showAttendance' );
	Route::get( '/standardLink/show/events/{id}', 'StandardsLinkDetailsController@showEvents' );
	Route::get( '/standardLink/show/upcomingExams/{id}', 'StandardsLinkDetailsController@showUpcomingExams' );
	Route::get( '/standardLink/show/pastExams/{id}', 'StandardsLinkDetailsController@showPastExams' );
	Route::get( '/standardLink/show/fees/{id}', 'StandardsLinkDetailsController@showFees' );
	Route::get( '/standardLink/show/classwall/{id}', 'StandardsLinkDetailsController@showClassWall' );
	Route::get( '/standardLink/show/comments/{post_id}', 'StandardsLinkDetailsController@showComments' );
	Route::get( '/standardLink/show/conference/{id}', 'StandardsLinkDetailsController@showConference' );
	//edit
	Route::get( '/standardLink/editlist/{id}', 'StandardsLinkController@editList' );
	Route::get( '/standardLink/edit/{id}', 'StandardsLinkController@edit' );
	Route::post( '/standardLink/edit/{id}', 'StandardsLinkController@update' );
	Route::post( '/standardLink/updateStatus/{id}', 'StandardsLinkController@updateStatus' );
	//delete
	Route::get( '/standardLink/delete/{id}', 'StandardsLinkController@destroy' );

//standards
	//add
	Route::post( '/standard/add', 'StandardController@store' );
	Route::get( '/standard/create', 'StandardController@create' );
	Route::post( '/standard/create', 'StandardController@add' );

//sections
	//add
	Route::post( '/section/add', 'SectionController@store' );

//notes
Route::post( '/getnotes', 'NotesController@index' );
Route::get( '/notes/delete/{id}', 'NotesController@delete' );
Route::get( '/notes/edit/{id}', 'NotesController@edit' );
Route::get( '/notes', 'NotesController@create' );
Route::post( '/notes', 'NotesController@store' );

//activity_log
Route::get( '/activity', 'ActivityLogController@index' );

//discipline
	//index
	Route::get( '/disciplines', 'DisciplineController@index' );
	//add
	Route::get( '/discipline/list', 'DisciplineController@list' );
	Route::get( '/discipline/add', 'DisciplineController@create' );
	Route::post( '/discipline/add', 'DisciplineController@store' );
	//show
	Route::get( '/discipline/show/{id}', 'DisciplineController@show' );
	//edit
	Route::get( '/discipline/editlist/{id}', 'DisciplineController@editlist' );
	Route::get( '/discipline/edit/{id}', 'DisciplineController@edit' );
	Route::post( '/discipline/edit/{id}', 'DisciplineController@update' );
	//delete
	Route::get( '/discipline/delete/{id}', 'DisciplineController@destroy' );
	Route::post( '/discipline/updateStatus/{id}', 'DisciplineController@updateStatus' );


	//Telephone Directory

		//index
		Route::get('/phonenumbers', 'TelephoneDirectoryController@index');

		//add
		Route::get('/phonenumber/list', 'TelephoneDirectoryController@list');
		Route::get('/phonenumber/add', 'TelephoneDirectoryController@create');
		Route::post('/phonenumber/add', 'TelephoneDirectoryController@store');

		//show
		Route::get('/phonenumber/show/{id}','TelephoneDirectoryController@show');

		//edit
		Route::get('/phonenumber/editlist/{id}', 'TelephoneDirectoryController@editlist');
		Route::get('/phonenumber/edit/{id}', 'TelephoneDirectoryController@edit');
		Route::post('/phonenumber/edit/{id}','TelephoneDirectoryController@update');

		//delete
		Route::get('/phonenumber/delete/{id}','TelephoneDirectoryController@destroy');

//students
	//index
	Route::get( '/students/find', 'StudentController@find' );
	Route::get( '/students', 'StudentController@index' );
	Route::get( '/students/blockedstudents', 'StudentController@blockedstudents' );
	//add
	Route::get( '/student/add', 'StudentController@create' );
	Route::get( '/student', 'StudentController@member' );
	Route::post( '/student/add/validationUser', 'StudentController@validationUser' );
	Route::post( '/student/add', 'StudentController@store' );
	//show
	Route::get( '/student/show/details/{name}', 'StudentDetailsController@showDetails' );
	Route::get( '/student/show/relations/{name}', 'StudentDetailsController@showRelations' );
	Route::get( '/student/show/siblings/{name}', 'StudentDetailsController@showSiblings' );
	Route::get( '/student/show/activity/{name}', 'StudentDetailsController@showActivity' );
	Route::get( '/student/show/discipline/{name}', 'StudentDetailsController@showDisciplines' );
	Route::get( '/student/show/attendance/{name}', 'StudentDetailsController@showAttendance' );
	Route::get( '/student/show/libraryactivity/{name}', 'StudentDetailsController@showBookLent' );
	Route::get( '/student/show/{name}', 'StudentDetailsController@show' );
	Route::get( '/student/showmark/{name}', 'StudentDetailsController@showmark' );
	Route::get( '/student/showallmark/{name}', 'StudentDetailsController@showAllMark' );
	//Route::get( '/student/comparemark/{name}', 'StudentDetailsController@compareMarks' );
	Route::get( '/student/comparemark/{name}', 'StudentDetailsController@marksGraph' );
	Route::get( '/student/show/fees/{name}', 'StudentDetailsController@showFees' );
	Route::get( '/student/show/medicalHistory/{name}', 'StudentDetailsController@showMedicalHistory' );
	Route::get( '/student/add/medicalHistory/{name}', 'StudentDetailsController@createMedicalHistory' );
	Route::post( '/student/add/medicalHistory/{name}', 'StudentDetailsController@addMedicalHistory' );
	//edit
	Route::get( '/student/editStudent/{name}', 'StudentController@editStudent' );
	Route::get( '/student/edit/{name}', 'StudentController@edit' );
	Route::post( '/student/edit/validationUser/{name}', 'StudentController@editValidationUser' );
	Route::post( '/student/edit/{name}', 'StudentController@update' );

	//delete
	Route::delete('/student/delete/{name}','StudentController@destroy');
	//send message
	Route::get( '/sentmessages', 'SendMessageController@index' );
	Route::post( '/student/sendMessageToAll', 'SendMessageController@store' );
	//update status
	Route::post( '/user/updateStatus/{name}', 'UserController@updateStatus' );
	//reset password
	Route::get( '/user/resetPassword/{id}', 'UserController@resetPassword' );
	//email verify
	Route::get( '/user/{id}/verificationcode', 'UserController@emailVerification' );

	Route::get( '/credentials/get/{name}', 'UserProfileController@getCredentials' );
	Route::post( '/credentials/add/{name}', 'UserProfileController@updateCredentials' );

//parent
	//index
	Route::get( '/parent/list', 'ParentController@list' );
	Route::get( '/parents', 'ParentController@index' );
	//delete
	Route::delete('/parent/delete/{name}','ParentController@destroy');
	//add
	Route::get( '/parent/get', 'ParentController@addList' );
	Route::post( '/parent/add/validationParent', 'ParentController@validationParent' );
	Route::get( '/parent/add', 'ParentController@create' );
	Route::post( '/parent/add', 'ParentController@store' );
	//show
	Route::get( '/parent/show/{name}', 'ParentController@show' );
	Route::get( '/parent/show/children/{name}', 'ParentController@showChildren' );
	Route::get( '/parent/show/activity/{name}', 'ParentController@showActivityLog' );
	Route::get( '/parent/show/feedback/{name}', 'ParentController@showFeedbacks' );
	//edit
	Route::get( '/parent/editlist/{name}', 'ParentController@editList' );
	Route::post( '/parent/edit/validationUser/{name}', 'ParentController@editValidationUser' );
	Route::get( '/parent/edit/{name}', 'ParentController@edit' );
	Route::post( '/parent/edit/{name}', 'ParentController@update' );

//teacher
	//index
	Route::get( '/teachers/find', 'TeacherListController@find' );
	Route::get( '/teachers', 'TeacherListController@index' );
	//delete
	Route::delete('/teacher/delete/{name}','TeacherListController@destroy');
	//send message
	Route::post( '/teacher/sendMessageToAll', 'SendMessageController@storeTeacher' );
	//add
	Route::get( '/teacher/add', 'TeacherAddController@create' );
	Route::get( '/teacher', 'TeacherAddController@member' );
	Route::post( '/teacher/add/validationProfile', 'TeacherAddController@validationProfile' );
	Route::post( '/teacher/add/validationAvatar', 'TeacherAddController@validationAvatar' );
	Route::post( '/teacher/add/validationAddress', 'TeacherAddController@validationAddress' );
	Route::post( '/teacher/add/validationQualification', 'TeacherAddController@validationQualification' );
	Route::post( '/teacher/add/validationNote', 'TeacherAddController@validationNote' );
	Route::post( '/teacher/add', 'TeacherAddController@store' );
	//show
	Route::get( '/teacher/show/details/{name}', 'TeacherShowController@showDetails' );
	Route::get( '/teacher/show/timetable/{name}', 'TeacherShowController@showTimetable' );
	Route::get( '/teacher/show/classes/{name}', 'TeacherShowController@showClasses' );
	Route::get( '/teacher/show/classteacher/{name}', 'TeacherShowController@showClassTeacher' );
	Route::get( '/teacher/show/leave/{name}', 'TeacherShowController@showLeaveHistory' );
	Route::get( '/teacher/show/activity/{name}', 'TeacherShowController@showActivity' );
	Route::get( '/teacher/show/logactivity/{name}', 'TeacherShowController@showActivityLog' );
	Route::get( '/teacher/show/{name}', 'TeacherShowController@show' );
	//edit
	Route::get( '/teacher/editTeacher/{name}', 'TeacherEditController@editTeacher' );
	Route::get( '/teacher/edit/{name}', 'TeacherEditController@edit' );
	Route::post( '/teacher/edit/validationProfile/{name}', 'TeacherEditController@editValidationProfile' );
	Route::post( '/teacher/edit/validationQualification/{name}', 'TeacherEditController@editValidationQualification' );
	Route::post( '/teacher/edit/validationNote/{name}', 'TeacherEditController@editValidationNote' );
	Route::post( '/teacher/edit/validationAddress/{name}', 'TeacherEditController@editValidationAddress' );
	Route::post( '/teacher/edit/{name}', 'TeacherEditController@update' );
	//export
	Route::get( '/exportTeachers', 'TeacherImportExportController@export' );
	//import
	Route::get( '/import/teacher', 'TeacherImportExportController@importCreate' );
	Route::post( '/importTeachers', 'TeacherImportExportController@import' );
	Route::get( '/downloadformat/teacher', 'TeacherImportExportController@downloadFormat' );

//promotion
Route::get( '/promotion/list', 'PromotionController@index' );
Route::get( '/promotion/create', 'PromotionController@create' );
Route::post( '/promotion/export', 'PromotionController@export' );
Route::post( '/promotion/import', 'PromotionController@import' );




//documents
	//list
	Route::get( '/document/get/{name}', 'DocumentsController@index' );
	//add
	Route::post( '/document/add/{name}', 'DocumentsController@store' );
	//edit
	Route::get( '/document/edit/list/{id}', 'DocumentsController@edit' );
	Route::post( '/document/edit/{name}/{id}', 'DocumentsController@update' );
	//delete
	Route::get( '/document/delete/{id}', 'DocumentsController@destroy' );
	//subjects
	//add
	Route::post( '/subjects/add', 'SubjectController@store' );
	Route::post( '/subjects/create', 'SubjectController@create' );

	Route::get( '/subject/delete/{id}', 'SubjectController@destroy' );



Route::get( '/standardLink/id-card/{id}', 'StandardsLinkController@idcard' );
Route::get( '/standardLink/id-card-print/{id}', 'StandardsLinkController@printidcard' );


//recurring events
Route::get( '/events', 'EventsController@index' );
Route::get( '/events/list', 'EventsController@list' );
Route::get( '/events/show', 'EventsController@events' );
Route::post( '/events/create', 'EventsController@store' );
Route::post( '/events/update/{id}', 'EventsController@update' );
Route::post( '/events/changeevent/{id}', 'EventsController@changeevent' );
Route::post( '/events/validateedit/{id}', 'EventsController@validateedit' );
Route::get( '/events/edit/{id}', 'EventsController@edit' );
Route::get( '/events/show/details/{id}', 'EventsController@show' );
Route::get( '/events/showdetails/{id}', 'EventsController@showdetails' );
Route::get( '/events/details/{id}', 'EventsController@details' );
Route::get( '/events/delete/{id}', 'EventsController@destroy' );

Route::get( '/event/approve/{id}', 'EventsController@eventapprove' );

//Settings
//Route::get( 'settings/', 'SettingController@index' );
Route::get( '/settings/generalsettings', 'Setting\GeneralController@create' );
Route::post( '/settings/generalsettings', 'Setting\GeneralController@store' );
Route::get( '/settings/maintenancesettings', 'Setting\MaintenanceController@create' );
Route::post( '/settings/maintenancesettings', 'Setting\MaintenanceController@store' );
Route::get( '/settings/seodetailsettings', 'Setting\SeoDetailController@create' );
Route::post( '/settings/seodetailsettings', 'Setting\SeoDetailController@store' );

//password and avatar

Route::get( '/changepassword', 'UserProfileController@ChangePassword' );
Route::post( '/changepassword', 'UserProfileController@updateChangePassword' );

Route::get( '/changeavatar', 'UserProfileController@changeavatar' );
Route::post( '/changeavatar', 'UserProfileController@updatechangeavatar' );
Route::get( '/getavatar', 'UserProfileController@getavatar' );

Route::get( '/editprofile', 'UserProfileController@edit' );
Route::get( '/profile', 'UserProfileController@create' );
Route::post( '/profile', 'UserProfileController@update' );

//Export

Route::get( '/export', 'ExportMemberController@index' );
Route::get( '/exportUsers', 'ExportMemberController@exportUsers' );

// Custom Export
Route::post( '/student/export', 'ExportMemberController@studentexport' );
Route::get( '/student/export', 'ExportMemberController@studentexports' );
Route::post( '/student/pdf', 'ExportMemberController@exportpdf' );
Route::get( '/student/pdf', 'ExportMemberController@exportpdfs' );
Route::post( '/teacher/export', 'TeacherImportExportController@teacherexport' );
Route::get( '/teacher/export', 'TeacherImportExportController@teacherexports' );
Route::post( '/staff/export', 'StaffController@staffexport' );
Route::get( '/staff/export', 'StaffController@staffexports' );

//import

Route::get( '/import', 'ImportMemberController@index' );
Route::post( '/importUsers', 'ImportMemberController@importUsers' );
Route::get( '/downloadformat', 'ImportMemberController@downloadFormat' );

//without approval homework -- do not remove
/*//homework
	//index
	Route::get( '/homeworks', 'HomeWorkController@index' );

	//add
	Route::get( '/homework/list', 'HomeWorkController@list' );
	Route::get( '/homework/show/list', 'HomeWorkController@showList' );
	Route::get( '/homework/add', 'HomeWorkController@create' );
	Route::post( '/homework/add', 'HomeWorkController@store' );

	//show
	Route::get( '/homework/show/{id}', 'HomeWorkController@show' );
	
	//edit
	Route::get( '/homework/edit/list/{id}', 'HomeWorkController@editList' );
	Route::get( '/homework/edit/{id}', 'HomeWorkController@edit' );
	Route::post( '/homework/edit/{id}', 'HomeWorkController@update' );
	//delete
	Route::get( '/homework/delete/{id}', 'HomeWorkController@destroy' );*/
//without approval homework -- do not remove

//with approval  
//homework
	//index
	Route::get( '/homeworks', 'Approval\HomeWorkController@index' );

	Route::post( '/homework/status/update', 'Approval\HomeworkApprovalController@update' );

	//add
	Route::get( '/homework/list', 'Approval\HomeWorkController@list' );
	Route::get( '/homework/show/{status}/list', 'Approval\HomeWorkController@showList' );
	Route::get( '/homework/add', 'Approval\HomeWorkController@create' );
	Route::post( '/homework/add', 'Approval\HomeWorkController@store' );

	//show
	Route::get( '/homework/show/{id}', 'Approval\HomeWorkController@show' );
	
	//edit
	Route::get( '/homework/edit/list/{id}', 'Approval\HomeWorkController@editList' );
	Route::get( '/homework/edit/{id}', 'Approval\HomeWorkController@edit' );
	Route::post( '/homework/edit/{id}', 'Approval\HomeWorkController@update' );
	//delete
	Route::get( '/homework/delete/{id}', 'Approval\HomeWorkController@destroy' );
	Route::get( '/homework/viewers/{id}', 'Approval\HomeWorkController@view' );

	//approve
    Route::post('/homework/approve/{id}', 'Approval\HomeworkApprovalController@approve');

    //reject
    Route::post('/homework/reject/{id}', 'Approval\HomeworkApprovalController@reject');
//with approval  
    
//student homework
    //show
    Route::get( '/studenthomeworks/{id}', 'StudentHomeworkController@list' );
    Route::get( '/studenthomework/show/{id}', 'StudentHomeworkController@show' );

    //edit
    Route::post( '/studenthomework/edit/{id}', 'StudentHomeworkController@update' );

//attendance
	//add
	Route::get( '/attendance/list', 'AttendanceController@list' );
	Route::get( '/attendance/add', 'AttendanceController@create' );
	Route::post( '/attendance/add', 'AttendanceController@store' );
	//export
	Route::get( '/attendance/export/{standardLink_id}', 'AttendanceController@export' );
	//absentees - dashboard
	Route::get( '/absentees/student', 'AttendanceController@student' );
	Route::get( '/absentees/student/list', 'AttendanceController@studentList' );
	Route::get( '/absentees/teacher', 'AttendanceController@teacher' );


//noticeboard
	//index
	Route::get( '/notices', 'NoticeBoardController@index' );
	//add
	Route::get( '/notice/list', 'NoticeBoardController@list' );
	Route::get( '/notice/add', 'NoticeBoardController@create' );
	Route::post( '/notice/add', 'NoticeBoardController@store' );
	Route::get( '/notice/show/list', 'NoticeBoardController@showList' );
	//edit
	Route::get( '/notice/show/{id}', 'NoticeBoardController@show' );
	Route::get( '/notice/edit/{id}', 'NoticeBoardController@edit' );
	Route::post( '/notice/update/{id}', 'NoticeBoardController@update' );
	//delete
	Route::get( '/notice/delete/{id}', 'NoticeBoardController@destroy' );
	//backgroundImage
	Route::post( '/notice/background/add', 'NoticeBoardController@addimage' );

//leave types
	//index
	Route::get( '/leavetypes', 'LeaveTypesController@index' );
	//add
	Route::get( '/leavetype/add', 'LeaveTypesController@create' );
	Route::post( '/leavetype/add', 'LeaveTypesController@store' );
	//edit
	Route::get( '/leavetype/edit/{id}', 'LeaveTypesController@edit' );
	Route::post( '/leavetype/edit/{id}', 'LeaveTypesController@update' );
	//delete
	Route::get( '/leavetype/delete/{id}', 'LeaveTypesController@destroy' );

// //videos
// 	//index 
//     Route::get( '/videos/list', 'VideosController@standardlist' );
// 	Route::get( '/files', 'VideosController@index' );
// 	Route::get( '/file/list/{type}', 'VideosController@list' );

// 	//add
// 	Route::get( '/file/add', 'VideosController@create' );
// 	Route::post( '/file/add', 'VideosController@store' );
// 	Route::post( '/storevideos', 'VideosController@videostore' );
// 	Route::post( '/storeimage', 'VideosController@storeimage' );
// 	Route::post( '/sessionsave', 'VideosController@save' );

// 	//show
// 	Route::get( '/file/show/{id}', 'VideosController@show' );

// 	Route::get( '/video/show', 'VideosController@view' );

// 	//edit
// 	Route::get( '/file/edit/{id}', 'VideosController@edit' );
// 	Route::post( '/file/edit/{id}', 'VideosController@update' );
// 	Route::get( '/videos/download/{id}', 'VideosController@downloadattachments' );

// 	//delete
// 	Route::get( '/file/delete/{id}', 'VideosController@destroy' );
// 	Route::get( '/file/viewers/{id}', 'VideosController@viewers' );



//event_gallery
Route::post( '/upload/photos/{event_id}', 'EventGalleryController@store' );
Route::get( '/display/photos/{event_id}', 'EventsController@showimage' );
Route::get( '/getphoto/{event_id}', 'EventGalleryController@getPhoto' );

//magazine
Route::get( '/magazines', 'BulletinsController@index' );
Route::get( '/magazine/create', 'BulletinsController@create' );
Route::post( '/magazine/create', 'BulletinsController@store' );
Route::get( '/magazine/getDate', 'BulletinsController@getDate' );
Route::get( '/magazine/delete/{id}', 'BulletinsController@destroy' );
Route::get( '/magazine/download/{id}', 'BulletinsController@downloadattachments' );

//reports
Route::get( '/reports', 'ReportsController@report' );
Route::get( '/report/events', 'ReportsController@eventReport' );
//Route::get( '/report/index', 'ReportsController@index' );
//Route::get( '/report/show/{id}', 'ReportsController@show' );
//Route::post( '/report/filter', 'ReportsController@create' );
Route::get( '/report/fees', 'ReportsController@exportFee' );
Route::get( '/report/downloadholidayformat', 'ReportsController@holidayFormat' );
Route::get( '/report/holidays', 'ReportsController@holidayCreate' );
Route::post( '/report/importHolidays', 'ReportsController@holidayImport' );
Route::get( '/report/exportHolidays', 'ReportsController@holidayExport' );
Route::get( '/report/birthday/{type}', 'ReportsController@exportBirthday' );
Route::get( '/report/anniversary', 'ReportsController@exportAnniversary' );
Route::get( '/report/activeStudents', 'ReportsController@exportActiveStudents' );
Route::get( '/report/exitStudents', 'ReportsController@exportExitStudents' );
Route::get( '/report/suspendedStudents', 'ReportsController@exportSuspendedStudents' );
Route::get( '/report/parents', 'ReportsController@exportParent' );

Route::get( '/report/currentstock', 'ReportsController@currentstock' );
Route::get( '/report/monthlypurchase', 'ReportsController@monthlypurchase' );
Route::get( '/report/monthlysales', 'ReportsController@monthlysales' );

//payment
Route::get( '/payment/index/{id}', 'PaymentController@index' );
Route::post( '/payment/response', 'PaymentController@response' );
Route::get( '/payment/subscription', 'PaymentController@Subscription' );

//feedback
	//index
	Route::get( '/feedbacks', 'FeedbackController@index' );
	//reply
	Route::get( '/feedback/edit/{feedbackid}', 'FeedbackController@edit' );
	Route::post( '/feedback/updateStatus/{id}', 'FeedbackController@updateStatus' );
	Route::post( '/feedback/edit/{feedbackid}', 'FeedbackController@update' );

//task
	//add
	Route::get('/task/add/list','TaskController@list');
	Route::get('/tasks','TaskController@index');
	Route::get('/task/add','TaskController@create');
	Route::post('/task/add','TaskController@store');

	//index
	Route::get('/task/list', 'TaskController@showlist');
	Route::post('/task/completed','TaskController@changestatus');

	//show
	Route::get('/task/show/{id}', 'TaskController@show');

	//edit
	Route::get('/task/edit/list/{id}', 'TaskController@editList');
	Route::get('/task/edit/{id}', 'TaskController@edit');
	Route::post('/task/edit/{id}', 'TaskController@update');

	//snooze
	Route::post('/task/snooze/{id}', 'TaskController@snooze');

	//delete
	Route::get('/task/{id}/delete', 'TaskController@destroy');

//visitor-log
	//index
	Route::get( '/visitorlog', 'VisitorLogController@index' );
	Route::get('/visitorlog/showlist', 'VisitorLogController@showlist');

	//add
	Route::get('/visitorlog/list', 'VisitorLogController@list');
	Route::get('/visitorlog/add','VisitorLogController@create');
	Route::post('/visitorlog/add','VisitorLogController@store');

	//show
	Route::get('/visitorlog/show/{id}','VisitorLogController@show');

	//edit
	Route::get('/visitorlog/edit/{id}', 'VisitorLogController@edit');
	Route::post('/visitorlog/update/{id}', 'VisitorLogController@update');

	//delete
	Route::get('/visitorlog/delete/{id}', 'VisitorLogController@destroy');

	//print
	Route::get('/visitorlog/view/{id}','VisitorLogController@view');
	Route::get('/visitorlog/print/{id}','VisitorLogController@print');

//call-log
Route::get( '/calllog', 'CallLogController@index' );
Route::get('/calllog/showlist', 'CallLogController@showlist');
Route::get('/calllog/list', 'CallLogController@list');
Route::get('/calllog/add','CallLogController@create');
Route::post('/calllog/add','CallLogController@store');
Route::get('/calllog/show/{id}','CallLogController@show');
Route::get('/calllog/edit/{id}', 'CallLogController@edit');
Route::post('/calllog/update/{id}', 'CallLogController@update');
Route::get('/calllog/delete/{id}', 'CallLogController@destroy');

//feed
Route::get('/feeds', 'FeedController@index');
Route::get('/feed/list', 'FeedController@list');
Route::get('/feed', 'FeedController@create');
Route::get('/feed/filter', 'FeedController@filter');


//call-log
Route::get( '/postalrecord', 'PostalRecordController@index' );
Route::get('/postalrecord/showlist', 'PostalRecordController@showlist');
Route::get('/postalrecord/list', 'PostalRecordController@list');
Route::get('/postalrecord/add','PostalRecordController@create');
Route::post('/postalrecord/add','PostalRecordController@store');
Route::get('/postalrecord/show/{id}','PostalRecordController@show');
Route::get('/postalrecord/edit/{id}', 'PostalRecordController@edit');
Route::post('/postalrecord/update/{id}', 'PostalRecordController@update');
Route::get('/postalrecord/delete/{id}', 'PostalRecordController@destroy');

//page category
Route::get( '/classwall/pageCategory/list', 'PageCategoryController@list' );
Route::post( '/classwall/pageCategory/add', 'PageCategoryController@store' );

//page
Route::get( '/classwall/page/list', 'PagesController@list' );
Route::get( '/classwall/pages', 'PagesController@index' );

Route::get( '/classwall/page/add', 'PagesController@create' );
Route::post( '/classwall/page/add', 'PagesController@store' );

Route::get( '/classwall/page/showList/{id}', 'PagesController@showList' );
Route::get( '/classwall/page/show/{id}', 'PagesController@show' );

Route::get( '/classwall/page/editList/{id}', 'PagesController@editList' );
Route::get( '/classwall/page/edit/{id}', 'PagesController@edit' );
Route::post( '/classwall/page/edit/{id}', 'PagesController@update' );

Route::get( '/classwall/page/delete/{id}', 'PagesController@destroy' );

Route::post( '/classwall/page/follow/{id}', 'PageDetailsController@follow' );
Route::post( '/classwall/page/like/{id}', 'PageDetailsController@like' );
Route::post( '/classwall/page/dislike/{id}', 'PageDetailsController@dislike' );

//post
Route::get( '/classwall/post/list', 'PostsController@indexList' );
Route::get( '/classwall/posts', 'PostsController@index' );

Route::get( '/classwall/post/showList/{id}', 'PostsController@showList' );
Route::get( '/classwall/post/show/{id}', 'PostsController@show' );

Route::get( '/classwall/post/delete/{id}', 'PostsController@destroy' );

Route::get( '/classwall/post/add/list', 'PostAddController@createList' );
Route::get( '/classwall/post/add', 'PostAddController@create' );
Route::post( '/classwall/post/add', 'PostAddController@store' );
Route::post( '/classwall/post/add/attachment', 'PostAddController@attachment' );

Route::get( '/classwall/post/editList/{id}', 'PostEditController@editList' );
Route::get( '/classwall/post/edit/{id}', 'PostEditController@edit' );
Route::post( '/classwall/post/edit/{id}', 'PostEditController@update' );
Route::post( '/classwall/post/edit/attachment/{id}', 'PostEditController@editAttachment' );

Route::post( '/classwall/post/like/{post_id}', 'PostDetailController@like' );
Route::post( '/classwall/post/dislike/{post_id}', 'PostDetailController@dislike' );
Route::post( '/classwall/post/save/{post_id}', 'PostDetailController@save' );
Route::post( '/classwall/post/unsave/{post_id}', 'PostDetailController@unsave' );

Route::post( '/classwall/post/add/comment/{post_id}', 'PostCommentsController@addComment' );
Route::get( '/classwall/post/edit/commentList/{comment_id}', 'PostCommentsController@editCommentList' );
Route::post( '/classwall/post/edit/comment/{comment_id}', 'PostCommentsController@editComment' );
Route::get( '/classwall/post/delete/comment/{comment_id}', 'PostCommentsController@destroy' );

Route::get( '/classwall/post/replyComment/{post_comment_id}', 'PostReplyCommentsController@list' );
Route::post( '/classwall/post/reply/add/comment/{post_comment_id}', 'PostReplyCommentsController@addComment' );
Route::post( '/classwall/post/reply/edit/comment/{post_comment_id}', 'PostReplyCommentsController@editComment' );
Route::get( '/classwall/post/reply/delete/comment/{post_comment_id}', 'PostReplyCommentsController@destroy' );

Route::post( '/classwall/post/comment/like/{comment_id}', 'PostCommentDetailsController@like' );
Route::post( '/classwall/post/comment/dislike/{comment_id}', 'PostCommentDetailsController@dislike' );







Route::get('/notification/list', 'NotificationController@indexList');
Route::get('/notifications', 'NotificationController@index');
Route::post('/notification/read', 'NotificationController@store');
Route::get('/notification/showList', 'NotificationController@showList');


Route::get('/bankdetails/get/{name}', 'BankDetailController@index');
Route::post('/bankdetails/add/{name}', 'BankDetailController@store');
Route::get('/bankdetails/edit/{id}', 'BankDetailController@edit');
Route::post('/bankdetails/update/{id}', 'BankDetailController@update');


// staffAttendance

Route::get( '/attendance/staff/list', 'StaffAttendanceController@list');
Route::get( '/attendance/staff/add', 'StaffAttendanceController@create');
Route::post( '/attendance/staff/add', 'StaffAttendanceController@store');

Route::get( '/absentees/staff', 'StaffAttendanceController@staff');
Route::get( '/absentees/staff/list', 'StaffAttendanceController@stafflist');

Route::get( '/teacher/attendances/show/{name}', 'StaffAttendanceController@getStudentAttendance');

Route::get( '/teacher/show/attendance/{name}', 'StaffAttendanceController@showAttendance');

Route::get( '/staffs/find', 'StaffController@find');
Route::get( '/staffs', 'StaffController@index');
Route::get( '/staff/show/{name}', 'StaffController@show');
Route::delete('/staff/delete/{name}','StaffController@destroy');
Route::get( '/staff/add', 'StaffController@create');
Route::post( '/staff/add', 'StaffController@store');
Route::get( '/staff/edit/{name}', 'StaffController@edit');
Route::post( '/staff/edit/{name}', 'StaffController@update');


// Emergency Message

Route::get( '/emergency', 'SendEmergencyMessageController@create');
Route::post( '/emergency/send', 'SendEmergencyMessageController@store');
Route::post( '/student/shift', 'SendMessageController@shift' );

//new add 
Route::get( '/teacher/show/libraryactivity/{name}', 'TeacherShowController@showBookLent' );

// Show single Bus Pass 
Route::post('student/buspass', 'StudentDetailsController@create');
Route::get('student/showbuspass/show/{name}', 'StudentDetailsController@showbus');
Route::get('student/buspass/showprint/{name}', 'StudentDetailsController@showprint_buspass');

//Teacher ID Card 
Route::get( '/teacher/id-card', 'TeacherListController@idcard' );
Route::get( '/teacher/id-card-print', 'TeacherListController@printidcard' );
Route::get( '/teacher/id-card/{name}', 'TeacherShowController@showidcard' );
Route::get( '/teacher/show-idcardprint/{name}', 'TeacherShowController@showprintidcard' );
//Non-Teacher ID Card 
Route::get( '/staffs/id-card', 'StaffController@idcard' );
Route::get( '/staffs/id-card-print', 'StaffController@printidcard' );
Route::get( '/staffs/id-card/{name}', 'StaffController@showidcard' );
Route::get( '/staffs/show-idcardprint/{name}', 'StaffController@showprintidcard' );

//Addons

Route::get('/addon', function () {
    return view('admin.addon.index');
});

Route::get('/addon/{slug}/detail', function ($slug) {
    return view('admin.addon.detail',compact('slug'));
});

Route::get('/payment/razorpay/checkout', function () {
    return view('admin.addon.razorpay');
});

Route::get('/purchase/addon/histories', function () {
    return view('admin.addon.purchase-history');
});
   //Setting

  //City
   Route::get('setting/cities', function () {
        return view('admin.setting.cities');
    })->name('superadmin.setting.cities');

   Route::get('setting/city/create', function () {
        return view('admin.setting.cityform');
    })->name('admin.setting.cities.create');

   Route::get('setting/city/update/{id}', function ($id) {
        return view('admin.setting.cityform', compact('id'));
    })->name('admin.setting.cities.update');

   Route::get('setting/city/detail/{id}', function ($id) {
        return view('admin.setting.citydetail',compact('id'));
    })->name('admin.setting.city.detail');

   //Country
   Route::get('setting/countries', function () {
        return view('admin.setting.countries');
    })->name('admin.setting.countries');

   Route::get('setting/country/create', function () {
   	    $id='';
        return view('admin.setting.countryform',compact('id'));
    })->name('admin.setting.countries.create');

    Route::get('setting/country/update/{id}', function ($id) {
        return view('admin.setting.countryform', compact('id'));
    })->name('admin.setting.countries.update');

    Route::get('setting/country/detail/{id}', function ($id) {
        return view('admin.setting.countrydetail',compact('id'));
    })->name('admin.setting.countries.detail');

    //State
    Route::get('setting/states', function () {
        return view('admin.setting.states');
    })->name('admin.setting.states');

    Route::get('setting/state/create', function () {
    	$id='';
        return view('admin.setting.stateform', compact('id'));
    })->name('admin.setting.states.create');

    Route::get('setting/state/update/{id}', function ($id) {
        return view('admin.setting.stateform', compact('id'));
    })->name('admin.setting.states.update');

    Route::get('setting/state/detail/{id}', function ($id) {
        return view('admin.setting.statedetail',compact('id'));
    })->name('admin.setting.states.detail');

    // sms
    Route::get('setting/smstemplates', function () {
        return view('admin.setting.smstemplates');
    })->name('admin.setting.smstemplate');

    Route::get('setting/smstemplate/{id}/update', function ($id) {
        return view('admin.setting.edit_smstemplate',compact('id'));
    })->name('admin.setting.smstemplate.update');

