<template>
   <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3" v-bind:class="[this.route_tab==1?'block' :'hidden']">
  <div class="relative">
    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}<button  @click="closeMsg()">
              &times;
            </button></div>
    <div class="flex flex-wrap lg:flex-row justify-between items-center">
      <div class="">
        <h1 class="admin-h1">Vehicle List</h1>
      </div>
       <div class="relative flex items-center w-8/12 lg:w-1/4 md:w-1/4 justify-end">
        <div class="flex items-center">
        </div>
      </div>
      <div class="relative flex items-center w-8/12 lg:w-1/4 md:w-1/4 justify-end">
      </div>
    </div>
    <div class="">
      <div class="flex flex-wrap custom-table my-3 overflow-auto">
        <table class="w-full">
          <thead class="bg-grey-light">
            <tr class="border-b">
              <th class="text-left text-sm px-2 py-2 text-grey-darker">Name</th>
               <th class="text-left text-sm px-2 py-2 text-grey-darker">Code</th>
             <!--   <th class="text-left text-sm px-2 py-2 text-grey-darker">Action</th> -->
              
            </tr>
          </thead>   
          <tbody v-if="this.vehicles != ''" >
            <tr class="border-b" v-for="(vehicle,k1) in vehicles" :key="k=k1+1">
              <td class="py-3 px-2" >
                 <a :href="url+'/admin/transport/vehicle/'+vehicle.vehicle_id+'/show'" target="_blank" class="cursor-pointer" ><p class="font-semibold text-xs"> {{vehicle.vehicle.name}} </p></a>
              </td>
               <td class="py-3 px-2" >
                <p class="font-semibold text-xs">{{vehicle.vehicle.code}}</p>
              </td>
            
              
            <!--   <td class="py-3 px-2">
          

                  <a href="#" @click="deletevehicle(vehicle.id)" class="cursor-pointer" title="Delete">
                   <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-4 h-4 fill-current text-gray-500 mx-1"><g><g><g><polygon points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804       "></polygon> <rect x="235.948" y="175.791" width="40.104" height="237.285"></rect> <polygon points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804       "></polygon> <path d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74
      c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474
      L380.665,471.896z"></path></g></g></g> <g><g><path d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42
    C354.924,14.992,339.932,0,321.504,0z"></path></g></g></svg>
                  </a>
              </td> -->
            </tr>
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
        
    
  </div>
 <vehicleform :url="url" ></vehicleform>
</div>
</template>

<script>
import { bus } from "../../../app";
import vehicleform from './Addvehicle';

export default {
  props:['url','routeid'],
  data () {
    return {
      vehicles:[],
      route_tab:'1',
      success:null,
      
    }
  },
components: {
     
      vehicleform
     
    },
  methods:{
    getlist()
    {
       axios.get('/admin/transport/route/'+this.routeid+'/vehicle').then(response=>{
                  console.log(response);
                  this.vehicles=response.data;
                  
                 });
    },
    
    deletevehicle(id) 
    {
      var thisswal = this;
      swal({
        title: 'Are you sure',
        text: 'Do you want to delete this Vehicle ?',
        icon: "info",
        buttons: [
          'No',
          'Yes'
        ],
        dangerMode: true,
      }).then(function(isConfirm) {
        if (isConfirm) 
        {
          axios.delete('/admin/transport/route/vehicle/'+id+'/delete').then(response => {
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

   

   

  },
  
  created()
  {   
    this.getlist();
     bus.$on("dataRouteTab", data => {
          if(data!='')
          {
            this.route_tab=data;                   
          }
        });   
         bus.$on("add_vehicle", data => {
        if(data!='')
        {
          this.success=data; 
          this.getlist();                  
        }
      });      
  }
}
</script>

