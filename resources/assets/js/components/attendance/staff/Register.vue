<template>
  <div class="bg-white shadow px-4 py-3">
    <div class="flex flex-col lg:flex-row my-5 gap-6">
      <div class="w-full lg:w-2/5 lg:pr-4">
        <FullCalendar :options="calendarOptions" />
        <div class="flex flex-wrap gap-4 mt-3 text-xs text-gray-600">
          <span class="flex items-center"><span class="sr-legend-swatch sr-legend-has-record"></span>Recorded</span>
          <span class="flex items-center"><span class="sr-legend-swatch sr-legend-no-record"></span>Not recorded</span>
          <span class="flex items-center"><span class="sr-legend-swatch sr-legend-selected"></span>Selected</span>
        </div>
      </div>

      <div class="w-full lg:w-3/5 lg:pl-6 lg:border-l mt-6 lg:mt-0">
        <h2 class="font-semibold text-base text-gray-700 mb-4">{{ formattedSelectedDate }}</h2>

        <div v-if="loading" class="text-sm text-gray-500">Loading...</div>

        <template v-else>
          <div v-if="staff.length === 0" class="text-sm text-gray-500">No staff found.</div>

          <div v-else-if="!sessionsRecorded.forenoon && !sessionsRecorded.afternoon" class="text-sm text-gray-500">
            Attendance has not been recorded for this date.
          </div>

          <template v-else>
            <div class="flex flex-wrap gap-4 mb-4 text-sm">
              <span class="font-semibold text-green-700">{{ summary.present }} Present</span>
              <span class="font-semibold text-red-700">{{ summary.absent }} Absent</span>
              <span v-if="summary.not_recorded" class="font-semibold text-gray-500">{{ summary.not_recorded }} Not Recorded</span>
            </div>

            <div class="overflow-y-auto" style="max-height: 28rem;">
              <table class="w-full text-sm">
                <thead>
                  <tr class="border-b text-left text-gray-600">
                    <th class="py-2 pr-2">Staff</th>
                    <th class="py-2 px-2">Forenoon</th>
                    <th class="py-2 px-2">Afternoon</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="member in staff" :key="member.user_id" class="border-b">
                    <td class="py-2 pr-2">
                      <p class="font-semibold">{{ member.name }}</p>
                      <p v-if="member.designation" class="text-xs text-gray-500">{{ member.designation }}</p>
                    </td>
                    <td class="py-2 px-2">
                      <span v-if="member.sessions.forenoon === null" class="text-xs text-gray-400">--</span>
                      <span v-else-if="member.sessions.forenoon.status" class="text-xs font-semibold text-green-700 bg-green-100 rounded px-2 py-1">Present</span>
                      <span v-else class="text-xs font-semibold text-red-700 bg-red-100 rounded px-2 py-1" :title="member.sessions.forenoon.remarks || ''">
                        Absent<span v-if="member.sessions.forenoon.reason"> &middot; {{ member.sessions.forenoon.reason }}</span>
                      </span>
                    </td>
                    <td class="py-2 px-2">
                      <span v-if="member.sessions.afternoon === null" class="text-xs text-gray-400">--</span>
                      <span v-else-if="member.sessions.afternoon.status" class="text-xs font-semibold text-green-700 bg-green-100 rounded px-2 py-1">Present</span>
                      <span v-else class="text-xs font-semibold text-red-700 bg-red-100 rounded px-2 py-1" :title="member.sessions.afternoon.remarks || ''">
                        Absent<span v-if="member.sessions.afternoon.reason"> &middot; {{ member.sessions.afternoon.reason }}</span>
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </template>
        </template>
      </div>
    </div>
  </div>
</template>

<script>
import FullCalendar from '@fullcalendar/vue3'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction'

export default {
  components: { FullCalendar },
  props: ['url', 'mode', 'academicYearStart', 'today'],
  data() {
    return {
      selectedDate: this.today,
      loading: false,
      staff: [],
      summary: { present: 0, absent: 0, not_recorded: 0 },
      sessionsRecorded: { forenoon: false, afternoon: false },
      recordedDates: [],
      visibleStart: null,
      visibleEnd: null,
    }
  },
  computed: {
    formattedSelectedDate() {
      return new Date(this.selectedDate + 'T00:00:00').toLocaleDateString('en-GB', {
        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric',
      });
    },
    calendarOptions() {
      return {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: 'dayGridMonth',
        headerToolbar: { left: 'prev,next today', center: 'title', right: '' },
        dayHeaderFormat: { weekday: 'narrow' },
        validRange: { start: this.academicYearStart, end: this.nextDay(this.today) },
        initialDate: this.today,
        height: 'auto',
        fixedWeekCount: false,
        dateClick: this.handleDateClick,
        datesSet: this.handleDatesSet,
        events: this.calendarEvents,
      };
    },
    calendarEvents() {
      if (! this.visibleStart || ! this.visibleEnd) {
        return [];
      }

      const events = [];
      const rangeStart = new Date(this.academicYearStart + 'T00:00:00');
      const rangeEnd = new Date(this.nextDay(this.today) + 'T00:00:00');

      for (let d = new Date(this.visibleStart); d < new Date(this.visibleEnd); d.setDate(d.getDate() + 1)) {
        if (d < rangeStart || d >= rangeEnd) {
          continue;
        }

        const dateStr = d.toISOString().slice(0, 10);
        let className = 'fc-bg-no-record';

        if (dateStr === this.selectedDate) {
          className = 'fc-bg-selected';
        } else if (this.recordedDates.includes(dateStr)) {
          className = 'fc-bg-has-record';
        }

        events.push({ start: dateStr, display: 'background', classNames: [className] });
      }

      return events;
    },
  },
  methods: {
    nextDay(dateStr) {
      const d = new Date(dateStr + 'T00:00:00');
      d.setDate(d.getDate() + 1);
      return d.toISOString().slice(0, 10);
    },
    handleDateClick(info) {
      if (info.dayEl.classList.contains('fc-day-disabled')) {
        return;
      }

      this.selectedDate = info.dateStr;
      this.fetchRegister();
    },
    fetchRegister() {
      this.loading = true;

      axios.get('/' + this.mode + '/attendance/staff/register/' + this.selectedDate).then(response => {
        this.staff = response.data.staff;
        this.summary = response.data.summary;
        this.sessionsRecorded = response.data.sessionsRecorded;
      }).finally(() => {
        this.loading = false;
      });
    },
    handleDatesSet(arg) {
      this.visibleStart = arg.startStr.slice(0, 10);
      this.visibleEnd = arg.endStr.slice(0, 10);

      const d = arg.view.currentStart;
      const month = d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0');
      this.fetchMonthSummary(month);
    },
    fetchMonthSummary(month) {
      axios.get('/' + this.mode + '/attendance/staff/register/summary/' + month).then(response => {
        this.recordedDates = response.data.recordedDates;
      });
    },
  },
  created() {
    this.fetchRegister();
  },
}
</script>

<style scoped>
:deep(.fc-toolbar-title) {
  font-size: 1rem;
}
:deep(.fc-button) {
  padding: 0.2rem 0.4rem;
  font-size: 0.75rem;
}
:deep(.fc-col-header-cell-cushion) {
  font-size: 0.7rem;
  color: #6b7280;
}

/* Shorter cells, bigger day numbers, obvious clickability.
   FullCalendar syncs row heights via inline styles, so plain CSS
   height/min-height can't win without !important. */
:deep(.fc-daygrid-day-frame) {
  height: 2.25rem !important;
  min-height: 2.25rem !important;
  cursor: pointer;
  transition: box-shadow 0.15s ease;
}
:deep(.fc-daygrid-day-events) {
  min-height: 0 !important;
}
:deep(.fc-daygrid-day-frame:hover) {
  box-shadow: inset 0 0 0 1.5px #9ca3af;
}
:deep(.fc-daygrid-day-top) {
  justify-content: center;
}
:deep(.fc-daygrid-day-number) {
  font-size: 1rem;
  font-weight: 600;
  padding: 0.25rem;
  color: #1f2937;
}

/* Disabled days (outside the selectable range) shouldn't look clickable */
:deep(.fc-day-disabled .fc-daygrid-day-frame) {
  cursor: default;
  opacity: 0.4;
}
:deep(.fc-day-disabled .fc-daygrid-day-frame:hover) {
  box-shadow: none;
}

/* Day states, driven by background events so they stay reactive */
:deep(.fc-bg-event) {
  opacity: 1;
}
:deep(.fc-bg-has-record) {
  background-color: #dcfce7;
}
:deep(.fc-bg-no-record) {
  background-color: #f9fafb;
}
:deep(.fc-bg-selected) {
  background-color: #bfdbfe;
  box-shadow: inset 0 0 0 2px #2563eb;
}

.sr-legend-swatch {
  display: inline-block;
  width: 0.75rem;
  height: 0.75rem;
  border-radius: 0.15rem;
  margin-right: 0.35rem;
}
.sr-legend-has-record {
  background-color: #dcfce7;
}
.sr-legend-no-record {
  background-color: #f9fafb;
  border: 1px solid #e5e7eb;
}
.sr-legend-selected {
  background-color: #bfdbfe;
  box-shadow: inset 0 0 0 2px #2563eb;
}
</style>
