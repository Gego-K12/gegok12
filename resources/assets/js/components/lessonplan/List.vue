<template>
  <div class="relative">
    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
    <Teleport to="#approve_lesson_plan">
    <div class="flex flex-wrap lg:flex-row  items-center mb-5 justify-between">
        <div class="">
          <h1 class="admin-h1 my-3">Lesson Plans</h1>
        </div>
        <div class="relative flex items-center w-6/12 lg:w-1/2 md:w-1/2 justify-end">
          <div class="w-full relative mt-2">
            <input type="text" name="searches" id="searches" v-model="searches" class="border px-4 py-2 text-sm border-gray-400 w-full rounded bg-white shadow" placeholder="Search">
            <span class="input-group-btn absolute right-0 px-3 py-3 top-0">
              <a href="#" @click="searchLesson()">
                <svg class="w-4 h-4 fill-current text-gray-600" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30.239px" height="30.239px" viewBox="0 0 30.239 30.239" style="enable-background:new 0 0 30.239 30.239;" xml:space="preserve"><g><path d="M20.194,3.46c-4.613-4.613-12.121-4.613-16.734,0c-4.612,4.614-4.612,12.121,0,16.735 c4.108,4.107,10.506,4.547,15.116,1.34c0.097,0.459,0.319,0.897,0.676,1.254l6.718,6.718c0.979,0.977,2.561,0.977,3.535,0 c0.978-0.978,0.978-2.56,0-3.535l-6.718-6.72c-0.355-0.354-0.794-0.577-1.253-0.674C24.743,13.967,24.303,7.57,20.194,3.46z M18.073,18.074c-3.444,3.444-9.049,3.444-12.492,0c-3.442-3.444-3.442-9.048,0-12.492c3.443-3.443,9.048-3.443,12.492,0 C21.517,9.026,21.517,14.63,18.073,18.074z"/></g></svg>
              </a>
            </span>
          </div>
        </div>
        <div class="relative flex items-center  lg:w-1/4 md:w-1/4 justify-end" v-if="role != 'principal'">
          <div class="flex items-center w-full justify-end">
            <div class="flex items-center">
              <a :href="url+'/teacher/lessonplan/add'" class="no-underline text-white px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center">
                <span class="mx-1 text-sm font-semibold whitespace-no-wrap">Add Lesson Plan</span>
                <svg data-v-777009f9="" data-v-2a22d6ae="" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 409.6 409.6" xml:space="preserve" class="w-3 h-3 fill-current text-white"><g data-v-777009f9="" data-v-2a22d6ae=""><g data-v-777009f9="" data-v-2a22d6ae=""><path data-v-777009f9="" data-v-2a22d6ae="" d="M392.533,187.733H221.867V17.067C221.867,7.641,214.226,0,204.8,0s-17.067,7.641-17.067,17.067v170.667H17.067 C7.641,187.733,0,195.374,0,204.8s7.641,17.067,17.067,17.067h170.667v170.667c0,9.426,7.641,17.067,17.067,17.067 s17.067-7.641,17.067-17.067V221.867h170.667c9.426,0,17.067-7.641,17.067-17.067S401.959,187.733,392.533,187.733z"></path></g></g></svg>
              </a>
            </div>
          </div>
        </div>
      </div>
    </Teleport>
    <div class="">
      <div class="flex flex-wrap custom-table overflow-auto">
        <table class="w-full">
          <thead class="bg-grey-light">
            <tr class="border-b">
              <th class="text-left text-sm px-2 py-2 text-grey-darker" v-if="role == 'principal'">Teacher</th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker">Class</th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker">Subject</th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker">Unit No.</th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker">Unit Name</th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker">Title</th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker">Duration</th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker">Actions</th>
            </tr>
          </thead>   
          <tbody v-if="this.lessonplans != ''">
            <tr class="border-b" v-for="(lessonplan,index) in lessonplans">
              <td class="py-3 px-2" v-if="role == 'principal'">
                <p class="font-semibold text-xs">{{ lessonplan.teacher_fullname }}</p>
              </td>
              <td class="py-3 px-2">
                <p class="font-semibold text-xs">{{ lessonplan.class }}</p>
              </td>
              <td class="py-3 px-2">
                <p class="font-semibold text-xs">{{ lessonplan.subject }}</p>
              </td>
              <td class="py-3 px-2">
                <p class="font-semibold text-xs">{{ lessonplan.unit_no }}</p>
              </td>
              <td class="py-3 px-2">
                <p class="font-semibold text-xs">{{ lessonplan.unit_name }}</p>
              </td>
              <td class="py-3 px-2">
                <p class="font-semibold text-xs">{{ lessonplan.title }}</p>
              </td>
              <td class="py-3 px-2">
                <p class="font-semibold text-xs">{{ lessonplan.duration }}</p>
              </td>
              <td class="py-3 px-2">
                <div class="flex items-center">
                  <a :href="url+'/teacher/lessonplan/edit/'+lessonplan.id" v-if="role != 'principal' && (lessonplan.status == 'pending' || lessonplan.status == 'draft')" title="Edit">
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 477.873 477.873" xml:space="preserve" class="w-4 h-4 fill-current text-black-500 mx-1"><g><g><path d="M392.533,238.937c-9.426,0-17.067,7.641-17.067,17.067V426.67c0,9.426-7.641,17.067-17.067,17.067H51.2 c-9.426,0-17.067-7.641-17.067-17.067V85.337c0-9.426,7.641-17.067,17.067-17.067H256c9.426,0,17.067-7.641,17.067-17.067 S265.426,34.137,256,34.137H51.2C22.923,34.137,0,57.06,0,85.337V426.67c0,28.277,22.923,51.2,51.2,51.2h307.2 c28.277,0,51.2-22.923,51.2-51.2V256.003C409.6,246.578,401.959,238.937,392.533,238.937z"></path></g></g> <g><g><path d="M458.742,19.142c-12.254-12.256-28.875-19.14-46.206-19.138c-17.341-0.05-33.979,6.846-46.199,19.149L141.534,243.937 c-1.865,1.879-3.272,4.163-4.113,6.673l-34.133,102.4c-2.979,8.943,1.856,18.607,10.799,21.585 c1.735,0.578,3.552,0.873,5.38,0.875c1.832-0.003,3.653-0.297,5.393-0.87l102.4-34.133c2.515-0.84,4.8-2.254,6.673-4.13 l224.802-224.802C484.25,86.023,484.253,44.657,458.742,19.142z M434.603,87.419L212.736,309.286l-66.287,22.135l22.067-66.202 L390.468,43.353c12.202-12.178,31.967-12.158,44.145,0.044c5.817,5.829,9.095,13.72,9.12,21.955 C443.754,73.631,440.467,81.575,434.603,87.419z"></path></g></g></svg>
                  </a>

                  <a :href="url+'/teacher/lessonplan/show/'+lessonplan.id" title="Show">
                    <svg height="512pt" viewBox="-27 0 512 512" width="512pt" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-current text-black-500 mx-2"><path d="m188 492c0 11.046875-8.953125 20-20 20h-88c-44.113281 0-80-35.886719-80-80v-352c0-44.113281 35.886719-80 80-80h245.890625c44.109375 0 80 35.886719 80 80v191c0 11.046875-8.957031 20-20 20-11.046875 0-20-8.953125-20-20v-191c0-22.054688-17.945313-40-40-40h-245.890625c-22.054688 0-40 17.945312-40 40v352c0 22.054688 17.945312 40 40 40h88c11.046875 0 20 8.953125 20 20zm117.890625-372h-206c-11.046875 0-20 8.953125-20 20s8.953125 20 20 20h206c11.042969 0 20-8.953125 20-20s-8.957031-20-20-20zm20 100c0-11.046875-8.957031-20-20-20h-206c-11.046875 0-20 8.953125-20 20s8.953125 20 20 20h206c11.042969 0 20-8.953125 20-20zm-226 60c-11.046875 0-20 8.953125-20 20s8.953125 20 20 20h105.109375c11.046875 0 20-8.953125 20-20s-8.953125-20-20-20zm355.472656 146.496094c-.703125 1.003906-3.113281 4.414062-4.609375 6.300781-6.699218 8.425781-22.378906 28.148437-44.195312 45.558594-27.972656 22.324219-56.757813 33.644531-85.558594 33.644531s-57.585938-11.320312-85.558594-33.644531c-21.816406-17.410157-37.496094-37.136719-44.191406-45.558594-1.5-1.886719-3.910156-5.300781-4.613281-6.300781-4.847657-6.898438-4.847657-16.097656 0-22.996094.703125-1 3.113281-4.414062 4.613281-6.300781 6.695312-8.421875 22.375-28.144531 44.191406-45.554688 27.972656-22.324219 56.757813-33.644531 85.558594-33.644531s57.585938 11.320312 85.558594 33.644531c21.816406 17.410157 37.496094 37.136719 44.191406 45.558594 1.5 1.886719 3.910156 5.300781 4.613281 6.300781 4.847657 6.898438 4.847657 16.09375 0 22.992188zm-41.71875-11.496094c-31.800781-37.832031-62.9375-57-92.644531-57-29.703125 0-60.84375 19.164062-92.644531 57 31.800781 37.832031 62.9375 57 92.644531 57s60.84375-19.164062 92.644531-57zm-91.644531-38c-20.988281 0-38 17.011719-38 38s17.011719 38 38 38 38-17.011719 38-38-17.011719-38-38-38zm0 0"></path></svg>
                  </a>

                  <a href="#" @click="deletelesson(lessonplan.id)" v-if="role != 'principal' && (lessonplan.status == 'pending' || lessonplan.status == 'draft')" title="Delete">
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-4 h-4 fill-current text-black-500 mx-1"><g><g><g><polygon points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804"></polygon><rect x="235.948" y="175.791" width="40.104" height="237.285"></rect><polygon points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804"></polygon><path d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74 c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474 L380.665,471.896z"></path></g></g></g> <g><g><path d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42 C354.924,14.992,339.932,0,321.504,0z"></path></g></g></svg>
                  </a>

                  <a href="#" class="capitalize text-white rounded px-1 py-1 font-medium activate" @click="approvelesson(lessonplan.id)" v-if="role == 'principal' && lessonplan.status == 'pending'"><svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-5 h-5 fill-current text-green-600 mx-auto"><g><g><path d="M383.841,171.838c-7.881-8.31-21.02-8.676-29.343-0.775L221.987,296.732l-63.204-64.893 c-8.005-8.213-21.13-8.393-29.35-0.387c-8.213,7.998-8.386,21.137-0.388,29.35l77.492,79.561 c4.061,4.172,9.458,6.275,14.869,6.275c5.134,0,10.268-1.896,14.288-5.694l147.373-139.762 C391.383,193.294,391.735,180.155,383.841,171.838z"></path></g></g><g><g><path d="M256,0C114.84,0,0,114.84,0,256s114.84,256,256,256s256-114.84,256-256S397.16,0,256,0z M256,470.487c-118.265,0-214.487-96.214-214.487-214.487c0-118.265,96.221-214.487,214.487-214.487c118.272,0,214.487,96.221,214.487,214.487C470.487,374.272,374.272,470.487,256,470.487z"></path></g></g></svg></a>

                  <a href="#" class="capitalize text-white  rounded px-1 py-1 font-medium activate" @click="rejectlesson(lessonplan.id)" v-if="role == 'principal' && lessonplan.status == 'pending'">
                    <svg height="512pt" viewBox="0 0 512 512" width="512pt" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mx-auto text-red-600 fill-current"><path d="m256 512c-141.160156 0-256-114.839844-256-256s114.839844-256 256-256 256 114.839844 256 256-114.839844 256-256 256zm0-475.429688c-120.992188 0-219.429688 98.4375-219.429688 219.429688s98.4375 219.429688 219.429688 219.429688 219.429688-98.4375 219.429688-219.429688-98.4375-219.429688-219.429688-219.429688zm0 0"></path><path d="m347.429688 365.714844c-4.679688 0-9.359376-1.785156-12.929688-5.359375l-182.855469-182.855469c-7.144531-7.144531-7.144531-18.714844 0-25.855469 7.140625-7.140625 18.714844-7.144531 25.855469 0l182.855469 182.855469c7.144531 7.144531 7.144531 18.714844 0 25.855469-3.570313 3.574219-8.246094 5.359375-12.925781 5.359375zm0 0"></path><path d="m164.570312 365.714844c-4.679687 0-9.355468-1.785156-12.925781-5.359375-7.144531-7.140625-7.144531-18.714844 0-25.855469l182.855469-182.855469c7.144531-7.144531 18.714844-7.144531 25.855469 0 7.140625 7.140625 7.144531 18.714844 0 25.855469l-182.855469 182.855469c-3.570312 3.574219-8.25 5.359375-12.929688 5.359375zm0 0"></path></svg>
                  </a>

                  <a :href="url+'/teacher/lessonplan/print/'+lessonplan.id" target="_blank" v-if="lessonplan.status == 'approved'">
                    <svg id="Layer" enable-background="new 0 0 64 64" height="512" viewBox="0 0 64 64" width="512" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current text-black-500 mx-1"><path d="m30.586 45.414c.39.391.902.586 1.414.586s1.023-.195 1.414-.586l10-10c.781-.781.781-2.047 0-2.828s-2.047-.781-2.828 0l-6.586 6.586v-29.172c0-1.104-.896-2-2-2s-2 .896-2 2v29.172l-6.586-6.586c-.78-.781-2.048-.781-2.828 0-.781.781-.781 2.047 0 2.828z"></path><path d="m18 56h28c3.309 0 6-2.691 6-6v-8c0-1.104-.896-2-2-2s-2 .896-2 2v8c0 1.103-.897 2-2 2h-28c-1.103 0-2-.897-2-2v-8c0-1.104-.896-2-2-2s-2 .896-2 2v8c0 3.309 2.691 6 6 6z"></path></svg>
                  </a>

                  <a href="#" v-if="role == 'principal' && lessonplan.status == 'approved'">
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-4 h-4 fill-current text-green-600 mx-auto"><g><g><path d="M383.841,171.838c-7.881-8.31-21.02-8.676-29.343-0.775L221.987,296.732l-63.204-64.893 c-8.005-8.213-21.13-8.393-29.35-0.387c-8.213,7.998-8.386,21.137-0.388,29.35l77.492,79.561 c4.061,4.172,9.458,6.275,14.869,6.275c5.134,0,10.268-1.896,14.288-5.694l147.373-139.762 C391.383,193.294,391.735,180.155,383.841,171.838z"></path></g></g> <g><g><path d="M256,0C114.84,0,0,114.84,0,256s114.84,256,256,256s256-114.84,256-256S397.16,0,256,0z M256,470.487 c-118.265,0-214.487-96.214-214.487-214.487c0-118.265,96.221-214.487,214.487-214.487c118.272,0,214.487,96.221,214.487,214.487 C470.487,374.272,374.272,470.487,256,470.487z"></path></g></g></svg>
                  </a>

                  <a href="#" v-if="role == 'principal' && lessonplan.status == 'rejected'">
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-4 h-4 fill-current text-green-600 mx-auto"><g><g><path d="M383.841,171.838c-7.881-8.31-21.02-8.676-29.343-0.775L221.987,296.732l-63.204-64.893 c-8.005-8.213-21.13-8.393-29.35-0.387c-8.213,7.998-8.386,21.137-0.388,29.35l77.492,79.561 c4.061,4.172,9.458,6.275,14.869,6.275c5.134,0,10.268-1.896,14.288-5.694l147.373-139.762 C391.383,193.294,391.735,180.155,383.841,171.838z"></path></g></g> <g><g><path d="M256,0C114.84,0,0,114.84,0,256s114.84,256,256,256s256-114.84,256-256S397.16,0,256,0z M256,470.487 c-118.265,0-214.487-96.214-214.487-214.487c0-118.265,96.221-214.487,214.487-214.487c118.272,0,214.487,96.221,214.487,214.487 C470.487,374.272,374.272,470.487,256,470.487z"></path></g></g></svg>
                  </a>

                 <a href="#"
                     @click.prevent="openPublishModal(lessonplan)"
                     v-if="role != 'principal' && lessonplan.status == 'approved' && lessonplan.is_published == 0"
                     title="Publish">
                    <svg version="1.1" id="publish_icon" xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 512 512"
                         class="w-5 h-5 fill-current text-green-600 mx-1">
                      <g>
                        <path d="M256 0C114.84 0 0 114.84 0 256s114.84 256 256 256 256-114.84 256-256S397.16 0 256 0zm0 470.487c-118.265 0-214.487-96.214-214.487-214.487S137.735 41.513 256 41.513 470.487 137.735 470.487 256 374.272 470.487 256 470.487z"/>
                        <path d="M383.841 171.838c-7.881-8.31-21.02-8.676-29.343-.775L221.987 296.732l-63.204-64.893c-8.005-8.213-21.13-8.393-29.35-.387-8.213 7.998-8.386 21.137-.388 29.35l77.492 79.561c4.061 4.172 9.458 6.275 14.869 6.275 5.134 0 10.268-1.896 14.288-5.694l147.373-139.762c8.316-7.888 8.668-21.027.774-29.344z"/>
                      </g>
                    </svg>
                  </a>

                  <span v-if="role != 'principal' && lessonplan.status == 'approved' && lessonplan.is_published == 1"
                        class="text-green-600 text-xs font-semibold mx-1">
                    Published
                  </span>

                </div>
              </td>
            </tr>
          </tbody>
          <tbody v-if="this.lessonplans == ''">
            <tr class="border-b" v-if="this.role != 'principal'">
              <td colspan="7">
                <p class="font-semibold text-s" style="text-align: center">No Records Found</p>
              </td>
            </tr>
            <tr class="border-b" v-else>
              <td colspan="8">
                <p class="font-semibold text-s" style="text-align: center">No Records Found</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <!-- Publish Modal -->
      <div v-if="showPublishModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded shadow-lg w-11/12 md:w-1/3 p-5">
          <h2 class="text-lg font-semibold mb-4">Publish Lesson Plan</h2>

          <div class="mb-3">
            <label class="block text-sm font-semibold mb-1">Start Date</label>
            <input type="date"
                   v-model="publishForm.start_date"
                   :min="todayDate"
                   class="border px-3 py-2 w-full rounded">

            <p v-if="errors.start_date" class="text-red-600 text-xs mt-1">
              {{ errors.start_date[0] }}
            </p>
          </div>

          <div class="mb-3">
            <label class="block text-sm font-semibold mb-1">End Date</label>
            <input type="date"
                   v-model="publishForm.end_date"
                   :min="publishForm.start_date || todayDate"
                   class="border px-3 py-2 w-full rounded">

            <p v-if="errors.end_date" class="text-red-600 text-xs mt-1">
              {{ errors.end_date[0] }}
            </p>
          </div>

          <div class="flex justify-end mt-5">
            <button type="button"
                    class="px-4 py-2 bg-gray-400 text-white rounded mr-2"
                    @click="closePublishModal">
              Cancel
            </button>

            <button type="button"
                    class="px-4 py-2 custom-green text-white rounded"
                    @click="publishLessonPlan">
              Submit
            </button>
          </div>
        </div>
      </div>
  </div>
</template>

<script>

  import { bus } from "../../app";
  export default {
    props:['url' , 'role'],
    data () {
      return {
        lessonplans:[],
        searches:'',
        status:'',
        params:{},
        errors:[],
        success:null,
        showPublishModal: false,
        selectedLessonPlanId: null,
        todayDate: new Date().toISOString().slice(0, 10),
        publishForm: {
          start_date: '',
          end_date: '',
        },
      }
    },

    methods:{
      getData(url)
      {
        axios.get(url).then(response => {
          this.lessonplans    = response.data.data;
          //console.log(this.lessonplans);    
        });
      },

      deletelesson(id) 
      {
        var thisswal = this;
        swal({
          title: 'Are you sure',
          text: 'Do you want to delete this lesson plan ?',
          icon: "info",
          buttons: [
            'No',
            'Yes'
          ],
          dangerMode: true,
        }).then(function(isConfirm) {
          if (isConfirm) 
          {
            axios.get(thisswal.url+ '/teacher/lessonplan/delete/'+ id).then(response => {
              thisswal.success = response.data.success;
              window.location.reload();
            });
          }
          else 
          {
            swal("Cancelled");
          }
        });
      },

      approvelesson(id) 
      {
        var thisswal = this;
        swal({
          title: 'Are you sure',
          text: 'Do you want to approve this lesson plan ?',
          icon: "info",
          buttons: [
            'No',
            'Yes'
          ],
          dangerMode: true,
        }).then(function(isConfirm) {
          if (isConfirm) 
          {
            window.location.href = thisswal.url+ '/teacher/lessonplan/show/'+id+'?status=approve';
          }
          else 
          {
            swal("Cancelled");
          }
        });
      },

      rejectlesson(id) 
      {
        var thisswal = this;
        swal({
          title: 'Are you sure',
          text: 'Do you want to reject this lesson plan ?',
          icon: "info",
          buttons: [
            'No',
            'Yes'
          ],
          dangerMode: true,
        }).then(function(isConfirm) {
          if (isConfirm) 
          {
            window.location.href = thisswal.url+ '/teacher/lessonplan/show/'+id+'?status=reject'; 
          }
          else 
          {
            swal("Cancelled");
          }
        });
      },

      searchLesson()
      {
        this.params = { searches:this.searches };

        this.final = this.url+'/teacher/lessonplan/list/'+this.status;
          
        Object.keys(this.params).forEach(key => {
          this.final = this.addParam(this.final, key, this.params[key])
        });
        axios.get(this.final).then(response => {
          this.lessonplans    = response.data.data;
          //console.log(this.lessonplans);    
        });
      },

      addParam(url, param, value) 
      {
        param = encodeURIComponent(param);
        var r = "([&?]|&amp;)" + param + "\\b(?:=(?:[^&#]*))*";
        var a = document.createElement('a');
        var regex = new RegExp(r);
        var str = param + (value ? "=" + encodeURIComponent(value) : ""); 
        a.href = url;
        var q = a.search.replace(regex, "$1"+str);
        if (q === a.search) 
        {
          a.search += (a.search ? "&" : "") + str;
        } 
        else 
        {
          a.search = q;
        }
        return a.href ;
      },
      openPublishModal(lessonplan)
      {
        this.errors = {};
        this.selectedLessonPlanId = lessonplan.id;

        this.publishForm.start_date = lessonplan.start_date ? lessonplan.start_date : this.todayDate;
        this.publishForm.end_date = lessonplan.end_date ? lessonplan.end_date : this.todayDate;

        this.showPublishModal = true;
      },

      closePublishModal()
      {
        this.showPublishModal = false;
        this.selectedLessonPlanId = null;
        this.publishForm.start_date = '';
        this.publishForm.end_date = '';
        this.errors = {};
      },

      publishLessonPlan()
      {
        this.errors = {};

        axios.post(this.url + '/teacher/lesson-plans/' + this.selectedLessonPlanId + '/publish', this.publishForm)
          .then(response => {
            this.success = response.data.message;
            this.closePublishModal();
            this.getData('/teacher/lessonplan/list/' + this.status);
          })
          .catch(error => {
            if (error.response && error.response.status == 422) {
              this.errors = error.response.data.errors ? error.response.data.errors : {};

              swal(
                "Validation Error",
                error.response.data.message ? error.response.data.message : "Please check the form",
                "error"
              );
            } else {
              swal("Error", "Something went wrong", "error");
            }
          });
      },
    },
  
    created()
    {   
      if(this.role == 'principal')
      {
        this.status = 'pending';
      }
      else 
      {
        this.status = 'draft';
      }   
      this.getData('/teacher/lessonplan/list/'+this.status); 
      bus.on("statusTab", data => {
        if(data!='')
        {
          this.status=data;      
          this.getData('/teacher/lessonplan/list/'+this.status);             
        }
      });
    }
  }
</script>