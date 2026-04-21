<template>
  <div class="bg-white shadow px-4 py-3">
    <div>
	    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

      <div class="flex flex-col lg:flex-row md:flex-row">
        <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="standardLink_id" class="tw-form-label">Select Class<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2">
              <select name="standardLink_id" id="standardLink_id" v-model="standardLink_id" class="tw-form-control w-full">
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
              <label for="subject_id" class="tw-form-label">Select Subject<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2">
              <select name="subject_id" id="subject_id" v-model="subject_id" class="tw-form-control w-full">
                <option value="" disabled>Select Subject</option>
                <option v-for="subject in subjectlist[this.standardLink_id]" v-bind:value="subject.subject_id">{{ subject.subject_name }}</option>
              </select>
            </div>
              <span v-if="errors.subject_id" class="text-red-500 text-xs font-semibold">{{errors.subject_id[0]}}</span>
          </div>
        </div>
      </div>

      <div class="flex flex-col lg:flex-row md:flex-row">
        <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="title" class="tw-form-label">Title<span class="text-red-500">*</span></label>
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
              <label for="description" class="tw-form-label">Description<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2">
              <textarea type="text" name="description" id="description" v-model="description" class="tw-form-control w-full" rows="3" placeholder="Enter Description"></textarea>
            </div>
            <span v-if="errors.description" class="text-red-500 text-xs font-semibold">{{errors.description[0]}}</span>
          </div>
        </div>
      </div>

      <div class="flex flex-col lg:flex-row md:flex-row">
        <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="attachment" class="tw-form-label">Attachment</label>
            </div>
            <div class="mb-2">
              <input type="file" name="attachment" @change="OnFileSelected" id="attachment" class="tw-form-control w-full">
            </div>
            <span v-if="errors.attachment" class="text-red-500 text-xs font-semibold">{{errors.attachment[0]}}</span>
          </div>
        </div>

        <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="marks" class="tw-form-label">Mark</label>
            </div>
            <div class="mb-2">
              <input type="text" name="marks" id="marks" v-model="marks" class="tw-form-control w-full" placeholder="Enter Mark">
            </div>
            <span v-if="errors.marks" class="text-red-500 text-xs font-semibold">{{errors.marks[0]}}</span>
          </div>
        </div>
      </div>

      <div class="flex flex-col lg:flex-row md:flex-row">
        <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="assigned_date" class="tw-form-label">Assigned Date<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2">
              <input type="date" name="assigned_date" v-model="assigned_date" id="assigned_date" class="tw-form-control w-full">
            </div>
            <span v-if="errors.assigned_date" class="text-red-500 text-xs font-semibold">{{errors.assigned_date[0]}}</span>
          </div>
        </div>

        <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="submission_date" class="input-group-addon tw-form-label">Submission Date<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2">
              <input type="date" name="submission_date" v-model="submission_date" id="submission_date" class="tw-form-control w-full">
            </div>
            <span v-if="errors.submission_date" class="text-red-500 text-xs font-semibold">{{errors.submission_date[0]}}</span> 
          </div>
        </div>
      </div>
      <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="status" class="tw-form-label">
              Status<span class="text-red-500">*</span>
            </label>
          </div>
          <div class="mb-2">
            <select name="status" id="status" v-model="status" class="tw-form-control w-full">
              <option value="" disabled>Select Status</option>
              <option value="pending">Draft</option>
              <option value="ongoing">Publish</option>
            </select>
          </div>
          <span v-if="errors.status" class="text-red-500 text-xs font-semibold">
            {{ errors.status[0] }}
          </span>
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

  data(){
    return{
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
      status: '',
    }
  },
        
  methods:
  {
    getList()
    {
      axios.get('/teacher/assignment/add/list').then(response => {
        this.standardLinklist = response.data.standardLinklist;
        this.subjectlist = response.data.subjectlist;
        //console.log(response.data)
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
      formData.append('status', this.status);          
                     
      axios.post('/teacher/assignment/add',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
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
  },

  created()
  {
    this.getList();
  }
}
</script>