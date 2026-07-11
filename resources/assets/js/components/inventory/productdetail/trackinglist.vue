<template>
   <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3" v-bind:class="[this.product_tab==3?'block' :'hidden']">
  <div class="relative">
    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}<button  @click="closeMsg()">
              &times;
            </button></div>
    <div class="flex flex-wrap lg:flex-row justify-between items-center">
      <div class="">
        <h1 class="admin-h1 my-3">Location Product</h1>
      </div>
      <div class="relative flex items-center w-8/12 lg:w-1/4 md:w-1/4 justify-end">
       <!--  <div class="flex items-center">
        
            
          <a  class="no-underline text-white px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center cursor-pointer rounded" @click="showModal()" >
            <span class="mx-1 text-sm font-semibold">Add </span>
           <svg data-v-2a22d6ae="" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 409.6 409.6" xml:space="preserve" class="w-3 h-3 fill-current text-white"><g data-v-2a22d6ae=""><g data-v-2a22d6ae=""><path data-v-2a22d6ae="" d="M392.533,187.733H221.867V17.067C221.867,7.641,214.226,0,204.8,0s-17.067,7.641-17.067,17.067v170.667H17.067
         C7.641,187.733,0,195.374,0,204.8s7.641,17.067,17.067,17.067h170.667v170.667c0,9.426,7.641,17.067,17.067,17.067
         s17.067-7.641,17.067-17.067V221.867h170.667c9.426,0,17.067-7.641,17.067-17.067S401.959,187.733,392.533,187.733z"></path></g></g></svg>
          </a> 
        </div> -->
      </div>
    </div>
    <div class="">
      <div class="flex flex-wrap custom-table my-3 overflow-auto">
        <table class="w-full">
          <thead class="bg-grey-light">
            <tr class="border-b">
              <th class="text-left text-sm px-2 py-2 text-grey-darker" > No </th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker">Location</th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker" > Product Unique code </th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker" > Start Date </th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker" >End Date </th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker" >Remarks </th>
           <!--    
              <th class="text-left text-sm px-2 py-2 text-grey-darker" > Actions </th> -->
              
            </tr>
          </thead>   
          <tbody v-if="this.locationproducts != ''" >
            <tr class="border-b" v-for="(locationproduct,k1) in locationproducts" :key="k=k1+1">
              <td class="py-3 px-2" >
                <p class="font-semibold text-xs">{{k}}</p>
              </td>
              <td class="py-3 px-2" >
                <p class="font-semibold text-xs">{{locationproduct.location.name}}</p>
              </td>
              <td class="py-3 px-2">
                <p class="font-semibold text-xs">{{locationproduct.productcode.code}}
                  
                </p>
              </td>
              <td class="py-3 px-2">
                <p class="font-semibold text-xs">{{locationproduct.start_date}}
                  
                </p>
              </td>
              <td class="py-3 px-2">
                <p class="font-semibold text-xs">{{locationproduct.end_date}}
                  
                </p>
              </td>
              <td class="py-3 px-2">
                <p class="font-semibold text-xs">{{locationproduct.remarks}}
                  
                </p>
              </td>
              
             <!--  <td class="py-3 px-2">
                <div class="flex items-center">                
             <a @click="locationproductEdit(locationproduct.id)" class="cursor-pointer" title="Edit">
                 <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 477.873 477.873" xml:space="preserve" class="w-4 h-4 fill-current text-black-500 mx-1"><g><g><path d="M392.533,238.937c-9.426,0-17.067,7.641-17.067,17.067V426.67c0,9.426-7.641,17.067-17.067,17.067H51.2
    c-9.426,0-17.067-7.641-17.067-17.067V85.337c0-9.426,7.641-17.067,17.067-17.067H256c9.426,0,17.067-7.641,17.067-17.067
    S265.426,34.137,256,34.137H51.2C22.923,34.137,0,57.06,0,85.337V426.67c0,28.277,22.923,51.2,51.2,51.2h307.2
    c28.277,0,51.2-22.923,51.2-51.2V256.003C409.6,246.578,401.959,238.937,392.533,238.937z"></path></g></g> <g><g><path d="M458.742,19.142c-12.254-12.256-28.875-19.14-46.206-19.138c-17.341-0.05-33.979,6.846-46.199,19.149L141.534,243.937
    c-1.865,1.879-3.272,4.163-4.113,6.673l-34.133,102.4c-2.979,8.943,1.856,18.607,10.799,21.585
    c1.735,0.578,3.552,0.873,5.38,0.875c1.832-0.003,3.653-0.297,5.393-0.87l102.4-34.133c2.515-0.84,4.8-2.254,6.673-4.13
    l224.802-224.802C484.25,86.023,484.253,44.657,458.742,19.142z M434.603,87.419L212.736,309.286l-66.287,22.135l22.067-66.202
    L390.468,43.353c12.202-12.178,31.967-12.158,44.145,0.044c5.817,5.829,9.095,13.72,9.12,21.955
    C443.754,73.631,440.467,81.575,434.603,87.419z"></path></g></g></svg>
                  </a>

                  

                  <a href="#" @click="deletelocationproduct(locationproduct.id)" class="cursor-pointer" title="Delete">
                   <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-4 h-4 fill-current text-black-500 mx-1"><g><g><g><polygon points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804       "></polygon> <rect x="235.948" y="175.791" width="40.104" height="237.285"></rect> <polygon points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804       "></polygon> <path d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74
      c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474
      L380.665,471.896z"></path></g></g></g> <g><g><path d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42
    C354.924,14.992,339.932,0,321.504,0z"></path></g></g></svg>
                  </a>
                  
                </div>
              </td> -->
            </tr>
  <paginate
    v-model="page"
    :page-count="this.page_count"
    :page-range="3"
    :margin-pages="1"
    :click-handler="getlist"
    :prev-text="'<'"
    :next-text="'>'"
    :container-class="'pagination'"
    :page-class="'page-item'"
    :prev-link-class="'prev'"
    :next-link-class="'next'">
  </paginate>
          </tbody>
          <tbody v-else="" >
            <tr class="border-b">
              <td colspan="8">
                <p class="font-semibold text-s" style="text-align: center">No Records Found</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
        <div v-if="this.show == 1" class="modal modal-mask">
      <div class="modal-wrapper px-4">
        <div class="modal-container w-full  max-w-md px-8 mx-auto">
          <div class="modal-header flex justify-between items-center">
            <h2>Add Location</h2>
            <button id="close-button" class="modal-default-button text-2xl py-1" @click="closeModal()">
              &times;
            </button>
          </div>
          <div class="modal-body" >
            <div class="flex items-center">
              <div class="w-full"> 
                <label for="obtained_marks" class="tw-form-label">Location</label>
             
              <div class="my-1 w-full">
                
                 <select name="location_id"  v-model="location_id" class="tw-form-control w-full">
                <option value="" disabled>Select location</option>
                <option v-for="location in locations" v-bind:value="location.id">{{location.name}}</option>
              </select><br>
                <span v-if="errors.location_id"  class="text-red-500 text-xs font-semibold">{{errors.location_id[0]}}</span>
              
              </div>

              <div class="modal-body" >
            <div class="flex items-center">
              <div class="w-full"> 
               
              <div>
              <label class="tw-form-label">Start date</label>
              <div class="my-1">
                      <input v-model="start_date" name="start_date"  value="start_date" type="date" class="tw-form-control w-full"><span v-if="errors.start_date" class="text-red-500 text-xs font-semibold">{{errors.start_date[0]}}</span>
              </div>

              <div>
              <label class="tw-form-label">Remarks</label>
              <div class="my-1">
                      <textarea v-model="remarks" name="remarks"  value="remarks" type="textarea" placeholder="remarks" class="tw-form-control w-full"></textarea><span v-if="errors.remarks" class="text-red-500 text-xs font-semibold">{{errors.remarks[0]}}</span>
              </div>

              </div></div>
              </div></div></div></div></div>
            <div class="flex items-center">

              <div class="my-2 ">
                <input type="button" name=""  value="submit" @click="addlocationproduct()" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium cursor-pointer">
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
            <h2>Edit Location Product </h2>
            <button id="close-button" class="modal-default-button text-2xl py-1" @click="close()">
              &times;
            </button>
          </div>
          <div class="modal-body" >
            <div class="">
          
              <div class="w-full "> 
                <label for="obtained_marks" class="tw-form-label">Location</label>
             
              <div class="my-1 w-full">
                 <select name="editlocation_id"  v-model="editlocation_id" class="tw-form-control w-full">
                <option value="" disabled>Select location</option>
                <option v-for="location in locations" v-bind:value="location.id">{{location.name}}</option>
              </select><br>
                <span v-if="errors.location_id" class="text-red-500 text-xs font-semibold">{{errors.location_id[0]}}</span>
               
              </div>
            </div>
       
              <div>
              <label class="w-full tw-form-label">Start date</label>
              <div class="my-1">
                <input v-model="editstart_date" name="editstart_date"  value="editstart_date" type="date" class="tw-form-control w-full"><span v-if="errors1.start_date" class="text-red-500 text-xs font-semibold">{{errors1.start_date[0]}}</span>
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
                <div >
                     <label class="w-full tw-form-label">Remarks</label>
                     <div class="my-1">
                      <textarea v-model="editremarks" name="editremarks"  value="editremarks" type="textarea" placeholder="remarks" class="tw-form-control w-full"></textarea><span v-if="errors.remarks" class="text-red-500 text-xs font-semibold">{{errors.remarks[0]}}</span>
                      </div>
                    </div>



          
            
            </div>
            <div class="flex items-center">

              <div class="my-2">
                <input type="button" name=""  value="submit" @click="updatelocationproduct(edit.id)" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium cursor-pointer">
              </div>
            </div>
          </div>
         
        </div>
      </div>
    </div>
<!-- End modal -->
  </div>
</div>
</template>

<script>
import { bus } from "../../../app";

export default {
  props:['url','productid'],
  data () {
    return {
      locationproducts:[],
      locations:[],
      codes:[],
      edit:[],
      location_id:'',
      product_unique_code:'',
     
      start_date:'',
      end_date:'',
      remarks:'',
      editlocation_id:'',
      editproduct_unique_code:'',
      
      editstart_date:'',
      editend_date:'',
      editremarks:'',
      show:0,
      editshow:0,
      errors:[],
      errors1:[],
      success:null,
      product_tab:'',
      page_count:0,
      page:0,
      total :'',
    }
  },

  methods:{
    getlist(page=1)
    {
       axios.get('/admin/locationproduct/'+this.productid+'/list?page='+this.page).then(response=>{
                  console.log(response);
                  this.locationproducts=response.data.location_product.data;
                  this.page_count = response.data.location_product.last_page;
                  this.total = response.data.location_product.total;
                  //this.locations=response.data.location;
                  //this.codes=response.data.code;
                 });
    },
    addlocationproduct()
    {

      this.errors=[];
                 axios.post("/admin/locationproduct/add",{
                    location_id:this.location_id,
                    product_unique_code:this.productid,
                    start_date:this.start_date,
                    remarks:this.remarks,
                    //end_date:this.end_date,
                    
                  }).then(response => {
                    console.log(response);
                    this.success = response.data.message;
                    this.closeModal();
                    this.resetForm();
                    this.getlist();
                 }).catch(error => {
                   this.errors = error.response.data.errors;
                 });
    },
    locationproductEdit(id)
    {
     this.editshow=1;
      axios.get('/admin/locationproduct/'+id+'/edit').then(response => {
            this.edit=response.data;
            
            this.editlocation_id=this.edit.location_id;
            this.editstart_date=this.edit.start_date;
            this.editend_date=this.edit.end_date;
            this.editremarks=this.edit.remarks;
          });   
    },
    updatelocationproduct(id)
    {
     // alert(id);
      axios.post('/admin/locationproduct/'+id+'/update',{
        
        location_id:this.editlocation_id,
        product_unique_code:this.productid,
        start_date:this.editstart_date,
        end_date:this.editend_date,
        remarks:this.editremarks,
      }).then(response => {
        this.success    = response.data.message;
        this.close();
        this.resetedit();
        this.getlist();
      }).catch(error=>{
        this.errors1=error.response.data.errors;
      });    
    },

    deletelocationproduct(id) 
    {
      var thisswal = this;
      swal({
        title: 'Are you sure',
        text: 'Do you want to delete this category ?',
        icon: "info",
        buttons: [
          'No',
          'Yes'
        ],
        dangerMode: true,
      }).then(function(isConfirm) {
        if (isConfirm) 
        {
          axios.delete('/admin/locationproduct/'+id+'/delete').then(response => {
             thisswal.success    = response.data.message;
             thisswal.getlist();
          });  
        }
        else 
        {
          swal("Cancelled");
        }
      });
    },

    resetForm()
               {
                this.location_id='';
                this.product_unique_code='';                
                this.start_date='';
                this.end_date='';
                this.remarks='';
                this.errors=[];
               },
    resetedit()
    {
      this.editlocation_id='';
      this.editproduct_unique_code='';      
      this.editstart_date='';
      this.editend_date='';
      this.editremarks='';
      this.errors1=[];      
    },
    showModal()
    {
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
     bus.$on("dataProductTab", data => {
          if(data!='')
          {
            this.product_tab=data;                   
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