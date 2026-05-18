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
                <select v-model="standard_id" name="standard_id" id="standard_id" class="tw-form-control w-full" v-on:change="addRow()">
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
              <div class="lg:mx-3 md:mx-3 w-40" >
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
              <label for="class_teacher_id" class="tw-form-label">Class Teacher<span class="text-red-500">*</span></label>
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
         <!--  <div class="modal-body">
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
          </div> -->

          <div class="modal-body">
            <div class="flex items-center">
              <div class="w-full lg:w-1/4">
                <label for="subject_standard_id" class="tw-form-label">Standard<span class="text-red-500">*</span></label>
              </div>
              <div class="my-2 w-full lg:w-3/4">
                <select v-model="subject_standard_id" name="subject_standard_id" id="subject_standard_id" class="tw-form-control w-full">
                  <option value="" disabled="disabled">Select Standard</option>
                  <option v-for="standard in standardlist" v-bind:value="standard.id">{{ convertInteger(standard.name) }}</option>
                </select>
                 <span v-if="errors.subject_standard_id" class="text-red-500 text-xs font-semibold">{{errors.subject_standard_id[0]}}</span>
              </div>
             
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
                </select> <span v-if="errors.subject_section_id" class="text-red-500 text-xs font-semibold">{{errors.subject_section_id[0]}}</span>
              </div>
             
            </div> 
          </div>
           <div class="modal-body">
            <table class="w-full border">
        <thead class="bg-gray-400">
          <tr class="border-b">
            <th class="tw-form-label text-left px-3 py-2">Subject<span class="text-red-500">*</span></th>
            <th class="tw-form-label text-left px-3 py-2">Code<span class="text-red-500">*</span></th>
            <th class="tw-form-label text-left px-3 py-2">Type<span class="text-red-500">*</span></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr class="border-b " v-for="(subjectoption, k1) in subjectoptions" :key="k1">
            <td class="py-3 px-2">
              <input type="text"  class="tw-form-control w-full" id="subject_name" v-model="subjectoption.subject_name" name="subject_name[]">
              <span v-if="errors['subject_name'+k1]" class="text-red-500 text-xs font-semibold">{{errors['subject_name'+k1][0]}}</span>
            </td>

            <td class="py-3 px-2">
              <input type="text"  class="tw-form-control w-full" id="subject_code" v-model="subjectoption.subject_code" name="subject_code[]">
              <span v-if="errors['subject_code'+k1]" class="text-red-500 text-xs font-semibold">{{errors['subject_code'+k1][0]}}</span>
            </td>
            <td class="py-3 px-2">
              <select class="tw-form-control w-full" id="subject_type" v-model="subjectoption.subject_type" name="subject_type[]">
                <option value="" disabled>Select Type</option>
                <option value="core">Core</option>
                <option value="elective">Elective</option>
                <option value="exam">Exam</option>
              </select>
              <span v-if="errors['subject_type'+k1]" class="text-red-500 text-xs font-semibold whitespace-no-wrap">{{errors['subject_type'+k1][0]}}</span>
            </td>

            <td>
             <!--   <button  class="add_more px-3 text-3xl"  @click.prevent="removeoption(k1)" v-show="k1 || ( !k1 && subjectoptions.length >1)">-</button> -->
             <div class="flex items-center">
            
              <a href="#" class="btn-times" @click="removeoption(k1)" title="Delete" v-show="k1 || ( !k1 && subjectoptions.length >1)">
                <svg data-v-689010ab="" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-4 h-4 fill-current text-gray-500"><g data-v-689010ab=""><g data-v-689010ab=""><g data-v-689010ab=""><polygon data-v-689010ab="" points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804"></polygon><rect data-v-689010ab="" x="235.948" y="175.791" width="40.104" height="237.285"></rect><polygon data-v-689010ab="" points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804"></polygon><path data-v-689010ab="" d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74 c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474 L380.665,471.896z"></path></g></g></g> <g data-v-689010ab=""><g data-v-689010ab=""><path data-v-689010ab="" d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42 C354.924,14.992,339.932,0,321.504,0z"></path></g></g></svg>
              </a>
                <button class="add_more px-2 text-xl"  @click.prevent="addoption(k1)" v-show="k1 == subjectoptions.length-1">+</button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
           </div>

          <div class="my-6">
            <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="addSubject()">Submit</a>
          </div>
        </div>
      </div>
    </div>

    <div class="tw-form-group">
      <div class="flex flex-col lg:flex-row">
        <div class="w-full lg:w-1/3 md:w-1/3 flex flex-col lg:flex-row">
          <div class="w-full lg:w-8/12 md:w-8/12">
            <div class="mb-2">
              <label for="class_teacher_id" class="tw-form-label">Subjects<span class="text-red-500">*</span></label>
            </div>
          </div>
          <div class="w-4/12 lg:mx-1 md:m-1">
            <div class="lg:mx-3 md:mx-0 w-40">
              <a href="#" class="bg-blue-500 rounded text-sm text-white px-2 py-1 whitespace-no-wrap" @click="showModal('subject')">Add New Subject</a>
            </div>
          </div> 
        </div>
      </div>
    </div>

    <div class="tw-form-group" v-if="this.standard_id != '' & this.section_id != '' ">
      <table class="w-full lg:w-3/4 md:w-3/4 border" v-if="Object.keys(inputs).length > 0">
        <thead class="bg-gray-400">
          <tr class="border-b">
            <th class="tw-form-label py-2">Subject<span class="text-red-500">*</span></th>
            <th class="tw-form-label py-2">Teacher<span class="text-red-500">*</span></th>
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

            <td>
              <a href="#" class="btn-times" @click="deleteRow(index,input)" title="Delete">
                <svg data-v-689010ab="" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-4 h-4 fill-current text-gray-500"><g data-v-689010ab=""><g data-v-689010ab=""><g data-v-689010ab=""><polygon data-v-689010ab="" points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804"></polygon><rect data-v-689010ab="" x="235.948" y="175.791" width="40.104" height="237.285"></rect><polygon data-v-689010ab="" points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804"></polygon><path data-v-689010ab="" d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74 c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474 L380.665,471.896z"></path></g></g></g> <g data-v-689010ab=""><g data-v-689010ab=""><path data-v-689010ab="" d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42 C354.924,14.992,339.932,0,321.504,0z"></path></g></g></svg>
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
     
    <div class="py-3">
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
      type:'',
      subject_standard_id:'',
      subject_section_id:'',
      position:'',
      standard_name:'',
      ref_standard_id:'',
      positionlist:[{id : 'before' , name : 'Before'} , {id : 'after' , name : 'After'}],
      teacherlist:[],
      subjectlist:[],
      standardlist:[],
      sectionlist:[],
      streamlist:[{'id' : 'science' , 'name' : 'Science'} , {'id' : 'arts' , 'name' : 'Arts'} , {'id' : 'others' , 'name' : 'Others'}],
      show:0,
      errors:[],
      success:null,
      inputs: [{
        subject_id:'',
        teacher_id:'',
      }],
       subjectoptions: [
          {
            subject_name:'',
            subject_code: '',
            subject_type:''
          },
        ],
    }
  },

  methods:
  {
    groupBy(array, key)
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

    getStandard()
    {
        axios.get('/admin/getStandard?standard_id='+this.standard_id).then(response => {
            this.standard_name = response.data.name;
            //console.log(this.standard_name);
        });
    },


    addRow()
    {
      this.getStandard();
      this.subject_standard_id=this.standard_id;
      this.subject_section_id=this.section_id;
      this.inputs=[];
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
    },

    convertInteger(num)
    {
      return num;
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
          this.show = 0;
          window.location.reload(); 
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
          this.success = response.data.success; 
          this.show = 0;
          window.location.reload();
        }).catch(error => {
          this.errors = error.response.data.errors;
        });
    },

    addSubject()
    {
      this.errors=[];
      this.success=null;

      let formData=new FormData(); 

      formData.append('subject_standard_id',this.subject_standard_id);
      formData.append('subject_section_id',this.subject_section_id);
     // formData.append('subject',this.subject);
     // formData.append('code',this.code);
     // formData.append('type',this.type);
      formData.append('subjectscount',this.subjectoptions.length);
      for(let i=0; i<this.subjectoptions.length;i++)
      {
        if(typeof this.subjectoptions[i]['subject_name'] !== "undefined")
        {
          formData.append('subject_name'+i,this.subjectoptions[i]['subject_name']);
        }
        else
        {
          formData.append('subject_name'+i,'');
        }

        if(typeof this.subjectoptions[i]['subject_code'] !== "undefined")
        {
          formData.append('subject_code'+i,this.subjectoptions[i]['subject_code']);
        }
        else
        {
          formData.append('subject_code'+i,'');
        }

        if(typeof this.subjectoptions[i]['subject_type'] !== "undefined")
        {
          formData.append('subject_type'+i,this.subjectoptions[i]['subject_type']);
        }
        else
        {
          formData.append('subject_type'+i,'');
        }
      }


      axios.post('/admin/subjects/create',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
          this.success = response.data.success; 
          this.show = 0;
          window.location.reload();
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
      }
        axios.post('/admin/standardLink/add',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
          this.success = response.data.success; 
          window.location.reload();
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

    

    deleteRow(index,input) 
    {

      console.log(input['subject_id']);

      var subject_id=input['subject_id'];

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

                axios.get('/admin/subject/delete/'+subject_id).then(response => {
          this.success = response.data.success;
        //console.log(this.list)
         
           });

               



            }
            else 
            {
                swal("Cancelled");
            }
        });
    },

     addoption(index)
      {
        this.subjectoptions.push({ 
            subject_name:'',
            subject_code: '',
            subject_type:''
          });
      },

      removeoption(index) 
      {
        this.subjectoptions.splice(index, 1);
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
  padding: 12px 22px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
  transition: all .3s ease;
  max-height: 550px;
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