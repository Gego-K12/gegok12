<template>
  <div class=" overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3 bg-white shadow px-3" v-bind:class="[this.profile_tab==2?'block' :'hidden']">      
    <div class="flex flex-col lg:flex-row">
      <div class="tw-form-group w-full lg:w-1/3">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="ug_degree" class="tw-form-label">UG degree</label>
          </div>
          <div class="mb-2">
            <select class="tw-form-control w-full" id="ug_degree" v-model="ug_degree" name="ug_degree">
              <option value="" disabled>Select UG degree</option>
              <option v-for="list in uglist" v-bind:value="list.id">{{ list.display_name }}</option>
            </select>
          </div>
          <span v-if="errors.ug_degree" class="text-red-500 text-xs font-semibold">{{errors.ug_degree[0]}}</span>
        </div>
      </div>

      <div class="tw-form-group w-full lg:w-1/3">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="pg_degree" class="tw-form-label">PG degree</label>
          </div>
          <div class="mb-2">
            <select class="tw-form-control w-full" id="pg_degree" v-model="pg_degree" name="pg_degree">
              <option value="" disabled>Select PG degree</option>
              <option v-for="list in pglist" v-bind:value="list.id">{{ list.display_name }}</option>
            </select>
          </div>
          <span v-if="errors.pg_degree" class="text-red-500 text-xs font-semibold">{{errors.pg_degree[0]}}</span>
        </div>
      </div>

      <div class="tw-form-group w-full lg:w-1/3">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="specialization" class="tw-form-label">Subject Specialization<span class="text-red-500">*</span></label>
          </div>
          <div class="mb-2">
            <input type="text" class="tw-form-control w-full" id="specialization" v-model="specialization" name="specialization" placeholder="Specialization">
          </div>
          <span v-if="errors.specialization" class="text-red-500 text-xs font-semibold">{{errors.specialization[0]}}</span>
        </div> 
      </div>
    </div>

     <div class="tw-form-group w-full lg:w-1/3" >
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="sub_qualification" class="tw-form-label">Other Courses / Certificates</label>
          </div>
          <div class="mb-2">
            <input type="text" v-model="sub_qualification" name="sub_qualification" id="sub_qualification" class="tw-form-control w-full" placeholder="Eg:SSLC,HSSLC,Diploma">
          </div>
          <span v-if="errors.sub_qualification" class="text-red-500 text-xs font-semibold">{{errors.sub_qualification[0]}}</span>
        </div> 
      </div>
      
    <div class="flex flex-col">
      <div class="tw-form-group w-full lg:w-1/3" v-for="(input, index) in inputs">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <!-- <label for="qualification_id" class="tw-form-label">Additional Courses / Certificates<span class="text-red-500">*</span></label> -->
            <label for="qualification_id" class="tw-form-label">Professional Courses / Certificates</label>
          </div>
          <div class="mb-2">
            <select class="tw-form-control w-full" id="qualification_id" v-model="input.qualification_id" name="qualification_id[]" v-on:change="enableDiv(input.qualification_id)">
              <option value="" disabled>Select</option>
              <option v-for="qualifications in qualificationlist" v-bind:value="qualifications.id">{{ qualifications.display_name }}</option>
            </select>
          </div>
          <span v-if="errors['qualification_id'+index]" class="text-red-500 text-xs font-semibold">{{errors['qualification_id'+index]}}</span>
        </div>
        <a href="#" class="btn-times" v-show="index || ( !index && input.length >1)" @click="deleteRow(index)">
          <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-4 h-4 fill-current text-black-500 mx-1"><g><g><g><polygon points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804"></polygon><rect x="235.948" y="175.791" width="40.104" height="237.285"></rect><polygon points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804"></polygon><path d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74 c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474 L380.665,471.896z"></path></g></g></g> <g><g><path d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42 C354.924,14.992,339.932,0,321.504,0z"></path></g></g></svg>
        </a> 
      </div>
    </div>
    <div class="flex flex-col lg:flex-row">
      <div class="tw-form-group w-full lg:w-1/3">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="add_qualification" class="tw-form-label">Add New</label>
          </div>
          <div class="mb-2">
            <a href="#" class="tw-form-control w-full" @click="addRow">+ </a>
          </div>
        </div>
      </div>
     
    </div>

    <div class="my-6">
      <a href="#" dusk="submit-btn" class=" btn-primary submit-btn blue-bg text-sm text-white px-2 py-1 rounded mx-1" @click="previous('1')">Previous</a>
      <a href="#" dusk="submit-btn" class=" btn-primary submit-btn blue-bg text-sm text-white px-2 py-1 rounded mx-1" @click="submitForm('3')">Submit</a>
      <a href="#" class="btn-reset reset-btn" @click="resetForm()">Reset</a>
    </div>
  </div>
</template>

<script>
  import { bus } from "../../app";

  export default {
    props:['url' , 'type' , 'teacher_name'],
    data () {
      return {
        profile_tab:'',
        user:[],
        uglist:[],
        pglist:[],
        qualificationlist:[],
        ug_degree:'',
        pg_degree:'',
        qualification_id:[],
        sub_qualification:'',
        specialization:'',
        inputs: [{
          qualification_id:'',
        }],
        errors:[],
        success:null,
      }
    },

    methods:
    {
      getData(getUrl)
      {
        axios.get(getUrl).then(response => {
          this.user = response.data;
          this.setData();   
        });
      },

      setData()
      {
        if(Object.keys(this.user).length>0)
        {
          if(this.type == 'add')
          {
            this.academic_year_id   = this.user.academic_year_id;
            if(this.academic_year_id == null)
            {
              alert("Add Academic Year")
            }
            else
            {
              this.qualificationlist  = this.user.qualificationlist;
              this.uglist             = this.user.uglist;
              this.pglist             = this.user.pglist;
            }
          }
          else if(this.type == 'edit')
          {
            this.qualificationlist  = this.user.qualificationlist;
            this.uglist             = this.user.uglist;
            this.pglist             = this.user.pglist;
            this.ug_degree          = this.user.ug_degree;
            this.pg_degree          = this.user.pg_degree;
            this.specialization     = this.user.specialization;
            this.sub_qualification  = this.user.sub_qualification;
            this.inputs             = this.user.qualification_id || [{ qualification_id: '' }];

            if(this.sub_qualification != null)
            {
              $('#qualification_others').removeClass('hidden').addClass('block');
            }
          }
        }
      },

       resetForm()
      {
        this.ug_degree='';
        this.pg_degree='';
        this.sub_qualification='';
        this.specialization='';
      }, 

      enableDiv(id)
      {
        if( id == 1)
        { 
          if($('#qualification_others').hasClass('hidden'))
          {
            $('#qualification_others').removeClass('hidden').addClass('block');
          }
        }
      },

      setProfileTab(val)
      {
        this.profile_tab=val;
        bus.emit("dataProfileTab", this.profile_tab);
      },


      addRow() 
      {
        this.inputs.push({
          qualification_id:'',
        });
      },

      deleteRow(index) 
      {
        this.inputs.splice(index,1);
      },

      previous(tab)
      {
        //this.profile_tab=val;
        bus.emit("dataProfileTab", tab);

      } ,

      submitForm(val)
      {
        this.errors=[];
        this.success=null; 

        let formData =  new FormData(); 

        formData.append('ug_degree',this.ug_degree);
        formData.append('pg_degree',this.pg_degree);
        formData.append('sub_qualification',this.sub_qualification); 
        formData.append('specialization',this.specialization);
        formData.append('count',this.inputs.length);

        for(let i=0; i<this.inputs.length;i++)
        { 
          if(typeof this.inputs[i]['qualification_id'] !== "undefined")
          {
            formData.append('qualification_id'+i,this.inputs[i]['qualification_id']);
          }
          else
          {
            formData.append('qualification_id'+i,'');
          }
        }

        if(this.type == 'add')
        {
          axios.post('/admin/teacher/add/validationQualification',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
            this.setProfileTab(val); 
          }).catch(error => {
            this.errors = error.response.data.errors;
          });
        }
        else if(this.type == 'edit')
        {
          axios.post('/admin/teacher/edit/validationQualification/'+this.teacher_name,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
            this.setProfileTab(val); 
          }).catch(error => {
            this.errors = error.response.data.errors;
          });
        }
      },
    },
    
    created()
    {  
      if(this.type == 'add')
      {
        this.getData('/admin/teacher');
      }
      else if(this.type == 'edit')
      {
        this.getData('/admin/teacher/editTeacher/'+this.teacher_name);
      }

      bus.on("dataProfileTab", data => {
        if(data!='')
        {
          this.profile_tab=data;                    
        }
      });    
    }
  } 
</script>