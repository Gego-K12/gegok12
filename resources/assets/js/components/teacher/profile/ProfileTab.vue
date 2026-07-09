<template>
  <div>
    <ul class="list-reset flex text-xs profile-tab flex-wrap">
      <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '1'}]">
        <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('1')">Profile</a>
      </li>

      <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '2'}]">
        <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('2')">Timeline</a>
      </li>

      <li v-if="this.mode=='teacher'" class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '3'}]">
        <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('3')">Assigned Classes</a>
      </li>

      <li v-if="this.mode=='teacher'" class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '4'}]">
        <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('4')">Leave History</a>
      </li>

      <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '5'}]">
        <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('5')">Notes</a>
      </li>

      <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '6'}]">
        <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('6')">Documents</a>
      </li>
      <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '7'}]">
        <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('7')">Bank Details</a>
      </li>
      <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '8'}]">
        <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('8')">Attendance</a>
      </li>
      <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '9'}]">
        <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('9')">Library Activities</a>
      </li>

      <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '10'}]">
        <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('10')">Qualification</a>
      </li>

    </ul>

    <Teleport to="#teacherprofile">
      <myprofile :url="this.url" :name="this.name"></myprofile>
      <timeline :url="this.url" :name="this.name"></timeline>
      <classes :url="this.url" :name="this.name"></classes>
      <leaveHistory :url="this.url" :name="this.name"></leaveHistory>
      <documents :url="this.url" :name="this.name"></documents>
      <attendance :url="this.url" :name="this.name"></attendance>
      <bankdetails :url="this.url" :name="this.name"></bankdetails>
      <libraryactivity :url="this.url" :name="this.name"></libraryactivity>
      <qualification :url="this.url" :name="this.name"></qualification>
      <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3" v-bind:class="[this.profile_tab==5?'block' :'hidden']">
        <notes :url="this.url" :entity_id="this.entity_id" entity_name="user" :school_id="this.school_id"></notes>
      </div>
    </Teleport>
  </div>
</template>

<script>
  import {
    bus
  } from "../../../app";
  import notes from '../../notes';
  import myprofile from './myprofile';
  import timeline from './timeline';
  import classes from './classes';
  import leaveHistory from './leaveHistory';
  import documents from './documents';
  import bankdetails from './bankdetails';
  import attendance from './attendance';
  import libraryactivity from './libraryactivity';
  import qualification from './qualification';

  export default {
    props: ['url', 'name', 'entity_id', 'school_id', 'mode'],
    data() {
      return {
        profile_tab: '1',
      }
    },
    components: {
      myprofile,
      timeline,
      notes,
      classes,
      leaveHistory,
      documents,
      bankdetails,
      attendance,
      libraryactivity,
      qualification
    },

    methods: {
      setProfileTab(val) {
        this.profile_tab = val;
        bus.emit("dataProfileTab", this.profile_tab);
      }
    },

    created() {
      bus.emit("dataProfileTab", this.profile_tab);

      bus.on("dataProfileTab", data => {
        if (data != '') {
          this.profile_tab = data;
        }
      });
    }
  }
</script>