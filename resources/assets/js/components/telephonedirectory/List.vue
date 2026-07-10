<template>
  <div class="relative">
    <div class="flex flex-wrap lg:flex-row justify-between items-center">
            <div class="">
                <h1 class="admin-h1 my-3">Telephone Directory</h1>
            </div>
            <div class="relative flex items-center w-8/12 lg:w-1/4 md:w-1/4 justify-end">
                <div class="flex items-center w-full  justify-end">
                    <a :href="'/'+this.mode+'/phonenumber/add'" class="no-underline text-white px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center">
                        <span class="mx-1 text-sm font-semibold whitespace-no-wrap">Add Phone Number</span>
                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 409.6 409.6" xml:space="preserve" class="w-3 h-3 fill-current text-white"><g><g><path d="M392.533,187.733H221.867V17.067C221.867,7.641,214.226,0,204.8,0s-17.067,7.641-17.067,17.067v170.667H17.067 C7.641,187.733,0,195.374,0,204.8s7.641,17.067,17.067,17.067h170.667v170.667c0,9.426,7.641,17.067,17.067,17.067 s17.067-7.641,17.067-17.067V221.867h170.667c9.426,0,17.067-7.641,17.067-17.067S401.959,187.733,392.533,187.733z"></path></g></g></svg>
                    </a> 
                </div>
            </div>
        </div>

    <vue-good-table
      :columns="columns"
      :rows="rows"
      :total-rows="rows.length"
      :is-loading="isLoading"
      :pagination-options="{
        enabled: true,
        perPage: 10,
        mode: 'pages'
      }"
      :search-options="{
        enabled: true,
        placeholder: 'Search table'
      }"
    >

      <!-- Vue 3 SLOT FIX -->
      <template #table-row="props">

        <!-- Name -->
        <span v-if="props.column.field === 'name'">
          {{ props.row.name }}
        </span>

        <!-- Designation -->
        <span v-else-if="props.column.field === 'designation'">
          {{ props.row.designation }}
        </span>

        <!-- Phone -->
        <span v-else-if="props.column.field === 'phone_number'">
          {{ props.row.phone_number }}
        </span>

        <!-- Actions -->
        <span v-else-if="props.column.field === 'action'">
          <div v-if="props.row.type === 'telephone_directory'" class="flex items-center gap-2">

            <a :href="'/' + mode + '/phonenumber/edit/' + props.row.id">
              Edit
            </a>

            <button @click="deleteNumber(props.row.id)">
              Delete
            </button>

          </div>
        </span>

      </template>

    </vue-good-table>

  </div>
</template>

<script>
import { VueGoodTable } from "vue-good-table-next";
import "vue-good-table-next/dist/vue-good-table-next.css";
import axios from "axios";

export default {
  props: ["url", "mode"],

  components: {
    VueGoodTable,
  },

  data() {
    return {
      isLoading: false,
      rows: [],
      success: null,

      columns: [
        {
          label: "Name",
          field: "name",
          filterOptions: {
            enabled: true,
            placeholder: "Search Name",
          },
        },
        {
          label: "Designation",
          field: "designation",
          filterOptions: {
            enabled: true,
            placeholder: "Search Designation",
          },
        },
        {
          label: "Phone Number",
          field: "phone_number",
          filterOptions: {
            enabled: true,
            placeholder: "Search Phone",
          },
        },
        {
          label: "Actions",
          field: "action",
        },
      ],
    };
  },

  mounted() {
    this.getData();
  },

  methods: {
    async getData() {
      this.isLoading = true;
      try {
        const response = await axios.get(
          "/" + this.mode + "/phonenumber/list"
        );
        this.rows = response.data.data;
      } catch (error) {
        console.error(error);
      }
      this.isLoading = false;
    },

    async deleteNumber(id) {
      if (!confirm("Are you sure you want to delete?")) return;

      try {
        await axios.delete(
          this.url + "/" + this.mode + "/phonenumber/delete/" + id
        );
        this.getData(); // refresh table without reload
      } catch (error) {
        console.error(error);
      }
    },
  },
};
</script>