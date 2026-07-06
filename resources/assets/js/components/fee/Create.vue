<template>
  <div class="bg-white shadow px-4 py-3">
    <div>
      <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

      <div class="my-5">
        <div class="tw-form-group w-full lg:w-3/4 md:w-3/4">
          <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
            <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
              <label for="type" class="tw-form-label">Fees For</label>
            </div>
            <div class="mb-2 w-full lg:w-1/2 md:w-1/2">
              <select name="type" id="type" v-model="type" class="tw-form-control w-full">
                <option value="" disabled>Select</option>
                <option v-for="value in list" v-bind:value="value.id">{{ value.name }}</option>
              </select>
              <span v-if="errors.type" class="text-red-500 text-xs font-semibold">{{errors.type[0]}}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="my-5" v-if="this.type == 'standard'">
        <div class="tw-form-group w-full lg:w-3/4 md:w-3/4">
          <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
            <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
              <label for="standardLink_id" class="tw-form-label">Select Class</label>
            </div>
            <div class="mb-2 w-full lg:w-1/2 md:w-1/2">
              <select name="standardLink_id" id="standardLink_id" v-model="standardLink_id" class="tw-form-control w-full">
                <option value="" disabled>Select Class</option>
                <option v-for="standardLink in standardLinklist" v-bind:value="standardLink.id">{{ standardLink.standard_section }}</option>
              </select>
               <span v-if="errors.standardLink_id" class="text-red-500 text-xs font-semibold">{{errors.standardLink_id[0]}}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="my-5">
        <div class="tw-form-group w-full lg:w-3/4 md-3/4">
          <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
            <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
              <label for="name" class="tw-form-label">Title</label>
            </div>
            <div class="mb-2 w-full lg:w-1/2 md:w-1/2">
              <input type="text" name="name" id="name" v-model="name" class="tw-form-control w-full" placeholder="Enter Title" @keyup='remaincharCount(20)' maxlength="20">
              <span v-if="errors.name" class="text-red-500 text-xs font-semibold">{{errors.name[0]}}</span>
            </div>
          </div>
        </div>
      </div>

       <div class="my-5">
        <div class="tw-form-group w-full lg:w-3/4 md:w-3/4">
          <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
            <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
              <label for="fee_type" class="tw-form-label">Fee Type</label>
            </div>
            <div class="mb-2 w-full lg:w-1/2 md:w-1/2">
              <select name="fee_type" id="fee_type" v-model="fee_type" class="tw-form-control w-full">
                 <option value="" disabled>Select Fee Type</option>
                <option value="structural">Structural</option>
              <option value="non-structural">Non-structural</option>
              </select>
               <span v-if="errors.fee_type" class="text-red-500 text-xs font-semibold">{{errors.fee_type[0]}}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="my-5">
        <div class="tw-form-group w-full lg:w-3/4 md-3/4">
          <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
            <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
              <label for="term" class="tw-form-label">Term</label>
              <span class="font-semibold text-xs text-gray-700">(Enter Number , for example 1)</span>
            </div>
            <div class="mb-2 w-full lg:w-1/2 md:w-1/2">
              <input type="term" name="term" id="term" v-model="term" class="tw-form-control w-full" placeholder="Enter Term">
              <span v-if="errors.term" class="text-red-500 text-xs font-semibold">{{errors.term[0]}}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="my-5">
        <div class="tw-form-group w-full lg:w-3/4 md-3/4">
          <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
            <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
              <label for="amount" class="tw-form-label">Amount</label>
            </div>
            <div class="mb-2 w-full lg:w-1/2 md:w-1/2">
              <input type="amount" name="amount" id="amount" v-model="amount" class="tw-form-control w-full" placeholder="Enter Amount">
              <span v-if="errors.amount" class="text-red-500 text-xs font-semibold">{{errors.amount[0]}}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="my-5">
        <div class="tw-form-group w-full lg:w-3/4 md:w-3/4">
          <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
            <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
              <label for="start_date" class="tw-form-label">Start Date</label>
            </div>
            <div class="mb-2 w-full lg:w-1/2 md:w-1/2">
              <input type="date" name="start_date" v-model="start_date" class="tw-form-control w-full" id="start_date">
              <span v-if="errors.start_date" class="text-red-500 text-xs font-semibold">{{errors.start_date[0]}}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="my-5">
        <div class="tw-form-group w-full lg:w-3/4 md:w-3/4">
          <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
            <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
              <label for="end_date" class="tw-form-label">End Date</label>
            </div>
            <div class="mb-2 w-full lg:w-1/2 md:w-1/2">
              <input type="date" name="end_date" v-model="end_date" class="tw-form-control w-full" id="end_date">
              <span v-if="errors.end_date" class="text-red-500 text-xs font-semibold">{{errors.end_date[0]}}</span>
            </div>
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

  export default {

    props:[],

    data(){
      return{
        type:'',
        standardLink_id:'',
        name:'',
        term:'',
        amount:'',
        fee_type:'',
        start_date:'',
        end_date:'',
        standardLinklist:[],
        list:[ { 'id' : 'school' , 'name' : 'School' } , { 'id' : 'standard' , 'name' : 'Class' }],
        errors:[],
        success:null,
      }
    },
        
    methods:
    {
      getList()
      {
        axios.get('/admin/fee/add/list').then(response => {
          this.standardLinklist = response.data.data;
          //console.log(this.standardLinklist)
        });
      },

      remaincharCount(len)
      {
        var maxLength = len;
        $('textarea').keyup(function() {
          var textlen = maxLength - $(this).val().length+'/'+maxLength;
          $('#rchars').text(textlen);
        });
      },

      resetForm()
      {
        this.type='';
        this.standardLink_id='';
        this.name='';
        this.term='';
        this.amount='';
        this.fee_type='';   
        this.start_date='';   
        this.end_date='';   
      }, 

      submitForm()
      {
        this.errors=[];
        this.success=null; 

        let formData=new FormData();

        formData.append('type',this.type);                 
        formData.append('standardLink_id',this.standardLink_id);                 
        formData.append('name',this.name);                 
        formData.append('fee_type',this.fee_type);                 
        formData.append('term',this.term);                 
        formData.append('amount',this.amount);                 
        formData.append('start_date',this.start_date);          
        formData.append('end_date',this.end_date);          
                     
        axios.post('/admin/fee/add',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
          this.success = response.data.success;
          this.resetForm();
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