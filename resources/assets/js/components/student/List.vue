<template>
    <div>
        <Teleport to="#student_count">
            <div class="">
                <h1 class="admin-h1 mb-3 font-bold font-exo">Students ( {{ Object.keys(this.users).length }} )</h1>
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
                    <div class="relative flex flex-wrap items-center gap-2 w-full lg:w-auto lg:justify-end mx-3 lg:mx-0 md:mx-0 my-2 lg:my-0 md:my-0" v-if="this.selectedUsersCount > 0">
                        <a href="#" class="bulk-action-btn bulk-action-btn--green" @click="showModal('tag')">Add Tag</a>
                        <a href="#" class="bulk-action-btn bulk-action-btn--blue" @click="showModal('message')">Send Message</a>
                        <a href="#" class="bulk-action-btn bulk-action-btn--blue" @click="showModal('shift')">Shift</a>
                        <a href="#" class="bulk-action-btn bulk-action-btn--green" @click="showModal('group')">Add Group</a>
                        <a v-if="isFeeEnabled" href="#" class="bulk-action-btn bulk-action-btn--green" @click="showModal('fees')">Add Fees Details</a>
                        <!-- <a href="#" class="bulk-action-btn bulk-action-btn--blue" @click="buspass()">Bus Pass</a> -->
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

                            <a class="flex p-2  w-full no-underline" v-bind:class="[user['status']=='active' ? 'active': 'bg-red-300' ]" :id="user['id']" :href="url + '/admin/student/show/' + encodeURIComponent(user.name)">
                                <img :src="user['avatar']" class="w-16 h-16">
                                <div class="px-2">
                                    <h2 class="font-bold text-base text-gray-700">{{ user['fullname'] }}</h2>
                                    <p class="text-gray-700">{{ user['class'] }}</p>
                                    <p v-if="birthday == 'true'" class="text-gray-700">{{ user['date_of_birth'] }}</p>
                                </div>
                            </a>
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
                <div class="modal-container gmodal w-full max-w-lg mx-auto">
                    <div class="gmodal-head">
                        <div class="gmodal-head-icon gmodal-head-icon--blue">
                            <i class="fa-solid fa-paper-plane"></i>
                        </div>
                        <div>
                            <h2 class="gmodal-title">Send Message</h2>
                            <p class="gmodal-subtitle">To the parents of {{ selectedUsersCount }} selected student(s)</p>
                        </div>
                        <button type="button" class="gmodal-close" @click="closeModal()">&times;</button>
                    </div>

                    <div class="gmodal-body">
                        <div class="gmodal-field">
                            <label for="subject" class="gmodal-label">Subject</label>
                            <input type="text" id="subject" name="subject" v-model="subject" class="tw-form-control w-full" placeholder="Enter subject">
                            <span v-if="errors.subject"><p class="text-red-500 text-xs font-semibold my-1">{{errors.subject[0]}}</p></span>
                        </div>

                        <div class="gmodal-field">
                            <label for="message" class="gmodal-label">Message</label>
                            <textarea id="message" name="message" v-model="message" class="tw-form-control w-full" rows="6" placeholder="Type your message to the parents..."></textarea>
                            <span v-if="errors.message"><p class="text-red-500 text-xs font-semibold my-1">{{errors.message[0]}}</p></span>
                        </div>

                        <label class="gmodal-check" :class="{ 'gmodal-check--on': send_later }">
                            <input type="checkbox" name="send_later" v-model="send_later" @click="enableDate($event)">
                            <span>
                                <span class="gmodal-check-title">Send later</span>
                                <span class="gmodal-check-desc">Schedule delivery for a specific date and time instead of sending now.</span>
                            </span>
                        </label>

                        <div class="gmodal-field gmodal-field--inset" v-if="this.show == 'executed'">
                            <label for="executed_at" class="gmodal-label">Deliver on</label>
                            <VueDatePicker
                              v-model="executed_at"
                              format="dd-MM-yyyy HH:mm:ss"
                              model-type="format"
                              :enable-time-picker="true"
                              :is-24="true"
                              :auto-apply="true"
                              input-class-name="w-full rounded"
                            />
                            <span v-if="errors.executed_at"><p class="text-red-500 text-xs font-semibold my-1">{{errors.executed_at[0]}}</p></span>
                        </div>
                    </div>

                    <div class="gmodal-foot">
                        <button type="button" class="gmodal-btn gmodal-btn--ghost" @click="closeModal()">Cancel</button>
                        <button type="button" class="gmodal-btn gmodal-btn--primary" @click="submit()">
                            <i class="fa-solid fa-paper-plane mr-2 text-xs"></i>
                            {{ send_later ? 'Schedule Message' : 'Send Message' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>


         <div v-if="this.tab == 'shift'" class="modal modal-mask">
            <div class="modal-wrapper px-4">
                <div class="modal-container gmodal w-full max-w-lg mx-auto">
                    <div class="gmodal-head">
                        <div class="gmodal-head-icon">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <h2 class="gmodal-title">Shift Students</h2>
                            <p class="gmodal-subtitle">Move {{ selectedUsersCount }} selected student(s) to another class</p>
                        </div>
                        <button type="button" class="gmodal-close" @click="closeModal()">&times;</button>
                    </div>

                    <div class="gmodal-body">
                        <div class="gmodal-alert">
                            <i class="fa-solid fa-circle-info"></i>
                            <span>This is a rarely used, critical action — records for the current academic year are rewritten immediately.</span>
                        </div>

                        <div class="gmodal-field">
                            <label for="shift_std" class="gmodal-label">Move to class</label>
                            <select class="tw-form-control w-full" id="shift_std" v-model="shift_std" name="shift_std">
                                <option value="" disabled>Select Class</option>
                                <option v-for="standard in standardLinks" v-bind:value="standard.id">{{ standard.standard_section }}</option>
                            </select>
                            <span v-if="errors.shift_std"><p class="text-red-500 text-xs font-semibold my-1">{{errors.shift_std[0]}}</p></span>
                        </div>

                        <p class="gmodal-label">Before you continue, acknowledge each point</p>

                        <label class="gmodal-check" :class="{ 'gmodal-check--on': shiftAcks.scope }">
                            <input type="checkbox" v-model="shiftAcks.scope">
                            <span>
                                <span class="gmodal-check-title">Selection verified</span>
                                <span class="gmodal-check-desc">The shift applies to every selected student, not only the ones visible on this page.</span>
                            </span>
                        </label>
                        <label class="gmodal-check" :class="{ 'gmodal-check--on': shiftAcks.records }">
                            <input type="checkbox" v-model="shiftAcks.records">
                            <span>
                                <span class="gmodal-check-title">Records follow the class</span>
                                <span class="gmodal-check-desc">Each student's class for the current academic year changes immediately — attendance, exams and fee records follow the new class.</span>
                            </span>
                        </label>
                        <label class="gmodal-check" :class="{ 'gmodal-check--on': shiftAcks.irreversible }">
                            <input type="checkbox" v-model="shiftAcks.irreversible">
                            <span>
                                <span class="gmodal-check-title">No undo</span>
                                <span class="gmodal-check-desc">A wrong shift can only be corrected by manually shifting the students back.</span>
                            </span>
                        </label>
                    </div>

                    <div class="gmodal-foot">
                        <button type="button" class="gmodal-btn gmodal-btn--ghost" @click="closeModal()">Cancel</button>
                        <button type="button" class="gmodal-btn gmodal-btn--primary" :disabled="!shiftReady" @click="shiftstudents()">
                            Shift {{ selectedUsersCount }} Student(s)
                        </button>
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
        <div class="modal-container gmodal w-full max-w-md mx-auto">
            <div class="gmodal-head">
                <div class="gmodal-head-icon gmodal-head-icon--green">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <div>
                    <h2 class="gmodal-title">Add To Group</h2>
                    <p class="gmodal-subtitle">Add {{ selectedUsersCount }} selected student(s) to a group</p>
                </div>
                <button type="button" class="gmodal-close" @click="closeModal()">&times;</button>
            </div>

            <div class="gmodal-body">
                <div class="gmodal-field">
                    <label for="group_id" class="gmodal-label">Group</label>
                    <select id="group_id" v-model="group_id" class="tw-form-control w-full">
                        <option value="">&mdash; Choose a group &mdash;</option>
                        <option
                            v-for="group in groups"
                            :key="group.id"
                            :value="group.id"
                        >
                            {{ group.group_name ? group.group_name.replace(/\b\w/g, char => char.toUpperCase()) : '' }}
                        </option>
                    </select>
                    <span v-if="errors.group_id"><p class="text-red-500 text-xs font-semibold my-1">{{ errors.group_id[0] }}</p></span>
                    <span v-if="errors.selectedUsers"><p class="text-red-500 text-xs font-semibold my-1">{{ errors.selectedUsers[0] }}</p></span>
                </div>
            </div>

            <div class="gmodal-foot">
                <button type="button" class="gmodal-btn gmodal-btn--ghost" @click="closeModal()">Cancel</button>
                <button type="button" class="gmodal-btn gmodal-btn--primary" :disabled="!group_id" @click="submitGroup()">
                    <i class="fa-solid fa-layer-group mr-2 text-xs"></i>
                    Add To Group
                </button>
            </div>
        </div>
    </div>
</div>
<div v-if="this.tab == 'tag'" class="modal modal-mask">
    <div class="modal-wrapper px-4" @click.self="closeModal()">
        <div class="modal-container gmodal w-full max-w-md mx-auto">
            <div class="gmodal-head">
                <div class="gmodal-head-icon gmodal-head-icon--green">
                    <i class="fa-solid fa-tag"></i>
                </div>
                <div>
                    <h2 class="gmodal-title">Add Tag</h2>
                    <p class="gmodal-subtitle">Tag {{ selectedUsersCount }} selected student(s)</p>
                </div>
                <button type="button" class="gmodal-close" @click="closeModal()">&times;</button>
            </div>

            <div class="gmodal-body">
                <div class="gmodal-field">
                    <label class="gmodal-label">Existing tag</label>
                    <select v-model="tag_name" class="tw-form-control w-full">
                        <option value="">&mdash; Choose a tag &mdash;</option>
                        <option v-for="tag in tags" :key="tag.id" :value="tag.tag_name">
                            {{ tag.tag_name }}
                        </option>
                    </select>
                </div>

                <div class="gmodal-divider"><span>or</span></div>

                <button type="button" class="gmodal-toggle" :class="{ 'gmodal-toggle--open': showNewTag }" @click="showNewTag = !showNewTag">
                    <span><i class="fa-solid fa-plus mr-2"></i>Create a new tag</span>
                    <i class="fa-solid fa-chevron-down gmodal-toggle-chevron"></i>
                </button>

                <transition name="slide-down">
                    <div v-if="showNewTag" class="gmodal-field gmodal-field--inset">
                        <label class="gmodal-label">Tag name</label>
                        <input
                            type="text"
                            v-model="new_tag_name"
                            placeholder="e.g. Needs Extra Support"
                            class="tw-form-control w-full"
                        />
                    </div>
                </transition>

                <span v-if="errors.tag_name"><p class="text-red-500 text-xs font-semibold my-1">{{ errors.tag_name[0] }}</p></span>
                <span v-if="errors.selectedUsers"><p class="text-red-500 text-xs font-semibold my-1">{{ errors.selectedUsers[0] }}</p></span>
            </div>

            <div class="gmodal-foot">
                <button type="button" class="gmodal-btn gmodal-btn--ghost" @click="closeModal()">Cancel</button>
                <button type="button" class="gmodal-btn gmodal-btn--primary" :disabled="!tag_name && !new_tag_name.trim()" @click="submitTag()">
                    <i class="fa-solid fa-tag mr-2 text-xs"></i>
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
    import { VueDatePicker } from '@vuepic/vue-datepicker'
    import '@vuepic/vue-datepicker/dist/main.css'

    export default {
        props:['url' , 'searchquery' , 'letter' , 'standard' , 'birthday' , 'selected_standard'],

        components:
        {
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
                shiftAcks: { scope: false, records: false, irreversible: false },
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
            if(this.isFeeEnabled && this.tab=='fees')
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
                    // users are objects from /admin/students/find, not name
                    // strings -- calling charAt on the object throws and
                    // freezes the whole component render.
                    users = users.filter((user) => {
                        let firstLetter = (user.firstname || '').charAt(0).toUpperCase()
                        return firstLetter === this.selectedLetter
                    })
                }
                return users
            },

            shiftReady ()
            {
                return this.shiftAcks.scope && this.shiftAcks.records && this.shiftAcks.irreversible
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

                    if(value == 'shift')
                    {
                        // acknowledgments must be re-checked on every open
                        this.shiftAcks = { scope: false, records: false, irreversible: false };
                    }
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
                // Shifting rewrites academic records with no undo -- require
                // every acknowledgment even if the disabled button is bypassed.
                if (!this.shiftReady) { return; }

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
                this.shiftAcks = { scope: false, records: false, irreversible: false };
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
  .bulk-action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.375rem 0.9rem;
    font-size: 0.875rem;
    font-weight: 600;
    color: #fff;
    border-radius: 0.375rem;
    white-space: nowrap;
    text-decoration: none;
  }

  .bulk-action-btn--green {
    background-color: #00c982;
  }

  .bulk-action-btn--green:hover {
    background-color: #00b374;
  }

  .bulk-action-btn--blue {
    background-color: #3492e2;
  }

  .bulk-action-btn--blue:hover {
    background-color: #2c7dc4;
  }

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

/* --- gmodal: header / body / footer modal layout (Shift Students) --- */
/* Overrides .modal-container's fixed height + flat padding, so it must
   stay below that rule in this style block. */
.gmodal {
    padding: 0;
    height: auto;
    max-height: 85vh;
    display: flex;
    flex-direction: column;
    border-radius: 0.5rem;
    overflow: hidden;
}

.gmodal-head {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.gmodal-head-icon {
    flex: none;
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 9999px;
    background: #fef3c7;
    color: #b45309;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.gmodal-head-icon--blue {
    background: #dbeafe;
    color: #1d6fb8;
}

.gmodal-head-icon--green {
    background: #d1fae5;
    color: #047857;
}

.gmodal-title {
    margin: 0;
    font-family: 'Exo 2', sans-serif;
    font-size: 1.125rem;
    font-weight: 700;
    color: #1f2937;
    line-height: 1.3;
}

.gmodal-subtitle {
    margin: 0;
    font-size: 0.8rem;
    color: #6b7280;
}

.gmodal-close {
    margin-left: auto;
    flex: none;
    width: 2rem;
    height: 2rem;
    border-radius: 9999px;
    font-size: 1.25rem;
    line-height: 1;
    color: #6b7280;
    background: transparent;
}

.gmodal-close:hover {
    background: #f3f4f6;
    color: #111827;
}

.gmodal-body {
    padding: 1.25rem 1.5rem;
    overflow-y: auto;
}

.gmodal-alert {
    display: flex;
    gap: 0.5rem;
    align-items: flex-start;
    background: #fffbeb;
    border: 1px solid #fcd34d;
    color: #92400e;
    font-size: 0.8rem;
    line-height: 1.45;
    border-radius: 0.375rem;
    padding: 0.625rem 0.75rem;
    margin-bottom: 1.25rem;
}

.gmodal-alert i {
    margin-top: 0.15rem;
}

.gmodal-field {
    margin-bottom: 1.25rem;
}

.gmodal-field--inset {
    margin: 0.5rem 0 0.25rem;
    padding: 0.75rem;
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 0.375rem;
}

.gmodal-divider {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin: 1rem 0;
}

.gmodal-divider::before,
.gmodal-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e5e7eb;
}

.gmodal-divider span {
    font-size: 0.7rem;
    font-weight: 600;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    color: #9ca3af;
}

.gmodal-toggle {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.55rem 0.75rem;
    font-size: 0.8rem;
    font-weight: 600;
    color: #4b5563;
    background: #fff;
    border: 1px dashed #d1d5db;
    border-radius: 0.375rem;
    cursor: pointer;
    transition: border-color .15s ease, background-color .15s ease, color .15s ease;
}

.gmodal-toggle:hover {
    border-color: #00c982;
    background: #f0fdf7;
    color: #047857;
}

.gmodal-toggle-chevron {
    font-size: 0.7rem;
    transition: transform .15s ease;
}

.gmodal-toggle--open .gmodal-toggle-chevron {
    transform: rotate(180deg);
}

.gmodal-label {
    display: block;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    color: #6b7280;
    margin: 0 0 0.5rem;
}

.gmodal-check {
    display: flex;
    gap: 0.625rem;
    align-items: flex-start;
    border: 1px solid #e5e7eb;
    border-radius: 0.375rem;
    padding: 0.625rem 0.75rem;
    margin-bottom: 0.5rem;
    cursor: pointer;
    transition: border-color .15s ease, background-color .15s ease;
}

.gmodal-check:hover {
    border-color: #9ca3af;
}

.gmodal-check--on {
    border-color: #00c982;
    background: #f0fdf7;
}

.gmodal-check input {
    margin-top: 0.2rem;
    flex: none;
    width: 1rem;
    height: 1rem;
    accent-color: #00c982;
}

.gmodal-check-title {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    color: #1f2937;
}

.gmodal-check-desc {
    display: block;
    font-size: 0.75rem;
    color: #6b7280;
    line-height: 1.45;
}

.gmodal-foot {
    display: flex;
    justify-content: flex-end;
    gap: 0.5rem;
    padding: 0.875rem 1.5rem;
    background: #f9fafb;
    border-top: 1px solid #e5e7eb;
}

.gmodal-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.5rem 1.1rem;
    font-size: 0.875rem;
    font-weight: 600;
    border-radius: 0.375rem;
    transition: background-color .15s ease;
}

.gmodal-btn--ghost {
    background: #fff;
    color: #374151;
    border: 1px solid #d1d5db;
}

.gmodal-btn--ghost:hover {
    background: #f3f4f6;
}

.gmodal-btn--primary {
    background: #3492e2;
    color: #fff;
    border: 1px solid transparent;
}

.gmodal-btn--primary:hover:not(:disabled) {
    background: #2c7dc4;
}

.gmodal-btn--primary:disabled {
    background: #9ca3af;
    cursor: not-allowed;
    opacity: .7;
}
</style>