<template>
    <div class="bg-white shadow px-4 py-3">
        <div>
            <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/5 md:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                            <label for="standardLink_id" class="tw-form-label">Class<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-3/4 md:w-2/3">
                            <select class="tw-form-control w-full" id="standardLink_id" v-model="standardLink_id" name="standardLink_id">
                                <option v-for="standard in standardlist" v-bind:value="standard.id">{{ standard.standard_section }}</option>
                            </select>
                            <span v-if="errors.standardLink_id"><p class="text-red-500 text-xs font-semibold">{{errors.standardLink_id[0]}}</p></span>
                        </div>
                    </div> 
                </div>
            </div>

            <div class="my-5" v-if="this.standardLink_id != ''">
                <div class="tw-form-group w-full lg:w-3/5 md:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                            <label for="subject_id" class="tw-form-label">Subject</label>
                        </div>
                        <div class="mb-2 w-full lg:w-3/4 md:w-2/3">
                            <select class="tw-form-control w-full" id="subject_id" v-model="subject_id" name="subject_id">
                                <option value="" disabled>Select Subject</option>
                                <option v-for="subject in subjectlist[this.standardLink_id]" v-bind:value="subject.subject_id">{{ subject.subject_name }}</option>
                            </select>
                            <span v-if="errors.subject_id" class="text-red-500 text-xs font-semibold">{{errors.subject_id[0]}}</span>
                        </div>
                    </div> 
                </div>
            </div> 

            <div class="my-5" v-if="this.subject_id != '' & this.standardLink_id != ''">
                <div class="tw-form-group w-full lg:w-3/5 md:w-3/4" v-if="mode == 'admin'">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                            <label for="teacher_id" class="tw-form-label">Teacher</label>
                        </div>
                        <div class="mb-2 w-full lg:w-3/4 md:w-2/3">
                            <select class="tw-form-control w-full" id="teacher_id" v-model="teacher_id" name="teacher_id">
                                <option value="" disabled>Select Teacher</option>
                                <option v-for="teacher in teacherlist[this.standardLink_id][this.subject_id]" v-bind:value="teacher.id">{{ teacher.fullname }}</option>
                            </select>
                            <span v-if="errors.teacher_id" class="text-red-500 text-xs font-semibold">{{errors.teacher_id[0]}}</span>
                        </div>
                    </div> 
                </div>
            </div> 
    
            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/5 md:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                            <label for="description" class="tw-form-label">Description<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-3/4 md:w-2/3">
                            <!-- <textarea type="text" name="description" id="description" v-model="description" class="tw-form-control w-full" rows="3"></textarea> -->
                            <QuillEditor
                              v-model:content="description"
                              contentType="html"
                              theme="snow"
                            />
                            <span v-if="errors.description" class="text-red-500 text-xs font-semibold">{{errors.description[0]}}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/5 md:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="w-full w-full lg:w-1/4 md:w-1/4">
                            <label for="attachment" class="tw-form-label">Attachment</label>
                        </div>
                        <div class="mb-2 w-full lg:w-3/4 md:w-2/3">
                            <input type="file" name="attachment" @change="OnFileSelected" id="attachment" class="tw-form-control w-full">
                            <span v-if="errors.attachment" class="text-red-500 text-xs font-semibold">{{errors.attachment[0]}}</span>
                        </div>
                        <a :href="this.attachment_file" target="_blank" v-if="this.attachment_file != '' " class="text-sm mx-2 text-gray-700">Click to Open</a>
                    </div>
                </div>
            </div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/5 md:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="w-full w-full lg:w-1/4 md:w-1/4">
                            <label for="date" class="tw-form-label">Date<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-3/4 md:w-2/3">
                            <input type="date" name="date" v-model="date" class="tw-form-control w-full" id="date">
                            <span v-if="errors.date" class="text-red-500 text-xs font-semibold">{{errors.date[0]}}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/5">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="w-full w-full lg:w-1/4 md:w-1/3">
                            <label for="submission_date" class="tw-form-label">Submission Date<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-3/4 md:w-2/3">
                            <input type="date" name="submission_date" v-model="submission_date" :min="date" class="tw-form-control w-full" id="date">
                            <span v-if="errors.submission_date" class="text-red-500 text-xs font-semibold">{{errors.submission_date[0]}}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="my-5">
            <div class="tw-form-group w-full lg:w-3/5 md:w-3/4">
                <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                    <div class="w-full lg:w-1/4 md:w-1/4">
                        <label class="tw-form-label">
                            Status <span class="text-red-500">*</span>
                        </label>
                    </div>
                    <div class="mb-2 w-full lg:w-3/4 md:w-2/3">
                        <select class="tw-form-control w-full" v-model="status">
                            <option value="" disabled>Select Status</option>
                            <option value="draft">Draft</option>
                            <option value="publish">Publish</option>
                        </select>

                        <span v-if="errors.status" class="text-red-500 text-xs font-semibold">
                            {{ errors.status[0] }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

            <div class="my-6">
                <a href="#" id="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm()">Submit</a>
            </div>
        </div>
    </div>
</template>

<script>
    import { QuillEditor } from '@vueup/vue-quill'
    import '@vueup/vue-quill/dist/vue-quill.snow.css'
    export default {
        components:{ 
          QuillEditor,
        },
        props:['url' , 'id' , 'mode'],
        data(){
            return{
                list:[],
                standardlist:[],
                subjectlist:[],
                teacherlist:[],
                standardLink_id:'',
                subject_id:'',
                teacher_id:'',
                description:'',
                attachment:'',
                attachment_file:'',
                date:'',
                submission_date:'',
                status: '',
                editorOption:{
                    theme: 'snow',
                    modules: {
                        toolbar: {
                            container: [
                                ['bold', 'italic', 'underline', 'strike'],       
                                [{ 'color': [] }, { 'background': [] }],
                                [{ 'script': 'sub' }, { 'script': 'super' }],        
                                [{ 'align': [] }],
                                ['image'],
                            ],      
                        }
                    } 
                },
                errors:[],
                success:null,
            }
        },
        
        methods:
        {
            submitForm()
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();

                formData.append('mode',this.mode);                 
                formData.append('standardLink_id',this.standardLink_id);                 
                formData.append('subject_id',this.subject_id);                 
                formData.append('teacher_id',this.teacher_id);                 
                formData.append('description',this.description);          
                formData.append('attachment',this.attachment);          
                formData.append('date',this.date); 
                formData.append('submission_date',this.submission_date);  
                formData.append('status', this.status);        
              
                axios.post('/'+this.mode+'/homework/edit/'+this.id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            OnFileSelected(event)
            {
                this.attachment = event.target.files[0];
            },

            getData()
            {
                axios.get('/'+this.mode+'/homework/edit/list/'+this.id).then(response => {
                    this.list = response.data;
                    //console.log(this.list)
                    this.setData();
                });
            },

            setData()
            {
                if(Object.keys(this.list).length > 0)
                {
                    this.standardlist     = this.list.standardlist;
                    this.subjectlist      = this.list.subjectlist;
                    this.teacherlist      = this.list.teacherlist;
                    this.standardLink_id  = this.list.standardLink_id;
                    this.subject_id       = this.list.subject_id;
                    this.teacher_id       = this.list.teacher_id;
                    this.description      = this.list.description;  
                    this.attachment_file  = this.list.attachment;  
                    this.date             = this.list.date; 
                    this.submission_date  = this.list.submission_date; 
                    this.status = this.list.status;
                }
            },
        },
        created()
        {
            this.getData();
        }
    }
</script>