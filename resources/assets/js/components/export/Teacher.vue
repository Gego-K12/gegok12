<template>
	<div>
    <!--  <div class="dropdown inline-block relative">
    <button class="bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded inline-flex items-center">
      <span class="mr-1">Export</span>
      <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/> </svg>
    </button>
    <ul class="dropdown-menu absolute hidden text-gray-700 pt-1">
      <li class=""><a  v-bind:href="this.url+'/admin/exportTeachers/?'+ this.searchquery" id="export-button" class="rounded-b bg-gray-200 hover:bg-gray-400 py-2 px-4 block whitespace-no-wrap">
           <span class="mx-1 text-sm font-semibold">Export</span>
           </a> </li>
      <li class=""><a @click="customexport()"  id="export-button" class="rounded-b bg-gray-200 hover:bg-gray-400 py-2 px-4 block whitespace-no-wrap">
                        <span class="mx-1 text-sm font-semibold">Custom</span>
                    </a> </li>
    </ul>
  </div> -->
               <div class="">
                    <a @click="customexport()" id="export-button" class="no-underline flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fa-solid fa-file-export w-4 text-center mr-2 text-gray-500"></i> Export
                    </a>
                </div>
      	
        
         <div v-if="this.exporttab == 1" class="modal modal-mask">
            <div class="modal-wrapper px-4">
                <div class="modal-container w-full  max-w-md px-8 mx-auto">
                    <div class="modal-header flex justify-between items-center">
                        <h2>Custom Export</h2>
                        <button id="close-button" class="modal-default-button text-2xl py-1" @click="closeexport()">&times;</button>
                    </div>
                    <div class="modal-body">
                    	<input type='checkbox' @click='checkAll()' v-model='isCheckAll'> Check All<br>
                        <input type="checkbox" id="employee_id" value="employee_id" v-model="checkedNames">
                        <label for="employee_id">Employee Id</label><br>
                        <input type="checkbox" id="designation" value="designation" v-model="checkedNames">
                        <label for="designation">Designation</label><br>
                        <input type="checkbox" id="name" value="name" v-model="checkedNames">
                        <label for="name">Name</label><br>
                        <input type="checkbox" id="email" value="email" v-model="checkedNames">
                        <label for="email">Email</label><br>
                        <input type="checkbox" id="mobile" value="mobile_no" v-model="checkedNames">
                        <label for="mobile">Mobile Number</label><br>
                        <input type="checkbox" id="gender" value="gender" v-model="checkedNames">
                        <label for="gender">Gender</label><br>
                        <input type="checkbox" id="Joining_date" value="Joining_date" v-model="checkedNames">
                        <label for="Joining_date">Joining Date</label><br>
                        <input type="checkbox" id="adhaar" value="adhaar" v-model="checkedNames">
                        <label for="adhaar">Adhaar Number</label><br>
                        <input type="checkbox" id="blood_group" value="blood_group" v-model="checkedNames">
                        <label for="blood_group">Blood Group</label><br>
                        <input type="checkbox" id="date_of_birth" value="date_of_birth" v-model="checkedNames">
                        <label for="date_of_birth">Date_of_birth</label><br>
                        <input type="checkbox" id="address" value="address" v-model="checkedNames">
                        <label for="address">Address</label><br>
                        <input type="checkbox" id="city" value="city" v-model="checkedNames">
                        <label for="city">City</label><br>
                        <input type="checkbox" id="state" value="state" v-model="checkedNames">
                        <label for="state">State</label><br>
                        <input type="checkbox" id="country" value="country" v-model="checkedNames">
                        <label for="country">Country</label><br>
                        <input type="checkbox" id="pincode" value="pincode" v-model="checkedNames">
                        <label for="pincode">Pincode</label><br>
                    </div>
                    <div class="my-6">
                        <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitExport()">Submit</a>
                        <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="resetform()">Reset</a>
                    </div>
                </div>
            </div>
        </div>
        </div>
</template>
<script>


export default {

   props:['url' , 'searchquery' ],


  data(){
    return{
                exporttab:'',
                isCheckAll: false,
                checkedNames:[],
                value:['employee_id','designation','name','email','mobile_no','gender','Joining_date','adhaar','blood_group','date_of_birth','address','city','state','country','pincode',],

    }
  },
        
  methods:
  {
  	        customexport()
            {
              this.exporttab=1;
            },
            closeexport()
            {
              this.exporttab=0;
              this.checkedNames=[];
            },
            checkAll()
            {
              
		      this.isCheckAll = !this.isCheckAll;
		      this.checkedNames = [];
		      if(this.isCheckAll){ // Check all
		        for (var key in this.value) {
		          this.checkedNames.push(this.value[key]);
		        }
		      }
            },
            submitExport()
            {
                axios.post('/admin/teacher/export',{
                  headings:this.checkedNames, 
                }).then(response => {
                  this.success = response.data.message;
                  window.location="/admin/teacher/export?"+this.searchquery;
                  this.checkedNames=[];
                  this.exporttab=0;
                }).catch(error => {
                  this.errors = error.response.data.errors;
                });
            },
   
  },
  created()
  {  
    
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
 max-height: 550px;
  overflow:auto;
}

.modal-header h3 {
  margin-top: 0;
  color: #42b983;
}

.modal-body {
  margin: 10px 0;
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
.dropdown:hover .dropdown-menu {
  display: block;
}
</style>