<template>
    <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3 bg-white shadow" v-bind:class="[this.profile_tab==3?'block' :'hidden']">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{ this.success }}</div>
        <!-- start -->
        <div class="flex flex-col">
            <div class="tw-form-group w-full">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="introduction" class="tw-form-label">Introduction<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <QuillEditor
                              v-model:content="introduction"
                              contentType="html"
                              theme="snow"
                            />
                    </div>
                    <span v-if="errors.introduction" class="text-red-500 text-xs font-semibold">{{ errors.introduction[0] }}</span>
                </div>
            </div>
            <div class="tw-form-group w-full">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="procedure" class="tw-form-label">Procedure<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">

                        <QuillEditor
                              v-model:content="procedure"
                              contentType="html"
                              theme="snow"
                            />

                    </div>
                    <span v-if="errors.procedure" class="text-red-500 text-xs font-semibold">{{ errors.procedure[0] }}</span>
                </div>
            </div>
        </div>
        <!-- end -->

        <input type="hidden" v-if="this.introduction != null" name="introduction" :value="this.introduction">
        <input type="hidden" v-if="this.procedure != null" name="procedure" :value="this.procedure">
        <input type="hidden" v-if="this.conclusion != null" name="conclusion" :value="this.conclusion">
     
        <div class="flex flex-col lg:flex-row md:flex-row">
            <div class="tw-form-group w-full">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="conclusion" class="tw-form-label">Conclusion<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <QuillEditor
                          v-model:content="conclusion"
                          contentType="html"
                          theme="snow"
                        />
                    </div>
                    <span v-if="errors.conclusion" class="text-red-500 text-xs font-semibold">{{errors.conclusion[0]}}</span> 
                </div>
            </div>
        </div>

        <div class="my-6">
            <a href="#"  class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="setTab('2')">Back</a>
            <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm('4')">Save & Continue</a>

            <a href="#" class="btn btn-submit bg-yellow-500 text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="setProfileTab('4','')">Next</a>
            <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="resetForm()" v-if="this.type == 'add'">Reset</a>
        </div>
    </div>
</template>

<script>
    import { bus } from "../../app";
    import { QuillEditor } from '@vueup/vue-quill'
    import '@vueup/vue-quill/dist/vue-quill.snow.css'

    export default {
        components:{ 
          QuillEditor,
        },
        props:['url' , 'type' , 'id'],
        data(){
            return{
                lessonplan:[],
                lesson_id:'',
                profile_tab:'',
                introduction:'',
                procedure:'',
                conclusion:'',
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
            getData()
            {
                if(this.type == 'edit')
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
                    this.introduction         = this.lessonplan.introduction;
                    this.procedure            = this.lessonplan.procedure;  
                    this.conclusion           = this.lessonplan.conclusion;
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
                this.introduction   = '';  
                this.procedure      = '';                
                this.conclusion     = '';  
                this.errors         = [];
            },

            submitForm(val)
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();
                                  
                formData.append('introduction',this.introduction);                 
                formData.append('procedure',this.procedure);          
                formData.append('conclusion',this.conclusion);
                 
                if(this.type == 'add')  
                {               
                    axios.post('/teacher/lessonplan/add/stepThree/'+this.lesson_id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {      
                        this.setProfileTab(val,response);
                    }).catch(error => {
                        this.errors = error.response.data.errors;
                    });
                }
                else if(this.type == 'edit') 
                {
                    axios.post('/teacher/lessonplan/edit/stepThree/'+this.id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {      
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
            bus.on("lessonId", data => {
                if(data!='')
                {
                    this.lesson_id=data;                   
                }
            });
            bus.on("message", data => {
                if(data!='')
                {
                    this.success=data;                   
                }
            });
        }
    }
</script>