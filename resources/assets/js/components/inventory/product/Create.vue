<template>
 
      
            <div class="">
              
<!-- *** heading ***-->
                   <h1 class="admin-h1 my-3 flex items-center">
                   <a :href="url+'/admin/product/show'" title="Back" class="rounded-full bg-gray-100 p-2">
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
                    <label for="" class="tw-form-label">Quantity</label>
                    </div>
                      <input v-model="quantity" name="quantity"  value="quantity" type="number" placeholder="quantity" class="tw-form-control w-full"><span v-if="errors.quantity" class="text-red-500 text-xs font-semibold">{{errors.quantity[0]}}</span>
                    </div>
                    </div>
                    </div>
              <!-- *** -->

              <!-- *** -->
               <div class="flex flex-col lg:flex-row">
                    <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                    <div class="mb-2">
                    <label for="" class="tw-form-label">Depreciation Rate</label>
                    </div>
                      <input v-model="depreciation_rate" name="depreciation_rate"  value="depreciation_rate" type="text" placeholder="Depreciation Rate" class="tw-form-control w-full"><span v-if="errors.depreciation_rate" class="text-red-500 text-xs font-semibold">{{errors.depreciation_rate[0]}}</span>
                    </div>
                   </div>
                    <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                    <div class="mb-2">
                    <label for="" class="tw-form-label">Cost Per Quantity</label>
                    </div>
                      <input v-model="cost_per_quantity" name="cost_per_quantity"  value="cost_per_quantity" type="text" placeholder="Cost Per Quantity" class="tw-form-control w-full"><span v-if="errors.cost_per_quantity" class="text-red-500 text-xs font-semibold">{{errors.cost_per_quantity[0]}}</span>
                    </div>
                    </div>
                    </div>
                  <!-- *** -->

                  
            
                     <!-- *** -->
                       <div class="flex flex-col lg:flex-row">
                   <div class="w-full lg:w-1/2">
                    <div class="lg:mr-8 md:mr-8 mb-2">
                     <div class="mb-2">
                    <label for="" class="tw-form-label">Bill scan</label>
                    </div>
                      <input v-model="bill_scan" name="bill_scan"  value="bill_scan" type="text" placeholder="bill scan" class="tw-form-control w-full"><span v-if="errors.bill_scan" class="text-red-500 text-xs font-semibold">{{errors.bill_scan[0]}}</span>
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
                    <label for="In use" class="tw-form-label">Warranty</label>
                    </div>
                <input type="checkbox" v-model="select" v-bind:true-value="1" v-bind:false-value="0" @click="change()">
                <span v-if="errors['warranty_status']" class="text-red-500 text-xs font-semibold">{{errors['warranty_status'][0]}}</span>
             <div v-show="warrantyshow" class="flex flex-col">
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
            </div>
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
        <div v-for="(specification,k1) in specifications" :key="k1" class="">
          <div class="flex items-center">
            <div class="flex-col">
              <input type="text"  v-model="specification.title" class="tw-form-control  ml-2"><br>
               <span v-if="errors['title'+k1]" class="text-red-500 text-xs font-semibold">{{errors['title'+k1][0]}}</span>
             </div>
             <div class="flex-col">
                <input type="text"  v-model="specification.detail" class="tw-form-control w-full ml-2"> <br>
                 <span v-if="errors['detail'+k1]" class="text-red-500 text-xs font-semibold">{{errors['detail'+k1][0]}}</span>
             </div>
           <div>    
              <button  class="add_more px-3 text-3xl"  @click.prevent="removespecification(k1)" v-show="k1 || ( !k1 && specifications.length >1)">-</button>
              <button class="add_more px-3 text-3xl "  @click.prevent="addspecification(k1)" v-show="k1 == specifications.length-1">+</button>
            </div> 
          </div>
        </div>
      </div>

                    <!-- **** -->
                  <!-- *** -->

                   <!-- *** -->
                   

            <div class="flex items-center">
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
            </div>
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
                   category_id:'',
                   vendor_id:'',
                   unique_code_prefix:'',
                   quantity:'',
                   purchased_date:'',
                   cost_per_quantity:'',
                   depreciation_rate:'',
                   in_use:0,
                   bill_scan:'',
                   remarks:'',
                   ownership_tracking:0,
                   location_tracking:0,
                   maintainence_tracking:0,
                   subcategories:[],
                   vendors:[],
                   errors:[],
                   success:null,
                   warrantyshow:false,
                   select:0,
                   year:0,
                   month:0,
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
    formData.append('category_id', this.category_id);
    formData.append('vendor_id', this.vendor_id);
    formData.append('unique_code_prefix', this.unique_code_prefix);
    formData.append('quantity', this.quantity);
    formData.append('purchased_date', this.purchased_date);
    formData.append('cost_per_quantity', this.cost_per_quantity);
    formData.append('depreciation_rate', this.depreciation_rate);
    formData.append('in_use', this.in_use);
    formData.append('bill_scan', this.bill_scan);
    formData.append('remarks', this.remarks);
    formData.append('ownership_tracking', this.ownership_tracking);
    formData.append('location_tracking', this.location_tracking);
    formData.append('maintainence_tracking', this.maintainence_tracking);
    formData.append('warranty_status', this.select);
    formData.append('month', this.month);
    formData.append('year', this.year);
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
                 axios.post("/admin/product/add",formData).then(response => {
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
          getvendor()
          {
            
            axios.get('/admin/product/vendor/'+this.category_id+'/list').then(response=>{
                  //console.log(response);
                  this.vendors=response.data;
                 });
          },
          change()
    {
      if(this.select==0)
      {
        this.warrantyshow=true;
       
      }
      else
      {
        this.month=0;
        this.year=0;
        this.warrantyshow=false;

      }

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
             this.getData();
        }
    }
</script>