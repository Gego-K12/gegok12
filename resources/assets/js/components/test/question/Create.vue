<template>
  <div class="bg-white shadow px-4 py-3">
    <div>
	    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
      <div class="flex flex-col lg:flex-row md:flex-row">
        <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="head_id" class="tw-form-label">Select Head<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2">
              <select name="head_id" id="head_id" v-model="head_id" class="tw-form-control w-full">
                <option value="" disabled>Select Head</option>
                <option v-for="head in heads" v-bind:value="head.id">{{head.name}}</option>
              </select>
            </div>
            <span v-if="errors.head_id" class="text-red-500 text-xs font-semibold">{{errors.head_id[0]}}</span>
          </div> 
        </div>
      </div>
      <div class="flex flex-col lg:flex-row md:flex-row">
        <div class="tw-form-group w-full">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="type" class="tw-form-label">Select Type<span class="text-red-500">*</span></label>
            </div>
            <div class="flex justify-between mb-2 w-full lg:w-2/3 ">
              <div class="flex self-center " v-for="type in types">
                <input type="radio" class="my-1" v-bind:value="type.id"  v-model="type_id" >
                <div class="flex-col">
                  <label class="ml-2 self-center  font-semibold">{{type.name}}</label>
                  <!-- <p class="ml-2 text-xs">{{type.description}}</p> -->
                </div>
              </div>
            </div>
           <span v-if="errors.type" class="text-red-500 text-xs font-semibold">{{errors.type[0]}}</span>
          </div>
        </div>
      </div>

      <div class="flex flex-col lg:flex-row md:flex-row">
        <div class="tw-form-group w-full">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="page_no" class="tw-form-label">Page no<span class="text-red-500">*</span></label>
            </div>
                <input type="number" class="my-1"   v-model="page_no" >
           <span v-if="errors.page_no" class="text-red-500 text-xs font-semibold">{{errors.page_no[0]}}</span>
          </div>
        </div>
      </div>

      <div class="flex flex-col lg:flex-row md:flex-row">
        <div class="tw-form-group w-full ">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="question" class="tw-form-label">Question<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2">
              <!--  <textarea type="text" name="question" id="question" v-model="question" class="tw-form-control w-full" rows="3" placeholder="Enter Question"></textarea> -->
              <!--  <ckeditor class="w-2/3" v-model="question"></ckeditor> -->
              <QuillEditor v-model:content="question" contentType="html" theme="snow" :modules="editorOption.modules" />
            </div>
            <span v-if="errors.question" class="text-red-500 text-xs font-semibold">{{errors.question[0]}}</span>
          </div>
        </div>
      </div>

      <div class="mb-2 items-center">
        <label for="title" class="tw-form-label"> <input type="checkbox" v-model="select" v-bind:true-value="1" v-bind:false-value="0" @click="change()"> Option<span class="text-red-500">*</span></label>
      </div> 
      <div class="flex flex-col" >
        <span v-if="errors.optioncount" class="text-red-500 text-xs font-semibold">{{errors.optioncount[0]}}</span> 
        <span v-if="errors.optioncount" class="text-red-500 text-xs font-semibold">{{errors.optioncount[1]}}</span> 
        <span v-if="errors.optioncount" class="text-red-500 text-xs font-semibold">{{errors.optioncount[2]}}</span>       
      </div>
      <div class="flex flex-col w-full lg:w-1/2" v-show="this.optionshow==true">
        <div v-for="(option1,k1) in options" :key="k1" class="">
          <div class="flex items-center">
            <input type="checkbox"  v-bind:true-value="1" v-bind:false-value="0" v-model="option1.is_answer">
            <div v-show="hideoption" class="flex-col w-1/2">
              <input type="text"  v-model="option1.option" class="tw-form-control w-full ml-2">  
            </div>      
            <div v-show="hideoption">    
              <button  class="add_more px-3 text-3xl"  @click.prevent="removeoption(k1)" v-show="k1 || ( !k1 && options.length >1)">-</button>
              <button class="add_more px-3 text-3xl "  @click.prevent="addoption(k1)" v-show="k1 == options.length-1">+</button>
            </div> 
            <div v-show="!hideoption" class="ml-2">
              <label>{{option1.option}}</label>
            </div>
          </div>
          <span v-if="errors['option'+k1]" class="text-red-500 text-xs font-semibold">{{errors['option'+k1][0]}}</span>
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
  import { QuillEditor } from '@vueup/vue-quill'
  import '@vueup/vue-quill/dist/vue-quill.snow.css'
  export default {
    props:['url','subject_id','chapter_id'],
    components: {
      QuillEditor
    },
    data(){
      return{
        heads:[],
        types:[
        {'id':'all','name':'All'},
        {'id':'repeated','name':'Repeated'},
        {'id':'important','name':'Important'}
        ],
        type_id:'',
        head_id:'',
        question:'',
        optionshow:false,
        select:0,
        options: [
          {
            is_answer:0,
            option: '',
          },
          {
            is_answer:0,
            option: '',
          }
        ],
        myoption:['Yes','No'], 
        hideoption:true,
        errors:[],
        success:null,
        editorOption:{
          theme: 'snow',
          modules: {
            toolbar: {
              container: [
                ['bold', 'italic', 'underline', 'strike'],       
                [{ 'color': [] }, { 'background': [] }],
                [{ 'script': 'sub' }, { 'script': 'super' }],        
                [{ 'align': [] }],
                ['image'],
              ],      
            }
          } 
        },
      }
    },
        
    methods:
    {
      getList()
      {
        axios.get(this.url+'/question/head/'+this.subject_id).then(response => {
         this.heads=response.data.data;
          console.log(response);    
        });
          
      },

      check()
      {
        var formData = new FormData();
        formData.append('head',this.head_id);
        formData.append('type',this.type_id);
        formData.append('page_no',this.page_no);
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
          //location.reload();
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