<template>
<div class="">
<div>
  <!-- <div class="group" v-if="parseInt(this.count)<=parseInt(this.no_of_groups)"> -->
  <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

<!--end-->
<!--start-->


    <div class="my-5">
          <div class="tw-form-group w-3/4">

    <div class="lg:mr-8 md:mr-8 flex items-center w-full">
      <div class="mb-2 w-1/4">
        <label for="standard_id" class="tw-form-label">Select Class</label>
      </div>
      <div class="mb-2 w-1/4">
        <select class="tw-form-control w-full" id="standard_id" v-model="standard_id" name="standard_id">
          <option value="" disabled>Select Class</option>
         <option value="" v-for="standard in standardlist" v-bind:value="standard.id">  {{standard.standard_name}} - {{standard.section_name}} </option>
        </select>
      </div>
      <span v-if="errors.standard_id"><p class="text-red-500 text-xs font-semibold">{{errors.standard_id[0]}}</p></span>
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
            <label class="tw-form-label ">Exam Type</label>
        </div>
        <div class="mb-2 w-1/4">
        <select v-model="type" id="type" class="tw-form-control w-full">
             <option value="private">Mid Exam</option>
              <option value="public">Public Exam</option>
              </select> 
               <span v-if="errors.type"><p class="text-danger text-xs my-1">{{errors.type[0]}}</p></span>
              </div>

          </div>
      </div>
</div>
<!--end-->
<!--start-->
<div class="my-5">
        <div class="tw-form-group w-3/4">
          <div class="lg:mr-8 md:mr-8 flex items-center w-full">
        <div class="mb-2 w-1/4">
            <label for="start_time" class="tw-form-label ">Date</label>
        </div>
        <div class="mb-2 w-1/4">
            <VueDatePicker
              v-model="start_time"
              :enable-time-picker="true"
              format="dd-MM-yyyy HH:mm:ss"
              model-type="yyyy-MM-dd HH:mm:ss"
              class="rounded w-full"
            />            <span v-if="errors.start_time" class="text-red-500 text-xs font-semibold">{{errors.start_time[0]}}</span>
          </div>
        </div>
        </div>
</div>


<!--end-->
<!--start-->


<div class="my-6">
      <a href="#" id="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="updateSchedule()">Update</a>
        
          
      </div>



</div>
</div>
</template>


<script>

  import { VueDatePicker } from '@vuepic/vue-datepicker'
  import '@vuepic/vue-datepicker/dist/main.css'

  export default {

    props:['id','url'],

   components: { VueDatePicker },

      data(){

        return{

            examschedule:[],
          
            standardlist:[],
            examlist:[],
            subjectlist:[],
            standard_id:'',
            exam_id:'',
            subject_id:'',
            type:'',
            start_time:'',
            
            
            

            errors:[],
            success:null,
        }
      },
        
  methods:
  {   

      editSchedule()
      {
         //alert('kjhkjh');

            axios.get('/admin/examschedule/show/'+this.id).then(response => {
        

            this.examschedule= response.data.data[0]; 

            this.standard_id=this.examschedule.standard_id;
            this.exam_id=this.examschedule.exam_id;
            this.subject_id=this.examschedule.subject_id;
            this.start_time=this.examschedule.start_time;
            this.type=this.examschedule.type;
           
          
         
           //window.location.reload();
          //
          //console.log(this.examschedule); 


           });             
      },


      updateSchedule()
      {
    
        
        this.errors=[];
        this.success=null; 


        let formData=new FormData();

        formData.append('standard_id',this.standard_id);      
        formData.append('exam_id',this.exam_id);
        formData.append('subject_id',this.subject_id);      
        formData.append('start_time',this.start_time);      
        formData.append('type',this.type);      
       
      
              
       axios.post('/admin/examschedule/update/'+this.id,formData).then(response => {   
        this.examschedule = response.data;
        this.success = response.data.success;
      
        console.log(this.examschedule);
        //alert(this.school_id);
        //window.location.reload();
        }).catch(error => {
          this.errors = error.response.data.errors;
        });
 
      },
          
        getData()
        {
            axios.get('/admin/examschedule/list').then(response => {
                this.standardlist = response.data.standardlist;
                this.examlist=response.data.examlist;
                this.subjectlist=response.data.subjectlist;
                this.editSchedule();
                //console.log(this.standardlist);
                  
            });
        },


  },
      created()
      {
        this.getData();
        //alert(this.standard);
      }
  }
</script>