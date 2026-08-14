<template>
  <div class="bg-white shadow px-4 py-3">
    <div class="my-5">
      <!-- Filters -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Class Filter -->
        <div class="tw-form-group">
          <label for="standardLink_id" class="tw-form-label">Class</label>
          <select
            v-model="filters.standardLink_id"
            class="tw-form-control w-full"
            id="standardLink_id"
            @change="applyFilters"
          >
            <option value="">All Classes</option>
            <option v-for="standard in standards" :key="standard.id" :value="standard.id">
              {{ standard.standard_name }} - {{ standard.section_name }}
            </option>
          </select>
        </div>

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
          + Add Attendance
        </a>
      </div>

      <!-- Attendance Table -->
      <div v-if="loading" class="text-center py-6">
        <p class="text-gray-500">Loading...</p>
      </div>

      <div v-else-if="filteredAttendance.length === 0" class="text-center py-6">
        <p class="text-gray-500">No attendance records found</p>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-gray-100 border-b">
            <tr>
              <th class="px-4 py-2 text-left">Date</th>
              <th class="px-4 py-2 text-left">Class</th>
              <th class="px-4 py-2 text-left">Session</th>
              <th class="px-4 py-2 text-left">Student</th>
              <th class="px-4 py-2 text-center">Status</th>
              <th class="px-4 py-2 text-left">Reason</th>
            </tr>
          </thead>
          <tbody>
            <tr
              v-for="record in filteredAttendance"
              :key="record.id"
              class="border-b hover:bg-gray-50"
            >
              <td class="px-4 py-2">{{ formatDate(record.date) }}</td>
              <td class="px-4 py-2">{{ record.standardLink_name }}</td>
              <td class="px-4 py-2 capitalize">{{ record.session }}</td>
              <td class="px-4 py-2">{{ record.user_name }}</td>
              <td class="px-4 py-2 text-center">
                <span
                  v-if="record.status === 1"
                  class="px-3 py-1 bg-green-100 text-green-700 rounded text-xs font-semibold"
                >
                  Present
                </span>
                <span
                  v-else
                  class="px-3 py-1 bg-red-100 text-red-700 rounded text-xs font-semibold"
                >
                  Absent
                </span>
              </td>
              <td class="px-4 py-2 text-xs text-gray-600">
                {{ record.reason || '--' }}
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
  props: ['url', 'mode', 'standards', 'students', 'absentReasons'],
  data() {
    return {
      loading: false,
      attendance: [],
      filters: {
        standardLink_id: '',
        date: '',
        session: '',
        status: '',
      },
    };
  },
  computed: {
    filteredAttendance() {
      let filtered = this.attendance;

      if (this.filters.standardLink_id) {
        filtered = filtered.filter(r => r.standardLink_id === parseInt(this.filters.standardLink_id));
      }

      if (this.filters.date) {
        filtered = filtered.filter(r => r.date === this.filters.date);
      }

      if (this.filters.session) {
        filtered = filtered.filter(r => r.session === this.filters.session);
      }

      if (this.filters.status !== '') {
        filtered = filtered.filter(r => r.status === parseInt(this.filters.status));
      }

      return filtered;
    },
  },
  methods: {
    applyFilters() {
      // Filters are applied via computed property
    },
    formatDate(date) {
      const d = new Date(date + 'T00:00:00');
      return d.toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' });
    },
    goToAdd() {
      window.location.href = this.url + '/' + this.mode + '/attendance/add';
    },
  },
  created() {
    // Initialize with sample data from props
    // In production, this would be fetched from an API
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
