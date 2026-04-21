<template>
    <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3 bg-white shadow" v-bind:class="[this.profile_tab==2?'block' :'hidden']">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{ this.success }}</div>

        <!-- start -->
        <div class="flex flex-col lg:flex-row md:flex-row">
            <div class="tw-form-group w-full">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="objective" class="tw-form-label">Objective<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">

                        <QuillEditor
                              v-model:content="objective"
                              contentType="html"
                              theme="snow"
                            />
                    </div>
                    <span v-if="errors.objective" class="text-red-500 text-xs font-semibold">{{ errors.objective[0] }}</span>
                </div>
            </div>
        </div>
        <!-- end -->

        <!-- start -->
        <div class="flex flex-col lg:flex-row md:flex-row">
            <div class="tw-form-group w-full">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="materials_required" class="tw-form-label">Materials Required<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <QuillEditor
                              v-model:content="materials_required"
                              contentType="html"
                              theme="snow"
                            />
                    </div>
                    <span v-if="errors.materials_required" class="text-red-500 text-xs font-semibold">{{ errors.materials_required[0] }}</span>
                </div>
            </div>
        </div>
        <!-- end -->

        <input type="hidden" v-if="this.objective != null" name="objective" :value="this.objective">
        <input type="hidden" v-if="this.materials_required != null" name="materials_required" :value="this.materials_required">

        <!-- start -->
        <div class="flex flex-col lg:flex-row md:flex-row">
            <div class="tw-form-group w-full">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="assessment" class="tw-form-label">Assessment<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <textarea type="text" name="assessment" v-model="assessment" id="assessment" class="tw-form-control w-full" rows="3" placeholder="Enter Assessment"></textarea>
                    </div>
                    <span v-if="errors.assessment" class="text-red-500 text-xs font-semibold">{{ errors.assessment[0] }}</span>
                </div>
            </div>
        </div>
        <!-- end -->

        <div class="my-6">
            <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="setTab('1')">Back</a>
            <a href="#"  class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm('3')">Save & Continue</a>

            <a href="#"  class="btn btn-submit bg-yellow-500 text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="setProfileTab('3','')">Next</a>

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
                objective:'',
                materials_required:'',
                assessment:'',
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
                    this.objective            = this.lessonplan.objective;
                    this.materials_required   = this.lessonplan.materials_required; 
                    this.assessment           = this.lessonplan.assessment;
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
                this.objective              = '';  
                this.materials_required     = '';                
                this.assessment             = '';  
                this.errors                 = [];
            },

            submitForm(val)
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();
                        
                formData.append('objective',this.objective);                
                formData.append('materials_required',this.materials_required);         
                formData.append('assessment',this.assessment);  
              
                if(this.type == 'add')  
                {     
                    axios.post('/teacher/lessonplan/add/stepTwo/'+this.lesson_id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {      
                        this.setProfileTab(val,response);
                    }).catch(error => {
                        this.errors = error.response.data.errors;
                    });
                }
                else if(this.type == 'edit') 
                {
                    axios.post('/teacher/lessonplan/edit/stepTwo/'+this.id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
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