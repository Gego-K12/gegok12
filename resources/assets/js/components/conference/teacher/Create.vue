<template>
   <div class="w-full">
    <loading v-model:active="isLoading"
        :can-cancel="true"
        :on-cancel="onCancel"
        :is-full-page="fullPage"></loading>
        <div>
            <h1 class="admin-h1 my-3 flex items-center">
                <a :href="this.url+'/teacher/video-conference'" title="Back" class="rounded-full bg-gray-100 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" xml:space="preserve" width="512px" height="512px" class="w-3 h-3 fill-current text-gray-700"><g><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124 c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844    L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412 c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008 c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788    C492,219.198,479.172,207.418,464.344,207.418z" data-original="#000000" fill="" class="active-path"></path></g></g></g></svg>
                </a>
                <span class="mx-3">Create Room</span>
            </h1>
        </div>
        <div class="bg-white shadow px-4 py-3">

           <!-- *** -->
                <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                     <div class="mb-2">
                    <label for="standardLink_id" class="tw-form-label">Class<span class="text-red-500">*</span></label>
                    </div>
                        <select class="tw-form-control w-full" id="standardLink_id" v-model="standardLink_id" name="standardLink_id" @change="getStudents()">
                                <option value="" disabled>Select Class</option>
                                <option v-for="standard in standardlist" v-bind:value="standard.id">{{ standard.standard_section }}</option>
                        </select>
                        <span v-if="errors.standard"><p class="text-red-500 text-xs font-semibold">{{errors.standard[0]}}</p></span>
                    </div>
                    </div>

                    <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                      <div class="mb-2">
                     <label for="subject_id" class="tw-form-label">Subject</label>
                    </div>
                     <select class="tw-form-control w-full" id="subject_id" v-model="subject_id" name="subject_id">
                                <option value="" disabled>Select Subject</option>
                                <option v-for="subject in subjectlist[this.standardLink_id]" v-bind:value="subject.subject_id">{{ subject.subject_name }}</option>
                            </select>
                            <span v-if="errors.subject" class="text-red-500 text-xs font-semibold">{{errors.subject[0]}}</span>
                    </div>
                    </div>
                    </div>
              <!-- *** -->
           

             <!-- *** -->
                <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                     <div class="mb-2">
                    <label for="name" class="tw-form-label">Title</label>
                    </div>
                      <input type="text" name="name" v-model="name" id="name" class="tw-form-control w-full"  placeholder="Title">
                      <span v-if="errors.name"  class="text-danger text-xs">{{ errors.name[0] }}</span>
                    </div>
                    </div>

                    <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                      <div class="mb-2">
                     <label for="description" class="tw-form-label">Description</label>
                    </div>
                     <textarea type="textarea" name="description" v-model="description" id="description" class="tw-form-control w-full" placeholder="Description"></textarea>
                            <span v-if="errors.description"  class="text-danger text-xs">{{ errors.description[0] }}</span>
                    </div>
                    </div>
                    </div>
              <!-- *** -->


               <!-- *** -->
                <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                     <div class="mb-2">
                    <label class="tw-form-label">Schedule date</label>
                    </div>
                       <VueDatePicker
                         v-model="joining_date"
                         format="dd-MM-yyyy HH:mm:ss"
                         model-type="format"
                         :enable-time-picker="true"
                         :is-24="true"
                         :auto-apply="true"
                         input-class-name="rounded w-full"
                       />
                       <span v-if="errors.joining_date" class="text-red-500 text-xs font-semibold">{{errors.joining_date[0]}}</span>
                    </div>
                    </div>

                    <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                      <div class="mb-2">
                     <label for="duration" class="tw-form-label">Duration(mins)</label>
                    </div>
                     <input  type="number" min="0" name="duration" v-model="duration" id="duration" class="tw-form-control w-full" placeholder="Duration">
                            <span v-if="errors.duration"  class="text-danger text-xs">{{ errors.duration[0] }}</span>
                    </div>
                    </div>
                    </div>
              <!-- *** -->

                <!-- *** -->
                <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                     <div class="mb-2">
                    <label class="tw-form-label">Class Link</label>
                    </div>
                       <input  type="text"  name="class_link" v-model="class_link" id="class_link" class="tw-form-control w-full" placeholder="Class Link">
                       <span v-if="errors.class_link" class="text-red-500 text-xs font-semibold">{{errors.class_link[0]}}</span>
                    </div>
                    </div>
                    </div>
              <!-- *** -->

               

                <div class="admin-h1 my-3">
             

               <!-- teacher list -->
                <div class="my-8">
        <div class="w-full flex flex-wrap items-center justify-between mb-4">
          <div class="flex flex-wrap items-center text-sm">
            <div class="px-3 border-r" v-if="this.selectedUsersCount > 0">
              {{ parseInt(this.selectedUsersCount) }} Students selected
            </div>
            <div class="px-3 border-r relative">
              <input class="opacity-0 absolute w-full h-full cursor-pointer" type="checkbox" @click="selectAll($event)" v-model="allSelected"><span>Select All</span>
            </div>
            <div class="px-3 relative" v-if="this.selectedUsersCount > 0">
              <input class="opacity-0 absolute w-full h-full cursor-pointer" type="checkbox" @click="selectNone($event)" v-model="noneSelected"><span>Select None</span>
            </div>
            <span v-if="errors.students"  class="text-danger text-xs">{{ errors.students[0] }}</span>
          </div> 
        </div>

         <div class="flex flex-wrap">
                    <div class="w-full lg:w-1/4 md:w-1/2 my-2 relative" v-for="user in users">
                        <div class="flex items-center member-list">
                            <div class="flex items-center student_select">
                                <input class="absolute w-5 h-5" type="checkbox" v-model="selected" :value="user['id']" @click="selectedCount(user['id'],$event)" >
                                <label></label>
                            </div>
                            <div class="flex p-2 w-full active">
                                <div class="px-2">
                                     <h2 class="font-bold text-base text-gray-700">{{user['fullname']}}</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

       
      </div>
                <!--teacher list  -->
                <div class="mt-6 mb-4">
                    <button class="btn btn-primary blue-bg text-white rounded px-3 py-1 text-sm font-medium" id="submit" @click="submitForm()">Submit</button>
                </div>
            
        </div>
      </div>
    </div>
</template>
<script>
import { VueDatePicker } from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'
// Import component
    import Loading from 'vue-loading-overlay';
    // Import stylesheet
    import 'vue-loading-overlay/dist/css/index.css';

  export default {
    props:['url','date'],
    components: { VueDatePicker,Loading },
  data () {
    return {
      isLoading: false,
      fullPage: true,
      users:[],
      selected: [],
      name:'',
      description:'',
      joining_date:this.date,
      duration:'',
      class_link:'',
      standardLink_id:'',
      subject_id:'',
      standardlist:[],
      subjectlist:[],
      selectedUsersCount:0,
      allSelected: false,
      noneSelected:false,
      errors:[],
      success:null,
    }
  },

  methods: 
  {
    getMember()
    {
      axios.get('/teacher/assignment/add/list').then(response => {
        this.standardlist = response.data.standardLinklist;
        this.subjectlist = response.data.subjectlist;
       // console.log(response.data)
      });
    },

    getStudents()
    {
               axios.post('/teacher/video-conference/student-list',{
                  standard:this.standardLink_id,        
                }).then(response => {
                    //console.log(response);
                    this.isLoading=false;
                    this.users=response.data.data;
                    this.selected=[];
                }).catch(error => {
                   this.isLoading=false;
                });
    },

    onCancel()
    {

    },

    submitForm()
            {
                this.errors = [];
                this.success = null;
                this.isLoading=true;

               
                axios.post('/teacher/video-conference/save',{
                  name:this.name,
                  description:this.description,
                  joining_date:this.joining_date,
                  duration:this.duration,
                  standard:this.standardLink_id,
                  subject:this.subject_id,
                  class_link:this.class_link,
                  students:this.selected,
                    
                }).then(response => {
                    this.success = response.data.success;
                    this.isLoading=false;
                    window.location.href = "/teacher/video-conference";
                }).catch(error => {
                  this.isLoading=false;
                  this.errors = error.response.data.errors;
                });
            },

      selectAll(e) 
      { 
        var selected = [];
        if (e.target.checked) 
        {
          if(this.allSelected == false) 
          {
            this.users.forEach(function (user) 
            {
              selected.push(user.id);
              $('#'+user.id).addClass('student_selected');
            });
            this.selected = selected;
            this.selectedUsersCount = selected.length;
            this.allSelected = true;
          }
        }
        else
        {
          this.users.forEach(function (user) 
          {
            selected.splice(user.id);
            $('#'+user.id).removeClass('student_selected');
          });
          this.selected = selected;
          this.selectedUsersCount = selected.length;
          this.noneSelected = false;
        }
      },

      selectNone(e) 
      { 
        var selected = [];
        if (e.target.checked) 
        {
          this.users.forEach(function (user) 
          {
            selected.splice(user.id);
             $('#'+user.id).removeClass('student_selected');
          });
          this.selected = selected;
          this.selectedUsersCount = selected.length;
          this.noneSelected = false;
        }
      },
      
       selectedCount(id,e) 
      { 
        if (e.target.checked) 
        {
          this.selectedUsersCount++;
          $('#'+id).addClass('student_selected');
        }
        else
        {
          this.selectedUsersCount--;
          $('#'+id).removeClass('student_selected');
        }
      },
  },

  created()
  {
    this.getMember();
  }
}

</script>
<style scoped>
  .myCustomClass {
     margin-top:10px;
     bottom:0px;
     right:0px;
     position: fixed;
     z-index: 40;
}
</style>