<template>
  <div class="px-4 py-4" :class="[profile_tab == 14 ? 'block' : 'hidden']">

    <!-- Main Card -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

      <!-- Card Header -->
      <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50">
        <div class="flex items-center gap-2">
          <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5a1.99 1.99 0 011.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
          </svg>
          <h3 class="text-sm font-semibold text-gray-800 tracking-wide">Student Tags</h3>
        </div>
        <div class="flex items-center gap-2">
          <button
            @click="showModal('tag')"
            class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-lg border border-gray-300 text-gray-600 bg-white hover:bg-gray-50 hover:border-gray-400 transition-all"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            New Tag
          </button>
          <button
            @click="saveTags"
            :disabled="loading"
            class="inline-flex items-center gap-1.5 text-xs font-medium px-3 py-1.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all shadow-sm"
          >
            <svg v-if="!loading" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
            </svg>
            <svg v-else class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
            </svg>
            {{ loading ? 'Saving...' : 'Save Tags' }}
          </button>
        </div>
      </div>

      <!-- Alerts -->
      <div v-if="message" class="flex items-center gap-2 mx-5 mt-4 px-3 py-2.5 bg-green-50 border border-green-200 rounded-lg text-green-700 text-xs">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ message }}
      </div>
      <div v-if="error" class="flex items-center gap-2 mx-5 mt-4 px-3 py-2.5 bg-red-50 border border-red-200 rounded-lg text-red-700 text-xs">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ error }}
      </div>

      <div class="p-5 space-y-5">

        <!-- Selected Tags -->
        <div>
          <div class="flex items-center gap-2 mb-2.5">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Selected</span>
            <span
              v-if="selectedTagObjects.length"
              class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-blue-100 text-blue-700 text-[10px] font-bold"
            >{{ selectedTagObjects.length }}</span>
          </div>
          <div class="min-h-[42px] flex flex-wrap gap-2 p-3 bg-gray-50 border border-gray-200 rounded-lg">
            <span
              v-for="tag in selectedTagObjects"
              :key="'sel-' + tag.id"
              class="inline-flex items-center gap-1.5 bg-blue-600 text-white text-xs font-medium px-3 py-1 rounded-full shadow-sm"
            >
              {{ tag.tag_name }}
              <button
                type="button"
                @click.stop="toggleTag(tag.tag_name)"
                class="flex items-center justify-center w-4 h-4 rounded-full bg-blue-500 hover:bg-blue-400 transition-colors text-white leading-none"
                aria-label="Remove tag"
              >
                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </span>
            <span v-if="selectedTagObjects.length === 0" class="text-xs text-gray-400 italic self-center">
              No tags selected — click a tag below to add it
            </span>
          </div>
        </div>

        <!-- Available Tags -->
        <div>
          <div class="flex items-center justify-between mb-2.5">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Available Tags</span>
            <span class="text-xs text-gray-400">{{ availableTags.length }} tags</span>
          </div>
          <div
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2 overflow-y-auto pr-0.5"
            style="max-height: 340px;"
          >
            <button
              v-for="tag in availableTags"
              :key="'avail-' + tag.id"
              type="button"
              @click="toggleTag(tag.tag_name)"
              class="group flex items-center gap-2 border border-gray-200 rounded-lg px-3 py-2 text-xs text-gray-600 text-left bg-white hover:bg-blue-50 hover:border-blue-300 hover:text-blue-700 transition-all cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-200"
            >
              <svg class="w-3.5 h-3.5 text-gray-300 group-hover:text-blue-400 shrink-0 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
              </svg>
              {{ tag.tag_name }}
            </button>

            <div
              v-if="availableTags.length === 0"
              class="col-span-3 flex flex-col items-center justify-center py-8 text-gray-400"
            >
              <svg class="w-8 h-8 mb-2 opacity-40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h.01M15 12h.01M12 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <span class="text-xs">All available tags are selected</span>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- Modal Overlay -->
    <transition name="modal-fade">
      <div
        v-if="tab === 'tag'"
        class="fixed inset-0 z-50 flex items-center justify-center px-4"
        style="background: rgba(0,0,0,0.45); backdrop-filter: blur(2px);"
        @click.self="closeModal"
      >
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden">

          <!-- Modal Header -->
          <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div class="flex items-center gap-2">
              <div class="w-7 h-7 rounded-full bg-blue-100 flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5a1.99 1.99 0 011.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                </svg>
              </div>
              <h2 class="text-sm font-semibold text-gray-800">Add Tag to Student</h2>
            </div>
            <button
              @click="closeModal"
              class="w-7 h-7 flex items-center justify-center rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-600 transition-colors"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
              </svg>
            </button>
          </div>

          <div class="px-6 py-5 space-y-4">
            <!-- Existing Tag Select -->
            <div>
              <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Select Existing Tag</label>
              <select
                v-model="tag_name"
                class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition-all appearance-none"
                style="background-image: url('data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 fill=%22none%22 viewBox=%220 0 20 20%22><path stroke=%22%236b7280%22 stroke-width=%221.5%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22 d=%22M6 8l4 4 4-4%22/></svg>'); background-repeat: no-repeat; background-position: right 0.7rem center; background-size: 1.2em; padding-right: 2.5rem;"
              >
                <option value="">— Choose a tag —</option>
                <option v-for="tag in tags" :key="tag.id" :value="tag.tag_name">
                  {{ tag.tag_name }}
                </option>
              </select>
            </div>

            <!-- Divider with OR -->
            <div class="flex items-center gap-3">
              <div class="flex-1 h-px bg-gray-100"></div>
              <span class="text-xs font-medium text-gray-400">OR</span>
              <div class="flex-1 h-px bg-gray-100"></div>
            </div>

            <!-- Create New Tag Toggle -->
            <div>
              <button
                type="button"
                @click="showNewTag = !showNewTag"
                class="w-full flex items-center justify-between text-xs font-semibold text-gray-600 px-3 py-2 rounded-lg border border-dashed border-gray-300 hover:border-blue-300 hover:bg-blue-50 hover:text-blue-600 transition-all"
              >
                <span class="flex items-center gap-1.5">
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                  </svg>
                  Create a new tag
                </span>
                <svg
                  class="w-4 h-4 transition-transform"
                  :class="showNewTag ? 'rotate-180' : ''"
                  fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                </svg>
              </button>

              <transition name="slide-down">
                <div v-if="showNewTag" class="mt-2">
                  <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Tag Name</label>
                  <input
                    type="text"
                    v-model="new_tag_name"
                    placeholder="e.g. Needs Extra Support"
                    class="w-full text-sm border border-gray-200 rounded-lg px-3 py-2 text-gray-700 placeholder-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 transition-all"
                  />
                </div>
              </transition>
            </div>

            <!-- Validation error -->
            <p v-if="errors.tag_name" class="text-xs text-red-500 font-medium">
              {{ errors.tag_name[0] }}
            </p>
          </div>

          <!-- Modal Footer -->
          <div class="flex items-center justify-end gap-2 px-6 py-4 bg-gray-50 border-t border-gray-100">
            <button
              type="button"
              @click="closeModal"
              class="text-xs font-medium px-4 py-2 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-100 transition-all"
            >
              Cancel
            </button>
            <button
              type="button"
              @click="submitTag"
              class="text-xs font-medium px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-all shadow-sm"
            >
              Save Tag
            </button>
          </div>

        </div>
      </div>
    </transition>

  </div>
</template>

<script>
import { bus } from "../../../app";

export default {
  props: ["url", "name", "entity_id", "school_id", "mode"],

  data() {
    return {
      profile_tab: "1",
      tags: [],
      selectedTags: [],
      loading: false,
      message: "",
      error: "",
      tab: "",
      tag_name: "",
      new_tag_name: "",
      showNewTag: false,
      errors: {},
      success: null,
    };
  },

  computed: {
    selectedTagObjects() {
      return this.tags.filter(tag =>
        this.selectedTags.includes(tag.tag_name)
      );
    },
    availableTags() {
      return this.tags.filter(tag =>
        !this.selectedTags.includes(tag.tag_name)
      );
    },
  },

  created() {
    bus.on("dataProfileTab", data => {
      if (data !== "") this.profile_tab = data;
    });
  },

  mounted() {
    this.getTags();
    this.getStudentTags();
  },

  methods: {
    showModal(value) {
      this.tab = value;
    },

    closeModal() {
      this.tab = "";
      this.tag_name = "";
      this.new_tag_name = "";
      this.showNewTag = false;
      this.errors = {};
    },

    submitTag() {
      this.errors = {};
      const name = this.new_tag_name.trim() || this.tag_name;

      if (!name) {
        this.errors = { tag_name: ["Please select or enter a tag name."] };
        return;
      }

      axios.post(this.url + "/admin/tags/add-students", {
        tag_name: name,
        selectedUsers: [this.entity_id],
      })
      .then(response => {
        this.success = response.data.message;
        this.closeModal();
        this.getTags();
        this.getStudentTags();
      })
      .catch(error => {
        this.errors = error.response?.data?.errors || {};
      });
    },

    getTags() {
      axios
        .get(this.url + "/admin/student-tags")
        .then(response => {
          this.tags = response.data.tags;
        })
        .catch(() => {
          this.error = "Unable to load tags.";
        });
    },

    getStudentTags() {
      axios
        .get(this.url + "/admin/student/" + this.entity_id + "/tags")
        .then(response => {
          this.selectedTags = response.data.tags;
        })
        .catch(() => {
          this.error = "Unable to load student tags.";
        });
    },

    toggleTag(tagName) {
      const index = this.selectedTags.indexOf(tagName);
      if (index > -1) {
        this.selectedTags.splice(index, 1);
      } else {
        this.selectedTags.push(tagName);
      }
    },

    saveTags() {
      this.loading = true;
      this.message = "";
      this.error = "";

      axios
        .post(this.url + "/admin/student/" + this.entity_id + "/tags", {
          tags: this.selectedTags,
        })
        .then(response => {
          this.message = response.data.message || "Tags saved successfully.";
          setTimeout(() => { this.message = ""; }, 3000);
        })
        .catch(error => {
          this.error = error.response?.data?.message || "Unable to save tags.";
        })
        .finally(() => {
          this.loading = false;
        });
    },
  },
};
</script>

<style scoped>
/* Modal fade transition */
.modal-fade-enter-active,
.modal-fade-leave-active {
  transition: opacity 0.2s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
  opacity: 0;
}
.modal-fade-enter-active .bg-white,
.modal-fade-leave-active .bg-white {
  transition: transform 0.2s ease;
}
.modal-fade-enter-from .bg-white {
  transform: scale(0.95) translateY(8px);
}

/* Slide-down for new tag input */
.slide-down-enter-active,
.slide-down-leave-active {
  transition: all 0.2s ease;
  overflow: hidden;
}
.slide-down-enter-from,
.slide-down-leave-to {
  opacity: 0;
  max-height: 0;
}
.slide-down-enter-to,
.slide-down-leave-from {
  opacity: 1;
  max-height: 100px;
}

/* Smooth spinner */
@keyframes spin {
  to { transform: rotate(360deg); }
}
.animate-spin {
  animation: spin 0.8s linear infinite;
}
</style>