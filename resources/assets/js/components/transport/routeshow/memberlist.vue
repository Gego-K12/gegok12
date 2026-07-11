<template>
   <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3" v-bind:class="[this.route_tab==2?'block' :'hidden']">
  <div class="relative">
    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}<button  @click="closeMsg()">
              &times;
            </button></div>
    <div class="flex flex-wrap lg:flex-row justify-between items-center">
      <div class="">
        <h1 class="admin-h1">Member List</h1>
      </div>
       <div class="relative flex items-center w-8/12 lg:w-1/4 md:w-1/4 justify-end">
        <div class="flex items-center">
         
      
        </div>
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
              <th class="text-left text-sm px-2 py-2 text-grey-darker">User Name</th>
               <th class="text-left text-sm px-2 py-2 text-grey-darker">Stop</th>
              
              
            </tr>
          </thead>   
          <tbody v-if="this.members != ''" >
            <tr class="border-b" v-for="(member,k1) in members" :key="k=k1+1">
              <td class="py-3 px-2" >
                <p class="font-semibold text-xs"> {{member.user_name}} </p>
              </td>
               <td class="py-3 px-2" >
                <p class="font-semibold text-xs">{{member.stop_name}}</p>
              </td>
            
              
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
  
</div>
</template>

<script>
import { bus } from "../../../app";

export default {
  props:['url','routeid'],
  data () {
    return {
      members:[],
      route_tab:'',
      success:null,
      
    }
  },
  methods:{
    getlist()
    {
       axios.get('/admin/transport/route/'+this.routeid+'/memberslist').then(response=>{
                  console.log(response);
                  this.members=response.data.data;
                  
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
        
  }
}
</script>

