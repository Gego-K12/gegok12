<template>
  <div class="bg-white shadow px-3 py-4">
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
            <select class="tw-form-control w-full" v-model="vehicle_id" name="vehicle_id" id="vehicle_id">
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
            <label for="relation" class="tw-form-label">Select Stoppage<span class="text-red-500">*</span></label>
          </div>
          <div class="mb-2">
            <select class="tw-form-control w-full" v-model="stop_id" name="stop_id" id="stop_id">
              <option value="" disabled>Select Stop</option>
              <option v-for="stop in stoplist" v-bind:value="stop.id">{{ stop.stopname }}</option>
            </select>
            <span v-if="errors.stop_id" class="text-red-500 text-xs font-semibold">{{errors.stop_id[0]}}</span>
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
        vehicle_id:'',
        route_id:'',
        stop_id:'',
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


      resetForm()
      {
        this.vehicle_id='';
        this.route_id='';
        this.stop_id='';
      },

      routechange(index)
      {
         //alert(index-1);
         this.vehiclelist=this.routelist[index-1].vehicle;
         this.stoplist=this.routelist[index-1].stoppages;
      } ,

      

      submitForm()
      {
        this.errors=[];
        this.success=null;                 
        let formData=new FormData(); 

        formData.append('ref_name',this.ref_name);
        formData.append('vehicle_id',this.vehicle_id);
        formData.append('route_id',this.route_id);
        formData.append('stop_id',this.stop_id);
       
        axios.post('/admin/transport/detail/add',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {   
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
  }
 }

</script>