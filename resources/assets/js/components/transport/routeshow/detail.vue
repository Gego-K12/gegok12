<template>
<div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3" v-bind:class="[this.route_tab==1?'block' :'hidden']">
<div class="w-full flex flex-col lg:flex-row md:flex-row ">
  <div class="w-full lg:w-1/2 md:w-1/2">
    <ul class="list-reset">
      <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Vehicle Name:</p>
        <p class="mb-0 w-1/2">{{vehicle.name}}</p>
      </li>
      <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Code:</p>
        <p class="mb-0 w-1/2">{{vehicle.code}}</p>
      </li>
      <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Vehicle Number:</p>
        <p class="mb-0 w-1/2">{{vehicle.vehicle_number}}</p>
      </li>
      <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Chassis Number:</p>
        <p class="mb-0 w-1/2">{{vehicle.chassis_number}}</p>
      </li>
      <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Engine Number:</p>
        <p class="mb-0 w-1/2">{{vehicle.engine_number}}</p>
      </li>
       <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Registration Number:</p>
        <p class="mb-0 w-1/2">{{vehicle.registration_number}}</p>
      </li>
       <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Registration Date:</p>
        <p class="mb-0 w-1/2">{{vehicle.registration_date}}</p>
      </li>
      <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Availablity:</p>
        <p v-if="vehicle.availability==1" class="mb-0 w-1/2">Available</p>
        <p v-else="" class="mb-0 w-1/2">Not Available</p>
      </li>
      <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Status:</p>
        <p v-if="vehicle.status==1" class="mb-0 w-1/2">Active</p>
        <p v-else="" class="mb-0 w-1/2">In active</p>
      </li>
      <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Vehicle type :</p>
        <p class="mb-0 w-1/2">{{vehicle.vehicle_type}}</p>
      </li>
      <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Ownership status:</p>
        <p class="mb-0 w-1/2">{{vehicle.ownership_status}}</p>
      </li>
      <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Seat:</p>
        <p class="mb-0 w-1/2">{{vehicle.seat}}</p>
      </li>
      <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Fuel Type:</p>
        <p class="mb-0 w-1/2 font-bold text-lg">{{vehicle.fuel_type}}</p>
      </li>
      <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Remarks:</p>
        <p class="mb-0 w-1/2">{{vehicle.remarks}}</p>
      </li>
    </ul>
  </div>

  <div class="w-full lg:w-1/2 md:w-1/2">
        <div>
          <h1 class="admin-h1 my-3 flex items-center">
          <span >Spicification</span>
          </h1>
        </div>
      
        <div v-for="specification in specifications" class="flex items-center text-sm py-2">
          <p class="mb-0 w-1/2 tw-form-label ">{{specification.title}}</p>
          <p class="mb-0 w-2">{{specification.detail}}</p>
       </div>
  </div>
</div>
</div>
 
</template>

<script>
import { bus } from "../../../app";
  export default {
      props:['url','vehicleid'],
      data () {
        return {
          vehicle:[],
          specifications:[],
          route_tab:1,
          errors:[],
          success:null,   
        }
      },
      methods:
      {
        
        getData()
          {
            axios.get('/admin/transport/vehicle/'+this.vehicleid+'/editshow').then(response => {
            this.vehicle = response.data;
            this.specifications=JSON.parse(this.vehicle.specification);
            console.log(this.vehicle) 
            });
          },

       
      },
  
      created()
      {    
        this.getData(); 

        bus.$on("dataRouteTab", data => {
          if(data!='')
          {
            this.route_tab=data;                   
          }
        });      
      }
  }
</script>