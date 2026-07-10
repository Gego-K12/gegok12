<template>
  <div class="relative">
    <div v-for="details in details" class="bg-white shadow px-4 py-2 my-2">
      <div  class="flex flex-wrap custom-table my-3 overflow-auto">
        <div class="px-2 flex py-1 text-black">
      
          <h3 class="justify-center question text-base" v-html="details.questions"></h3>
        </div>
      </div>
      <div class="flex justify-center mx-2 w-3/4 text-sm" v-for="opt in details.options">
        <label class="w-full p-2 rounded text-black my-2 shadow " v-bind:class="[{'bg-green-200' : opt.is_answer==1}]">
          <span class="ml-1 self-center">{{opt.option}} </span>
        </label>
      </div>
      <div class="px-2 flex-col py-1 text-black">
        <p>Your answers:</p>  
        <p v-for="answer in details.answered" class="font-semibold my-1 capitalize">{{answer}}</p>
      </div>
    </div>
       
    <paginate
      v-model="page"
      :page-count="this.page_count"
      :page-range="3"
      :margin-pages="1"
      :click-handler="getlist"
      :prev-text="'<'"
      :next-text="'>'"
      :container-class="'pagination'"
      :page-class="'page-item'"
      :prev-link-class="'prev'"
      :next-link-class="'next'">
    </paginate>   
  </div>
</template>

<script>

  export default {
    props:['url','test_id'],
    data () {
      return {
        page_count:0,
        page:0,
        total :'',
        quiz_tab:'',
        details:[],
        success:null,
      }
    },

    methods:
    {
      getlist()
      {
        axios.get(this.url+'/student/quiz/test/'+this.test_id+'/details?page='+this.page).then(response => {
          this.details    = response.data.data;
          this.page_count = response.data.meta.last_page;
          this.total = response.data.meta.total;
          //console.log(response.data);    
        });
      },
    },
  
    created()
    {    
      this.getlist();
    }
  }
</script>