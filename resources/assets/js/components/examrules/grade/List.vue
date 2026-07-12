<template>
  <div class="relative">
    <div v-if="this.success!=null" class="alert alert-success mt-2" id="success-alert">{{this.success}}</div>
    <div class="flex flex-wrap lg:flex-row justify-between items-center">
      <div class="">
        <h1 class="admin-h1 my-3">Grade</h1>
      </div>
    </div>
    <div class="">
      <div class="flex flex-wrap custom-table my-3 overflow-auto">
        <table class="w-full">
          <thead class="bg-grey-light">
            <tr class="border-b">
              <th class="text-left text-sm px-2 py-2 text-grey-darker">Exam type</th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker">Grade name</th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker">Min mark</th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker">Max mark</th>
              <th class="text-left text-sm px-2 py-2 text-grey-darker">Actions</th>
            </tr>
          </thead>   
          <tbody v-if="this.grades != ''">
            <tr class="border-b" v-for="grade in grades" >
              <td class="py-3 px-2">
                <p class="font-semibold text-xs" >{{grade.name}}</p>
              </td>

               <td class="py-3 px-2">
                <p class="font-semibold text-xs">{{grade.grade}}</p>
              </td>
             
              <td class="py-3 px-2">
                <p class="font-semibold text-xs">{{grade.min_mark}}</p>
              </td>

              <td class="py-3 px-2">
                <p class="font-semibold text-xs">{{grade.max_mark}}</p>
              </td>
             
              <td class="py-3 px-2">
                <div class="flex items-center custom_icon">                
                  <a @click="editgrade(grade.id)" class="cursor-pointer" title="Edit">
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 477.873 477.873" xml:space="preserve" class="w-4 h-4 fill-current mx-1"><g><g><path d="M392.533,238.937c-9.426,0-17.067,7.641-17.067,17.067V426.67c0,9.426-7.641,17.067-17.067,17.067H51.2 c-9.426,0-17.067-7.641-17.067-17.067V85.337c0-9.426,7.641-17.067,17.067-17.067H256c9.426,0,17.067-7.641,17.067-17.067 S265.426,34.137,256,34.137H51.2C22.923,34.137,0,57.06,0,85.337V426.67c0,28.277,22.923,51.2,51.2,51.2h307.2 c28.277,0,51.2-22.923,51.2-51.2V256.003C409.6,246.578,401.959,238.937,392.533,238.937z"></path></g></g> <g><g><path d="M458.742,19.142c-12.254-12.256-28.875-19.14-46.206-19.138c-17.341-0.05-33.979,6.846-46.199,19.149L141.534,243.937 c-1.865,1.879-3.272,4.163-4.113,6.673l-34.133,102.4c-2.979,8.943,1.856,18.607,10.799,21.585 c1.735,0.578,3.552,0.873,5.38,0.875c1.832-0.003,3.653-0.297,5.393-0.87l102.4-34.133c2.515-0.84,4.8-2.254,6.673-4.13 l224.802-224.802C484.25,86.023,484.253,44.657,458.742,19.142z M434.603,87.419L212.736,309.286l-66.287,22.135l22.067-66.202 L390.468,43.353c12.202-12.178,31.967-12.158,44.145,0.044c5.817,5.829,9.095,13.72,9.12,21.955 C443.754,73.631,440.467,81.575,434.603,87.419z"></path></g></g></svg>
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
   
   <!-- Edit topic model -->
    <div v-if="this.editshow == 1" class="modal modal-mask">
      <div class="modal-wrapper px-4">
        <div class="modal-container w-full  max-w-md px-8 mx-auto">
          <div class="modal-header flex justify-between items-center">
            <h2>Edit Grade</h2>
            <button id="close-button" class="modal-default-button text-2xl py-1" @click="close()">
              &times;
            </button>
          </div>
          <div class="modal-body">
            <div class="">
              <div class="w-full"> 
                <label for="name" class="tw-form-label">Name</label>
              </div>
              <div class="my-2 w-full">
                <input type="text" name="name" v-model="name" class="tw-form-control w-full"><br>
                <span v-if="errors.name" class="text-red-500 text-xs font-semibold">{{errors.name[0]}}</span>
              </div>
            </div>

            <div class="">
              <div class="w-full"> 
                <label for="min_mark" class="tw-form-label">Min Mark</label>
              </div>
              <div class="my-2 w-full">
                <input type="text" name="min_mark" v-model="min_mark" class="tw-form-control w-full"><br>
                <span v-if="errors.min_mark" class="text-red-500 text-xs font-semibold">{{errors.min_mark[0]}}</span>
              </div>
            </div>

             <div class="">
              <div class="w-full"> 
                <label for="max_mark" class="tw-form-label">Max Mark</label>
              </div>
              <div class="my-2 w-full">
                <input type="text" name="max_mark" v-model="max_mark" class="tw-form-control w-full"><br>
                <span v-if="errors.max_mark" class="text-red-500 text-xs font-semibold">{{errors.max_mark[0]}}</span>
              </div>
            </div>

            <div class="">
              <div class="my-2 w-full lg:w-3/4 ">
                <input type="button" name="" value="submit" @click="updategrade()" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium cursor-pointer">
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

export default {
  props:['url','mode'],
  data () {
    return {
      grades:[],
      edit:[],
      errors:[],
      errors1:[],
      success:null,
      editshow:0,
      name:'',
      min_mark:'',
      max_mark:'',
      grade_id:'',

     
    }
  },

  methods:
  {
    getlist()
    {
      axios.get(this.url+'/'+this.mode+'/exam/grade/list').then(response => {
        this.grades = response.data.data;
        console.log(this.grades);    
      });
    },

    editgrade(id)
    {
      this.editshow=1;
      this.grade_id=id;
      axios.get(this.url+'/'+this.mode+'/exam/grade/'+id).then(response => {
        this.edit = response.data.data;
        this.name=this.edit.grade;
        this.min_mark=this.edit.min_mark;
        this.max_mark=this.edit.max_mark;
        //console.log(this.grades);    
      });

    },

    updategrade()
    {
        this.errors=[];
        this.success=null; 

        let formData=new FormData();

        formData.append('name',this.name);                 
        formData.append('min_mark',this.min_mark);                 
        formData.append('max_mark',this.max_mark);                                

       axios.post(this.url+'/'+this.mode+'/exam/grade/'+this.grade_id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
          this.success = response.data.success;
          this.close();
          this.getlist();
        }).catch(error => {
          this.errors = error.response.data.errors;
        });

    },

    close()
    {
      this.editshow=0;
    },

    deletefacility(id) 
    {
      var thisswal = this;
      swal({
        title: 'Are you sure',
        text: 'Do you want to delete this Facility ?',
        icon: "info",
        buttons: [
          'No',
          'Yes'
        ],
        dangerMode: true,
      }).then(function(isConfirm) {
        if (isConfirm) 
        {
          axios.delete(thisswal.url+'/'+thisswal.mode+'/facility/'+id).then(response => {
             thisswal.success    = response.data.message;
             //thisswal.getlist();
             window.location.reload();
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