<template>
    <div class="bg-white shadow px-4 py-3">
        <div>
            <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/5">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="standardLink_id" class="tw-form-label">Select Class<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-3/4 md:w-2/3">
                            <select class="tw-form-control w-full" id="standardLink_id" v-model="standardLink_id" name="standardLink_id">
                                <option value="" disabled>Select Class</option>
                                <option v-for="standard in standardlist" v-bind:value="standard.id">{{standard.standard_name}}-{{standard.section_name}}</option>
                            </select>
                            <span v-if="errors.standardLink_id"><p class="text-red-500 text-xs font-semibold">{{errors.standardLink_id[0]}}</p></span>
                        </div>
                    </div> 
                </div>
            </div>

      <div class="my-5">
        <div class="tw-form-group w-full lg:w-3/5">
          <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center w-full">
            <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
              <label for="date" class="tw-form-label">Date<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2 w-full lg:w-3/4  md:w-2/3">
              <input type="date" name="date" v-model="dateValue" class="tw-form-control w-full" id="date">
              <span v-if="errors.date" class="text-red-500 text-xs font-semibold">{{errors.date[0]}}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="my-5">
        <div class="tw-form-group  w-full lg:w-3/5">
          <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center w-full">
            <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
              <label for="session" class="tw-form-label">Session<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2 w-full lg:w-3/4  md:w-2/3 ">
            <div class="flex">
              <div class="w-1/2 flex items-center tw-form-control mr-1 lg:mr-4 md:mr-4"> 
                <input type="radio" v-model="session" name="session" id="forenoon" value="forenoon">
                <span class="text-sm mx-1">Forenoon</span>
              </div>
              <div class="w-1/2 flex items-center tw-form-control"> 
                <input type="radio" v-model="session" name="session" id="afternoon" value="afternoon">
                <span class="text-sm mx-1">Afternoon</span>
              </div>
              </div>
               <span v-if="errors.session" class="text-red-500 text-xs font-semibold">{{errors.session[0]}}</span>
            </div>
           
          </div> 
        </div>
      </div> 

      <div class="my-6" id="select_student_btn">
        <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="selectStudent()">Select Students</a>
      </div>

      <div class="w-full flex flex-col lg:flex-row" v-if="showSelection">
        <div class="w-full lg:w-1/2 bg-white shadow border px-4">
          <div class="w-full my-4">
            <div class="flex justify-between items-center my-4">
              <h2 class="font-semibold text-base text-gray-700 capitalize">Students
              <span class="text-xs">( Click on checkbox to mark absent )</span></h2>
            </div>
            <div v-for="(present,index) in presents" class="">
              <div class="flex items-center py-1" :id="present.present_id">
                <div class="w-6">
                  <input type="checkbox"
                   :checked="true"
                   @change="absentStudent(present, index)">
                </div>
                <div class="mx-2"> 
                  <p class="tw-form-label">{{ present.user_name }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="w-full lg:w-1/2 bg-white shadow border px-4 lg:ml-2 my-2 lg:my-0">
          <div class="w-full my-4">
            <div class="flex justify-between items-center my-4">
              <h2 class="font-semibold text-base text-gray-700 capitalize">Absent Students</h2>
            </div>
            <div class="flex items-center justify-between py-1" v-for="(absent,index) in absents">
              <div class="flex items-center">
                <div class="w-6">
                  <input type="checkbox" @change="presentStudent(absent, index)">
                </div>
                <div class="mx-2"> 
                  <p class="tw-form-label">{{ absent.user_name }}</p>
                </div>
              </div>
              <div class="flex items-center">
                <div class="mx-1">
                  <select name="reason_id" v-model="absent.reason_id" id="reason_id" class="tw-form-control w-full">
                    <option value="" disabled>Select Reason</option>
                    <option v-for="reason in absentReasonlist" v-bind:value="reason.id">{{ reason.title }}</option>
                  </select>
                  <span v-if="errors['reason_id'+index]" class="text-red-500 text-xs font-semibold">{{errors['reason_id'+index]}}</span>
                </div>

                <div class="mx-1">
                  <input type="text" name="remarks" v-model="absent.remarks" class="tw-form-control w-full" placeholder="Remarks">
                  <span v-if="errors['remarks'+index]" class="text-red-500 text-xs font-semibold">{{errors['remarks'+index]}}</span>
                </div>
              </div>
            </div>
            <div class="flex justify-end my-6" id="save_btn">
              <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="saveStudents()">Save</a>
            </div>
          </div>
        </div>
      </div>

        <div class="my-6" v-if="showSubmitButton">
        <a href="#" id="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm()">Submit</a>
            <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="resetForm()">Reset</a>    
        </div>
      </div>
  </div>
</template>

<script>
    export default {
    props:['url','standard','mode','date','standardlist','studentlist','absentReasons'],
    data(){
      return{
        list:[],
        absentReasonlist:[],
        standardlist:[],
        studentlist:[],
        standardLink_id:'',
        user_id:[],
        session:'',
        status:'',
        reason_id:[],
        remarks:'',
        presents:[{ 
          present_id : '',
        }],
        absents:[{ 
          user_id : '',
          reason_id:'',
          remarks:'',
        }],
        errors:[],
        success:null,
        dateValue:'',
        showSelection: false,
        showSubmitButton: false,
      }
    },
    methods:
    {
      resetForm()
      {
        window.location.reload();
      },

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

      selectStudent() {
        this.showSelection = true;

        this.presents = [];
        this.absents = [];

        const students = this.studentlist[this.standardLink_id] || [];

        students.forEach(student => {
            this.presents.push({
                present_id: student.user_id,
                user_name: student.name,
                user: student
            });
        });
    },

      absentStudent(present, index) {
        // remove from presents
        const student = this.presents.splice(index, 1)[0];

        // add to absents
        this.absents.push({
            user_id: student.present_id,
            user_name: student.user_name,
            user: student.user,
            reason_id: '',
            remarks: ''
        });
    },

      presentStudent(absent, index) {
        const student = this.absents.splice(index, 1)[0];

        this.presents.push({
            present_id: student.user_id,
            user_name: student.user_name,
            user: student.user
        });
    },

      saveStudents()
      {
        this.showSubmitButton = true;
      },

      submitForm()
      {
        this.errors=[];
        this.success=null; 

        let formData=new FormData();

        formData.append('standardLink_id',this.standardLink_id);   
        formData.append('date',this.dateValue);                 
        formData.append('session',this.session); 
        
        formData.append('absentCount',this.absents.length);
        for(let i=0; i<this.absents.length;i++)
        {
          if(typeof this.absents[i]['user_id'] !== "undefined")
          {
            formData.append('user_id'+i,this.absents[i]['user_id']);
          }
          else
          {
            formData.append('user_id'+i,'');
          }

          if(typeof this.absents[i]['reason_id'] !== "undefined")
          {
            formData.append('reason_id'+i,this.absents[i]['reason_id']);
          }
          else
          {
            formData.append('reason_id'+i,'');
          }

          if(typeof this.absents[i]['remarks'] !== "undefined")
          {
            formData.append('remarks'+i,this.absents[i]['remarks']);
          }
          else
          {
            formData.append('remarks'+i,'');
          }
        } 

        formData.append('presentCount',this.presents.length);
        for(let i=0; i<this.presents.length;i++)
        {
          if(typeof this.presents[i]['present_id'] !== "undefined")
          {
            formData.append('present_id'+i,this.presents[i]['present_id']);
          }
          else
          {
            formData.append('present_id'+i,'');
          }
        }
      
        axios.post('/'+this.mode+'/attendance/add',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
          this.success = response.data.success;
          //this.resetForm();
        }).catch(error => {
          this.errors = error.response.data.errors;
        });
      },

      getData()
      {
        axios.get('/'+this.mode+'/attendance/list').then(response => {
          this.list = response.data;
          this.setData();
          //console.log(this.list)
        });
      },
      setData()
      {
        if(Object.keys(this.list).length > 0)
        {
          this.studentlist      = this.list.studentlist;
          this.absentReasonlist = this.list.absentReasonlist;
          this.standardlist     = this.list.standardlist;
          if(this.standard != '')
          {
            this.standardLink_id = this.standard;
          }
        }
      },
    },
    created()
    {
      this.dateValue = this.date || '';

      // Use props if provided (from blade view), otherwise fetch from API
      if (this.$props.standardlist && this.$props.standardlist.length > 0) {
        this.standardlist = this.$props.standardlist;
        this.studentlist = this.$props.studentlist || {};
        this.absentReasonlist = this.$props.absentReasons || [];

        if(this.standard != '') {
          this.standardLink_id = this.standard;
        }
      } else {
        this.getData();
      }
    }
  }
</script>