<template>
    <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3 bg-white shadow" v-bind:class="[this.profile_tab==1?'block' :'hidden']">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

        <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/3">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="photo" class="tw-form-label">Photo<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2 flex">
                        <input type="file" name="photo" @change="OnFileSelected" id="photo" class="tw-form-control w-full">
                    </div>
                    <span v-if="errors.photo" class="text-red-500 text-xs font-semibold">{{ errors.photo[0] }}</span>
                </div>
            </div>
       
            <div class="tw-form-group w-full lg:w-1/3">
                <div class="lg:mr-8 md:mr-8"> 
                    <div class="mb-2">
                        <label for="passing_session" class="tw-form-label">Batch</label>
                    </div>
                </div>
                <div class="mb-2 flex">
                    <select name="passing_session" v-model="passing_session" id="passing_session" class="tw-form-control w-full">
                        <option value="" disabled="disabled">Select Batch</option>
                        <option v-for="i in range(start,end)" v-bind:value="i">{{ i }}</option>
                    </select>
                </div>
                <span v-if="errors.passing_session" class="text-red-500 text-xs font-semibold">{{ errors.passing_session[0] }}</span>
            </div>     
        </div>

        <div class="my-6">
            <a href="#" dusk="submit-btn" class=" btn-primary submit-btn blue-bg text-sm text-white px-2 py-1 rounded mx-1" @click="submitForm('2')">Submit</a>
            <a href="#" class="btn-reset reset-btn" @click="resetForm()">Reset</a>
        </div>
    </div>
</template>

<script> 
    import { bus } from "../../app";
    export default {
        props:['url'],
        data(){
            return {
                profile_tab:'1',
                date:[],      
                photo:'',
                passing_session:'',
                start:'',
                end:'',      
                errors:[],
                success:null,
            }
        },

        methods:
        {
            getData()
            {
                axios.get('/alumni/getdate').then(response => {
                    this.date = response.data;
                    //console.log(this.date);
                    this.setData();   
                });
            },

            setData()
            {
                if(Object.keys(this.date).length>0)
                {             
                    this.start  = parseInt(this.date.start);
                    if(this.date.end != null)
                    {
                        this.end = parseInt(this.date.end);
                    }
                    else
                    {
                        this.end = null;
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
                this.photo='';
                this.passing_session='';        
            }, 

            range(max,min)
            {
                var array = [],
                j = 0;
                if(min != null)
                {
                    for(var i = max; i >= min; i--)
                    {
                        array[j] = i;
                        j++;
                    }
                }
                else
                {
                    array[j] = max
                }
                return array;
            },

            submitForm(val)
            {
                this.errors=[];
                this.success=null; 

                let formData = new FormData();        

                formData.append('photo',this.photo);
                formData.append('passing_session',this.passing_session);

                axios.post('/alumni/add/validationProfile',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.setProfileTab(val); 
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            OnFileSelected(event)
            {
                this.photo = event.target.files[0];
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