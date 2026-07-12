<template>
    <div class="bg-white shadow px-4 py-3">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
        <div>
            <!--start-->
            <div class="my-5">
                <div class="tw-form-group w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex items-center w-full">
                        <div class="mb-2 w-1/4">
                            <label for="academic_year_id" class="tw-form-label">Academic Year</label>
                        </div>
                        <div class="mb-2 w-1/4">
                            <select class="tw-form-control w-full" id="academic_year_id" v-model="academic_year_id" name="academic_year_id">
                                <option value="" disabled>Select Academic Year</option>
                                <option v-for="academic in academiclist" v-bind:value="academic.id">{{ academic.name }}</option>
                            </select>
                        </div>
                        <span v-if="errors.academic_year_id" class="text-red-500 text-xs font-semibold">{{ errors.academic_year_id[0] }}</span>
                    </div> 
                </div>
            </div>   
            <!--end-->

            <!--start-->
            <div class="my-5">
                <div class="tw-form-group w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex items-center w-full">
                        <div class="mb-2 w-1/4">
                            <label for="exam_id" class="tw-form-label">Exam</label>
                        </div>
                        <div class="mb-2 w-1/4">
                            <select class="tw-form-control w-full" id="exam_id" v-model="exam_id" name="exam_id">
                                <option value="" disabled>Select Exam</option>
                                <option v-for="exam in examlist" v-bind:value="exam.id">{{ exam.name }}</option>
                            </select>
                        </div>
                        <span v-if="errors.exam_id" class="text-red-500 text-xs font-semibold">{{ errors.exam_id[0] }}</span>
                    </div> 
                </div>
            </div>   
            <!--end-->

            <!--start-->
            <div class="my-6">
                <a href="#" id="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="checkForm()">Submit</a>
                <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="reset()">Reset</a>
            </div>  
            <!--end-->
    
            <!--display-->
            <div class="w-full" v-if="Object.keys(showmark).length > 0">  
                <div>
                    <h1 class="admin-h1 mb-5 flex items-center">
                        <span class="">Mark List</span>
                    </h1>
                </div>
                <div class="py-3 custom-table">
                    <table class="w-full">
                        <thead class="bg-grey-light">
                            <tr class="border-t-2 border-b-2">
                                <th class="text-left text-sm px-2 py-2 text-grey-darker">Exam</th>
                                <th class="text-left text-sm px-2 py-2 text-grey-darker">Subject</th>
                                <th class="text-left text-sm px-2 py-2 text-grey-darker">Marks</th>
                            </tr>
                        </thead>
                        <tbody v-if="Object.keys(showmark).length > 0">
                            <tr v-for="mark in showmark">
                                <td>{{ mark.exam.name}}</td>
                                <td>{{ mark.subject.name}}</td>
                                <td>{{ mark.obtained_marks }}</td>
                            </tr>
                        </tbody>
                        <tbody v-else>
                            <tr class="border-b">
                                <td colspan="4">
                                    <p class="font-semibold text-s" style="text-align: center">No Records Found</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <!--end display-->
        </div>
    </div>
</template>

<script>
    export default {
        props:['user_id','standard_id','mode'],
        data(){
            return{
                showmark:[],
                academiclist:[],
                examlist:[],
                academic_year_id:'',
                exam_id:'',
                errors:[],
                success:null,
            }
        },
            
        methods:
        {
            reset()
            { 
                this.academic_year_id='';
                this.exam_id='';
            }, 

            checkForm()
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();

                formData.append('standard_id',this.standard_id);                                 
                formData.append('user_id',this.user_id);
                formData.append('academic_year_id',this.academic_year_id);                  
                formData.append('exam_id',this.exam_id);                 
                  
                axios.get('/'+this.mode+'/marks/show',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                    this.getMark();    
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            getData()
            {
                axios.get('/'+this.mode+'/marks/view/'+this.standard_id).then(response => {
                    this.academiclist = response.data.academiclist;
                    this.examlist = response.data.examlist;       
                });
            },

            getMark()
            {
                axios.get('/'+this.mode+'/marks/viewmark/'+this.standard_id+'/'+this.user_id+'/'+this.exam_id+'/'+this.academic_year_id).then(response => {
                    this.showmark = response.data;
                    this.success  = response.data.success;
                });
            },
        },

        created()
        {
            this.getData();
        }
    }
</script>