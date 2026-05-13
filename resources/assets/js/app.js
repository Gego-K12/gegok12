
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import { createApp, defineAsyncComponent } from 'vue'
import mitt from 'mitt';


export const bus = mitt();
window.bus = bus;

import uploader from 'vue-simple-uploader'
import AudioRecorder from 'vue-audio-recorder'

import PrimeVue from 'primevue/config'

// styles
import 'primevue/resources/themes/lara-light-blue/theme.css'
import 'primevue/resources/primevue.min.css'
import 'primeicons/primeicons.css'

import registerCustomAddon from './custom_addon'

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const appContainer = document.getElementById('app');
const rootTemplate = appContainer ? appContainer.innerHTML : '<div></div>';
const app = createApp({ template: rootTemplate });

const originalComponent = app.component.bind(app);

app.component = (name, component) => {
    if (typeof component === 'function') {
        component = defineAsyncComponent(component);
    }
    return originalComponent(name, component);
};


app.component('example-component', () => import('./components/ExampleComponent.vue').then(m => m.default));

//demo
app.component('demo-tab', () => import('./components/demo/Tab.vue').then(m => m.default));

//admission
app.component('admission-list', () => import('./components/admission/List.vue').then(m => m.default));
app.component('edit-admission-form', () => import('./components/admission/Edit.vue').then(m => m.default));
app.component('add-admission', () => import('./components/admission/AdmissionTab.vue').then(m => m.default));
app.component('select-standard', () => import('./components/admission/SelectStandard.vue').then(m => m.default));
app.component('student-detail', () => import('./components/admission/StudentDetail.vue').then(m => m.default));
app.component('academic-detail', () => import('./components/admission/AcademicDetail.vue').then(m => m.default));
app.component('parent-detail', () => import('./components/admission/ParentDetail.vue').then(m => m.default));
app.component('personal-detail', () => import('./components/admission/PersonalDetail.vue').then(m => m.default));



// classwall

	//page
	app.component('page-list', () => import('./components/classwall/page/List.vue').then(m => m.default));
	app.component('create-page', () => import('./components/classwall/page/Create.vue').then(m => m.default));
	app.component('edit-page', () => import('./components/classwall/page/Edit.vue').then(m => m.default));
	app.component('show-page', () => import('./components/classwall/page/Show.vue').then(m => m.default));
	app.component('page-tab', () => import('./components/classwall/page/tabs/pageTab.vue').then(m => m.default));

	//post
	app.component('post-list', () => import('./components/classwall/post/List.vue').then(m => m.default));
	app.component('create-post', () => import('./components/classwall/post/Create.vue').then(m => m.default));
	app.component('edit-post', () => import('./components/classwall/post/Edit.vue').then(m => m.default));
	app.component('show-post', () => import('./components/classwall/post/Show.vue').then(m => m.default));
	app.component('comment-list', () => import('./components/classwall/post/Comments.vue').then(m => m.default));
	app.component('emoji', () => import('./components/classwall/post/Emoji.vue').then(m => m.default));

//notification

app.component('notification-list', () => import('./components/notification/List.vue').then(m => m.default));
app.component('notification', () => import('./components/notification/Show.vue').then(m => m.default));

//School Detail
app.component('create-schooldetail', () => import('./components/schooldetail/Create.vue').then(m => m.default));
app.component('edit-schooldetail', () => import('./components/schooldetail/Edit.vue').then(m => m.default));

//student
app.component('member-list', () => import('./components/student/List.vue').then(m => m.default));
app.component('profile-tab', () => import('./components/student/profile/ProfileTab.vue').then(m => m.default));
app.component('search-filter', () => import('./components/student/Filter.vue').then(m => m.default));
app.component('create-member', () => import('./components/student/Create.vue').then(m => m.default));
app.component('edit-member', () => import('./components/student/Edit.vue').then(m => m.default));
app.component('create-medical-history', () => import('./components/student/CreateMedicalHistory.vue').then(m => m.default));

app.component('change-password-student', () => import('./components/student/ChangePassword.vue').then(m => m.default));

//Bulletin
app.component('create-bulletin', () => import('./components/bulletin/Create.vue').then(m => m.default));





//parent
app.component('parent-list', () => import('./components/parent/List.vue').then(m => m.default));
app.component('parent-search-filter', () => import('./components/parent/Filter.vue').then(m => m.default));
app.component('create-parent', () => import('./components/parent/Create.vue').then(m => m.default));
app.component('edit-parent', () => import('./components/parent/Edit.vue').then(m => m.default));
app.component('profile-tab-parent', () => import('./components/parent/profile/ProfileTab.vue').then(m => m.default));

//teacher
app.component('teacher-list', () => import('./components/teacher/List.vue').then(m => m.default));
app.component('teacher-filter', () => import('./components/teacher/Filter.vue').then(m => m.default));
app.component('create-teacher', () => import('./components/teacher/Create.vue').then(m => m.default));
app.component('profile-tab-teacher', () => import('./components/teacher/profile/ProfileTab.vue').then(m => m.default));
app.component('edit-teacher', () => import('./components/teacher/Edit.vue').then(m => m.default));
app.component('address-tab', () => import('./components/teacher/Address.vue').then(m => m.default));
app.component('notes-tab', () => import('./components/teacher/Notes.vue').then(m => m.default));
app.component('add-tab-teacher', () => import('./components/teacher/addTab.vue').then(m => m.default));

app.component('change-password-teacher', () => import('./components/teacher/ChangePassword.vue').then(m => m.default));
app.component('change-avatar-teacher', () => import('./components/teacher/ChangeAvatar.vue').then(m => m.default));

//Staff
app.component('staff-list', () => import('./components/staff/List.vue').then(m => m.default));
app.component('staff-filter', () => import('./components/staff/Filter.vue').then(m => m.default));

//promotion
app.component('create-promotion', () => import('./components/promotion/Create.vue').then(m => m.default));

//attendance
app.component('create-attendance', () => import('./components/attendance/Create.vue').then(m => m.default));
app.component('create-staff-attendance', () => import('./components/attendance/staff/Create.vue').then(m => m.default));



//discipline
app.component('create-discipline', () => import('./components/discipline/Create.vue').then(m => m.default));
app.component('edit-discipline', () => import('./components/discipline/Edit.vue').then(m => m.default));

//academic
app.component('standard-setup', () => import('./components/settings/StandardSetup.vue').then(m => m.default));
app.component('class-tab', () => import('./components/academic/class/classTab.vue').then(m => m.default));
app.component('create-class', () => import('./components/academic/Create1.vue').then(m => m.default));
app.component('edit-class', () => import('./components/academic/Edit.vue').then(m => m.default));
app.component('standardfilter', () => import('./components/academic/Filter.vue').then(m => m.default));

//academic year
app.component('list-academic-year', () => import('./components/academicyear/List.vue').then(m => m.default));
app.component('create-academic-year', () => import('./components/academicyear/Create.vue').then(m => m.default));
app.component('edit-academic-year', () => import('./components/academicyear/Edit.vue').then(m => m.default));

//holiday
app.component('add-holiday', () => import('./components/academic/holiday/Create.vue').then(m => m.default));
app.component('holiday-list', () => import('./components/academic/holiday/List.vue').then(m => m.default));

//subject
app.component('add-subjects', () => import('./components/subject/Create.vue').then(m => m.default));
app.component('edit-subjects', () => import('./components/subject/Edit.vue').then(m => m.default));



//Homework
app.component('create-homework', () => import('./components/homework/Create.vue').then(m => m.default));
app.component('edit-homework', () => import('./components/homework/Edit.vue').then(m => m.default));
app.component('show-homework', () => import('./components/homework/Show.vue').then(m => m.default));
app.component('home-work-list', () => import('./components/homework/List.vue').then(m => m.default));
app.component('list-tab-homework', () => import('./components/homework/approvedhomework/listTab.vue').then(m => m.default));

//Notice Board
app.component('create-circular', () => import('./components/noticeboard/Create.vue').then(m => m.default));
app.component('edit-circular', () => import('./components/noticeboard/Edit.vue').then(m => m.default));
app.component('notice-board-list', () => import('./components/noticeboard/List.vue').then(m => m.default));






//assignment-teacher
app.component('create-assignment', () => import('./components/assignment/teacher/Create.vue').then(m => m.default));
app.component('edit-assignment', () => import('./components/assignment/teacher/Edit.vue').then(m => m.default));
app.component('student-assignment-list', () => import('./components/assignment/teacher/StudentAssignmentList.vue').then(m => m.default));

//assignment
app.component('assignment-list', () => import('./components/assignment/List.vue').then(m => m.default));
app.component('list-tab-assignment', () => import('./components/assignment/approvedassignment/listTab.vue').then(m => m.default));

//student assignment
app.component('assignment-list-student', () => import('./components/assignment/student/List.vue').then(m => m.default));
app.component('attachment-assignment', () => import('./components/assignment/student/Attachment.vue').then(m => m.default));


//student homework
app.component('homework-list', () => import('./components/homework/student/List.vue').then(m => m.default));
app.component('attachment-homework', () => import('./components/homework/student/Attachment.vue').then(m => m.default));

//lesson-plan
app.component('lesson-plan-list', () => import('./components/lessonplan/List.vue').then(m => m.default));
app.component('approve-lesson-plan', () => import('./components/lessonplan/Approve.vue').then(m => m.default));
app.component('list-tab-lesson', () => import('./components/lessonplan/listTab.vue').then(m => m.default));
app.component('add-tab-lesson', () => import('./components/lessonplan/addTab.vue').then(m => m.default));


//leave application
app.component('leave-teacher-list', () => import('./components/leave/teacher/List.vue').then(m => m.default));
app.component('create-leave', () => import('./components/leave/teacher/Create.vue').then(m => m.default));
app.component('edit-leave', () => import('./components/leave/teacher/Edit.vue').then(m => m.default));
app.component('approve-leave', () => import('./components/leave/teacher/Approve.vue').then(m => m.default));
app.component('pending-count', () => import('./components/leave/teacher/PendingCount.vue').then(m => m.default));

//student leave application
app.component('student-leave-tab', () => import('./components/leave/student/listTab.vue').then(m => m.default));
app.component('student-leave-list', () => import('./components/leave/student/List.vue').then(m => m.default));
app.component('approve-student-leave', () => import('./components/leave/student/Approve.vue').then(m => m.default));

//reception leave application
app.component('reception-leave-list', () => import('./components/leave/reception/List.vue').then(m => m.default));
app.component('reception-create-leave', () => import('./components/leave/reception/Create.vue').then(m => m.default));
app.component('reception-edit-leave', () => import('./components/leave/reception/Edit.vue').then(m => m.default));

//absentees
app.component('absentees-student', () => import('./components/dashboard/StudentAttendance.vue').then(m => m.default));
app.component('absentees-staff', () => import('./components/dashboard/StaffAttendance.vue').then(m => m.default));

//visitor Log
app.component('add-visitor-log', () => import('./components/visitorlog/Create.vue').then(m => m.default));
app.component('list-visitor-log', () => import('./components/visitorlog/List.vue').then(m => m.default));
app.component('edit-visitor-log', () => import('./components/visitorlog/Edit.vue').then(m => m.default));

//call Log
app.component('add-call-log', () => import('./components/calllog/Create.vue').then(m => m.default));
app.component('list-call-log', () => import('./components/calllog/List.vue').then(m => m.default));
app.component('edit-call-log', () => import('./components/calllog/Edit.vue').then(m => m.default));


//postal Log
app.component('add-postal-record', () => import('./components/postalrecord/Create.vue').then(m => m.default));
app.component('list-postal-record', () => import('./components/postalrecord/List.vue').then(m => m.default));
app.component('edit-postal-record', () => import('./components/postalrecord/Edit.vue').then(m => m.default));

//visitor Log
app.component('add-teacher-visitor-log', () => import('./components/teacher/visitorlog/Create.vue').then(m => m.default));
app.component('list-teacher-visitor-log', () => import('./components/teacher/visitorlog/List.vue').then(m => m.default));
app.component('edit-teacher-visitor-log', () => import('./components/teacher/visitorlog/Edit.vue').then(m => m.default));

//call Log
app.component('add-teacher-call-log', () => import('./components/teacher/calllog/Create.vue').then(m => m.default));
app.component('list-teacher-call-log', () => import('./components/teacher/calllog/List.vue').then(m => m.default));
app.component('edit-teacher-call-log', () => import('./components/teacher/calllog/Edit.vue').then(m => m.default));


//postal Log
app.component('add-teacher-postal-record', () => import('./components/teacher/postalrecord/Create.vue').then(m => m.default));
app.component('list-teacher-postal-record', () => import('./components/teacher/postalrecord/List.vue').then(m => m.default));
app.component('edit-teacher-postal-record', () => import('./components/teacher/postalrecord/Edit.vue').then(m => m.default));




//dashboard
app.component('birthday', () => import('./components/dashboard/Birthday.vue').then(m => m.default));
app.component('view-birthday', () => import('./components/dashboard/ViewBirthday.vue').then(m => m.default));
app.component('birthday-teacher', () => import('./components/dashboard/BirthdayTeacher.vue').then(m => m.default));
app.component('view-birthday-teacher', () => import('./components/dashboard/ViewBirthdayTeacher.vue').then(m => m.default));
app.component('work-anniversary', () => import('./components/dashboard/WorkAnniversary.vue').then(m => m.default));
app.component('view-work-anniversary', () => import('./components/dashboard/ViewWorkAnniversary.vue').then(m => m.default));
app.component('dashboard-timetable-teacher', () => import('./components/dashboard/Timetable.vue').then(m => m.default));

//Event
app.component('create-event', () => import('./components/event/Create.vue').then(m => m.default));
app.component('edit-event', () => import('./components/event/Edit.vue').then(m => m.default));
app.component('show-event', () => import('./components/event/show.vue').then(m => m.default));
app.component('event-popup', () => import('./components/event/Popup.vue').then(m => m.default));
app.component('event-tab', () => import('./components/event/details/EventTab.vue').then(m => m.default));

//Edit Userprofile
app.component('edit-profile', () => import('./components/admin/EditProfile.vue').then(m => m.default));
app.component('change-password', () => import('./components/admin/ChangePassword.vue').then(m => m.default));
app.component('change-avatar', () => import('./components/admin/ChangeAvatar.vue').then(m => m.default));
app.component('change-credential', () => import('./components/admin/ChangeCredential.vue').then(m => m.default));

app.component('showimage', () => import('./components/event/details/ShowImage.vue').then(m => m.default));
//app.component('galleryimage', () => import('./components/event/details/GalleryImage.vue').then(m => m.default));

//Contact
app.component('contact', () => import('./components/contact.vue').then(m => m.default));

app.component('event', () => import('./components/dashboard/Event.vue').then(m => m.default));

//library


//custom-export
app.component('student-export', () => import('./components/export/Student.vue').then(m => m.default));
app.component('teacher-export', () => import('./components/export/Teacher.vue').then(m => m.default));
app.component('staff-export', () => import('./components/export/Staff.vue').then(m => m.default));

//books
app.component('add-book', () => import('./components/book/Create.vue').then(m => m.default));
app.component('edit-book', () => import('./components/book/Edit.vue').then(m => m.default));
app.component('edit-bookcategory', () => import('./components/bookcategory/Edit.vue').then(m => m.default));

//telephone directory
app.component('add-phone-number', () => import('./components/telephonedirectory/Create.vue').then(m => m.default));
app.component('edit-phone-number', () => import('./components/telephonedirectory/Edit.vue').then(m => m.default));
app.component('list-phone-number', () => import('./components/telephonedirectory/List.vue').then(m => m.default));



//Payroll
app.component('payroll-template', () => import('./components/accountant/payroll/template/List.vue').then(m => m.default));
app.component('create-template', () => import('./components/accountant/payroll/template/Create.vue').then(m => m.default));
app.component('edit-template', () => import('./components/accountant/payroll/template/Edit.vue').then(m => m.default));
app.component('payroll-salary', () => import('./components/accountant/payroll/salary/List.vue').then(m => m.default));
app.component('create-salary', () => import('./components/accountant/payroll/salary/Create.vue').then(m => m.default));
app.component('edit-salary', () => import('./components/accountant/payroll/salary/Edit.vue').then(m => m.default));
app.component('payroll-list', () => import('./components/accountant/payroll/payslip/List.vue').then(m => m.default));
app.component('create-payroll', () => import('./components/accountant/payroll/payslip/Create.vue').then(m => m.default));
app.component('edit-payroll', () => import('./components/accountant/payroll/payslip/Edit.vue').then(m => m.default));
app.component('transaction-list', () => import('./components/accountant/payroll/transaction/List.vue').then(m => m.default));
app.component('create-transaction', () => import('./components/accountant/payroll/transaction/Create.vue').then(m => m.default));
app.component('edit-transaction', () => import('./components/accountant/payroll/transaction/Edit.vue').then(m => m.default));

app.component('teacher-payroll-list', () => import('./components/payroll/teacher/payslip/List.vue').then(m => m.default));
app.component('teacher-transaction-list', () => import('./components/payroll/teacher/transaction/List.vue').then(m => m.default));
app.component('payroll-search-filter', () => import('./components/accountant/PayrollFilter.vue').then(m => m.default));

//Emergency Message

app.component('emergency-message', () => import('./components/emergency/Create.vue').then(m => m.default));

//booklending
app.component('add-booklending', () => import('./components/booklending/Create.vue').then(m => m.default));
app.component('edit-booklending', () => import('./components/booklending/Edit.vue').then(m => m.default));

//student library avtivity
app.component('list-lentbook', () => import('./components/booklending/List.vue').then(m => m.default));

//to do list
app.component('create-todo', () => import('./components/todolist/Create.vue').then(m => m.default));
app.component('edit-todo', () => import('./components/todolist/Edit.vue').then(m => m.default));
app.component('list-todo', () => import('./components/todolist/List.vue').then(m => m.default));
app.component('task-show', () => import('./components/todolist/Show.vue').then(m => m.default));

//dashboard to do 
app.component('list-task-tab', () => import('./components/todolist/listTab.vue').then(m => m.default));
app.component('dashboard-task', () => import('./components/dashboard/Task.vue').then(m => m.default));

//student task
app.component('add-student-task', () => import('./components/studenttask/List.vue').then(m => m.default));
app.component('add-student-task-popup', () => import('./components/studenttask/Create.vue').then(m => m.default));


//report
app.component('stock-search-filter', () => import('./components/report/StockFilter.vue').then(m => m.default));


//feed
app.component('add-post', () => import('./components/feed/Create.vue').then(m => m.default));
app.component('show-feed', () => import('./components/feed/ShowFeed.vue').then(m => m.default));
app.component('slider-image', () => import('./components/feed/slider.vue').then(m => m.default));


// home slider
app.component('homeslider', () => import('./components/Slider.vue').then(m => m.default));
app.component('nav-bar', () => import('./components/Navigation.vue').then(m => m.default));

//new

app.component('teacherlist-lentbook', () => import('./components/booklending/TeacherList.vue').then(m => m.default));

//Library cards 
app.component('librarymembersearch-filter', () => import('./components/librarycard/Filter.vue').then(m => m.default));
app.component('librarymember-list', () => import('./components/librarycard/List.vue').then(m => m.default));
app.component('libraryteachersearch-filter', () => import('./components/librarycard/TeacherFilter.vue').then(m => m.default));
app.component('libraryteacher-list', () => import('./components/librarycard/TeacherList.vue').then(m => m.default));
app.component('librarystaff-list', () => import('./components/librarycard/StaffList.vue').then(m => m.default));
app.component('librarystaff-filter', () => import('./components/librarycard/StaffFilter.vue').then(m => m.default));



import Paginate from 'vuejs-paginate-next'
app.component('paginate', Paginate)

import VueCookies from 'vue3-cookies'
app.use(VueCookies)


import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";
app.use(Toast)


// // Plugins used by leave and noticeboard components (registered globally for Vue 3)
// import 'vue-flash-message/dist/vue-flash-message.min.css';
// import VueFlashMessage from 'vue-flash-message';
// app.use(VueFlashMessage);

// import VueQuillEditor from 'vue-quill-editor';
// import 'quill/dist/quill.core.css';
// import 'quill/dist/quill.snow.css';
// import 'quill/dist/quill.bubble.css';
// app.use(VueQuillEditor);
registerCustomAddon(app)
// app.use(uploader)
// app.use(AudioRecorder)
import PickList from 'primevue/picklist';

app.component('PickList', PickList);

app.use(PrimeVue)

app.mount('#app');