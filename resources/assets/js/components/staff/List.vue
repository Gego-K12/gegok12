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
          <option value="" disabled>A - Z</option>
          <option v-for="alphabet in alphabets" :key="alphabet" :value="alphabet">{{ alphabet }}</option>
        </select>
        <a href="#" class="clear-btn" @click.prevent="clearAll()">Clear</a>
      </div>
    </div>
    <div class="no-names-message" v-if="selectedLetter && !users.length">
      <i class="fa-solid fa-circle-info"></i> No staff found for the letter "{{ selectedLetter }}".
    </div>
    <div>
      <div class="my-8 overflow-x-auto staff-table-wrap">
        <vue-good-table
          :columns="tableColumns"
          :rows="users"
          :pagination-options="{ enabled: true, perPage: 20 }"
        >
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
  </div>
</template>

<script>

  import staffNameCell from '../teacher/NameCell';
  import { VueGoodTable } from 'vue-good-table-next'
  import 'vue-good-table-next/dist/vue-good-table-next.css'
  export default {
    props:['url','searchquery','letter','birthday'],
      data(){
        return{
          users:[],
          user:'',
          alphabets: [
          'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
          ],
          selectedLetter: this.letter || '',
          active: false,
          view: 'current',
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
      VueGoodTable,
    },

    methods:
    {
      getData()
      {
        var viewQuery = this.view == 'exit' ? '&view=exit' : '';
        axios.get('/admin/staffs/find?'+this.searchquery+viewQuery).then(response => {
          this.users = response.data.data;
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
        var q='alphabet='+this.selectedLetter;
        var url = this.currenturl;

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
    }
  }
</script>

<style scoped>

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
