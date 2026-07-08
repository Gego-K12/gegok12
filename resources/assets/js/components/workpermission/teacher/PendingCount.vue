<template>
    <span v-if="count > 0">( {{ count }} )</span>
</template>

<script>
    export default {
        data() {
            return {
                count: 0,
            }
        },

        methods: {
            getCount() {
                axios.get('/teacher/workpermission/pendingCount').then(response => {
                    this.count = response.data;
                });
            },
        },

        created() {
            this.getCount();
        },
    }
</script>
