<template>
  <div class="bg-white shadow px-4 py-2">
    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
    <div class="tw-form-group">
      <div class="flex flex-col lg:flex-row">
        <div class="w-full lg:w-1/3 md:w-8/12">
          <div class="w-full lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="standard_id" class="tw-form-label">Class</label>
            </div>
            <div class="mb-2">
              <input type="text" name="standard_id" v-model="standard_id" id="standard_id" class="tw-form-control w-full" readonly>
            </div>
          </div>

          <div class="w-full my-6 lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="section_id" class="tw-form-label">Section</label>
            </div>
            <div class="mb-2">
              <input type="text" v-model="section_id" name="section_id" id="section_id" class="tw-form-control w-full" readonly>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="flex flex-col lg:flex-row" v-if="standard == '11' | standard == '12'">
      <div class="tw-form-group w-full lg:w-8/12">
        <div class="">
          <div class="mb-2">
            <label for="stream" class="tw-form-label">Stream<span class="text-red-500">*</span></label>
          </div>
          <div class="mb-2">
            <select v-model="stream" name="stream" id="stream" class="tw-form-control w-full">
              <option v-for="streams in streamlist" v-bind:value="streams.id">{{ streams.name }}</option>
            </select>
          </div>
          <span v-if="errors.stream" class="text-red-500 text-xs font-semibold">{{errors.stream[0]}}</span>
        </div> 
      </div>

      <div class="tw-form-group w-full lg:w-1/3 mx-2" v-if="this.stream == 'others'">
        <div class="">
          <div class="mb-2">
            <label for="other_stream" class="tw-form-label">Stream Name<span class="text-red-500">*</span></label>
          </div>
          <div class="mb-2">
            <input type="text" name="other_stream" v-model="other_stream" class="tw-form-control w-full" placeholder="Enter Stream Name">
          </div>
          <span v-if="errors.other_stream" class="text-red-500 text-xs font-semibold">{{errors.other_stream[0]}}</span>
        </div> 
      </div>
    </div>

    <div class="tw-form-group">
      <div class="flex flex-col lg:flex-row">
        <div class="w-full lg:w-1/3 md:w-8/12">
          <div class="w-full  lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="class_teacher_id" class="tw-form-label">Class Teacher<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2">
              <select class="tw-form-control w-full" id="class_teacher_id" v-model="class_teacher_id" name="class_teacher_id">
                <option v-for="teacher in teacherlist" v-bind:value="teacher.id">{{ teacher.fullname }}</option>
              </select>
            </div>
            <span v-if="errors.class_teacher_id" class="text-red-500 text-xs font-semibold">{{errors.class_teacher_id[0]}}</span>
          </div>

          <!-- <div class="w-full my-6 lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="no_of_students" class="tw-form-label">Available Seats<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2">
              <input type="text" v-model="no_of_students" name="no_of_students" id="mobile_no" class="tw-form-control w-full" placeholder="Available Seats">
            </div>
            <span v-if="errors.no_of_students" class="text-red-500 text-xs font-semibold">{{errors.no_of_students[0]}}</span>
          </div> -->
        </div>
      </div>
    </div>

    <div class="tw-form-group pt-6">
      <table class="w-full lg:w-3/4 md:w-3/4 border mb-5">
        <thead class="bg-gray-400">
          <tr class="border-b">
            <th class="tw-form-label px-3 py-2 text-left">Subject<span class="text-red-500">*</span></th>
            <th class="tw-form-label px-3 py-2 text-left">Teacher<span class="text-red-500">*</span></th>
            
            <!-- <th class="tw-form-label py-2" showv-if="this.standard_name != '12'">Subject Type<span class="text-red-500">*</span> </th> -->
          <!-- <th class="tw-form-label px-3 py-2 text-left">Number of periods<span class="text-red-500">*</span></th> -->
            
            <th class="tw-form-label">Delete</th>
          </tr>
        </thead>
        <tbody>
          <tr class="" v-for="(input, index) in inputs">
            <td class="py-4 px-2">
              <select class="tw-form-control w-full" id="subject_id" v-model="input.subject_id" name="subject_id[]">
                <option value="" disabled>Select Subject</option>
                <option v-for="subject in subjectlist" v-bind:value="subject.id">{{ subject.name }}</option>
              </select>
              <span v-if="errors['subject_id'+index]" class="text-red-500 text-xs font-semibold">{{errors['subject_id'+index][0]}}</span>
            </td>

            <td class="py-4 px-2">
              <select class="tw-form-control w-full" id="teacher_id" v-model="input.teacher_id" name="teacher_id[]">
                <option value="" disabled>Select Teacher</option>
                <option v-for="teacher in teacherlist" v-bind:value="teacher.id">{{ teacher.fullname }}</option>
              </select>
              <span v-if="errors['teacher_id'+index]" class="text-red-500 text-xs font-semibold">{{errors['teacher_id'+index][0]}}</span>
            </td>

           <!-- <td class="py-3 px-2">
                 <select id="subject_type" v-model="input.subject_type" name="subject_type[]" class="tw-form-control w-full">
              <option v-for="type in subject_type" v-bind:value="type.id">{{ type.name }}</option>
            </select>
              <span v-if="errors['subject_type'+index]" class="text-red-500 text-xs font-semibold">{{errors['subject_type'+index][0]}}</span>
            </td>

            <td class="py-3 px-2">
                 <input type="text" v-model="input.no_of_periods" name="">
                 <span v-if="errors['no_of_periods'+index]" class="text-red-500 text-xs font-semibold">{{errors['no_of_periods'+index][0]}}</span>
            </td> -->

            <td class="px-4">
              <a href="#" class="btn-times text-white px-1 py-1" @click="deleteRow(index)" title="Delete">
                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-4 h-4 fill-current text-red-500"><g><g><g><polygon points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804"></polygon><rect x="235.948" y="175.791" width="40.104" height="237.285"></rect><polygon points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804"></polygon><path d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74 c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474 L380.665,471.896z"></path></g></g></g> <g><g><path d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42 C354.924,14.992,339.932,0,321.504,0z"></path></g></g></svg>
              </a>
            </td>
             

          </tr>
        </tbody>
      </table>
      <a id="add_new" href="#" @click="addNew" class="border rounded text-sm p-2 bg-gray-200">+ Add Another</a>
    </div>
     
    <div class="my-6">
      <a href="#" dusk="submit-btn" class="btn btn-primary submit-btn" @click="addStandardLink()">Submit</a>
    </div>
  </div>
</template>

<script> 
export default {
  props:['url' , 'id'],
  data(){
    return {
      list:[],
      standardLink_id:'',
      standard_id:'',
      standard:'',
      section_id:'',
      no_of_students:'',
      stream:'',
      other_stream:'',
      class_teacher_id:'',
      subject_id:'',
      teacher_id:'',
    
      teacherlist:[],
      subjectlist:[],
      streamlist:[{'id' : 'science' , 'name' : 'Science'} , {'id' : 'arts' , 'name' : 'Arts'} , {'id' : 'others' , 'name' : 'Others'}],
      subject_type:[{'id' : 'science' , 'name' : 'Science'} , {'id' : 'arts' , 'name' : 'Arts'} , {'id' : 'language' , 'name' : 'Language'},{'id':'group_dedicated_subject','name':'Dedicated Subject For Group'}],
      errors:[],
      success:null,
      inputs: [{
        subject_id:'',
        teacher_id:'',
       
      }],
    }
  },

  methods:
  {
    addRow()
    {
      this.inputs.splice(0,1);
      var count = (this.subjectlist).length;
      var subjects = this.subjectlist;
      for(var i=0,subjects ; i < count ; i++)
      {
        this.inputs.push({
          subject_id:subjects[i].id,
          teacher_id:'',
          subject_type:'',
          no_of_periods:'',
          
        });
      }
      $('#add_new').addClass('hidden').removeClass('block');
    },

    addNew()
    {
      this.inputs.push({
        subject_id:'',
        teacher_id:'',
        no_of_periods:'',
       
      });
    },
    
    addStandardLink()
    {
      this.errors=[];
      this.success=null;

      let formData=new FormData(); 

      formData.append('id',this.id);
      formData.append('class_teacher_id',this.class_teacher_id);
      //formData.append('no_of_students',this.no_of_students);
      formData.append('stream',this.stream);
      formData.append('standard',this.standard);
      formData.append('other_stream',this.other_stream);
      formData.append('count',this.inputs.length);
      formData.append('std_id',this.id);
      for(let i=0; i<this.inputs.length;i++)
      {
        if(typeof this.inputs[i]['subject_id'] !== "undefined")
        {
          formData.append('subject_id'+i,this.inputs[i]['subject_id']);
        }
        else
        {
          formData.append('subject_id'+i,'');
        }

        if(typeof this.inputs[i]['teacher_id'] !== "undefined")
        {
          formData.append('teacher_id'+i,this.inputs[i]['teacher_id']);
        }
        else
        {
          formData.append('teacher_id'+i,'');
        }
        
        if(typeof this.inputs[i]['subject_type'] !== "subject_type")
        {
          formData.append('subject_type'+i,this.inputs[i]['subject_type']);
        }
        else
        {
          formData.append('subject_type'+i,'');
        }

        if(typeof this.inputs[i]['no_of_periods'] !== "no_of_periods")
        {
          formData.append('no_of_periods'+i,this.inputs[i]['no_of_periods']);
        }
        else
        {
          formData.append('no_of_periods'+i,'');
        }
       
         

      }
        axios.post('/admin/standardLink/edit/'+this.id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
          this.success = response.data.success; 
          //window.location.reload();
        }).catch(error => {
          this.errors = error.response.data.errors;
        });
    },

    getData()
    {
      axios.get('/admin/standardLink/editlist/'+this.id).then(response => {
        this.list = response.data;
        //console.log(this.list)
        this.setData();   
      });
    },

    setData()
    {
      if(Object.keys(this.list).length>0)
      {
        this.subjectlist        = this.list.subjectlist;
        this.teacherlist        = this.list.teacherlist;
        this.standard_id        = this.list.standard_id;
        this.standard           = this.list.standard;
        this.section_id         = this.list.section_id;
        this.class_teacher_id   = this.list.class_teacher_id;
        this.no_of_students     = this.list.no_of_students;
        this.stream             = this.list.stream;
        if(this.list.inputs != null)
        {
          this.inputs             = this.list.inputs;
        }
        else
        {
          this.addRow();
        }
      }
    },

    deleteRow(index) 
    {
        var thisswal = this;
        swal({
            title: 'Are you sure',
            text: 'Do you want to remove this Subject and Teacher ?',
            icon: "info",
            buttons: [
                'No',
                'Yes'
            ],
            dangerMode: true,
        }).then(function(isConfirm) {
            if (isConfirm) 
            {
                thisswal.inputs.splice(index,1); 
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
    this.getData();
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