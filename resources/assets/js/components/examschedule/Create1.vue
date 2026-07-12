<template>
    <div class="bg-white shadow px-4 py-3">
        <div>
            <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

            <div class="flex items-center justify-between px-3">
                <div class="mb-2 w-1/3">
                    <label for="subject_id" class="text-gray-800 text-lg">Subject</label>
                </div>
                <div class="mb-2 w-1/3">
                    <label for="start_time" class="text-gray-800 text-lg">Date</label>
                </div>
                <div class="mb-2 w-1/3">
                    <label for="duration" class="text-gray-800 text-lg">Duration</label>
                </div>
                <div class="mb-2 w-1/3">
                    <label for="portion" class="text-gray-800 text-lg">Portion</label>
                </div>
            </div>
            <div class="my-2 px-3" v-for="(shedule,index) in shedulelist">
                <div class="flex items-center justify-between">
                    <div class="tw-form-group w-1/3">
                        <div class="lg:mr-8 md:mr-8 flex items-center w-full">
                                <div class="mx-1">
                                <input type="checkbox"  true-value="1" false-value="0" v-model="shedule.status" >
                                </div>
                            <div class="mb-2 w-10/12">
                                <input class="tw-form-control w-full" id="subject_id" v-model="shedule.subject_name" name="subject_id[]" readonly>
                            </div>
                            <span v-if="errors.subject_id" class="text-red-500 text-xs font-semibold">{{ errors.subject_id[0] }}</span>
                        </div> 
                    </div>

                    <div class="tw-form-group w-1/3 my-2" v-show="shedule.status=='1'">
                        <div class="lg:mr-8 md:mr-8 flex items-center w-full">
                            <div class="mb-2 w-10/12">
                                <div class="flex items-center">
                                    <div>
                                        <VueDatePicker
                                            v-model="shedule.start_time"
                                            :enable-time-picker="true"
                                            format="dd-MM-yyyy HH:mm:ss"
                                            model-type="yyyy-MM-dd HH:mm:ss"
                                            class="rounded w-full"
                                        />

                                    </div>
                                </div>
                                <span v-if="errors['start_time'+index]" class="text-red-500 text-xs font-semibold">{{ errors['start_time'+index][0] }}</span> 
                            </div>
                        </div>
                    </div>

                    <div class="tw-form-group w-1/3 my-2" v-show="shedule.status=='1'">
                        <div class="lg:mr-8 md:mr-8 flex items-center w-full">
                            <div class="mb-2 w-2/4">
                                <select type="text" name="duration[]" v-model="shedule.duration" class="tw-form-control w-3/4" >
                                    <option value="" disabled>Select Duration</option>
                                    <option v-for="list in durationlist" v-bind:value="list.id">{{ list.name }}</option>
                                </select>
                                <span v-if="errors['duration'+index]" class="text-red-500 text-xs font-semibold">{{ errors['duration'+index][0] }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="tw-form-group w-1/3" v-show="shedule.status=='1'">
                        <div class="lg:mr-8 md:mr-8 flex items-center w-full">
                            <div class="mb-2 w-10/12">
                                <textarea class="tw-form-control w-full" id="portion[]" v-model="shedule.portion" name="portion[]" placeholder="Portion"></textarea>
                                <span v-if="errors['portion'+index]" class="text-red-500 text-xs font-semibold">{{ errors['portion'+index][0] }}</span>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>   

            <div class="my-6">
                <a href="#" id="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="checkForm()">Save</a>
                <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="reset()">Reset</a>  
            </div>
        </div>
    </div>
</template>

<script>
    import { VueDatePicker } from '@vuepic/vue-datepicker'
    import '@vuepic/vue-datepicker/dist/main.css'
    export default {
        props:['url','id'],
        components: { VueDatePicker },
        data(){
            return{
                subjectlist:[],
                standardlist:[],
                standard_id:'',
                subject_id:'',
                subjectcount:'',
                duration:[],
                start_time:[],
                portion:[],
                count:0,
                inputs:
                [{ 
                    subject_id:'',
                    start_time:'',
                    duration:'',
                    portion:'',
                }],
                durationlist:[ { id : '15' , name : '15'} , { id : '30' , name : '30'} , { id : '45' , name : '45'} , { id : '60' , name : '60'} , { id : '75' , name : '75'} , { id : '90' , name : '90'} , { id : '105' , name : '105'} , { id : '120' , name : '120'} , { id : '135' , name : '135'} , { id : '150' , name : '150'} , { id : '165' , name : '165'} , { id : '180' , name : '180'} ],
                shedulelist:[],
                errors:[],
                success:null,
            }
        },
        
        methods:
        {   
            reset()
            {
                this.subject_id='';
                this.start_time='';
                this.duration='';
                this.portion='';
            }, 

            checkForm()
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();

                formData.append('shedulelist',this.shedulelist.length);

                for(let i=0; i<this.shedulelist.length;i++)
                {
                    if(typeof this.shedulelist[i]['subject_id'] !== "undefined")
                    {
                        formData.append('subject_id'+i,this.shedulelist[i]['subject_id']);
                    }
                    else
                    {
                        formData.append('subject_id'+i,'');
                    }

                    if(typeof this.shedulelist[i]['start_time'] !== "undefined")
                    {
                        formData.append('start_time'+i,this.shedulelist[i]['start_time']);
                    }
                    else
                    {
                        formData.append('start_time'+i,'');
                    }

                    if(typeof this.shedulelist[i]['duration'] !== "undefined")
                    {
                        formData.append('duration'+i,this.shedulelist[i]['duration']);
                    }
                    else
                    {
                        formData.append('duration'+i,'');
                    }

                    if(typeof this.shedulelist[i]['portion'] !== "undefined")
                    {
                        formData.append('portion'+i,this.shedulelist[i]['portion']);
                    }
                    else
                    {
                        formData.append('portion'+i,'');
                    }

                    if(typeof this.shedulelist[i]['status'] !== "undefined")
                    {
                        formData.append('status'+i,this.shedulelist[i]['status']);
                    }
                    else
                    {
                        formData.append('status'+i,'');
                    }

                }               
                                           
                axios.post('/admin/examschedule/add/'+this.id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
                    this.success = response.data.success;
                    //this.reset();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            }, 

            getData()
            {
                axios.get('/admin/examschedule/list/'+this.id).then(response => {
                    this.subjectlist=response.data.subjectlist;
                    this.subjectcount=response.data.subjectcount;
                    this.standard_id=response.data.standard_id;
                    for (var i =0;i<this.subjectlist.length;i++) {
                        this.shedulelist.push({ 
                            subject_id:this.subjectlist[i].subject_id,
                            subject_name:this.subjectlist[i].subject_name,
                            start_time:'',
                            duration:'',
                            portion:'',
                            status:"1",
                        });
                    }
                });
            },
        },
        created()
        {
            this.getData();
        }
    }
</script>