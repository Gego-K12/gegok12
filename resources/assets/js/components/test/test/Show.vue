<template>
  <div>
    <ul class="list-reset flex text-xs profile-tab flex-wrap">
      <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : test_tab === '1'}]" >
        <a href="#" class="text-gray-700 font-medium" @click="setQuizTab('1')">Pattern</a>
      </li>
      <li class="px-2 mx-3 py-2" v-bind:class="[{'active' : test_tab === '2'}]" >
        <a href="#" class="text-gray-700 font-medium" @click="setQuizTab('2')">Papers</a>
      </li>
    </ul>

    <Teleport to="#showtest">
      <patternlist :url="this.url" :test_id="this.test_id" :total_marks="this.total_marks" :marks_taken="this.marks_taken"></patternlist>
      <paperlist :url="this.url" :test_id="this.test_id" :total_marks="this.total_marks" :marks_taken="this.marks_taken"></paperlist>
    </Teleport>
  </div>
</template>

<script>
  import { bus } from "../../../app";
  import patternlist from './patternList';
  import paperlist from './papersList';
  

  export default {
    props:['url','test_id','total_marks','marks_taken'],
    data () {
      return {
        test_tab:'1',     
      }
    },
    components: {
      patternlist,
      paperlist,
     
    },

    methods:
    {
      setQuizTab(val)
      {
        this.test_tab=val;
        bus.$emit("dataTestTab", this.test_tab);
      }
    },

    created()
    {
      bus.$emit("dataTestTab", this.test_tab);
       
      bus.$on("dataTestTab", data => {
        if(data!='')
        {
          this.test_tab=data;                   
        }
      });     
    }
  }
</script>