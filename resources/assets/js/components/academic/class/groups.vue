<template>
    <div
        class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto"
        :class="profile_tab == '14' ? 'block' : 'hidden'"
    >
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Groups</h2>

            <button
                class="bg-blue-500 text-white px-4 py-2 rounded"
                @click="getGroups"
            >
                Refresh
            </button>
        </div>

        <!-- Loading -->
        <div v-if="loading" class="text-center py-5">
            Loading...
        </div>

        <!-- Table -->
        <div v-else>
            <table class="table-auto w-full border">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-2 text-left">#</th>
                        <th class="border px-4 py-2 text-left">
                            Group Name
                        </th>
                        <!-- <th class="border px-4 py-2 text-left">
                            Students Count
                        </th> -->
                    </tr>
                </thead>

                <tbody>
                    <tr v-if="groups.length === 0">
                        <td colspan="2" class="text-center py-4">
                            No Groups Found
                        </td>
                    </tr>

                    <tr
                        v-for="(group, index) in groups"
                        :key="group.id"
                    >
                        <td class="border px-4 py-2">
                            {{ index + 1 }}
                        </td>

                        <td class="border px-4 py-2">
                            {{ group.group_name }}
                        </td>

                        <!-- <td class="border px-4 py-2">
                            {{ group.members_count }}
                        </td> -->
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
  import { bus } from "../../../app";

export default {

    props: {
        url: String,
        id: Number,
        mode: String,
        profile_tab: Number
    },

    data() {
        return {
            loading: false,
            groups: [],
            profile_tab:'',
        };
    },

    methods: {

        async getGroups()
        {
            this.loading = true;

            try {

                const response = await axios.get(
                    '/' + this.mode + '/groups/' + this.id
                );

                this.groups = response.data.data || response.data;

            } catch (error) {

                console.log(error);

            } finally {

                this.loading = false;
            }
        }
    },
    created()
  {
      this.getGroups();

      bus.on("dataProfileTab", data => {

          if(data != '')
          {
              this.profile_tab = data;
          }
      });
  }
};
</script>