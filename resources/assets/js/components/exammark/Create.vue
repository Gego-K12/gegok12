<template>
    <div class="bg-white shadow px-3 py-3">
        <div>
	       <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
            <div>
                <div class="flex flex-col lg:flex-row">
                    <div class="tw-form-group w-full lg:w-full">
                        <div class="lg:mr-8 md:mr-8">
                            <div class="flex">
                                <div class="w-1/2 flex items-center lg:mr-8 md:mr-8"> 
                                    <input type="radio" v-model="download" name="download" id="sample" value="sample"@click="enableDiv($event)">
                                    <span class="text-sm mx-1">Sample Format</span>
                                </div>
                                <div class="w-1/2 flex items-center mr-2 lg:mr-8 md:mr-8"> 
                                    <input type="radio" v-model="download" name="download" id="import" value="import" @click="enableDiv($event)">
                                    <span class="text-sm mx-1">Import File</span>
                                </div>
                            </div>
                            <span v-if="errors.download" class="text-red-500 text-xs font-semibold">{{errors.download[0]}}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-full md:w-full">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:fle-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                            <label for="academic_year_id" class="tw-form-label">Academic Year</label>
                        </div>
                        <!-- <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                            <p for="academic_year_id" class="tw-form-label" v-model="academic_year_id" v-bind:value="academiclist.id">{{ academiclist.name }}</p>
                            <span v-if="errors.academic_year_id" class="text-red-500 text-xs font-semibold">{{ errors.academic_year_id[0] }}</span>
                        </div> --> 
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                            <p class="tw-form-label">
                                {{ academiclist.name }}
                            </p>
                            <span v-if="errors.academic_year_id"
                                  class="text-red-500 text-xs font-semibold">
                                {{ errors.academic_year_id[0] }}
                            </span>
                        </div>
                    </div>
                </div>   
            </div> 
     
            <div class="hidden" id="sample_download">
                <div class="my-3" v-if="this.downloadpath==null">
                    <a href="#" id="sample" class="no-underline text-white px-4 my-3 bg-blue-500 py-1 w-20 text-center rounded" @click="checkForm()">Save</a>
                </div>
            </div>
            <div class="mt-3" v-if="this.downloadpath!=null">
                <a :href="this.downloadpath"  @click="handleDownload" class="btn btn-submit bg-blue-500 text-white rounded px-3 py-1 mr-3 text-sm font-medium" target="_blank">Click Here To Download</a> 
            </div>

            <!--import-->
            <div class="" id="import_download">
                <div class="my-5">      
                    <div class="tw-form-group w-full lg:w-3/4 md:w-3/4">
                        <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:flex-row w-full">
                            <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                                <label for="import" class="tw-form-label">Import</label>
                            </div>
                            <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                                <input type="file" name="import_file" @change="OnFileSelected" id="import_file" class="tw-form-control w-full">
                                <!-- <span v-if="errors.import_file" class="text-red-500 text-xs font-semibold">{{errors.file[0]}}</span>  -->
                                <span v-if="errors.import_file" class="text-red-500 text-xs font-semibold">{{ errors.import_file[0] }}</span>

                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="#" id="import" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="importMarks()">Import</a>
                        </div>
                    </div> 
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props:['url','id'],
        data(){
            return{
                exammark:[],
                standardlist:[],
                subjectlist:[],
                teacherlist:[],
                academiclist:[],
                examlist:[],
                schedule:[],
                standard_id:'',
                subject_id:'',
                grade_name:'',
                academic_year_id:'',
                exam_id:'',
                download:'import',
                downloadpath:null,
                errors:[],
                success:null,
            }
        },
        
        methods:
        {   
            reset()
            {
                this.standard_id='';
                this.subject_id='';
                this.teacher_id='';
                this.exam_id=''; 
                this.grade_name='';  
            }, 

            checkForm()
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();

                formData.append('academic_year_id',this.academiclist.id);
                formData.append('standardLink_id',this.schedule.standard_id);                 
                formData.append('subject_id',this.schedule.subject_id);
                formData.append('exam_id',this.schedule.exam_id);                   
                formData.append('teacher_id',null);          
                       
                axios.post('/admin/exammarks/'+this.id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
                    this.downloadpath = response.data.path;  
                    this.success = response.data.success;
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            }, 

            OnFileSelected(event)
            {
                this.import_file = event.target.files[0];
            },  

            importMarks()
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();

                formData.append('file',this.import_file);
                formData.append('grade_name',this.grade_name);
                formData.append('academic_year_id',this.academiclist.name);
                formData.append('standard_id',this.schedule.standard_id);                 
                formData.append('subject_id',this.schedule.subject_id); 
                formData.append('teacher_id',null);                                 
                formData.append('exam_id',this.schedule.exam_id);

                axios.post('/admin/importMarks',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => { 
                    this.success = response.data.success;
                }).catch(error => {
                    if (error.response && error.response.data && error.response.data.errors) {
        this.errors = error.response.data.errors;
    } else {
        this.errors = { import_file: ['Unexpected error occurred.'] };
    }
                });
            },

            enableDiv(e)
            {
                if(e.target.checked)
                {
                    if(e.target.value == 'sample')
                    { 
                        if($('#sample_download').hasClass('hidden'))
                        {
                            $('#sample_download').addClass('block').removeClass('hidden');
                            $('#import_download').addClass('hidden').removeClass('block');
                        }
                    }
                    else if(e.target.value == 'import')
                    {
                        if($('#import_download').hasClass('hidden'))
                        {
                            $('#import_download').addClass('block').removeClass('hidden');
                            $('#sample_download').addClass('hidden').removeClass('block');
                        }
                    }
                }
            },  

            getData()
            {
                axios.get('/admin/exammarks/list/'+this.id).then(response => {
                    this.academiclist = response.data.academiclist;
                    this.schedule=response.data.schedule;
                    //console.log(this.academiclist);    
                });
            },
            handleDownload() {

            setTimeout(() => {
              this.downloadpath = null;
            }, 10000); 
          }
        },
        created()
        {
            this.getData();
        }

    }
</script>