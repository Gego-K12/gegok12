<template>
  <div class="bg-white shadow px-4 py-3">
    <div v-if="mode !== 'mine'" class="flex flex-wrap lg:flex-row justify-between items-center my-3">
      <h1 class="admin-h1">{{ type === 'check' ? 'Work Permission Approvals' : 'Work Permissions' }}</h1>
      <a v-if="type === 'apply'" href="/teacher/workpermission/add" class="no-underline text-white px-4 py-1 flex items-center custom-green">
        <span class="text-sm font-semibold">Apply Permission</span>
      </a>
    </div>

    <div v-if="permissions.length === 0" class="text-sm text-gray-500 py-4">No work permission requests found.</div>

    <table v-else class="w-full text-sm">
      <thead>
        <tr class="border-b text-left text-gray-600">
          <th v-if="type === 'check'" class="py-2 pr-2">Staff</th>
          <th class="py-2 px-2">Date</th>
          <th class="py-2 px-2">Time</th>
          <th class="py-2 px-2">Type</th>
          <th class="py-2 px-2">Reason</th>
          <th class="py-2 px-2">Status</th>
          <th class="py-2 px-2">Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="permission in permissions" :key="permission.id" class="border-b">
          <td v-if="type === 'check'" class="py-2 pr-2">{{ permission.user_name }}</td>
          <td class="py-2 px-2">{{ permission.date }}</td>
          <td class="py-2 px-2">{{ permission.from_time }} - {{ permission.to_time }}</td>
          <td class="py-2 px-2">{{ permission.type }}</td>
          <td class="py-2 px-2">{{ permission.reason }}</td>
          <td class="py-2 px-2">
            <span :class="statusClass(permission.status)" class="text-xs font-semibold rounded px-2 py-1">{{ permission.status }}</span>
          </td>
          <td class="py-2 px-2">
            <template v-if="type === 'check' && permission.status === 'Pending'">
              <a :href="'/teacher/workpermission/approve/' + permission.id" class="text-green-700 text-xs font-semibold mr-2">Approve</a>
              <a :href="'/teacher/workpermission/reject/' + permission.id" class="text-red-700 text-xs font-semibold">Reject</a>
            </template>
            <template v-else-if="type === 'apply' && permission.status === 'Pending'">
              <a href="#" @click.prevent="cancel(permission.id)" class="text-red-700 text-xs font-semibold">Cancel</a>
            </template>
          </td>
        </tr>
      </tbody>
    </table>

    <div v-if="meta && meta.last_page > 1" class="flex justify-end mt-4 gap-2">
      <a v-for="page in meta.last_page" :key="page" href="#" @click.prevent="fetchList(page)"
         class="text-xs px-2 py-1 rounded" :class="page === meta.current_page ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700'">
        {{ page }}
      </a>
    </div>
  </div>
</template>

<script>
  export default {
    props: ['url', 'type', 'mode'],
    data() {
      return {
        permissions: [],
        meta: null,
      }
    },

    methods: {
      endpoint() {
        return this.mode === 'mine' ? '/teacher/workpermission/mylist' : '/teacher/workpermission/list';
      },

      statusClass(status) {
        const map = {
          Pending: 'bg-yellow-100 text-yellow-700',
          Approved: 'bg-green-100 text-green-700',
          Rejected: 'bg-red-100 text-red-700',
          Cancelled: 'bg-gray-100 text-gray-500',
        };
        return map[status] || 'bg-gray-100 text-gray-500';
      },

      fetchList(page) {
        axios.get(this.endpoint(), {params: {page: page || 1}}).then(response => {
          this.permissions = response.data.data;
          this.meta = response.data.meta;
        });
      },

      cancel(id) {
        if (! confirm('Cancel this work permission request?')) {
          return;
        }
        axios.get('/teacher/workpermission/delete/' + id).then(() => {
          this.fetchList();
        });
      },
    },

    created() {
      this.fetchList();
    },
  }
</script>
