<template>
 <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3" v-bind:class="[this.test_tab==2?'block' :'hidden']">
    <div class="relative">
      <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
      <div class="flex flex-wrap lg:flex-row justify-between items-center my-1">
        <div class="">
          <h1 class="admin-h1">Test Papers</h1>
        </div>
        <div class="relative flex items-center w-8/12 lg:w-1/4 md:w-1/4 justify-end">
          <div class="flex items-center"> 
          </div>
        </div>
      </div>
      <div class="">
        <div class="flex flex-wrap custom-table my-3 overflow-auto">
          <table class="w-full">
            <thead class="bg-grey-light">
              <tr class="border-b">
                <th class="text-left text-sm px-2 py-2 text-grey-darker" > No </th>
                <th class="text-left text-sm px-2 py-2 text-grey-darker">Created At</th>
                <th class="text-left text-sm px-2 py-2 text-grey-darker">Path</th>
                <th class="text-left text-sm px-2 py-2 text-grey-darker" width="10%"> Actions </th>
              </tr>
            </thead>   
            <tbody v-if="this.tests != ''">
              <tr class="border-b" v-for="(test,k1) in tests" :key="k=k1+1"  >
                <td class="py-3 px-2">
                  <p class="font-semibold text-xs">{{k}}</p>
                </td>
                <td class="py-3 px-2">
                  <p class="font-semibold text-xs">{{test.created_at}}</p>
                </td>
                 <td class="py-3 px-2">
                  <!-- <p class="font-semibold text-xs">{{test.question_path}}</p> -->
                  <a :href="test.question_path" target="_blank"  title="Attachment">
                                    <svg class="w-5 h-5 fill-current text-black-500 mx-1" id="Layer" enable-background="new 0 0 64 64" height="512" viewBox="0 0 64 64" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m30.586 45.414c.39.391.902.586 1.414.586s1.023-.195 1.414-.586l10-10c.781-.781.781-2.047 0-2.828s-2.047-.781-2.828 0l-6.586 6.586v-29.172c0-1.104-.896-2-2-2s-2 .896-2 2v29.172l-6.586-6.586c-.78-.781-2.048-.781-2.828 0-.781.781-.781 2.047 0 2.828z"/><path d="m18 56h28c3.309 0 6-2.691 6-6v-8c0-1.104-.896-2-2-2s-2 .896-2 2v8c0 1.103-.897 2-2 2h-28c-1.103 0-2-.897-2-2v-8c0-1.104-.896-2-2-2s-2 .896-2 2v8c0 3.309 2.691 6 6 6z"/></svg>
                                </a>
                </td>
                 
                <td class="py-3 px-2">
                  <div class="flex items-center">
              
                    <a @click="deleteTest(test.id)" title="Delete">
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

    
   

  </div>
</template>

<script>
 import { bus } from "../../../app";
  export default {
    props:['url','mode','test_id'],
    data () {
      return {
        page_count:0,
        page:0,
        total :'',
        tests:[],
        errors:[],
        success:null,
        test_tab:'',
      }
    },

    methods:
    {
      getlist()
      {
        axios.get('/test/question/paper/'+this.test_id+'?page='+this.page).then(response => {
          this.tests    = response.data.data;
          this.page_count = response.data.meta.last_page;
          this.total = response.data.meta.total;
          console.log(response.data.data);    
        });
      },



    deleteTest(id) 
    {
      var thisswal = this;
      swal({
        title: 'Are you sure',
        text: 'Do you want to delete this Paper ?',
        icon: "info",
        buttons: [
          'No',
          'Yes'
        ],
        dangerMode: true,
      }).then(function(isConfirm) {
        if (isConfirm) 
        {
          axios.get(thisswal.url+'/test/paper/'+id+'/delete').then(response => {
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

    

  },
    created()
    {   
      this.getlist();

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