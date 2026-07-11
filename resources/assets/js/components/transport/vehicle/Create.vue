<template>
 
      
            <div class="">
              
<!-- *** heading ***-->
                   <h1 class="admin-h1 my-3 flex items-center">
                   <a :href="url+'/admin/transport/vehicle'" title="Back" class="rounded-full bg-gray-100 p-2">
                   <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" xml:space="preserve" width="512px" height="512px" class="w-3 h-3 fill-current text-gray-700"><g><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124    c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844    L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412    c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008    c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788    C492,219.198,479.172,207.418,464.344,207.418z" data-original="#000000" fill="" class="active-path"></path></g></g></g></svg>
                   </a> 
                   <span class="mx-3">Vehicle</span>
                   </h1>
 <!-- *** heading ***-->
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
                    <label for="" class="tw-form-label"> Vehicle name</label>
                    </div>
                      <input v-model="name" name="name"  value="name" type="text" placeholder="Vehicel name" class="tw-form-control w-full"><span v-if="errors.name" class="text-red-500 text-xs font-semibold">{{errors.name[0]}}</span>
                    </div>
                    </div>
                   <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                     <div class="mb-2">
                    <label for="" class="tw-form-label">Registration date</label>
                    </div>
                      <input v-model="reg_date" name="reg_date"  value="reg_date" type="date" class="tw-form-control w-full"><span v-if="errors.reg_date" class="text-red-500 text-xs font-semibold">{{errors.reg_date[0]}}</span>
                    </div>
                    </div>  
                  </div>
                  <!-- *** -->

                  <!-- *** -->
                   <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                     <div class="mb-2">
                    <label for="" class="tw-form-label">Vehicle Type</label>
                    </div>

         <select name="vehicle_type" id="vehicle_type" v-model="vehicle_type" class="tw-form-control w-full">
                <option value="" disabled>Select category</option>
                <option v-for="vehicletype in vehicletypes" v-bind:value="vehicletype">{{vehicletype}}</option>
              </select>
              <span v-if="errors.vehicle_type" class="text-red-500 text-xs font-semibold">{{errors.vehicle_type[0]}}</span>
                 </div>
                 </div>
              <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                <div class="mb-2">
                    <label for="" class="tw-form-label">Registration Number</label>
                    </div>
                    <input v-model="reg_number" name="reg_number"  value="reg_number" type="text" placeholder="Registration Number" class="tw-form-control w-full">
                     <span v-if="errors.reg_number" class="text-red-500 text-xs font-semibold">{{errors.reg_number[0]}}</span>
             </div>
             </div>
             </div>
            <!-- *** -->

               <!-- *** -->
                <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                     <div class="mb-2">
                    <label for="" class="tw-form-label">Vehicle code</label>
                    </div>
                      <input v-model="vehicle_code" name="vehicle_code"  value="vehicle_code" type="text" placeholder="Unique code" class="tw-form-control w-full"><span v-if="errors.vehicle_code" class="text-red-500 text-xs font-semibold">{{errors.vehicle_code[0]}}</span>
                    </div>
                    </div>

                    <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                      <div class="mb-2">
                    <label for="" class="tw-form-label">Seating Capacity</label>
                    </div>
                      <input v-model="seat" name="seat"  value="seat" type="number" placeholder="seat" class="tw-form-control w-full"><span v-if="errors.seat" class="text-red-500 text-xs font-semibold">{{errors.seat[0]}}</span>
                    </div>
                    </div>
                    </div>
              <!-- *** -->

              <!-- *** -->
               <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                    <div class="mb-2">
                    <label for="" class="tw-form-label">Chassis Number</label>
                    </div>
                      <input v-model="chassis_number" name="chassis_number"  value="chassis_number" type="text" placeholder="Chassis Number" class="tw-form-control w-full"><span v-if="errors.chassis_number" class="text-red-500 text-xs font-semibold">{{errors.chassis_number[0]}}</span>
                    </div>
                   </div>
                    <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                    <div class="mb-2">
                    <label for="" class="tw-form-label">Engine Number</label>
                    </div>
                      <input v-model="engine_number" name="engine_number"  value="Engine Number" type="text" placeholder="Cost Per Quantity" class="tw-form-control w-full"><span v-if="errors.engine_number" class="text-red-500 text-xs font-semibold">{{errors.engine_number[0]}}</span>
                    </div>
                    </div>
                    </div>
                  <!-- *** -->

                   <!-- *** -->
                       <div class="flex flex-col lg:flex-row">
                   <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                     <div class="mb-2">
                    <label for="" class="tw-form-label">Vehicle Number</label>
                    </div>
                       <input v-model="vehicle_number" name="vehicle_number"  value="" type="text" placeholder="Vehicle Number" class="tw-form-control w-full"><span v-if="errors.vehicle_number" class="text-red-500 text-xs font-semibold">{{errors.vehicle_number[0]}}</span>
                    </div>
                    </div>
                    <div class="w-full lg:w-1/2 ">
                    <div class="lg:mr-8 md:mr-8 mb-2 lg:mt-6 flex items-end">
                    <div class="mb-2 w-full">

                        <div class="text-sm flex items-center ">
                        <div class="w-full lg:w-1/6 flex items-center">
                        <input type="radio" id="one" value="Owned" v-model="ownership">
                        <label for="one" class="mx-2">Owned</label>
                        </div>
                        <div class="w-full lg:w-1/6 flex items-center">
                        <input type="radio" id="two" value="Contract" v-model="ownership">
                        <label for="two" class="mx-2">Contract</label>
                        </div>
                        </div>
                        </div>
                    </div>
                    <span v-if="errors.ownership" class="text-red-500 text-xs font-semibold">{{errors.ownership[0]}}</span>
                    </div>
                    </div>

                    <!-- *** -->
            
                     <!-- *** -->
                       <div class="flex flex-col lg:flex-row">
                   <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                     <div class="mb-2">
                    <label for="" class="tw-form-label">Fuel used</label>
                    </div>
                       <select name="fuel_type" id="fuel_type" v-model="fuel_type" class="tw-form-control w-full">
                        <option value="" disabled>Select category</option>
                        <option v-for="fueltype in fueltypes" v-bind:value="fueltype">{{fueltype}}</option>
                      </select>
                       <span v-if="errors.fuel_type" class="text-red-500 text-xs font-semibold">{{errors.fuel_type[0]}}</span>
                    </div>
                    </div>
                    <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                    <div class="mb-2">
                    <label for="" class="tw-form-label">Remarks</label>
                    </div>
                      <textarea v-model="remarks" name="remarks"  value="remarks" type="textarea" placeholder="remarks" class="tw-form-control w-full"></textarea><span v-if="errors.remarks" class="text-red-500 text-xs font-semibold">{{errors.remarks[0]}}</span>
                    </div>
                    </div>
                    </div>

                    <!-- *** -->

                    <!-- *** -->
                    <div class="flex flex-col lg:flex-row">
                <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                  <div class="mb-2">
                    <label for="In use" class="tw-form-label">Availablity</label>
                    </div>
                <input type="checkbox" v-model="select" v-bind:true-value="1" v-bind:false-value="0" >
                <span v-if="errors['availability']" class="text-red-500 text-xs font-semibold">{{errors['availability'][0]}}</span>
             <!-- <div v-show="warrantyshow" class="flex flex-col">
              <div class="my-2 w-1/4 flex">
                <div class="">
                  <label for="obtained_marks" class="tw-form-label">Year</label>
                    <input type="number"  name="" min="0" v-model="year" class="tw-form-control w-full"><br>
                    <span v-if="errors['year']" class="text-red-500 text-xs font-semibold">{{errors['year'][0]}}</span>

                </div>
                 <div class="ml-2">
                  <label for="obtained_marks" class="tw-form-label">Month</label>
                    <input type="number"  name="" min="0" v-model="month" class="tw-form-control w-full"><br>
                    <span v-if="errors['month']" class="text-red-500 text-xs font-semibold">{{errors['month'][0]}}</span>
                 </div>
              </div>
            </div> -->
            </div>
            </div>
                   <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                    <div class="mb-2">
                    <label for="In use" class="tw-form-label">In use</label>
                    </div>
                     <input type="checkbox" v-model="in_use" v-bind:true-value="1" v-bind:false-value="0" name="in_use" class="tw-form-control">
                    </div>
                    </div>
                  </div>
                   <!-- *** -->

                     <div class="flex flex-col w-full lg:w-1/2">
                      <div class="mb-2">
                    <label for="" class="tw-form-label">Specification</label>
                    </div>
                      <div class="flex flex-col">
        <div v-for="(specification,k1) in specifications" :key="k1" class="">
          <div class="flex items-center mb-3">
            <div class="flex items-center w-2/5">
              <input type="text"  v-model="specification.title" placeholder="Tittle" class="tw-form-control w-11/12"><br>
               <span v-if="errors['title'+k1]" class="text-red-500 text-xs font-semibold">{{errors['title'+k1][0]}}</span>
             </div>
             <div class="flex-col w-2/5">
                <input type="text"  v-model="specification.detail" placeholder="Specification" class="tw-form-control  w-11/12 ml-2"> <br>
                 <span v-if="errors['detail'+k1]" class="text-red-500 text-xs font-semibold">{{errors['detail'+k1][0]}}</span>
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
      props:['url','categoryid'],
      

        data () {
      return {
                   name:'',
                   vehicle_type:'',
                   fuel_type:'',
                   reg_number:'',
                   vehicle_code:'',
                   seat:'',
                   reg_date:'',
                   vehicle_number:'',
                   engine_number:'',
                   chassis_number:'',
                   in_use:0,
                   remarks:'',
                   ownership:'',
                  /* ownership_tracking:0,
                   location_tracking:0,
                   maintainence_tracking:0,*/
                   vehicletypes:['Bus','Van','Jeep','Others'],
                   fueltypes:['Petrol','Disel','CNG'],
                   errors:[],
                   success:null,
                   warrantyshow:false,
                   select:0,
                  /* year:0,
                   month:0,*/
                   specifications: [
                      {
                        title:'',
                        detail: '',
                      }
                    ],


      }
  },
  methods:{
               checkForm()
               {
    const formData = new FormData();
    formData.append('name', this.name);
    formData.append('vehicle_type', this.vehicle_type);
    formData.append('fuel_type', this.fuel_type);
    formData.append('reg_number', this.reg_number);
    formData.append('vehicle_code', this.vehicle_code);
    formData.append('seat', this.seat);
    formData.append('reg_date', this.reg_date);
    formData.append('vehicle_number', this.vehicle_number);
    formData.append('engine_number', this.engine_number);
    formData.append('chassis_number', this.chassis_number);
    formData.append('in_use', this.in_use);
    formData.append('ownership', this.ownership);
    formData.append('availability', this.select);
   // formData.append('bill_scan', this.bill_scan);
    formData.append('remarks', this.remarks);
   /* formData.append('ownership_tracking', this.ownership_tracking);
    formData.append('location_tracking', this.location_tracking);
    formData.append('maintainence_tracking', this.maintainence_tracking);
    formData.append('warranty_status', this.select);
    formData.append('month', this.month);
    formData.append('year', this.year);*/
    formData.append('specification', JSON.stringify(this.specifications));
    formData.append('specification_count',this.specifications.length);
     for(let i=0;i<this.specifications.length;i++)
        {
          if(typeof this.specifications[i]['title'] !== "undefined")
          {
            formData.append('title'+i,this.specifications[i]['title']);
          }
          else
          {
            formData.append('title'+i,'');
          }
        }
        for(let i=0;i<this.specifications.length;i++)
        {
          if(typeof this.specifications[i]['detail'] !== "undefined")
          {
            formData.append('detail'+i,this.specifications[i]['detail']);
          }
          else
          {
            formData.append('detail'+i,'');
          }
        }

                this.errors=[];
                 axios.post("/admin/transport/vehicle/store",formData).then(response => {
                    //console.log(response);
                    this.success = response.data.message;
                    this.resetForm();
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
            
            axios.get('/admin/product/category/list').then(response=>{
                  //console.log(response);
                  this.subcategories=response.data;
                 });
          },
         
   
     addspecification(index)
      {
        this.specifications.push({ title:'',detail: ''});
      },

      removespecification(index) 
      {
        this.specifications.splice(index, 1);
      },
},
        created() {
             //this.getData();
        }
    }
</script>