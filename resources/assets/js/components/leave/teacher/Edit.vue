<template>
    <div class="bg-white shadow px-4 py-3">
        <div>
            <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

            <div class="flex flex-col lg:flex-row md:flex-col">
                <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="from_date" class="tw-form-label">From Date<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 flex items-center relative">
                            <VueDatePicker
                                v-model="from_date"
                                format="dd-MM-yyyy"
                                model-type="format"
                                :time-config="{ enableTimePicker: false }"
                                :auto-apply="true"
                                input-class-name="w-full rounded"
                                />
                            <div class="absolute right-0">
                                <svg id="Capa_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current text-gray-500 mx-2"><g><path d="m144 249h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"></path><path d="m144 313h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"></path><path d="m144 377h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"></path><path d="m272 249h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"></path><path d="m272 313h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"></path><path d="m272 377h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"></path><path d="m400 249h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"></path><path d="m400 313h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"></path><path d="m400 377h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"></path><path d="m467 65h-36v-25c0-8.284-6.716-15-15-15s-15 6.716-15 15v25h-130v-25c0-8.284-6.716-15-15-15s-15 6.716-15 15v25h-130v-25c0-8.284-6.716-15-15-15s-15 6.716-15 15v25h-36c-24.813 0-45 20.187-45 45v332c0 24.813 20.187 45 45 45h422c24.813 0 45-20.187 45-45 0-9.682 0-323.575 0-332 0-24.813-20.187-45-45-45zm-437 45c0-8.271 6.729-15 15-15h36v25c0 8.284 6.716 15 15 15s15-6.716 15-15v-25h130v25c0 8.284 6.716 15 15 15s15-6.716 15-15v-25h130v25c0 8.284 6.716 15 15 15s15-6.716 15-15v-25h36c8.271 0 15 6.729 15 15v59h-452zm437 347h-422c-8.271 0-15-6.729-15-15v-243h452v243c0 8.271-6.729 15-15 15z"></path></g></svg>
                            </div>
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
                              format="dd-MM-yyyy"
                              model-type="format"
                              :time-config="{ enableTimePicker: false }"
                              :auto-apply="true"
                              input-class-name="w-full rounded"
                            />
                            <div class="absolute right-0">
                                <svg id="Capa_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current text-gray-500 mx-2"><g><path d="m144 249h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"></path><path d="m144 313h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"></path><path d="m144 377h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"></path><path d="m272 249h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"></path><path d="m272 313h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"></path><path d="m272 377h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"></path><path d="m400 249h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"></path><path d="m400 313h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"></path><path d="m400 377h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"></path><path d="m467 65h-36v-25c0-8.284-6.716-15-15-15s-15 6.716-15 15v25h-130v-25c0-8.284-6.716-15-15-15s-15 6.716-15 15v25h-130v-25c0-8.284-6.716-15-15-15s-15 6.716-15 15v25h-36c-24.813 0-45 20.187-45 45v332c0 24.813 20.187 45 45 45h422c24.813 0 45-20.187 45-45 0-9.682 0-323.575 0-332 0-24.813-20.187-45-45-45zm-437 45c0-8.271 6.729-15 15-15h36v25c0 8.284 6.716 15 15 15s15-6.716 15-15v-25h130v25c0 8.284 6.716 15 15 15s15-6.716 15-15v-25h130v25c0 8.284 6.716 15 15 15s15-6.716 15-15v-25h36c8.271 0 15 6.729 15 15v59h-452zm437 347h-422c-8.271 0-15-6.729-15-15v-243h452v243c0 8.271-6.729 15-15 15z"></path></g></svg>
                            </div>
                        </div>
                        <span v-if="errors.to_date" class="text-red-500 text-xs font-semibold">{{errors.to_date[0]}}</span>
                    </div>
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
                <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="reset()">Reset</a>    
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
        props : ['url','id'],
        data(){
            return{
                leaves:[],
                reasonlist:[],
                leavelist:[],
                sessionlist:[
                { id : 'day' , name : 'Day'},
                { id : 'forenoon' , name : 'Forenoon'},
                { id : 'afternoon' , name : 'Afternoon'}
                ],
                session:'',
                from_date:'',
                to_date:'',
                reason_id:'',
                remarks:'',
                leave_type_id:'',
                errors:[],
                success:null,
            }
        },
        
        methods:
        {
            getList()
            {
                axios.get('/teacher/leave/edit/list/'+this.id).then(response => {
                    this.leaves = response.data;
                    this.setList();
                    //console.log(this.leaves)
                });
            },

            setList()
            {
                if(Object.keys(this.leaves).length > 0)
                {
                    this.from_date      = this.leaves.from_date;
                    this.to_date        = this.leaves.to_date;
                    this.reason_id      = this.leaves.reason_id;
                    this.remarks        = this.leaves.remarks;  
                    this.session        = this.leaves.session;  
                    this.leave_type_id  = this.leaves.leave_type_id;
                    this.reasonlist     = this.leaves.reasonlist;
                    this.leavelist      = this.leaves.leavelist;
                }
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
                     
                axios.post('/teacher/leave/edit/'+this.id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },
        },

        created()
        {
            this.getList();
        }
    }
</script>