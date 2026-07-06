<template>
    <div>
        <ul class="list-reset flex text-xs profile-tab flex-wrap">
            <li class="px-2 mx-1 py-1" v-bind:class="[{'active' : status === 'structural'}]">
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('structural')">Structural</a>
            </li>

            <li class="px-2 mx-1 py-1" v-bind:class="[{'active' : status === 'non_structural'}]">
                <!--<a href="#" class="text-gray-700 font-medium" @click="setProfileTab('non_structural')">Non Structural</a> -->
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('non_structural')">Other Fees</a>
            </li>
        </ul>

        <Teleport to="#list_fee">
            <List :url="this.url" :mode="this.mode"></List>
        </Teleport>
    </div>
</template>

<script>
    import { bus } from "../../app";
    import List from './List';
    export default {
        props:['url' , 'mode'],
        data () {
            return {
                status:'structural',     
            }
        },
        components: {
            List,
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