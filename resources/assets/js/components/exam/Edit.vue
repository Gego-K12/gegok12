<template>
    <div class="">
        <div>
            <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
            <div class="my-5">
                <div class="tw-form-group w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex items-center w-full">
                        <div class="mb-2 w-1/4">
                            <label for="standard_id" class="tw-form-label">Select Class</label>
                        </div>
                        <div class="mb-2 w-1/4">
                            <select class="tw-form-control w-full" id="standard_id" v-model="standard_id" name="standard_id">
                                <option value="" disabled>Select Class</option>
                                <option v-for="standard in standardlist" v-bind:value="standard.id">{{ standard.standard_section }}</option>
                            </select>
                        </div>
                        <span v-if="errors.standard_id"><p class="text-red-500 text-xs font-semibold">{{errors.standard_id[0]}}</p></span>
                    </div> 
                </div>
            </div>

            <div class="my-5">
                <div class="tw-form-group w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex items-center w-full">
                        <div class="mb-2 w-1/4">
                            <label for="name" class="tw-form-label">Exam Name</label>
                        </div>
                        <div class="mb-2 w-1/4">
                            <input type="text" name="name" v-model="name" class="tw-form-control w-full" id="name" placeholder="Exam Name">
                            <span v-if="errors.name" class="text-red-500 text-xs font-semibold">{{errors.name[0]}}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-5">
                <div class="tw-form-group w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex items-center w-full">
                        <div class="mb-2 w-1/4">
                            <label for="total_marks" class="tw-form-label ">Total Marks</label>
                        </div>
                        <div class="mb-2 w-1/4">
                            <input type="text" name="total_marks" v-model="total_marks" class="tw-form-control w-full" id="name">
                            <span v-if="errors.total_marks" class="text-red-500 text-xs font-semibold">{{errors.total_marks[0]}}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-6">
                <a href="#" id="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="updateExam()">Update</a>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        props:['id','url'],
        data(){
            return{
                exam:[],
                standardlist:[],
                standard_id:'',
                name:'',
                total_marks:'',
                errors:[],
                success:null,
            }
        },
        
        methods:
        {
            editExam()
            {
                axios.get('/admin/exam/show/'+this.id).then(response => {
                    this.exam= response.data.data[0]; 
                    this.standard_id=this.exam.standard_id;
                    this.name=this.exam.name;
                    this.total_marks=this.exam.total_marks;
                    //console.log(this.exam); 
                });             
            },

            updateExam()
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();

                formData.append('standard_id',this.standard_id);      
                formData.append('name',this.name);
                formData.append('total_marks',this.total_marks);      
         
                axios.post('/admin/exam/update/'+this.id,formData).then(response => {   
                    this.success = response.data.success;
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            getData()
            {
                axios.get('/admin/exam/list').then(response => {
                    this.standardlist = response.data.standardlist;
                    this.editExam();                  
                });
            },
        },
        created()
        {
            this.getData();
        }
    }
</script>