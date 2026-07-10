<template>
    <div>
        <ul class="list-reset flex text-xs profile-tab flex-wrap">
            <li class="px-2 mx-1 py-1" v-bind:class="[{'active' : fee_type === 'structural'}]">
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('structural')">Structural</a>
            </li>

            <li class="px-2 mx-1 py-1" v-bind:class="[{'active' : fee_type === 'non_structural'}]">
                <!-- <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('non_structural')">Non Structural</a> -->
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('non_structural')">Other Fees</a>
            </li>
        </ul>

        <Teleport to="#add_fee">
            <Structural :url="this.url" :mode="this.mode"></Structural>
            <NonStructural :url="this.url" :mode="this.mode"></NonStructural>
        </Teleport>
    </div>
</template>

<script>
    import { bus } from "../../app";
    import Structural from './Structural';
    import NonStructural from './NonStructural';
    export default {
        props:['url' , 'mode'],
        data () {
            return {
                fee_type:'structural',     
            }
        },
        components: {
            Structural,
            NonStructural,
        },

        methods:
        {
            setProfileTab(val)
            {
                this.fee_type=val;
                bus.emit("statusTab", this.fee_type);
            }
        },

        created()
        {
            bus.emit("statusTab", this.fee_type);
       
            bus.on("statusTab", data => {
                if(data!='')
                {
                    this.fee_type=data;                   
                }
            });
        }
    }
</script>