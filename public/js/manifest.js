/*
 * ATTENTION: An "eval-source-map" devtool has been used.
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file with attached SourceMaps in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			id: moduleId,
/******/ 			loaded: false,
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Flag the module as loaded
/******/ 		module.loaded = true;
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/ensure chunk */
/******/ 	(() => {
/******/ 		__webpack_require__.f = {};
/******/ 		// This file contains only the entry chunk.
/******/ 		// The chunk loading function for additional chunks
/******/ 		__webpack_require__.e = (chunkId) => {
/******/ 			return Promise.all(Object.keys(__webpack_require__.f).reduce((promises, key) => {
/******/ 				__webpack_require__.f[key](chunkId, promises);
/******/ 				return promises;
/******/ 			}, []));
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/get javascript chunk filename */
/******/ 	(() => {
/******/ 		// This function allow to reference async chunks
/******/ 		__webpack_require__.u = (chunkId) => {
/******/ 			// return url for filenames not based on template
/******/ 			if ({"resources_assets_js_components_ExampleComponent_vue":1,"resources_assets_js_components_demo_Tab_vue":1,"resources_assets_js_components_admission_List_vue":1,"resources_assets_js_components_admission_Edit_vue":1,"resources_assets_js_components_admission_AdmissionTab_vue":1,"resources_assets_js_components_admission_SelectStandard_vue":1,"resources_assets_js_components_admission_StudentDetail_vue":1,"resources_assets_js_components_admission_AcademicDetail_vue":1,"resources_assets_js_components_admission_ParentDetail_vue":1,"resources_assets_js_components_admission_PersonalDetail_vue":1,"resources_assets_js_components_classwall_page_List_vue":1,"resources_assets_js_components_classwall_page_Create_vue":1,"resources_assets_js_components_classwall_page_Edit_vue":1,"resources_assets_js_components_classwall_page_Show_vue":1,"resources_assets_js_components_classwall_page_tabs_pageTab_vue":1,"resources_assets_js_components_classwall_post_List_vue":1,"resources_assets_js_components_classwall_post_Create_vue":1,"resources_assets_js_components_classwall_post_Edit_vue":1,"resources_assets_js_components_classwall_post_Show_vue":1,"resources_assets_js_components_classwall_post_Comments_vue":1,"resources_assets_js_components_classwall_post_Emoji_vue":1,"resources_assets_js_components_notification_List_vue":1,"resources_assets_js_components_notification_Show_vue":1,"resources_assets_js_components_schooldetail_Create_vue":1,"resources_assets_js_components_schooldetail_Edit_vue":1,"resources_assets_js_components_student_List_vue":1,"resources_assets_js_components_student_profile_ProfileTab_vue":1,"resources_assets_js_components_student_Filter_vue":1,"resources_assets_js_components_student_Create_vue":1,"resources_assets_js_components_student_Edit_vue":1,"resources_assets_js_components_student_CreateMedicalHistory_vue":1,"resources_assets_js_components_student_ChangePassword_vue":1,"resources_assets_js_components_bulletin_Create_vue":1,"resources_assets_js_components_parent_List_vue":1,"resources_assets_js_components_parent_Filter_vue":1,"resources_assets_js_components_parent_Create_vue":1,"resources_assets_js_components_parent_Edit_vue":1,"resources_assets_js_components_parent_profile_ProfileTab_vue":1,"resources_assets_js_components_teacher_List_vue":1,"resources_assets_js_components_teacher_Filter_vue":1,"resources_assets_js_components_teacher_Create_vue":1,"resources_assets_js_components_teacher_profile_ProfileTab_vue":1,"resources_assets_js_components_teacher_Edit_vue":1,"resources_assets_js_components_teacher_Address_vue":1,"resources_assets_js_components_teacher_Notes_vue":1,"resources_assets_js_components_teacher_addTab_vue":1,"resources_assets_js_components_teacher_ChangePassword_vue":1,"resources_assets_js_components_teacher_ChangeAvatar_vue":1,"resources_assets_js_components_staff_List_vue":1,"resources_assets_js_components_staff_Filter_vue":1,"resources_assets_js_components_promotion_Create_vue":1,"resources_assets_js_components_attendance_Create_vue":1,"resources_assets_js_components_attendance_staff_Create_vue":1,"resources_assets_js_components_attendance_staff_Register_vue":1,"resources_assets_js_components_discipline_Create_vue":1,"resources_assets_js_components_discipline_Edit_vue":1,"resources_assets_js_components_settings_StandardSetup_vue":1,"resources_assets_js_components_academic_class_classTab_vue":1,"resources_assets_js_components_academic_Create1_vue":1,"resources_assets_js_components_academic_Edit_vue":1,"resources_assets_js_components_academic_Filter_vue":1,"resources_assets_js_components_academicyear_List_vue":1,"resources_assets_js_components_academicyear_Create_vue":1,"resources_assets_js_components_academicyear_Edit_vue":1,"resources_assets_js_components_academic_holiday_Create_vue":1,"resources_assets_js_components_academic_holiday_List_vue":1,"resources_assets_js_components_subject_Create_vue":1,"resources_assets_js_components_subject_Edit_vue":1,"resources_assets_js_components_homework_Create_vue":1,"resources_assets_js_components_homework_Edit_vue":1,"resources_assets_js_components_homework_Show_vue":1,"resources_assets_js_components_homework_List_vue":1,"resources_assets_js_components_homework_approvedhomework_listTab_vue":1,"resources_assets_js_components_noticeboard_Create_vue":1,"resources_assets_js_components_noticeboard_Edit_vue":1,"resources_assets_js_components_noticeboard_List_vue":1,"resources_assets_js_components_assignment_teacher_Create_vue":1,"resources_assets_js_components_assignment_teacher_Edit_vue":1,"resources_assets_js_components_assignment_teacher_StudentAssignmentList_vue":1,"resources_assets_js_components_assignment_List_vue":1,"resources_assets_js_components_assignment_approvedassignment_listTab_vue":1,"resources_assets_js_components_assignment_student_List_vue":1,"resources_assets_js_components_assignment_student_Attachment_vue":1,"resources_assets_js_components_homework_student_List_vue":1,"resources_assets_js_components_homework_student_Attachment_vue":1,"resources_assets_js_components_lessonplan_List_vue":1,"resources_assets_js_components_lessonplan_Approve_vue":1,"resources_assets_js_components_lessonplan_listTab_vue":1,"resources_assets_js_components_lessonplan_addTab_vue":1,"resources_assets_js_components_leave_teacher_List_vue":1,"resources_assets_js_components_leave_teacher_Create_vue":1,"resources_assets_js_components_leave_teacher_Edit_vue":1,"resources_assets_js_components_leave_teacher_Approve_vue":1,"resources_assets_js_components_leave_teacher_PendingCount_vue":1,"resources_assets_js_components_leave_student_listTab_vue":1,"resources_assets_js_components_leave_student_List_vue":1,"resources_assets_js_components_leave_student_Approve_vue":1,"resources_assets_js_components_leave_reception_List_vue":1,"resources_assets_js_components_leave_reception_Create_vue":1,"resources_assets_js_components_leave_reception_Edit_vue":1,"resources_assets_js_components_dashboard_StudentAttendance_vue":1,"resources_assets_js_components_dashboard_StaffAttendance_vue":1,"resources_assets_js_components_visitorlog_Create_vue":1,"resources_assets_js_components_visitorlog_List_vue":1,"resources_assets_js_components_visitorlog_Edit_vue":1,"resources_assets_js_components_calllog_Create_vue":1,"resources_assets_js_components_calllog_List_vue":1,"resources_assets_js_components_calllog_Edit_vue":1,"resources_assets_js_components_postalrecord_Create_vue":1,"resources_assets_js_components_postalrecord_List_vue":1,"resources_assets_js_components_postalrecord_Edit_vue":1,"resources_assets_js_components_teacher_visitorlog_Create_vue":1,"resources_assets_js_components_teacher_visitorlog_List_vue":1,"resources_assets_js_components_teacher_visitorlog_Edit_vue":1,"resources_assets_js_components_teacher_calllog_Create_vue":1,"resources_assets_js_components_teacher_calllog_List_vue":1,"resources_assets_js_components_teacher_calllog_Edit_vue":1,"resources_assets_js_components_teacher_postalrecord_Create_vue":1,"resources_assets_js_components_teacher_postalrecord_List_vue":1,"resources_assets_js_components_teacher_postalrecord_Edit_vue":1,"resources_assets_js_components_dashboard_Birthday_vue":1,"resources_assets_js_components_dashboard_ViewBirthday_vue":1,"resources_assets_js_components_dashboard_BirthdayTeacher_vue":1,"resources_assets_js_components_dashboard_ViewBirthdayTeacher_vue":1,"resources_assets_js_components_dashboard_WorkAnniversary_vue":1,"resources_assets_js_components_dashboard_ViewWorkAnniversary_vue":1,"resources_assets_js_components_dashboard_Timetable_vue":1,"resources_assets_js_components_event_Create_vue":1,"resources_assets_js_components_event_Edit_vue":1,"resources_assets_js_components_event_show_vue":1,"resources_assets_js_components_event_Popup_vue":1,"resources_assets_js_components_event_details_EventTab_vue":1,"resources_assets_js_components_admin_EditProfile_vue":1,"resources_assets_js_components_admin_ChangePassword_vue":1,"resources_assets_js_components_admin_ChangeAvatar_vue":1,"resources_assets_js_components_admin_ChangeCredential_vue":1,"resources_assets_js_components_event_details_ShowImage_vue":1,"resources_assets_js_components_contact_vue":1,"resources_assets_js_components_dashboard_Event_vue":1,"resources_assets_js_components_export_Student_vue":1,"resources_assets_js_components_export_Teacher_vue":1,"resources_assets_js_components_export_Staff_vue":1,"resources_assets_js_components_book_Create_vue":1,"resources_assets_js_components_book_Edit_vue":1,"resources_assets_js_components_bookcategory_Edit_vue":1,"resources_assets_js_components_telephonedirectory_Create_vue":1,"resources_assets_js_components_telephonedirectory_Edit_vue":1,"resources_assets_js_components_telephonedirectory_List_vue":1,"resources_assets_js_components_accountant_payroll_template_List_vue":1,"resources_assets_js_components_accountant_payroll_template_Create_vue":1,"resources_assets_js_components_accountant_payroll_template_Edit_vue":1,"resources_assets_js_components_accountant_payroll_salary_List_vue":1,"resources_assets_js_components_accountant_payroll_salary_Create_vue":1,"resources_assets_js_components_accountant_payroll_salary_Edit_vue":1,"resources_assets_js_components_accountant_payroll_payslip_List_vue":1,"resources_assets_js_components_accountant_payroll_payslip_Create_vue":1,"resources_assets_js_components_accountant_payroll_payslip_Edit_vue":1,"resources_assets_js_components_accountant_payroll_transaction_List_vue":1,"resources_assets_js_components_accountant_payroll_transaction_Create_vue":1,"resources_assets_js_components_accountant_payroll_transaction_Edit_vue":1,"resources_assets_js_components_payroll_teacher_payslip_List_vue":1,"resources_assets_js_components_payroll_teacher_transaction_List_vue":1,"resources_assets_js_components_accountant_PayrollFilter_vue":1,"resources_assets_js_components_emergency_Create_vue":1,"resources_assets_js_components_booklending_Create_vue":1,"resources_assets_js_components_booklending_Edit_vue":1,"resources_assets_js_components_booklending_List_vue":1,"resources_assets_js_components_todolist_Create_vue":1,"resources_assets_js_components_todolist_Edit_vue":1,"resources_assets_js_components_todolist_List_vue":1,"resources_assets_js_components_todolist_Show_vue":1,"resources_assets_js_components_todolist_listTab_vue":1,"resources_assets_js_components_dashboard_Task_vue":1,"resources_assets_js_components_studenttask_List_vue":1,"resources_assets_js_components_studenttask_Create_vue":1,"resources_assets_js_components_report_StockFilter_vue":1,"resources_assets_js_components_feed_Create_vue":1,"resources_assets_js_components_feed_ShowFeed_vue":1,"resources_assets_js_components_feed_slider_vue":1,"resources_assets_js_components_Slider_vue":1,"resources_assets_js_components_Navigation_vue":1,"resources_assets_js_components_booklending_TeacherList_vue":1,"resources_assets_js_components_librarycard_Filter_vue":1,"resources_assets_js_components_librarycard_List_vue":1,"resources_assets_js_components_librarycard_TeacherFilter_vue":1,"resources_assets_js_components_librarycard_TeacherList_vue":1,"resources_assets_js_components_librarycard_StaffList_vue":1,"resources_assets_js_components_librarycard_StaffFilter_vue":1}[chunkId]) return "js/" + chunkId + ".js";
/******/ 			// return url for filenames based on template
/******/ 			return undefined;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/get mini-css chunk filename */
/******/ 	(() => {
/******/ 		// This function allow to reference all chunks
/******/ 		__webpack_require__.miniCssF = (chunkId) => {
/******/ 			// return url for filenames based on template
/******/ 			return "" + chunkId + ".css";
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/global */
/******/ 	(() => {
/******/ 		__webpack_require__.g = (function() {
/******/ 			if (typeof globalThis === 'object') return globalThis;
/******/ 			try {
/******/ 				return this || new Function('return this')();
/******/ 			} catch (e) {
/******/ 				if (typeof window === 'object') return window;
/******/ 			}
/******/ 		})();
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/load script */
/******/ 	(() => {
/******/ 		var inProgress = {};
/******/ 		// data-webpack is not used as build has no uniqueName
/******/ 		// loadScript function to load a script via script tag
/******/ 		__webpack_require__.l = (url, done, key, chunkId) => {
/******/ 			if(inProgress[url]) { inProgress[url].push(done); return; }
/******/ 			var script, needAttach;
/******/ 			if(key !== undefined) {
/******/ 				var scripts = document.getElementsByTagName("script");
/******/ 				for(var i = 0; i < scripts.length; i++) {
/******/ 					var s = scripts[i];
/******/ 					if(s.getAttribute("src") == url) { script = s; break; }
/******/ 				}
/******/ 			}
/******/ 			if(!script) {
/******/ 				needAttach = true;
/******/ 				script = document.createElement('script');
/******/ 		
/******/ 				script.charset = 'utf-8';
/******/ 				script.timeout = 120;
/******/ 				if (__webpack_require__.nc) {
/******/ 					script.setAttribute("nonce", __webpack_require__.nc);
/******/ 				}
/******/ 		
/******/ 		
/******/ 				script.src = url;
/******/ 			}
/******/ 			inProgress[url] = [done];
/******/ 			var onScriptComplete = (prev, event) => {
/******/ 				// avoid mem leaks in IE.
/******/ 				script.onerror = script.onload = null;
/******/ 				clearTimeout(timeout);
/******/ 				var doneFns = inProgress[url];
/******/ 				delete inProgress[url];
/******/ 				script.parentNode && script.parentNode.removeChild(script);
/******/ 				doneFns && doneFns.forEach((fn) => (fn(event)));
/******/ 				if(prev) return prev(event);
/******/ 			}
/******/ 			var timeout = setTimeout(onScriptComplete.bind(null, undefined, { type: 'timeout', target: script }), 120000);
/******/ 			script.onerror = onScriptComplete.bind(null, script.onerror);
/******/ 			script.onload = onScriptComplete.bind(null, script.onload);
/******/ 			needAttach && document.head.appendChild(script);
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/node module decorator */
/******/ 	(() => {
/******/ 		__webpack_require__.nmd = (module) => {
/******/ 			module.paths = [];
/******/ 			if (!module.children) module.children = [];
/******/ 			return module;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/publicPath */
/******/ 	(() => {
/******/ 		__webpack_require__.p = "/";
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			"/js/manifest": 0,
/******/ 			"css/app": 0
/******/ 		};
/******/ 		
/******/ 		__webpack_require__.f.j = (chunkId, promises) => {
/******/ 				// JSONP chunk loading for javascript
/******/ 				var installedChunkData = __webpack_require__.o(installedChunks, chunkId) ? installedChunks[chunkId] : undefined;
/******/ 				if(installedChunkData !== 0) { // 0 means "already installed".
/******/ 		
/******/ 					// a Promise means "currently loading".
/******/ 					if(installedChunkData) {
/******/ 						promises.push(installedChunkData[2]);
/******/ 					} else {
/******/ 						if(!/^(\/js\/manifest|css\/app)$/.test(chunkId)) {
/******/ 							// setup Promise in chunk cache
/******/ 							var promise = new Promise((resolve, reject) => (installedChunkData = installedChunks[chunkId] = [resolve, reject]));
/******/ 							promises.push(installedChunkData[2] = promise);
/******/ 		
/******/ 							// start chunk loading
/******/ 							var url = __webpack_require__.p + __webpack_require__.u(chunkId);
/******/ 							// create error before stack unwound to get useful stacktrace later
/******/ 							var error = new Error();
/******/ 							var loadingEnded = (event) => {
/******/ 								if(__webpack_require__.o(installedChunks, chunkId)) {
/******/ 									installedChunkData = installedChunks[chunkId];
/******/ 									if(installedChunkData !== 0) installedChunks[chunkId] = undefined;
/******/ 									if(installedChunkData) {
/******/ 										var errorType = event && (event.type === 'load' ? 'missing' : event.type);
/******/ 										var realSrc = event && event.target && event.target.src;
/******/ 										error.message = 'Loading chunk ' + chunkId + ' failed.\n(' + errorType + ': ' + realSrc + ')';
/******/ 										error.name = 'ChunkLoadError';
/******/ 										error.type = errorType;
/******/ 										error.request = realSrc;
/******/ 										installedChunkData[1](error);
/******/ 									}
/******/ 								}
/******/ 							};
/******/ 							__webpack_require__.l(url, loadingEnded, "chunk-" + chunkId, chunkId);
/******/ 						} else installedChunks[chunkId] = 0;
/******/ 					}
/******/ 				}
/******/ 		};
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = self["webpackChunk"] = self["webpackChunk"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/nonce */
/******/ 	(() => {
/******/ 		__webpack_require__.nc = undefined;
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	
/******/ })()
;