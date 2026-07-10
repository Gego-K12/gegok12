<template>
    <div class="bg-white shadow px-4 py-3" v-bind:class="[this.fee_type == 'non_structural'?'block' :'hidden']">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

        <div class="my-5">
            <div class="tw-form-group w-full lg:w-3/4">
                <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                    <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                        <label for="fee_group_id" class="tw-form-label">Fees Type<span class="text-red-500 text-xs font-semibold">*</span></label>
                    </div>
                    <div class="mb-2 w-full lg:w-1/2 md:w-2/3 ml-6">
                        <select name="fee_group_id" id="fee_group_id" v-model="fee_group_id" class="tw-form-control w-full">
                            <option value="" disabled>Select Fees Type</option>
                            <option v-for="feegroup in feegrouplist" v-bind:value="feegroup.id">{{ feegroup.name }}</option>
                        </select>
                        <span v-if="errors.fee_group_id" class="text-red-500 text-xs font-semibold">{{errors.fee_group_id[0]}}</span>
                    </div>
                    <div class="w-full lg:w-4/12 md:w-4/12">
                        <div class="lg:mx-3 md:mx-3">
                            <a href="#" class="bg-blue-500 rounded text-sm text-white px-2 py-1 whitespace-no-wrap" @click="showModal()">Add New Fees Type</a>
                        </div> 
                    </div>
                </div>
            </div>
        </div>

        <div v-if="this.show == 1" class="modal modal-mask">
            <div class="modal-wrapper px-4">
                <div class="modal-container w-full max-w-md px-8 mx-auto">
                    <div class="modal-header flex justify-between items-center">
                        <h2>Add Fees Type</h2>
                        <button id="close-button" class="modal-default-button text-2xl py-1"  @click="closeModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="flex items-center">
                            <div class="w-full lg:w-1/4"> 
                                <label for="name" class="tw-form-label">Fee Name<span class="text-red-500 text-xs font-semibold">*</span></label>
                            </div>
                            <div class="my-2 w-full lg:w-3/4">
                                <input type="text" class="tw-form-control w-full" id="name" v-model="name" name="name" Placeholder="Fee Name">
                                <span v-if="errors.name" class="text-red-500 text-xs font-semibold">{{errors.name[0]}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="flex items-center">
                            <div class="w-full lg:w-1/4"> 
                                <label for="description" class="tw-form-label">Description<span class="text-red-500 text-xs font-semibold">*</span></label>
                            </div>
                            <div class="my-2 w-full lg:w-3/4">
                                <textarea type="textarea" name="description" id="description" v-model="description" class="tw-form-control w-full" placeholder="Enter Description" @keyup='remaincharCount()' maxlength="100"></textarea>
                                <span v-if="errors.description" class="text-red-500 text-xs font-semibold">{{errors.description[0]}}</span>
                            </div>
                        </div>
                    </div>

                    <div class="my-6">
                        <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="addFeeType()">Submit</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="my-5" v-if="fee_group_name != 'transport_fee'">
            <div class="tw-form-group w-full lg:w-3/4">
                <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                    <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                        <label for="type" class="tw-form-label">Fees For<span class="text-red-500 text-xs font-semibold">*</span></label>
                    </div>
                    <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                        <select name="type" id="type" v-model="type" class="tw-form-control w-full">
                            <option value="" disabled>Select</option>
                            <option v-for="value in list" v-bind:value="value.id">{{ value.name }}</option>
                        </select>
                        <span v-if="errors.type" class="text-red-500 text-xs font-semibold">{{errors.type[0]}}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="my-5" v-if="this.type == 'standard'">
            <div class="tw-form-group w-full lg:w-3/4">
                <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                    <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                        <label for="standardLink_id" class="tw-form-label">Select Class</label>
                    </div>
                    <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                        <select name="standardLink_id" id="standardLink_id" v-model="standardLink_id" class="tw-form-control w-full" @change="getList()">
                            <option value="null" disabled>Select Class</option>
                            <option v-for="standardLink in standardLinklist" v-bind:value="standardLink.id">{{ standardLink.standard_section }}</option>
                        </select>
                        <span v-if="errors.standardLink_id" class="text-red-500 text-xs font-semibold">{{errors.standardLink_id[0]}}</span>
                    </div>
                </div>
            </div>
        </div> -->

        <div class="my-8" v-if="this.type == 'standard'">
            <div class="w-full flex flex-wrap items-center justify-between mb-4">
                <div class="flex items-center text-sm">
                    <div class="px-3 border-r relative">
                        <input class="opacity-0 absolute w-full h-full cursor-pointer" type="checkbox" @click="selectAllClass($event)" v-model="allSelectedClass"><span>Select All</span>
                    </div>
                    <div class="px-3 relative" v-if="this.selectedClassCount > 0">
                        <input class="opacity-0 absolute w-full h-full cursor-pointer" type="checkbox" @click="selectNoneClass($event)" v-model="noneSelectedClass"><span>Select None</span>
                    </div>
                </div>
            </div>
            <div class="flex flex-wrap">
                <div class="w-full lg:w-1/4 md:w-1/2 my-2 relative" v-for="standardLink in standardLinklist">
                    <div class="flex justify-between member-list">
                        <div class="flex items-center student_select">
                            <input class="w-5 h-5" type="checkbox" v-model="selectedClass" :value="standardLink.id" @click="selectedCountClass(standardLink.id,$event)">
                            <label></label>
                        </div>
                        <div class="flex p-2 active w-full" :id="standardLink.id">
                            <div class="px-2">
                                <h2 class="font-bold text-base text-gray-700">{{ standardLink.standard_section }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <span v-if="errors.selectedClassCount" class="text-red-500 text-xs font-semibold">{{errors.selectedClassCount[0]}}</span>
        </div>

        <div class="my-8" v-if="Object.keys(this.studentlist).length > 0 && this.type == 'standard'">
            <div class="w-full flex flex-wrap items-center justify-between mb-4">
                <div class="flex items-center text-sm">
                    <div class="px-3 border-r">
                        {{ parseInt(this.selectedUsersCount) }} students selected
                    </div>
                    <div class="px-3 relative">
                        <input class="opacity-0 absolute w-full h-full cursor-pointer" type="checkbox" @click="selectAll($event)" v-model="allSelected">
                        <span>{{ this.select_all_keyword }}</span>
                    </div>
                </div>
            </div>
            <div v-if="standardLink_id != null">
                <div class="flex flex-wrap" v-for="standard in standardLink_id">
                    <div class="w-full lg:w-1/4 md:w-1/2 my-2 relative" v-for="user in studentlist[standard]">
                        <div class="flex justify-between member-lists">
                            <div class="flex items-center student_select">
                                <input class="w-5 h-5" type="checkbox" v-model="selected" :value="user.id" @click="selectedCount(user.id,$event,standard)">
                                <label></label>
                            </div>
                            <div class="flex p-2 active w-full">
                                <img :src="user['avatar']" class="w-16 h-16">
                                <div class="px-2">
                                    <h2 class="font-bold text-base text-gray-700">{{ user['fullname'] }}</h2>
                                    <p>{{ user['class'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else>
                <div class="flex flex-wrap">
                    <div class="w-full lg:w-1/4 md:w-1/2 my-2 relative" v-for="user in studentlist">
                        <div class="flex justify-between member-lists">
                            <div class="flex items-center student_select">
                                <input class="w-5 h-5" type="checkbox" v-model="selected" :value="user.id" @click="selectedCount(user.id,$event,standard)">
                                <label></label>
                            </div>
                            <div class="flex p-2 active w-full">
                                <img :src="user['avatar']" class="w-16 h-16">
                                <div class="px-2">
                                    <h2 class="font-bold text-base text-gray-700">{{ user['fullname'] }}</h2>
                                    <p>{{ user['class'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <span v-if="errors.selectedUsersCount" class="text-red-500 text-xs font-semibold">{{ errors.selectedUsersCount[0] }}</span>
        </div>

        <div class="my-5">
            <div class="tw-form-group w-full lg:w-3/4">
                <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                    <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                        <label for="title" class="tw-form-label">Title<span class="text-red-500 text-xs font-semibold">*</span></label>
                    </div>
                    <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                        <input type="text" name="title" id="title" v-model="title" class="tw-form-control w-full" placeholder="Enter Title" @keyup='remaincharCount(20)' maxlength="20">
                        <span v-if="errors.title" class="text-red-500 text-xs font-semibold">{{errors.title[0]}}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="my-5">
            <div class="tw-form-group w-full lg:w-3/4 md-3/4">
                <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                    <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                        <label for="term" class="tw-form-label">Term</label>
                        <span class="font-semibold text-xs text-gray-700">(Enter Number , for example 1)</span>
                    </div>
                    <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                        <input type="term" name="term" id="term" v-model="term" class="tw-form-control w-full" placeholder="Enter Term" @keyup='remaincharCount()' maxlength="1">
                        <span v-if="errors.term" class="text-red-500 text-xs font-semibold">{{errors.term[0]}}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="my-5">
            <div class="tw-form-group w-full lg:w-3/4">
                <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                    <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                        <label for="amount" class="tw-form-label">Amount</label>
                    </div>
                    <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                        <input type="amount" name="amount" id="amount" v-model="amount" class="tw-form-control w-full" placeholder="Enter Amount" @keyup='remaincharCount()' maxlength="6">
                        <span v-if="errors.amount" class="text-red-500 text-xs font-semibold">{{errors.amount[0]}}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="my-5">
            <div class="tw-form-group w-full lg:w-3/4">
                <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                    <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                        <label for="start_date" class="tw-form-label">Payment Date</label>
                    </div>
                    <div class="mb-2 w-full lg:w-1/2 md:w-1/2">
                        <input type="date" name="start_date" v-model="start_date" class="tw-form-control w-full" id="start_date">
                        <span v-if="errors.start_date" class="text-red-500 text-xs font-semibold">{{errors.start_date[0]}}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="my-5">
            <div class="tw-form-group w-full lg:w-3/4 md:w-3/4">
                <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                    <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                        <label for="end_date" class="tw-form-label">Due Date<span class="text-red-500 text-xs font-semibold">*</span></label>
                    </div>
                    <div class="mb-2 w-full lg:w-1/2 md:w-1/2">
                        <input type="date" name="end_date" v-model="end_date" class="tw-form-control w-full" id="end_date">
                        <span v-if="errors.end_date" class="text-red-500 text-xs font-semibold">{{errors.end_date[0]}}</span>
                    </div>
                </div>
            </div>
        </div>
      
        <div class="my-6">
            <a href="#" id="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm()">Submit</a>
            <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="resetForm()">Reset</a>  
        </div>
    </div>
</template>

<script>
    import { bus } from "../../app";
    export default {
        props:['mode'],
        data(){
            return{
                fee_type:'',
                type:'',
                standardLink_id:null,
                fee_group_id:'',
                title:'',
                term:'',
                amount:'',
                start_date:'',
                end_date:'',
                name:'',
                description:'',
                show:0,
                standardLinklist:[],
                feegrouplist:[],
                studentlist:[],
                list:[ { 'id' : 'school' , 'name' : 'School' } , { 'id' : 'standard' , 'name' : 'Class' }],
                selectedClass:[],
                selectedClassCount:0,
                select_all_keyword:'Select All',
                allSelectedClass: false,
                noneSelectedClass:false,
                selected:[],
                selectedUsers:[],
                selectedUsersCount:0,
                allSelected: false,
                noneSelected:false,
                fee_group_name:'',
                params:{},
                errors:[],
                success:null,
            }
        },
        
        methods:
        {
            getList()
            {
                this.params = { fee_group_id:this.fee_group_id };

                this.final = '/'+this.mode+'/fee/add/list/'+this.standardLink_id;

                Object.keys(this.params).forEach(key => {
                    this.final = this.addParam(this.final, key, this.params[key])
                });

                axios.get(this.final).then(response => {
                    this.standardLinklist = response.data.standardLink;
                    this.feegrouplist = response.data.feegrouplist;
                    this.start_date = response.data.start_date;
                    this.end_date = response.data.end_date;
                    if(this.standardLink_id != null)
                    {
                        this.studentlist = response.data.studentlist;
                        //console.log(this.studentlist)
                    }
                    if(response.data.fee_group_name != null)
                    {
                        this.fee_group_name = response.data.fee_group_name;
                        /*if(this.fee_group_name == 'transport_fee')
                        {
                            this.type = 'school';
                        }*/
                    }
                });
            },

            remaincharCount(len)
            {
                var maxLength = len;
                $('textarea').keyup(function() {
                    var textlen = maxLength - $(this).val().length+'/'+maxLength;
                    $('#rchars').text(textlen);
                });
            },

            resetForm()
            {
                this.type='';
                this.standardLink_id='';
                this.fee_group_id='';
                this.title='';
                this.term='';
                this.amount='';  
                this.start_date='';   
                this.end_date='';   
            }, 

            submitForm()
            {
                this.errors=[];
                this.success=null; 

                axios.post('/'+this.mode+'/fee/add/non_structural',{
                    fee_type:this.fee_type,                 
                    type:this.type,                 
                    standardLink_id:this.standardLink_id,                 
                    fee_group_id:this.fee_group_id, 
                    fee_group_name:this.fee_group_name, 
                    title:this.title,                  
                    term:this.term,                 
                    amount:this.amount,                 
                    start_date:this.start_date,          
                    end_date:this.end_date,
                    selected:this.selected, 
                    selectedUsers:this.selectedUsers, 
                    selectedUsersCount:this.selectedUsersCount,              
                    selectedClass:this.selectedClass,                
                    selectedClassCount:this.selectedClassCount,
                }).then(response => {     
                    this.success = response.data.success;
                    this.resetForm();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            showModal(name)
            { 
                this.show = 1;
            },

            closeModal()
            {
                this.show = 0;
            },

            /*selectType()
            {
                this.params = { fee_group_id:this.fee_group_id };

                this.final = '/'+this.mode+'/fee/add/list/'+this.standardLink_id;

                Object.keys(this.params).forEach(key => {
                    this.final = this.addParam(this.final, key, this.params[key])
                });

                axios.get(this.final).then(response => {
                    this.standardLinklist = response.data.standardLink;
                    this.feegrouplist = response.data.feegrouplist;
                    this.start_date = response.data.start_date;
                    this.end_date = response.data.end_date;
                    if(this.standardLink_id != null)
                    {
                        this.studentlist = response.data.studentlist;
                        //console.log(this.studentlist)
                    }
                    //if(Object.keys(response.data.studentlist).length > 0)
                    //{
                        //this.studentlist = response.data.studentlist;
                        //console.log(this.studentlist)
                    //}
                    if(response.data.fee_group_name != null)
                    {
                        this.fee_group_name = response.data.fee_group_name;
                        //if(this.fee_group_name == 'transport_fee')
                        //{
                            //this.type = 'school';
                        //}
                    } 
                });
            },*/

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

            addFeeType()
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();
                         
                formData.append('name',this.name);                 
                formData.append('description',this.description);                 
                
                axios.post('/'+this.mode+'/feegroup/add',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                    this.show = 0;
                    window.location.reload();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            selectAll(e) 
            { 
                var selected = [];
                var selectedUsers = [];
                const Parameters=[];
                var obj = {};
                if (e.target.checked) 
                {
                    $('.member-lists').addClass('student_selected');
                    if(this.fee_group_name != 'transport_fee')
                    {
                        if(this.allSelected == false) 
                        {
                            for(let i = 0 ; i < Object.keys(this.standardLink_id).length ; i++)
                            {
                                var standard = this.standardLink_id[i];
                                this.studentlist[standard].forEach(function (user) 
                                {
                                    selected.push(user.id);
                                    selectedUsers.push({ [standard] : user.id });
                                });
                            }
                            this.selected = selected; 
                            this.selectedUsers = selectedUsers; 
                            this.selectedUsersCount = selected.length;
                            this.allSelected = true;
                        }
                    }
                    else
                    {
                        this.studentlist.forEach(function (user) 
                        {
                            selectedUsers.push(user.id);
                            selected.push(user.id);
                        });
                        this.selected = selected; 
                        this.selectedUsers = selectedUsers; 
                        this.selectedUsersCount = selected.length;
                        this.allSelected = true;
                    }
                    this.select_all_keyword = 'Select None';
                }
                else
                {
                    if(this.fee_group_name != 'transport_fee')
                    {
                        for(let i = 0 ; i < Object.keys(this.standardLink_id).length ; i++)
                        {
                            var standard = this.standardLink_id[i];
                            this.studentlist[standard].forEach(function (user) 
                            {
                                selected.splice(user.id);
                                selectedUsers.splice({ [standard] : user.id });
                            });
                        }
                        this.selected = selected;
                        this.selectedUsers = selectedUsers;
                        this.selectedUsersCount = selected.length;
                        this.noneSelected = false;
                    }
                    else
                    {
                        this.studentlist.forEach(function (user) 
                        {
                            selectedUsers.splice(user.id);
                            selected.splice(user.id);
                        });
                        this.selected = selected; 
                        this.selectedUsers = selectedUsers; 
                        this.selectedUsersCount = selected.length;
                        this.noneSelected = false;
                    }
                    $('.member-lists').removeClass('student_selected');
                    this.select_all_keyword = 'Select All';
                }
            },

            selectedCount(id,e,standard) 
            { 
                if (e.target.checked) 
                {
                    this.selectedUsersCount++;
                    this.selectedUsers.push({ [standard] : id });
                    $('#'+id).addClass('student_selected');
                }
                else
                {
                    this.selectedUsersCount--;
                    //this.selectedUsers.splice({ [standard] : id });
                    this.removeUser(id,this.selectedUsers,standard);
                    $('#'+id).removeClass('student_selected');
                }
            },

            selectAllClass(e) 
            { 
                var selectedClass = [];
                if (e.target.checked) 
                {
                    $('.member-list').addClass('student_selected');
                    if(this.allSelectedClass == false) 
                    {
                        this.standardLinklist.forEach(function (user) 
                        {
                            selectedClass.push(user.id);
                        });
                        this.selectedClass = selectedClass; 
                        this.selectedClassCount = selectedClass.length;
                        this.allSelected = true;
                    }
                }
                else
                {
                    this.standardLinklist.forEach(function (user) 
                    {
                        selectedClass.splice(user.id);
                    });
                    this.selectedClass = selectedClass;
                    this.selectedClassCount = selectedClass.length;
                    this.noneSelectedClass = false;
                    $('.member-list').removeClass('student_selected');
                }
            },

            selectNoneClass(e) 
            { 
                var selectedClass = [];
                if (e.target.checked) 
                {
                    $('.member-list').removeClass('student_selected');
                    this.standardLinklist.forEach(function (user) 
                    {
                        selectedClass.splice(user.id);
                    });
                    this.selectedClass = selectedClass;
                    this.selectedClassCount = selectedClass.length;
                    this.noneSelectedClass = false;
                }
            },

            selectedCountClass(id,e) 
            { 
                if (e.target.checked) 
                {
                    this.selectedClassCount++;
                    this.selectedClass.push(id);
                    $('#'+id).addClass('student_selected');
                    this.standardLink_id = this.selectedClass;
                    this.getList();
                }
                else
                {
                    this.selectedClassCount--;
                    //this.selectedClass.splice(id);
                    this.removeClasses(id,this.selectedClass);
                    $('#'+id).removeClass('student_selected');
                    this.standardLink_id = this.selectedClass;
                    this.getList();
                }
            },

            removeClasses(item, arr)
            {
                for (var i=0 ; i < arr.length ; i++)
                {
                    if (arr[i]==item)
                    {
                        arr.splice(i,1); //this delete from the "i" index in the array to the "1" length
                        break;
                    }
                } 
            },

            removeUser(item, arr, standard)
            {
                for (var i=0 ; i < arr.length ; i++)
                {
                    if (arr[i][standard]==item)
                    {
                        arr.splice(i,1); //this delete from the "i" index in the array to the "1" length
                        break;
                    }
                } 
            },
        },

        created()
        {
            this.getList();

            bus.on("statusTab", data => {
                if(data!='')
                {
                    this.fee_type=data;           
                }
            });
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
        /*height: 550px;*/
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
</style>