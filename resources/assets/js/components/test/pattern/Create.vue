<template>
  <div class="bg-white shadow px-4 py-3">
    <div>
	    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
      <div class="flex flex-col lg:flex-row md:flex-row">
        <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="head_id" class="tw-form-label">Select Chapter<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2">
               <div v-for="(chapter, index) in chapters" :key="index">
                <input type="checkbox" :id="chapter.name" v-model="chapter.checked">
                <label :for="chapter.name">{{ chapter.name }}</label>
               </div>
            </div>
            <span v-if="errors.topic" class="text-red-500 text-xs font-semibold">{{errors.topic[0]}}</span>
          </div> 
        </div>
      </div>
     
 
      <div class="my-6">
        <a href="#" id="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="check()">Submit</a>
    		<a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="reset()">Reset</a>	
      </div>
	  </div>
  </div>
</template>

<script>
  
  export default {
    props:['url','test_id'],
    data(){
      return{
        chapters:[],
        errors:[],
        success:null,
      }
    },
        
    methods:
    {
      getList()
      {
        axios.get(this.url+'/test/pattern/'+this.test_id+'/list').then(response => {
         this.chapters=response.data.chapterlist;
          console.log(response);    
        });
          
      },

      check()
      {
        var formData = new FormData();
        formData.append('head',this.head_id);
        formData.append('type',this.type_id);
        formData.append('chapter',this.chapter_id);
        formData.append('option',this.options);
        formData.append('question',this.question);
        formData.append('optioncount',this.options.length);
        formData.append('optionstatus',this.select);

        for(let i=0;i<this.options.length;i++)
        {
          if(typeof this.options[i]['option'] !== "undefined")
          {
            formData.append('option'+i,this.options[i]['option']);
          }
          else
          {
            formData.append('option'+i,'');
          }
        }
        for(let i=0;i<this.options.length;i++)
        {
          if(typeof this.options[i]['is_answer'] !== "undefined")
          {
            formData.append('is_answer'+i,this.options[i]['is_answer']);
          }
          else
          {
            formData.append('is_answer'+i,'');
          }
        }

        axios.post(this.url+'/test/question/create',formData).then(response => {
          this.success=response.data.message;
         // location.reload();
        }).catch(error=>{
          this.errors=error.response.data.errors;
        }); 
      },

      change()
      {
          if(this.select==0)
          {
            this.optionshow=true;
          }
          else
          {
          this.optionshow=false;
          }
       },

      typechange(id)
      {
        if(id==3)
        {
          this.options=[];
          this.hideoption=false;
          for (var i =0;i<this.myoption.length;i++) 
          {
            this.options.push({ is_answer:0,option:this.myoption[i]});
          }
        }
        else
        {
          this.errors=[];
          //this.options=[{
          //  is_answer:0,
          //  option: '',
          //}];
          this.hideoption=true;
        }
      },

      addoption(index)
      {
        this.options.push({ is_answer:0,option: ''});
      },

      removeoption(index) 
      {
        this.options.splice(index, 1);
      },

      reset()
      { 
       // location.reload();
        
      }, 
    },

    created()
    {
      this.getList();
    }
  }
</script>