<template>
    <div class="">
        <div>
	        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
            <div class="">
                <div class="">
                    <div class="pb-2">
                        <div class="">
                            <label for="academic_year" class="tw-form-label hidden lg:block">Select Academic Year</label>
                        </div>
                        <div class="">
                            <select class="tw-form-control w-full" id="academic_year" v-model="academic_year" name="academic_year" @change="showYear()">
                                <option  v-for="academic in academiclist" v-bind:value="academic.id" >{{ academic.name }}</option>
                            </select>
                            <span v-if="errors.academic"><p class="text-red-500 text-xs font-semibold">{{ errors.academic[0] }}</p></span>
                        </div>
                    </div> 
                </div>
            </div>
	   </div>
    </div>
</template>

<script>
	export default {
        props:[],
        data(){
            return{
                academic_year:'',
                academiclist:[],
                errors:[],
                success:null,
            }
        },
        
        methods:
        {
            showYear()
            {
                this.errors=[];
                this.success=null;    

                let formData=new FormData();

                formData.append('academic_year_id',this.academic_year);

                axios.post('/admin/academicyear/index',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    window.location.reload();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },
     
            getAcademicYear()
            {
                axios.get('/admin/list/academicyear').then(response => {
                    this.academiclist = response.data.academiclist;
                    // this.academic_year= response.data.current_year.id;
                    if (response.data.current_year) {
                        this.academic_year = response.data.current_year.id;
                    }
                });
            },
        },
        created()
        {
            this.getAcademicYear();
        }
    }
</script>