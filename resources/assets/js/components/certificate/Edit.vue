<template>
  <div class="bg-white shadow px-4 py-3">
    <div>
	    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

      <div class="flex flex-col lg:flex-row md:flex-row">
        <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">

          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="standardLink_id" class="tw-form-label" >Select Class<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2">
              <select name="standardLink_id" id="standardLink_id" v-model="standardLink_id" class="tw-form-control w-full" @change="getstudents()">
                <option value="" disabled>Select Class</option>
                <option v-for="standardLink in standardLinklist" v-bind:value="standardLink.id">{{ standardLink.standard_section }}</option>
              </select>
            </div>
            <span v-if="errors.standardLink_id" class="text-red-500 text-xs font-semibold">{{errors.standardLink_id[0]}}</span>
          </div> 
        </div>

        <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="student_id" class="tw-form-label">Select Student<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2">
              <select name="student_id" id="student_id" v-model="student_id" class="tw-form-control w-full">
                <option value="" disabled>Select student</option>
                <option v-for="student in students" v-bind:value="student.id">{{ student.name }}</option>
              </select>
            </div>
              <span v-if="errors.student_id" class="text-red-500 text-xs font-semibold">{{errors.student_id[0]}}</span>
          </div>
        </div>
      </div>

      <div class="flex flex-col lg:flex-row md:flex-row">
        <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="title" class="tw-form-label">Program name<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2">
              <input type="text" name="title" id="title" v-model="title" class="tw-form-control w-full" placeholder="Enter Title">
            </div>
            <span v-if="errors.title" class="text-red-500 text-xs font-semibold">{{errors.title[0]}}</span>
          </div>
        </div>
      
        <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="event_name" class="tw-form-label">Event Name<span class="text-red-500">*</span></label>
            </div>
             <div class="mb-2">
              <input type="text" name="event_name" id="event_name" v-model="event_name" class="tw-form-control w-full" placeholder="Enter Title">
            </div>
            <span v-if="errors.event_name" class="text-red-500 text-xs font-semibold">{{errors.event_name[0]}}</span>
          </div>
        </div>
      </div>

      <div class="flex flex-col lg:flex-row md:flex-row">
      

        <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="prize" class="tw-form-label">Prize</label>
            </div>
            <div class="mb-2">
              <input type="text" name="prize" id="prize" v-model="prize" class="tw-form-control w-full" placeholder="Enter Mark">
            </div>
            <span v-if="errors.prize" class="text-red-500 text-xs font-semibold">{{errors.prize[0]}}</span>
          </div>
        </div>
        <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="assigned_date" class="tw-form-label">Date<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2">
              <input type="date" name="assigned_date" v-model="assigned_date" id="assigned_date" class="tw-form-control w-full">
            </div>
            <span v-if="errors.assigned_date" class="text-red-500 text-xs font-semibold">{{errors.assigned_date[0]}}</span>
          </div>
        </div>
      
      </div>

      
    	
      <div class="my-6">
        <a href="#" id="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="checkForm()">Submit</a>
    		<a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="reset()">Reset</a>	
      </div>
	  </div>
  </div>
</template>

<script>

export default {
  props:['url','id'],

  data(){
    return{
        form: {
               standardLink_id:'',student_id: '' //define your v-models here
            },
      standardLinklist:[],
      subjectlist:[],
      subject_id:'',
      standardLink_id:'',
      title:'',
      description:'',
      attachment:'',
      marks:'',
      assigned_date:'',
      submission_date:'',
      errors:[],
      success:null,
      student_id:'',
      event_name:'',
      prize:'',
      students:[],
      certificate_id:'',
    }
  },
        
  methods:
  {

    getdata()
    {
     
       axios.get('/admin/certificate/show/'+this.id).then(response => {
        this.certificate = response.data;
        this.student_id = this.certificate.student_id;
        this.title = this.certificate.program_name;
        this.event_name = this.certificate.event_name;
        this.prize = this.certificate.certificate_for;
        this.assigned_date = this.certificate.date;
        this.standardLink_id = this.certificate.standardLink_id;
        this.getstudent(this.standardLink_id);
        //console.log(this.certificate.date);
      });
      //alert(response);
    },
    getList()
    {
      axios.get('/admin/certificate/classList').then(response => {
        this.standardLinklist = response.data.standardLinklist;
      console.log(this.standardLinklist);
      });
      
    },

    resetForm()
    {
      this.standardLink_id='';
      this.subject_id='';
      this.title='';
      this.description='';  
      this.attachment=''; 
      this.marks=''; 
      this.assigned_date='';
      this.submission_date='';  
      this.student_id='';  
      this.event_name='';  
      this.prize='';  
    }, 

    checkForm()
    {
      this.errors=[];
      this.success=null; 
      let formData=new FormData();
                
      formData.append('standardLink_id',this.standardLink_id);  
      formData.append('subject_id',this.subject_id);                
      formData.append('title',this.title);                
      formData.append('description',this.description);          
      formData.append('attachment',this.attachment);                  
      formData.append('marks',this.marks);                  
      formData.append('assigned_date',this.assigned_date);                 
      formData.append('submission_date',this.submission_date);           
      formData.append('student_id',this.student_id);           
      formData.append('event_name',this.event_name);           
      formData.append('prize',this.prize);           
      formData.append('certificate_id',this.id);           
                     
      axios.post('/admin/certificate/update'+this.certificate_id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
        this.success = response.data.success;
        this.resetForm();
      }).catch(error => {
        this.errors = error.response.data.errors;
      });
    },

    OnFileSelected(event)
    {
      this.attachment = event.target.files[0];
    },
    getstudents()
    {
      //alert(this.standardLink_id);
       axios.get('/admin/certificate/studentList/'+this.standardLink_id).then(response => {
        this.students = response.data.data;//alert(response);
      });
      //alert(response);
    },
     getstudent()
    {
      
       axios.get('/admin/certificate/studentList/'+this.standardLink_id).then(response => {
        this.students = response.data.data;
      });
      //alert(response);
    },
  },

  created()
  {
    this.getList();
    this.getdata();
    
   // this.onChange();
  }
}

$('#standardLink_id').on('change', () => {
   // alert("test" + index_selected);
 });
</script>