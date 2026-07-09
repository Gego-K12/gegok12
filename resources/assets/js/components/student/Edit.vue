<template>
  <div class="bg-white shadow px-4 py-3">
    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
    <h1>Personal Details</h1>
    <div class="my-6">
      <div class="flex items-center">
        <img :src="this.avatar_display" class="img-responsive w-12 h-12 rounded-full">
        <div class="mx-2">
          <h2 class="font-semibold text-sm text-gray-700">{{ user.firstname }} {{ user.lastname }}</h2>
          <label class="tw-label cursor-pointer text-xs text-gray-600"> Change Avatar
            <input type="file" size="60" name="avatar" id="avatar" @change="OnFileSelected">
            <span v-if="errors.avatar" class="text-red-500 text-xs font-semibold">{{errors.avatar[0]}}</span>
          </label> 
        </div>
      </div>
    </div>
    <div class="flex flex-col lg:flex-row">
      <div class="tw-form-group w-full lg:w-1/3">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="firstname" class="tw-form-label">First Name<span class="text-red-500">*</span></label>
          </div>
          <div class="mb-2">
           
            <input type="text" class="tw-form-control w-full" id="firstname" v-model="firstname" name="firstname" Placeholder="First Name">
          </div>
          <span v-if="errors.firstname" class="text-red-500 text-xs font-semibold">{{errors.firstname[0]}}</span>
        </div> 
      </div>

       <div class="tw-form-group w-full lg:w-1/3">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="lastname" class="tw-form-label">Last Name </label>
          </div>
          <div class="mb-2">
           
            <input type="text" v-model="lastname" name="lastname" id="lastname" class="tw-form-control w-full" Placeholder="Last Name">
          </div>
          <span v-if="errors.lastname" class="text-red-500 text-xs font-semibold">{{errors.lastname[0]}}</span>
        </div> 
      </div>

      <div class="tw-form-group w-full lg:w-1/3">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="date_of_birth" class="tw-form-label">Date Of Birth<span class="text-red-500">*</span></label>
          </div>
          <div class="mb-2">
            <input type="date" v-model="date_of_birth" name="date_of_birth" id="date_of_birth" class="tw-form-control w-full">
          </div>
          <span v-if="errors.date_of_birth" class="text-red-500 text-xs font-semibold">{{errors.date_of_birth[0]}}</span>
        </div> 
      </div>

    </div>

    <div class="flex flex-col lg:flex-row">
      <div class="tw-form-group w-full lg:w-1/3">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="gender" class="tw-form-label">Gender<span class="text-red-500">*</span></label>
          </div>
          <div class="flex">

            <div class="w-1/2 flex items-center tw-form-control mr-2 lg:mr-8 md:mr-8"> 
              <input type="radio" v-model="gender" name="gender" id="gender1" value="male">
              <span class="text-sm mx-1">Boy</span>
            </div>
            <div class="w-1/2 flex items-center tw-form-control lg:mr-8 md:mr-8"> 
              <input type="radio" v-model="gender" name="gender" id="gender2" value="female">
              <span class="text-sm mx-1">Girl</span>
            </div>
          </div>
          <span v-if="errors.gender" class="text-red-500 text-xs font-semibold">{{errors.gender[0]}}</span>
        </div>
      </div>

      <div class="tw-form-group w-full lg:w-1/3">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="blood_group" class="tw-form-label">Blood Group<span class="text-red-500">*</span></label>
          </div>
          <div class="mb-2">
            <select class="tw-form-control w-full" id="blood_group" v-model="blood_group" name="blood_group">
              <option 
                  v-for="blood_group in blood_groups"
                  :key="blood_group.num"
                  :value="blood_group.num">
                  {{ blood_group.name }}
                </option>
            </select>
          </div>
          <span v-if="errors.blood_group" class="text-red-500 text-xs font-semibold">{{errors.blood_group[0]}}</span>
        </div> 
      </div>
      
      <div class="tw-form-group w-full lg:w-1/3">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="aadhar_number" class="tw-form-label">Aadhaar Number</label>
          </div>
          <div class="mb-2">
            <input type="text" class="tw-form-control w-full" id="aadhar_number" v-model="aadhar_number" name="aadhar_number" Placeholder="Aadhar Number">
          </div>
          <span v-if="errors.aadhar_number" class="text-red-500 text-xs font-semibold">{{errors.aadhar_number[0]}}</span>
        </div> 
      </div>
    </div>

    <GoogleMap
      v-model:address="address"
      v-model:latitude="latitude"
      v-model:longitude="longitude"
    />

    <div class="tw-form-group">
      <div class="flex flex-col lg:flex-row">
        <div class="w-full lg:w-1/4 lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="country" class="tw-form-label">Country<span class="text-red-500">*</span></label>
          </div>
          <div class="mb-2">
            <select class="tw-form-control w-full" id="country_id" v-model="country_id" name="country_id">
              <option value="" disabled>Select Country</option>
              <option v-for="country in countrylist" v-bind:value="country.id">{{country.name}}</option>
            </select>
          </div>
          <span v-if="errors.country_id" class="text-red-500 text-xs font-semibold">{{errors.country_id[0]}}</span>
        </div>

         <div class="w-full lg:w-1/4 lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="state" class="tw-form-label">State<span class="text-red-500">*</span></label>
          </div>
          <div class="mb-2">
            <select class="tw-form-control w-full" id="state_id" v-model="state_id" name="state_id">
              <option value="" disabled>Select State</option>
              <option v-for="state in statelist[this.country_id]" v-bind:value="state.id">{{state.name}}</option>
            </select>  
          </div>
          <span v-if="errors.state_id" class="text-red-500 text-xs font-semibold">{{errors.state_id[0]}}</span>
        </div>

        <div class="w-full lg:w-1/4 lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="city" class="tw-form-label">City<span class="text-red-500">*</span></label>
          </div>
          <div class="mb-2">
            <select class="tw-form-control w-full" id="city_id" v-model="city_id" name="city_id">
              <option value="" disabled>Select City</option>
              <option v-for="city in citylist [this.state_id]" v-bind:value="city.id">{{city.name}}</option>
            </select>   
          </div>
          <span v-if="errors.city_id" class="text-red-500 text-xs font-semibold">{{errors.city_id[0]}}</span>
        </div>

        <div class="w-full lg:w-1/4 lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="pincode" class="tw-form-label">Pincode</label>
          </div>
          <div class="mb-2">
            <input type="text" class="tw-form-control w-full" v-model="pincode" name="pincode" id="pincode"  placeholder="Enter Pincode">
          </div>
          <span v-if="errors.pincode" class="text-red-500 text-xs font-semibold">{{errors.pincode[0]}}</span>
        </div>
      </div>
    </div>

    <div class="tw-form-group">
      <div class="flex flex-col lg:flex-row">
        <div class="w-full lg:w-1/4 lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="birth_place" class="tw-form-label">Birth Place</label>
          </div>
          <div class="mb-2">
            <input type="text" class="tw-form-control w-full" id="birth_place" v-model="birth_place" name="birth_place" placeholder="Birth Place">
          </div>
          <span v-if="errors.birth_place" class="text-red-500 text-xs font-semibold">{{errors.birth_place[0]}}</span>
        </div>

         <div class="w-full lg:w-1/4 lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="native_place" class="tw-form-label">Native Place</label>
          </div>
          <div class="mb-2">
            <input type="text" class="tw-form-control w-full" id="native_place" v-model="native_place" name="native_place" placeholder="Native Place">
          </div>
          <span v-if="errors.native_place" class="text-red-500 text-xs font-semibold">{{errors.native_place[0]}}</span>
        </div>

        <div class="w-full lg:w-1/4 lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="mother_tongue" class="tw-form-label">Mother Tongue<span class="text-red-500">*</span></label>
          </div>
          <div class="mb-2">
            <input type="text" class="tw-form-control w-full" id="mother_tongue" v-model="mother_tongue" name="mother_tongue" placeholder="Mother Tongue">
          </div>
          <span v-if="errors.mother_tongue" class="text-red-500 text-xs font-semibold">{{errors.mother_tongue[0]}}</span>
        </div>

        <div class="w-full lg:w-1/4 lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="caste" class="tw-form-label">Caste<span class="text-red-500">*</span></label>
          </div>
          <div class="mb-2">
            <select class="tw-form-control w-full" v-model="caste" name="caste" id="caste">
              <option value="" disabled>Select Caste</option>
              <option v-for="castes in castelist" v-bind:value="castes.id">{{ castes.name }}</option>
            </select>
          </div>
          <span v-if="errors.caste" class="text-red-500 text-xs font-semibold">{{errors.caste[0]}}</span>
        </div>

         <div class="w-full lg:w-1/4 lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="sub_caste" class="tw-form-label">Sub Caste<span class="text-red-500"></span></label>
          </div>
          <div class="mb-2">
             <input type="text" class="tw-form-control w-full" id="sub_caste" v-model="sub_caste" name="sub_caste" placeholder="Sub Caste">
          </div>
          <span v-if="errors.sub_caste" class="text-red-500 text-xs font-semibold">{{errors.sub_caste[0]}}</span>
        </div>
      </div>
    </div>
    
    <div class="flex flex-col lg:flex-row">
      <div class="tw-form-group w-full lg:w-1/3">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="mode_of_transport" class="tw-form-label">Mode Of Transport</label>
          </div>
          <div class="mb-2">
            <select class="tw-form-control w-full" id="mode_of_transport" v-model="mode_of_transport" name="mode_of_transport">
              <option value="" disabled>Select Transport</option>
              <option
                v-for="transport in transportlist"
                :key="transport.id"
                :value="transport.id">
                {{ transport.name }}
              </option>
            </select>
          </div>
          <span v-if="errors.mode_of_transport" class="text-red-500 text-xs font-semibold">{{errors.mode_of_transport[0]}}</span>
        </div> 
      </div>
      
      <div class="tw-form-group w-full lg:w-1/2" v-if="checkInArray(this.lists,this.mode_of_transport)">
        <div class="flex flex-col lg:flex-row">
          <div class="w-full lg:w-1/2">
            <div class="lg:mr-8 md:mr-8">
              <div class="mb-2">
                <label for="driver_name" class="tw-form-label">Driver Name<span class="text-red-500">*</span></label>
              </div>
              <div class="mb-2">
                <input type="text" v-model="driver_name" name="driver_name" id="driver_name" class="tw-form-control w-full" placeholder="Driver Name">
              </div>
              <span v-if="errors.driver_name" class="text-red-500 text-xs font-semibold">{{errors.driver_name[0]}}</span>
            </div> 
          </div>

          <div class="w-full lg:w-1/2">
            <div class="lg:mr-8 md:mr-8">
              <div class="mb-2">
                <label for="driver_contact_number" class="tw-form-label">Driver Contact Number<span class="text-red-500">*</span></label>
              </div>
              <div class="mb-2">
                <input type="text" v-model="driver_contact_number" name="driver_contact_number" id="driver_contact_number" class="tw-form-control w-full" placeholder="Driver Contact Number">
              </div>
              <span v-if="errors.driver_contact_number" class="text-red-500 text-xs font-semibold">{{errors.driver_contact_number[0]}}</span>
            </div> 
          </div>  
        </div>
      </div>
    </div>

    <div class="flex flex-col lg:flex-row">
      <div class="tw-form-group w-full lg:w-1/2">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="notes" class="tw-form-label">Notes</label>
          </div>
          <div class="mb-2">
            <textarea type="text" class="tw-form-control w-full" v-model="notes" id="notes" name="notes" rows="3"></textarea>
          </div>
          <span v-if="errors.notes" class="text-red-500 text-xs font-semibold">{{errors.notes[0]}}</span>
        </div> 
      </div>
    </div>
    <hr style="border-width: 1px;">
    <h1>Academic Details</h1>
    <div class="flex flex-col lg:flex-row">
      <div class="tw-form-group w-full lg:w-1/3">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="registration_number" class="tw-form-label">Admission Number<span class="text-red-500">*</span></label>
          </div>
          <div class="mb-2">
            <input type="text" v-model="registration_number" name="registration_number" id="registration_number" class="tw-form-control w-full" placeholder="Registration Number">
          </div>
          <span v-if="errors.registration_number" class="text-red-500 text-xs font-semibold">{{errors.registration_number[0]}}</span>
        </div> 
      </div>
      
      <div class="tw-form-group w-full lg:w-1/3">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="EMIS_number" class="tw-form-label">EMIS Number</label>
          </div>
          <div class="mb-2">
            <input type="text" v-model="EMIS_number" name="EMIS_number" id="EMIS_number" class="tw-form-control w-full" placeholder="EMIS Number">
          </div>
          <span v-if="errors.EMIS_number" class="text-red-500 text-xs font-semibold">{{errors.EMIS_number[0]}}</span>
        </div> 
      </div>
      
      <div class="tw-form-group w-full lg:w-1/3">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="joining_date" class="tw-form-label">Joining date<span class="text-red-500">*</span></label>
          </div>
          <div class="mb-2">
            <input type="date" name="joining_date" v-model="joining_date" id="joining_date" class="tw-form-control w-full">
          </div>
          <span v-if="errors.joining_date" class="text-red-500 text-xs font-semibold">{{errors.joining_date[0]}}</span>
        </div> 
      </div>      
    </div>

    <div class="flex flex-col lg:flex-row">
      <div class="tw-form-group w-full lg:w-1/3">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="standard" class="tw-form-label">Class<span class="text-red-500">*</span></label>
          </div>
          <div class="mb-2">
            <select class="tw-form-control w-full" id="standard" v-model="standard" name="standard">
              <option
                  v-for="standardLink in standardLinklist"
                  :key="standardLink.id"
                  :value="standardLink.id">
                  {{ standardLink.standard_section }}
                </option>
            </select>
          </div>
          <span v-if="errors.standard" class="text-red-500 text-xs font-semibold">{{errors.standard[0]}}</span>
        </div> 
      </div>

      <div class="tw-form-group w-full lg:w-1/3">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="roll_number" class="tw-form-label">Roll Number<span class="text-red-500">*</span></label>
          </div>
          <div class="mb-2">
            <input type="text" v-model="roll_number" name="roll_number" id="roll_number" class="tw-form-control w-full" placeholder="Roll Number">
          </div>
          <span v-if="errors.roll_number" class="text-red-500 text-xs font-semibold">{{errors.roll_number[0]}}</span>
        </div> 
      </div>
      
      <div class="tw-form-group w-full lg:w-1/3">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="id_card_number" class="tw-form-label">ID Card Number</label>
          </div>
          <div class="mb-2">
            <input type="text" v-model="id_card_number" name="id_card_number" id="id_card_number" class="tw-form-control w-full" placeholder="ID Card Number">
          </div>
          <span v-if="errors.id_card_number" class="text-red-500 text-xs font-semibold">{{errors.id_card_number[0]}}</span>
        </div> 
      </div>

      <div class="tw-form-group w-full lg:w-1/3">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="board_registration_number" class="tw-form-label whitespace-no-wrap">Board Registration Number<span class="text-red-500 whitespace-no-wrap">*Only For Class X , XI , XII</span></label>
          </div>
          <div class="mb-2">
            <input type="text" v-model="board_registration_number" name="board_registration_number" id="board_registration_number" class="tw-form-control w-full" placeholder="Board Registration Number">
          </div>
          <span v-if="errors.board_registration_number" class="text-red-500 text-xs font-semibold">{{errors.board_registration_number[0]}}</span>
        </div> 
      </div>   
    </div>

    <Teleport to="#submit-btn">
      <div class="my-6">
        <a href="#" dusk="submit-btn" class="btn btn-primary submit-btn" @click="submitForm()">Submit</a>
        <a href="#" class="btn btn-reset reset-btn" @click="resetForm()">Reset</a>
        <input type="submit" class="hidden" id="real-submit-btn">
      </div>
    </Teleport>
  </div>
</template>

<script> 
  import GoogleMap from '../GoogleMap.vue'
export default {
  props:['url' , 'student_name'],
    components: {
        GoogleMap
    },

  data(){
    return {
      user:[],
      firstname:'',
      lastname:'',
      gender:'',
      date_of_birth:'',
      blood_group:'',
      standard:'',
      standard_name:'',
      section_name:'',
      city_id:'',
      state_id:'',
      country_id:7,
      pincode:'',
      birth_place:'',
      native_place:'',
      mother_tongue:'',
      caste:'',
      sub_caste:'',
      aadhar_number:'',
      joining_date:'',
      registration_number:'',
      EMIS_number:'',
      roll_number:'',
      id_card_number:'',
      board_registration_number:'',
      mode_of_transport:'',
      driver_name:'',
      driver_contact_number:'',
      notes:'',
      avatar:'',
      avatar_display:'',
      today:'',
      countrylist:[],
      statelist:[],
      citylist:[],
      standardLinklist:[],
      blood_groups:[],
      castelist:[],
      transportlist:[],
      lists:['auto','rickshaw','taxi'],
      errors:[],
      success:null,
    }
  },
  methods:
  {
    getData()
    {
      axios.get('/admin/student/editStudent/'+this.student_name).then(response => {
        this.user = response.data;
        //console.log(this.user)
        this.setData();   
      });
    },

    setData()
    {
      if(Object.keys(this.user).length>0)
      {
        this.firstname            = this.user.firstname;
        this.lastname             = this.user.lastname;
        this.date_of_birth        = this.user.date_of_birth;
        this.gender               = this.user.gender;
        this.blood_group          = this.user.blood_group;
        this.aadhar_number        = this.user.aadhar_number;
        this.city_id              = this.user.city_id;
        this.state_id             = this.user.state_id;
        this.country_id           = this.user.country_id;
        this.pincode              = this.user.pincode;
        this.birth_place          = this.user.birth_place;
        this.native_place         = this.user.native_place;
        this.mother_tongue        = this.user.mother_tongue;
        this.caste                = this.user.caste;
        this.sub_caste            = this.user.sub_caste;
        this.avatar_display       = this.user.avatar;
        this.notes                = this.user.notes;
        this.registration_number  = this.user.registration_number;
        this.EMIS_number          = this.user.EMIS_number;
        this.joining_date         = this.user.joining_date;

        this.standard                   = this.user.standardLink_id;
        this.roll_number                = this.user.roll_number;
        this.id_card_number             = this.user.id_card_number;
        this.board_registration_number  = this.user.board_registration_number;
        this.mode_of_transport          = this.user.mode_of_transport;
        this.driver_name                = this.user.driver_name;
        this.driver_contact_number      = this.user.driver_contact_number;

        this.countrylist      = this.user.countrylist;
        this.statelist        = this.user.statelist;
        this.citylist         = this.user.citylist;
        this.standardLinklist = this.user.standardLinklist;
        this.blood_groups     = this.user.blood_groups;
        this.castelist        = this.user.castelist;
        this.transportlist    = this.user.transportlist;
        this.today            = this.user.today;
      }
    },
              
    submitForm()
    {
      this.errors=[];
      this.success=null; 

      let formData=new FormData(); 

      formData.append('firstname',this.firstname);          
      formData.append('lastname',this.lastname);           
      formData.append('gender',this.gender);          
      formData.append('date_of_birth',this.date_of_birth);       
      formData.append('blood_group',this.blood_group); 
      formData.append('standard',this.standard);
      formData.append('city_id',this.city_id);
      formData.append('state_id',this.state_id);          
      formData.append('country_id',this.country_id);          
      formData.append('pincode',this.pincode);
      formData.append('birth_place',this.birth_place);
      formData.append('native_place',this.native_place);
      formData.append('mother_tongue',this.mother_tongue);
      formData.append('caste',this.caste);
      formData.append('sub_caste',this.sub_caste);
      formData.append('aadhar_number',this.aadhar_number);  
      formData.append('joining_date',this.joining_date);  
      formData.append('registration_number',this.registration_number);   
      formData.append('EMIS_number',this.EMIS_number);  
      formData.append('roll_number',this.roll_number);  
      formData.append('id_card_number',this.id_card_number);  
      formData.append('board_registration_number',this.board_registration_number);  
      formData.append('mode_of_transport',this.mode_of_transport);  
      formData.append('driver_name',this.driver_name);
      formData.append('driver_contact_number',this.driver_contact_number);
      formData.append('notes',this.notes);
      formData.append('avatar',this.avatar);
      formData.append('address', this.address);

      axios.post('/admin/student/edit/validationUser/'+this.student_name,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
        $('#real-submit-btn').click(); 
      }).catch(error => {
        this.errors = error.response.data.errors;
      });
    },

    OnFileSelected(event)
    {
      this.avatar = event.target.files[0];
    },

    checkInArray(array,value)
    {
      if( array.includes(value) )
      {
        return true;
      }
    },
  },
    
  created()
  {
    this.getData();
  }
}

</script>

<style scoped>
  .tw-label{
    color:#3492e2;
  }
  .tw-label input[type="file"] {
    display: none;
  }
</style>