<template>
    <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3 bg-white shadow" v-bind:class="[this.profile_tab==4?'block' :'hidden']">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{ this.success }}</div>

        <!-- start -->
        <div class="flex flex-col lg:flex-row md:flex-row">
            <div class="tw-form-group w-full">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="modification" class="tw-form-label">Modification</label>
                    </div>
                    <div class="mb-2">
                        <textarea type="text" name="modification" v-model="modification" id="modification" class="tw-form-control w-full" rows="3" placeholder="Enter Modification"></textarea>
                    </div>
                    <span v-if="errors.modification" class="text-red-500 text-xs font-semibold">{{ errors.modification[0] }}</span> 
                </div>
            </div>
        </div>
        <!--end-->

        <!-- start -->
        <div class="flex flex-col lg:flex-row md:flex-row">
            <div class="tw-form-group w-full">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="notes" class="tw-form-label">Notes</label>
                    </div>
                    <div class="mb-2">
                        <textarea type="text" name="notes" v-model="notes" id="notes" class="tw-form-control w-full" rows="3" placeholder="Enter Notes"></textarea>
                    </div>
                    <span v-if="errors.notes" class="text-red-500 text-xs font-semibold">{{ errors.notes[0] }}</span>
                </div>
            </div>
        </div>
        <!--end-->

        <div class="my-6">
            <button 
                type="button"
                @click="setTab('3')" 
                class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium">
                Back
            </button>

            <button 
                type="button"
                @click="submitForm()" 
                id="final_submit"
                class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium">
                Submit
            </button>

            <button 
                v-if="type == 'add'" 
                type="button"
                @click="resetForm()" 
                class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium">
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
                lessonplan:[],
                lesson_id:'',
                profile_tab:'',
                modification:'',
                notes:'',
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
                    this.modification         = this.lessonplan.modification; 
                    this.notes                = this.lessonplan.notes; 
                } 
            },

            setTab(val)
            {
                this.profile_tab=val;
                bus.emit("dataProfileTab", this.profile_tab);
            },

            resetForm()
            {
                this.modification   = '';  
                this.notes          = '';     
                this.errors         = [];
            },

            submitForm()
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();
                                  
                formData.append('modification',this.modification); 
                formData.append('notes',this.notes);          
                 
                if(this.type == 'add')  
                {            
                    axios.post('/teacher/lessonplan/add/stepFour/'+this.lesson_id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => { 
                     
                    }).catch(error => {
                        this.errors = error.response.data.errors;
                    });
                }
                else if(this.type == 'edit') 
                {
                    axios.post('/teacher/lessonplan/edit/stepFour/'+this.id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {  
                      
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