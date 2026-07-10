<template>
  <div class="relative">
    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
    <div class="flex flex-wrap lg:flex-row justify-between items-center">
      <div class="">
        <h1 class="admin-h1 my-3">Quiz</h1>
      </div>
      <div class="relative flex items-center w-8/12 lg:w-1/4 md:w-1/4 justify-end">
        <div class="flex items-center">
          <!-- <div class="mb-1 w-8 mx-1">
            <input type="checkbox" name="showCompleted" id="showCompleted" v-model="showCompleted" class="tw-form-control w-full" >
          </div>
          <div class="mb-2 lg:w-full">
            <label for="showCompleted" class="tw-form-label">Show Completed</label>
          </div> -->
            
        
        </div>
      </div>
    </div>
    <div class="">
      <div class="flex flex-wrap custom-table my-3 overflow-auto">
        <table class="w-full">
          <thead class="bg-grey-light" >
            <tr class="border-b">
              <th class="text-left text-sm px-2 py-2 text-grey-darker" > No </th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker"> Question </th>
                <th class="text-left text-sm px-2 py-2 text-grey-darker"> Type </th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker"> Options </th>

             <th class="text-left text-sm px-2 py-2 text-grey-darker"> Correct answer </th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker"> Test answer </th>
            
               <th class="text-left text-sm px-2 py-2 text-grey-darker"> Status </th>
            
            </tr>
          </thead>   
          <tbody v-if="this.details != ''"  >
            <tr class="border-b" v-for="(details,k1) in details" :key="k=k1+1"   >
              <td class="py-3 px-2" >
                <p class="font-semibold text-xs">{{k}}</p>
              </td>
              <td class="py-3 px-2" >
                
                  <div class="font-semibold text-xs" v-html="details.questions"></div>
              </td>
               <td class="py-3 px-2" >
                <p class="font-semibold text-xs">{{details.type}}</p>
              </td>
              <td  class="py-3 px-2" >
               
                <p  v-for="opt in details.options" class="font-semibold text-xs">{{opt.option}}</p>
            
              </td>
               <td  class="py-3 px-2" >
               
                <p   v-for="opt in details.options"  class="font-semibold text-xs"><span v-if="opt.is_answer==1">{{opt.option}}</span></p>

            
              </td>
              <td v-if="details.answered!=''" class="py-3 px-2" >
               
                 <p  v-for="answer in details.answered" class="font-semibold text-xs">{{answer}}</p>
                  
              </td>
              <td v-else="" class="py-3 px-2" >
               
                 <p   class="font-semibold text-xs">Not answered</p>
                  
              </td>
              <td class="py-3 px-2" >
               

                 <p v-if="details.flag==1" class="font-semibold text-xs bg-teal-500 text-white rounded inline-block px-1">correct</p>

                 <p v-else="" class="font-semibold text-xs bg-red-500 text-white rounded inline-block px-1">wrong</p>
          </td>
          </tr>
          </tbody >
          <tbody v-else=""  >
            <tr class="border-b">
              <td colspan="8">
                <p class="font-semibold text-s" style="text-align: center">No Records Found</p>
              </td>
            </tr>
          </tbody>
        </table>
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
    </div>
       

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

  methods:{
    getlist()
    {
      axios.get(this.url+'/teacher/quiz/test/'+this.test_id+'/show?page='+this.page).then(response => {
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
  margin: 20px 0;
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