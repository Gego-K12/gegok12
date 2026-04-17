<template>
    <div>
        <ul class="list-reset flex text-xs profile-tab flex-wrap">
            <!-- <li class="px-2 mx-1 py-1" v-bind:class="[{'active' : status === 'pending'}]">
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('pending')" v-if="this.role == 'admin'">Waiting For Review</a>
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('pending')" v-else>Pending</a>
            </li> -->

            <li class="px-2 mx-1 py-1" v-bind:class="[{'active' : status === 'draft'}]">
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('draft')">Draft</a>
            </li>

            <li class="px-2 mx-1 py-1" v-bind:class="[{'active' : status === 'publish'}]">
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('publish')">Published</a>
            </li>
        </ul>

        <Teleport to="#list_homework">
            <HomeworkList :url="this.url" :role="this.role" :mode="this.mode" :scope="this.scope" :hidecolumns="this.hidecolumns" :searchquery="this.searchquery"></HomeworkList>
        </Teleport>
    </div>
</template>

<script>
    
    import { bus } from "../../../app";
    import HomeworkList from './List';

    export default {
        props:['url' , 'role' , 'mode' , 'scope' , 'hidecolumns', 'searchquery'],
        data () {
            return {
                status:'draft',     
            }
        },
        components: {
            HomeworkList,
        },

        methods:
        {
            setProfileTab(val)
            {
                this.status=val;
                bus.emit("statusTab", this.status);
            }
        },

        created()
        {
            bus.emit("statusTab", this.status);
       
            bus.on("statusTab", data => {
                if(data!='')
                {
                    this.status=data;                   
                }
            });
        }
    }
</script>