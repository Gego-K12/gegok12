<template>
    <div>
        <ul class="list-reset flex text-xs profile-tab flex-wrap">
            <li class="px-2 mx-1 py-1" v-bind:class="[{'active' : status === 'pending'}]">
                <!-- <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('pending')" v-if="this.role == 'principal'">Waiting For Review</a> -->
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('pending')">Draft</a>
            </li>

            <li class="px-2 mx-1 py-1" v-bind:class="[{'active' : status === 'ongoing'}]">
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('ongoing')">Publish</a>
            </li>

            <!-- <li class="px-2 mx-1 py-1" v-bind:class="[{'active' : status === 'rejected'}]">
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('rejected')">Rejected</a>
            </li> -->
        </ul>

        <Teleport to="#list_assignment">
            <AssignmentList :url="this.url" :role="this.role" :type="this.type" :scope="this.scope" :hidecolumns="this.hidecolumns" :searchquery="this.searchquery"></AssignmentList>
        </Teleport>
    </div>
</template>

<script>
    
    import { bus } from "../../../app";
    import AssignmentList from './List';

    export default {
        props:['url' , 'role' , 'type' , 'scope' , 'hidecolumns', 'searchquery'],
        data () {
            return {
                status:'pending',     
            }
        },
        components: {
            AssignmentList,
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