<template>
    <div class="">
        <div>
            <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

            <div class="my-8">
                <div class="w-full flex items-center justify-between mb-4">
                    <div class="flex items-center text-sm">
                        <div class="pr-3 border-r" v-if="selected.length > 0">
                            {{ selected.length }} Classes selected
                        </div>

                        <div class="px-3 border-r relative">
                            <input
                                type="checkbox"
                                :checked="selected.length === standardlist.length && standardlist.length > 0"
                                @change="toggleSelectAll"
                            />
                            <span>Select All</span>
                        </div>

                        <div class="px-3 relative" v-if="selected.length > 0">
                            <button @click="clearSelection" class="text-blue-600">
                                Select None
                            </button>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap" v-if="Object.keys(this.standardlist).length > 0">
                    <div class="w-full lg:w-1/4 md:w-1/2 my-2 relative" v-for="standard in standardlist">
                        <div class="flex items-center member-list">
                            <div class="flex items-center student_select">
                                <input class="w-5 h-5" type="checkbox" 
                                    v-model="selected" 
                                    :value="standard['id']">
                                <label></label>
                            </div>
                            <div class="flex p-2 w-full active">
                                <div class="px-2">
                                    <h2 class="font-bold text-base text-gray-700">{{ standard.standard_section }}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow px-4 py-3">
                <div class="my-5">
                    <div class="tw-form-group w-full lg:w-3/4 md:w-3/4">
                        <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                            <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                                <label for="name" class="tw-form-label">Exam Name</label>
                            </div>
                            <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                                <input type="text" name="name" v-model="name" class="tw-form-control w-full" id="name" placeholder="Exam Name">
                                <span v-if="errors.name" class="text-red-500 text-xs font-semibold">{{errors.name[0]}}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="my-5">
                    <div class="tw-form-group w-full lg:w-3/4 md:w-3/4">
                        <div class="lg:mr-8 md:mr-8 flex flex-col lg:fle-row md:flex-row lg:items-center md:items-center w-full">
                            <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                                <label for="exam_type" class="tw-form-label">Exam Type</label>
                            </div>
                            <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                                <select class="tw-form-control w-full" id="exam_type" v-model="exam_type" name="exam_type">
                                    <option value="" disabled>Select Exam Type</option>
                                    <option v-for="list in exam_type_list" v-bind:value="list.id">{{ list.name }}</option>
                                </select>
                                <span v-if="errors.exam_type" class="text-red-500 text-xs font-semibold">{{errors.exam_type[0]}}</span>
                            </div>     
                        </div> 
                    </div>
                </div> 

                <div class="my-5">
                    <div class="tw-form-group w-full lg:w-3/4 md:w-3/4">
                        <div class="lg:mr-8 md:mr-8 flex flex-col lg:fle-row md:flex-row lg:items-center md:items-center w-full">
                            <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                                <label for="sc_grade" class="tw-form-label">Scholatic system</label>
                            </div>
                            <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                                <select class="tw-form-control w-full" id="sc_grade" v-model="sc_grade" name="sc_grade">
                                    <option value="" disabled>Select Scholastic System</option>
                                    <option v-for="sc in scgradelist" v-bind:value="sc.id">{{sc.grading_method}}</option>
                                </select>
                                <span v-if="errors.sc_grade" class="text-red-500 text-xs font-semibold">{{errors.sc_grade[0]}}</span>
                            </div> 
                        </div> 
                    </div>
                </div> 

                <!--<div class="my-5"  v-if="this.exam_type!='classtest'">
                    <div class="tw-form-group w-full lg:w-3/4 md:w-3/4">
                        <div class="lg:mr-8 md:mr-8 flex flex-col lg:fle-row md:flex-row lg:items-center md:items-center w-full">
                            <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                                <label for="non_sc_grade" class="tw-form-label">Non scholatic system</label>
                            </div>
                            <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                                <select class="tw-form-control w-full" id="non_sc_grade" v-model="non_sc_grade" name="non_sc_grade">
                                    <option value="" disabled>Select Non-Scholastic System</option>
                                    <option v-for="non_sc in nonscgradelist" v-bind:value="non_sc.id">{{non_sc.grade_name}}</option>
                                </select>
                                <span v-if="errors.non_sc_grade" class="text-red-500 text-xs font-semibold">{{errors.non_sc_grade_id[0]}}</span>
                            </div>
                        </div> 
                    </div>
                </div> 

                <div class="my-5">
                    <div class="tw-form-group w-full lg:w-3/4 md:w-3/4">
                        <div class="lg:mr-8 md:mr-8 flex flex-col lg:fle-row md:flex-row lg:items-center md:items-center w-full">
                            <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                                <label for="grade_type" class="tw-form-label">Grade System</label>
                            </div>
                            <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                                <select class="tw-form-control w-full" id="grade_type" v-model="grade_type" name="grade_type">
                                    <option value="" disabled>Select Grade</option>
                                    <option value="scholastic">Scholastic</option>
                                    <option value="nonscholastic">Non-Scholastic</option>
                                </select>
                                <span v-if="errors.grade_type" class="text-red-500 text-xs font-semibold">{{errors.grade_name[0]}}</span>
                            </div>
                        </div> 
                    </div>
                </div>

                <div class="my-5">
                    <div class="tw-form-group w-full lg:w-3/4 md:w-3/4">
                        <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                            <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                                <label for="total_marks" class="tw-form-label">Total Marks</label>
                            </div>
                            <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                                <input type="text" name="total_marks" v-model="total_marks" class="tw-form-control w-full" id="name" placeholder="Total Marks">
                                <span v-if="errors.total_marks" class="text-red-500 text-xs font-semibold">{{errors.total_marks[0]}}</span>
                            </div>
                        </div>
                    </div>
                </div>-->

                <div class="my-6">
                    <a href="#" id="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="checkForm()">Save</a>
                    <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="reset()">Reset</a>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props:['url'],
        data(){
            return{
                exam:[],
                standardlist:[],
                scgradelist:[],
                nonscgradelist:[],
                standard_id:'',
                active: false,
                selected:[],
                selectedClass:[],
                allSelected: false,
                noneSelected:false,
                name:'',
                total_marks:'',
                sc_grade:'',
                non_sc_grade:'',
                exam_type:'',
                grade_type:'',
                exam_type_list:[{id : 'term' , name : 'Term Exam'} , {id : 'classtest' , name : 'Class Test'} , {id : 'final' , name : 'Final Exam'}],
                errors:[],
                success:null,
            }
        },
        computed: {
          selectedClassCount() {
            return this.selected.length
          }
        },
        methods:
        {   
            reset()
            {
                this.selected           =   [];
                this.name               =   '';
                this.exam_type          =   '';
                this.sc_grade           =   ''; 
            }, 

            checkForm()
            {
                this.errors=[];
                this.success=null;                            
                                     
                axios.post('/admin/exam/add',{
                    'standard_id':this.selected,
                    'name':this.name,
                    'exam_type':this.exam_type,
                    'sc_grade':this.sc_grade,
                    'non_sc_grade':this.non_sc_grade,
                }).then(response => {  
                    this.success = response.data.success;
                    this.reset();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            }, 
            toggleSelectAll(e) {
                if (e.target.checked) {
                  this.selected = this.standardlist.map(s => s.id)
                } else {
                  this.selected = []
                }
            },

            clearSelection() {
                this.selected = [];
            },
          
            getData()
            {
                axios.get('/admin/exam/list').then(response => {
                    this.standardlist = response.data.standardlist;
                    this.scgradelist = response.data.scgradelist;
                    this.nonscgradelist = response.data.nonscgradelist;
                    //console.log(this.standardlist);    
                });
            },
        },
        created()
        {
            this.getData();
        }
    }
</script>