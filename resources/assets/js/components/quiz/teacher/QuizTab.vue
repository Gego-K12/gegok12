<template>
  <div>
    <ul class="list-reset flex text-xs profile-tab flex-wrap">
      <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : quiz_tab === '1'}]" >
        <a href="#" class="text-gray-700 font-medium" @click="setQuizTab('1')">Questions({{this.question_count}})</a>
      </li>
      <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : quiz_tab === '2'}]" >
        <a href="#" class="text-gray-700 font-medium" @click="setQuizTab('2')">Participants({{this.participant_count}})</a>
      </li>
      <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : quiz_tab === '3'}]">
        <a href="#" class="text-gray-700 font-medium" @click="setQuizTab('3')">Assign({{this.assign_count}})</a>
      </li>
    </ul>

    <Teleport to="#quiztopic">
      <questions :url="this.url" :topic_id="this.quiz_topic"></questions>
      <participants :url="this.url" :topic="this.quiz_topic"></participants>
      <assign :url="this.url" :topic="this.quiz_topic"></assign>
    </Teleport>
  </div>
</template>

<script>
  import { bus } from "../../../app";
  import questions from './questionlist';
  import participants from './participantslist';
  import assign from './assignlist';

  export default {
    props:['url','quiz_topic','question_count','participant_count','assign_count'],
    data () {
      return {
        quiz_tab:'1',     
      }
    },
    components: {
      questions,
      participants,
      assign,
    },

    methods:
    {
      setQuizTab(val)
      {
        this.quiz_tab=val;
        bus.$emit("dataQuizTab", this.quiz_tab);
      }
    },

    created()
    {
      bus.$emit("dataQuizTab", this.quiz_tab);
       
      bus.$on("dataQuizTab", data => {
        if(data!='')
        {
          this.quiz_tab=data;                   
        }
      });     
    }
  }
</script>