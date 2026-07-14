<template>
    <div>
        <Teleport to="#student_count">
            <div class="">
                <h1 class="admin-h1 my-3">Students ( {{ Object.keys(this.users).length }} )</h1>
            </div>
        </Teleport>
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
        <div class="my-4 filter-alphabet">
            <ul class="list-reset flex flex-wrap">
                <li v-for="alphabet in alphabets">
                    <a href="#" id="filter" class="block font-bold p-2 bg-grey-light border border-grey mx-2 ni" v-bind:class="letter === alphabet?'active':'text-blue'" v-text="alphabet"  @click="sortMembers(alphabet)"> </a>   
                </li>
                <li>
                    <a href="#" class="block font-bold p-2 bg-grey-light border border-grey mx-2 ni" @click="clearAll()">Clear All</a>   
                </li>
            </ul>
            <!-- <div class="my-4" v-if="!filteredNames.length">No names for this letter</div> -->
            <div class="" v-if="filteredNames.length"></div>
            <!-- <div class="list-reset flex flex-wrap">
                    <a @click="customexport()"  id="export-button" class="no-underline text-white px-4 my-3 mx-1 flex items-center custom-green py-1">
                        <span class="mx-1 text-sm font-semibold">Custom Export</span>
                    </a> 
                </div> -->
        </div>

        <div>
            <memberdetails :url="this.url"></memberdetails>
            <div id="memberdetail"></div>
            <div class="my-8">
                <div class="w-full flex flex-wrap items-center justify-between mb-4">
                    <div class="flex items-center text-sm">
                        <div class="px-3 border-r" v-if="this.selectedUsersCount > 0">
                            {{ parseInt(this.selectedUsersCount) }} students selected
                        </div>
                        <div class="px-3 border-r relative">
                            <input class="opacity-0 absolute w-full h-full cursor-pointer" type="checkbox" @click="selectAll($event)" v-model="allSelected"><span>Select All</span>
                        </div>
                        <div class="px-3 relative" v-if="this.selectedUsersCount > 0">
                            <input class="opacity-0 absolute w-full h-full cursor-pointer" type="checkbox" @click="selectNone($event)" v-model="noneSelected"><span>Select None</span>
                        </div>
                    </div> 
                    <div class="relative flex items-center w-full lg:w-1/3 md:w-1/3 lg:justify-end mx-3 lg:mx-0 md:mx-0 my-2 lg:my-0 md:my-0" v-if="this.selectedUsersCount > 0">
                        <a
                            href="#"
                            class="no-underline text-white px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center"
                            @click="showModal('tag')"
                        >
                            Add Tag
                        </a>
                        <a href="#" class="btn btn-submit blue-bg text-white px-3 py-1 text-sm font-medium rounded mx-1" @click="showModal('message')">Send Message</a>
                       <a href="#" class="btn btn-submit blue-bg text-white px-3 py-1  text-sm font-medium rounded mx-1" @click="showModal('shift')">Shift</a>
                       <a href="#" class="no-underline text-white  px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center" @click="showModal('group')">Add Group</a>
                        <a v-if="isFeeEnabled" href="#" class="no-underline text-white  px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center" @click="showModal('fees')">
                            <span class="mx-1 text-sm font-semibold">Add Fees Details</span>
                            <svg class="w-3 h-3 fill-current text-white" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 409.6 409.6" style="enable-background:new 0 0 409.6 409.6;" xml:space="preserve"><g><g><path d="M392.533,187.733H221.867V17.067C221.867,7.641,214.226,0,204.8,0s-17.067,7.641-17.067,17.067v170.667H17.067 C7.641,187.733,0,195.374,0,204.8s7.641,17.067,17.067,17.067h170.667v170.667c0,9.426,7.641,17.067,17.067,17.067 s17.067-7.641,17.067-17.067V221.867h170.667c9.426,0,17.067-7.641,17.067-17.067S401.959,187.733,392.533,187.733z"/></g></g></svg>
                        </a>
                        <!-- <a href="#" class="btn btn-submit blue-bg text-white text-sm font-medium rounded " @click="buspass()">Bus Pass</a> -->
                    </div>
                </div>
                <div class="flex flex-wrap" v-if="Object.keys(this.users).length > 0">
                    <div class="w-full lg:w-1/4 md:w-1/2 my-2 relative" v-for="user in users">
                        <div class="flex justify-between member-list">
                            <div class="flex items-center  student_select">
                                <!-- <input v-if="user.status=='active'" class="w-5 h-5" type="checkbox" v-model="selected" :value="user['parent_id']" @click="selectedCount(user['id'],$event)"> -->
                                <input v-if="user.status=='active'" class="w-5 h-5" type="checkbox" :checked="selectedUsers.includes(user.id)" @click="selectedCount(user.id, user.parent_id, $event)">
                                <label></label>
                            </div>

                            <div class="flex p-2  w-full" v-bind:class="[user['status']=='active' ? 'active': 'bg-red-300' ]" :id="user['id']" @click="enableform(user['name'])">
                                <img :src="user['avatar']" class="w-16 h-16">
                                <div class="px-2">
                                    <h2 class="font-bold text-base text-gray-700">{{ user['fullname'] }}</h2>
                                    <p>{{ user['class'] }}</p>
                                    <p v-if="birthday == 'true'">{{ user['date_of_birth'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap" v-else>
                    <div class="w-full">
                        <div class="flex justify-between">
                            <p style="text-align: center;">No Students Found</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="this.tab == 'message'" class="modal modal-mask">
            <div class="modal-wrapper px-4">
                <div class="modal-container w-full  max-w-md px-8 mx-auto">
                    <div class="modal-header flex justify-between items-center">
                        <h2>Send Message</h2>
                        <button id="close-button" class="modal-default-button text-2xl py-1"  @click="closeModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="flex items-center">
                            <div class="w-full lg:w-1/4"> 
                                <label for="subject" class="tw-form-label">Subject</label>
                            </div>
                            <div class="my-2 w-full lg:w-3/4">
                                <input type="text" name="subject" v-model="subject" class="tw-form-control w-full" placeholder="Enter Subject">
                                <span v-if="errors.subject" class="text-red-500 text-xs font-semibold">{{errors.subject[0]}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="flex">
                            <div class="w-full lg:w-1/4">
                                <label for="message" class="tw-form-label">Message</label>
                            </div>
                            <div class="w-full lg:w-3/4">
                                <textarea type="text" name="message" v-model="message" class="tw-form-control w-full" rows="10" placeholder="Enter Message"></textarea>
                                <span v-if="errors.message" class="text-red-500 text-xs font-semibold">{{errors.message[0]}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="flex items-center">
                            <div class="w-6">
                                <input type="checkbox" name="send_later" v-model="send_later" class="tw-form-control w-full" @click="enableDate($event)">
                            </div>
                            <div class="mx-1"> 
                                <label for="subject" class="tw-form-label">Send Later</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body" v-if="this.show == 'executed'">
                        <div class="flex">
                            <div class="w-full lg:w-1/4">
                                <label for="executed_at" class="tw-form-label">Date Time</label>
                            </div>
                            <div class="w-full lg:w-3/4">
                                <VueDatePicker
                                  v-model="executed_at"
                                  format="dd-MM-yyyy HH:mm:ss"
                                  model-type="format"
                                  :enable-time-picker="true"
                                  :is-24="true"
                                  :auto-apply="true"
                                  input-class-name="w-full rounded"
                                />
                                <span v-if="errors.executed_at" class="text-red-500 text-xs font-semibold">{{errors.executed_at[0]}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="my-6">
                        <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submit()">Send</a>
                    </div>
                </div>
            </div>
        </div>


         <div v-if="this.tab == 'shift'" class="modal modal-mask">
            <div class="modal-wrapper px-4">
                <div class="modal-container w-full  max-w-md px-8 mx-auto">
                    <div class="modal-header flex justify-between items-center">
                        <h2>Shift Students</h2>
                        <button id="close-button" class="modal-default-button text-2xl py-1"  @click="closeModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="flex items-center">
                            <div class="w-full lg:w-1/4"> 
                                <label for="subject" class="tw-form-label">Select Standard</label>
                            </div>
                            <div class="my-2 w-full lg:w-3/4">
                                 <select class="tw-form-control w-full" id="shift_std" v-model="shift_std" name="shift_std">
                                <option value="" disabled>Select Class</option>
                                <option v-for="standard in standardLinks" v-bind:value="standard.id">{{ standard.standard_section }}</option>
                            </select>
                            <span v-if="errors.shift_std"><p class="text-red-500 text-xs font-semibold">{{errors.shift_std[0]}}</p></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="my-6">
                        <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="shiftstudents()">Shift</a>
                    </div>
                </div>
            </div>
        </div>

         <div v-if="this.exporttab == 1" class="modal modal-mask">
            <div class="modal-wrapper px-4">
                <div class="modal-container w-full  max-w-md px-8 mx-auto">
                    <div class="modal-header flex justify-between items-center">
                        <h2>Custom Export</h2>
                        <button id="close-button" class="modal-default-button text-2xl py-1" @click="closeexport()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <input type="checkbox" id="name" value="name" v-model="checkedNames">
                        <label for="name">Name</label><br>
                        <input type="checkbox" id="email" value="email" v-model="checkedNames">
                        <label for="email">Email</label><br>
                        <input type="checkbox" id="mobile" value="mobile_no" v-model="checkedNames">
                        <label for="mobile">Mobile Number</label><br>
                        <input type="checkbox" id="standard" value="standard" v-model="checkedNames">
                        <label for="standard">Standard</label><br>
                        <input type="checkbox" id="gender" value="gender" v-model="checkedNames">
                        <label for="gender">Gender</label><br>
                    </div>
                    <div class="my-6">
                        <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitExport()">Submit</a>
                        <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="resetform()">Reset</a>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="this.tab == 'fees'" class="modal modal-mask">
            <div class="modal-wrapper px-4">
                <div class="modal-container w-full  max-w-md px-8 mx-auto">
                    <div class="modal-header flex justify-between items-center">
                        <h2>Fees Payment Detail</h2>
                        <button id="close-button" class="modal-default-button text-2xl py-1" @click="closeModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <table class="w-full">
                            <thead class="bg-grey-light">
                                <tr class="border-b">
                                    <th class="text-left text-sm px-2 py-2 text-grey-darker"></th>
                                    <th class="text-left text-sm px-2 py-2 text-grey-darker"> Title </th>
                                    <th class="text-left text-sm px-2 py-2 text-grey-darker"> Class </th>
                                    <th class="text-left text-sm px-2 py-2 text-grey-darker"> Term </th>
                                    <th class="text-left text-sm px-2 py-2 text-grey-darker"> Amount </th>
                                    <th class="text-left text-sm px-2 py-2 text-grey-darker"> Due Date </th>
                                </tr>
                            </thead>   
                            <tbody v-if="this.feelist != ''">
                                <tr class="border-b" v-for="fee in feelist">
                                    <td class="py-3 px-2">
                                        <input type="radio" v-model="fee_id" :value="fee['id']" @click="selectedFee(fee['id'],fee['standardLink_id'])"><label></label>
                                    </td>
                                    <td class="py-3 px-2">
                                        <p class="font-semibold text-xs">{{ fee['name'] }}</p>
                                    </td>
                                    <td class="py-3 px-2">
                                        <p class="font-semibold text-xs">{{ fee['standardLink_id'] }}</p>
                                    </td>
                                    <td class="py-3 px-2">
                                        <p class="font-semibold text-xs">{{ fee['term'] }}</p>
                                    </td>
                                    <td class="py-3 px-2">
                                        <p class="font-semibold text-xs">{{ fee['amount'] }}</p>
                                    </td>
                                    <td class="py-3 px-2">
                                        <p class="font-semibold text-xs">{{ fee['end_date'] }}</p>
                                    </td>
                                </tr>
                                <span v-if="errors.fee_id" class="text-red-500 text-xs font-semibold">{{errors.fee_id[0]}}</span>
                            </tbody>
                            <tbody v-if="this.feelist == ''">
                                <tr class="border-b">
                                    <td colspan="6">
                                        <p class="font-semibold text-s" style="text-align: center">No Records Found</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-body" v-if="this.show == 'paid'">
                        <div class="flex">
                            <div class="w-full lg:w-1/4">
                                <label for="paid_on" class="tw-form-label">Paid On</label>
                            </div>
                            <div class="w-full lg:w-3/4">
                                <input type="date" name="paid_on" v-model="paid_on" class="tw-form-control w-full" id="paid_on">
                                <span v-if="errors.paid_on" class="text-red-500 text-xs font-semibold">{{errors.paid_on[0]}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body" v-if="this.show == 'paid'">
                        <div class="flex items-center">
                            <div class="w-6">
                                <input type="checkbox" name="notify_parent" v-model="notify_parent" class="tw-form-control w-full" @click="addNotify($event)">
                            </div>
                            <div class="mx-1"> 
                                <label for="notify_parent" class="tw-form-label">Notify Parent</label>
                            </div>
                        </div>
                    </div>
                    <div class="my-6">
                        <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitFee()">Submit</a>
                        <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="resetform()">Reset</a>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="this.tab == 'group'" class="modal modal-mask">
    <div class="modal-wrapper px-4" @click.self="closeModal()">
        <div class="modal-container w-full max-w-md mx-auto bg-white rounded-xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="modal-header flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-800">Add Students To Group</h2>
                <button
                    class="modal-default-button text-gray-400 hover:text-gray-600 hover:bg-gray-200 rounded-full w-8 h-8 flex items-center justify-center text-xl transition-colors"
                    @click="closeModal()"
                >
                    &times;
                </button>
            </div>

            <!-- Body -->
            <div class="modal-body px-6 py-5">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">
                    Select Group
                </label>
                <select
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-400 focus:border-blue-400 transition-shadow"
                    v-model="group_id"
                >
                    <option value="">Select Group</option>
                    <option
                        v-for="group in groups"
                        :key="group.id"
                        :value="group.id"
                    >
                        {{ group.group_name ? group.group_name.replace(/\b\w/g, char => char.toUpperCase()) : '' }}
                    </option>
                </select>

                <span
                    v-if="errors.group_id"
                    class="text-red-500 text-xs font-medium mt-1.5 block"
                >
                    {{ errors.group_id[0] }}
                </span>
                <span
                    v-if="errors.selectedUsers"
                    class="text-red-500 text-xs font-medium block mt-2"
                >
                    {{ errors.selectedUsers[0] }}
                </span>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <button
                    class="px-4 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-200 transition-colors"
                    @click="closeModal()"
                >
                    Cancel
                </button>
                <button
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-colors"
                    @click="submitGroup()"
                >
                    Update Group
                </button>
            </div>
        </div>
    </div>
</div>
<div v-if="this.tab == 'tag'" class="modal modal-mask">
    <div class="modal-wrapper px-4" @click.self="closeModal()">
        <div class="modal-container w-full max-w-md mx-auto bg-white rounded-xl shadow-2xl overflow-hidden">

            <!-- Header -->
            <div class="modal-header flex justify-between items-center px-6 py-4 border-b border-gray-100 bg-gray-50">
                <div class="flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5a1.99 1.99 0 011.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <h2 class="text-lg font-semibold text-gray-800">Add Tag To Students</h2>
                </div>
                <button
                    class="w-8 h-8 flex items-center justify-center rounded-full text-gray-400 hover:bg-gray-200 hover:text-gray-600 transition-colors text-xl"
                    @click="closeModal()"
                >
                    &times;
                </button>
            </div>

            <!-- Body -->
            <div class="modal-body px-6 py-5 space-y-4">

                <!-- Existing Tag Select -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                        Select Existing Tag
                    </label>
                    <select
                        v-model="tag_name"
                        class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition-all appearance-none"
                        style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 fill=%22none%22 viewBox=%220 0 20 20%22><path stroke=%22%236b7280%22 stroke-width=%221.5%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22 d=%22M6 8l4 4 4-4%22/></svg>'); background-repeat: no-repeat; background-position: right 0.7rem center; background-size: 1.2em; padding-right: 2.5rem;"
                    >
                        <option value="">— Choose a tag —</option>
                        <option v-for="tag in tags" :key="tag.id" :value="tag.tag_name">
                            {{ tag.tag_name }}
                        </option>
                    </select>
                </div>

                <!-- Divider with OR -->
                <div class="flex items-center gap-3">
                    <div class="flex-1 h-px bg-gray-100"></div>
                    <span class="text-xs font-medium text-gray-400">OR</span>
                    <div class="flex-1 h-px bg-gray-100"></div>
                </div>

                <!-- Create New Tag Toggle -->
                <div>
                    <button
                        type="button"
                        @click="showNewTag = !showNewTag"
                        class="w-full flex items-center justify-between text-xs font-semibold text-gray-600 px-3 py-2 rounded-lg border border-dashed border-gray-300 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600 transition-all"
                    >
                        <span class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                            </svg>
                            Create a new tag
                        </span>
                        <svg
                            class="w-4 h-4 transition-transform"
                            :class="showNewTag ? 'rotate-180' : ''"
                            fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <transition name="slide-down">
                        <div v-if="showNewTag" class="mt-2">
                            <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">
                                Tag Name
                            </label>
                            <input
                                type="text"
                                v-model="new_tag_name"
                                placeholder="e.g. Needs Extra Support"
                                class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition-all"
                            />
                        </div>
                    </transition>
                </div>

                <!-- Validation errors -->
                <span
                    v-if="errors.tag_name"
                    class="text-red-500 text-xs font-medium block"
                >
                    {{ errors.tag_name[0] }}
                </span>
                <span
                    v-if="errors.selectedUsers"
                    class="text-red-500 text-xs font-medium block"
                >
                    {{ errors.selectedUsers[0] }}
                </span>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end gap-3">
                <button
                    type="button"
                    class="px-4 py-2 text-sm font-medium text-gray-600 rounded-lg hover:bg-gray-200 transition-colors"
                    @click="closeModal()"
                >
                    Cancel
                </button>
                <button
                    type="button"
                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition-colors"
                    @click="submitTag()"
                >
                    Save Tag
                </button>
            </div>

        </div>
    </div>
</div>
    </div>
</template>

<script>
    import { bus } from "../../app";
    import memberdetails from './Detail';
    import { VueDatePicker } from '@vuepic/vue-datepicker'
    import '@vuepic/vue-datepicker/dist/main.css'

    export default {
        props:['url' , 'searchquery' , 'letter' , 'standard' , 'birthday' , 'selected_standard'],

        components:
        {
            memberdetails,
            VueDatePicker,
        },
        data(){
            return{
                users:[],
                feelist:[],
                standardLink_id:'',
                fee_id:'',
                notify_parent:'',
                paid_on:'',
                user:'',
                alphabets: ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'],
                selectedLetter: undefined,
                active: false,
                selected: [],
                selectedUsers:[],
                selectedUsersCount:0,
                send_later:'',
                allSelected: false,
                noneSelected:false,
                subject:'',
                message:'',
                executed_at:'',
                tab:'',
                show:'',
                errors:[],
                success:null,
                exporttab:'',
                checkedNames:[],
                standardLinks:[],
                shift_std:'',
                isFeeEnabled: window.AppConfig?.gfee_enabled ?? false,
                groups: [],
                group_id: '',
                tag_name: '',
                tags: [],
                new_tag_name: '',
                showNewTag: false,
            }
        },

        created() 
        {
            this.selectedLetter = this.letter || null;
            axios.get('/admin/students/find?'+this.searchquery).then(response => {
                this.users = response.data.data;
            });
            this.getUrl();
            if(this.tab=='fees')
            {
                this.getFeeDetails();
            }
            
            this.getStandardsList();


        },

        computed: 
        {
            filteredNames () 
            {
                let users = this.users
                if (this.selectedLetter) 
                {
                    users = users.filter((name) => {
                        let firstLetter = name.charAt(0).toUpperCase()
                        return firstLetter === this.selectedLetter
                    })
                }
                return users
            }
        },

        methods:
        {
            getFeeDetails()
            {
                if(this.searchquery != '')
                {
                    axios.get('/admin/feedetails/list?'+this.searchquery).then(response => {
                        this.feelist = response.data.data;
                        console.log();this.feelist
                    });
                }
                else
                {
                    axios.get('/admin/feedetails/list?standard='+this.selected_standard).then(response => {
                        this.feelist = response.data.data;
                        //console.log(this.feelist);
                    })
                }
            },

            getStandardsList()
            {
                axios.get('/admin/homework/list').then(response => {
                    this.standardLinks = response.data.standardlist;

                });
            },

            clearAll()
            {
                window.location.href = '/admin/students';
            },

            selectedFee(id,standardLink_id = null)
            {
                this.show = 'paid';
                this.standardLink_id = standardLink_id;
            },

            enableform(val)
            {
                this.success=null;
                $('#show-detail').removeClass('hide-menu').addClass('block');
                bus.emit("dataMemberName", val);
            },
            
            sortMembers(name)
            {
                this.selectedLetter= name; 
                this.active = true; 
                var q='alphabet='+this.selectedLetter;
                //var url = window.location.href; 
                var url = this.currenturl;  

                if (window.location.search.indexOf('alphabet=') > -1) 
                {
                    var href = new URL(url); 
                    href.searchParams.set('alphabet', this.selectedLetter);
                    url=href.toString();       
                } 
                else 
                {
                    if (url.indexOf('?') > -1)
                    {
                        url += '&'
                    }
                    else
                    {
                        url += '?'
                    }
                    url += q;
                }
                window.location.href = url;
            },

            getUrl()
            {
                this.currenturl =  this.url+"/admin/students"; 
                if(this.searchquery!='')
                {
                    this.currenturl =  this.currenturl+'?'+this.searchquery;
                } 
                if(this.standard != '')
                {
                    this.currenturl =  this.currenturl+'?standard='+this.standard;
                }
            },

            selectAll(e) 
            { 
                var selected = [];
                var selectedUsers = [];
                if (e.target.checked) 
                {
                    $('.member-list').addClass('student_selected');
                    if(this.allSelected == false) 
                    {
                        this.users.forEach(function (user) 
                        {
                            selectedUsers.push(user.id);
                            selected.push(user.parent_id);
                        });
                        this.selected = selected; 
                        this.selectedUsers = selectedUsers; 
                        this.selectedUsersCount = selected.length;
                        this.allSelected = true;
                    }
                }
                else
                {
                    this.users.forEach(function (user) 
                    {
                        selected.splice(user.parent_id);
                        selectedUsers.splice(user.id);
                    });
                    this.selected = selected; 
                    this.selectedUsers = selectedUsers;
                    this.selectedUsersCount = selected.length;
                    this.noneSelected = false;
                    $('.member-list').removeClass('student_selected');
                }
            },

            selectNone(e) 
            { 
                var selected = [];
                var selectedUsers = [];
                if (e.target.checked) 
                {
                    $('.member-list').removeClass('student_selected');
                    this.users.forEach(function (user) 
                    {
                        selected.splice(user.id);
                        selectedUsers.splice(user.id);
                    });
                    this.selected = selected;
                    this.selectedUsers = selectedUsers;
                    this. selectedUsersCount= selected.length;
                    this.noneSelected = false;
                }
            },
      
            showModal(value)
            {
                if(value === 'fees' && !this.isFeeEnabled){
                    return; // block access
                }
                if(this.selectedUsersCount > 0)
                {
                   
                    this.tab = value;

                    if(value == 'group')
                    {
                        this.getGroups();
                    }
                    if(value == 'tag')
                    {
                        this.getTags();
                    }
                }
                else
                {
                  alert("Select Students")
                }
            },
            getTags()
            {
                axios.get('/admin/student-tags')
                .then(response => {
                    this.tags = response.data.tags;
                })
                .catch(error => {
                    console.log('ERROR', error.response);
                });
            },
            submitTag()
            {
                this.errors = [];
                this.success = null;

                const name = this.new_tag_name.trim() || this.tag_name;

                if (!name) {
                    this.errors = { tag_name: ["Please select or enter a tag name."] };
                    return;
                }

                axios.post('/admin/tags/add-students', {
                    tag_name: name,
                    selectedUsers: this.selectedUsers,
                }).then(response => {

                    this.success = response.data.message;
                    this.tab = 0;
                    window.location.reload();

                }).catch(error => {

                    this.errors = error.response.data.errors;

                });
            },
            getGroups()
            {
                axios.get('/admin/grouplist')
                .then(response => {
                    console.log('SUCCESS', response.data);
                    this.groups = response.data.data;
                })
                .catch(error => {
                    console.log('ERROR', error.response);
                });
            },

            customexport()
            {
                this.exporttab=1;
            },
            closeexport()
            {
              this.checkedNames=[];
              this.exporttab=0;
            },
            submitExport()
            {
                axios.post('/admin/student/export',{
                  headings:this.checkedNames, 
                }).then(response => {
                  this.success = response.data.message;
                  window.location="/admin/student/export?"+this.searchquery;
                  this.exporttab=0;
                  this.checkedNames=[];
                }).catch(error => {
                  this.errors = error.response.data.errors;
                });
            },

            shiftstudents()
            {
                this.errors=[];
                this.success=null;
                
                axios.post('/admin/student/shift',{
                  //selected:this.selected, 
                  selectedUsers:this.selectedUsers,
                  shift_std:this.shift_std,
                }).then(response => {
                  this.success = response.data.message;
                  this.tab=0;
                  window.location.reload();
                }).catch(error => {
                  this.errors = error.response.data.errors;
                });
            },

            submit()
            {     
                this.errors=[];
                this.success=null;
                
                axios.post('/admin/student/sendMessageToAll',{
                  selected:this.selected, 
                  selectedUsers:this.selectedUsers,
                  subject:this.subject,
                  message:this.message, 
                  send_later:this.send_later,
                  executed_at:this.executed_at,
                }).then(response => {
                  this.success = response.data.message;
                  this.tab=0;
                  window.location.reload();
                }).catch(error => {
                  this.errors = error.response.data.errors;
                });
            },

            resetform()
            {
                this.standardLink_id = '';
                this.fee_id = '';
                this.notify_parent = '';
                this.paid_on = '';
                this.user = '';
                this.selectedLetter = '';
                this.active = '';
                this.selected = '';
                this.selectedUsers = '';
                this.selectedUsersCount = '';
                this.send_later = '';
                this.allSelected = '';
                this.noneSelected = '';
                this.subject = '';
                this.message = '';
                this.executed_at = '';
                this.tab = '';
                this.show = '';
            },

            submitFee()
            {
                this.errors=[];
                this.success=null;
        
                axios.post('/admin/feedetail/add',{
                    selectedUsers:this.selectedUsers, 
                    standardLink_id:this.standardLink_id,
                    fee_id:this.fee_id,
                    paid_on:this.paid_on,
                    notify_parent:this.notify_parent,
                }).then(response => {
                    this.success = response.data.success;
                    this.tab=0;
                    window.location.reload();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },
            submitGroup()
            {
                this.errors = [];
                this.success = null;

                axios.post('/admin/groups/add-members', {

                    group_id: this.group_id,
                    selectedUsers: this.selectedUsers,

                }).then(response => {

                    this.success = response.data.message;

                    this.tab = 0;

                    window.location.reload();

                }).catch(error => {

                    this.errors = error.response.data.errors;

                });
            },

            closeModal()
            {
                this.tab = 0;
                this.tag_name = '';
                this.new_tag_name = '';
                this.showNewTag = false;
            },

            addNotify(e)
            {
                if (e.target.checked) 
                {
                    this.notify_parent = 1;
                }
                else
                {
                    this.notify_parent = 0;
                }
            },

            enableDate(e)
            {
                if (e.target.checked) 
                {
                    this.send_later = 1;
                    this.show = 'executed';
                }
                else
                {
                    this.send_later = 0;
                    this.show = '';
                }
            },

            // selectedCount(id,e) 
            // { 
            //    // alert(e.target.checked)
            //     if (e.target.checked) 
            //     {
            //         this.selectedUsersCount++;
            //         this.selectedUsers.push(id);
            //         $('#'+id).addClass('student_selected');
            //     }
            //     else
            //     {
            //         this.selectedUsersCount--;
            //         this.selectedUsers.splice(id);
            //         $('#'+id).removeClass('student_selected');
            //     }
            // },
            selectedCount(id, parent_id, e) {
                if (e.target.checked) {
                    if (!this.selectedUsers.includes(id)) {
                        this.selectedUsers.push(id);
                        this.selected.push(parent_id);
                        this.selectedUsersCount++;
                    }
                    $('#' + id).addClass('student_selected');
                } else {
                    const userIndex = this.selectedUsers.indexOf(id);
                    if (userIndex !== -1) {
                        this.selectedUsers.splice(userIndex, 1);
                        this.selectedUsersCount--;
                    }

                    const parentIndex = this.selected.indexOf(parent_id);
                    if (parentIndex !== -1) {
                        this.selected.splice(parentIndex, 1);
                    }

                    $('#' + id).removeClass('student_selected');
                }
            },

            buspass()
            {
                this.errors=[];
                this.success=null;
                
                axios.post('/admin/student/buspass',{
                  //selected:this.selected, 
                  selectedUsers:this.selectedUsers,
                  shift_std:this.shift_std,
                }).then(response => {
                  this.success = response.data.message;
                  this.tab=0;
                  window.location.reload();
                }).catch(error => {
                  this.errors = error.response.data.errors;
                });
            },
        }
    }
</script>

<style scoped>
  .modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, .5);
    display: table;
    transition: opacity .3s ease;
  }

  .modal-wrapper {
    display: table-cell;
    vertical-align: middle;
      overflow:auto;
  }

  .modal-container {
    margin: 0px auto;
    padding: 20px 30px;
    background-color: #fff;
    border-radius: 2px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
    transition: all .3s ease;
    height: 550px;
    overflow:auto;
  }

  .modal-header h3 {
    margin-top: 0;
    color: #42b983;
  }

  .modal-body {
    margin: 20px 0;
  }

  .modal-default-button {
    float: right;
  }

  /*
   * The following styles are auto-applied to elements with
   * transition="modal" when their visibility is toggled
   * by Vue.js.
   *
   * You can easily play with the modal transition by editing
   * these styles.
   */

  .modal-enter {
    opacity: 0;
  }

  .modal-leave-active {
    opacity: 0;
  }

  .modal-enter .modal-container,
  .modal-leave-active .modal-container {
    -webkit-transform: scale(1.1);
    transform: scale(1.1);
  }

  .text-danger
  {
    color:red;
  }
  .modal-mask {
    position: fixed;
    z-index: 9998;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    transition: opacity 0.2s ease;
}

.modal-wrapper {
    margin: auto;
    display: flex;
    align-items: center;
    width: 100%;
}

.modal-container {
    animation: modal-pop 0.2s ease-out;
}

@keyframes modal-pop {
    from {
        opacity: 0;
        transform: scale(0.96) translateY(8px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}
.slide-down-enter-active,
.slide-down-leave-active {
  transition: all 0.2s ease;
  overflow: hidden;
}
.slide-down-enter-from,
.slide-down-leave-to {
  opacity: 0;
  max-height: 0;
}
.slide-down-enter-to,
.slide-down-leave-from {
  opacity: 1;
  max-height: 100px;
}
</style>