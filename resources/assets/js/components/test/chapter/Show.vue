<template>
  <div>
    <ul class="list-reset flex text-xs profile-tab flex-wrap">
      <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : quiz_tab === '1'}]" >
        <a href="#" class="text-gray-700 font-medium" @click="setQuizTab('1')">QuestionHeads</a>
      </li>
      <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : quiz_tab === '2'}]" >
        <a href="#" class="text-gray-700 font-medium" @click="setQuizTab('2')">Chapters</a>
      </li>
    </ul>

    <Teleport to="#subjectchapter">
      <questions :url="this.url" :subject_id="this.subject_id"></questions>
      <chapterlist :url="this.url" :subject_id="this.subject_id"></chapterlist>
    </Teleport>
  </div>
</template>

<script>
  import { bus } from "../../../app";
  import questions from './questionhead';
  import chapterlist from './chpaterlist';
  

  export default {
    props:['url','subject_id'],
    data () {
      return {
        quiz_tab:'1',     
      }
    },
    components: {
      questions,
      chapterlist,
     
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