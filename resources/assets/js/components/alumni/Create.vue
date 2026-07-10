<template>
    <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3 bg-white shadow">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
        <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="name" class="tw-form-label">Name<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="text" v-model="name" name="name" id="name" class="tw-form-control w-full " placeholder="Name"> 
                    </div>
                    <span v-if="errors.name" class="text-red-500 text-xs font-semibold">{{ errors.name[0] }}</span>
                </div>
            </div>
            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="passing_session" class="tw-form-label">Batch<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <select name="passing_session" v-model="passing_session" id="passing_session" class="tw-form-control w-full">
                            <option value="" disabled="disabled">Select Batch</option>
                            <option v-for="i in range(start,end)" v-bind:value="i">{{ i }}</option>
                        </select>
                    </div>
                    <span v-if="errors.passing_session" class="text-red-500 text-xs font-semibold">{{ errors.passing_session[0] }}</span>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="mobile_no" class="tw-form-label">Mobile Number<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="text" v-model="mobile_no" name="mobile_no" id="mobile_no" class="tw-form-control w-full " placeholder="Mobile Number"> 
                    </div>
                    <span v-if="errors.mobile_no" class="text-red-500 text-xs font-semibold">{{ errors.mobile_no[0] }}</span>
                </div>
            </div>

            <div class="tw-form-group w-full lg:w-1/2">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <label for="email" class="tw-form-label">Email ID<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <input type="text" v-model="email" name="email" id="email" class="tw-form-control w-full " placeholder="Email ID">  
                    </div>
                    <span v-if="errors.email" class="text-red-500 text-xs font-semibold">{{ errors.email[0] }}</span>
                </div>
            </div>
        </div>

        <div class="my-6">
            <a href="#" dusk="submit-btn" class=" btn-primary submit-btn blue-bg text-sm text-white px-2 py-1 rounded mx-1" @click="submitForm()">Submit</a>
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
                date:[],
                name:'',
                mobile_no:'',
                email:'',
                start:'',
                end:'',
                passing_session:'',
                errors:[],
                success:null,
            }
        },

        methods:
        {
            getData()
            {
                axios.get('/admin/alumni/getdate').then(response => {
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

            resetForm()
            {
                this.name='';
                this.mobile_no='';
                this.email='';
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

            submitForm()
            {
                this.errors=[];
                this.success=null; 

                let formData = new FormData(); 
       
                formData.append('name',this.name);          
                formData.append('mobile_no',this.mobile_no);          
                formData.append('email',this.email);           
                formData.append('passing_session',this.passing_session);           
       
                axios.post('/admin/alumni/add',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },     
        },
      
        created()
        {
            this.getData(); 
        }
    }
</script>