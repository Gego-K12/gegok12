<template>
  
  <div class="" >
    
    <div class="flex flex-wrap lg:flex-row justify-between">
      <div class=""></div>
      <div class="flex items-center">
        <div  class="flex items-center">
          <a  href="#" class="no-underline text-white px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center" @click="addModal()">
            <span class="mx-1 text-sm font-semibold">Credentials</span>
            
          </a> 
        </div>
      </div>
    </div> 
   
    <div v-if="this.tab == 1" class="modal modal-mask">
      <div class="modal-wrapper px-4">
        <div class="modal-container w-full  max-w-md px-8 mx-auto">
          <div class="modal-header flex justify-between items-center">
            <h2>Change Credentials</h2>
            <button id="close-button" class="modal-default-button text-2xl py-1"  @click="closeModal()">
              &times;
            </button>
          </div>
          <div class="modal-body">
            <div class="flex items-center">
              <div class="w-full lg:w-1/4"> 
                <label for="type" class="tw-form-label">Email</label>
              </div>
              <div class="my-2 w-full lg:w-3/4">
                 <input type="text" name="email" v-model="email" id="email" class="tw-form-control w-full" placeholder="">
                <span v-if="errors.email" class="text-red-500 text-xs font-semibold">{{errors.email[0]}}</span>
              </div>
            </div>
          </div>
          <div class="modal-body">
            <div class="flex items-center">
              <div class="w-full lg:w-1/4"> 
                <label for="mobile_no" class="tw-form-label">Mobile Number</label>
              </div>
              <div class="my-2 w-full lg:w-3/4">
                 <input type="text" name="mobile_no" v-model="mobile_no" id="mobile_no" class="tw-form-control w-full" placeholder="Mobile Number">
                <span v-if="errors.mobile_no" class="text-red-500 text-xs font-semibold">{{errors.mobile_no[0]}}</span>
              </div>
            </div>
          </div>
          
          <div class="my-6">
            <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submit()">Submit</a>
          </div>
        </div>
      </div>
    </div>
    <div >
     </div>
  </div>
</template>

<script>

    // import 'vue-flash-message/dist/vue-flash-message.min.css';
    // import VueFlashMessage from 'vue-flash-message';
    // Vue.use(VueFlashMessage);
  export default {
    props:['url' , 'name'],
    data () {
      return {
        lists:[],
        email:'',
        mobile_no:'',
        user_group:'',
        tab:0,   
        errors:[],
        success:null,  
      }
    },

    methods:
    {
      getData()
      {
        axios.get('/admin/credentials/get/'+this.name).then(response => {
          this.lists = response.data;
          this.email=this.lists.email;
          this.mobile_no=this.lists.mobile_no;
          this.user_group=this.lists.usergroup_id;
          console.log(this.lists)
        });
      },

      addModal()
      {
        this.getData();
        this.tab=1;
      },
      closeModal()
      {
        this.errors=[];
       this.tab=0;
      },

     

      submit()
      {
        this.errors=[];
        this.success=null;

        let formData=new FormData(); 
        formData.append('user_name',this.name); 
        formData.append('email',this.email); 
        formData.append('mobile_no',this.mobile_no); 
        formData.append('user_group',this.user_group); 

        axios.post('/admin/credentials/add/'+this.name,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
          // this.flash(response.data.message,'success',{timeout: 8000});
          this.closeModal();
          this.reset();
          location.reload();
        }).catch(error => {
          this.errors = error.response.data.errors;
        });
      },


      reset(){

          this.email ='';
          this.mobile_no = '';
         

      },


      
    },

    created()
    {   
      this.getData();  
     
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