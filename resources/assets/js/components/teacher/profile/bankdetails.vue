<template>
  <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto" v-bind:class="[this.profile_tab==7?'block' :'hidden']">
    <div v-if="this.success!=null" class="alert alert-success mt-3" id="success-alert">{{this.success}}</div>
    <div class="flex flex-wrap lg:flex-row justify-between">
      <div class=""></div>
      <div class="flex items-center">
        <div  class="flex items-center">
          <a v-if="Object.keys(lists).length <= 0" href="#" class="no-underline text-white px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center" @click="addModal()">
            <span class="mx-1 text-sm font-semibold">Add</span>
            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 409.6 409.6" xml:space="preserve" class="w-3 h-3 fill-current text-white"><g><g><path d="M392.533,187.733H221.867V17.067C221.867,7.641,214.226,0,204.8,0s-17.067,7.641-17.067,17.067v170.667H17.067 C7.641,187.733,0,195.374,0,204.8s7.641,17.067,17.067,17.067h170.667v170.667c0,9.426,7.641,17.067,17.067,17.067 s17.067-7.641,17.067-17.067V221.867h170.667c9.426,0,17.067-7.641,17.067-17.067S401.959,187.733,392.533,187.733z"></path></g></g></svg>
          </a> 
        </div>
      </div>
    </div> 
    <div class="custom-table mx-3 my-3">
    <table class="w-full overflow-x-auto"> <!-- profiletab-table -->
      <thead>
        <tr>
          <th class="text-white">Bank Name</th>
          <th class="text-white">Account Number</th>
          <th class="text-white">IFSC code</th>
          <th class="text-white">Actions</th>
        </tr>
      </thead>
      <tbody v-if="Object.keys(lists).length > 0">
        <tr >
          <td>
            <p>{{ lists.name }}</p>
          </td>
          <td>
            <p>{{ lists.account_number }}</p>
          </td>
          <td>
            <p>{{ lists.ifsc_code }}</p>
          </td>
          <td>
            <div class="flex items-center">
              <a href="#" @click="editModal(lists.id)">
                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 477.873 477.873" xml:space="preserve" class="w-4 h-4 fill-current text-gray-500 mx-1"><g><g><path d="M392.533,238.937c-9.426,0-17.067,7.641-17.067,17.067V426.67c0,9.426-7.641,17.067-17.067,17.067H51.2 c-9.426,0-17.067-7.641-17.067-17.067V85.337c0-9.426,7.641-17.067,17.067-17.067H256c9.426,0,17.067-7.641,17.067-17.067 S265.426,34.137,256,34.137H51.2C22.923,34.137,0,57.06,0,85.337V426.67c0,28.277,22.923,51.2,51.2,51.2h307.2 c28.277,0,51.2-22.923,51.2-51.2V256.003C409.6,246.578,401.959,238.937,392.533,238.937z"></path></g></g> <g><g><path d="M458.742,19.142c-12.254-12.256-28.875-19.14-46.206-19.138c-17.341-0.05-33.979,6.846-46.199,19.149L141.534,243.937 c-1.865,1.879-3.272,4.163-4.113,6.673l-34.133,102.4c-2.979,8.943,1.856,18.607,10.799,21.585 c1.735,0.578,3.552,0.873,5.38,0.875c1.832-0.003,3.653-0.297,5.393-0.87l102.4-34.133c2.515-0.84,4.8-2.254,6.673-4.13 l224.802-224.802C484.25,86.023,484.253,44.657,458.742,19.142z M434.603,87.419L212.736,309.286l-66.287,22.135l22.067-66.202 L390.468,43.353c12.202-12.178,31.967-12.158,44.145,0.044c5.817,5.829,9.095,13.72,9.12,21.955 C443.754,73.631,440.467,81.575,434.603,87.419z"></path></g></g></svg>
              </a>

             <!--  <a href="#" @click="deleteDoc(lists.id)">
                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-4 h-4 fill-current text-gray-500 mx-1"><g><g><g><polygon points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804"></polygon><rect x="235.948" y="175.791" width="40.104" height="237.285"></rect><polygon points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804"></polygon> <path d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74 c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474 L380.665,471.896z"></path></g></g></g> <g><g><path d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42 C354.924,14.992,339.932,0,321.504,0z"></path></g></g></svg>
              </a> -->
            </div>
          </td>
        </tr>
      </tbody>
      <tbody v-else>
        <tr>
          <td colspan="4">
            <p class="font-semibold text-s" style="text-align: center">No Records Found</p>
          </td>
        </tr>
      </tbody>
    </table>
    </div>
    <div v-if="this.tab == 'add'" class="modal modal-mask">
      <div class="modal-wrapper px-4">
        <div class="modal-container w-full  max-w-md px-8 mx-auto">
          <div class="modal-header flex justify-between items-center">
            <h2>Add Bank Details</h2>
            <button id="close-button" class="modal-default-button text-2xl py-1"  @click="closeModal()">
              &times;
            </button>
          </div>
          <div class="modal-body">
            <div class="flex items-center">
              <div class="w-full lg:w-1/4"> 
                <label for="type" class="tw-form-label">Bank Name</label>
              </div>
              <div class="my-2 w-full lg:w-3/4">
                 <input type="text" name="bank_name" v-model="bank_name" id="bank_name" class="tw-form-control w-full" placeholder="">
                <span v-if="errors.bank_name" class="text-red-500 text-xs font-semibold">{{errors.bank_name[0]}}</span>
              </div>
            </div>
          </div>
          <div class="modal-body">
            <div class="flex items-center">
              <div class="w-full lg:w-1/4"> 
                <label for="type" class="tw-form-label">Key</label>
              </div>
              <div class="my-2 w-full lg:w-3/4">
                 <input type="text" name="key" v-model="key" id="key" class="tw-form-control w-full" placeholder="Eg:SBI">
                <span v-if="errors.key" class="text-red-500 text-xs font-semibold">{{errors.key[0]}}</span>
              </div>
            </div>
          </div>
          <div class="modal-body">
            <div class="flex items-center">
              <div class="w-full lg:w-1/4"> 
                <label for="account_number" class="tw-form-label">Account Number</label>
              </div>
              <div class="my-2 w-full lg:w-3/4">
                <input type="text" name="account_number" v-model="account_number" id="account_number" class="tw-form-control w-full" placeholder="Enter account number">
                <span v-if="errors.account_number" class="text-red-500 text-xs font-semibold">{{errors.account_number[0]}}</span>
              </div>
            </div>
          </div>
          <div class="modal-body">
            <div class="flex items-center">
              <div class="w-full lg:w-1/4"> 
                <label for="ifsc_code" class="tw-form-label">IFSC code</label>
              </div>
              <div class="my-2 w-full lg:w-3/4">
               <input type="text" name="ifsc_code" v-model="ifsc_code" id="ifsc_code" class="tw-form-control w-full" >
                <span v-if="errors.ifsc_code" class="text-red-500 text-xs font-semibold">{{errors.ifsc_code[0]}}</span>
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
      <div v-if="this.tab == 'edit'" class="modal modal-mask">
        <div class="modal-wrapper px-4">
          <div class="modal-container w-full  max-w-md px-8 mx-auto">
            <div class="modal-header flex justify-between items-center">
              <h2>Edit Bank Details</h2>
              <button id="close-button" class="modal-default-button text-2xl py-1"  @click="closeModal()">
                &times;
              </button>
            </div>
              <div class="modal-body">
            <div class="flex items-center">
              <div class="w-full lg:w-1/4"> 
                <label for="type" class="tw-form-label">Bank Name</label>
              </div>
              <div class="my-2 w-full lg:w-3/4">
                 <input type="text" name="bank_name" v-model="bank_name" id="bank_name" class="tw-form-control w-full" placeholder="">
                <span v-if="errors.bank_name" class="text-red-500 text-xs font-semibold">{{errors.bank_name[0]}}</span>
              </div>
            </div>
          </div>
          <div class="modal-body">
            <div class="flex items-center">
              <div class="w-full lg:w-1/4"> 
                <label for="type" class="tw-form-label">Key</label>
              </div>
              <div class="my-2 w-full lg:w-3/4">
                 <input type="text" name="key" v-model="key" id="key" class="tw-form-control w-full" placeholder="Eg:SBI">
                <span v-if="errors.key" class="text-red-500 text-xs font-semibold">{{errors.key[0]}}</span>
              </div>
            </div>
          </div>
          <div class="modal-body">
            <div class="flex items-center">
              <div class="w-full lg:w-1/4"> 
                <label for="account_number" class="tw-form-label">Account Number</label>
              </div>
              <div class="my-2 w-full lg:w-3/4">
                <input type="text" name="account_number" v-model="account_number" id="account_number" class="tw-form-control w-full" placeholder="Enter account number">
                <span v-if="errors.account_number" class="text-red-500 text-xs font-semibold">{{errors.account_number[0]}}</span>
              </div>
            </div>
          </div>
          <div class="modal-body">
            <div class="flex items-center">
              <div class="w-full lg:w-1/4"> 
                <label for="ifsc_code" class="tw-form-label">IFSC code</label>
              </div>
              <div class="my-2 w-full lg:w-3/4">
               <input type="text" name="ifsc_code" v-model="ifsc_code" id="ifsc_code" class="tw-form-control w-full" >
                <span v-if="errors.ifsc_code" class="text-red-500 text-xs font-semibold">{{errors.ifsc_code[0]}}</span>
              </div>
            </div>
          </div>
            <div class="my-6">
              <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="update(edit.id)">Submit</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import { bus } from "../../../app";
  export default {
    props:['url' , 'name'],
    data () {
      return {
        profile_tab:'',
        lists:[],
        edit:[],
        bank_name:'',
        account_number:'',
        ifsc_code:'',
        key:'',
        tab:'',   
        errors:[],
        success:null,  
      }
    },

    methods:
    {
      getData()
      {
        axios.get('/admin/bankdetails/get/'+this.name).then(response => {
          this.lists = response.data;
          console.log(this.lists)
        });
      },

      addModal()
      {
        this.tab = 'add';
      },

      editModal(id)
      {
        this.tab = 'edit';
        axios.get('/admin/bankdetails/edit/'+id).then(response => {
          this.edit=response.data;
          this.bank_name = response.data.name;
          this.account_number = response.data.account_number;
          this.ifsc_code = response.data.ifsc_code;
          this.key = response.data.key;
        });
       // this.tab = id;
      },

      closeModal()
      {
        this.tab = 0;
      },


      submit()
      {
        this.errors=[];
        this.success=null;

        let formData=new FormData(); 

        formData.append('bank_name',this.bank_name); 
        formData.append('account_number',this.account_number); 
        formData.append('ifsc_code',this.ifsc_code);
        formData.append('key',this.key); 

        axios.post('/admin/bankdetails/add/'+this.name,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
          this.success = response.data.success;
          this.closeModal();
          this.reset();
          this.getData();
        }).catch(error => {
          this.errors = error.response.data.errors;
        });
      },

      update(id)
      {
        this.errors=[];
        this.success=null;

        let formData=new FormData(); 

        formData.append('bank_name',this.bank_name); 
        formData.append('account_number',this.account_number); 
        formData.append('ifsc_code',this.ifsc_code);
        formData.append('key',this.key);

        axios.post('/admin/bankdetails/update/'+id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
          this.success = response.data.success;
          this.closeModal();
          this.reset();
          this.getData();
        }).catch(error => {
          this.errors = error.response.data.errors;
        });
      },

      reset(){

          this.bank_name ='';
          this.account_number = '';
          this.ifsc_code = '';
          this.key = '';

      },


      deleteDoc(id) 
      {
        var thisswal = this;
        swal({
          title: 'Are you sure',
          text: 'Do you want to delete this Document ?',
          icon: "info",
          buttons: [
            'No',
            'Yes'
          ],
          dangerMode: true,
        }).then(function(isConfirm) {
          if (isConfirm) 
          {
            axios.delete(thisswal.url+ '/admin/document/delete/'+ id).then(response => {
              thisswal.success = response.data.success;
              window.location.reload();
            }); 
          }
          else 
          {
            swal("Cancelled");
          }
        });
      },
    },

    created()
    {   
      this.getData();  
      bus.on("dataProfileTab", data => {
        if(data!='')
        {
          this.profile_tab=data;                   
        }
      });    
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
</style>