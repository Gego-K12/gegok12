<template>
 
      
            <div class="">
              

 <!-- *** error message *** -->
                    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
 <!-- *** error message *** -->
 <!-- *** main content *** -->
        
                  <div class="bg-white shadow px-4 py-3">
                  <div class="">
                  <!-- **** -->
                  <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                    <div class="mb-2">
                    <label for="" class="tw-form-label"> Route name</label>
                    </div>
                      <input v-model="name" name="name"  value="name" type="text" placeholder="Route name" class="tw-form-control w-full"><span v-if="errors.name" class="text-red-500 text-xs font-semibold">{{errors.name[0]}}</span>
                    </div>
                    </div>
                  <!--  <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                     <div class="mb-2">
                    <label for="" class="tw-form-label">Vehicle</label>
                    </div>
                     <select name="vehicle_id" id="vehicle_id" v-model="vehicle_id" class="tw-form-control w-full">
                      <option value="" disabled>Select Vehicle</option>
                      <option v-for="vehicle in vehicles" v-bind:value="vehicle.id">{{vehicle.code}}</option>
                     </select>
                      <span v-if="errors.type" class="text-red-500 text-xs font-semibold">{{errors.type[0]}}</span>
                    </div>
                    </div>   -->
                  </div>
                  <!-- *** -->

                  <div class="flex flex-col lg:flex-row">
                <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                  <div class="mb-2">
                    <label for="In use" class="tw-form-label">Status</label>
                    </div>
                <input type="checkbox" v-model="select" v-bind:true-value="1" v-bind:false-value="0" >
                <span v-if="errors['status']" class="text-red-500 text-xs font-semibold">{{errors['status'][0]}}</span>
            </div>
             </div>
            </div>


                  <!--  -->


                     <div class="flex flex-col w-full lg:w-1/2">
                      <div class="mb-2 flex items-center">
                    <label for="" class="tw-form-label w-2/5">Stoppage</label>
                     <label for="" class="tw-form-label w-2/5 lg:ml-2">Pickuptime</label>
                      <label for="" class="tw-form-label w-2/5">Drop time</label>
                       <label class="flex items-center w-3/12 tw-form-label"></label>
                    </div>
                      <div class="flex flex-col">
        <div v-for="(specification,k1) in specifications" :key="k1" class="">
          <div class="flex items-center mb-3">
            <div class="flex-col w-2/5">
               <select name="stoppage_id" id="stoppage_id" v-model="specification.stoppage_id" class="tw-form-control w-full">
                      <option value="" disabled>Select Stoppage</option>
                      <option v-for="stoppage in stoppages" v-bind:value="stoppage.id">{{stoppage.name}}</option>
                     </select>
                     <span v-if="errors['stoppage_id'+k1]" class="text-red-500 text-xs font-semibold">{{errors['stoppage_id'+k1][0]}}</span>
             </div>
             <div class="flex-col w-2/5">
                <input type="time"  v-model="specification.pickup_time" placeholder="Pickup time" class="tw-form-control  w-11/12 ml-2"> <br>
                 <span v-if="errors['pickup_time'+k1]" class="text-red-500 text-xs font-semibold">{{errors['pickup_time'+k1][0]}}</span>
             </div>
              <div class="flex-col w-2/5">
                <input type="time"  v-model="specification.drop_time" placeholder="Drop time" class="tw-form-control  w-11/12 ml-2"> <br>
                 <span v-if="errors['drop_time'+k1]" class="text-red-500 text-xs font-semibold">{{errors['drop_time'+k1][0]}}</span>
             </div>
           <div class="flex items-center px-5 w-3/12">    
              <button  class="add_more px-2 bg-red-600 text-white rounded"  @click.prevent="removespecification(k1)" v-show="k1 || ( !k1 && specifications.length >1)">-</button>
              <button class="add_more bg-teal-500 text-white rounded px-2 mx-2"  @click.prevent="addspecification(k1)" v-show="k1 == specifications.length-1">+</button>
            </div> 
          </div>
        </div>
        </div>
      </div>

                    <!-- **** -->
                  <!-- *** -->

                   <!-- *** -->
                   

         <!--    <div class="flex items-center">
              <div class="w-full lg:w-1/4"> 
              <div class="my-2 w-full lg:w-3/4 ">
            <input type="checkbox" v-model="ownership_tracking" v-bind:true-value="1" v-bind:false-value="0" name="ownership_tracking"  ><span class="mx-2 text-sm">Ownership tracking</span>
        </div></div>

            </div>
            <div class="flex items-center">
              <div class="w-full lg:w-1/4"> 
              <div class="my-2 w-full lg:w-3/4 ">
            <input type="checkbox" v-model="location_tracking" v-bind:true-value="1" v-bind:false-value="0" name="location_tracking"  ><span class="mx-2 text-sm">Location tracking</span>
       </div></div>
            </div>
            <div class="flex items-center ">
              <div class="w-full lg:w-1/4"> 
              <div class="my-2 w-full lg:w-3/4 ">
            <input type="checkbox" v-model="maintainence_tracking" v-bind:true-value="1" v-bind:false-value="0" name="maintainence_tracking"><span class="mx-2 text-sm">Maintainence tracking</span>
        </div></div>
            </div> -->
             <!-- *** -->

   <button style="cursor:pointer" type="submit" class="btn btn-submit blue-bg text-white rounded px-3 py-1 my-2 text-sm font-medium" @click.prevent="checkForm()" >Submit</button>  
                   
        
                </div>
            </div>
        </div>
 
</template>

<script>
    export default {
      props:['url','route_id'],
      

        data () {
      return {
                   edit:[],
                   name:'',
                   vehicle_id:'',
                   stoppages:[],
                   vehicles:[],
                   errors:[],
                   success:null,
                   select:0,
                   specification:[],
                   specifications:[],


      }
  },
  methods:{
               checkForm()
               {
    const formData = new FormData();
    formData.append('name', this.name);
     formData.append('vehicle_id', this.vehicle_id);
     formData.append('status', this.select);
    formData.append('specification', JSON.stringify(this.specifications));
    formData.append('specification_count',this.specifications.length);
     for(let i=0;i<this.specifications.length;i++)
        {
          if(typeof this.specifications[i]['stoppage_id'] !== "undefined")
          {
            formData.append('stoppage_id'+i,this.specifications[i]['stoppage_id']);
          }
          else
          {
            formData.append('stoppage_id'+i,'');
          }
        }
        for(let i=0;i<this.specifications.length;i++)
        {
          if(typeof this.specifications[i]['pickup_time'] !== "undefined")
          {
            formData.append('pickup_time'+i,this.specifications[i]['pickup_time']);
          }
          else
          {
            formData.append('pickup_time'+i,'');
          }
        }
        for(let i=0;i<this.specifications.length;i++)
        {
          if(typeof this.specifications[i]['drop_time'] !== "undefined")
          {
            formData.append('drop_time'+i,this.specifications[i]['drop_time']);
          }
          else
          {
            formData.append('drop_time'+i,'');
          }
        }

                this.errors=[];
                 axios.post('/admin/transport/route/'+this.route_id+'/update',formData).then(response => {
                    //console.log(response);
                    this.success = response.data.message;
                   // this.resetForm();
                 }).catch(error => {
                   this.errors = error.response.data.errors;
                 });
             },
               resetForm()
               {
                location.reload();
               },

               getData()
          {
            
            axios.get('/admin/transport/route/getlist').then(response=>{
                  //console.log(response);
                  this.vehicles=response.data.vehicles;
                  this.stoppages=response.data.stoppages;
                 });
          },

           geteditData()
          {
            
            axios.get('/admin/transport/route/'+this.route_id+'/editshow').then(response=>{
                  console.log(response);
                   this.edit=response.data;
                   this.name=this.edit.name;
                   this.select=this.edit.status;
                   this.vehicle_id=this.edit.vehicle_id;
                   this.specification=this.edit.routestoppages;
                 
                   for (var i =0;i<this.specification.length;i++) {
                        this.specifications.push({ stoppage_id:this.specification[i].stop_id,pickup_time:this.specification[i].pickup_time,drop_time:this.specification[i].drop_time});
                      }
                    

                 });
          },
         
   
     addspecification(index)
      {
        this.specifications.push({ stoppage_id:'',pickup_time: '',drop_time:''});
      },

      removespecification(index) 
      {
        this.specifications.splice(index, 1);
      },
},
        created() {
             this.getData();
             this.geteditData();
        }
    }
</script>