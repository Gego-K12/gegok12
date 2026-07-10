<template>
    <div class=" overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3 bg-white shadow px-3" v-bind:class="[this.profile_tab==3?'block' :'hidden']">    
        <div class="flex flex-col">
            <div class="tw-form-group w-full lg:w-full" v-for="(input, index) in inputs">
                <div class="flex flex-row">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="company_name" class="tw-form-label">Company Name</label>
                        </div>
                        <div class="mb-2">
                            <input type="text" class="tw-form-control w-full" id="company_name" v-model="input.company_name" name="company_name[]" placeholder="Company Name">            
                        </div>
                        <span v-if="errors['company_name'+index]" class="text-red-500 text-xs font-semibold">{{ errors['company_name'+index] }}</span>
                    </div>

                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="designation" class="tw-form-label">Designation</label>
                        </div>
                        <div class="mb-2">
                            <input type="text" class="tw-form-control w-full" id="designation" v-model="input.designation" name="designation[]" placeholder="Designation">                                
                        </div>
                        <span v-if="errors['designation'+index]" class="text-red-500 text-xs font-semibold">{{ errors['designation'+index] }}</span>
                    </div>

                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="location" class="tw-form-label">Location</label>
                        </div>
                        <div class="mb-2">
                            <input type="text" class="tw-form-control w-full" id="location" v-model="input.location" name="location[]" placeholder="Location">            
                        </div>
                        <span v-if="errors['location'+index]" class="text-red-500 text-xs font-semibold">{{ errors['location'+index] }}</span>
                    </div>

                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="job_start_month" class="tw-form-label">Job Start Month</label>
                        </div>
                        <div class="mb-2">
                            <select class="tw-form-control w-full" id="job_start_month" v-model="input.job_start_month" name="job_start_month[]">
                                <option value="" disabled>Job Start Month</option>
                                <option v-for="list in months" v-bind:value="list.id">{{ list.name }}</option>
                            </select>            
                        </div>
                        <span v-if="errors['job_start_month'+index]" class="text-red-500 text-xs font-semibold">{{ errors['job_start_month'+index] }}</span>
                    </div>

                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="job_start_year" class="tw-form-label">Job Start Year</label>
                        </div>
                        <div class="mb-2">
                            <input type="text" class="tw-form-control w-full" id="job_start_year" v-model="input.job_start_year" name="job_start_year[]" placeholder="Job Start Year">            
                        </div>
                        <span v-if="errors['job_start_year'+index]" class="text-red-500 text-xs font-semibold">{{ errors['job_start_year'+index] }}</span>
                    </div>

                    <div class="lg:mr-8 md:mr-8" id="job_end_month">
                        <div class="mb-2">
                            <label for="job_end_month" class="tw-form-label">Job End Month</label>
                        </div>
                        <div class="mb-2">
                            <select class="tw-form-control w-full" id="job_end_month" v-model="input.job_end_month" name="job_end_month[]"> 
                                <option value="" disabled>Job End Month</option>
                                <option v-for="list in months" v-bind:value="list.id">{{ list.name }}</option>
                            </select>    
                        </div>
                        <span v-if="errors['job_end_month'+index]" class="text-red-500 text-xs font-semibold">{{ errors['job_end_month'+index] }}</span>
                    </div>

                    <div class="lg:mr-8 md:mr-8" id="job_end_year">
                        <div class="mb-2">
                            <label for="job_end_year" class="tw-form-label">Job End Year</label>
                        </div>
                        <div class="mb-2">
                            <input type="text" class="tw-form-control w-full" id="job_end_year" v-model="input.job_end_year" name="job_end_year[]" placeholder="Job End Year">            
                        </div>
                        <span v-if="errors['job_end_year'+index]" class="text-red-500 text-xs font-semibold">{{ errors['job_end_year'+index] }}</span>
                    </div>

                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="present" class="tw-form-label whitespace-no-wrap">Currently Working</label>
                        </div>
                        <div class="mb-2">
                            <input type="checkbox" class="tw-form-control w-full" id="present" v-bind:true-value="1" v-bind:false-value="0" v-model="input.present" name="present[]" @click="enableDiv($event)">            
                        </div>
                        <span v-if="errors['present'+index]" class="text-red-500 text-xs font-semibold">{{ errors['present'+index] }}</span>
                    </div>

                    <a href="#" class="btn-times py-10" @click="deleteRow(index)">
                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-4 h-4 fill-current text-black-500 mx-1"><g><g><g><polygon points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804"></polygon><rect x="235.948" y="175.791" width="40.104" height="237.285"></rect><polygon points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804"></polygon> <path d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74 c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474 L380.665,471.896z"></path></g></g></g> <g><g><path d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42 C354.924,14.992,339.932,0,321.504,0z"></path></g></g></svg>
                    </a> 
                </div>
            </div>
        </div>
        <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/3">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <a href="#" class=" btn-primary submit-btn blue-bg text-sm text-white px-2 py-1 rounded mx-1" @click="addRow">Add Experience</a>
                    </div>
                </div>
            </div>    
        </div>

        <div class="my-6">
            <a href="#" dusk="submit-btn" class=" btn-primary submit-btn blue-bg text-sm text-white px-2 py-1 rounded mx-1" @click="submitForm('4')">Submit</a>
            <a href="#" class="btn-reset reset-btn" @click="resetForm()">Reset</a>
        </div>
    </div>
</template>

<script>
    import { bus } from "../../app";
    export default {
        props:['url','type'],
        data () {
            return {
                profile_tab:'',
                user:[],
                company_name:[],
                designation:[],
                location:[],
                job_start_year:[],
                job_start_month:[],
                job_end_year:[],
                job_end_month:[],               
                inputs: [{
                    company_name:'',
                    designation:'',
                    location:'',
                    job_start_year:'',
                    job_start_month:'',
                    job_end_year:'',
                    job_end_month:'',
                    present:'',                 
                }],
                months:[{id:'01' , name:'January'} , {id:'02' , name:'February'} , {id:'03' , name:'March'} , {id:'04' , name:'April'} , {id:'05' , name:'May'} , {id:'06' , name:'June'} , {id:'07' , name:'July'} , {id:'08' , name:'August'} , {id:'09' , name:'September'} , {id:'10' , name:'October'} , {id:'11' , name:'November'} , {id:'12' , name:'December'}],
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
                    if(this.type == 'edit')
                    {   
                        if(this.alumni.work != null)
                        {        
                            this.inputs             = this.alumni.work;
                            
                            if(this.inputs[0]['present'] == 1)
                            {
                                $('#job_end_month').addClass('hidden').removeClass('block');
                                $('#job_end_year').addClass('hidden').removeClass('block');
                            }
                            else
                            {
                                $('#job_end_month').addClass('block').removeClass('hidden');
                                $('#job_end_year').addClass('block').removeClass('hidden');
                            }
                        }
                    }
                }
            },
     
            setProfileTab(val)
            {
                this.profile_tab=val;
                bus.$emit("dataAlumniTab", this.profile_tab);
            },

            addRow() 
            {
                this.inputs.push({
                    company_name:'',
                    designation:'',
                    location:'',
                    job_start_year:'',
                    job_start_month:'',
                    job_end_year:'',
                    job_end_month:'',
                    present:'',
                });
            },

            deleteRow(index) 
            {
                this.inputs.splice(index,1);
            }, 

            enableDiv(e)
            {
                if(e.target.checked == true)
                {
                    $('#job_end_month').addClass('hidden').removeClass('block');
                    $('#job_end_year').addClass('hidden').removeClass('block');
                }
                else
                {
                    $('#job_end_month').addClass('block').removeClass('hidden');
                    $('#job_end_year').addClass('block').removeClass('hidden');
                }
            },

            submitForm(val)
            {
                this.errors=[];
                this.success=null; 

                let formData =  new FormData(); 
      
                formData.append('count',this.inputs.length);

                for(let i=0; i<this.inputs.length;i++)
                { 
                    if(typeof this.inputs[i]['company_name'] !== "undefined")
                    {
                        formData.append('company_name'+i,this.inputs[i]['company_name']);
                        formData.append('designation'+i,this.inputs[i]['designation']);
                        formData.append('location'+i,this.inputs[i]['location']);
                        formData.append('job_start_year'+i,this.inputs[i]['job_start_year']);
                        formData.append('job_start_month'+i,this.inputs[i]['job_start_month']);
                        formData.append('job_end_year'+i,this.inputs[i]['job_end_year']);
                        formData.append('job_end_month'+i,this.inputs[i]['job_end_month']);
                        formData.append('present'+i,this.inputs[i]['present']);
                    }
                    else
                    {
                        formData.append('company_name'+i,'');
                        formData.append('designation'+i,'');
                        formData.append('location'+i,'');
                        formData.append('job_start_year'+i,'');
                        formData.append('job_start_month'+i,'');
                        formData.append('job_end_year'+i,'');
                        formData.append('job_end_month'+i,'');
                        formData.append('present'+i,'');
                    }
                }

                if(this.type == 'add')
                {
                    axios.post('/alumni/add/validationJob',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                        this.setProfileTab(val); 
                    }).catch(error => {
                        this.errors = error.response.data.errors;
                    });
                }
                else if(this.type == 'edit')
                {
                    axios.post('/alumni/edit/validationJob',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                        this.setProfileTab(val); 
                    }).catch(error => {
                        this.errors = error.response.data.errors;
                    });
                }
            },

            resetForm()
            {
                var count = this.inputs.length;
                for(var i = 0 ; i <= count ; i++)
                {
                    this.inputs.splice(i-1,1);
                }
                this.addRow();
            },
        },
    
        created()
        {  
            if(this.type == 'edit')
            {
                this.getData();
            }

            bus.$on("dataAlumniTab", data => {
                if(data!='')
                {
                    this.profile_tab=data;                    
                }
            });    
        }
    } 
</script>