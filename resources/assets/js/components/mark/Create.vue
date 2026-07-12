<template>
<div class="">
<div>
	<!-- <div class="group" v-if="parseInt(this.count)<=parseInt(this.no_of_groups)"> -->
	<div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>


      <div class="my-5">
          <div class="tw-form-group w-3/4">

          <div class="lg:mr-8 md:mr-8 flex items-center w-full">
            <div class="mb-2 lg:w-1/4">
                <label for="student_status" class="tw-form-label">Student Status</label>
            </div>
            <div class="mb-2 w-1/4">
             <select v-model="student_status" class="repeats tw-form-control w-full" placeholder = "Select status" id="student_status">
             <option value="present">Present</option>
              <option value="absent">Absent</option>
              </select>
            </div>
          </div>
            <span v-if="errors.student_status" class="text-red-500 text-xs font-semibold">{{errors.student_status[0]}}</span>
          </div>
      </div>

<!--end-->
<!--start-->

  <!--   <div class="my-5">
          <div class="tw-form-group w-3/4">

    <div class="lg:mr-8 md:mr-8 flex items-center w-full">
      <div class="mb-2 w-1/4">
        <label for="standard_id" class="tw-form-label">Select Class</label>
      </div>
      <div class="mb-2 w-1/4">
        <select class="tw-form-control w-full" id="standard_id" v-model="standard_id" name="standard_id">
          <option value="" disabled>Select Class</option>
         <option value="" v-for="standard in standardlist" v-bind:value="standard.id">{{standard.standard_name}}-{{standard.section_name}}</option>
        </select>
      </div>
      <span v-if="errors.standard_id"><p class="text-red-500 text-xs font-semibold">{{errors.standard_id[0]}}</p></span>
    </div> 

  </div>
</div> -->
   

<!--end-->
<!--start-->

 <div class="my-5">
          <div class="tw-form-group w-3/4">

    <div class="lg:mr-8 md:mr-8 flex items-center w-full">
      <div class="mb-2 w-1/4">
        <label for="subject_id" class="tw-form-label">Select Subject</label>
      </div>
      <div class="mb-2 w-1/4">
        <select class="tw-form-control w-full" id="subject_id" v-model="subject_id" name="subject_id">
          <option value="" disabled>Select Subject</option>
         <option value="" v-for="subject in subjectlist[this.standard_id]" v-bind:value="subject.id">{{subject.name}}</option>
        </select>
      </div>
      <span v-if="errors.subject_id" class="text-red-500 text-xs font-semibold">{{errors.subject_id[0]}}</span>
    </div> 
  </div>
    </div>   

<!--end-->
<!--start-->

<div class="my-5">
          <div class="tw-form-group w-3/4">

    <div class="lg:mr-8 md:mr-8 flex items-center w-full">
      <div class="mb-2 w-1/4">
        <label for="exam_id" class="tw-form-label">Select Exam</label>
      </div>
      <div class="mb-2 w-1/4">

        <select class="tw-form-control w-full" id="exam_id" v-model="exam_id" name="exam_id">
          <option value="" disabled>Select Exam</option>
         <option value="" v-for="exam in examlist[this.standard_id]" v-bind:value="exam.id">{{exam.name}}</option>
        </select>
      </div>
      <span v-if="errors.exam_id" class="text-red-500 text-xs font-semibold">{{errors.exam_id[0]}}</span>
    </div> 
  </div>
    </div>   

<!--end-->
<!--start-->

  	
      <div class="my-5"  v-if="this.student_status=='present'">
          <div class="tw-form-group w-3/4">

          <div class="lg:mr-8 md:mr-8 flex items-center w-full">
            <div class="mb-2 lg:w-1/4">
                <label for="obtained_marks" class="tw-form-label">Marks Obtained</label>
            </div>
            <div class="mb-2 w-1/4">
              <input type="text" name="obtained_marks" id="obtained_marks" v-model="obtained_marks" class="tw-form-control w-full">
            </div>
          </div>
            <span v-if="errors.obtained_marks" class="text-red-500 text-xs font-semibold">{{errors.obtained_marks[0]}}</span>
          </div>
     
<!--end-->
<!--start-->

      <div class="my-5">
          <div class="tw-form-group w-3/4">

          <div class="lg:mr-8 md:mr-8 flex items-center w-full">
            <div class="mb-2 lg:w-1/4">
                <label for="total_marks" class="tw-form-label">Total Marks</label>
            </div>
            <div class="mb-2 w-1/4">
              <input type="text" name="total_marks" id="total_marks" v-model="total_marks" class="tw-form-control w-full">
            </div>
          </div>
            <span v-if="errors.total_marks" class="text-red-500 text-xs font-semibold">{{errors.total_marks[0]}}</span>
          </div>
      </div>
 </div>
<!--end-->
<!--start-->


      <div class="my-5">
          <div class="tw-form-group w-3/4">

          <div class="lg:mr-8 md:mr-8 flex items-center w-full">
            <div class="mb-2 lg:w-1/4">
                <label for="comments" class="tw-form-label">Comments</label>
            </div>
            <div class="mb-2 w-1/4">
              <input type="text" name="comments" id="comments" v-model="comments" class="tw-form-control w-full">
            </div>
          </div>
            <span v-if="errors.comments" class="text-red-500 text-xs font-semibold">{{errors.comments[0]}}</span>
          </div>
      </div>

<!--end-->
<!--start-->




      
    	
    	<div class="my-6">
      <a href="#" id="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="checkForm()">Submit</a>
    		<a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="reset()">Reset</a>
      		
    	</div>
	</div>


  </div>
</template>



<script>
	export default {
    props:['user_id','standard_id'],
      data(){
        return{
        	  mark:[],
            subjectlist:[],
            examlist:[],
            subject_id:'',
            exam_id:'',
          	obtained_marks:'',
            total_marks:'',
            comments:'',
            student_status:'',

          	errors:[],
          	success:null,
        }
      },
        
      methods:
        {
        	reset()
            {
              this.standard_id='';
              this.exam_id='',
              this.obtained_marks='';  
              this.total_marks='';  
              this.comments=''; 
              this.student_status='';
            }, 

            checkForm()
            {
              this.errors=[];
              this.success=null; 

              let formData=new FormData();

              formData.append('standard_id',this.standard_id);                 
              formData.append('subject_id',this.subject_id);                 
              formData.append('user_id',this.user_id);                 
              formData.append('exam_id',this.exam_id);                 
              formData.append('obtained_marks',this.obtained_marks);          
              formData.append('total_marks',this.total_marks);          
              formData.append('comments',this.comments);          
              formData.append('student_status',this.student_status);          
              
        	  axios.post('/admin/marks/add',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
                this.mark = response.data;     
          			this.success = response.data.success;
          			this.resetForm();
                //console.log(this.work);
        		}).catch(error => {
          			this.errors = error.response.data.errors;
                });
            },



            getData()
          {
            axios.get('/admin/marks/list').then(response => {
                this.subjectlist = response.data.subjectlist;
                this.examlist = response.data.examlist;
                //console.log(this.standardlist);
                  
            });
          },


        },
      created()
      {
      	this.getData();
      	//alert(this.standard_id);
      }
  }
</script>