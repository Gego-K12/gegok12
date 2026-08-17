<template>
  <div class="bg-white shadow px-4 py-3">
    <div class="my-5">
      <!-- Filters -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        <!-- Date Filter -->
        <div class="tw-form-group">
          <label for="date" class="tw-form-label">Date</label>
          <input
            type="date"
            v-model="filters.date"
            class="tw-form-control w-full"
            id="date"
            @change="applyFilters"
          >
        </div>

        <!-- Session Filter -->
        <div class="tw-form-group">
          <label for="session" class="tw-form-label">Session</label>
          <select
            v-model="filters.session"
            class="tw-form-control w-full"
            id="session"
            @change="applyFilters"
          >
            <option value="">All Sessions</option>
            <option value="forenoon">Forenoon</option>
            <option value="afternoon">Afternoon</option>
          </select>
        </div>

        <!-- Status Filter -->
        <div class="tw-form-group">
          <label for="status" class="tw-form-label">Status</label>
          <select
            v-model="filters.status"
            class="tw-form-control w-full"
            id="status"
            @change="applyFilters"
          >
            <option value="">All</option>
            <option value="1">Present</option>
            <option value="0">Absent</option>
          </select>
        </div>
      </div>

      <!-- Add Attendance Button -->
      <div class="mb-6">
        <a
          href="#"
          class="btn btn-submit blue-bg text-white rounded px-4 py-2 text-sm font-medium"
          @click.prevent="goToAdd"
        >
          + Record Attendance
        </a>
        <a
          href="#"
          class="btn btn-submit blue-bg text-white rounded px-4 py-2 text-sm font-medium ml-2"
          @click.prevent="goToRegister"
        >
          📅 View Calendar
        </a>
      </div>

      <!-- Staff List Table -->
      <div v-if="loading" class="text-center py-6">
        <p class="text-gray-500">Loading...</p>
      </div>

      <div v-else-if="stafflist.length === 0" class="text-center py-6">
        <p class="text-gray-500">No staff found</p>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-gray-100 border-b">
            <tr>
              <th class="px-4 py-2 text-left">Staff Name</th>
              <th class="px-4 py-2 text-left">Staff ID</th>
              <th class="px-4 py-2 text-left">Designation</th>
              <th class="px-4 py-2 text-center">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="staff in stafflist"
              :key="staff.teacher_id"
              class="border-b hover:bg-gray-50"
            >
              <td class="px-4 py-2 font-medium">{{ staff.teacher_name }}</td>
              <td class="px-4 py-2">{{ staff.teacher_id }}</td>
              <td class="px-4 py-2 text-xs text-gray-600">
                {{ staff.designation || '--' }}
              </td>
              <td class="px-4 py-2 text-center">
                <a
                  href="#"
                  class="text-blue-600 hover:text-blue-900 text-xs font-medium"
                  @click.prevent="viewStaffAttendance(staff.teacher_id)"
                >
                  View History
                </a>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Summary Stats -->
      <div v-if="stafflist.length > 0" class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-blue-50 border border-blue-200 rounded px-4 py-3">
          <p class="text-xs text-gray-600">Total Staff</p>
          <p class="text-2xl font-bold text-blue-600">{{ stafflist.length }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['url', 'mode', 'stafflist', 'absentReasons'],
  data() {
    return {
      loading: false,
      filters: {
        date: '',
        session: '',
        status: '',
      },
    };
  },
  methods: {
    applyFilters() {
      // Filters would be applied via computed property or API call
    },
    goToAdd() {
      window.location.href = this.url + '/' + this.mode + '/attendance/staff/add';
    },
    goToRegister() {
      window.location.href = this.url + '/' + this.mode + '/attendance/staff/register';
    },
    viewStaffAttendance(staffId) {
      // Could open a modal or navigate to detail page
      alert('View attendance history for staff: ' + staffId);
    },
  },
};
</script>

<style scoped>
.tw-form-label {
  @apply block text-sm font-medium text-gray-700 mb-1;
}

.tw-form-control {
  @apply px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500;
}

.btn-submit {
  @apply inline-block cursor-pointer transition-colors duration-200;
}

.blue-bg {
  @apply bg-blue-600 hover:bg-blue-700;
}
</style>
