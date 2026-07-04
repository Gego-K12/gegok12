<template>
  <div>
    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
    <div class="my-4 filter-alphabet">
      <ul class="list-reset flex flex-wrap">
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
          :select-options="{ enabled: true, selectOnCheckboxOnly: true, selectionText: selectionText, clearSelectionText: 'clear' }"
          :pagination-options="{ enabled: true, perPage: 20 }"
          @selected-rows-change="onSelectionChanged"
        >
          <template #selected-row-actions>
            <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 text-sm font-medium" @click="sendMessage()">Send Message</a>
          </template>
          <template #table-row="props">
            <div v-if="props.column.field == 'fullname'">
              <teacher-name-cell :url="url" show-path="/admin/teacher/show/" :name="props.row.name" :avatar="props.row.avatar" :title="props.row.title" :fullname="props.row.fullname" :employee-id="props.row.employee_id" :joining-date="props.row.joining_date" :relieved-at="props.row.relieved_at"></teacher-name-cell>
            </div>
            <div v-else-if="props.column.field == 'designation_name'">
              {{ props.row.designation_name }}
            </div>
            <div v-else-if="props.column.field == 'status'">
              <span class="rounded-full px-2 py-1 text-xs font-semibold" v-bind:class="statusBadgeClass(props.row.status)">{{ statusLabel(props.row.status) }}</span>
            </div>
            <div v-else-if="props.column.field == 'class_teacher_of'">
              <span v-if="props.row.class_teacher_of">Class Teacher to: {{ props.row.class_teacher_of }}</span>
              <span v-else class="text-gray-400">&mdash;</span>
            </div>
            <div v-else-if="props.column.field == 'subject_teacher_of'">
              <div v-if="props.row.subject_teacher_of && props.row.subject_teacher_of.length">
                <div class="text-xs" v-for="item in props.row.subject_teacher_of" :key="item">{{ item }}</div>
              </div>
              <span v-else class="text-gray-400">&mdash;</span>
            </div>
            <div v-else-if="props.column.field == 'date_of_birth'">
              {{ props.row.date_of_birth }}
            </div>
          </template>
        </vue-good-table>
      </div>
    </div>
    <div v-if="this.send == 1" class="modal modal-mask">
      <div class="modal-wrapper px-4">
        <div class="modal-container w-full  max-w-md px-8 mx-auto">
          <div class="modal-header flex justify-between items-center">
            <h2>Send Message</h2>
            <button id="close-button" class="modal-default-button text-2xl py-1"  @click="closeModal()">
              &times;
            </button>

          </div>
          <div class="modal-body">
            <div class="flex flex-col lg:flex-row md:flex-row  lg:items-center">
              <div class="w-full lg:w-1/4">
                <label for="subject" class="tw-form-label">Subject</label>
              </div>
              <div class="my-2 w-full lg:w-3/4">
                <input type="text" name="subject" v-model="subject" class="tw-form-control w-full">
                <span v-if="errors.subject" class="text-red-500 text-xs font-semibold">{{errors.subject[0]}}</span>
              </div>
            </div>
          </div>
          <div class="modal-body">
            <div class="flex flex-col lg:flex-row md:flex-row lg:items-center">
              <div class="w-full lg:w-1/4">

                <label for="message" class="tw-form-label">Message</label>
              </div>
              <div class="w-full lg:w-3/4">
                <textarea type="text" name="message" v-model="message" class="tw-form-control w-full" rows="10"></textarea>
                <span v-if="errors.message" class="text-red-500 text-xs font-semibold">{{errors.message[0]}}</span>
              </div>
            </div>
          </div>
          <div class="modal-body">
            <div class="flex items-center">
              <div class="w-6">
                <input type="checkbox" name="send_later" v-model="send_later" class="tw-form-control w-full" @click="enableDate($event)">
              </div>
              <div class="mx-1">
                <label for="subject" class="tw-form-label">Send Later</label>
              </div>

            </div>
          </div>
          <div class="modal-body hidden" id="show_date">
            <div class="flex">
              <div class="w-full lg:w-1/4">
                  <label for="executed_at" class="tw-form-label">Date Time</label>
              </div>
              <div class="w-full lg:w-3/4">
                <VueDatePicker format="DD-MM-YYYY h:i:s" name="executed_at" v-model="executed_at" class="w-full rounded" id="executed_at">
                </VueDatePicker>
                <span v-if="errors.executed_at" class="text-red-500 text-xs font-semibold">{{errors.executed_at[0]}}</span>
              </div>
            </div>
          </div>
          <div class="my-6">
            <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submit()">Send</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

  import teacherNameCell from './NameCell';
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
          alphabets: [
          'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'
          ],
          selectedLetter: undefined,
          active: false,
          view: 'current',
          selected: [],
          selectedUsersCount:0,
          send_later:'',
          subject:'',
          message:'',
          executed_at:'',
          send:0,
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
          //console.log(users);
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
          { label: 'Primary Role', field: 'class_teacher_of' },
          { label: 'Subject Teacher to', field: 'subject_teacher_of', sortable: false },
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
      'teacher-name-cell': teacherNameCell,
      VueDatePicker,
      VueGoodTable,
    },

    methods:
    {
      getData()
      {
        var viewQuery = this.view == 'exit' ? '&view=exit' : '';
        axios.get('/admin/teachers/find?'+this.searchquery+viewQuery).then(response => {
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
        window.location.href = '/admin/teachers';
      },

      sortMembers(name)
      {
        this.selectedLetter= name;
        this.active = true;
        var q='alphabet='+this.selectedLetter;
        //var url = window.location.href;
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
        //console.log(url);
        window.location.href = url;
      },

      getUrl()
      {
        this.currenturl =  this.url+"/admin/teachers/";
        if(this.searchquery!='')
        {
          this.currenturl =  this.currenturl+'?'+this.searchquery;
        }
      },

      selectionText(count)
      {
        return count + (count == 1 ? ' teacher selected' : ' teachers selected');
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
          alert("Select Teachers")
        }
      },

      submit()
      {
        this.errors=[];
        axios.post('/admin/teacher/sendMessageToAll',{
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
    overflow:auto;
}

.modal-container {
  margin: 0px auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
  transition: all .3s ease;
/*  height: 550px;*/
  overflow:auto;
}

.modal-header h3 {
  margin-top: 0;
  color: #42b983;
}

.modal-body {
  margin: 20px 0;
}

.modal-default-button {
  float: right;
}

/*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

.modal-enter {
  opacity: 0;
}

.modal-leave-active {
  opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}

.text-danger
{
  color:red;
}




</style>
