<template>
  <div class="bg-white shadow px-4 py-3">
    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

    <div class="flex flex-col lg:flex-col">
     
      <div class="tw-form-group w-full lg:w-1/2">
        <div class="lg:mr-8 ">
          <div class="mb-2">
            <label for="relation" class="tw-form-label">Select Route<span class="text-red-500">*</span></label>
          </div>
          <div class="mb-2">
            <select class="tw-form-control w-full" v-model="route_id" name="route_id" id="route_id" @change="routechange($event.target.selectedIndex)">
              <option value="" disabled>Select Route</option>
              <option v-for="(route,index) in routelist" v-bind:value="route.id">{{ route.name }}</option>
            </select>
            <span v-if="errors.route_id" class="text-red-500 text-xs font-semibold">{{errors.route_id[0]}}</span>
          </div> 
        </div>
      </div>

       <div class="tw-form-group w-full lg:w-1/2">
        <div class="lg:mr-8 ">
          <div class="mb-2">
            <label for="relation" class="tw-form-label">Select Vehicle<span class="text-red-500">*</span></label>
          </div>
          <div class="mb-2">
            <select class="tw-form-control w-full" v-model="vehicle_id" name="vehicle_id" id="vehicle_id"  @change="vehiclechange($event.target.selectedIndex)">
              <option value="" disabled>Select Vehicle</option>
              <option v-for="vehicle in vehiclelist" v-bind:value="vehicle.vehicle_id">{{ vehicle.vehicle.code }}</option>
            </select>
            <span v-if="errors.vehicle_id" class="text-red-500 text-xs font-semibold">{{errors.vehicle_id[0]}}</span>
          </div> 
        </div>
      </div>
    </div>

      <div class="flex flex-col lg:flex-row">
         <div class="tw-form-group w-full lg:w-1/2">
        <div class="lg:mr-8 ">
          <div class="mb-2">
            <label for="relation" class="tw-form-label">Driver<span class="text-red-500">*</span></label>
          </div>
          <div class="mb-2">
             <select name="user_id"  v-model="user_id" class="tw-form-control w-full" >
                <option value="">Select driver</option>
                <option v-for="user in users" v-bind:value="user.id">{{user.fullname}}</option>
              </select><br>
                <span v-if="errors.user_id" class="text-red-500 text-xs font-semibold">{{errors.user_id[0]}}</span>
          </div> 
        </div>
      </div>

       
      </div>

     

      <div class="my-6">
        <a href="#" dusk="submit-btn" class="btn btn-primary submit-btn" @click="submitForm()">Submit</a>
        <a href="#" class="btn btn-reset reset-btn" @click="resetForm()">Reset</a>
    </div>
  </div>
</template>

<script> 
import Multiselect from 'vue-multiselect'
export default {
  props:['url' , 'ref_name'],
  components: {
    Multiselect,
  },
   data(){
      return {
        routelist:[],
        vehiclelist:[],
        stoplist:[],
        users:[],
        vehicle_id:'',
        route_id:'',
        user_id:'',
        errors:[],
        success:null,
      }
    },
    methods:
    {
      getData()
      {
        axios.get('/admin/transport/detail/list').then(response => {
          //this.list = response.data;
          this.routelist      = response.data.data;
          console.log(response)
          //this.setData(); 
        });
      },
      getlist()
    {
       axios.get('/admin/transport/route/driverlist').then(response=>{
                  console.log(response);
                  this.users=response.data.data;
                  
                 });
    },


      resetForm()
      {
        this.vehicle_id='';
        this.route_id='';
        this.user_id='';
      },

      routechange(index)
      {
        this.vehicle_id='';
        this.user_id='';
         //alert(index-1);
         this.vehiclelist=this.routelist[index-1].vehicle;
         //this.stoplist=this.routelist[index-1].stoppages;
          //console.log(this.vehiclelist)
          //console.log(this.routelist[index].vehicle)
      } ,

      vehiclechange(index)
      {
        this.user_id='';
         //alert(index-1);
         this.user_id=this.vehiclelist[index-1].vehicle.incharge.user_id;
         //this.stoplist=this.routelist[index-1].stoppages;
          //console.log(this.vehiclelist)
      } ,

      

      submitForm()
      {
        this.errors=[];
        this.success=null;                 
        let formData=new FormData(); 

        //formData.append('ref_name',this.ref_name);
        formData.append('vehicle_id',this.vehicle_id);
        formData.append('route_id',this.route_id);
        formData.append('user_id',this.user_id);
       
        axios.post('/admin/transport/service/add',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {   
          this.success = response.data.success;
          //this.resetForm();
          window.location.reload();
        }).catch(error => {
          this.errors = error.response.data.errors;
        });
      },
  },
    
  created()
  {
    //
    this.getData();
    this.getlist();
  }
 }

</script>