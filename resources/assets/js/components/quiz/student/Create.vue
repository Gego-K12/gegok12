<template>
  <div class="bg-white shadow px-4 py-3">
     <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
    <div v-if="this.success!=null" class=" text-2xl py-1 font-semibold" id="success-alert">Score : <span class="text-green-500">{{this.score}} / {{this.attend}} </span></div>
    <div v-if="this.success!=null">
     <a :href="url+'/student/quiz/test/'+this.test_id+'/review'" target="_blank"><button type="button" class="bg-blue-500 text-white px-4 py-1  my-2 w-32 text-sm">Review</button></a>
     </div>
    <div  v-if="this.quizstatus!='completd'">
	   
     
       
  
 <div v-show="hidequiz" class="flex justify-between" >
  <div class="flex mx-2">
  <p class=" my-1 self-center text-lg">Quiz : <b class="self-center">{{this.topic_name}}</b></p>
  </div>
  <div class="flex mr-2">
    <p class=" my-1 self-center text-xl">Question:{{this.answered.length}}/{{this.total_count}} </p>
  </div>
  </div>
  
  <div class="px-2 py-3 text-black">
   <h3 class="question text-xl" v-html="questions.question"></h3>

   </div>
   
 <div class="flex justify-center mx-2 w-3/4" v-for="option in questions.quizoptions">
<label class="w-full p-2 rounded text-black my-2 shadow">
            <input v-if="questions.type_id==1 || questions.type_id==3" type="radio" name="answer[]" :value="option.option"  v-model="answers[0]" class="self-center text-white">
            <input v-else="" type="checkbox" name="answer[]" :value="option.option"  v-model="answers" class="self-center text-white">
            <span class="ml-1 self-center">{{option.option}} </span>
          </label>
  </div>
 
  
<div class="flex  justify-end my-3">
<!-- <div class="flex-col mx-3 ">
  <p class="text-gray-600">Is something wrong with this question?</p>
  <p class="text-black font-semibold">Give me the feedback</p>
</div> -->

 <button class="btn btn-submit  text-white rounded px-3 py-1 mr-3 text-sm font-medium" v-bind:class="[errors.answers?'bg-red-600' :'blue-bg']" type="submit" :disabled="isDisabled" @click="addanswer()" >
  Next Question
</button>

</div>
<div v-if="this.timing!=''">
     <vue-countdown-timer
      @start_callback="startCallBack()"
      @end_callback="endCallBack()"
      :start-time="startAt"
      :end-time="endAt"
      :interval="1000"
      :start-label="'Time start:'"
      :end-label="'Time left:'"
      label-position="begin"
      :end-text="endtext"
      :day-txt=false
      :hour-txt=false
      :minutes-txt=false
      :seconds-txt="'seconds'">
    </vue-countdown-timer>  
    </div>

      </div>
	  </div>
</template>

<script>
export default {
  props:['url','test_id','topic','total_count','completed_score','total_answered','timing'],

  data(){
    return{
      questions:[],
      score:0,
      answers:[],
      answered:[],
      question_id:null,
      status:'',
      quizstatus:'',
      topic_name:'',
      errors:[],
      success:null,
      hidequiz:true,
      attend:'',
      //timer
      startAt:(new Date).getTime()  ,
      endAt:(new Date).getTime()+15000,
      endtext:'wait for next question',
      isDisabled:false,
      
    }
  },
        
  methods:
  {
    getList()
    {
      axios.get(this.url+'/student/quiz/test/'+this.topic+'/question').then(response => {
        this.answered=response.data.answered;
        if(this.total_count<=this.answered.length)
        {
          this.hidequiz=false;
          this.quizstatus="completd";
          this.success="Quiz completed";
          this.score=this.completed_score;
          
        }
        this.questions    = response.data;
        this.question_id=this.questions.id;
        this.topic_name=this.questions.quiztopic.name;
        this.total=this.questions.length;
        this.answered.push(this.question_id);

      });
    },

    addanswer()
    {  
    var b=this;
    if(b.timing!="" ){    
    b.isDisabled=true;
  }
      axios.post(this.url+'/student/quiz/answer/add',{
        topic:this.topic,
        question_id:this.question_id,
        answers:this.answers,
        test_id:this.test_id,
        timer:this.timing,
        answered:this.answered,
      }).then(response => {
        this.questions=[];
        this.answers=[];
        this.errors=[];
         b.isDisabled=false;
         if(this.answered.length==(this.total_count-1))
         {
          this.endtext='Quiz end';
         }
        console.log(response);   
        if(this.total_count==this.answered.length)
        {
          this.endtext='';
          this.hidequiz=false;
          this.quizstatus="completd";
          this.success=response.data.message;
          this.score=response.data.score;
          this.attend=this.total_count;
        }
        else
        { 
          if(b.timing!="" ){
          b.startCallBack();
        }
        this.questions    = response.data;
        this.question_id=this.questions.id;
        this.answered.push(this.question_id);
      }
      }).catch(error=>{
        console.log(error);
        this.errors=error.response.data.errors;
      }); 
    },
   

  endCallBack()
  {
     if(this.timing!="" ){
     this.addanswer();
   }
  },
  startCallBack()
  {
    if(this.timing!="" )
    {
    this.startAt=  (new Date).getTime();
    this.endAt=  (new Date).getTime()+parseInt(this.timing*1000);
   
  }
  else{
    this.startAt=false;
    this.endAt= false;
    this.endtext='';
  }
  },
  
    
  },

  created()
  {


    if(this.completed_score=='')
    {
    this.getList();
   
  }
  else
  {
          this.hidequiz=false;
          this.quizstatus="completd";
          this.success="Quiz completed";
          this.score=this.completed_score;
          this.attend=this.total_answered;
  }


  }
}
</script>