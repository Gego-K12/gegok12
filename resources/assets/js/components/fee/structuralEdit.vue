<template>
    <div class="bg-white shadow px-4 py-3">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

        <div class="my-5">
            <div class="tw-form-group w-full lg:w-3/4">
                <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                    <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                        <label for="fee_group_id" class="tw-form-label">Fees Type<span class="text-red-500 text-xs font-semibold">*</span></label>
                    </div>
                    <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                        <select name="fee_group_id" id="fee_group_id" v-model="fee_group_id" class="tw-form-control w-full">
                            <option value="" disabled>Select Fees Type</option>
                            <option v-for="feegroup in feegrouplist" v-bind:value="feegroup.id">{{ feegroup.name }}</option>
                        </select>
                        <span v-if="errors.fee_group_id" class="text-red-500 text-xs font-semibold">{{errors.fee_group_id[0]}}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="my-5">
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

        <div class="my-5" v-if="this.type == 'standard'">
            <div class="tw-form-group w-full lg:w-3/4">
                <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                    <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                        <label for="standardLink_id" class="tw-form-label">Select Class<span class="text-red-500 text-xs font-semibold">*</span></label>
                    </div>
                    <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                        <select name="standardLink_id" id="standardLink_id" v-model="standardLink_id" class="tw-form-control w-full">
                            <option value="" disabled>Select Class</option>
                            <option v-for="standardLink in standardLinklist" v-bind:value="standardLink.id">{{ standardLink.standard_section }}</option>
                        </select>
                        <span v-if="errors.standardLink_id" class="text-red-500 text-xs font-semibold">{{errors.standardLink_id[0]}}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div class="my-8" v-if="this.type == 'standard'">
            <div class="flex flex-wrap">
                <div class="w-full lg:w-1/4 md:w-1/2 my-2 relative" v-for="standardLink in standardLinklist">
                    <div class="flex justify-between member-list">
                        <div class="flex items-center student_select">
                            <input class="w-5 h-5" type="checkbox" v-model="selectedClass" :value="standardLink.id">
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
            <span v-if="errors.selectedClassCount" class="text-red-500 text-xs font-semibold">{{ errors.selectedClassCount[0] }}</span>
        </div> -->

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
                        <label for="term" class="tw-form-label">Term<span class="text-red-500 text-xs font-semibold">*</span></label>
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
                        <label for="amount" class="tw-form-label">Amount<span class="text-red-500 text-xs font-semibold">*</span></label>
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
                        <label for="start_date" class="tw-form-label">Payment Date<span class="text-red-500 text-xs font-semibold">*</span></label>
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
            <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="reset()">Reset</a>  
        </div>
    </div>
</template>

<script>
    import { bus } from "../../app";
    export default {
        props:['id','mode'],
        data(){
            return{
                feelist:[],
                fee_type:'structural',
                type:'',
                standardLink_id:'',
                fee_group_id:'',
                scope:'',
                title:'',
                term:'',
                amount:'',
                start_date:'',
                end_date:'',
                name:'',
                description:'',
                standardLinklist:[],
                feegrouplist:[],
                list:[ { id : 'school' , name : 'School' } , { id : 'standard' , name : 'Class' }],
                selectedClass:'',
                selectedClassCount:0,
                allSelectedClass: false,
                noneSelectedClass:false,
                errors:[],
                success:null,
            }
        },
        
        methods:
        {
            getList()
            {
                axios.get('/'+this.mode+'/fee/edit/list/'+this.id+'/null').then(response => {
                    this.feelist = response.data;
                    this.setData();
                    //console.log(this.feelist)
                });
            },

            setData()
            {
                if(Object.keys(this.feelist).length > 0)
                {
                    this.title              = this.feelist.title;
                    this.type               = this.feelist.type;
                    this.fee_type           = this.feelist.fee_type;
                    this.fee_group_id       = this.feelist.fee_group_id;
                    this.term               = this.feelist.term;
                    this.amount             = this.feelist.amount;
                    this.start_date         = this.feelist.start_date;
                    this.end_date           = this.feelist.end_date;
                    this.standardLink_id    = this.feelist.standardLink_id;
                    //this.selectedClass      = [this.feelist.standardLink_id];
                    this.standardLinklist   = this.feelist.standardLinklist;
                    this.feegrouplist       = this.feelist.feegrouplist;
                    this.studentlist        = this.feelist.studentlist;             
                    //console.log(this.standardLinklist)
                }
            },

            remaincharCount(len)
            {
                var maxLength = len;
                $('textarea').keyup(function() {
                    var textlen = maxLength - $(this).val().length+'/'+maxLength;
                    $('#rchars').text(textlen);
                });
            }, 

            submitForm()
            {
                this.errors=[];
                this.success=null; 

                axios.post('/'+this.mode+'/fee/edit/'+this.id,{
                    fee_type:this.fee_type,                 
                    type:this.type,               
                    standardLink_id:this.standardLink_id,               
                    fee_group_id:this.fee_group_id,  
                    title:this.title,                  
                    term:this.term,                 
                    amount:this.amount,                 
                    start_date:this.start_date,          
                    end_date:this.end_date,  
                }).then(response => {            
                    this.success = response.data.success;
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },
        },

        created()
        {
            this.getList();
        }
    }
</script>