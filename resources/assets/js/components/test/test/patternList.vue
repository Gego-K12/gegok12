<template>
  <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3" v-bind:class="[this.test_tab==1?'block' :'hidden']">
    <div class="relative">
      <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
      <div class="flex flex-wrap lg:flex-row justify-between items-center my-1">
        <div class="">
          <h1 class="admin-h1">Test pattern</h1>
        </div>
        <div class="relative flex items-center w-8/12 lg:w-1/4 md:w-1/4 justify-end">
          <div class="flex items-center"> 
           <!--  <a :href="url+'/test/pattern/'+test_id+'/create'"  class="no-underline text-white px-4 mx-1 flex items-center custom-green py-1 justify-center"> -->
               <a v-if="this.marks_taken!=this.total_marks" @click="addPattern()" class="no-underline cursor-pointer text-white px-4 mx-1 flex items-center custom-green py-1 justify-center">
              <span class="mx-1 text-sm font-semibold">Add</span>
              <svg data-v-2a22d6ae="" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 409.6 409.6" xml:space="preserve" class="w-3 h-3 fill-current text-white"><g data-v-2a22d6ae=""><g data-v-2a22d6ae=""><path data-v-2a22d6ae="" d="M392.533,187.733H221.867V17.067C221.867,7.641,214.226,0,204.8,0s-17.067,7.641-17.067,17.067v170.667H17.067 C7.641,187.733,0,195.374,0,204.8s7.641,17.067,17.067,17.067h170.667v170.667c0,9.426,7.641,17.067,17.067,17.067 s17.067-7.641,17.067-17.067V221.867h170.667c9.426,0,17.067-7.641,17.067-17.067S401.959,187.733,392.533,187.733z"></path></g></g></svg>
            </a> 
            <a v-if="this.marks_taken==this.total_marks" :href="url+'/test/generate/'+test_id" class="no-underline cursor-pointer text-white px-4 mx-1 flex items-center custom-green py-1 justify-center">
              <span class="mx-1 text-sm font-semibold">Generate test</span>
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
                <th class="text-left text-sm px-2 py-2 text-grey-darker"> Chapter</th>
                <th class="text-left text-sm px-2 py-2 text-grey-darker"> Question head </th>
                <th class="text-left text-sm px-2 py-2 text-grey-darker"> Type </th>
                <th class="text-left text-sm px-2 py-2 text-grey-darker"> Count </th>
                <th class="text-left text-sm px-2 py-2 text-grey-darker"> Marks </th>
                <th class="text-left text-sm px-2 py-2 text-grey-darker" width="10%"> Actions </th>
              </tr>
            </thead>   
            <tbody v-if="this.patterns != ''">
              <tr class="border-b" v-for="(pattern,k1) in patterns" :key="k=k1+1"  >
                <td class="py-3 px-2">
                  <p class="font-semibold text-xs">{{k}}</p>
                </td>
                <td class="py-3 px-2">
                  <p class="font-semibold text-xs">{{pattern.chapter_name}}</p>
                </td>
                <td class="py-3 px-2">
                  <p class="font-semibold text-xs">{{pattern.question_head}}</p>
                </td>
                 <td class="py-3 px-2">
                  <p class="font-semibold text-xs">{{pattern.type_name}}</p>
                </td>
                 <td class="py-3 px-2">
                  <p class="font-semibold text-xs">{{pattern.count}}</p>
                </td>
                 <td class="py-3 px-2">
                  <p class="font-semibold text-xs">{{pattern.marks}}</p>
                </td>
                <td class="py-3 px-2">
                  <div class="flex items-center">                
                   <!--  <a :href="url+'/test/pattern/'+pattern.id+'/edit'" target="_blank" title="Edit">
                      <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 477.873 477.873" xml:space="preserve" class="w-4 h-4 fill-current text-black-500 mx-1"><g><g><path d="M392.533,238.937c-9.426,0-17.067,7.641-17.067,17.067V426.67c0,9.426-7.641,17.067-17.067,17.067H51.2 c-9.426,0-17.067-7.641-17.067-17.067V85.337c0-9.426,7.641-17.067,17.067-17.067H256c9.426,0,17.067-7.641,17.067-17.067 S265.426,34.137,256,34.137H51.2C22.923,34.137,0,57.06,0,85.337V426.67c0,28.277,22.923,51.2,51.2,51.2h307.2 c28.277,0,51.2-22.923,51.2-51.2V256.003C409.6,246.578,401.959,238.937,392.533,238.937z"></path></g></g> <g><g><path d="M458.742,19.142c-12.254-12.256-28.875-19.14-46.206-19.138c-17.341-0.05-33.979,6.846-46.199,19.149L141.534,243.937 c-1.865,1.879-3.272,4.163-4.113,6.673l-34.133,102.4c-2.979,8.943,1.856,18.607,10.799,21.585 c1.735,0.578,3.552,0.873,5.38,0.875c1.832-0.003,3.653-0.297,5.393-0.87l102.4-34.133c2.515-0.84,4.8-2.254,6.673-4.13 l224.802-224.802C484.25,86.023,484.253,44.657,458.742,19.142z M434.603,87.419L212.736,309.286l-66.287,22.135l22.067-66.202 L390.468,43.353c12.202-12.178,31.967-12.158,44.145,0.044c5.817,5.829,9.095,13.72,9.12,21.955 C443.754,73.631,440.467,81.575,434.603,87.419z"></path></g></g></svg>
                    </a> -->
                    <a @click="deletequestion(pattern.id)" title="Delete">
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

    <div v-if="this.show == 1" class="modal modal-mask">
      <div class="modal-wrapper px-4">
        <div class="modal-container w-full  max-w-md px-8 mx-auto">
          <div class="modal-header flex justify-between items-center">
            <h2>Add Pattern</h2>
            <button id="close-button" class="modal-default-button text-2xl py-1" @click="closeModal()">
              &times;
            </button>
          </div>
      <div class="modal-body" >
             <div class="flex flex-col">
                            <div class="w-full lg:w-1/4"> 
                                <label for="chapter_id" class="tw-form-label">Chapter<span class="text-red-500">*</span></label>
                            </div>
                            <div class="my-2 w-full">
                                <select class="tw-form-control w-full" id="chapter_id" v-model="chapter_id" name="chapter_id" @change="onChange()" >
                                  <option value="" disabled>Select Chapter</option>
                                 <option value="" v-for="chapter in chapters" v-bind:value="chapter.id">{{chapter.name}}</option>
                                </select>
                                <span v-if="errors.chapter_id" class="text-red-500 text-xs font-semibold">{{ errors.chapter_id[0] }}</span>
                            </div>
              </div>
              <div class="flex flex-col">
                            <div class="w-full lg:w-1/4"> 
                                <label for="head_id" class="tw-form-label">Question Head<span class="text-red-500">*</span></label>
                            </div>
                            <div class="my-2 w-full">
                                <select class="tw-form-control w-full" id="head_id" v-model="head_id" name="head_id" @change="onChange()" >
                                  <option value="" disabled>Select Question Head</option>
                                 <option value="" v-for="head in heads" v-bind:value="head.id">{{head.name}} - {{head.marks}} marks</option>
                                </select>
                                <span v-if="errors.head_id" class="text-red-500 text-xs font-semibold">{{ errors.head_id[0] }}</span>
                            </div>
              </div>
              <div class="flex flex-col">
                            <div class="w-full lg:w-1/4"> 
                                <label for="head_id" class="tw-form-label">PageNo<span class="text-red-500">*</span></label>
                            </div>
                            <div class="my-2 w-full">
                                <select class="tw-form-control w-full" id="page_no" v-model="page_no" name="page_no" @change="onChange()" >
                                  <option value="" >All Pages</option>
                                 <option value="" v-for="pagenum,index in pagenums" v-bind:value="pagenum">{{pagenum}}</option>
                                </select>
                                <span v-if="errors.page_no" class="text-red-500 text-xs font-semibold">{{ errors.page_no[0] }}</span>
                            </div>
              </div>
               <div v-show="this.chapter_id!='' && this.head_id!=''" class="flex flex-col">
                            <div class="w-full lg:w-1/4"> 
                              <!--   <label for="type_id" class="tw-form-label">Type <span class="text-red-500">*</span></label> -->
                            </div>
                            <div class="my-2 w-full">
                               <!--  <select class="tw-form-control w-full" id="type_id" v-model="type_id" name="type_id">
                                  <option value="" disabled>Select Type</option>
                                 <option value="" v-for="type in types" v-bind:value="type.id">{{type.name}}</option>
                                </select> -->
                                <table class="custom-table w-full">
                                <thead>
                                  <tr>
                                    <th>Check</th>
                                    <th>Type</th>
                                    <th>Available</th>
                                    <th>Count</th>
                                  </tr>
                                </thead>
                                <tbody>
                                <tr  v-for="(type, index) in types" :key="index">
                               <!--  <div class="flex-col justify-between" v-for="(type, index) in types" :key="index"> -->
                                    <td width="3%"><input v-show="type.available>0" type="checkbox" :id="type.name" true-value="1" false-value="0" v-model="type.status"></td>
                                   <td> <label :for="type.name" >{{ type.name }}</label> </td>
                                   <td>  <label :for="type.name" class="mx-2">{{type.available}}</label> </td>
                                   <td> 
                                   <div class="flex flex-col">
                                   <input v-show="type.status=='1'" type="number" name="count" :min="0" :max="type.available" v-model="type.count" class="tw-form-control mx-2 ">
                                    <span  v-if="errors['count'+index]" ><small class="font-muller text-red-500 mx-2">{{errors['count'+index][0]}}</small></span>
                                    </div>
                                     </td>
                                   
                               <!-- </div> -->
                               </tr>
                              
                               </tbody>
                               </table>
                                
                               
                            </div>
              </div>
              <!-- <div class="flex flex-col">
                            <div class="w-full lg:w-1/4"> 
                                <label for="count" class="tw-form-label">Count<span class="text-red-500">*</span></label>
                            </div>
                            <div class="my-2 w-full">
                                 <input type="text" name="count" v-model="count" class="tw-form-control w-full"><br>
                                <span v-if="errors.count" class="text-red-500 text-xs font-semibold">{{ errors.count[0] }}</span>
                            </div>
              </div> -->
             
             
               <div class="flex items-center">
                            <div class="my-2 w-full lg:w-3/4">
                                <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 cursor-pointer text-sm font-medium" @click="submit">Submit</a>
                            </div>
                </div>
          </div>
         
    </div>
         
        </div>
      </div>
    </div>

</template>

<script>
  import { bus } from "../../../app";

  export default {
    props:['url','test_id','total_marks','marks_taken'],
    data () {
      return {
        page_count:0,
        page:0,
        total :'',
        test_tab:1,
        patterns:[],
        chapters:[],
        pagenums:[],
        heads:[],
        types:[
        {id:'all',name:'All',status:"0",available:'',count:''},
        {id:'repeated',name:'Repeated',status:"0",available:'',count:''},
        {id:'important',name:'Important',status:"0",available:'',count:''}
        ],
        typecount:[],
        chapter_id:'',
        head_id:'',
        type_id:'',
        page_no:'',
        errors:[],
        success:null,
        show:0,
      }
    },

    methods:
    {
      getlist()
      {
        axios.get('/test/'+this.test_id+'/pattern').then(response => {
          this.patterns    = response.data.data;
          //this.page_count = response.data.meta.last_page;
          //this.total = response.data.meta.total;
          //console.log(response.data.data);    
        });
      },

      addlist()
      {
        axios.get('/test/pattern/'+this.test_id+'/list').then(response => {
          this.chapters    = response.data.chapterlist;
          this.heads       = response.data.questionhead;
          console.log(response.data);    
        });
      },

      onChange()
      {
        if(this.chapter_id!='')
        {
          axios.get('/test/page/'+this.chapter_id+'/list').then(response => {
           console.log(response);
           this.pagenums=response.data;
          });
        }
        if(this.chapter_id!=''&&this.head_id!='')
        axios.post(this.url+'/test/question/counts',{
            test_id:this.test_id,
            chapter_id:this.chapter_id,
            head_id:this.head_id,
            page_no:this.page_no,
          }).then(response => {
           console.log(response);
           this.typecount=response.data; 
             for (var i =0;i<this.types.length;i++) {
              this.types[i].available=this.typecount[this.types[i].id];
              this.types[i].status="0";
         //this.types[i].push({ is_answer:this.getoption[i].is_answer,option:this.getoption[i].option,});
         }
        });

      },

      submit()
      {
        var formData = new FormData();
        formData.append('test_id',this.test_id);
        formData.append('chapter_id',this.chapter_id);
        formData.append('head_id',this.head_id);
        formData.append('page_no',this.page_no);
        formData.append('typecount',this.types.length);
  
        for(let i=0;i<this.types.length;i++)
        {
          if(typeof this.types[i]['status'] !== "undefined")
          {
            formData.append('status'+i,this.types[i]['status']);
          }
          else
          {
            formData.append('status'+i,'');
          }
        }

        for(let i=0;i<this.types.length;i++)
        {
          if(typeof this.types[i]['count'] !== "undefined")
          {
            formData.append('count'+i,this.types[i]['count']);
          }
          else
          {
            formData.append('count'+i,'');
          }
        }

         for(let i=0;i<this.types.length;i++)
        {
          if(typeof this.types[i]['id'] !== "undefined")
          {
            formData.append('type'+i,this.types[i]['id']);
          }
          else
          {
            formData.append('type'+i,'');
          }
        }

        for(let i=0;i<this.types.length;i++)
        {
          if(typeof this.types[i]['available'] !== "undefined")
          {
            formData.append('available'+i,this.types[i]['available']);
          }
          else
          {
            formData.append('available'+i,'');
          }
        }

      axios.post(this.url+'/test/pattern/'+this.test_id,formData).then(response => {
        this.success=response.data.message;
        this.errors=[];
        this.closeModal();
        location.reload();
      }).catch(error=>{
        this.errors=error.response.data.errors;
      });

      },

      addPattern()
      {
        this.show=1;
      },

      closeModal()
      {
        this.show=0;
        this.chapter_id='';
        this.head_id='';
        this.page_no='';
        this.errors=[];
      },

      deletequestion(id) 
      {
        var thisswal = this;
        swal({
          title: 'Are you sure',
          text: 'Do you want to delete this Pattern?',
          icon: "info",
          buttons: [
            'No',
            'Yes'
          ],
          dangerMode: true,
        }).then(function(isConfirm) {
          if (isConfirm) 
          {
            axios.get(thisswal.url+'/test/pattern/'+id+'/delete').then(response => {
              thisswal.success    = response.data.message;
              thisswal.getlist();
              location.reload();
            });  
          }
          else 
          {
            swal("Cancelled");
          }
        });
      },
    },
  
    created()
    {   
      this.getlist();
      this.addlist();
      bus.$on("dataTestTab", data => {
        if(data!='')
        {

          this.test_tab=data;                   
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