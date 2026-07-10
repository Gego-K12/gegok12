<template>
  <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3" v-bind:class="[this.profile_tab==4?'block' :'hidden']">
  <div class="custom-table ">
    <table class=" w-full overflow-x-auto"> <!-- profiletab-table -->
      <thead>
        <tr>
          <th>Name</th>
          <th>Relation</th>
          <th>Date Of Birth</th>
          <th>Class</th>
        </tr>
      </thead>
      <tbody v-if="this.users != ''">
        <tr v-for="user in users">
          <td>
            <div class="flex items-center">
              <a href="#" class="mx-2 text-blue-600 hover:text-blue-400">{{ user.fullname }}</a>
            </div>
          </td>
          <td>{{ user.relation }}</td>
          <td>{{ user.date_of_birth }}</td>
          <td>{{ user.standard_section }}</td>
        </tr>
      </tbody>
      <tbody v-if="this.siblingDetails.length > 0">
        <tr v-for="sibling in siblingDetails">
          <td>
            <div class="flex items-center">
              <span class="mx-2">{{ sibling.fullname }}</span>
            </div>
          </td>
          <td>{{ sibling.relation }}</td>
          <td>{{ sibling.date_of_birth }}</td>
          <td>{{ sibling.standard_section }}</td>
        </tr>
      </tbody>
      <tbody v-if="(this.users == '') && (this.siblingDetails.length == 0)">
        <tr>
          <td colspan="4">
            <p class="font-semibold text-s" style="text-align: center">No Records Found</p>
          </td>
        </tr>
      </tbody>
    </table>
    </div>
  </div>
</template>

<script>
  import { bus } from "../../../app";

  export default {
    props:['url','name','mode'],
    data () {
      return {
        profile_tab:'',
        users:[],
        siblingDetails:[],
        errors:[],
        success:null,
      }
    },

    methods:{

      getData()
      {
        axios.get('/'+this.mode+'/student/show/siblings/'+this.name).then(response => {
          this.users = response.data.data;
          //console.log(this.users)
        });
      },

      getSiblingDetails()
      {
        axios.get('/'+this.mode+'/student/show/siblingdetails/'+this.name).then(response => {
          this.siblingDetails = response.data;
        });
      },
    },

    created()
    {
      this.getData();
      this.getSiblingDetails();

      bus.on("dataProfileTab", data => {
        if(data!='')
        {
          this.profile_tab=data;
        }
      });
    }
  }
</script>