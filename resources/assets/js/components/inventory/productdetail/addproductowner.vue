<template>
  <div class="relative">
        <div v-if="this.show == 1" class="modal modal-mask">
      <div class="modal-wrapper px-4">
        <div class="modal-container w-full  max-w-md px-8 mx-auto">
          <div class="modal-header flex justify-between items-center">
            <h2>Add Ownership</h2>
            <button id="close-button" class="modal-default-button text-2xl py-1" @click="closeModal()">
              &times;
            </button>
          </div>
          <div class="modal-body" >
            <div class="flex items-center">
              <div class="w-full"> 

              <div class="modal-body" >
            <div class="flex items-center">
              <div class="w-full"> 
                <label for="obtained_marks" class="tw-form-label">Owner ID</label>
             
              <div class="my-1 w-full">
                <select name="owner_id"  v-model="owner_id" class="tw-form-control w-full">
                <option value="" disabled>Select member</option>
                <option v-for="owner in owners" v-bind:value="owner.id">{{owner.name}}</option>
              </select><br>
                <span v-if="errors.owner_id" class="text-red-600"><small class="text-red=600">{{errors.owner_id[0]}}</small></span>
              </div>

              <div>
              <label class="tw-form-label">Start date</label>
              <div class="my-1">
                      <input v-model="start_date" name="start_date"  value="start_date" type="date" class="tw-form-control w-full"><span v-if="errors.start_date" class="text-red-500 text-xs font-semibold">{{errors.start_date[0]}}</span>
              </div>
              </div>


              </div></div></div></div></div>
            <div class="flex items-center">

              <div class="my-2 ">
                <input type="button" name=""  value="submit" @click="addproductowner()" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium cursor-pointer">
              </div>
            </div>
          </div>
         
        </div>
      </div>
    </div>
    <!-- Edit topic model -->
       <div v-if="this.editshow == 1" class="modal modal-mask">
      <div class="modal-wrapper px-4">
        <div class="modal-container w-full  max-w-md px-8 mx-auto">
          <div class="modal-header flex justify-between items-center">
            <h2>Edit Product Owners</h2>
            <button id="close-button" class="modal-default-button text-2xl py-1" @click="close()">
              &times;
            </button>
          </div>
          <div class="modal-body" >
            <div class="">
            
              <div class="w-full "> 
                <label for="obtained_marks" class="tw-form-label">Owner Name</label>
             
              <div class="my-1">
                <select name="editowner_id"  v-model="editowner_id" class="tw-form-control w-full">
                <option value="" disabled>Select member</option>
                <option v-for="owner in owners" v-bind:value="owner.id">{{owner.name}}</option>
              </select><br>
                <span v-if="errors1.owner_id" class="text-red-500 text-xs font-semibold">{{errors1.owner_id[0]}}</span>
              </div>
            </div>
            <!-- *** -->
            <!-- *** -->
              <div>
              <label class="w-full tw-form-label">Start date</label>
              <div class="my-1">
                <input v-model="editstart_date" name="editstart_date"  value="editstart_date" type="date" class="tw-form-control w-full"><span v-if="errors1.start_date" class="text-red-600"><small>{{errors1.start_date[0]}}</small></span>
              </div>
               </div>
                <!-- *** -->
                <!-- *** -->
                    <div >
                     <label class="w-full tw-form-label">End date</label>
                     <div class="my-1">
                      <input v-model="editend_date" name="editend_date"  value="editend_date" type="date" class="tw-form-control w-full"><span v-if="errors1.end_date" class="text-red-500 text-xs font-semibold">{{errors1.end_date[0]}}</span>
                      </div>
                    </div>
                <!-- *** -->
          
            
            </div>
            <div class="flex items-center">

              <div class="my-2">
                <input type="button" name=""  value="submit" @click="updateproductowner(edit.id)" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium cursor-pointer">
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
import { bus } from "../../../app";
export default {
  props:['url'],
  data () {
    return {
      
      owners:[],
      edit:[],
      product_unique_code:'',
      owner_id:'',
      start_date:'',
      end_date:'',
      editproduct_unique_code:'',
      editowner_id:'',
      editstart_date:'',
      editend_date:'',
      show:0,
      editshow:0,
      errors:[],
      errors1:[],
      success:null,
     
    }
  },

  methods:{
    getlist(page=1)
    {
       axios.get('/admin/productcode/ownershipmembers').then(response=>{
                  //console.log(response);
                  this.owners=response.data.data;
                  console.log(this.owners);
                 });
    },
    addproductowner()
    {

      this.errors=[];
                 axios.post("/admin/productowner/add",{
                    product_unique_code:this.product_unique_code,
                    owner_id:this.owner_id,
                    start_date:this.start_date,
                    end_date:this.end_date,
                    
                  }).then(response => {
                    //console.log(response);
                    this.success = response.data.message;
                    this.closeModal();
                    this.resetForm();
                    //this.getlist();
                     bus.$emit("addform", response.data.message);
                 }).catch(error => {
                   this.errors = error.response.data.errors;
                 });
    },
    productownerEdit(id)
    {
     this.editshow=1;
      axios.get('/admin/productowner/'+id+'/edit').then(response => {
       // console.log(response);
            this.edit=response.data;
            this.editowner_id=this.edit.owner_id;
            this.editstart_date=this.edit.start_date;
            this.editend_date=this.edit.end_date;
          });   
    },
    updateproductowner(id)
    {
     // alert(id);
      axios.post('/admin/productowner/'+id+'/update',{
        //product_unique_code:this.product_unique_code,
        owner_id:this.editowner_id,
        start_date:this.editstart_date,
        end_date:this.editend_date,
      }).then(response => {
        this.success    = response.data.message;
        this.close();
        this.resetedit();
        //this.getlist();
        bus.$emit("addform", response.data.message);
      }).catch(error=>{
        this.errors1=error.response.data.errors;
      });    
    },

    

    resetForm()
               {
                this.product_unique_code='';
                this.owner_id='';
                this.start_date='';
                this.end_date='';
                this.errors=[];
               },
    resetedit()
    {
      this.editproduct_unique_code='';
      this.editowner_id='';
      this.editstart_date='';
      this.editend_date='';
      this.errors1=[];      
    },
    showModal(codeid)
    {
      
      this.product_unique_code=codeid;
      this.show = 1;
     // this.reset();
    },
    
    closeModal()
    {
      this.show = 0;
      this.resetForm();
    },
    close()
    {
      this.editshow=0;
      this.resetedit();
    },
    closeMsg()
    {
      this.success=null;
    }

   

  },
  
  created()
  {
    this.getlist();

    bus.$on("inventory-add-ownership", code => {
      this.showModal(code);
    });
    bus.$on("inventory-edit-ownership", codeid => {
      this.productownerEdit(codeid);
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