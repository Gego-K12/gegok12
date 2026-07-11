<template>
 
      
            <div class="">
              
<!-- *** heading ***-->
                   <h1 class="admin-h1 my-3 flex items-center">
                   <a :href="url+'/'+mode+'/sales/show'" title="Back" class="rounded-full bg-gray-100 p-2">
                   <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" xml:space="preserve" width="512px" height="512px" class="w-3 h-3 fill-current text-gray-700"><g><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124    c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844    L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412    c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008    c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788    C492,219.198,479.172,207.418,464.344,207.418z" data-original="#000000" fill="" class="active-path"></path></g></g></g></svg>
                   </a> 
                   <span class="mx-3">Sales</span>
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
                    <label for="" class="tw-form-label"> Product</label>
                    </div>
                      <select name="product_id" id="product_id" v-model="product_id"  class="tw-form-control w-full">
                <option value="" disabled>Select Product</option>
                <option v-for="productlist in productlists" v-bind:value="productlist.id">{{productlist.name}}</option>
              </select><span v-if="errors.product_id" class="text-red-500 text-xs font-semibold">{{errors.product_id[0]}}</span>
                    </div>
                    </div>
                  </div>
                  <!-- *** -->

                  <!-- *** -->
                   <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                     <div class="mb-2">
                    <label for="" class="tw-form-label">Class</label>
                    </div>

         <select name="standardLink_id" id="standardLink_id" v-model="standardLink_id"  @change='getstudent()' class="tw-form-control w-full">
                <option value="" disabled>Select class</option>
                <option v-for="classlist in classlists" v-bind:value="classlist.id">{{classlist.standard_section}}</option>
              </select>
              <span v-if="errors.standardLink_id" class="text-red-500 text-xs font-semibold">{{errors.standardLink_id[0]}}</span>
                 </div>
                 </div>
               </div>
                <div class="flex flex-col lg:flex-row">
              <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                <div class="mb-2">
                    <label for="" class="tw-form-label">Student</label>
                    </div>
              <select name="user_id" id="user_id" v-model="user_id" class="tw-form-control w-full">
                <option value="" disabled>Select Student</option>
                <option v-for="student in students" v-bind:value="student.student_id">{{student.student_name}}</option>
              </select>
              <span v-if="errors.user_id" class="text-red-500 text-xs font-semibold">{{errors.user_id[0]}}</span>
             </div>
             </div>
             </div>
            <!-- *** -->

               <!-- *** -->
               <!--  <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                      <div class="mb-2">
                    <label for="" class="tw-form-label">Quantity</label>
                    </div>
                      <input v-model="quantity" name="quantity"  value="quantity" type="number" placeholder="quantity" class="tw-form-control w-full"><span v-if="errors.quantity" class="text-red-500 text-xs font-semibold">{{errors.quantity[0]}}</span>
                    </div>
                    </div>
                    </div> -->
              <!-- *** -->

              <!-- *** -->
               <div class="flex flex-col lg:flex-row">
                   <!--  <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                    <div class="mb-2">
                    <label for="" class="tw-form-label">Selling Price</label>
                    </div>
                      <input v-model="selling_price" name="selling_price"  value="selling_price" type="text" placeholder="Selling Price" class="tw-form-control w-full"><span v-if="errors.selling_price" class="text-red-500 text-xs font-semibold">{{errors.selling_price[0]}}</span>
                    </div>
                    </div> -->
                     <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                     <div class="mb-2">
                    <label for="" class="tw-form-label">Sales date</label>
                    </div>
                      <input v-model="sales_date" name="sales_date"  value="sales_date" type="date" class="tw-form-control w-full"><span v-if="errors.sales_date" class="text-red-500 text-xs font-semibold">{{errors.sales_date[0]}}</span>
                    </div>
                    </div>  
                    </div>
                  <!-- *** -->

                    <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                     <div class="mb-2">
                    <label for="" class="tw-form-label">Payment Status</label>
                    </div>

         <select name="payment_status" id="payment_status" v-model="payment_status"  class="tw-form-control w-full">
                <option value="" disabled>Select Payment Status</option>
                <option value="paid">Paid</option>
                <option value="notpaid">Not Paid</option>
              </select>
              <span v-if="errors.payment_status" class="text-red-500 text-xs font-semibold">{{errors.payment_status[0]}}</span>
                 </div>
                 </div>
               </div>

   <button style="cursor:pointer" type="submit" class="btn btn-submit blue-bg text-white rounded px-3 py-1 my-2 text-sm font-medium" @click.prevent="checkForm()" >Update</button>  
                   
        
                </div>
            </div>
        </div>
 
</template>


<script>
    export default {
      props:['url','salesid','mode'],
      

        data () {
         return {
                   sales:[],
                   product_id:'',
                   user_id:'',
                   standardLink_id:'',
                   quantity:'',
                   sales_date:'',
                   selling_price:'',
                   payment_status:'',
                   classlists:[],
                   productlists:[],
                   students:[],
                   errors:[],
                   success:null,

      }
  },
  methods:{
               checkForm()
               {
                
    const formData = new FormData();
    formData.append('product_id', this.product_id);
    formData.append('user_id', this.user_id);
    formData.append('standardLink_id', this.standardLink_id);
    formData.append('quantity', this.quantity);
    formData.append('sales_date', this.sales_date);
    formData.append('selling_price', this.selling_price);
    formData.append('payment_status', this.payment_status);
                this.errors=[];
                 axios.post(this.url+'/'+this.mode+'/sales/'+this.salesid+'/update',formData).then(response => {
                    //console.log(response);
                    this.success = response.data.message;
                 }).catch(error => {
                   this.errors = error.response.data.errors;
                 });
             },
             
              

               getData()
          {
            
            axios.get(this.url+'/'+this.mode+'/sales/'+this.salesid+'/editshow').then(response=>{
                  //console.log(response);
                   this.sales=response.data.data[0];
                   console.log(this.sales);
                   this.product_id=this.sales.product_id;
                   this.user_id=this.sales.user_id;
                   this.standardLink_id=this.sales.standardLink_id;
                   this.quantity=this.sales.quantity;
                   this.sales_date=this.sales.sales_date;
                   this.selling_price=this.sales.selling_price;
                   this.quantity=this.sales.quantity;
                   this.payment_status=this.sales.payment_status;
                  this.getstudent();
                 });
          },
           getstandard()
          {                        
            axios.get(this.url+'/'+this.mode+'/sales/standard/list').then(response=>{
                 
                  this.classlists=response.data.standardlist;
                  this.productlists=response.data.productlist;
                 });
          },
          getstudent()
          {
            
             axios.get(this.url+'/'+this.mode+'/sales/student/'+this.standardLink_id+'/list').then(response=>{
                  //console.log(response);
                  this.students=response.data.data;
                  //console.log(this.students);
                 });
          },
     
               },
        created() {

             this.getData();
             this.getstandard();

        }
    }
</script>