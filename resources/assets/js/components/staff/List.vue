<template>
  <div>
    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
    <div class="my-4 filter-alphabet">
      <ul class="list-reset flex" style="max-width: calc(100vw - 40px);overflow: auto;">
        <li v-for="alphabet in alphabets">
          <a href="#" id="filter" class="block font-bold p-2 bg-grey-light border border-grey mx-2 ni" v-bind:class="letter === alphabet?'active':'text-blue'" v-text="alphabet"  @click="sortMembers(alphabet)"> </a>
        </li>
        <li>
          <a href="#" class="block font-bold p-2 bg-grey-light border border-grey mx-2 ni" @click="clearAll()">Clear All</a>
        </li>
      </ul>
      <div class="my-4" v-if="!filteredNames.length">No names for this letter</div>
      <div class="" v-if="filteredNames.length"></div>
    </div>
    <ul class="list-reset flex text-xs profile-tab flex-wrap">
      <li class="px-2 mx-1 py-1" :class="{'active': view === 'current'}">
        <a href="#" class="text-gray-700 font-medium" @click.prevent="filterByView('current')">Current Staff</a>
      </li>
      <li class="px-2 mx-1 py-1" :class="{'active': view === 'exit'}">
        <a href="#" class="text-gray-700 font-medium" @click.prevent="filterByView('exit')">Relieved Staff</a>
      </li>
    </ul>
    <div>
      <div class="my-8">
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
          selectedLetter: undefined,
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
        filteredNames ()
        {
          let users = this.users
          if (this.selectedLetter)
          {
            users = users.filter((name) => {
            let firstLetter = name.charAt(0).toUpperCase()
            return firstLetter === this.selectedLetter
          })
        }
        return users
      },

      tableColumns()
      {
        var columns = [
          { label: 'Name', field: 'fullname', filterOptions: { enabled: true, placeholder: 'Search name' } },
          { label: 'Designation', field: 'designation_name', filterOptions: { enabled: true, placeholder: 'Search designation' } },
          { label: 'Status', field: 'status' },
        ];
        if (this.birthday == 'true')
        {
          columns.push({ label: 'Date of Birth', field: 'date_of_birth' });
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
