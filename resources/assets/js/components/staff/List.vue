<template>
  <div>
    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
    <div class="my-4 flex flex-wrap items-center justify-between staff-toolbar">
      <ul class="list-reset flex staff-view-tabs">
        <li :class="{'active': view === 'current'}">
          <a href="#" @click.prevent="filterByView('current')">Current Staff</a>
        </li>
        <li :class="{'active': view === 'exit'}">
          <a href="#" @click.prevent="filterByView('exit')">Relieved Staff</a>
        </li>
      </ul>

      <div class="flex items-center gap-2">
        <select class="alphabet-select" v-model="selectedLetter" @change="sortMembers(selectedLetter)">
          <option value="">A - Z</option>
          <option v-for="alphabet in alphabets" :key="alphabet" :value="alphabet">{{ alphabet }}</option>
        </select>
        <a href="#" class="clear-btn" @click.prevent="clearAll()">Clear</a>
      </div>
    </div>
    <div class="no-names-message" v-if="selectedLetter && !users.length">
      <i class="fa-solid fa-circle-info"></i> No staff found for the letter "{{ selectedLetter }}".
    </div>
    <div v-if="selectedUsersCount > 0" class="bg-amber-50 border border-amber-200 rounded p-3 my-3 flex items-center justify-between">
      <div>
        <span class="text-sm text-amber-800">{{ selectedUsersCount }} of {{ totalStaffCount }} staff selected.</span>
        <a v-if="selectedUsersCount < totalStaffCount" href="#" @click.prevent="selectAllStaff()" class="text-amber-600 font-semibold text-sm ml-2">Select all {{ totalStaffCount }} staff</a>
      </div>
    </div>
    <div>
      <div class="my-8 overflow-x-auto staff-table-wrap">
        <vue-good-table
          :columns="tableColumns"
          :rows="users"
          :select-options="{ enabled: true, selectOnCheckboxOnly: true, selectionText: selectionText, clearSelectionText: 'clear' }"
          :pagination-options="{ enabled: true, perPage: 20 }"
          @selected-rows-change="onSelectionChanged"
        >
          <template #selected-row-actions>
            <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 text-sm font-medium" @click="sendMessage()">Send Message</a>
          </template>
          <template #table-row="props">
            <div v-if="props.column.field == 'fullname'">
              <staff-name-cell :url="url" show-path="/admin/staff/show/" :name="props.row.name" :avatar="props.row.avatar" :title="props.row.title" :fullname="props.row.fullname" :employee-id="props.row.employee_id" :joining-date="props.row.joining_date" :relieved-at="props.row.relieved_at"></staff-name-cell>
            </div>
            <div v-else-if="props.column.field == 'designation_name'">
              {{ props.row.designation_name }}
            </div>
            <div v-else-if="props.column.field == 'status'">
              <span class="rounded-full px-2 py-1 text-xs font-semibold" v-bind:class="statusBadgeClass(props.row.status)">{{ statusLabel(props.row.status) }}</span>
            </div>
            <div v-else-if="props.column.field == 'date_of_birth'">
              {{ props.row.date_of_birth }}
            </div>
            <div v-else-if="props.column.field == 'last_login_at'">
              <span v-if="props.row.last_login_at" class="text-gray-700">{{ props.row.last_login_at }}</span>
              <span v-else class="rounded-full px-2 py-1 text-xs font-semibold bg-gray-100 text-gray-500">Never Logged In</span>
            </div>
          </template>
        </vue-good-table>
      </div>
    </div>
    <div v-if="this.send == 1" class="modal modal-mask">
      <div class="modal-wrapper px-4">
        <div class="modal-container w-full max-w-2xl px-8 mx-auto">
          <div class="modal-header flex justify-between items-center mb-6">
            <div class="flex items-center gap-3">
              <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                <i class="fa-solid fa-paper-plane text-blue-600 text-lg"></i>
              </div>
              <div>
                <h2 class="text-2xl font-bold text-gray-800">Send Message</h2>
                <p class="text-sm text-gray-600">To {{ selectedUsersCount }} selected staff member(s)</p>
              </div>
            </div>
            <button class="text-gray-400 hover:text-gray-600 text-3xl leading-none" @click="closeModal()">
              ×
            </button>
          </div>

          <div class="space-y-6">
            <div>
              <label for="subject" class="block text-sm font-semibold text-gray-700 mb-2 uppercase">Subject</label>
              <input type="text" name="subject" v-model="subject" placeholder="Enter subject" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" />
              <span v-if="errors.subject" class="text-red-500 text-xs font-semibold mt-1 block">{{errors.subject[0]}}</span>
            </div>

            <div>
              <label for="message" class="block text-sm font-semibold text-gray-700 mb-2 uppercase">Message</label>
              <textarea name="message" v-model="message" placeholder="Type your message to the staff..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" rows="8"></textarea>
              <span v-if="errors.message" class="text-red-500 text-xs font-semibold mt-1 block">{{errors.message[0]}}</span>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
              <label class="flex items-start gap-3 cursor-pointer">
                <input type="checkbox" name="send_later" v-model="send_later" class="mt-1" @click="enableDate($event)">
                <div>
                  <span class="text-sm font-semibold text-gray-700">Send later</span>
                  <p class="text-xs text-gray-600 mt-1">Schedule delivery for a specific date and time instead of sending now.</p>
                </div>
              </label>
            </div>

            <div class="hidden" id="show_date">
              <label for="executed_at" class="block text-sm font-semibold text-gray-700 mb-2 uppercase">Date & Time</label>
              <VueDatePicker format="DD-MM-YYYY h:i:s" name="executed_at" v-model="executed_at" class="w-full rounded" id="executed_at" />
              <span v-if="errors.executed_at" class="text-red-500 text-xs font-semibold mt-1 block">{{errors.executed_at[0]}}</span>
            </div>

            <div class="flex gap-3 pt-4">
              <button class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50 transition" @click="closeModal()">
                Cancel
              </button>
              <button class="px-6 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition flex items-center gap-2" @click="submit()">
                <i class="fa-solid fa-paper-plane"></i> Send Message
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

  import staffNameCell from '../teacher/NameCell';
  import { VueDatePicker } from '@vuepic/vue-datepicker'
  import '@vuepic/vue-datepicker/dist/main.css'
  import { VueGoodTable } from 'vue-good-table-next'
  import 'vue-good-table-next/dist/vue-good-table-next.css'
  export default {
    props:['url','searchquery','letter','birthday'],
      data(){
        return{
          users:[],
          user:'',
          allStaff:[],
          totalStaffCount: 0,
          alphabets: [
          'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
          ],
          selectedLetter: this.letter || '',
          active: false,
          view: 'current',
          selected: [],
          selectedUsersCount: 0,
          send: 0,
          subject: '',
          message: '',
          send_later: '',
          executed_at: '',
          errors:[],
          success:null,
        }
      },

      created()
      {
        this.getData();
        this.getUrl();
      },

      computed:
      {
        tableColumns()
        {
          var columns = [
            { label: 'Name', field: 'fullname', width: '260px', filterOptions: { enabled: true, placeholder: 'Search name' } },
            { label: 'Designation', field: 'designation_name', width: '200px', filterOptions: { enabled: true, placeholder: 'Search designation' } },
            { label: 'Status', field: 'status', width: '150px' },
            { label: 'Last Login', field: 'last_login_at', width: '200px', sortable: false },
          ];
          if (this.birthday == 'true')
          {
            columns.push({ label: 'Date of Birth', field: 'date_of_birth', width: '180px' });
          }
          return columns;
        },
    },

    components:
    {
      'staff-name-cell': staffNameCell,
      VueDatePicker,
      VueGoodTable,
    },

    methods:
    {
      getData()
      {
        var viewQuery = this.view == 'exit' ? '&view=exit' : '';
        axios.get('/admin/staffs/find?'+this.searchquery+viewQuery).then(response => {
          this.users = response.data.data;
          this.totalStaffCount = response.data.total || this.users.length;
        }).catch(error => {
          console.error('Failed to load staff list', error);
        });
      },

      filterByView(view)
      {
        this.view = view;
        this.getData();
      },

      statusLabel(status)
      {
        if (status == 'exit')
        {
          return 'Relieved';
        }
        return status == 'active' ? 'Active' : 'Inactive';
      },

      statusBadgeClass(status)
      {
        if (status == 'exit')
        {
          return 'bg-red-100 text-red-700';
        }
        return status == 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-200 text-gray-600';
      },

      clearAll()
      {
        window.location.href = '/admin/staffs';
      },

      sortMembers(name)
      {
        this.selectedLetter= name;
        this.active = true;
        var url = this.currenturl;

        if (name === '') {
          // Show all (A-Z) - remove alphabet parameter
          if (window.location.search.indexOf('alphabet=') > -1)
          {
            var href = new URL(url);
            href.searchParams.delete('alphabet');
            url=href.toString();
          }
        } else {
          // Filter by specific letter
          var q='alphabet='+this.selectedLetter;
          if (window.location.search.indexOf('alphabet=') > -1)
          {
            var href = new URL(url);
            href.searchParams.set('alphabet', this.selectedLetter);
            url=href.toString();
          }
          else
          {
            if (url.indexOf('?') > -1)
            {
               url += '&'
            }
            else
            {
              url += '?'
            }
            url += q;
          }
        }
        window.location.href = url;
      },

      getUrl()
      {
        this.currenturl =  this.url+"/admin/staffs/";
        if(this.searchquery!='')
        {
          this.currenturl =  this.currenturl+'?'+this.searchquery;
        }
      },

      selectionText(count)
      {
        return count + (count == 1 ? ' staff selected' : ' staff selected');
      },

      onSelectionChanged(params)
      {
        this.selected = params.selectedRows.map(function (row) { return row.id; });
        this.selectedUsersCount = this.selected.length;
      },

      sendMessage()
      {
        if(this.selectedUsersCount > 0)
        {
          this.send = 1;
        }
        else
        {
          alert("Select Staff")
        }
      },

      submit()
      {
        this.errors=[];
        axios.post('/admin/staff/sendMessageToAll',{
          selected:this.selected,
          subject:this.subject,
          message:this.message,
          send_later:this.send_later,
          executed_at:this.executed_at,
        }).then(response => {
          this.success = response.data.message;
          this.send=0;
          window.location.reload();
        }).catch(error => {
          this.errors = error.response.data.errors;
        });
      },

      closeModal()
      {
        this.send = 0;
      },

      enableDate(e)
      {
        if (e.target.checked)
        {
          this.send_later = 1;
          if($('#show_date').hasClass('hidden'))
          {
            $('#show_date').removeClass('hidden').addClass('block');
          }
        }
        else
        {
          this.send_later = 0;
          if($('#show_date').hasClass('block'))
          {
            $('#show_date').removeClass('block').addClass('hidden');
          }
        }
      },

      selectAllStaff()
      {
        var viewQuery = this.view == 'exit' ? '&view=exit' : '';
        axios.get('/admin/staffs/find?limit=all&'+this.searchquery+viewQuery).then(response => {
          this.allStaff = response.data.data || [];
          this.selected = this.allStaff.map(function (row) { return row.id; });
          this.selectedUsersCount = this.selected.length;
        }).catch(error => {
          console.error('Failed to load all staff', error);
        });
      },
    }
  }
</script>

<style scoped>

.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .5);
  display: table;
  transition: opacity .3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
  overflow: auto;
}

.modal-container {
  margin: 0px auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
  transition: all .3s ease;
  overflow: auto;
}

.modal-header h2 {
  margin-top: 0;
  color: #42b983;
}

.modal-body {
  margin: 20px 0;
}

.modal-default-button {
  float: right;
}

.staff-view-tabs {
  gap: 0.5rem;
}

.staff-view-tabs li a {
  display: inline-block;
  padding: 0.5rem 1.25rem;
  font-size: 0.95rem;
  font-weight: 600;
  color: #6b7280;
  border-radius: 9999px;
  text-decoration: none;
}

.staff-view-tabs li a:hover {
  color: #374151;
}

.staff-view-tabs li.active a {
  background-color: #e53e3e;
  color: #fff;
}

.alphabet-select {
  border: 1px solid #d1d5db;
  border-radius: 0.375rem;
  padding: 0.375rem 0.75rem;
  font-size: 0.875rem;
  font-weight: 600;
  color: #374151;
  background-color: #fff;
}

.clear-btn {
  font-size: 0.8rem;
  font-weight: 600;
  color: #e53e3e;
  text-decoration: none;
  white-space: nowrap;
}

.clear-btn:hover {
  text-decoration: underline;
}

.staff-table-wrap :deep(table.vgt-table) {
  min-width: 850px;
  table-layout: fixed;
}

.no-names-message {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  margin: 0.75rem 0;
  padding: 0.75rem 1rem;
  font-size: 0.875rem;
  color: #92400e;
  background-color: #fffbeb;
  border: 1px solid #fde68a;
  border-radius: 0.375rem;
}

</style>
