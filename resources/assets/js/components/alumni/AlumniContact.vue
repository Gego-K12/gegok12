<template>
    <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3 bg-white shadow" v-bind:class="[this.profile_tab==4?'block' :'hidden']">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
        <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/3">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="twitter" class="tw-form-label">Twitter</label>
                    </div>
                    <div class="mb-2">
                        <input type="text" v-model="twitter" name="twitter" id="twitter" class="tw-form-control w-full" placeholder="Twitter"> 
                    </div>
                    <span v-if="errors.twitter" class="text-red-500 text-xs font-semibold">{{ errors.twitter[0] }}</span>
                </div>
            </div>

            <div class="tw-form-group w-full lg:w-1/3">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="linkedin" class="tw-form-label">LinkedIn</label>
                    </div>
                    <div class="mb-2">
                        <input type="text" v-model="linkedin" name="linkedin" id="linkedin" class="tw-form-control w-full" placeholder="LinkedIn">  
                    </div>
                    <span v-if="errors.linkedin" class="text-red-500 text-xs font-semibold">{{ errors.linkedin[0] }}</span>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/3">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="telegram" class="tw-form-label">Telegram</label>
                    </div>
                    <div class="mb-2 flex">
                        <input type="text" v-model="telegram" name="telegram" id="telegram" class="tw-form-control w-full" placeholder="Telegram">            
                    </div>
                    <span v-if="errors.telegram" class="text-red-500 text-xs font-semibold">{{ errors.telegram[0] }}</span>
                </div>
            </div>

            <div class="tw-form-group w-full lg:w-1/3">
                <div class="lg:mr-8 md:mr-8"> 
                    <div class="mb-2">
                        <label for="facebook" class="tw-form-label">Facebook</label>
                    </div>
                </div>
                <div class="mb-2 flex">
                    <input type="text" v-model="facebook" name="facebook" id="facebook" class="tw-form-control w-full" placeholder="Facebook">  
                    <span v-if="errors.facebook" class="text-red-500 text-xs font-semibold">{{ errors.facebook[0] }}</span>
                </div>
            </div>
        </div>

        <div class="tw-form-group w-full lg:w-1/3">
            <div class="lg:mr-8 md:mr-8"> 
                <div class="mb-2">
                    <label for="about_me" class="tw-form-label">About Me<span class="text-red-500">*</span></label>
                </div>
            </div>
            <div class="mb-2 flex">
                <textarea type="textarea" v-model="about_me" name="about_me" id="about_me" class="tw-form-control w-full " placeholder="About Yourself"></textarea>    
            </div>
            <span v-if="errors.about_me" class="text-red-500 text-xs font-semibold">{{errors.about_me[0]}}</span>
        </div>

        <div id="submit-btn-target"></div>
        <Teleport to="#submit-btn-target">
            <div class="my-6">
                <a href="#" dusk="submit-btn" class=" btn-primary submit-btn blue-bg text-sm text-white px-2 py-1 rounded mx-1" @click="submitForm()">Submit</a>
                <input type="submit" class="hidden" id="submit-btn">
                <a href="#" class="btn-reset reset-btn" @click="resetForm()">Reset</a>
            </div>
        </Teleport>
    </div>
</template>

<script>
    import { bus } from "../../app";
    export default {
        props:['url','type'],
        data(){
            return {
                profile_tab:'',
                twitter:'',
                linkedin:'',
                telegram:'',
                facebook:'',
                about_me:'',
                errors:[],
                success:null,
            }
        },

        methods:
        {
            getData()
            {
                axios.get('/alumni/editAlumni').then(response => {
                    this.alumni = response.data;
                    this.setData();   
                });
            },

            setData()
            {
                if(Object.keys(this.alumni).length>0)
                {
                    if(this.type == 'add')
                    {
                        this.start  = parseInt(this.alumni.start);
                        this.end    = parseInt(this.alumni.end);
                    }
                    else  if(this.type == 'edit')
                    {
                        this.start  = parseInt(this.alumni.start);
                        this.end    = parseInt(this.alumni.end);
                        this.twitter=this.alumni.twitter;
                        this.facebook=this.alumni.facebook;
                        this.telegram=this.alumni.telegram;
                        this.linkedin=this.alumni.linkedin;
                        this.about_me=this.alumni.about_me;
                    }
                }
            },

            setProfileTab(val)
            {
                this.profile_tab=val;
                bus.$emit("dataAlumniTab", this.profile_tab);
            },

            resetForm()
            {
                this.twitter='';
                this.linkedin='';
                this.facebook='';
                this.telegram='';
                this.about_me='';
            }, 

            range(max,min)
            {
                var array = [],
                j = 0;
                for(var i = max; i >= min; i--)
                {
                    array[j] = i;
                    j++;
                }
                return array;
            },

            submitForm(val)
            {
                this.errors=[];
                this.success=null; 

                let formData = new FormData(); 
       
                formData.append('twitter',this.twitter);          
                formData.append('linkedin',this.linkedin);           
                formData.append('telegram',this.telegram);
                formData.append('facebook',this.facebook);
                formData.append('about_me',this.about_me);

                if(this.type == 'add')
                {
                    axios.post('/alumni/add/validationContact',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                        $('#submit-btn').click();
                    }).catch(error => {
                        this.errors = error.response.data.errors;
                    });
                }
                else if(this.type == 'edit')
                {
                    axios.post('/alumni/edit/validationContact',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                        $('#submit-btn').click(); 
                    }).catch(error => {
                        this.errors = error.response.data.errors;
                    });
                }
            },
        },
      
        created()
        {
            this.getData(); 
            bus.$on("dataAlumniTab", data => {
                if(data!='')
                {
                    this.profile_tab=data;                   
                }
            });
        }
    }
</script>