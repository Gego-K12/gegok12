<template>
  <div>
    <ul class="list-reset flex text-xs profile-tab flex-wrap">
      <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '1'}]" >
        <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('1')">Profile</a>
      </li>
      <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '2'}]" >
        <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('2')">Timeline</a>
      </li>
 
      <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '3'}]">
        <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('3')">Parents / Guardians</a>
      </li>
 
      <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '4'}]">
        <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('4')">Siblings</a>
      </li>

      <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '5'}]">
        <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('5')">Disciplines</a>
      </li>
 
      <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '6'}]" v-if="this.mode == 'admin'">
        <a href="#" class="text-gray-700 font-medium"  @click="setProfileTab('6')">Notes</a>
      </li>

      <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '7'}]">
        <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('7')">Library Activities</a>
      </li>

      <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '8'}]">
        <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('8')">Documents</a>
      </li>

      <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '9'}]">
        <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('9')">Attendances</a>
      </li>

      <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '10'}]">
        <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('10')">Medical History</a>
      </li>

            <li v-if="gfeeEnabled" class="px-2 mx-2 py-2" v-bind:class="[{'active' : profile_tab === '11'}]">
                <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('11')">Fees Record</a>
            </li>

            <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '12'}]">
        <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('12')">Leave History</a>
      </li>
       <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '13'}]">
        <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('13')">Bank Details</a>
      </li>
      <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : profile_tab === '14'}]">
      <a href="#" class="text-gray-700 font-medium" @click="setProfileTab('14')">Tags</a>
    </li>

    </ul>
    <Teleport to="#profile">
      <myprofile :url="this.url" :name="this.name" :mode="mode"></myprofile>
      <timeline :url="this.url" :name="this.name" :mode="mode"></timeline>
      <family :url="this.url" :name="this.name" :mode="mode"></family>
      <siblings :url="this.url" :name="this.name" :mode="mode"></siblings>
      <discipline :url="this.url" :name="this.name" :mode="mode"></discipline>
      <libraryactivity :url="this.url" :name="this.name" :mode="mode"></libraryactivity>
      <documents :url="this.url" :name="this.name" :mode="mode"></documents>
      <attendances :url="this.url" :name="this.name" :mode="mode"></attendances>
      <medicalHistory :url="this.url" :name="this.name" :mode="mode"></medicalHistory>
            <fees :url="this.url" :name="this.name" :mode="mode"></fees>
            <leaveHistory :url="this.url" :name="this.name" :mode="mode"></leaveHistory>
             <bankdetails :url="this.url" :name="this.name" :mode="mode"></bankdetails>
             <studentTags 
  :url="this.url" 
  :name="this.name" 
  :entity_id="this.entity_id" 
  :school_id="this.school_id" 
  :mode="mode">
</studentTags>
    </Teleport>
    <Teleport to="#notes">
      <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3" v-bind:class="[this.profile_tab==6?'block' :'hidden']">
        <notes :url="this.url" :entity_id="this.entity_id" entity_name="user" :school_id="this.school_id"></notes>
      </div>    
    </Teleport>
  </div>
</template>

<script>
  import { bus } from "../../../app";
  import notes from '../../notes';
  import myprofile from './myprofile';
  import timeline from './timeline';
  import discipline from './discipline';
  import libraryactivity from './libraryactivity';
  import documents from './documents';
  import family from './family';
  import siblings from './siblings';
  import attendances from './attendance';
  import medicalHistory from './medicalHistory';
  import leaveHistory from './leaveHistory';
  import bankdetails from './bankdetails';
  import fees from './fees';
  import studentTags from './studentTags';
 
  export default {
    props:['url','name','entity_id','school_id','mode'],
    data () {
      return {
        profile_tab:'1', 
        gfeeEnabled: false    
      }
    },
    mounted() {
      this.gfeeEnabled = window.AppConfig?.gfee_enabled ?? false;

    },
    components: {
      myprofile,
      timeline,
      notes,
      discipline,
      libraryactivity,
      documents,
      family,
      siblings,
      attendances,
      medicalHistory,
      fees,
      leaveHistory,
      bankdetails,
      studentTags
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