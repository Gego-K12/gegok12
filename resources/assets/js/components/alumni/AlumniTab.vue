<template>
    <div>
        <ul class="list-reset flex text-xs profile-tab flex-wrap">
            <li class="px-2 mx-1 lg:mx-3 py-2" v-bind:class="[{'active' : profile_tab === '1'}]">
                <!-- <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('1')">Profile</a> -->
                <a href="#" class="text-gray-700 font-medium">Profile</a>
            </li>

            <li class="px-2 mx-1 lg:mx-3 py-2" v-bind:class="[{'active' : profile_tab === '2'}]">
                <!-- <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('2')">Educational Qualification</a> -->
                <a href="#" class="text-gray-700 font-medium">Educational Qualification</a>
            </li>

            <li class="px-2 mx-1 lg:mx-3 py-2" v-bind:class="[{'active' : profile_tab === '3'}]">
                <!-- <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('3')">Notes(Optional)</a> -->
                <a href="#" class="text-gray-700 font-medium">Work Experience</a>
            </li>

            <li class="px-2 mx-1 lg:mx-3 py-2" v-bind:class="[{'active' : profile_tab === '4'}]">
                <!-- <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('4')">Address</a> -->
                <a href="#" class="text-gray-700 font-medium">About</a>
            </li>
        </ul>

        <Teleport to="#add_alumniform" v-if="this.type == 'add'">
            <alumni-personal :url="this.url" :type="this.type"></alumni-personal>
            <alumni-education :url="this.url" :type="this.type"></alumni-education>
            <alumni-job :url="this.url" :type="this.type"></alumni-job>
            <alumni-contact :url="this.url" :type="this.type"></alumni-contact>
        </Teleport>

        <Teleport to="#edit_alumniform" v-if="this.type == 'edit'">
            <edit-alumni :url="this.url" :type="this.type"></edit-alumni>
            <alumni-education :url="this.url" :type="this.type"></alumni-education>
            <alumni-job :url="this.url" :type="this.type"></alumni-job>
            <alumni-contact :url="this.url" :type="this.type"></alumni-contact>
        </Teleport>
    </div>
</template>

<script>
    import { bus } from "../../app";
    import alumniPersonal from './AlumniPersonal';
    import alumniEducation from './AlumniEducation';
    import alumniJob from './AlumniJob';
    import alumniContact from './AlumniContact';
    import editAlumni from './Edit';
    export default {
        props:['url' , 'type'],
        data () {
            return {
                profile_tab:'1',     
            }
        },
        components: {
            alumniPersonal,
            alumniEducation,
            alumniJob,
            alumniContact,
            editAlumni,
        },

        methods:
        {
            setProfileTab(val)
            {
                this.profile_tab=val;
                bus.$emit("dataAlumniTab", this.profile_tab);
            }
        },

        created()
        {
            bus.$emit("dataAlumniTab", this.profile_tab);
       
            bus.$on("dataAlumniTab", data => {
                if(data!='')
                {
                    this.profile_tab=data;                   
                }
            });   
        }
    }
</script>