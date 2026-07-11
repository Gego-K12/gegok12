<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-default">

                  <h1 class="admin-h1 my-3 flex items-center">
                   <a :href="url+'/admin/vendor/show'" title="Back" class="rounded-full bg-gray-100 p-2">
                   <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" xml:space="preserve" width="512px" height="512px" class="w-3 h-3 fill-current text-gray-700"><g><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124    c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844    L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412    c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008    c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788    C492,219.198,479.172,207.418,464.344,207.418z" data-original="#000000" fill="" class="active-path"></path></g></g></g></svg>
                   </a>
                   <span class="mx-3">Add Vendor</span>
                   </h1>
                    <div v-if="this.success!=null" class="font-muller alert alert-success" id="success-alert">{{this.success}}</div>
<div class="bg-white shadow px-4 py-3">

        <form   class="my-2" id="change_password">
         <div class="w-full lg:w-1/2">
                   <!-- *** -->

                    <div class="mb-2">
                    <div class="mb-2">
                    <label for="" class="tw-form-label"> Vendor name</label>
                    </div>
                      <input v-model="name" name="name"  value="name" type="text" placeholder="Name" class="tw-form-control w-full"><span v-if="errors.name" class="text-red-500 text-xs font-semibold">{{errors.name[0]}}</span>
                    </div>
                   <!-- *** -->
                    <!-- *** -->
                    <div class="mb-2">
                     <div class="mb-2">
                    <label for="" class="tw-form-label">Email</label>
                    </div>
                      <input v-model="email" name="email"  value="email" type="text" placeholder="email" class="tw-form-control w-full"><span v-if="errors.email" class="text-red-500 text-xs font-semibold">{{errors.email[0]}}</span>
                    </div>
                    <!-- *** -->
                    <!-- *** -->
                    <div class="mb-2">
                     <div class="mb-2">
                    <label for="" class="tw-form-label">Phone</label>
                    </div>
                      <input v-model="phone" name="phone"  value="phone" type="text" placeholder="phone" class="tw-form-control w-full"><span v-if="errors.phone" class="text-red-500 text-xs font-semibold">{{errors.phone[0]}}</span>
                    </div>
                    <!-- *** -->
                     <!-- *** -->
                    <div class="mb-2">
                    <div class="mb-2">
                    <label for="" class="tw-form-label">Address</label>
                    </div>
                      <textarea v-model="address" name="address"  value="address"  placeholder="address" class="tw-form-control w-full"></textarea> <br>
                      <span v-if="errors.address" class="text-red-500 text-xs font-semibold">{{errors.address[0]}}</span>
                    </div>
                    <!-- *** -->
                    <!-- *** -->
                   <div class="mb-2">
                   <div class="mb-2">
                    <label for="" class="tw-form-label">Vendor Status</label>
                    </div>
            <input type="radio" v-model="status" id="option1" name="status" value="0" ><span class="mx-2 text-sm">Inactive</span>
            <input type="radio" v-model="status" id="option2" name="status" value="1"  ><span class="mx-2 text-sm">Active</span>
        </div>
                   <!-- *** -->




                    <button style="cursor:pointer" type="submit" class="btn btn-submit blue-bg text-white rounded px-3 py-1 my-2 text-sm font-medium" @click.prevent="checkForm()" >Submit</button>

                     </div>
        </form>

            </div>
        </div>

    </div>
    </div>
    </div>
</template>

<script>
    export default {
      props:['url'],

        data () {
      return {
                   name:'',
                   email:'',
                   phone:'',
                   address:'',
                   status:'',
                   errors:[],
                   success:null,

      }
  },
  methods:{
               checkForm()
               {
                this.errors=[];
                 axios.post("/admin/vendor/add",{
                    name:this.name,
                    email:this.email,
                    phone:this.phone,
                    address:this.address,
                    status:this.status,

                  }).then(response => {
                    //console.log(response);
                    this.success = response.data.message;
                    this.resetForm();
                 }).catch(error => {
                   this.errors = error.response.data.errors;
                 });
             },
               resetForm()
               {
                this.name='',
                this.email='',
                this.phone='',
                this.address='',
                this.status=''
               },

               },
        created() {

        }
    }
</script>
