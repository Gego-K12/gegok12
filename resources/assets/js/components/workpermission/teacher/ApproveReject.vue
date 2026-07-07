<template>
  <div class="bg-white shadow px-4 py-3">
    <div v-if="permission" class="mb-6">
      <table class="w-full lg:w-3/4 text-sm">
        <tbody>
        <tr class="border-b">
          <td class="py-2 font-semibold text-xs w-1/3">Staff</td>
          <td class="py-2 text-xs">{{ permission.user_name }}</td>
        </tr>
        <tr class="border-b">
          <td class="py-2 font-semibold text-xs">Date</td>
          <td class="py-2 text-xs">{{ permission.date }}</td>
        </tr>
        <tr class="border-b">
          <td class="py-2 font-semibold text-xs">Time</td>
          <td class="py-2 text-xs">{{ permission.from_time }} - {{ permission.to_time }} ({{ permission.duration_minutes }} min)</td>
        </tr>
        <tr class="border-b">
          <td class="py-2 font-semibold text-xs">Type</td>
          <td class="py-2 text-xs">{{ permission.type }}<span v-if="permission.is_emergency" class="text-red-600 font-semibold"> &middot; Emergency</span></td>
        </tr>
        <tr class="border-b">
          <td class="py-2 font-semibold text-xs">Reason</td>
          <td class="py-2 text-xs">{{ permission.reason }}</td>
        </tr>
        <tr v-if="permission.contact_number" class="border-b">
          <td class="py-2 font-semibold text-xs">Contact Number</td>
          <td class="py-2 text-xs">{{ permission.contact_number }}</td>
        </tr>
        </tbody>
      </table>
    </div>

    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

    <div class="tw-form-group w-full lg:w-3/4">
      <div class="mb-2">
        <label for="comments" class="tw-form-label">Comments</label>
      </div>
      <div class="mb-2">
        <textarea name="comments" id="comments" v-model="comments" class="tw-form-control w-full" rows="3" placeholder="Enter Comments"></textarea>
      </div>
      <span v-if="errors.comments" class="text-red-500 text-xs font-semibold">{{errors.comments[0]}}</span>
    </div>

    <div class="my-6">
      <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm()">
        {{ status === 'reject' ? 'Reject' : 'Approve' }}
      </a>
    </div>
  </div>
</template>

<script>
  export default {
    props: ['url', 'id', 'status'],
    data() {
      return {
        permission: null,
        comments: '',
        errors: [],
        success: null,
      }
    },

    methods: {
      loadPermission() {
        axios.get('/teacher/workpermission/approve/list/' + this.id).then(response => {
          this.permission = response.data;
        });
      },

      submitForm() {
        this.errors = [];
        this.success = null;

        let formData = new FormData();
        formData.append('comments', this.comments);

        const action = this.status === 'reject' ? 'reject' : 'approve';

        axios.post('/teacher/workpermission/' + action + '/' + this.id, formData, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
          this.success = response.data.success;
          window.location.href = '/teacher/workpermissions';
        }).catch(error => {
          this.errors = error.response.data.errors;
        });
      },
    },

    created() {
      this.loadPermission();
    },
  }
</script>
