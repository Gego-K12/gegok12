<template>
  <div class="relative">
    <div v-if="this.success!=null" class="alert alert-success mt-2" id="success-alert">{{this.success}}</div>
    <div class="flex flex-wrap lg:flex-row justify-between items-center">
     
      <div class="relative flex items-center  justify-end">
        <div class="flex items-center">
         <a  class="no-underline text-white px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center cursor-pointer" @click="showModal()">
            <span class="mx-1 text-sm font-semibold">Add </span>
            <svg data-v-2a22d6ae="" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 409.6 409.6" xml:space="preserve" class="w-3 h-3 fill-current text-white"><g data-v-2a22d6ae=""><g data-v-2a22d6ae=""><path data-v-2a22d6ae="" d="M392.533,187.733H221.867V17.067C221.867,7.641,214.226,0,204.8,0s-17.067,7.641-17.067,17.067v170.667H17.067 C7.641,187.733,0,195.374,0,204.8s7.641,17.067,17.067,17.067h170.667v170.667c0,9.426,7.641,17.067,17.067,17.067 s17.067-7.641,17.067-17.067V221.867h170.667c9.426,0,17.067-7.641,17.067-17.067S401.959,187.733,392.533,187.733z"></path></g></g></svg>
          </a> 
        </div>
      </div>
    </div>
    


    <div v-if="this.show == 1" class="modal modal-mask">
      <div class="modal-wrapper px-4">
        <div class="modal-container w-full  max-w-md px-8 mx-auto">
          <div class="modal-header flex justify-between items-center">
            <h2 class="text-xl">Add Purchase</h2>
            <button id="close-button" class="modal-default-button text-xl" @click="closeModal()">
              &times;
            </button>
          </div>

          <div class="flex flex-col lg:flex-row">
      <div class="tw-form-group w-full">
        <div class="">
          <div class="mb-1">
            <label for="description" class="tw-form-label">Description</label>
          </div>
          <div class="mb-1">
           <textarea rows="3" class="w-full bg-gray-100 px-2 py-2 text-sm" id="description" v-model="description" name="description" placeholder="What's on your mind.."></textarea>
            <div class="text-gray-700 text-xs my-1" v-text="(500 - description.length)+'/'+500" style="text-align: right"></div>               
          </div>
          <span v-if="errors.description" class="text-red-500 text-xs font-semibold">{{errors.description[0]}}</span>
        </div> 
      </div>
    </div>
 

    <div class="flex flex-col lg:flex-row">
      <div class="tw-form-group w-full">
        <div class="">
          <div class="mb-1">
            <label for="attachment" class="tw-form-label">Attachment</label>
          </div>
          <div class="mb-1">
            <file-pond ref="myFilePond" name="file" allow-multiple :instant-upload="false" @updatefiles="handleAttachmentFiles" />
            <a href="#" class="btn btn-reset reset-btn" @click="removeAllFiles()">Remove All Files</a>
          </div>
          <span v-if="errors.attachment" class="text-red-500 text-xs font-semibold">{{errors.attachment[0]}}</span>
        </div> 
      </div>
    </div>

    <div class="flex flex-col lg:flex-row">
      <div class="tw-form-group w-full">
        <div class="">
          <div class="mb-1">
            <label for="visibility" class="tw-form-label">Visibility<span class="text-red-500">*</span></label>
          </div>
          <div class="mb-1">
            <select class="tw-form-control w-full" id="visibility" v-model="visibility" name="visibility">
              <option value="" disabled>Select Visibility</option>
              <option v-for="visible in visiblelist" v-bind:value="visible.id">{{ visible.name }}</option>
            </select>
          </div>
          <span v-if="errors.visibility" class="text-red-500 text-xs font-semibold">{{errors.visibility[0]}}</span>
        </div> 
      </div>

      <div class="tw-form-group w-full " v-if="this.visibility == 'select_class'">
        <div class="">
          <div class="mb-1">
            <label for="visible_for" class="tw-form-label">Select Class<span class="text-red-500">*</span></label>
          </div>
          <div class="mb-1">
            <select class="tw-form-control w-full" id="visible_for" v-model="visible_for" name="visible_for">
              <option value="" disabled>Select Class</option>
              <option v-for="standard in standardLinkList" v-bind:value="standard.id">{{ standard.standard_section }}</option>
            </select>
          </div>
          <span v-if="errors.visible_for" class="text-red-500 text-xs font-semibold">{{errors.visible_for[0]}}</span>
        </div> 
      </div>
    </div>

    <div class="tw-form-group w-full">
         <div class="">
        <div class="mb-1">
            <label for="tag" class="tw-form-label">Tag Name<span class="text-red-500"></span></label>
          </div>
         <div class="mb-1">
            <input type="text" v-model="tag" name="tag" id="tag" class="tw-form-control w-full">
          </div>
        
          <span v-if="errors.tag" class="text-red-500 text-xs font-semibold">{{errors.tag[0]}}</span>
        </div>
      </div>

    <div class="flex flex-col  w-full lg:w-3/5">
      <div class="tw-form-group w-full lg:w-3/4">
        <div class="flex items-center">
          <div class="w-6">
            <input type="checkbox" v-model="post_later" name="post_later" id="post_later" class="tw-form-control w-full" @click="showDate($event)">
          </div>
          <div class="mx-1">
            <label for="post_later" class="tw-form-label">Post Later</label>
          </div>
          <span v-if="errors.post_later" class="text-red-500 text-xs font-semibold">{{errors.post_later[0]}}</span>
        </div>
      </div>

      <div class="tw-form-group w-full lg:w-3/4">
        <div class="lg:mr-8 md:mr-8 hidden" id="date">
          <div class="mb-2">
            <label for="posted_at" class="tw-form-label">Schedule Date<span class="text-red-500">*</span></label>
          </div>
          <div class="mb-2">
            <VueDatePicker
              v-model="posted_at"
              format="dd-MM-yyyy HH:mm:ss"
              model-type="format"
              :enable-time-picker="true"
              :is-24="true"
              :auto-apply="true"
              input-class-name="w-full rounded"
              id="posted_at"
            />
          </div>
          <span v-if="errors.posted_at" class="text-red-500 text-xs font-semibold">{{errors.posted_at[0]}}</span>
        </div> 
      </div>   
    </div>

    <div class="my-3">
      <a href="#" id="submit" class="btn btn-primary submit-btn" @click="submitForm()">Create</a>
      <a href="#" class="btn btn-reset reset-btn" @click="resetForm()">Reset</a>
    </div>


        </div>
      </div>
    </div>
 
  </div>

  
</template>

<script>
  import { VueDatePicker } from '@vuepic/vue-datepicker'
  import '@vuepic/vue-datepicker/dist/main.css'
  import vueFilePond from 'vue-filepond'
  import 'filepond/dist/filepond.min.css'

  const FilePond = vueFilePond()

  export default {
    components:{
      VueDatePicker,
      FilePond,
    },
    props:['url' , 'entity_id' , 'entity_name' , 'mode'],
    data(){
      return {
        standardLinkList:[],
        attachments:[],

       show:0,
        errors:[],
        success:null,
      }
    },
    methods:
    {
      /*getData()
      {
        axios.get(this.url+'/'+this.mode+'/classwall/post/add/list').then(response => {
          this.standardLinkList = response.data.data;

        });
      },*/

      handleAttachmentFiles(fileItems)
      {
        this.attachments = fileItems.map(item => item.file);
      },

      removeAllFiles()
      {
        this.$refs.myFilePond.removeFiles();
        this.attachments = [];
      },

      showModal()
    {
      this.show = 1;
      this.reset();
    },
    
    closeModal()
    {
      this.show = 0;
     
    },

  

      submitForm()
      {
        this.errors=[];
        this.success=null;

        let formData=new FormData(); 

                
        formData.append('entity_id',this.entity_id);          
        formData.append('entity_name',this.entity_name);        
        formData.append('description',this.description);
        formData.append('visibility',this.visibility);          
        formData.append('visible_for',this.visible_for);          
        formData.append('posted_at',this.posted_at);           
        formData.append('post_later',this.post_later);
        formData.append('tag',this.tag);

        axios.post('/admin/purchase/add',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => { 
          this.post_id = response.data.id;    
          this.init();
          this.success = response.data.success;
          window.location.reload();
        }).catch(error => {
          this.errors = error.response.data.errors;
        });
      },


    },
    
    created()
    {
      /*this.getData();*/
    }
  }
</script>
<style scoped>
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .5);
  display: table;
  transition: opacity .3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
    overflow:auto;
}

.modal-container {
 /* margin: 0px auto;*/
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
  transition: all .3s ease;
  height: 550px;
  overflow:auto;
}

.modal-header h3 {
  margin-top: 0;
  color: #42b983;
}

.modal-body {
  margin: 20px 0;
}

.modal-default-button {
  float: right;
}

/*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

.modal-enter {
  opacity: 0;
}

.modal-leave-active {
  opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}

</style>