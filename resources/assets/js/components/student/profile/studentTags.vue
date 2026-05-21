<template>
  <div class="px-3 py-3" v-bind:class="[profile_tab == 14 ? 'block' : 'hidden']">
    <div class="bg-white shadow rounded px-4 py-4">
      <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-semibold text-gray-700">Student Tags</h3>

        <button
          class="bg-blue-500 text-white px-3 py-1 rounded text-xs"
          @click="saveTags"
          :disabled="loading"
        >
          {{ loading ? 'Saving...' : 'Save Tags' }}
        </button>
      </div>

      <div v-if="message" class="mb-3 text-green-600 text-xs">
        {{ message }}
      </div>

      <div v-if="error" class="mb-3 text-red-600 text-xs">
        {{ error }}
      </div>

      <div class="grid grid-cols-2 md:grid-cols-4 gap-2 overflow-y-auto pr-2" style="max-height: 350px;">
        <label
          v-for="tag in tags"
          :key="tag.id"
          class="flex items-center text-xs border rounded px-2 py-2 cursor-pointer"
        >
          <input
            type="checkbox"
            class="mr-2"
            :value="tag.tag_name"
            v-model="selectedTags"
          >
          {{ tag.tag_name }}
        </label>
      </div>
    </div>
  </div>
</template>

<script>
import { bus } from "../../../app";

export default {
  props: ['url', 'name', 'entity_id', 'school_id', 'mode'],

  data() {
    return {
      profile_tab: '1',
      tags: [],
      selectedTags: [],
      loading: false,
      message: '',
      error: ''
    }
  },

  created() {
    bus.on("dataProfileTab", data => {
      if (data !== '') {
        this.profile_tab = data;
      }
    });
  },

  mounted() {
    this.getTags();
    this.getStudentTags();
  },

  methods: {
    getTags() {
      axios.get(this.url + '/admin/student-tags')
        .then(response => {
          this.tags = response.data.tags;
        })
        .catch(() => {
          this.error = 'Unable to load tags';
        });
    },

    getStudentTags() {
      axios.get(this.url + '/admin/student/' + this.entity_id + '/tags')
        .then(response => {
          this.selectedTags = response.data.tags;
        })
        .catch(() => {
          this.error = 'Unable to load student tags';
        });
    },

    saveTags() {
      this.loading = true;
      this.message = '';
      this.error = '';

      axios.post(this.url + '/admin/student/' + this.entity_id + '/tags', {
        tags: this.selectedTags
      })
      .then(response => {
        this.message = response.data.message;
      })
      .catch(error => {
        this.error = error.response?.data?.message || 'Unable to save tags';
      })
      .finally(() => {
        this.loading = false;
      });
    }
  }
}
</script>