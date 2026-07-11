<template>
  <div>
    <ul class="list-reset flex text-xs profile-tab flex-wrap">
      <li class="px-2 mx-3 py-2"  v-bind:class="[{'active' : vehicle_tab === '1'}]" >
        <a href="#" class="text-gray-700 font-medium" @click="setVehicleTab('1')">Vehicle Detail</a>
      </li>
      <li class="px-2 mx-3 py-2"  v-bind:class="[{'active' : vehicle_tab === '2'}]" >
        <a href="#" class="text-gray-700 font-medium" @click="setVehicleTab('2')">Driver list</a>
      </li>
      <li class="px-2 mx-3 py-2"  v-bind:class="[{'active' : vehicle_tab === '3'}]" >
        <a href="#" class="text-gray-700 font-medium" @click="setVehicleTab('3')">Document</a>
      </li>
      

    </ul>
    <detail :url="this.url" :vehicleid="this.vehicleid"></detail>
    <driverlist :url="this.url" :vehicleid="this.vehicleid"></driverlist>
    <document :url="this.url" :vehicleid="this.vehicleid" ></document>
  </div>
</template>

<script>
  import { bus } from "../../../app";
  import detail from './detail';
  import driverlist from './driverlist';
  import document from './documents';
 
  
  export default {
    props:['url','vehicleid'],
    data () {
      return {
        vehicle_tab:'1',     
      }
    },
    components: {
      detail,
      driverlist,
      document,
  
    },

    methods:
    {
      setVehicleTab(val)
      {
        this.vehicle_tab=val;
        bus.$emit("dataVehicleTab", this.vehicle_tab);
      }
    },

    created()
    {
      bus.$emit("dataVehicleTab", this.vehicle_tab);
       
      bus.$on("dataVehicleTab", data => {
        if(data!='')
        {
          this.vehicle_tab=data;                   
        }
      });     
    }
  }
</script>