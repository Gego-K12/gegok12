<template>
  <div class="bg-white shadow px-4 py-3">
    <div>
	    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
      

      <div class="my-5">
        <div class="tw-form-group w-full lg:w-3/5">
          <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center w-full">
            <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
              <label for="date" class="tw-form-label">Date<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2 w-full lg:w-3/4  md:w-2/3">
              <input type="date" name="date" v-model="date" class="tw-form-control w-full" id="date">
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
        <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="selectStudent()">Select Staffs</a>
      </div>

      <div class="w-full flex flex-col lg:flex-row hidden" id="select">
        <div class="w-full lg:w-1/3 bg-white shadow border px-4">
          <div class="w-full my-4">
            <div class="flex justify-between items-center my-4">
              <h2 class="font-semibold text-base text-gray-700 capitalize">staffs
              <span class="text-xs">( Click on checkbox to mark absent )</span></h2>
            </div>
            <div class="relative mb-3">
              <input type="text" v-model="searchStaff" placeholder="Search staff..." class="tw-form-control w-full pr-8">
              <button v-if="searchStaff" type="button" @click="searchStaff=''" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-base leading-none">&times;</button>
            </div>
            <div class="overflow-y-auto" style="min-height: 200px; max-height: 400px;">
              <div v-for="(present,index) in presents" :key="present.present_id" v-show="matchesSearch(present.user_name, searchStaff)" class="">
                <div class="flex items-center py-1" :id="present.present_id">
                  <div class="w-6">
                    <input type="checkbox"
                    :checked="true"
                    @change="absentStudent($event,present,index)">
                  </div>
                  <div class="mx-2">
                    <p class="tw-form-label">{{ present.user_name }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="w-full lg:w-1/3 bg-white shadow border px-4 lg:ml-2 my-2 lg:my-0">
          <div class="w-full my-4">
            <div class="flex justify-between items-center my-4">
              <h2 class="font-semibold text-base text-gray-700 capitalize">Absent Staffs</h2>
            </div>

            <div class="relative mb-3">
              <input type="text" v-model="searchAbsent" placeholder="Search absent staff..." class="tw-form-control w-full pr-8">
              <button v-if="searchAbsent" type="button" @click="searchAbsent=''" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-base leading-none">&times;</button>
            </div>
            <div class="overflow-y-auto" style="min-height: 200px; max-height: 400px;">
              <div class="flex flex-wrap items-center justify-between py-1" v-for="(absent,index) in absents" :key="absent.user_id" v-show="matchesSearch(absent.user_name, searchAbsent)">
                <div class="flex items-center">
                  <div class="w-6">
                    <input type="checkbox"
                      checked
                      @change="presentStudent($event,absent,index)">
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
            </div>
            <div class="flex justify-end my-6" id="save_btn">
              <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="savestaffs()">Save</a>
            </div>
          </div>
        </div>

        <div class="w-full lg:w-1/3 bg-white shadow border px-4 lg:ml-2 my-2 lg:my-0">
          <div class="w-full my-4">
            <div class="flex justify-between items-center my-4">
              <h2 class="font-semibold text-base text-gray-700 capitalize">Approved Leaves
              <span class="text-xs">( For the selected date &amp; session )</span></h2>
            </div>

            <div class="relative mb-3">
              <input type="text" v-model="searchLeave" placeholder="Search leaves..." class="tw-form-control w-full pr-8">
              <button v-if="searchLeave" type="button" @click="searchLeave=''" class="absolute right-2 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-base leading-none">&times;</button>
            </div>
            <div class="overflow-y-auto" style="min-height: 200px; max-height: 400px;">
              <div v-if="approvedLeaves.length === 0" class="text-sm text-gray-500">No approved leaves for this date.</div>
              <div class="py-2 border-b" v-for="leave in approvedLeaves" :key="leave.user_id" v-show="matchesSearch(leave.name, searchLeave)">
                <p class="tw-form-label">{{ leave.name }}</p>
                <p class="text-xs text-gray-500">{{ leave.leave_type }} &middot; {{ leave.session }}</p>
                <p class="text-xs text-gray-500" v-if="leave.reason">Reason: {{ leave.reason }}</p>
                <p class="text-xs text-gray-500" v-if="leave.remarks">{{ leave.remarks }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

    	<div class="my-6 hidden" id="btn_div">
        <a href="#" id="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm()">Submit</a>
    		<a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="resetForm()">Reset</a>	
    	</div>
	  </div>
  </div>
</template>

<script>
	export default {
    props:['url','standard','mode'],
    data(){
      return{
        list:[],
        absentReasonlist:[],
        stafflist:[],
        user_id:[],
        date:'',
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
        approvedLeaves:[],
        errors:[],
        success:null,
        searchStaff:'',
        searchAbsent:'',
        searchLeave:'',
      }
    },
        
    methods:
    {
      matchesSearch(text, search)
      {
        if(!search) return true;
        return (text || '').toString().toLowerCase().includes(search.toLowerCase());
      },

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

      selectStudent()
      {
        if($('#select').hasClass('hidden'))
        {
          $('#select').removeClass('hidden').addClass('block');
          $('#select_student_btn').addClass('hidden').removeClass('block');
        }

        this.presents = [];
        this.absents = [];

        this.stafflist.forEach(staff => {
          this.presents.push({
            present_id: staff.teacher_id,
            user_name: staff.teacher_name,
            user: staff
          });
        });

        this.fetchApprovedLeaves();
      },

      fetchApprovedLeaves()
      {
        axios.get('/'+this.mode+'/attendance/staff/approved-leaves/'+this.date+'/'+this.session).then(response => {
          this.approvedLeaves = response.data;
        });
      },

      absentStudent(e, staff, index)
      {
        if (!e.target.checked)
        {
          this.absents.push({
            user_id: staff.user.teacher_id,
            user_name: staff.user.teacher_name,
            reason_id: '',
            remarks: '',
          });

          this.presents.splice(index,1);
        }
      },

      presentStudent(e, staff, index)
      {
        if(!e.target.checked)
        {
          this.presents.push({
            present_id: staff.user_id,
            user_name: staff.user_name
          });

          this.absents.splice(index,1);
        }
      },
      savestaffs()
      {
        if($('#btn_div').hasClass('hidden'))
        {
          $('#btn_div').removeClass('hidden').addClass('block');
          $('#save_btn').addClass('hidden').removeClass('block');
        }
      },

      submitForm()
      {
        this.errors=[];
        this.success=null; 

        let formData=new FormData();
   
        formData.append('date',this.date);                 
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
      
        axios.post('/'+this.mode+'/attendance/staff/add',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
          this.success = response.data.success;
          //this.resetForm();
        }).catch(error => {
          this.errors = error.response.data.errors;
        });
      },

      getData()
      {
        axios.get('/'+this.mode+'/attendance/staff/list').then(response => {
          this.list = response.data;
          this.setData();
          console.log(this.list)
        });
      },
      setData()
      {
        if(Object.keys(this.list).length > 0)
        {
          this.stafflist      = this.list.stafflist;
          this.absentReasonlist = this.list.absentReasonlist;
        }
      },
    },
    created()
    {
      this.getData();
    }
  }
</script>