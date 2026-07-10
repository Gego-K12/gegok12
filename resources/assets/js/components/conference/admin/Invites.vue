<template>
   <div class="w-full">
    <loading v-model:active="isLoading"
        :can-cancel="true"
        :on-cancel="onCancel"
        :is-full-page="fullPage"></loading>
        <div>
            <h1 class="admin-h1 my-3 flex items-center">
                <a :href="this.url+'/admin/video-conference/manage-invites/'+this.conference_id" title="Back" class="rounded-full bg-gray-100 p-2">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" xml:space="preserve" width="512px" height="512px" class="w-3 h-3 fill-current text-gray-700"><g><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124 c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844    L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412 c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008 c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788    C492,219.198,479.172,207.418,464.344,207.418z" data-original="#000000" fill="" class="active-path"></path></g></g></g></svg>
                </a>
                <span class="mx-3">Add Invites</span>
            </h1>
        </div>
        <div class="bg-white shadow px-4 py-3">
           

                
               



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

      </div>
                <!--teacher list  -->
                <div class="mt-6 mb-4">
                    <button class="btn btn-primary blue-bg text-white rounded px-3 py-1 text-sm font-medium" id="submit" @click="submitForm()">Submit</button>
                </div>
            
        </div>
      </div>
</template>
<script>
// Import component
    import Loading from 'vue-loading-overlay';
    // Import stylesheet
    import 'vue-loading-overlay/dist/css/index.css';

  export default {
    props:['url','date','conference_id'],
    components: { Loading },
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
          this.selected=response.data.participant;
          this.selected.forEach(function (user) 
            {
              $('#'+user).addClass('student_selected');
            });
            this.selectedUsersCount = this.selected.length;
         // console.log(response.data);
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

                axios.post('/admin/video-conference/save-invites/'+this.conference_id,{
                  students:this.selected,
                    
                }).then(response => {
                    this.success = response.data.success;
                    this.isLoading=false;
                    window.location.href = "/admin/video-conference/manage-invites/"+this.conference_id;
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