<template>
    <div class="flex items-center">
        <img :src="avatar" class="w-10 h-10 rounded-full mr-2">
        <div>
            <a :href="url + showPath + name" class="font-semibold text-gray-700">{{ title }} {{ fullname }}</a>
            <p class="text-xs text-gray-500" v-if="employeeId">Employee ID: {{ employeeId }}</p>
            <p class="text-xs text-gray-500" v-if="joiningDate">Date of Join: {{ joiningDate }}</p>
            <p class="text-xs text-gray-500" v-if="relievedAt">Date of Relieve: {{ relievedAt }}</p>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['url', 'showPath', 'name', 'avatar', 'title', 'fullname', 'employeeId', 'joiningDate', 'relievedAt'],
    }
</script>
