<template>
  <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3" v-bind:class="[this.quiz_tab==1?'block' :'hidden']">
    <div class="relative">
      <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
      <div class="flex flex-wrap lg:flex-row justify-between items-center my-1">
        <div class="">
          <h1 class="admin-h1">Questions</h1>
        </div>
        <div class="relative flex items-center w-8/12 lg:w-1/4 md:w-1/4 justify-end">
          <div class="flex items-center cursor-pointer">
            <a @click="addHead"  class="no-underline text-white px-4 mx-1 flex items-center custom-green py-1 justify-center">
              <span class="mx-1 text-sm font-semibold">Add</span>
              <svg data-v-2a22d6ae="" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 409.6 409.6" xml:space="preserve" class="w-3 h-3 fill-current text-white"><g data-v-2a22d6ae=""><g data-v-2a22d6ae=""><path data-v-2a22d6ae="" d="M392.533,187.733H221.867V17.067C221.867,7.641,214.226,0,204.8,0s-17.067,7.641-17.067,17.067v170.667H17.067 C7.641,187.733,0,195.374,0,204.8s7.641,17.067,17.067,17.067h170.667v170.667c0,9.426,7.641,17.067,17.067,17.067 s17.067-7.641,17.067-17.067V221.867h170.667c9.426,0,17.067-7.641,17.067-17.067S401.959,187.733,392.533,187.733z"></path></g></g></svg>
            </a> 
          </div>
        </div>
      </div>
      <div class="">
        <div class="flex flex-wrap custom-table my-3 overflow-auto">
          <table class="w-full">
            <thead class="bg-grey-light">
              <tr class="border-b">
                <th class="text-left text-sm px-2 py-2 text-grey-darker" > No </th>
                <th class="text-left text-sm px-2 py-2 text-grey-darker"> Questions Head </th>
                <th class="text-left text-sm px-2 py-2 text-grey-darker"> Count </th>
                <th class="text-left text-sm px-2 py-2 text-grey-darker"> Marks </th>
                <th class="text-left text-sm px-2 py-2 text-grey-darker" width="5%"> Actions </th>
              </tr>
            </thead>   
            <tbody v-if="this.questions != ''">
              <tr class="border-b" v-for="(question,k1) in questions" :key="k=k1+1"  >
                <td class="py-3 px-2">
                  <p class="font-semibold text-xs">{{k}}</p>
                </td>
                <td class="py-3 px-2">
                  <p class="font-semibold text-xs">{{question.name}}</p>
                </td>
                 <td class="py-3 px-2">
                  <p class="font-semibold text-xs">{{question.count}}</p>
                </td>
                <td class="py-3 px-2">
                  <p class="font-semibold text-xs">{{question.marks}}</p>
                </td>
                <td class="py-3 px-2">
                  <div class="flex items-center">                
                   
                    <a @click="deletequestion(question.id)" title="Delete">
                      <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-4 h-4 fill-current text-black-500 mx-1"><g><g><g><polygon points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804"></polygon> <rect x="235.948" y="175.791" width="40.104" height="237.285"></rect> <polygon points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804"></polygon> <path d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74 c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474 L380.665,471.896z"></path></g></g></g> <g><g><path d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42 C354.924,14.992,339.932,0,321.504,0z"></path></g></g></svg>
                    </a>
                  </div>
                </td>
              </tr>
            </tbody>
            <tbody v-else="">
              <tr class="border-b">
                <td colspan="8">
                  <p class="font-semibold text-s" style="text-align: center">No Records Found</p>
                </td>
              </tr>
            </tbody>
          </table>
          <!-- <paginate
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
          </paginate> -->
        </div>
      </div>  
    </div>

    <!--show modal  -->
     <div v-if="this.show == 1" class="modal modal-mask">
      <div class="modal-wrapper px-4">
        <div class="modal-container w-full  max-w-md px-8 mx-auto">
          <div class="modal-header flex justify-between items-center">
            <h2>Question Head</h2>
            <button id="close-button" class="modal-default-button text-2xl py-1" @click="closeModal()">
              &times;
            </button>
          </div>
          <div class="modal-body" >
            <div class="flex flex-col">
                            <div class="w-full lg:w-1/4"> 
                                <label for="question_head" class="tw-form-label">Question Head<span class="text-red-500">*</span></label>
                            </div>
                            <div class="my-2 w-full">
                                <input type="text" name="question_head" v-model="question_head" class="tw-form-control w-full"><br>
                                <span v-if="errors.name" class="text-red-500 text-xs font-semibold">{{ errors.name[0] }}</span>
                            </div>
              </div>
              <div class="flex flex-col">
                            <div class="w-full lg:w-1/4"> 
                                <label for="mark" class="tw-form-label">Mark<span class="text-red-500">*</span></label>
                            </div>
                            <div class="my-2 w-full">
                                <input type="text" name="mark" v-model="mark" class="tw-form-control w-full"><br>
                                <span v-if="errors.marks" class="text-red-500 text-xs font-semibold">{{ errors.marks[0] }}</span>
                            </div>
              </div>
               <div class="flex items-center">
                            <div class="my-2 w-full lg:w-3/4">
                                <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 cursor-pointer text-sm font-medium" @click="submit">Submit</a>
                            </div>
                </div>
          </div>
         
        </div>
      </div>
    </div>
<!-- End modal -->
  </div>
</template>

<script>

  import { bus } from "../../../app";

  export default {
    props:['url','subject_id','chapter_id'],
    data () {
      return {
        //page_count:0,
        //page:0,
       // total :'',
        quiz_tab:'1',
        questions:[],
        edit:[],
        question_head:'',
        mark:'',
        show:0,
        editshow:0,
        errors:[],
        success:null,
      }
    },

    methods:
    {
      deletequestion(id) 
      {
        var thisswal = this;
        swal({
          title: 'Are you sure',
          text: 'Do you want to delete this Question Head?',
          icon: "info",
          buttons: [
            'No',
            'Yes'
          ],
          dangerMode: true,
        }).then(function(isConfirm) {
          if (isConfirm) 
          {
            axios.get(thisswal.url+'/question/head/'+id+'/delete').then(response => {
              thisswal.success    = response.data.message;
              thisswal.getlist();
            });  
          }
          else 
          {
            swal("Cancelled");
          }
        });
      },

      addHead()
      {
        //alert(id);  
        this.show=1;
        
       
      },
      closeModal()
      {
        this.show=0;
      },

      reset()
      {
        this.question_head='';
        this.mark='';
        this.errors=[];

      },

      submit()
      {
        axios.post(this.url+'/question/head/create',{
                    subject_id:this.subject_id,
                    name:this.question_head,
                    marks:this.mark,
                }).then(response => {
                    this.success = response.data.success;
                    this.closeModal();
                    this.getlist();
                    this.reset();
                }).catch(error=>{
                    this.errors=error.response.data.errors;
                }); 
      },

      getlist()
      {
        axios.get('/question/head/'+this.subject_id).then(response => {
          this.questions    = response.data.data;
          //this.total = response.data.meta.total;
          //this.page_count = response.data.meta.last_page;
          //console.log(response);    
        });
      },
    },
  
    created()
    {   
      this.getlist();

      bus.$on("dataQuizTab", data => {
        if(data!='')
        {
          this.quiz_tab=data;                   
        }
      });   
    }
  }
</script>
<style scoped>

.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .5);
  display: table;
  transition: opacity .3s ease;
}

.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
    overflow:auto;
}

.modal-container {
  margin: 0px auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
  transition: all .3s ease;
 /* height: 550px;*/
  overflow:auto;
}

.modal-header h3 {
  margin-top: 0;
  color: #42b983;
}

.modal-body {
  margin: 10px 0;
}

.modal-default-button {
  float: right;
}

/*
 * The following styles are auto-applied to elements with
 * transition="modal" when their visibility is toggled
 * by Vue.js.
 *
 * You can easily play with the modal transition by editing
 * these styles.
 */

.modal-enter {
  opacity: 0;
}

.modal-leave-active {
  opacity: 0;
}

.modal-enter .modal-container,
.modal-leave-active .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}

.text-danger
{
  color:red;
}
</style>