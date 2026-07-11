<template>
  <div>
    <ul class="list-reset flex text-xs profile-tab flex-wrap">
      <li class="px-2 mx-3 py-2"  v-bind:class="[{'active' : route_tab === '1'}]" >
        <a href="#" class="text-gray-700 font-medium" @click="setVehicleTab('1')">Vehicle list</a>
      </li>
      <li class="px-2 mx-3 py-2"  v-bind:class="[{'active' : route_tab === '2'}]" >
        <a href="#" class="text-gray-700 font-medium" @click="setVehicleTab('2')">Member list</a>
      </li>
    <!--   <li class="px-2 mx-3 py-2"  v-bind:class="[{'active' : route_tab === '3'}]" >
        <a href="#" class="text-gray-700 font-medium" @click="setVehicleTab('3')">Document</a>
      </li> -->
      

    </ul>
    <vehiclelist :url="this.url" :routeid="this.routeid"></vehiclelist>
    <memberlist :url="this.url" :routeid="this.routeid"></memberlist>
  </div>
</template>

<script>
  import { bus } from "../../../app";
  import vehiclelist from './vehiclelist';
  import memberlist from './memberlist';


  export default {
    props:['url','routeid'],
    data () {
      return {
        route_tab:'1',
      }
    },
    components: {
      vehiclelist,
      memberlist,

    },

    methods:
    {
      setVehicleTab(val)
      {
        this.route_tab=val;
        bus.$emit("dataRouteTab", this.route_tab);
      }
    },

    created()
    {
      bus.$emit("dataRouteTab", this.route_tab);
       
      bus.$on("dataRouteTab", data => {
        if(data!='')
        {
          this.route_tab=data;                   
        }
      });     
    }
  }
</script>