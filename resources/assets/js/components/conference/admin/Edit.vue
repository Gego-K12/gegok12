<template>
   <div class="w-full">
    <loading v-model:active="isLoading"
        :can-cancel="true"
        :on-cancel="onCancel"
        :is-full-page="fullPage"></loading>
        <div>
            <h1 class="admin-h1 my-3 flex items-center">
                <a :href="this.url+'/admin/video-conference'" title="Back" class="rounded-full bg-gray-100 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" xml:space="preserve" width="512px" height="512px" class="w-3 h-3 fill-current text-gray-700"><g><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124 c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844    L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412 c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008 c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788    C492,219.198,479.172,207.418,464.344,207.418z" data-original="#000000" fill="" class="active-path"></path></g></g></g></svg>
                </a>
                <span class="mx-3">Edit Room</span>
            </h1>
        </div>
        <div class="bg-white shadow px-4 py-3">
           
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

                <!-- <div class="admin-h1 my-3">
                    <div class="">
                        <div class="w-full lg:w-1/4">
                            <label for="name" class="tw-form-label">Title</label>
                        </div>
                        <div class="w-full lg:w-2/5 my-2">
                            <input type="text" name="name" v-model="name" id="name" class="tw-form-control w-full"  placeholder="Title">
                            <span v-if="errors.name"  class="text-danger text-xs">{{ errors.name[0] }}</span>
                        </div>
                    </div>
                </div>
                <div class="my-3">
                    <div class="">
                        <div class="w-full lg:w-1/4">
                            <label for="description" class="tw-form-label">Description</label>
                        </div>
                        <div class="w-full lg:w-2/5 my-2">
                            <textarea type="textarea" name="description" v-model="description" id="description" class="tw-form-control w-full" placeholder="Description"></textarea>
                            <span v-if="errors.description"  class="text-danger text-xs">{{ errors.description[0] }}</span>
                        </div>
                    </div>
                </div>

                <div class="my-5">
       <div class="my-3">
                    <div class="">
            <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
              <label class="tw-form-label">Schedule date</label>
            </div>
            <div class="flex items-center w-full lg:w-1/4 md:w-1/2">
            <div class="mb-2 w-full">
              <datetime format="DD-MM-YYYY h:i:s" name="joining_date" v-model="joining_date" class="rounded w-full" id="joining_date"></datetime>
              <span v-if="errors.joining_date" class="text-red-500 text-xs font-semibold">{{errors.joining_date[0]}}</span>
            </div>
            <div class="mb-2 mx-2">
              <svg id="Capa_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current text-gray-600"><g><path d="m144 249h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"></path><path d="m144 313h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"></path><path d="m144 377h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"></path><path d="m272 249h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"></path><path d="m272 313h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"></path><path d="m272 377h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"></path><path d="m400 249h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"></path><path d="m400 313h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"></path><path d="m400 377h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"></path><path d="m467 65h-36v-25c0-8.284-6.716-15-15-15s-15 6.716-15 15v25h-130v-25c0-8.284-6.716-15-15-15s-15 6.716-15 15v25h-130v-25c0-8.284-6.716-15-15-15s-15 6.716-15 15v25h-36c-24.813 0-45 20.187-45 45v332c0 24.813 20.187 45 45 45h422c24.813 0 45-20.187 45-45 0-9.682 0-323.575 0-332 0-24.813-20.187-45-45-45zm-437 45c0-8.271 6.729-15 15-15h36v25c0 8.284 6.716 15 15 15s15-6.716 15-15v-25h130v25c0 8.284 6.716 15 15 15s15-6.716 15-15v-25h130v25c0 8.284 6.716 15 15 15s15-6.716 15-15v-25h36c8.271 0 15 6.729 15 15v59h-452zm437 347h-422c-8.271 0-15-6.729-15-15v-243h452v243c0 8.271-6.729 15-15 15z"></path></g></svg>
            </div>
            </div>
          </div>
        </div>
      </div>

              <div class="my-3">
                    <div class="">
                        <div class="w-full lg:w-1/4">
                            <label for="duration" class="tw-form-label">Duration(mins)</label>
                        </div>
                        <div class="w-full lg:w-2/5 my-2">
                            <input  type="number" min="0" name="duration" v-model="duration" id="duration" class="tw-form-control w-full" placeholder="Duration">
                            <span v-if="errors.duration"  class="text-danger text-xs">{{ errors.duration[0] }}</span>
                        </div>
                    </div>
                </div>
 -->

               <!-- teacher list -->
                <div class="my-8">
        <div class="w-full flex flex-wrap items-center justify-between mb-4">
          <div class="flex flex-wrap items-center text-sm">
            <div class="px-3 border-r" v-if="this.selectedUsersCount > 0">
              {{ parseInt(this.selectedUsersCount) }} teachers selected
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

       <!--  <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-4 flex flex-wrap">
          <template class="" v-for="user in users">
            <div class="w-full lg:w-full md:w-full my-2 relative p-2">
              <div class="person-card bg-white border rounded flex justify-between relative">
                <div class="flex-grow w-full flex p-2 cursor-pointer hover:shadow" :id="user['id']" >
                  <div class="flex-grow px-2">
                    <h2 class="font-bold text-base text-gray-700">{{user['fullname']}}</h2>
                    <p class="text-sm">{{user['designation_name']}}</p>
                  </div>
                </div>
                <div class="student_select">
                  <input class="absolute w-5 h-5" type="checkbox" v-model="selected" :value="user['id']" @click="selectedCount(user['id'],$event)" >
                  <label></label>
                </div>
              </div>
            </div>
          </template>
        </div> -->
      </div>
                <!--teacher list  -->
                <div class="mt-6 mb-4">
                    <button class="btn btn-primary blue-bg text-white rounded px-3 py-1 text-sm font-medium" id="submit" @click="submitForm()">Submit</button>
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
    props:['url','date','conference_id'],
    components: { VueDatePicker,Loading },
  data () {
    return {
      isLoading: false,
      fullPage: true,
      users:[],
      selected: [],
      conference:[],
      name:'',
      description:'',
      joining_date:this.date,
      duration:'',
      class_link:'',
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
       axios.get('/admin/video-conference/editlist/'+this.conference_id).then(response => {
          this.users = response.data.users;
          this.conference=response.data.conference;
          this.selected=response.data.participant;
          this.name=this.conference.name;
          this.description=this.conference.description;
          this.joining_date=response.data.joining_date;
          this.duration=this.conference.duration;
          this.class_link=this.conference.class_link;
          this.selected.forEach(function (user) 
            {
              $('#'+user).addClass('student_selected');
            });
            this.selectedUsersCount = this.selected.length;
          //console.log(response.data);
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

               
                axios.post('/admin/video-conference/update/'+this.conference_id,{
                  name:this.name,
                  description:this.description,
                  joining_date:this.joining_date,
                  duration:this.duration,
                  class_link:this.class_link,
                  students:this.selected,
                    
                }).then(response => {
                    this.success = response.data.success;
                    this.isLoading=false;
                    window.location.href = "/admin/video-conference";
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