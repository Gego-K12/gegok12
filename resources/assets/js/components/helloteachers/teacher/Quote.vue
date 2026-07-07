<template>
  <div class="bg-white shadow px-6 py-8 max-w-xl">
    <div v-if="loading" class="text-sm text-gray-500">Loading...</div>
    <blockquote v-else class="text-lg italic text-gray-700">
      "{{ text }}"
      <footer class="mt-3 text-sm text-gray-500 not-italic" v-if="author">— {{ author }}</footer>
    </blockquote>

    <div class="mt-6">
      <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 text-sm font-medium" @click.prevent="fetchQuote">
        Show another
      </a>
    </div>
  </div>
</template>

<script>
  export default {
    props: ['url'],
    data() {
      return {
        text: '',
        author: '',
        loading: true,
      }
    },

    methods: {
      fetchQuote() {
        this.loading = true;
        axios.get('/teacher/helloteachers/quote').then(response => {
          this.text = response.data.text;
          this.author = response.data.author;
          this.loading = false;
        });
      },
    },

    created() {
      this.fetchQuote();
    },
  }
</script>
