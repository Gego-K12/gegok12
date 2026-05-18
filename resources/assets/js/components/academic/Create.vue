<template>
  <div class="bg-white shadow px-4">
    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
    <div class="flex flex-col lg:flex-row">
      <div class="tw-form-group w-full lg:w-1/3">
        <div class="">
          <div class="mb-2">
            <label for="standard_id" class="tw-form-label">Standard<span class="text-red-500">*</span></label>
          </div>
          <div class="flex flex-col lg:flex-row md:flex-row">
            <div class="w-full lg:w-8/12 md:w-8/12">
              <div class="mb-2">
                <select v-model="standard_id" name="standard_id" id="standard_id" class="tw-form-control w-full" v-on:change="getStandard">
                  <option value="" disabled="disabled">Select Standard</option>
                  <option v-for="standard in standardlist" v-bind:value="standard.id">{{ convertInteger(standard.name) }}</option>
                </select>
              </div>
              <span v-if="errors.standard_id" class="text-red-500 text-xs font-semibold">{{errors.standard_id[0]}}</span>
            </div> 
            <!-- <div class="w-4/12">
              <div class="lg:mx-3 md:mx-3">
                <a href="#" class="bg-blue-500 rounded text-sm text-white px-2 py-1 whitespace-no-wrap" @click="showModal('standard')">Add New Standard</a>
              </div> 
            </div> -->
          </div>
        </div>
      </div>
    </div>

    <!-- <div v-if="this.show == 'standard'" class="modal modal-mask">
      <div class="modal-wrapper px-4">
        <div class="modal-container w-full max-w-md px-8 mx-auto">
          <div class="modal-header flex justify-between items-center">
            <h2>Add Standard</h2>
               <button id="close-button" class="modal-default-button text-2xl py-1"  @click="closeModal()">
                  &times;
              </button>
          </div>
            <div v-if="Object.keys(this.standardlist).length > 0">
                <div class="modal-body">
                    <div class="flex flex-col">
                      <div class="w-full lg:w-1/4"> 
                        <label for="position" class="tw-form-label">Position<span class="text-red-500">*</span></label>
                      </div>
                      <div class="my-2 w-full">
                            <select name="position" v-model="position" id="position" class="tw-form-control w-full">
                                <option value="" disabled>Select Position</option>
                                <option v-for="list in positionlist" v-bind:value="list.id">{{ list.name }}</option>
                            </select>
                      </div>
                      <span v-if="errors.position" class="text-red-500 text-xs font-semibold">{{errors.position[0]}}</span>
                    </div>
                </div>
                <div class="modal-body" v-if="this.position != ''">
                    <div class="flex flex-col">
                      <div class="w-full lg:w-1/4"> 
                        <label for="ref_standard_id" class="tw-form-label">Standard<span class="text-red-500">*</span></label>
                      </div>
                      <div class="my-2 w-full">
                            <select name="ref_standard_id" v-model="ref_standard_id" id="ref_standard_id" class="tw-form-control w-full">
                                <option value="" disabled>Select Position</option>
                                <option v-for="list in standardlist" v-bind:value="list.id">{{ convertInteger(list.name) }}</option>
                            </select>
                      </div>
                      <span v-if="errors.ref_standard_id" class="text-red-500 text-xs font-semibold">{{errors.ref_standard_id[0]}}</span>
                    </div>
                </div>
            </div>
          <div class="modal-body">
            <div class="flex flex-col">
              <div class="w-full lg:w-1/4"> 
                <label for="standard" class="tw-form-label">Standard Name<span class="text-red-500">*</span></label>
              </div>
              <div class="my-2 w-full">
                <input type="text" class="tw-form-control w-full" id="standard" v-model="standard" name="standard" Placeholder="Standard">
                <span class="text-xs">Enter Standard Number , for example 10</span>
              </div>
              <span v-if="errors.standard" class="text-red-500 text-xs font-semibold">{{errors.standard[0]}}</span>
            </div>
          </div>
          <div class="my-6">
            <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="addStandard()">Submit</a>
          </div>
        </div>
      </div>
    </div> -->

    <div class="flex flex-col lg:flex-row">
      <div class="tw-form-group w-full lg:w-1/3">
        <div class="">
          <div class="mb-2">
            <label for="section_id" class="tw-form-label">Section<span class="text-red-500">*</span></label>
          </div>
          <div class="flex flex-col lg:flex-row md:flex-row">
            <div class="w-full lg:w-8/12 md:w-8/12">
              <div class="mb-2">
                <select v-model="section_id" name="section_id" id="section_id" class="tw-form-control w-full" v-on:change="addRow()">
                  <option value="" disabled="disabled">Select Section</option>
                  <option v-for="section in sectionlist" v-bind:value="section.id">{{ section.name }}</option>
                </select>
              </div>
              <span v-if="errors.section_id" class="text-red-500 text-xs font-semibold">{{errors.section_id[0]}}</span>
            </div> 
            <div class="w-full lg:w-4/12 md:w-4/12">
              <div class="lg:mx-3 md:mx-3">
                <a href="#" class="bg-blue-500 rounded text-sm text-white px-2 py-1 whitespace-no-wrap" @click="showModal('section')">Add New Section</a>
              </div> 
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-if="this.show == 'section'" class="modal modal-mask">
      <div class="modal-wrapper px-4">
        <div class="modal-container w-full max-w-md px-8 mx-auto">
          <div class="modal-header flex justify-between items-center">
            <h2>Add Section</h2>
               <button id="close-button" class="modal-default-button text-2xl py-1" @click="closeModal()">
                  &times;
              </button>
          </div>
          <div class="modal-body">
            <div class="flex flex-col">
              <div class="w-full lg:w-1/4"> 
                <label for="section" class="tw-form-label">Section Name<span class="text-red-500">*</span></label>
              </div>
              <div class="my-2 w-full">
                <input type="text" class="tw-form-control w-full" id="section" v-model="section" name="section" Placeholder="Section">
                <span v-if="errors.section" class="text-red-500 text-xs font-semibold">{{errors.section[0]}}</span>
              </div>
            </div>
          </div>
          <div class="my-6">
            <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="addSection()">Submit</a>
          </div>
        </div>
      </div>
    </div>

    <div class="flex flex-col lg:flex-row" v-if="this.standard_name == '11' | this.standard_name == '12'">
      <div class="tw-form-group w-full lg:w-1/3">
        <div class="">
          <div class="mb-2">
            <label for="stream" class="tw-form-label">Stream<span class="text-red-500">*</span></label>
          </div>
          <div class="mb-2">
            <select v-model="stream" name="stream" id="stream" class="tw-form-control w-full">
              <option value="" disabled="disabled">Select Stream</option>
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
              <label for="class_teacher_id" class="tw-form-label">Class Teacher<!-- <span class="text-red-500">*</span> --></label>
            </div>
            <div class="w-full lg:w-8/12 md:w-full">
            <div class="mb-2">
              <select class="tw-form-control w-full" id="class_teacher_id" v-model="class_teacher_id" name="class_teacher_id">
                <option value="" disabled>Select Class Teacher</option>
                <option v-for="teacher in teacherlist" v-bind:value="teacher.id">{{ teacher.fullname }}</option>
              </select>
            </div>
            <span v-if="errors.class_teacher_id" class="text-red-500 text-xs font-semibold">{{errors.class_teacher_id[0]}}</span>
            </div>
          </div>
          <!-- <div class="w-full my-6 lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="no_of_students" class="tw-form-label">Available Seats<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2">
              <input type="text" v-model="no_of_students" name="no_of_students" id="mobile_no" class="tw-form-control w-full
              " placeholder="Available Seats">
            </div>
            <span v-if="errors.no_of_students" class="text-red-500 text-xs font-semibold">{{errors.no_of_students[0]}}</span>
          </div> -->
        </div>
      </div>
    </div>

    <div v-if="this.show == 'subject'" class="modal modal-mask">
      <div class="modal-wrapper px-4">
        <div class="modal-container w-full max-w-md px-8 mx-auto">
          <div class="modal-header flex justify-between items-center">
            <h2>Add Subject</h2>
               <button id="close-button" class="modal-default-button text-2xl py-1"  @click="closeModal()">
                  &times;
              </button>
          </div>
          <div class="modal-body">
            <div class="flex items-center">
              <div class="w-full lg:w-1/4"> 
                <label for="subject" class="tw-form-label">Subject Name<span class="text-red-500">*</span></label>
              </div>
              <div class="my-2 w-full lg:w-3/4">
                <input type="text" class="tw-form-control w-full" id="subject" v-model="subject" name="subject" Placeholder="Subject">
                <span v-if="errors.subject" class="text-red-500 text-xs font-semibold">{{errors.subject[0]}}</span>
              </div>
            </div>
          </div>
          <div class="modal-body">
            <div class="flex items-center">
              <div class="w-full lg:w-1/4"> 
                <label for="code" class="tw-form-label">Subject Code</label>
              </div>
              <div class="my-2 w-full lg:w-3/4">
                <input type="text" class="tw-form-control w-full" id="code" v-model="code" name="code" Placeholder="Subject Code">
                <span v-if="errors.code" class="text-red-500 text-xs font-semibold">{{errors.code[0]}}</span>
              </div>
            </div>
          </div>

          <div class="modal-body">
            <div class="flex items-center">
              <div class="w-full lg:w-1/4"> 
                <label for="type" class="tw-form-label">Subject Type<span class="text-red-500">*</span></label>
              </div>
              <div class="my-2 w-full lg:w-3/4">
                <select class="tw-form-control w-full" id="type" v-model="type" name="type">
                  <option value="" disabled>Select Type</option>
                  <option value="core">Core</option>
                  <option value="elective">Elective</option>
                </select>
                <span v-if="errors.type" class="text-red-500 text-xs font-semibold">{{errors.type[0]}}</span>
              </div>
            </div>
          </div>

          <!-- <div class="modal-body">
            <div class="flex items-center">
              <div class="w-full lg:w-1/4">
                <label for="subject_standard_id" class="tw-form-label">Standard<span class="text-red-500">*</span></label>
              </div>
              <div class="my-2 w-full lg:w-3/4">
                <select v-model="subject_standard_id" name="subject_standard_id" id="subject_standard_id" class="tw-form-control w-full">
                  <option value="" disabled="disabled">Select Standard</option>
                  <option v-for="standard in standardlist" v-bind:value="standard.id">{{ convertInteger(standard.name) }}</option>
                </select>
              </div>
              <span v-if="errors.subject_standard_id" class="text-red-500 text-xs font-semibold">{{errors.subject_standard_id[0]}}</span>
            </div> 
          </div>
          <div class="modal-body">
            <div class="flex items-center">
              <div class="w-full lg:w-1/4">
                <label for="subject_section_id" class="tw-form-label">Section<span class="text-red-500">*</span></label>
              </div>
              <div class="my-2 w-full lg:w-3/4">
                <select v-model="subject_section_id" name="subject_section_id" id="subject_section_id" class="tw-form-control w-full">
                  <option value="" disabled="disabled">Select Section</option>
                  <option v-for="section in sectionlist" v-bind:value="section.id">{{ section.name }}</option>
                </select>
              </div>
              <span v-if="errors.subject_section_id" class="text-red-500 text-xs font-semibold">{{errors.subject_section_id[0]}}</span>
            </div> 
          </div> -->
          <div class="my-6">
            <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="addSubject()">Submit</a>
          </div>
        </div>
      </div>
    </div>

    <div class="tw-form-group" showv-if="this.standard_id != '' & this.section_id != '' ">
      <div class="flex flex-col lg:flex-row">
        <div class="w-full lg:w-1/3 md:w-1/3 flex flex-col lg:flex-row">
          <div class="w-full lg:w-8/12 md:w-8/12">
            <div class="mb-2">
              <label for="class_teacher_id" class="tw-form-label">Subjects<span class="text-red-500">*</span></label>
            </div>
          </div>
          <!-- <div class="w-4/12 lg:mx-1 md:m-1">
            <div class="lg:mx-3 md:mx-0">
              <a href="#" class="bg-blue-500 rounded text-sm text-white px-2 py-1 whitespace-no-wrap" @click="showModal('subject')">Add New Subject</a>
            </div>
          </div> --> 
        </div>
      </div>
    </div>

        <div class="w-1/2 flex flex-wrap" v-if="this.standard_id != '' & this.section_id != '' ">
            <div class="w-full lg:w-1/2 md:w-1/2 my-2 relative" v-for="subjects in subjectlist[this.standard_id][this.section_id]">
                <div class="flex items-center">
                    <input class="w-5 h-5" type="checkbox" :value="subjects.id" v-on:change="addRow(subjects.id)">
                    <span class="mx-2">{{ subjects.name }}</span>
                </div>
            </div>            
            <div class="w-full lg:w-1/2 md:w-1/2 my-2 relative">
                <div class="flex items-center">
                    <a href="#" class="no-underline text-white px-4 flex items-center blue-bg py-1 justify-center rounded" @click="showModal('subject')">
                        <span class="mx-1 text-sm font-semibold">Add</span>
                        <svg class="w-3 h-3 fill-current text-white" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 409.6 409.6" style="enable-background:new 0 0 409.6 409.6;" xml:space="preserve"><g><g><path d="M392.533,187.733H221.867V17.067C221.867,7.641,214.226,0,204.8,0s-17.067,7.641-17.067,17.067v170.667H17.067 C7.641,187.733,0,195.374,0,204.8s7.641,17.067,17.067,17.067h170.667v170.667c0,9.426,7.641,17.067,17.067,17.067 s17.067-7.641,17.067-17.067V221.867h170.667c9.426,0,17.067-7.641,17.067-17.067S401.959,187.733,392.533,187.733z"/></g></g></svg>
                    </a> 
                </div>
            </div>
        </div>

    <div class="tw-form-group hidden" id="table_body" v-if="this.standard_id != '' & this.section_id != '' ">
      <table class="w-full lg:w-3/4 md:w-3/4 border" v-if="Object.keys(inputs).length > 0">
        <thead class="bg-gray-400">
          <tr class="border-b">
            <th class="tw-form-label py-2">Subject<span class="text-red-500">*</span></th>
            <th class="tw-form-label py-2">Teacher<!-- <span class="text-red-500">*</span> --></th>
            <template v-if="this.standard_name == '12' | this.standard_name == '11'">
            <th class="tw-form-label py-2" showv-if="this.standard_name != '12'">Subject Type<!-- <span class="text-red-500">*</span> --></th>
          </template>
            <th class="tw-form-label py-2">Delete<!-- <span class="text-red-500">*</span> --></th>
            
           
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-b" v-for="(input, index) in inputs">
            <td class="py-3 px-2">
              <select class="tw-form-control w-full" id="subject_id" v-model="input.subject_id" name="subject_id[]">
                <option value="" disabled>Select Subject</option>
                <option v-for="subject in subjectlist[standard_id][section_id]" v-bind:value="subject.id">{{ subject.name }}</option>
              </select>
              <span v-if="errors['subject_id'+index]" class="text-red-500 text-xs font-semibold">{{errors['subject_id'+index][0]}}</span>
            </td>

            <td class="py-3 px-2">
              <select class="tw-form-control w-full" id="teacher_id" v-model="input.teacher_id" name="teacher_id[]">
                <option value="" disabled>Select Teacher</option>
                <option v-for="teacher in teacherlist" v-bind:value="teacher.id">{{ teacher.fullname }}</option>
              </select>
              <span v-if="errors['teacher_id'+index]" class="text-red-500 text-xs font-semibold">{{errors['teacher_id'+index][0]}}</span>
            </td>


            <td class="py-3 px-2">
              <select class="tw-form-control w-full" id="subject_type" v-model="input.subject_type" name="subject_type[]">
                  <option value="">Select subject Type</option>
                  <option value="science">Science</option>
                  <option value="arts">Arts</option>
                  <option value="language">Language</option>
                  <option value="group_dedicated_subject">Dedicated Subject for group (eg:biology,cs,Business Maths)</option>
                </select>
              <span v-if="errors['subject_type'+index]" class="text-red-500 text-xs font-semibold">{{errors['subject_type'+index][0]}}</span>
            </td>

            <td>
              <a href="#" class="btn-times" @click="deleteRow(index)" title="Delete">
                <svg data-v-689010ab="" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-4 h-4 fill-current text-black-500"><g data-v-689010ab=""><g data-v-689010ab=""><g data-v-689010ab=""><polygon data-v-689010ab="" points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804"></polygon><rect data-v-689010ab="" x="235.948" y="175.791" width="40.104" height="237.285"></rect><polygon data-v-689010ab="" points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804"></polygon><path data-v-689010ab="" d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74 c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474 L380.665,471.896z"></path></g></g></g> <g data-v-689010ab=""><g data-v-689010ab=""><path data-v-689010ab="" d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42 C354.924,14.992,339.932,0,321.504,0z"></path></g></g></svg>
              </a>
            </td>
           
           
          </tr>
        </tbody>
      </table>
    </div>
     
    <div class="mt-2 pb-4">
      <a href="#" dusk="submit-btn" class="btn btn-primary submit-btn" @click="addStandardLink()">Submit</a>
    </div>
  </div>
</template>

<script> 
export default {
  props:['url'],
  data(){
    return {
      list:[],
      academic_year_id:'',
      standardLink_id:'',
      standard_id:'',
      section_id:'',
      standard:'',
      section:'',
      subject:'',
      code:'',
      type:'',
      no_of_students:'',
      stream:'',
      other_stream:'',
      class_teacher_id:'',
      subject_id:'',
      teacher_id:'',
      subject_type:'',
      type:'',
      subject_standard_id:'',
      subject_section_id:'',
      position:'',
      ref_standard_id:'',
      positionlist:[{id : 'before' , name : 'Before'} , {id : 'after' , name : 'After'}],
      teacherlist:[],
      subjectlist:[],
      standardlist:[],
      sectionlist:[],
      streamlist:[{'id' : 'science' , 'name' : 'Science'} , {'id' : 'arts' , 'name' : 'Arts'} , {'id' : 'others' , 'name' : 'Others'}],
      show:0,
      standard_name:'',
      errors:[],
      success:null,
      inputs: [],
    }
  },

  methods:
  {
    /*groupBy(array, key)
    {
      const result = {}
      var count = Object.keys(array).length;
      var list = Object.keys(array);
      for(var i = 0 , array , list , key ; i < count ; i++)
      { 
        if(list[i] == key)
        { 
          return array[key];
        }
      }
    },

    addRow()
    {
      this.inputs.splice(0,1);
      var standard_subject = this.groupBy(this.subjectlist, this.standard_id);
      if( standard_subject != undefined)
      {
        var subjects = this.groupBy(standard_subject, this.section_id);
        var count = (subjects).length;
  
        for(var i=0,subjects ; i < count ; i++)
        {
          this.inputs.push({
            subject_id:subjects[i].id,
            teacher_id:'',
          });
        }
      }
      else
      {
        alert("Add Subject for this Class")
      }
    },*/

    addRow(id)
    {
        if($('#table_body').hasClass('hidden'))
        {
            $('#table_body').addClass('block').removeClass('hidden');
            this.inputs.splice(0,1);
        }
        this.inputs.push({
            subject_id:id,
            teacher_id:'',
            subject_type:'',

           
        });
    },

    convertInteger(num)
    {
      return num;
    },

    getStandard()
    {
        axios.get('/admin/getStandard?standard_id='+this.standard_id).then(response => {
            this.standard_name = response.data.name;
            //console.log(this.standard_name);
        });
    },

    addStandard()
    {
      this.errors=[];
      this.success=null;

      let formData=new FormData(); 

      formData.append('position',this.position);
      formData.append('ref_standard_id',this.ref_standard_id);
      formData.append('standard',this.standard);
      formData.append('standardlist',this.standardlist);

      axios.post('/admin/standard/add',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
          this.success = response.data.success;
          this.closeModal();
          this.getData(); 
          //window.location.reload();
        }).catch(error => {
          this.errors = error.response.data.errors;
        });
    },

    addSection()
    {
      this.errors=[];
      this.success=null;

      let formData=new FormData(); 

      formData.append('section',this.section);

      axios.post('/admin/section/add',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
          this.success = response.data.success;;
          this.closeModal();
          this.getData(); 
          //window.location.reload();
        }).catch(error => {
          this.errors = error.response.data.errors;
        });
    },

    addSubject()
    {
      this.errors=[];
      this.success=null;

      let formData=new FormData(); 

      formData.append('subject_standard_id',this.standard_id);
      formData.append('subject_section_id',this.section_id);
      formData.append('subject',this.subject);
      formData.append('code',this.code);
      formData.append('type',this.type);

      axios.post('/admin/subjects/add',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
          this.success = response.data.success;;
          this.closeModal();
          this.getData(); 
          //window.location.reload();
        }).catch(error => {
          this.errors = error.response.data.errors;
        });
    },

    addStandardLink()
    {
      this.errors=[];
      this.success=null;

      let formData=new FormData(); 

      formData.append('standardLink_id',this.standardLink_id);
      formData.append('standard_id',this.standard_id);
      formData.append('standard_name',this.standard_name);
      formData.append('section_id',this.section_id);
      formData.append('class_teacher_id',this.class_teacher_id);
      formData.append('no_of_students',this.no_of_students);
      formData.append('stream',this.stream);
      formData.append('other_stream',this.other_stream);
      formData.append('count',this.inputs.length);
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
       
       
      }
        axios.post('/admin/standardLink/add',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
          this.success = response.data.success; 
          //window.location.reload();
        }).catch(error => {
          this.errors = error.response.data.errors;
        });
    },

    showModal(name)
    {
      this.show = name;
    },

    closeModal()
    {
      this.show = 0;
    },

    getData()
    {
      axios.get('/admin/standardLink/list').then(response => {
        this.list = response.data;
        //console.log(this.list)
        this.setData();   
      });
    },

    setData()
    {
      if(Object.keys(this.list).length>0)
      {
        this.academic_year_id   = this.list.academic_year_id;
        if(this.academic_year_id == null)
        {
          alert("Add Academic Year")
        }
        else
        {
          this.standardlist       = this.list.standardlist;
          this.sectionlist        = this.list.sectionlist;
          this.subjectlist        = this.list.subjectlist;
          this.teacherlist        = this.list.teacherlist;
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
  /*height: 550px;*/
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