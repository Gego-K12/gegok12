<template>
  <div class="bg-white shadow px-4 py-4">
    <div>
	    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

      <div class="flex flex-col lg:flex-row md:flex-row">
        <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="title" class="tw-form-label">Title<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2">
              <input type="text" name="title" id="title" v-model="title" class="tw-form-control w-full">
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
              <textarea type="text" name="description" id="description" v-model="description" class="tw-form-control w-full" rows="3"></textarea>
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
            <a :href="url+'/'+this.attachment_file" target="_blank" v-if="this.attachment_file != '' ">Click to Open</a>
          </div>
        </div>

        <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="marks" class="tw-form-label">Mark</label>
            </div>
            <div class="mb-2">
              <input type="text" name="marks" id="marks" v-model="marks" class="tw-form-control w-full">
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
      </div>
	  </div>
  </div>
</template>

<script>


export default {

  props:['url','id'],

  data(){
    return{
      list:[],
      title:'',
      description:'',
      attachment:'',
      attachment_file:'',
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
      axios.get('/teacher/assignment/edit/list/'+this.id).then(response => {
        this.list = response.data;
        //console.log(this.list);
        this.setData();
      })
    },

    setData()
    {
      if(Object.keys(this.list).length > 0)
      {
        this.title            = this.list.title;
        this.description      = this.list.description;  
        this.attachment_file  = this.list.attachment; 
        this.marks            = this.list.marks; 
        this.assigned_date    = this.list.assigned_date;
        this.submission_date  = this.list.submission_date;
        this.status           = this.list.status;
      }  
    }, 

    checkForm()
    {
      this.errors=[];
      this.success=null; 

      let formData=new FormData();
                             
      formData.append('title',this.title);                
      formData.append('description',this.description);          
      formData.append('attachment',this.attachment);                  
      formData.append('marks',this.marks);                  
      formData.append('assigned_date',this.assigned_date);                 
      formData.append('submission_date',this.submission_date);
      formData.append('status', this.status);          
                     
      axios.post('/teacher/assignment/edit/'+this.id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
        this.success = response.data.success;
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