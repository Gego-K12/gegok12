<template>
    <div>
        <ul class="list-reset flex text-sm profile-tab flex-wrap">
            <li class="px-2 mx-1 lg:mx-2 md:mx-2 py-2 lg:py-3 md:py-2" v-bind:class="[{'active' : profile_tab === '1'}]">
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('1')">Notice Board</a>
            </li>
            
            <li v-if="gtimetableEnabled" class="px-2 mx-1 lg:mx-2 md:mx-2 py-2 lg:py-3 md:py-2" v-bind:class="[{'active' : profile_tab === '2'}]">
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('2')">Time Table</a>
            </li>
            <li class="px-2 mx-1 lg:mx-2 md:mx-2  py-2 lg:py-3 md:py-2" v-bind:class="[{'active' : profile_tab === '3'}]">
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('3')">Homeworks</a>
            </li>
            <li class="px-2 mx-1 lg:mx-2 md:mx-2 py-2 lg:py-3 md:py-2" v-bind:class="[{'active' : profile_tab === '4'}]">
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('4')">Teachers</a>
            </li>
            <li class="px-2 mx-1 lg:mx-2 md:mx-2 py-2 lg:py-3 md:py-2" v-bind:class="[{'active' : profile_tab === '5'}]">
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('5')">List Of Students</a>
            </li>
            <li class="px-2 mx-1 lg:mx-2 md:mx-2 py-2 lg:py-3 md:py-2" v-bind:class="[{'active' : profile_tab === '6'}]">
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('6')">Attendance</a>
            </li>
            <li v-if="gexamEnabled" class="px-2 mx-1 lg:mx-2 md:mx-2 py-2 lg:py-3 md:py-2" v-bind:class="[{'active' : profile_tab === '7'}]">
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('7')">Upcoming Exams</a>
            </li>
            <li v-if="gexamEnabled" class="px-2 mx-1 lg:mx-2 md:mx-2 py-2 lg:py-3 md:py-2" v-bind:class="[{'active' : profile_tab === '8'}]">
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('8')">Past Exams</a>
            </li>
            <li class="px-2 mx-1 lg:mx-2 md:mx-2 py-2 lg:py-3 md:py-2" v-bind:class="[{'active' : profile_tab === '9'}]">
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('9')">Events</a>
            </li>
            <li v-if="gfeeEnabled" class="px-2 mx-1 lg:mx-2 md:mx-2 py-2 lg:py-3 md:py-2" v-bind:class="[{'active' : profile_tab === '10'}]">
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('10')">Fees</a>
            </li>
            <li class="px-2 mx-1 lg:mx-2 md:mx-2 py-2 lg:py-3 md:py-2" v-bind:class="[{'active' : profile_tab === '11'}]" v-if="this.mode == 'admin'">
                <a href="#" class="text-gray-700 font-medium"  @click="setProfileTab('11')">Notes</a>
            </li>
            <li class="px-2 mx-1 lg:mx-2 md:mx-2 py-2 lg:py-3 md:py-2" v-bind:class="[{'active' : profile_tab === '12'}]">
                <a href="#" class="text-gray-700 font-medium"  @click="setProfileTab('12')">WallBoard</a>
            </li>
             <li v-if="gvideoroomEnabled && this.mode == 'admin'"  class="px-2 mx-1 lg:mx-2 md:mx-2 py-2 lg:py-3 md:py-2" v-bind:class="[{'active' : profile_tab === '13'}]">
                <a href="#" class="text-gray-700 font-medium"  @click="setProfileTab('13')">Online Class</a>
            </li>
            <li v-if="this.mode == 'admin'"  class="px-2 mx-1 lg:mx-2 md:mx-2 py-2 lg:py-3 md:py-2" v-bind:class="[{'active' : profile_tab === '14'}]">
                <a href="#" class="text-gray-700 font-medium"  @click="setProfileTab('14')">Groups</a>
            </li>
        </ul>
        <Teleport to="#class">
            <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3" v-bind:class="[this.profile_tab==1?'block' :'hidden']">
                <notice-board-list :url="this.url" :scope="this.id" :hidecolumns="true" :mode="this.mode"></notice-board-list>
            </div>
            <timetable v-if="gtimetableEnabled" :url="this.url" :id="this.id" :mode="this.mode"></timetable>
            <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3" v-bind:class="[this.profile_tab==3?'block' :'hidden']">
                <home-work-list :url="this.url" :scope="this.id" :hidecolumns="true" :searchquery="null" :mode="this.mode"></home-work-list>
            </div>
            <teachers :url="this.url" :id="this.id" :mode="this.mode"></teachers>
            <students :url="this.url" :id="this.id" :mode="this.mode"></students>
            <attendance :url="this.url" :id="this.id" :mode="this.mode"></attendance>
            <upcomingExams v-if="gexamEnabled" :url="this.url" :id="this.id" :mode="this.mode"></upcomingExams>
            <pastExams v-if="gexamEnabled" :url="this.url" :id="this.id" :mode="this.mode"></pastExams>
            <events :url="this.url" :id="this.id" :mode="this.mode"></events>
            <fees v-if="gfeeEnabled" :url="this.url" :id="this.id" :mode="this.mode"></fees>
            <wallBoard :url="this.url" :id="this.id" :mode="this.mode" :auth_id="this.auth_id"></wallBoard>
            <groups :url="this.url"
                    :id="this.id"
                    :mode="this.mode">
            </groups>
        </Teleport>
        <Teleport to="#notes">
            <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3" v-bind:class="[this.profile_tab==11?'block' :'hidden']">
                <notes :url="this.url" :entity_id="this.id" entity_name="class" :school_id="this.school_id"></notes>
            </div>
        </Teleport>
        <conference v-if="gvideoroomEnabled" :url="this.url" :id="this.id" :mode="this.mode"></conference>
    </div>
</template>

<script>
    
    import { bus } from "../../../app";
    import NoticeList from '../../noticeboard/List';
    import timetable from './timetable';
    import HomeWorkList from '../../homework/List';
    import PostList from '../../classwall/post/List';
    import teachers from './teachers';
    import students from './students';
    import attendance from './attendance';
    import upcomingExams from './upcomingExams';
    import pastExams from './pastExams';
    import events from './events';
    import fees from './fees';
    import wallBoard from './wallBoard';
    import notes from '../../notes';
    import conference from './conference';
    import groups from './groups';
 
    export default {
        props:['url' , 'id' , 'school_id' , 'mode' ,'auth_id'],
        data () {
            return {
                profile_tab:'1', 
                gtimetableEnabled: false,
                gexamEnabled: false,
                gfeeEnabled: false,
                gvideoroomEnabled: false,    
            }
        },
        mounted() {
              this.gtimetableEnabled = window.AppConfig?.gtimetable_enabled ?? false;
              this.gexamEnabled = window.AppConfig?.gexam_enabled ?? false;
              this.gfeeEnabled = window.AppConfig?.gfee_enabled ?? false;
              this.gvideoroomEnabled = window.AppConfig?.gvideoroom_enabled ?? false;
            },

        components: {
            NoticeList,
            timetable,
            HomeWorkList,
            teachers,
            students,
            attendance,
            upcomingExams,
            pastExams,
            events,
            fees,
            notes,
            wallBoard,
            conference,
            groups
        },

        methods:
        {
            setProfileTab(val)
            {
                this.profile_tab=val;
                bus.emit("dataProfileTab", this.profile_tab);
            }
        },

        created()
        {
            bus.emit("dataProfileTab", this.profile_tab);
       
            bus.on("dataProfileTab", data => {
                if(data!='')
                {
                    this.profile_tab=data;                     
                }
            });   
        }
    }
</script>