<template>
<div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3" v-bind:class="[this.product_tab==1?'block' :'hidden']">
<div class="w-full flex  ">
  <div class=" w-1/2">
    <ul class="list-reset">
      <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Product:</p>
        <p class="mb-0 w-1/2">{{product.name}}</p>
      </li>
      <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Category:</p>
        <p class="mb-0 w-1/2">{{category.name}}</p>
      </li>
      <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Purchased from:</p>
        <p class="mb-0 w-1/2">{{vendor.name}}</p>
      </li>
      <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Purchased at:</p>
        <p class="mb-0 w-1/2">{{product.purchased_date}}</p>
      </li>
      <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Warranty end_at:</p>
        <p v-if="product.warranty_enddate!=null" class="mb-0 w-1/2">{{product.warranty_enddate}}</p>
        <p v-else="" class="mb-0 w-1/2">No warranty</p>
      </li>
      <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Bill scan:</p>
        <p class="mb-0 w-1/2">{{product.bill_scan}}</p>
      </li>
      <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Depreciation rate:</p>
        <p class="mb-0 w-1/2">{{product.depreciation_rate}}</p>
      </li>
      <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Quantity:</p>
        <p class="mb-0 w-1/2">{{product.quantity}}</p>
      </li>
      <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Cost per quantity:</p>
        <p class="mb-0 w-1/2 font-bold text-lg">{{product.cost_per_quantity}}</p>
      </li>
      <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Remarks:</p>
        <p class="mb-0 w-1/2">{{product.remarks}}</p>
      </li>
    </ul>
  </div>

  <div class="w-1/2">
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
      props:['url','productid'],
      data () {
        return {
          product:[],
          vendor:[],
          category:[],
          specifications:[],
          product_tab:1,
          errors:[],
          success:null,   
        }
      },
      methods:
      {
        
        getData()
          {
            axios.get('/admin/product/'+this.productid+'/editshow').then(response => {
            this.product = response.data;
            this.specifications=JSON.parse(this.product.specification);
            this.category = response.data.category;
            this.vendor = response.data.vendor;
            console.log(this.product) 
            });
          },

       
      },
  
      created()
      {    
        this.getData(); 

        bus.$on("dataProductTab", data => {
          if(data!='')
          {
            this.product_tab=data;                   
          }
        });      
      }
  }
</script>