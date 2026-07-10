<template>
    <div class="overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3 bg-white shadow px-3" v-bind:class="[this.profile_tab==2?'block' :'hidden']">      
        <div class="flex flex-col w-full">
            <div class="tw-form-group w-full lg:w-full" v-for="(input, index) in inputs">
                <div class="flex flex-row">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="institution_name" class="tw-form-label">Institution Name<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2">
                            <input type="text" class="tw-form-control w-full" id="institution_name" v-model="input.institution_name" name="institution_name[]" placeholder="Institution Name">
                        </div>
                        <span v-if="errors['institution_name'+index]" class="text-red-500 text-xs font-semibold">{{ errors['institution_name'+index] }}</span>
                    </div>

                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="degree" class="tw-form-label">Degree<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2">
                            <input type="text" class="tw-form-control w-full" id="degree" v-model="input.degree" name="degree[]" placeholder="Degree">
                        </div>
                        <span v-if="errors['degree'+index]" class="text-red-500 text-xs font-semibold">{{ errors['degree'+index] }}</span>
                    </div>

                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="specialization" class="tw-form-label">Specilization<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2">
                            <input type="text" class="tw-form-control w-full" id="specialization" v-model="input.specialization" name="specialization[]" placeholder="Specialization">
                        </div>
                        <span v-if="errors['specialization'+index]" class="text-red-500 text-xs font-semibold">{{ errors['specialization'+index] }}</span>
                    </div>

                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="college_start_year" class="tw-form-label">Start Year<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2">
                            <input type="text" class="tw-form-control w-full" id="college_start_year" v-model="input.college_start_year" name="college_start_year[]" placeholder="College Start Year" >
                        </div>
                        <span v-if="errors['college_start_year'+index]" class="text-red-500 text-xs font-semibold">{{ errors['college_start_year'+index] }}</span>
                    </div>

                    <div class="lg:mr-8 md:mr-8" id="end_year">
                        <div class="mb-2">
                            <label for="college_end_year" class="tw-form-label">End Year<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2">
                            <input type="text" class="tw-form-control w-full" id="college_end_year" v-model="input.college_end_year" name="college_end_year[]" placeholder="College End Year">
                        </div>
                        <span v-if="errors['college_end_year'+index]" class="text-red-500 text-xs font-semibold">{{ errors['college_end_year'+index] }}</span>
                    </div>

                    <div class="lg:mr-8 md:mr-8" id="grade_div">
                        <div class="mb-2">
                            <label for="grade" class="tw-form-label">Grade<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2">
                            <input type="text" class="tw-form-control w-full" id="grade" v-model="input.grade" name="grade[]" placeholder="Grade">            
                        </div>
                        <span v-if="errors['grade'+index]" class="text-red-500 text-xs font-semibold">{{ errors['grade'+index] }}</span>
                    </div>

                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="current_studying" class="tw-form-label whitespace-no-wrap">Currently Studying</label>
                        </div>
                        <div class="mb-2">
                            <input type="checkbox" class="tw-form-control w-full" v-bind:true-value="1" v-bind:false-value="0" id="current_studying" v-model="input.current_studying" name="current_studying[]" @click="enableDiv($event)">            
                        </div>
                        <span v-if="errors['current_studying'+index]" class="text-red-500 text-xs font-semibold">{{ errors['current_studying'+index] }}</span>
                    </div>

                    <a href="#" class="btn-times py-10" @click="deleteRow(index)">
                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-4 h-4 fill-current text-black-500"><g><g><g><polygon points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804"></polygon> <rect x="235.948" y="175.791" width="40.104" height="237.285"></rect> <polygon points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804"></polygon><path d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74 c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474 L380.665,471.896z"></path></g></g></g> <g><g><path d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42 C354.924,14.992,339.932,0,321.504,0z"></path></g></g></svg>
                    </a> 
                </div>
            </div>
        </div>
        <div class="flex flex-col lg:flex-row">
            <div class="tw-form-group w-full lg:w-1/3">
                <div class="lg:mr-8 md:mr-8">
                    <div class="mb-2">
                        <a href="#" class=" btn-primary submit-btn blue-bg text-sm text-white px-2 py-1 rounded mx-1" @click="addRow">Add Qualification</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="my-6">
            <a href="#" dusk="submit-btn" class=" btn-primary submit-btn blue-bg text-sm text-white px-2 py-1 rounded mx-1" @click="submitForm('3')">Submit</a>
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
                institution_name:[],
                degree:[],
                specialization:[],
                college_start_year:[],
                college_end_year:[],
                grade:[],
                inputs: [{
                    institution_name:'',
                    degree:'',
                    specialization:'',
                    college_start_year:'',
                    college_end_year:'',
                    grade:'',
                    current_studying:'',
                }],
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
                        if (Array.isArray(this.alumni.inputs) && this.alumni.inputs.length > 0) {
                                this.inputs = this.alumni.inputs;
                            } else {
                                this.inputs = [{
                                    institution_name: '',
                                    degree: '',
                                    specialization: '',
                                    college_start_year: '',
                                    college_end_year: '',
                                    grade: '',
                                    current_studying: '',
                                }];
                            } 
                        
                        if(this.inputs[0]['current_studying'] == 1)
                        {
                            $('#end_year').addClass('hidden').removeClass('block');
                            $('#grade_div').addClass('hidden').removeClass('block');
                        }
                        else
                        {
                            $('#end_year').addClass('block').removeClass('hidden');
                            $('#grade_div').addClass('block').removeClass('hidden');
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
                    institution_name:'',
                    degree:'',
                    specialization:'',
                    college_start_year:'',
                    college_end_year:'',
                    grade:'',
                    current_studying:'',
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
                    $('#end_year').addClass('hidden').removeClass('block');
                    $('#grade_div').addClass('hidden').removeClass('block');
                }
                else
                {
                    $('#end_year').addClass('block').removeClass('hidden');
                    $('#grade_div').addClass('block').removeClass('hidden');
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
                    if(typeof this.inputs[i]['institution_name'] !== "undefined")
                    {
                        formData.append('institution_name'+i,this.inputs[i]['institution_name']);           
                    }
                    else
                    {
                        formData.append('institution_name'+i,'');            
                    }

                    if(typeof this.inputs[i]['degree'] !== "undefined")
                    {           
                        formData.append('degree'+i,this.inputs[i]['degree']);            
                    }
                    else
                    {           
                        formData.append('degree'+i,'');           
                    }

                    if(typeof this.inputs[i]['specialization'] !== "undefined")
                    {                   
                        formData.append('specialization'+i,this.inputs[i]['specialization']);                   
                    }
                    else
                    {                    
                        formData.append('specialization'+i,'');                    
                    }

                    if(typeof this.inputs[i]['college_start_year'] !== "undefined")
                    {                   
                        formData.append('college_start_year'+i,this.inputs[i]['college_start_year']);                   
                    }
                    else
                    {                   
                        formData.append('college_start_year'+i,'');                  
                    }

                    if(typeof this.inputs[i]['college_end_year'] !== "undefined")
                    {                   
                        formData.append('college_end_year'+i,this.inputs[i]['college_end_year']);                   
                    }
                    else
                    {                   
                        formData.append('college_end_year'+i,'');
                    }

                    if(typeof this.inputs[i]['grade'] !== "undefined")
                    {                    
                        formData.append('grade'+i,this.inputs[i]['grade']);
                    }
                    else
                    {                   
                        formData.append('grade'+i,'');
                    }

                    if(typeof this.inputs[i]['current_studying'] !== "undefined")
                    {                    
                        formData.append('current_studying'+i,this.inputs[i]['current_studying']);
                    }
                    else
                    {                   
                        formData.append('current_studying'+i,'');
                    }
                }
        
                if(this.type == 'add')
                {
                    axios.post('/alumni/add/validationEducation',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                        this.setProfileTab(val); 
                    }).catch(error => {
                        this.errors = error.response.data.errors;
                    });
                }
                else if(this.type == 'edit')
                {
                    axios.post('/alumni/edit/validationEducation',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
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