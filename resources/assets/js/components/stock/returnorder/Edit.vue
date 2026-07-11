<template>
 
      
            <div class="">
              
<!-- *** heading ***-->
                   <h1 class="admin-h1 my-3 flex items-center">
                   <a :href="url+'/'+mode+'/returnorder/show'" title="Back" class="rounded-full bg-gray-100 p-2">
                   <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" xml:space="preserve" width="512px" height="512px" class="w-3 h-3 fill-current text-gray-700"><g><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124    c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844    L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412    c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008    c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788    C492,219.198,479.172,207.418,464.344,207.418z" data-original="#000000" fill="" class="active-path"></path></g></g></g></svg>
                   </a> 
                   <span class="mx-3">Return Order</span>
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
                <!-- <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                      <div class="mb-2">
                    <label for="" class="tw-form-label">Quantity</label>
                    </div>
                      <input v-model="quantity" name="quantity"  value="quantity" type="number" placeholder="quantity" class="tw-form-control w-full"><span v-if="errors.quantity" class="text-red-500 text-xs font-semibold">{{errors.quantity[0]}}</span>
                    </div>
                    </div>

                     <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                      <div class="mb-2">
                    <label for="" class="tw-form-label">Price Per Item</label>
                    </div>
                      <input v-model="price_per_item" name="price_per_item"  value="price_per_item" type="text" placeholder="Price per Item" class="tw-form-control w-full"><span v-if="errors.price_per_item" class="text-red-500 text-xs font-semibold">{{errors.price_per_item[0]}}</span>
                    </div>
                    </div>
                    </div> -->
              <!-- *** -->

                <!-- *** -->
                <div class="flex flex-col lg:flex-row">
                   <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                     <div class="mb-2">
                    <label for="" class="tw-form-label">Return date</label>
                    </div>
                      <input v-model="return_date" name="return_date"  value="return_date" type="date" class="tw-form-control w-full"><span v-if="errors.return_date" class="text-red-500 text-xs font-semibold">{{errors.return_date[0]}}</span>
                    </div>
                    </div>  
                    <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                      <div class="mb-2">
                    <label for="" class="tw-form-label">Reason For Return</label>
                    </div>
                      <textarea v-model="reason_for_return" name="reason_for_return"  value="reason_for_return" type="textarea" placeholder="reason_for_return" class="tw-form-control w-full"></textarea>
                      <span v-if="errors.reason_for_return" class="text-red-500 text-xs font-semibold">{{errors.reason_for_return[0]}}</span>
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
      props:['url','returnorderid','mode'],
      

        data () {
         return {
                   returnorders:[],
                   product_id:'',
                   quantity:'',
                   price_per_item:'',
                   reason_for_return:'',
                   return_date:'',
                   productlists:[],
                   errors:[],
                   success:null,

      }
  },
  methods:{
               checkForm()
               {
                
    const formData = new FormData();
    formData.append('product_id', this.product_id);
    formData.append('quantity', this.quantity);
    formData.append('price_per_item', this.price_per_item);
    formData.append('reason_for_return', this.reason_for_return);
    formData.append('return_date', this.return_date);
                this.errors=[];
                 axios.post(this.url+'/'+this.mode+'/returnorder/'+this.returnorderid+'/update',formData).then(response => {
                    //console.log(response);
                    this.success = response.data.message;
                 }).catch(error => {
                   this.errors = error.response.data.errors;
                 });
             },
             
              

               getData()
          {
            
            axios.get(this.url+'/'+this.mode+'/returnorder/'+this.returnorderid+'/editshow').then(response=>{
                  //console.log(response);
                   this.returnorder=response.data.data[0];
                   //console.log(this.returnorder);
                   this.product_id=this.returnorder.product_id;
                   this.quantity=this.returnorder.quantity;
                   this.price_per_item=this.returnorder.price_per_item;
                   this.reason_for_return=this.returnorder.reason_for_return;
                   this.return_date=this.returnorder.return_date;
                 });

          },
           getstandard()
          {                        
            axios.get(this.url+'/'+this.mode+'/returnorder/standard/list').then(response=>{
                 
                  this.classlists=response.data.standardlist;
                  this.productlists=response.data.productlist;
                 });
          },
     
               },
        created() {

             this.getData();
             this.getstandard();

        }
    }
</script>