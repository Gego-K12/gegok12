<template>
  <div class="relative">
  <!-- add condtion -->
        <div v-if="this.show == 1" class="modal modal-mask">
      <div class="modal-wrapper px-4">
        <div class="modal-container w-full  max-w-md px-8 mx-auto">
          <div class="modal-header flex justify-between items-center">
            <h2>Add Product Condition</h2>
            <button id="close-button" class="modal-default-button text-2xl py-1" @click="closeModal()">
              &times;
            </button>
          </div>
          

              <div class="modal-body" >
            <div class="flex items-center">
              <div class="w-full"> 
      

              <div>
              <label class="tw-form-label">Conditions</label>
              <div class="my-1">
                      <textarea v-model="condition" name="condition"  value="condition" type="textarea" placeholder="condition" class="tw-form-control w-full"></textarea><span v-if="errors.condition" class="text-red-500 text-xs font-semibold">{{errors.condition[0]}}</span>
              </div>
              <div>
              <label class="tw-form-label">Date</label>
              <div class="my-1">
                      <input v-model="date" name="date"  value="date" type="date" class="tw-form-control w-full"><span v-if="errors.date" class="text-red-500 text-xs font-semibold">{{errors.date[0]}}</span>
              </div>

              </div></div>
              </div></div></div>
            <div class="flex items-center">

              <div class="my-2 ">
                <input type="button" name=""  value="submit" @click="addproductcondition()" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium cursor-pointer">
              </div>
            </div>
          </div>
          </div></div>
    <!-- Edit topic model -->
       <div v-if="this.editshow == 1" class="modal modal-mask">
      <div class="modal-wrapper px-4">
        <div class="modal-container w-full  max-w-md px-8 mx-auto">
          <div class="modal-header flex justify-between items-center">
            <h2>Edit Product Condition</h2>
            <button id="close-button" class="modal-default-button text-2xl py-1" @click="close()">
              &times;
            </button>
          </div>
          <div class="modal-body" >
            <div class="">
           
                <div >
                     <label class="w-full tw-form-label">Condition</label>
                     <div class="my-1">
                      <textarea v-model="editcondition" name="editcondition"  value="editcondition" type="textarea" placeholder="condition" class="tw-form-control w-full"></textarea><span v-if="errors1.condition" class="text-red-500 text-xs font-semibold">{{errors1.condition[0]}}</span>
                      </div>
                    </div>

                    <div>
              <label class="w-full tw-form-label">Date</label>
              <div class="my-1">
                <input v-model="editdate" name="editdate"  value="editdate" type="date" class="tw-form-control w-full"><span v-if="errors1.date" class="text-red-500 text-xs font-semibold">{{errors1.date[0]}}</span>
              </div>
               </div>

            </div>
            <div class="flex items-center">

              <div class="my-2">
                <input type="button" name=""  value="submit" @click="updateproductcondition(edit.id)" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium cursor-pointer">
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
      edit:[],
      product_unique_code:'',
      condition:'',
      date:'',      
      editproduct_unique_code:'',
      editcondition:'',
      editdate:'',
      show:0,
      editshow:0,
      product_tab:'',
      errors:[],
      errors1:[],
      success:null,
      page_count:0,
      page:0,
      total :'',
    }
  },

  methods:{
   
    addproductcondition()
    {

      this.errors=[];
                 axios.post("/admin/productcondition/add",{
                    
                    product_unique_code:this.product_unique_code,
                    condition:this.condition,
                    date:this.date,
                   
                  }).then(response => {
                   // console.log(response);
                    this.success = response.data.message;
                    this.closeModal();
                    this.resetForm();
                     bus.$emit("addform", response.data.message);
                 }).catch(error => {
                   this.errors = error.response.data.errors;
                 });
    },
    productconditionEdit(id)
    {
     this.editshow=1;
      axios.get('/admin/productcondition/'+id+'/edit').then(response => {
            this.edit=response.data;
            this.editproduct_unique_code=this.edit.product_unique_code;
            this.editcondition=this.edit.condition;
            this.editdate=this.edit.date;
           });   
    },
    updateproductcondition(id)
    {
     
      axios.post('/admin/productcondition/'+id+'/update',{
        
        condition:this.editcondition,
        date:this.editdate,
      
      }).then(response => {
        this.success    = response.data.message;
        this.close();
        this.resetedit();
         bus.$emit("addform", response.data.message);
      }).catch(error=>{
        this.errors1=error.response.data.errors;
      });    
    },

   
    resetForm()
               {
                
                this.product_unique_code='';                
                this.condition='';
                this.date='';
                this.errors=[];
               },
    resetedit()
    {
      
      this.editproduct_unique_code='';
      this.editcondition='';            
      this.editdate='';
      
      this.errors1=[];      
    },
    showModal(id)
    {
      this.product_unique_code=id;
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
    bus.$on("inventory-add-condition", code => {
      this.showModal(code);
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