<template>
  <div class="">
    <div>
      <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
      <div class="my-8">
        <div class="w-full flex items-center justify-between mb-4">
          <div class="flex items-center text-sm">
            <div class="pr-3 border-r">
              {{ parseInt(this.selectedClassCount) }} Classes selected
            </div>
            <div class="px-3 border-r relative">
              <input class="opacity-0 absolute w-full h-full cursor-pointer" type="checkbox" @click="selectAll($event)" v-model="allSelected"><span>Select All</span>
            </div>
            <div class="px-3 relative" v-if="this.selectedClassCount > 0">
              <input class="opacity-0 absolute w-full h-full cursor-pointer" type="checkbox" @click="selectNone($event)" v-model="noneSelected"><span>Select None</span>
            </div>
          </div>
        </div>

        <div class="flex flex-wrap">
          <div class="w-full lg:w-1/4 md:w-1/2 my-2 relative" v-for="standard in standardlist">
            <div class="flex items-center member-list">
              <div class="flex items-center student_select">
                <input class="w-5 h-5" type="checkbox" v-model="selected" :value="standard['id']" @click="selectedCount(standard['id'],$event)">
                <label></label>
              </div>
              <div class="flex p-2 w-full active">
                <div class="px-2">
                  <h2 class="font-bold text-base text-gray-700">{{ standard.standard_section }}</h2>
                </div>
              </div>
            </div>
          </div>
          <span v-if="errors.selected" class="text-red-500 text-xs font-semibold">{{errors.selected[0]}}</span>
        </div>
      </div>

      <div class="bg-white shadow px-4 py-3">
        <div class="my-5 ">
          <div class="tw-form-group w-full lg:w-3/4 md:w-3/4">
            <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
              <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                <label for="topic_id" class="tw-form-label">Select Topic</label>
              </div>
              <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                <select class="tw-form-control w-full" id="topic_id" v-model="topic_id" name="topic_id">
                  <option value="" disabled>Select Topic</option>
                  <option value="" v-for="topic in topics" v-bind:value="topic.id">{{topic.name}}</option>
                </select>
                <span v-if="errors.topic_id" class="text-red-500 text-xs font-semibold">{{errors.topic_id[0]}}</span>
              </div>
            </div>
          </div>
        </div>

       <!--  <div class="my-5">
          <div class="tw-form-group w-full lg:w-3/4 md:w-3/4">
            <div class="lg:mr-8 md:mr-8 flex flex-col lg:fle-row md:flex-row lg:items-center md:items-center w-full">
              <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                <label for="type" class="tw-form-label">Assign Type</label>
              </div>
              <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                <select class="tw-form-control w-full" id="type" v-model="type" name="type">
                  <option value="" disabled>Select Type</option>
                  <option value="class">class</option>
                </select>
                <span v-if="errors.type" class="text-red-500 text-xs font-semibold">{{errors.type[0]}}</span>
              </div>
            </div> 
          </div>
        </div>  -->

        <div class="my-6">
          <a href="#" id="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm()">Submit</a>
          <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="reset()">Reset</a>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
  export default {
    props:['url','topics_id'],
    data(){
      return{
        standardlist:[],
        topics:[],
        topic_id:'',
        active: false,
        selected:[],
        selectedClass:[],
        selectedClassCount:0,
        allSelected: false,
        noneSelected:false,
        type:'class',
        errors:[],
        success:null,
      }
    },
        
    methods:
    {   
      reset()
      {
        this.topic_id='';      
        this.type='';      
      }, 

      submitForm()
      {
        axios.post(this.url+'/teacher/quiz/participants',{
          type:this.type,       
          topic_id:this.topic_id,
          selected:this.selected,
        }).then(response => {
          this.errors=[];
          this.success=response.data.message;
        }).catch(error=>{
          this.errors=error.response.data.errors;
        }); 
      }, 

      selectAll(e) 
      { 
        var selected = [];
        if (e.target.checked) 
        {
          $('.standard-list').addClass('standard_selected');
          if(this.allSelected == false) 
          {
            this.standardlist.forEach(function (standard) 
            {
              selected.push(standard.id);
            });
            this.selected = selected; 
            this.selectedClassCount = selected.length;
            this.allSelected = true;
          }
        }
        else
        {
          this.standardlist.forEach(function (standard) 
          {
            selected.splice(standard.id);
          });
          this.selected = selected;
          this.selectedClassCount = selected.length;
          this.noneSelected = false;
          $('.standard-list').removeClass('standard_selected');         
        }
      },

      selectNone(e) 
      { 
        var selected = [];
        if (e.target.checked) 
        {
          $('.standard-list').removeClass('standard_selected');
          this.standardlist.forEach(function (standard) 
          {
            selected.splice(standard.id);
          });
          this.selected = selected;
          this.selectedClassCount = selected.length;
          this.noneSelected = false;
        }
      },

      selectedCount(id,e)
      {
        if (e.target.checked)
        {
          this.selectedClassCount++;
          $('#'+id).addClass('standard_selected');
        }
        else
        {
          this.selectedClassCount--;
          $('#'+id).removeClass('standard_selected');
        }
      },
            
      getData()
      { 
        axios.get('/teacher/quiz/participants/list').then(response => {
          this.topics         = response.data.topics;
          this.standardlist   = response.data.standardlist;
          this.topic_id       = this.topics_id;
        });
      },
    },
    created()
    {
      this.getData();
    }
  }
</script>