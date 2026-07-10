<template>
  <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3" v-bind:class="[this.profile_tab==10?'block' :'hidden']">
    <div v-if="Object.keys(user).length>0 && user.details">
      <ul class="list-reset leading-loose my-2 text-xs">
        <li class="flex py-1">
          <div class="flex items-center">
            <span class="text-gray-700 font-medium mx-2">UG Degree : </span>
          </div>
          <div>
            <p v-if="user.details.ug_degree_name != null">{{ user.details.ug_degree_name }}</p>
            <p v-else>--</p>
          </div>
        </li>

        <li class="flex py-1">
          <div class="flex items-center">
            <span class="text-gray-700 font-medium mx-2">PG Degree : </span>
          </div>
          <div>
            <p v-if="user.details.pg_degree_name != null">{{ user.details.pg_degree_name }}</p>
            <p v-else>--</p>
          </div>
        </li>

        <li class="flex py-1">
          <div class="flex items-center">
            <span class="text-gray-700 font-medium mx-2">Subject Specialization : </span>
          </div>
          <div>
            <p v-if="user.details.specialization != null">{{ user.details.specialization }}</p>
            <p v-else>--</p>
          </div>
        </li>

        <li class="flex py-1">
          <div class="flex items-center">
            <span class="text-gray-700 font-medium mx-2">Other Courses / Certificates : </span>
          </div>
          <div>
            <p v-if="user.details.sub_qualification != null">{{ user.details.sub_qualification }}</p>
            <p v-else>--</p>
          </div>
        </li>
      </ul>

      <div class="my-2">
        <span class="text-gray-700 font-medium text-xs mx-2">Professional Courses / Certificates : </span>
        <div v-if="user.details.qualification_name && user.details.qualification_name.length > 0" class="list-reset leading-loose my-2 text-xs">
          <li class="px-6" v-for="(qualification, index) in user.details.qualification_name" :key="index">{{ qualification }}</li>
        </div>
        <p v-else class="mx-2">--</p>
      </div>
    </div>
  </div>
</template>

<script>
  import { bus } from "../../../app";
  export default {
    props: ['url', 'name'],
    data() {
      return {
        profile_tab: '',
        user: [],
      }
    },

    methods: {
      getData() {
        axios.get('/admin/teacher/show/details/' + this.name).then(response => {
          this.user = response.data.data[0];
        });
      },
    },

    created() {
      this.getData();
      bus.on("dataProfileTab", data => {
        if (data != '') {
          this.profile_tab = data;
        }
      });
    }
  }
</script>
