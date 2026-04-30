<template>
    <div class="bg-white shadow px-4 py-3">
        <div>
            <flash-message :position="'right bottom'" :timeout="3000" class="myCustomClass"></flash-message>
        
	       <!--  <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div> -->

            <div class="flex flex-col lg:flex-row md:flex-row">
                <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="from_date" class="tw-form-label">From Date<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 flex items-center relative">
                            <VueDatePicker
                              v-model="from_date"
                              format="dd-MM-yyyy HH:mm:ss"
                              model-type="format"
                              :enable-time-picker="true"
                              :is-24="true"
                              :auto-apply="true"
                              input-class-name="w-full rounded"
                            />
                        </div>
                        <span v-if="errors.from_date" class="text-red-500 text-xs font-semibold">{{errors.from_date[0]}}</span>
                    </div>
                </div>

                <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="to_date" class="tw-form-label">To Date<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 flex items-center relative">
                            <VueDatePicker
                              v-model="to_date"
                              format="dd-MM-yyyy HH:mm:ss"
                              model-type="format"
                              :enable-time-picker="true"
                              :is-24="true"
                              :auto-apply="true"
                              input-class-name="w-full rounded"
                            />
                        </div>
                    </div>
                    <span v-if="errors.to_date" class="text-red-500 text-xs font-semibold">{{errors.to_date[0]}}</span>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row md:flex-col">
                <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="leave_type_id" class="tw-form-label">Leave Type<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2">
                            <select type="text" name="leave_type_id" id="leave_type_id" v-model="leave_type_id" class="tw-form-control w-full">
                                <option value="" disabled>Select Leave Type</option>
                                <option v-for="leave in leavelist" v-bind:value="leave.id">{{ leave.name }}</option>
                            </select>
                        </div>
                        <span v-if="errors.leave_type_id" class="text-red-500 text-xs font-semibold">{{errors.leave_type_id[0]}}</span>
                    </div>
                </div>

                 <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="session" class="tw-form-label">Session <span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2">
                            <select type="text" name="session" id="session" v-model="session" class="tw-form-control w-full">
                                <option value="" disabled>Select Session</option>
                                <option v-for="sessions in sessionlist" v-bind:value="sessions.id">{{ sessions.name }}</option>
                            </select>
                        </div>
                       <span v-if="errors.session" class="text-red-500 text-xs font-semibold">{{errors.session[0]}}</span>
                    </div>
                </div>

            </div>

            <div class="flex flex-col lg:flex-row md:flex-row">
                <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="reason_id" class="tw-form-label">Reason<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2">
                            <select type="text" name="reason_id" id="reason_id" v-model="reason_id" class="tw-form-control w-full">
                                <option value="" disabled>Select Reason</option>
                                <option v-for="reasons in reasonlist" v-bind:value="reasons.id">{{ reasons.title }}</option>
                            </select>
                        </div>
                        <span v-if="errors.reason_id" class="text-red-500 text-xs font-semibold">{{errors.reason_id[0]}}</span>
                    </div>
                </div>
      
                <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="remarks" class="tw-form-label">Remarks</label>
                        </div>
                        <div class="mb-2">
                            <textarea type="text" name="remarks" id="remarks" v-model="remarks" class="tw-form-control w-full" rows="3" placeholder="Enter Remarks"></textarea>
                        </div>
                        <span v-if="errors.remarks" class="text-red-500 text-xs font-semibold">{{errors.remarks[0]}}</span>
                    </div>
                </div>
            </div>

            

            
    	
            <div class="my-6">
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
        components: {
            VueDatePicker,
        },
        props:['url'],
        data(){
            return{
                reasonlist:[],
                sessionlist:[
                { id : 'day' , name : 'Day'},
                { id : 'forenoon' , name : 'Forenoon'},
                { id : 'afternoon' , name : 'Afternoon'}
                ],
                leavelist:[],
                from_date:'',
                to_date:'',
                reason_id:'',
                remarks:'',
                leave_type_id:'',
                session:'',
                errors:[],
                success:null,
            }
        },
        
        methods:
        {
            getList()
            {
                axios.get('/teacher/leave/add/list').then(response => {
                    this.reasonlist = response.data.reasonlist;
                    this.leavelist = response.data.leavelist;
                    this.from_date = response.data.from_date;
                    this.to_date = response.data.to_date;
                    //console.log(this.standardLinklist)
                });
            },

            resetForm()
            {
                this.from_date='';
                this.to_date='';
                this.reason_id='';
                this.remarks='';  
                this.leave_type_id='';  
                this.session='';   
            }, 

            submitForm()
            {       
                this.errors=[];
                this.success=null; 

                let formData=new FormData();
                
                formData.append('from_date',this.from_date);  
                formData.append('to_date',this.to_date);                
                formData.append('reason_id',this.reason_id);                
                formData.append('remarks',this.remarks); 
                formData.append('session',this.session);          
                formData.append('leave_type_id',this.leave_type_id);          
                     
                axios.post('/teacher/leave/add',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                    this.resetForm();
                    // this.flash(this.success,'success',{timeout: 3000});
                    window.location.href = "/teacher/leaves";
                }).catch(error => {
                    this.errors = error.response.data.errors;
                    // this.flash('Please fill all fields ☹','error',{timeout: 3000});
                });
            },
        },

        created()
        {
            this.getList();
        }
    }
</script>
<style scoped>
  .myCustomClass {
     margin-top:10px;
     bottom:0px;
     right:0px;
     position: fixed;
     z-index: 40;
}
</style>
