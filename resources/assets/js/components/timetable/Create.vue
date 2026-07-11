<template>
    <div class="bg-white shadow px-4 py-3">
        <div>
            <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row  lg:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4">
                            <label for="standardLink_id" class="tw-form-label">Select Class<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/4">
                            <select class="tw-form-control w-full" id="standardLink_id" v-model="standardLink_id" name="standardLink_id">
                                <option value="" disabled>Select Class</option>
                                <option v-for="standard in standardLinklist" v-bind:value="standard.id">{{standard.standard_name}}-{{standard.section_name}}</option>
                            </select>
                            <span v-if="errors.standardLink_id" class="text-red-500 text-xs font-semibold">{{errors.standardLink_id[0]}}</span>
                        </div>
                    </div> 
                </div>
            </div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row lg:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4">
                            <label for="periodCount" class="tw-form-label">No. Of Period<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/4">
                            <input type="text" name="periodCount" v-model="periodCount" id="periodCount" class="tw-form-control w-full" placeholder="No. Of Period">
                            <span v-if="errors.periodCount" class="text-red-500 text-xs font-semibold">{{errors.periodCount[0]}}</span>
                        </div>
                    </div> 
                </div>
            </div>

            <div class="my-6" id="save_btn">
                <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="addTable()">Save</a>
            </div>

            <div class="tw-form-group hidden" id="schedule_table" v-if="this.standardLink_id != ''">
                <div class="w-full border">
                    <div class="flex flex-wrap">
                        <div class="w-full lg:w-full md:w-full border-b" v-for="(input, index) in inputs">
                            <div class="py-3 px-2">
                                <div class="flex items-center justify-between w-full lg:w-3/5 md:w-3/5">
                                    <div class="w-6">
                                        <input type="checkbox" name="day" v-model="input.day" class="opacity-0 absolute cursor-pointer">
                                    </div>
                                    <div class="w-full"> 
                                        <p class="font-bold text-xl text-gray-800">{{ input.day_name }}</p>
                                    </div>
                                    <div class="flex justify-end">
                                        <a href="#" class="btn-times" @click="deleteRow(index)">
                                            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-4 h-4 fill-current text-black-500 mx-2"><g><g><g><polygon points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804"></polygon><rect x="235.948" y="175.791" width="40.104" height="237.285"></rect> <polygon points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804"></polygon><path d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74 c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474 L380.665,471.896z"></path></g></g></g> <g><g><path d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42 C354.924,14.992,339.932,0,321.504,0z"></path></g></g></svg>
                                        </a>
                                    </div>
                                </div>
                                <span v-if="errors['day'+index]" class="text-red-500 text-xs font-semibold">{{errors['day'+index][0]}}</span>
                                <div class="custom-table w-full lg:w-3/5 md:w-3/5">
                                    <table class="w-full">
                                        <thead class="bg-gray-400">
                                            <tr class="border-b">
                                                <th class="tw-form-label py-2">Period<span class="text-red-500">*</span></th>
                                                <th class="tw-form-label py-2">Subject<span class="text-red-500">*</span></th>
                                                <th class="tw-form-label py-2">Start Time<span class="text-red-500">*</span></th>
                                                <th class="tw-form-label py-2">End Time<span class="text-red-500">*</span></th>
                                            </tr>
                                        </thead>
              
                                        <tr class="border-b" v-for="(list, key)  in input.array"> 
                                            <td class="py-3 px-2">
                                                <input type="text" name="period" v-model="list.period" class="tw-form-control" readonly>
                                                <span v-if="errors['period'+index+key]" class="text-red-500 text-xs font-semibold">{{errors['period'+index+key][0]}}</span>
                                            </td>

                                            <td class="py-3 px-2">
                                                <select class="tw-form-control" id="subject_id" v-model="list.subject_id" name="subject_id[]">
                                                    <option value="" disabled>Select Subject</option>
                                                    <option v-for="subject in teacherLinklist[standardLink_id]" v-bind:value="subject.subject_name">{{ subject.subject_name }}</option>
                                                </select>
                                                <span v-if="errors['subject_id'+index+key]" class="text-red-500 text-xs font-semibold">{{errors['subject_id'+index+key][0]}}</span>
                                            </td>

                                            <td class="py-3 px-2">
                                                <VueDatePicker format="HH:mm" model-type="format" :time-picker="true" :is-24="true" :auto-apply="true" input-class-name="w-full rounded" name="start_time[]" v-model="list.start_time" id="start_time" />
                                                <span v-if="errors['start_time'+index+key]" class="text-red-500 text-xs font-semibold">{{errors['start_time'+index+key][0]}}</span>
                                            </td>

                                            <td class="py-3 px-2">
                                                <VueDatePicker format="HH:mm" model-type="format" :time-picker="true" :is-24="true" :auto-apply="true" input-class-name="w-full rounded" name="end_time[]" v-model="list.end_time" id="end_time" />
                                                <span v-if="errors['end_time'+index+key]" class="text-red-500 text-xs font-semibold">{{errors['end_time'+index+key][0]}}</span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-6 hidden" id="btn_div">
                <a href="#" id="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm()">Submit</a>
                <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="resetForm()">Reset</a>  
            </div>
        </div>
    </div>
</template>

<script>
    import { VueDatePicker } from '@vuepic/vue-datepicker'
    import '@vuepic/vue-datepicker/dist/main.css'
    export default {
        props:['url','standard'],
        components: {
            VueDatePicker,
        },
        data(){
            return{
                list:[],
                teacherLinklist:[],
                standardLinklist:[],
                standardLink_id:'',
                periodCount:'',
                daylist:[{id:'1' , name:'Monday'} , {id:'2' , name:'Tuesday'} , {id:'3' , name:'Wednesday'} , {id:'4' , name:'Thursday'} , {id:'5' , name:'Friday'} , {id:'6' , name:'Saturday'}],
                day:'',
                period:'',
                subject_id:'',
                start_time:'',
                end_time:'',
                inputs:[{
                    day:'',
                    period:'',
                    subject_id:'',
                    start_time:'',
                    end_time:'',
                }],
                errors:[],
                success:null,
            }
        },
        
        methods:
        {
            resetForm()
            {
                window.location.reload();
            },

            addRow()
            {
                this.inputs.splice(0,1);
                var days = this.daylist;
                var daycount = Object.keys(days).length;
                for(var j=0 ; j < daycount ; j++)
                { 
                    this.inputs.push({
                        day:days[j].id,
                        day_name:days[j].name,
                        array:[{
                            period:'',
                            subject_id:'',
                            start_time:'',
                            end_time:'',
                        }],
                    });
                }
            },

            addTable()
            {
                if(this.periodCount != '')
                {
                    if( $('#schedule_table').hasClass('hidden') && $('#btn_div').hasClass('hidden') )
                    {
                        $('#btn_div').removeClass('hidden').addClass('block');
                        $('#schedule_table').removeClass('hidden').addClass('block');
                        $('#save_btn').addClass('hidden').removeClass('block');
                    }
          
                    var days = this.daylist;
                    var daycount = Object.keys(days).length;
          
                    Object.keys(this.inputs).forEach(key => {
                        for(var i=0 ; i < this.periodCount ; i++)
                        { 
                            var input = [];
                            var input = this.inputs[key]['array'];
                            input.splice(i, 1);
                            input.push({
                                period:i+1,subject_id:'',start_time:'',end_time:'',
                            });
                        }
                    });
                }
                else
                {
                    alert('Enter No Of Period')
                }
            },

            deleteRow(index) 
            {
                this.inputs.splice(index,1);
            },

            submitForm()
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();

                formData.append('standardLink_id',this.standardLink_id); 
                formData.append('periodCount',this.periodCount); 
                formData.append('count',this.inputs.length);
                for(let i=0; i<this.inputs.length;i++)
                {
                    if(typeof this.inputs[i]['day_name'] !== "undefined")
                    {
                        formData.append('day'+i,this.inputs[i]['day_name']);
                    }
                    else
                    {
                        formData.append('day'+i,'');
                    }
          
                    for(let j=0; j<this.periodCount;j++)
                    { 
                        if(typeof this.inputs[i]['array'][j]['period'] !== "undefined")
                        {
                            formData.append('period'+i+j,this.inputs[i]['array'][j]['period']);
                        }
                        else
                        {
                            formData.append('period'+i+j,'');
                        }

                        if(typeof this.inputs[i]['array'][j]['subject_id'] !== "undefined")
                        {
                            formData.append('subject_id'+i+j,this.inputs[i]['array'][j]['subject_id']);
                        }
                        else
                        {
                            formData.append('subject_id'+i+j,'');
                        }

                        if(typeof this.inputs[i]['array'][j]['start_time'] !== "undefined")
                        {
                            formData.append('start_time'+i+j,this.inputs[i]['array'][j]['start_time']);
                        }
                        else
                        {
                            formData.append('start_time'+i+j,'');
                        }

                        if(typeof this.inputs[i]['array'][j]['end_time'] !== "undefined")
                        {
                            formData.append('end_time'+i+j,this.inputs[i]['array'][j]['end_time']);
                        }
                        else
                        {
                            formData.append('end_time'+i+j,'');
                        }
                    }
                }

      
                axios.post('/admin/timetable/add',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                    this.resetForm();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            getData()
            {
                axios.get('/admin/timetable/list').then(response => {
                    this.list = response.data;
                    //console.log(this.list)
                    this.setData();
                });
            },

            setData()
            {
                if(Object.keys(this.list).length > 0)
                {
                    this.teacherLinklist  = this.list.teacherLinklist;
                    this.standardLinklist = this.list.standardLinklist;
                    if(this.standard != '')
                    {
                        this.standardLink_id = this.standard;
                    }
                    this.addRow();
                }
            },
        },
        created()
        {
            this.getData();
        }
    }
</script>