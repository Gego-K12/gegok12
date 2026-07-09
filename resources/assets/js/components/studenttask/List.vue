<template>
  <div class="relative">
    <div v-if="this.success!=null" class="alert alert-success mt-2" id="success-alert">{{this.success}}</div>
    <div class="flex flex-wrap lg:flex-row justify-between items-center">
      <div class="">
        <h1 class="admin-h1 my-3">Task</h1>
      </div>
      <div class="relative flex items-center w-8/12 lg:w-1/4 md:w-1/4 justify-end">
        <div class="flex items-center">
          <div class="mb-1 w-8 mx-1">
            <input type="checkbox" name="showCompleted" id="showCompleted" v-model="showCompleted" class="tw-form-control w-full" @change="showCompletedTask($event)">
          </div>
          <div class="mb-2 lg:w-full">
            <label for="showCompleted" class="tw-form-label">Show Completed</label>
          </div>
          <a  class="no-underline text-white px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center cursor-pointer" @click="showModal()">
            <span class="mx-1 text-sm font-semibold">Add </span>
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
              <th class="text-left text-sm px-2 py-2 text-grey-darker">Task Name</th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker">Task Date</th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker">Actions</th>
            </tr>
          </thead>   
          <tbody v-if="this.task != ''">
            <tr class="border-b" v-for="task in task" >
              <td class="py-3 px-2" >
                <p class="font-semibold text-xs">{{task.to_do_list}}</p>
              </td>
             

              <td class="py-3 px-2">
                <p class="font-semibold text-xs">{{task.task_date}}</p>
              </td>
             
              <td class="py-3 px-2">
                <div class="flex items-center">                
                  <a @click="taskEdit(task.id)" class="cursor-pointer">
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 477.873 477.873" xml:space="preserve" class="w-4 h-4 fill-current text-black-500 mx-1"><g><g><path d="M392.533,238.937c-9.426,0-17.067,7.641-17.067,17.067V426.67c0,9.426-7.641,17.067-17.067,17.067H51.2 c-9.426,0-17.067-7.641-17.067-17.067V85.337c0-9.426,7.641-17.067,17.067-17.067H256c9.426,0,17.067-7.641,17.067-17.067 S265.426,34.137,256,34.137H51.2C22.923,34.137,0,57.06,0,85.337V426.67c0,28.277,22.923,51.2,51.2,51.2h307.2 c28.277,0,51.2-22.923,51.2-51.2V256.003C409.6,246.578,401.959,238.937,392.533,238.937z"></path></g></g> <g><g><path d="M458.742,19.142c-12.254-12.256-28.875-19.14-46.206-19.138c-17.341-0.05-33.979,6.846-46.199,19.149L141.534,243.937 c-1.865,1.879-3.272,4.163-4.113,6.673l-34.133,102.4c-2.979,8.943,1.856,18.607,10.799,21.585 c1.735,0.578,3.552,0.873,5.38,0.875c1.832-0.003,3.653-0.297,5.393-0.87l102.4-34.133c2.515-0.84,4.8-2.254,6.673-4.13 l224.802-224.802C484.25,86.023,484.253,44.657,458.742,19.142z M434.603,87.419L212.736,309.286l-66.287,22.135l22.067-66.202 L390.468,43.353c12.202-12.178,31.967-12.158,44.145,0.044c5.817,5.829,9.095,13.72,9.12,21.955 C443.754,73.631,440.467,81.575,434.603,87.419z"></path></g></g></svg>
                  </a>

                  <!-- <a :href="url+'/teacher/quiz/'+topic.id+'/participants/add'" target="_blank">
                    <svg height="512pt" viewBox="-27 0 512 512" width="512pt" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-current text-black-500 mx-2"><path d="m188 492c0 11.046875-8.953125 20-20 20h-88c-44.113281 0-80-35.886719-80-80v-352c0-44.113281 35.886719-80 80-80h245.890625c44.109375 0 80 35.886719 80 80v191c0 11.046875-8.957031 20-20 20-11.046875 0-20-8.953125-20-20v-191c0-22.054688-17.945313-40-40-40h-245.890625c-22.054688 0-40 17.945312-40 40v352c0 22.054688 17.945312 40 40 40h88c11.046875 0 20 8.953125 20 20zm117.890625-372h-206c-11.046875 0-20 8.953125-20 20s8.953125 20 20 20h206c11.042969 0 20-8.953125 20-20s-8.957031-20-20-20zm20 100c0-11.046875-8.957031-20-20-20h-206c-11.046875 0-20 8.953125-20 20s8.953125 20 20 20h206c11.042969 0 20-8.953125 20-20zm-226 60c-11.046875 0-20 8.953125-20 20s8.953125 20 20 20h105.109375c11.046875 0 20-8.953125 20-20s-8.953125-20-20-20zm355.472656 146.496094c-.703125 1.003906-3.113281 4.414062-4.609375 6.300781-6.699218 8.425781-22.378906 28.148437-44.195312 45.558594-27.972656 22.324219-56.757813 33.644531-85.558594 33.644531s-57.585938-11.320312-85.558594-33.644531c-21.816406-17.410157-37.496094-37.136719-44.191406-45.558594-1.5-1.886719-3.910156-5.300781-4.613281-6.300781-4.847657-6.898438-4.847657-16.097656 0-22.996094.703125-1 3.113281-4.414062 4.613281-6.300781 6.695312-8.421875 22.375-28.144531 44.191406-45.554688 27.972656-22.324219 56.757813-33.644531 85.558594-33.644531s57.585938 11.320312 85.558594 33.644531c21.816406 17.410157 37.496094 37.136719 44.191406 45.558594 1.5 1.886719 3.910156 5.300781 4.613281 6.300781 4.847657 6.898438 4.847657 16.09375 0 22.992188zm-41.71875-11.496094c-31.800781-37.832031-62.9375-57-92.644531-57-29.703125 0-60.84375 19.164062-92.644531 57 31.800781 37.832031 62.9375 57 92.644531 57s60.84375-19.164062 92.644531-57zm-91.644531-38c-20.988281 0-38 17.011719-38 38s17.011719 38 38 38 38-17.011719 38-38-17.011719-38-38-38zm0 0"></path></svg>
                  </a> -->

                  <a href="#" @click="deletetask(task.id)">
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-4 h-4 fill-current text-black-500 mx-1"><g><g><g><polygon points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804"></polygon><rect x="235.948" y="175.791" width="40.104" height="237.285"></rect><polygon points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804"></polygon> <path d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74 c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474 L380.665,471.896z"></path></g></g></g> <g><g><path d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42 C354.924,14.992,339.932,0,321.504,0z"></path></g></g></svg>
                  </a>
                </div>
              </td>
            </tr>
          </tbody>
          <tbody v-else="">
            <tr class="border-b">
              <td colspan="9">
                <p class="font-semibold text-s" style="text-align: center">No Records Found</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div v-if="this.show == 1" class="modal modal-mask">
      <div class="modal-wrapper px-4">
        <div class="modal-container w-full  max-w-md px-8 mx-auto">
          <div class="modal-header flex justify-between items-center">
            <h2>Create To Do</h2>
            <button id="close-button" class="modal-default-button text-xl" @click="closeModal()">
              &times;
            </button>
          </div>
          <div class="modal-body">
            <div class="flex flex-col">
              <div class="w-full lg:w-1/4"> 
                <label for="to_do_list" class="tw-form-label">Description</label>
              </div>
              <div class="my-2 w-full">
                <textarea type="text" name="to_do_list" v-model="to_do_list" class="tw-form-control w-full"></textarea><br>
                <span v-if="errors.to_do_list"><p class="text-red-500 text-xs font-semibold">{{errors.to_do_list[0]}}</p></span>
              </div>
            </div>
             <div class="flex flex-col">
              <div class="w-full flex"> 
                <label for="task_date" class="tw-form-label">When</label>
              </div>
              <div class="my-2">
               <input type="date"  name="task_date" v-model="task_date" class="tw-form-control w-full" id="task_date">
                <span v-if="errors.task_date"><p class="text-red-500 text-xs font-semibold">{{errors.task_date[0]}}</p></span>
              </div>
            </div>
            
            <div class="flex items-center">
              <div class="my-2 w-full lg:w-3/4">
                <input type="button" name=""  value="Create" class="btn btn-submit blue-bg text-white rounded px-3 py-1 cursor-pointer text-sm font-medium" @click="addtask()">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Edit topic model -->
       <div v-if="this.editshow == 1" class="modal modal-mask">
      <div class="modal-wrapper px-4">
        <div class="modal-container w-full  max-w-md px-8 mx-auto">
          <div class="modal-header flex justify-between items-center">
            <h2>Edit To Do</h2>
            <button id="close-button" class="modal-default-button text-xl" @click="close()">
              &times;
            </button>
          </div>
          <div class="modal-body">
            <div class="flex flex-col">
              <div class="w-full lg:w-1/4"> 
                <label for="to_do_list" class="tw-form-label">Decription</label>
              </div>
              <div class="my-2 w-full">
                <textarea type="text"  name="to_do_list" v-model="to_do_list" class="tw-form-control w-full"></textarea><br>
                <span v-if="errors.to_do_list"><p class="text-red-500 text-xs font-semibold">{{errors.to_do_list[0]}}</p></span>
              </div>
            </div>
            </div>
            <div class="flex flex-col">
              <div class="w-full flex"> 
                <label for="task_date" class="tw-form-label">When</label>
              </div>
              <div class="my-2 ">
                <input type="date"  name="task_date" v-model="task_date" class="tw-form-control w-full" id="task_date">
                <span v-if="errors.task_date"><p class="text-red-500 text-xs font-semibold">{{errors.task_date[0]}}</p></span>
              </div>
            </div>
            
            <div class="flex items-center">
              <div class="my-2 w-full lg:w-3/4">
                <input type="button" name="" value="Update" @click="updatetask(edittask.id)" class="btn btn-submit blue-bg text-white rounded px-3 py-1 cursor-pointer text-sm font-medium">
              </div>
            </div>
          </div>
        </div>
      </div>
<!-- End modal -->
  </div>
</template>

<script>

export default {
  props:['searchquery','url'],
  data () {
    return {
      task:[],
      edit:[],
      showCompleted:'',
      to_do_list:'',
      task_date:'',
      edittask:'',
      show:0,
      editshow:0,
      params:{},
      errors:[],
      errors1:[],
      success:null,
     
    }
  },

  methods:
  {
    getlist()
    {
      axios.get('/student/task/list').then(response => {
        this.task = response.data.data;
        //console.log(this.topics);    
      });
    },

    showCompletedTask(e)
    {
      if (e.target.checked) 
      {
        this.params = { showCompleted:this.showCompleted };

       
        this.final = this.url+'/student/task/list/?'+this.searchquery;
        
        //console.log(this.final);
        Object.keys(this.params).forEach(key => {
          this.final = this.addParam(this.final, key, this.params[key])
        });

        axios.get(this.final).then(response => {
          this.task = response.data.data;
        });
      }
      else if (!e.target.checked)
      {
       
          this.getlist();
        
      }
    },

     addParam(url, param, value) 
    {
      param = encodeURIComponent(param);
      var r = "([&?]|&amp;)" + param + "\\b(?:=(?:[^&#]*))*";
      var a = document.createElement('a');
      var regex = new RegExp(r);
      var str = param + (value ? "=" + encodeURIComponent(value) : ""); 
      a.href = url;
      var q = a.search.replace(regex, "$1"+str);
      if (q === a.search) 
      {
        a.search += (a.search ? "&" : "") + str;
      } 
      else 
      {
        a.search = q;
      }
      return a.href ;
    },

    addtask()
    {
      axios.post('/student/task/add',{
        to_do_list:this.to_do_list,
        task_date:this.task_date,
      
      }).then(response => {
        this.success = response.data.message;
        this.closeModal();
        this.getlist();
        this.reset();
      }).catch(error=>{
        this.errors=error.response.data.errors;
      }); 
    },

    taskEdit(id)
    {
      this.editshow=1;
      axios.get('/student/task/'+id+'/edit').then(response => {

        this.edittask=response.data;
        this.to_do_list=this.edittask.to_do_list;
        this.task_date=this.edittask.task_date;
       
      });   
    },

    updatetask(id)
    {
      axios.post('/student/task/'+id+'/update',{
        to_do_list:this.to_do_list,
        task_date:this.task_date,
      
      }).then(response => {
        this.success = response.data.message;
        this.close();
        this.getlist();
      }).catch(error=>{
        this.errors=error.response.data.errors;
      });    
    },

    deletetask(id) 
    {
      var thisswal = this;
      swal({
        title: 'Are you sure',
        text: 'Do you want to delete this task ?',
        icon: "info",
        buttons: [
          'No',
          'Yes'
        ],
        dangerMode: true,
      }).then(function(isConfirm) {
        if (isConfirm) 
        {
          axios.delete('/student/task/'+id+'/delete').then(response => {
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

    reset()
    {
      this.to_do_list='';
      this.task_date='';
     
    },
   

    resetedit()
    {
      this.to_do_list='';
      this.task_date='';
          
    },
    showModal()
    {
      this.show = 1;
      this.reset();
    },
    
    closeModal()
    {
      this.show = 0;
     
    },
    close()
    {
      this.editshow=0;
      
      this.resetedit();
    }

   

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