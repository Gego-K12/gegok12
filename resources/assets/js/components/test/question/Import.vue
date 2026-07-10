<template>
  <div class="relative">
    
     <loading v-model:active="isLoading"
        :can-cancel="true"
         :on-cancel="onCancel"
        :is-full-page="fullPage"></loading>
     
     
        
          <a @click="showModal()"  class="no-underline text-white px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center">
            <span class="mx-1 text-sm font-semibold">Import </span>
          </a> 
    
    
       <!--show modal  -->
     <div v-if="this.show == 1" class="modal modal-mask">
      <div class="modal-wrapper px-4">
        <div class="modal-container w-full  max-w-md px-8 mx-auto">
          <div class="modal-header text-black flex justify-between items-center">
            <h2>Question Head</h2>
            <button id="close-button" class="modal-default-button text-2xl py-1" @click="closeModal()">
              &times;
            </button>
          </div>
          <div class="modal-body" >
            <div class="flex">
            <a :href="url+'/test/downloadformat/questions'"  id="sample" class="no-underline text-white px-4 mb-3 flex items-center custom-green py-1">Download Sample Format</a>
            </div>
            <div class="flex flex-col">
                            <div class="w-full lg:w-1/4"> 
                                <label for="chapter_id" class="tw-form-label">Chapter<span class="text-red-500">*</span></label>
                            </div>
                            <div class="my-2 w-full">
                                <select class="text-black tw-form-control w-full" id="chapter_id" v-model="chapter_id" name="chapter_id"  >
                                  <option value="" disabled>Select Chapter</option>
                                 <option value="" v-for="chapter in chapters" v-bind:value="chapter.id">{{chapter.name}}</option>
                                </select>
                                <span v-if="errors.chapter_id" class="text-red-500 text-xs font-semibold">{{ errors.chapter_id[0] }}</span>
                            </div>
              </div>
              <div class="flex flex-col">
                            <div class="w-full lg:w-1/4"> 
                                <label for="head_id" class="tw-form-label">Question Head<span class="text-red-500">*</span></label>
                            </div>
                            <div class="my-2 w-full">
                                <select class=" text-black tw-form-control w-full" id="head_id" v-model="head_id" name="head_id"  >
                                  <option value="" disabled>Select Question Head</option>
                                 <option value="" v-for="head in heads" v-bind:value="head.id">{{head.name}} - {{head.marks}} marks</option>
                                </select>
                                <span v-if="errors.head_id" class="text-red-500 text-xs font-semibold">{{ errors.head_id[0] }}</span>
                            </div>
              </div>
              <div class="flex flex-col">
                            <div class="w-full lg:w-1/4"> 
                                <label for="head_id" class="tw-form-label">File<span class="text-red-500">*</span></label>
                            </div>
                            <div class="my-2 w-full">
                               <input class="text-black tw-form-control w-full" type="file" name="" @change="OnFileSelected" >
                                <span v-if="errors.import_file" class="text-red-500 text-xs font-semibold">{{ errors.import_file[0] }}</span>
                            </div>
              </div>
               <div class="flex items-center">
                            <div class="mt-4 w-full lg:w-3/4">
                                <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 cursor-pointer text-sm font-medium" @click="importadd()">Import</a>
                            </div>
                </div>
          </div>
         
        </div>
      </div>
    </div>
<!-- End modal -->

  </div>
</template>

<script>

// Import component
    import Loading from 'vue-loading-overlay';
    // Import stylesheet
    import 'vue-loading-overlay/dist/css/index.css';


export default {
  props:['url','subject_id'],
  components: { Loading },
  data () {
    return {
      chapters:[],
      heads:[],
      chapter_id:'',
      head_id:'',
      import_file:'',
      show:0,
      editshow:0,
      errors:[],
      errors1:[],
      success:null,
      isLoading: false,
      fullPage: true,
    }
  },

  methods:{
    getlist()
    {
      axios.get('/test/import/'+this.subject_id+'/list').then(response => {
          this.chapters    = response.data.chapterlist;
          this.heads    = response.data.questionhead;
          //this.total = response.data.meta.total;
          //this.page_count = response.data.meta.last_page;
          //console.log(response);    
        });
    },

    OnFileSelected(event)
    {
      this.import_file = event.target.files[0];
    },

    importadd()
    {
       var formData = new FormData();
        formData.append('chapter_id',this.chapter_id);
        formData.append('head_id',this.head_id);
        formData.append('import_file',this.import_file);

     axios.post(this.url+'/test/questions/import',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
        this.success    = response.data;
        this.closeModal();
        this.getlist();
        this.isLoading=false;
        this.flash(this.success,'success',{timeout: 3000});
        this.reset();
        location.reload();
      }).catch(error=>{
        this.errors=error.response.data.errors;
         this.isLoading=false;
         this.errors = error.response.data.errors;
         this.flash('Something went wrong ☹','error',{timeout: 3000});
      }); 
    },   

    reset()
    {
      this.chapter_id='';
      this.head_id='';
      this.import_file='';
      this.errors=[];
    },
    
    showModal()
    {
      this.show = 1;
      //this.reset();
    },
    
    closeModal()
    {
      this.show = 0;
       this.reset();
    },
    close()
    {
      this.editshow=0;
      this.resetedit();
    },

    onCancel()
    {

    },

  },
  
  created()
  {   
    this.getlist();
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
  margin: 0px auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
  transition: all .3s ease;
 /* height: 550px;*/
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

.text-danger
{
  color:red;
}

.myCustomClass {
     margin-top:10px;
     bottom:0px;
     right:0px;
     position: fixed;
     z-index: 40;
}
</style>
