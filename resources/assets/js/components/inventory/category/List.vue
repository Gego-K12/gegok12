<template>
  <div class="relative">
    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
    <div class="flex flex-wrap lg:flex-row justify-between items-center">
      <div class="">
        <h1 class="admin-h1 my-3">Category</h1>
      </div>
      <div class="relative flex items-center w-8/12 lg:w-1/4 md:w-1/4 justify-end">
        <div class="flex items-center">
          <a class="no-underline text-white px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center cursor-pointer rounded" @click="showModal()">
            <span class="mx-1 text-sm font-semibold">Add </span>
            <svg data-v-2a22d6ae="" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 409.6 409.6" xml:space="preserve" class="w-3 h-3 fill-current text-white"><g data-v-2a22d6ae=""><g data-v-2a22d6ae=""><path data-v-2a22d6ae="" d="M392.533,187.733H221.867V17.067C221.867,7.641,214.226,0,204.8,0s-17.067,7.641-17.067,17.067v170.667H17.067 C7.641,187.733,0,195.374,0,204.8s7.641,17.067,17.067,17.067h170.667v170.667c0,9.426,7.641,17.067,17.067,17.067 s17.067-7.641,17.067-17.067V221.867h170.667c9.426,0,17.067-7.641,17.067-17.067S401.959,187.733,392.533,187.733z"></path></g></g></svg>
          </a> 
        </div>
      </div>
    </div>
    <div class="my-3 bg-gray-200  py-1 text-sm">
      <a :href="url+'/admin/dashboard'" class="text-red-500 hover:underline">Home</a>
      <span class="mx-2">/</span>
      <span>Category</span>
    </div>
    <div class="cart-header flex flex-col lg:flex-row md:flex-row">
    <div class="flex-1 py-1">
   <p class="font-bold text-xs uppercase text-grey-darker flex">
   <span class="my-2 mr-2">Show</span>
    <span>
     <div class="relative">
        <select class="block appearance-none w-12  border  text-black px-2 py-2 leading-tight focus:outline-none" id="count" @change="changepage" v-model="per_page">
          <option value="5">5</option>
          <option value="10">10</option>
          <option value="15">15</option>
        </select>
        <div class="pointer-events-none absolute top-0 right-0 bottom-0 flex items-center px-1 text-grey-darker">
          <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
        </div>
      </div>
   </span>
    <span class="my-2 ml-2">Entries</span>
    </p>
    </div>
    <div class="flex-1 justify-start lg:justify-end md:justify-end flex my-3 lg:my-0 md:my-0">
<!-- <form > -->
    <div class="mb-4 flex">
    <span class="absolute my-2 mx-3">
    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
   viewBox="0 0 250.313 250.313" style="color:#ced8e3;" xml:space="preserve" class="w-4 h-4 fill-current mt-1">
<g id="Search">
  <path style="fill-rule:evenodd;clip-rule:evenodd;" d="M244.186,214.604l-54.379-54.378c-0.289-0.289-0.628-0.491-0.93-0.76
    c10.7-16.231,16.945-35.66,16.945-56.554C205.822,46.075,159.747,0,102.911,0S0,46.075,0,102.911
    c0,56.835,46.074,102.911,102.91,102.911c20.895,0,40.323-6.245,56.554-16.945c0.269,0.301,0.47,0.64,0.759,0.929l54.38,54.38
    c8.169,8.168,21.413,8.168,29.583,0C252.354,236.017,252.354,222.773,244.186,214.604z M102.911,170.146
    c-37.134,0-67.236-30.102-67.236-67.235c0-37.134,30.103-67.236,67.236-67.236c37.132,0,67.235,30.103,67.235,67.236
    C170.146,140.044,140.043,170.146,102.911,170.146z"/>
</g></svg>

    </span>
      <input class="border rounded-full w-full py-2 pl-8 pr-3 text-grey-darker leading-tight focus:outline-none" id="username" type="text"  v-on:change="searchevent()"  v-model="searchresult" 
      placeholder="Search...">
    </div>
    <!-- </form> -->
  </div>
    </div>
    <div class="">
      <div class="flex flex-wrap custom-table my-3 overflow-auto">
        <table class="w-full">
          <thead class="bg-grey-light">
            <tr class="border-b">
              <th class="text-left text-sm px-2 py-2 text-grey-darker">No </th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker">Category Name </th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker">Status </th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker">Options </th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker" width="5%">Actions </th>
            </tr>
          </thead>   
          <tbody v-if="this.categories != ''" >
            <tr class="border-b" v-for="(category,k1) in categories" :key="k=k1+1" >
              <td class="py-3 px-2" >
                <p class="font-semibold text-xs">{{k}}</p>
              </td>
              <td class="py-3 px-2" >
                <p class="font-semibold text-xs">{{category.name}}</p>
              </td>
              <td class="py-3 px-2">
                <p v-if="category.status==0" class="font-semibold text-xs">Inactive</p>
                <p v-else="" class="font-semibold text-xs">Active</p>
              </td>
              <td>
                <div class="flex items-center">     
                  <a :href="url+'/admin/category/'+category.id+'/show'" class="underline cursor-pointer" >Sub Category</a><span class="mx-1">{{category.subcount}}</span>
                </div>
             </td>
              <td class="py-3 px-2">
                <div class="flex items-center">                
                  <a @click="categoryEdit(category.id)" class="cursor-pointer" title="Edit">
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 477.873 477.873" xml:space="preserve" class="w-4 h-4 fill-current text-black-500 mx-1"><g><g><path d="M392.533,238.937c-9.426,0-17.067,7.641-17.067,17.067V426.67c0,9.426-7.641,17.067-17.067,17.067H51.2 c-9.426,0-17.067-7.641-17.067-17.067V85.337c0-9.426,7.641-17.067,17.067-17.067H256c9.426,0,17.067-7.641,17.067-17.067 S265.426,34.137,256,34.137H51.2C22.923,34.137,0,57.06,0,85.337V426.67c0,28.277,22.923,51.2,51.2,51.2h307.2 c28.277,0,51.2-22.923,51.2-51.2V256.003C409.6,246.578,401.959,238.937,392.533,238.937z"></path></g></g> <g><g><path d="M458.742,19.142c-12.254-12.256-28.875-19.14-46.206-19.138c-17.341-0.05-33.979,6.846-46.199,19.149L141.534,243.937 c-1.865,1.879-3.272,4.163-4.113,6.673l-34.133,102.4c-2.979,8.943,1.856,18.607,10.799,21.585 c1.735,0.578,3.552,0.873,5.38,0.875c1.832-0.003,3.653-0.297,5.393-0.87l102.4-34.133c2.515-0.84,4.8-2.254,6.673-4.13 l224.802-224.802C484.25,86.023,484.253,44.657,458.742,19.142z M434.603,87.419L212.736,309.286l-66.287,22.135l22.067-66.202 L390.468,43.353c12.202-12.178,31.967-12.158,44.145,0.044c5.817,5.829,9.095,13.72,9.12,21.955 C443.754,73.631,440.467,81.575,434.603,87.419z"></path></g></g></svg>
                  </a>

                  <a href="#" @click="deletecategory(category.id)" class="cursor-pointer" title="Delete">
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-4 h-4 fill-current text-black-500 mx-1"><g><g><g><polygon points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804"></polygon><rect x="235.948" y="175.791" width="40.104" height="237.285"></rect><polygon points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804"></polygon> <path d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74 c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474 L380.665,471.896z"></path></g></g></g><g><g><path d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42 C354.924,14.992,339.932,0,321.504,0z"></path></g></g></svg>
                  </a>
                  <a @click="showsubcat(category.id)" class="cursor-pointer" title="Add SubCategory">
                    <svg class="w-4 h-4 fill-current text-black-500 mx-1" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><g><g><path d="M256,0C114.833,0,0,114.833,0,256s114.833,256,256,256s256-114.853,256-256S397.167,0,256,0z M256,472.341 c-119.275,0-216.341-97.046-216.341-216.341S136.725,39.659,256,39.659S472.341,136.705,472.341,256S375.295,472.341,256,472.341z"/></g></g><g><g><path d="M355.148,234.386H275.83v-79.318c0-10.946-8.864-19.83-19.83-19.83s-19.83,8.884-19.83,19.83v79.318h-79.318 c-10.966,0-19.83,8.884-19.83,19.83s8.864,19.83,19.83,19.83h79.318v79.318c0,10.946,8.864,19.83,19.83,19.83 s19.83-8.884,19.83-19.83v-79.318h79.318c10.966,0,19.83-8.884,19.83-19.83S366.114,234.386,355.148,234.386z"/></g></g></svg>
                  </a>
                </div>
              </td>
            </tr>
            
          </tbody>
          <tbody v-else>
            <tr class="border-b">
              <td colspan="5">
                <p class="font-semibold text-s" style="text-align: center">No Records Found</p>
              </td>
            </tr>
          </tbody>
        </table>
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
      </div>
    </div>
    <div v-if="this.show == 1" class="modal modal-mask">
      <div class="modal-wrapper px-4">
        <div class="modal-container w-full  max-w-md px-8 mx-auto">
          <div class="modal-header flex justify-between items-center">
            <h2>Add Category</h2>
            <button id="close-button" class="modal-default-button text-2xl py-1" @click="closeModal()">
              &times;
            </button>
          </div>
          <div class="modal-body" >
            <div class="flex items-center">
              <div class="w-full"> 
                <label for="name" class="tw-form-label">Name:</label>
             
              <div class="my-2 w-full">
                <input type="text"  name="name" v-model="name" class="tw-form-control w-full"></div>
                <div><span v-if="errors.name" class="text-red-500 text-xs font-semibold">{{errors.name[0]}}</span></div>
              
              <div class="" >
            <div class="flex items-center">
              <div class="w-full lg:w-1/4"> 
              
            <label for="status" class="tw-form-label">Status:</label>
           
<div class="my-2 w-full lg:w-3/4 flex items-center">
            <input type="checkbox" v-model="status" v-bind:true-value="1"  name="status" class="tw-form-control"><span class="mx-2 text-sm">Active</span>

        </div></div>

            </div></div></div></div>
            <div class="flex items-center">

              <div class="my-2 w-full lg:w-3/4 ">
                <input type="button" name=""  value="Submit" @click="addcategory()" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium cursor-pointer">
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
            <h2>Edit Category</h2>
            <button id="close-button" class="modal-default-button text-2xl py-1" @click="close()">
              &times;
            </button>
          </div>
          <div class="modal-body" >
            <div class="">
              <div class="w-full"> 
                <label for="obtained_marks" class="tw-form-label">Category name</label>
              </div>
              <div class="my-2 w-full">
                <input type="text"  name="editname" v-model="editname" class="tw-form-control w-full"><br>
                <span v-if="errors1.name" class="text-red-500 text-xs font-semibold">{{errors1.name[0]}}</span>
              </div>
              <div class="modal-body" >
            <div class="">
              <div class="w-full"> 
              <div class="my-2 w-full ">
            <label for="status" class="tw-form-label">Status:</label>
            <div class="my-2 w-full lg:w-3/4 flex items-center">
            <input type="checkbox" v-model="editstatus" v-bind:true-value="1" v-bind:false-value="0" name="status"  ><span class="mx-2">Active</span>
  </div>
        </div></div>

            </div></div>
            </div>
            <div class="flex items-center">

              <div class="my-2 w-full lg:w-3/4 ">
                <input type="button" name="" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium cursor-pointer" value="submit" @click="updatecategory(edit.id)">
              </div>
            </div>
          </div>
         
        </div>
      </div>
    </div>
  <!-- End modal -->
  <!-- Add subcategory model -->
    <div v-if="this.subshow == 1" class="modal modal-mask">
      <div class="modal-wrapper px-4">
        <div class="modal-container w-full  max-w-md px-8 mx-auto">
          <div class="modal-header flex justify-between items-center">
            <h2>Add SubCategory</h2>
            <button id="close-button" class="modal-default-button text-2xl py-1" @click="subclose()">
              &times;
            </button>
          </div>
          <div class="modal-body" >
            <div class="">
              <div class="w-full"> 
                <label for="obtained_marks" class="tw-form-label">SubCategory name</label>
              </div>
              <div class="my-2 w-full">
                <input type="text"  name="editname" v-model="subname" class="tw-form-control w-full"><br>
                <span v-if="errors.name" class="text-red-500 text-xs font-semibold">{{errors.name[0]}}</span>
              </div>
              <div class="" >
            <div class="">
              <div class="w-full"> 
              <div class="my-2 w-full ">
            <label for="status" class="tw-form-label">Status:</label>
            <div class="my-2 w-full lg:w-3/4 flex items-center">
            <input type="checkbox" v-model="substatus" v-bind:true-value="1" v-bind:false-value="0" name="status"  ><span class="mx-2 text-sm">Active</span>
  </div>
        </div></div>

            </div></div>
            </div>
            <div class="flex items-center">

              <div class="my-2 w-full lg:w-3/4 ">
                <input type="button" name="" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium cursor-pointer" value="submit" @click="addsubcategory(parentid.id)">
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
export default {
  props:['url'],
  data () {
    return {
      page_count:0,
      page:0,
      total :'',
      categories:[],
      edit:[],
      parentid:null,
      name:'',
      status:0,
      editname:'',
      editstatus:0,
      subname:'',
      substatus:0,
      show:0,
      editshow:0,
      subshow:0,
      errors:[],
      errors1:[],
      success:null,
      searchresult:'',
      per_page:5,
    }
  },

  methods:{
    getlist(page=1)
    {
       axios.get('/admin/category/list?page='+this.page+'&perpage='+this.per_page+'&search='+this.searchresult).then(response=>{

                  //console.log(response);
                  this.categories=response.data.data;
                  this.page_count = response.data.meta.last_page;
                  this.total = response.data.meta.total;
                 });
    },
    changepage()
      {
        //alert(this.per_page);
        this.getlist();
      },
      searchevent()
      {
        this.getlist();
      },
    addcategory()
    {

      this.errors=[];
                 axios.post("/admin/category/add",{
                    name:this.name,
                    status:this.status,
                    
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
    addsubcategory()
    {

      this.errors=[];
                 axios.post("/admin/subcategory/add",{
                    name:this.subname,
                    status:this.substatus,
                     parent_id:this.parentid,
                    
                  }).then(response => {
                    console.log(response);
                    this.success = response.data.message;
                    this.subclose();
                    this.resetsubcat();
                    this.getlist();
                 }).catch(error => {
                   this.errors = error.response.data.errors;
                 });
    },

    categoryEdit(id)
    {
     this.editshow=1;
      axios.get('/admin/category/'+id+'/edit').then(response => {
            this.edit=response.data;
            this.editname=this.edit.name;
            this.editstatus=this.edit.status;
          });   
    },
    updatecategory(id)
    {
     // alert(id);
      axios.post('/admin/category/'+id+'/update',{
        name:this.editname,
        status:this.editstatus,
      }).then(response => {
        this.success    = response.data.message;
        this.close();
        this.getlist();
      }).catch(error=>{
        this.errors1=error.response.data.errors;
      });    
    },

    deletecategory(id) 
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
          axios.delete('/admin/category/'+id+'/delete').then(response => {
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
                this.name='';
                this.status=0;
               },
    resetedit()
    {
      this.edittopic='';
      this.errors1=[];      
    },
    resetsubcat()
    {
      this.subname='';
      this.parentid=null;
      this.substatus=0;
      this.errors=[];
    },
    showModal()
    {
      this.show = 1;
     // this.reset();
    },
    showsubcat(categoryid)
    {

      this.parentid=categoryid;
      this.subshow=1;
    },
    
    closeModal()
    {
      this.show = 0;
    },
    close()
    {
      this.editshow=0;
      this.resetedit();
    },
     subclose()
    {
      this.subshow=0;
      this.resetsubcat();
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
</style>