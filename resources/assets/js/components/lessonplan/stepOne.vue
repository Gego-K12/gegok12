<template>
    <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3 bg-white shadow" v-bind:class="[this.profile_tab==1?'block' :'hidden']">
	   <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

        <!-- start -->
        <div class="flex flex-col lg:flex-row md:flex-row" v-if="this.type == 'add'">
            <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="standardLink_id" class="tw-form-label">Class<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <select name="standardLink_id" id="standardLink_id" v-model="standardLink_id" class="tw-form-control w-full">
                            <option value="" disabled>Select Class</option>
                            <option v-for="standardLink in standardLinklist" v-bind:value="standardLink.id">{{ standardLink.standard_section }}</option>
                        </select>
                    </div>
                    <span v-if="errors.standardLink_id" class="text-red-500 text-xs font-semibold">{{ errors.standardLink_id[0] }}</span>
                </div> 
            </div>

            <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="subject_id" class="tw-form-label">Subject<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <select name="subject_id" id="subject_id" v-model="subject_id" class="tw-form-control w-full">
                            <option value="" disabled>Select Subject</option>
                            <option v-for="subject in subjectlist[this.standardLink_id]" v-bind:value="subject.subject_id">{{ subject.subject_name }}</option>
                        </select>
                    </div>
                    <span v-if="errors.subject_id" class="text-red-500 text-xs font-semibold">{{ errors.subject_id[0] }}</span>
                </div>
            </div>
        </div>
        <!-- end -->

        <!-- start -->
        <div class="flex flex-col lg:flex-row md:flex-row">
            <div class="tw-form-group w-full lg:w-1/3 md:w-1/3">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="unit_no" class="tw-form-label">Unit Number<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="unit_no" id="unit_no" v-model="unit_no" class="tw-form-control w-full" placeholder="Unit Number">
                    </div>
                    <span v-if="errors.unit_no" class="text-red-500 text-xs font-semibold">{{ errors.unit_no[0] }}</span>
                </div>
            </div>
      
            <div class="tw-form-group w-full lg:w-1/3 md:w-1/3">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="unit_name" class="tw-form-label">Unit Name<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="unit_name" id="unit_name" v-model="unit_name" class="tw-form-control w-full" rows="3" placeholder="Unit Name">
                    </div>
                    <span v-if="errors.unit_name" class="text-red-500 text-xs font-semibold">{{ errors.unit_name[0] }}</span>
                </div>
            </div>

            <div class="tw-form-group w-full lg:w-1/3 md:w-1/3">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="duration" class="tw-form-label">Duration ( In Minutes )<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="duration" v-model="duration" id="duration" class="tw-form-control w-full" placeholder="Duration In Minutes">
                    </div>
                    <span v-if="errors.duration" class="text-red-500 text-xs font-semibold">{{ errors.duration[0] }}</span>
                </div>
            </div>
        </div>
        <!-- end -->

        <!-- start -->
        <div class="flex flex-col lg:flex-row md:flex-row">
            <div class="tw-form-group w-full">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="title" class="tw-form-label">Title<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="title" id="title" v-model="title" class="tw-form-control w-full" placeholder="Title">
                    </div>
                    <span v-if="errors.title" class="text-red-500 text-xs font-semibold">{{ errors.title[0] }}</span>
                </div>
            </div>
        </div>
        <!-- end -->

        <!-- start -->
        <div class="flex flex-col lg:flex-row md:flex-row">
            <div class="tw-form-group w-full">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="description" class="tw-form-label">Unit Breakdown<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <textarea type="text" name="description" id="description" v-model="description" rows="3" class="tw-form-control w-full" placeholder="Unit Breakdown"></textarea>
                    </div>
                    <span v-if="errors.description" class="text-red-500 text-xs font-semibold">{{ errors.description[0] }}</span>
                </div>
            </div>
        </div>
        <!-- end -->

        <div class="my-6">
            <button 
                type="button"
                class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium"
                @click="submitForm('2')">
                Save & Continue
            </button>
    	       <button 
                    type="button"
                    class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium"
                    @click="resetForm()"
                    v-if="this.type == 'add'">
                    Reset
                </button>	
        </div>
    </div>
</template>

<script>
    import { bus } from "../../app";

    export default {
        props:['url' , 'type' , 'id'],
        data(){
            return{
                profile_tab:'1',
                standardLinklist:[],
                subjectlist:[],
                lessonplan:[],
                subject_id:'',
                standardLink_id:'',
                unit_no:'',
                unit_name:'',
                description:'',
                title:'',
                duration:'',
                errors:[],
                success:null,
            }
        },
        
        methods:
        {
            getData()
            {
                if(this.type == 'add')
                {
                    axios.get('/teacher/lessonplan/add/list').then(response => {
                        this.standardLinklist = response.data.standardLinklist;
                        this.subjectlist = response.data.subjectlist;
                        //console.log(this.standardLinklist)
                    }); 
                }
                else if(this.type == 'edit')
                {
                    axios.get('/teacher/lessonplan/edit/list/'+this.id).then(response => {
                        this.lessonplan = response.data;
                        this.setData();
                        //console.log(this.lessonplan)
                    });
                }
            },

            setData()
            {
                if(Object.keys(this.lessonplan).length > 0 )
                {
                    this.unit_no              = this.lessonplan.unit_no;
                    this.unit_name            = this.lessonplan.unit_name;
                    this.description          = this.lessonplan.description;
                    this.title                = this.lessonplan.title;
                    this.duration             = this.lessonplan.duration; 
                } 
            },

            setProfileTab(val,response)
            {
                this.profile_tab=val;
                bus.emit("dataProfileTab", this.profile_tab);
                bus.emit("lessonId", response.data.id);
                bus.emit("message", response.data.message);
            },

            setTab(val)
            {
                this.profile_tab=val;
                bus.emit("dataProfileTab", this.profile_tab);
            },

            resetForm()
            {
                this.standardLink_id    = '';  
                this.subject_id         = '';                
                this.unit_no            = '';                
                this.unit_name          = '';                
                this.description        = '';                
                this.title              = '';        
                this.duration           = '';
                this.errors             = [];
            },

            submitForm(val)
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();
                
                formData.append('type',this.type);  
                formData.append('standardLink_id',this.standardLink_id);  
                formData.append('subject_id',this.subject_id);                
                formData.append('unit_no',this.unit_no);                
                formData.append('unit_name',this.unit_name);                
                formData.append('description',this.description);                
                formData.append('title',this.title);        
                formData.append('duration',this.duration);    
              
                if(this.type == 'add')  
                {     
                    axios.post('/teacher/lessonplan/add/stepOne',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {    
                        this.setProfileTab(val,response);
                    }).catch(error => {
                        this.errors = error.response.data.errors;
                    });
                }
                else if(this.type == 'edit') 
                {
                    axios.post('/teacher/lessonplan/edit/stepOne/'+this.id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                        this.setProfileTab(val,response);
                    }).catch(error => {
                        this.errors = error.response.data.errors;
                    });
                }
            },
        },

        created()
        {
            this.getData(); 
            bus.on("dataProfileTab", data => {
                if(data!='')
                {
                    this.profile_tab=data;                   
                }
            });
        }
    }
</script>