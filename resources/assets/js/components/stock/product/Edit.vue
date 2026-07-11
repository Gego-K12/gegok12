<template>
 
      
            <div class="">
              
<!-- *** heading ***-->
                   <h1 class="admin-h1 my-3 flex items-center">
                   <a :href="url+'/'+mode+'/stockproduct/show'" title="Back" class="rounded-full bg-gray-100 p-2">
                   <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" xml:space="preserve" width="512px" height="512px" class="w-3 h-3 fill-current text-gray-700"><g><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124    c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844    L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412    c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008    c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788    C492,219.198,479.172,207.418,464.344,207.418z" data-original="#000000" fill="" class="active-path"></path></g></g></g></svg>
                   </a> 
                   <span class="mx-3">Product</span>
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
                    <label for="" class="tw-form-label"> Product name</label>
                    </div>
                      <input v-model="name" name="name"  value="name" type="text" placeholder="Product Name" class="tw-form-control w-full"><span v-if="errors.name" class="text-red-500 text-xs font-semibold">{{errors.name[0]}}</span>
                    </div>
                    </div>
                   <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                     <div class="mb-2">
                    <label for="" class="tw-form-label">Purchased date</label>
                    </div>
                      <input v-model="purchased_date" name="purchased_date"  value="purchased_date" type="date" class="tw-form-control w-full"><span v-if="errors.purchased_date" class="text-red-500 text-xs font-semibold">{{errors.purchased_date[0]}}</span>
                    </div>
                    </div>  
                  </div>
                  <!-- *** -->

                  <!-- *** -->
                   <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                     <div class="mb-2">
                    <label for="" class="tw-form-label">Category</label>
                    </div>

         <select name="category_id" id="category_id" v-model="category_id"  @change='getvendor()' class="tw-form-control w-full">
                <option value="" disabled>Select category</option>
                <option v-for="subcategory in subcategories" v-bind:value="subcategory.id">{{subcategory.name}}</option>
              </select>
              <span v-if="errors.category_id" class="text-red-500 text-xs font-semibold">{{errors.category_id[0]}}</span>
                 </div>
                 </div>
              <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                <div class="mb-2">
                    <label for="" class="tw-form-label">Purchased From</label>
                    </div>
              <select name="vendor_id" id="vendor_id" v-model="vendor_id" class="tw-form-control w-full">
                <option value="" disabled>Select Vendor</option>
                <option v-for="vendor in vendors" v-bind:value="vendor.vendor_id">{{vendor.vendor['name']}}</option>
              </select>
              <span v-if="errors.vendor_id" class="text-red-500 text-xs font-semibold">{{errors.vendor_id[0]}}</span>
             </div>
             </div>
             </div>
            <!-- *** -->

               <!-- *** -->
                <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                     <div class="mb-2">
                    <label for="" class="tw-form-label">Unique code</label>
                    </div>
                      <input v-model="unique_code_prefix" name="unique_code_prefix"  value="unique_code_prefix" type="text" placeholder="Unique code" class="tw-form-control w-full"><span v-if="errors.unique_code_prefix" class="text-red-500 text-xs font-semibold">{{errors.unique_code_prefix[0]}}</span>
                    </div>
                    </div>

                    <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                     <div class="mb-2">
                    <label for="" class="tw-form-label">Bill scan Number</label>
                    </div>
                      <input v-model="bill_scan" name="bill_scan"  value="bill_scan" type="text" placeholder="bill scan" class="tw-form-control w-full"><span v-if="errors.bill_scan" class="text-red-500 text-xs font-semibold">{{errors.bill_scan[0]}}</span>
                    </div>
                    </div>

                  <!--   <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                      <div class="mb-2">
                    <label for="" class="tw-form-label">Quantity</label>
                    </div>
                      <input v-model="quantity" name="quantity"  value="quantity" type="number" placeholder="quantity" class="tw-form-control w-full"><span v-if="errors.quantity" class="text-red-500 text-xs font-semibold">{{errors.quantity[0]}}</span>
                    </div>
                    </div> -->
                    </div>
              <!-- *** -->

              <!-- *** -->
               <div class="flex flex-col lg:flex-row">
                    <!-- <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                    <div class="mb-2">
                    <label for="" class="tw-form-label">Cost Per Quantity</label>
                    </div>
                      <input v-model="cost_per_quantity" name="cost_per_quantity"  value="cost_per_quantity" type="text" placeholder="Cost Per Quantity" class="tw-form-control w-full"><span v-if="errors.cost_per_quantity" class="text-red-500 text-xs font-semibold">{{errors.cost_per_quantity[0]}}</span>
                    </div>
                    </div> -->
                     
                    </div>
                  <!-- *** -->

                  
            
                     <!-- *** -->
                       <div class="flex flex-col lg:flex-row">
                  
                    <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                    <div class="mb-2">
                    <label for="" class="tw-form-label">Remarks</label>
                    </div>
                      <textarea v-model="remarks" name="remarks"  value="remarks" type="textarea" placeholder="Remarks" class="tw-form-control w-full"></textarea><span v-if="errors.remarks" class="text-red-500 text-xs font-semibold">{{errors.remarks[0]}}</span>
                    </div>
                    </div>
                    </div>

                  <!-- *** -->



   <button style="cursor:pointer" type="submit" class="btn btn-submit blue-bg text-white rounded px-3 py-1 my-2 text-sm font-medium" @click.prevent="checkForm()" >Update</button>  
                   
        
                </div>
            </div>
        </div>
 
</template>

<script>
    export default {
      props:['url','productid','mode'],
      

        data () {
         return {
                   edit:[],
                   name:'',
                   category_id:'',
                   vendor_id:'',
                   unique_code_prefix:'',
                   quantity:'',
                   purchased_date:'',
                   cost_per_quantity:'',
                   bill_scan:'',
                   remarks:'',


                   subcategories:[],
                   vendors:[],
                   errors:[],
                   success:null,
                   warrantyshow:false,
                   select:0,
                   year:0,
                   month:0,
                   warranty_period:[],
                   specification:[],
                   specifications: [],

      }
  },
  methods:{
               checkForm()
               {
                const formData = new FormData();
    formData.append('name', this.name);
    formData.append('category_id', this.category_id);
    formData.append('vendor_id', this.vendor_id);
    formData.append('unique_code_prefix', this.unique_code_prefix);
    formData.append('quantity', this.quantity);
    formData.append('purchased_date', this.purchased_date);
    formData.append('cost_per_quantity', this.cost_per_quantity);

    formData.append('bill_scan', this.bill_scan);
    formData.append('remarks', this.remarks);
   
                this.errors=[];
                 axios.post(this.url+'/'+this.mode+'/stockproduct/'+this.productid+'/update',formData).then(response => {
                    //console.log(response);
                    this.success = response.data.message;
                 }).catch(error => {
                   this.errors = error.response.data.errors;
                 });
             },
             
              

               getData()
          {
            
            axios.get(this.url+'/'+this.mode+'/stockproduct/'+this.productid+'/editshow').then(response=>{
                  //console.log(response);
                   this.edit=response.data;
                   console.log(this.edit);
                   this.product_id=this.edit.id;
                   this.name=this.edit.name;
                   this.category_id=this.edit.category_id;
                   this.vendor_id=this.edit.vendor_id;
                   this.unique_code_prefix=this.edit.unique_code_prefix;
                   this.quantity=this.edit.quantity;
                   this.purchased_date=this.edit.purchased_date;
                   this.cost_per_quantity=this.edit.cost_per_quantity;
                   
                   this.bill_scan=this.edit.bill_scan;
                   this.remarks=this.edit.remarks;
                  

                  this.getvendor();
                 });
          },
           getcategory()
          {
            
            axios.get(this.url+'/'+this.mode+'/stockproduct/category/list').then(response=>{
                  //console.log(response);
                  this.subcategories=response.data;
                 });
          },
          getvendor()
          {
            
            axios.get(this.url+'/'+this.mode+'/stockproduct/vendor/'+this.category_id+'/list').then(response=>{
                  //console.log(response);
                  this.vendors=response.data;
                 });
          },
           
               },
        created() {
             this.getData();
             this.getcategory();

        }
    }
</script>