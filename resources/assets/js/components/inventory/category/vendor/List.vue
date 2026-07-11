<template>
  <div class="relative">
    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
    <div class="flex flex-wrap lg:flex-row justify-between items-center">
      <div class="">
        <h1 class="admin-h1 my-3">Category Vendor List</h1>
        <a><h6> SubCategory : {{categoryname}}</h6></a>
      </div>
      <div class="relative flex items-center w-8/12 lg:w-1/4 md:w-1/4 justify-end">
        <div class="flex items-center">
          <a :href="url+'/admin/categoryvendor/'+categoryid+'/add'" class="no-underline text-white px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center rounded"  >
            <span class="mx-1 text-sm font-semibold">Add </span>
            <svg data-v-2a22d6ae="" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 409.6 409.6" xml:space="preserve" class="w-3 h-3 fill-current text-white"><g data-v-2a22d6ae=""><g data-v-2a22d6ae=""><path data-v-2a22d6ae="" d="M392.533,187.733H221.867V17.067C221.867,7.641,214.226,0,204.8,0s-17.067,7.641-17.067,17.067v170.667H17.067 C7.641,187.733,0,195.374,0,204.8s7.641,17.067,17.067,17.067h170.667v170.667c0,9.426,7.641,17.067,17.067,17.067 s17.067-7.641,17.067-17.067V221.867h170.667c9.426,0,17.067-7.641,17.067-17.067S401.959,187.733,392.533,187.733z"></path></g></g></svg>
          </a>
        </div>
      </div>
    </div>
    <div class="my-3 bg-gray-200 px-2 py-1 text-sm">
      <a :href="url+'/admin/dashboard'" class="text-red-500 hover:underline">Home</a>
      <span class="mx-2">/</span>
      <a :href="url+'/admin/category/show'" class="text-red-500 hover:underline">Category</a>
      <span class="mx-2">/</span>
      <a :href="url+'/admin/category/'+parentid+'/show'" class="text-red-500 hover:underline">Sub Category</a>
      <span class="mx-2">/</span>
      <span>Vendor</span>
    </div>
    <div class="">
      <div class="flex flex-wrap custom-table my-3 overflow-auto">
        <table class="w-full">
          <thead class="bg-grey-light">
            <tr class="border-b">
              <th class="text-left text-sm px-2 py-2 text-grey-darker">No </th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker">Vendor Name</th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker">Email </th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker">Phone </th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker">Address </th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker">Status </th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker">Actions </th>
            </tr>
          </thead>
          <tbody v-if="this.vendor != ''" >
            <tr class="border-b" v-for="(vendors,k1) in vendor" :key="k=k1+1">
              <td class="py-3 px-2" >
                <p class="font-semibold text-xs">{{k}}</p>
              </td>
              <td class="py-3 px-2" >
                <p class="font-semibold text-xs">{{vendors.vendor.name}}</p>
              </td>
              <td class="py-3 px-2">
                <p class="font-semibold text-xs">{{vendors.vendor.email}}</p>
              </td>
              <td class="py-3 px-2">
                <p class="font-semibold text-xs">{{vendors.vendor.phone}}</p>
              </td>
              <td class="py-3 px-2">
                <p class="font-semibold text-xs">{{vendors.vendor.address}}</p>
              </td>
              <td class="py-3 px-2">
                <p v-if="vendors.vendor.status==0" class="font-semibold text-xs">Inactive</p>
                <p v-else class="font-semibold text-xs">Active</p>
              </td>
              <td class="py-3 px-2">
                <div class="flex items-center">
                  <a href="#" @click="deletevendor(vendors.id)" title="Delete">
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-4 h-4 fill-current text-black-500 mx-1"><g><g><g><polygon points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804"></polygon><rect x="235.948" y="175.791" width="40.104" height="237.285"></rect><polygon points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804"></polygon><path d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74 c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474 L380.665,471.896z"></path></g></g></g> <g><g><path d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42 C354.924,14.992,339.932,0,321.504,0z"></path></g></g></svg>
                  </a>
                </div>
              </td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr class="border-b">
              <td colspan="7">
                <p class="font-semibold text-s" style="text-align: center">No Records Found</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    props:['url','categoryid','categoryname','parentid'],
    data(){
      return {
        vendor:[],
        success:null,
      }
    },
    methods:
    {
      categoryshow()
      {
        axios.get('/admin/product/vendor/'+this.categoryid+'/list').then(response=>{
          this.vendor=response.data;
        });
      },

      deletevendor(id)
      {
        var thisswal = this;
        swal({
          title: 'Are you sure',
          text: 'Do you want to delete this vendor category ?',
          icon: "info",
          buttons: [
            'No',
            'Yes'
          ],
          dangerMode: true,
        }).then(function(isConfirm) {
          if (isConfirm)
          {
            axios.delete('/admin/categoryvendor/'+id+'/delete').then(response => {
              thisswal.success    = response.data.message;
              thisswal.categoryshow();
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
      this.categoryshow();
    }
  }
</script>
