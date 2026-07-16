<template>
<div class="">
<h1 class="my-3 admin-h1">Edit Profile</h1>
<div class="bg-white shadow border border-grey px-5">
<form class="my-2" id="edit_profile">
    <div v-if="this.success!=null" class="font-muller alert alert-success" id="success-alert">{{this.success}}</div>

<div class="tw-form-group"> 
  <div class="my-1 lg:mr-8 md:mr-8">  
    <label class="tw-form-label">Name</label>
  </div>  

  <div class="flex flex-col lg:flex-row md:flex-row">
    <div class="w-full lg:w-1/3 mr-2 lg:mr-8 md:mr-8">
      <div class="mb-2">
        
        <input type="text" v-model="firstname" name="firstname" id="firstname" class="tw-form-control w-full " value="" placeholder="First Name">
      </div>
      <span v-if="errors.firstname" class="text-red-500 text-xs font-semibold">{{errors.firstname[0]}}</span>
    </div>
    <div class="w-full lg:w-1/3 mr-2 lg:mr-8 md:mr-8">
      <div class="mb-2">
       
        <input type="text" v-model="lastname" name="lastname" id="lastname" class="tw-form-control w-full " value="" placeholder="Last Name">
      </div>
      <span v-if="errors.lastname" class="text-red-500 text-xs font-semibold">{{errors.lastname[0]}}</span>
    </div>
  </div>
</div>

<!-- <div class="tw-form-group "> 
  <div class="my-1 lg:mr-8 md:mr-8">  
    <label class="tw-form-label">Birth Name</label>
  </div>   
  <div class="flex">
    <div class="w-full lg:w-1/3 mr-2 lg:mr-8 md:mr-8">
      <div class="mb-2">
        <span class="absolute m-2">
          <img :src="this.url+'/uploads/icons/form-user.svg'" class="w-4 h-4">
        </span>
        <input type="text" v-model="birth_firstname" name="birth_firstname" id="birth_firstname" class="tw-form-control w-full member-icon" value="" placeholder="Birth First Name">
      </div>
      <span v-if="errors.birth_firstname" class="text-red-500 text-xs font-semibold">{{errors.birth_firstname[0]}}</span>
    </div>
    <div class="w-full lg:w-1/3 mr-2 lg:mr-8 md:mr-8">
      <div class="mb-2">
        <span class="absolute m-2">
          <img :src="this.url+'/uploads/icons/form-user.svg'" class="w-4 h-4">
        </span>
        <input type="text" v-model="birth_lastname" name="birth_lastname" id="birth_lastname" class="tw-form-control w-full member-icon" value="" placeholder="Birth Last Name">
      </div>
      <span v-if="errors.birth_lastname" class="text-red-500 text-xs font-semibold">{{errors.birth_lastname[0]}}</span>
    </div>
  </div>
</div> -->



  <div class="tw-form-group">
    <div class="w-full lg:w-1/3 mr-2 lg:mr-8 md:mr-8">
      <div class="mb-2">
        <label for="date_of_birth" class="tw-form-label">Date Of Birth:</label>
      </div>
      <div class="mb-2 mr-2">
        <input type="date" v-model="date_of_birth" name="date_of_birth" class="tw-form-control w-full" value="">
      </div>
      <span v-if="errors.date_of_birth" class="text-red-500 text-xs font-semibold">{{errors.date_of_birth[0]}}</span>
  </div> 
</div>


<div class="tw-form-group">
  <div class="mb-2">
    <label for="gender" class="tw-form-label">Gender</label>
  </div>
  <div class="flex">
    <div class="w-1/2 lg:w-1/3 flex items-center tw-form-control mr-2 lg:mr-8 md:mr-8"> 
      <input type="radio" v-model="gender" name="gender" value="male">
      <span class="text-sm mx-1">Male</span>
    </div>
    <div class="w-1/2 lg:w-1/3 flex items-center tw-form-control mr-2 lg:mr-8 md:mr-8"> 
      <input type="radio" v-model="gender" name="gender" value="female">
      <span class="text-sm mx-1">Female</span>
    </div>
    </div>
    <span v-if="errors.gender" class="text-red-500 text-xs font-semibold">{{errors.gender[0]}}</span>
  </div>

<div class="tw-form-group">
  <div class="flex flex-col lg:flex-row md:flex-row">
    <div class="w-full lg:w-1/3 mr-2 lg:mr-8 md:mr-8">
      <div class="mb-2">
        <label for="address" class="tw-form-label">Address</label>
      </div>
      <div class="mb-2 w-full">
        <input type="string" class="tw-form-control w-full" v-model="address" name="address" value="">
      </div>
      <span v-if="errors.address" class="text-red-500 text-xs font-semibold">{{errors.address[0]}}</span>
    </div>

    <div class="w-full lg:w-1/3 mr-2 lg:mr-8 md:mr-8">
      <div class="mb-2">
        <label class="tw-form-label">Country</label>
      </div>
      <div class="mb-2">
        <!-- <select class="tw-form-control w-full" id="country_id" v-model="country_id" name="country_id">
        <option :value="this.country_id">{{ this.country_id }}</option>
         <option v-for="country in countrylist" v-bind:value="country.id">{{country.name}}</option>
        </select> -->
        <select class="tw-form-control w-full" id="country_id" v-model="country_id" name="country_id">
        <option value="">Select Country</option>
        <option v-for="country in countrylist" :value="country.id" :key="country.id">
          {{ country.name }}
        </option>
      </select>

      </div>
      <span v-if="errors.country_id" class="text-red-500 text-xs font-semibold">{{errors.country_id[0]}}</span>
    </div>
  </div>
</div>

<div class="tw-form-group">
  <div class="flex flex-col lg:flex-row md:flex-row">
    <div class="w-full lg:w-1/3 mr-2 lg:mr-8 md:mr-8">
      <div class="mb-2">
        <label class="tw-form-label">City</label>
      </div>
      <div class="mb-2">
        <!-- <select class="tw-form-control w-full" id="city_id" v-model="city_id" name="city_id">
         <option :value="this.city_id">{{ this.city_id }}</option>
         <option value="" v-for="city in citylist" v-bind:value="city.id">{{city.name}}</option>
        </select>  -->  
        <select class="tw-form-control w-full" v-model="city_id" name="city_id">
        <option value="">Select City</option>
        <option v-for="city in citylist" :value="city.id" :key="city.id">
          {{ city.name }}
        </option>
      </select>
      </div>
      <span v-if="errors.city_id" class="text-red-500 text-xs font-semibold">{{errors.city_id[0]}}</span>
    </div>

    <div class="w-full lg:w-1/3 mr-2 lg:mr-8 md:mr-8">
      <div class="mb-2">
        <label class="tw-form-label">State</label>
      </div>
      <div class="mb-2">
        <!-- <select class="tw-form-control w-full" id="state_id" v-model="state_id" name="state_id">
         <option :value="this.state_id">{{ this.state_id }}</option>
         <option value="" v-for="state in statelist" v-bind:value="state.id">{{state.name}}</option>
        </select> --> 
        <select class="tw-form-control w-full" id="state_id" v-model="state_id" name="state_id">
        <option value="">Select State</option>
        <option v-for="state in statelist" :value="state.id" :key="state.id">
          {{ state.name }}
        </option>
      </select> 
      </div>
      <span v-if="errors.state_id" class="text-red-500 text-xs font-semibold">{{errors.state_id[0]}}</span>
    </div>

    <div class="w-full lg:w-1/3 mr-2 lg:mr-8 md:mr-8">
      <div class="mb-2">
        <label for="pincode" class="tw-form-label">Pincode:</label>
      </div>
      <div class="mb-2">
        <input type="pincode" class="tw-form-control w-full" v-model="pincode" name="pincode" value="">
      </div>
      <span v-if="errors.pincode" class="text-red-500 text-xs font-semibold">{{errors.pincode[0]}}</span>
    </div>
  </div>
</div>
<div>
<div class="my-6">
  <a href="#" class="btn btn-primary submit-btn" @click="checkForm()">Submit</a>
</div>
</div>
</form>
</div>
</div>

</template>
<script> 
export default {
  props:['url'],
   data(){
      return {
          user:[],
          firstname:'',
          lastname:'',
          birth_firstname:'',
          birth_lastname:'',
          gender:'',
          date_of_birth:'',
          address:'',
          city_id:'',
          state_id:'',
          country_id:'100',
          pincode:'',
          countrylist:[],
          statelist:[],
          citylist:[],
          errors:[],
          success:null,
        }
    },
    methods:{
            

             checkForm()
            {
              this.errors=[];
              this.success=null;    
              let formData=new FormData();
             
              formData.append('firstname',this.firstname);          
              formData.append('lastname',this.lastname);          
             /* formData.append('birth_firstname',this.birth_firstname);          
              formData.append('birth_lastname',this.birth_lastname);*/          
              formData.append('gender',this.gender);          
              formData.append('date_of_birth',this.date_of_birth);                
              formData.append('address',this.address);
              formData.append('city_id',this.city_id);
              formData.append('state_id',this.state_id);          
              formData.append('country_id',this.country_id);          
              formData.append('pincode',this.pincode);                   
              formData.append('countrylist',this.countrylist);
              formData.append('statelist',this.statelist);
              formData.append('citylist',this.citylist);
        axios.post('/admin/profile',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
           this.success = response.data.message;
          
        }).catch(error => {
          this.errors = error.response.data.errors;
        });
    },
     
      getData()
          {
            axios.get('/admin/profile').then(response => {
            this.user = response.data;
            this.setData();   
            });
          },

      setData()
      {
        if(Object.keys(this.user).length>0)
        {
          this.firstname=this.user.firstname;
          this.lastname=this.user.lastname;
          /*this.birth_firstname=this.user.birth_firstname;
          this.birth_lastname=this.user.birth_lastname;*/
          this.gender=this.user.gender;
          this.date_of_birth=this.user.date_of_birth;
          this.address=this.user.address;
          this.country_id=this.user.country_id;
          this.city_id=this.user.city_id;
          this.state_id=this.user.state_id;
          this.countrylist=this.user.countrylist;
          this.statelist=this.user.statelist;
          this.citylist=this.user.citylist;
          this.pincode=this.user.pincode;
          //console.log(this.name);
        }
      },
  },
    
  created()
  {
    //alert(this.ref_name);
        this.getData();
  }
 }

</script>

<style scoped="">


</style>