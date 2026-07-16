<template>
  <div class="">
	  <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
    <div class="flex flex-col lg:flex-row">
      <div class="tw-form-group w-full lg:w-1/2">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="name" class="tw-form-label">School Name<span class="text-red-500">*</span></label>
          </div>
          <div class="w-full lg:w-3/4 my-2">
            <input type="text" name="name" v-model="name" id="name" class="tw-form-control w-full" placeholder="Enter School Name">
          </div>
          <span v-if="errors.name" class="text-red-500 text-xs font-semibold">{{errors.name[0]}}</span>
        </div>
      </div>
  		<div class="tw-form-group w-full lg:w-1/2">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="moto" class="tw-form-label">School Moto<span class="text-red-500">*</span></label>
          </div>
          <div class="w-full lg:w-3/4 my-2">
            <input type="text" name="moto" v-model="moto" id="moto" class="tw-form-control w-full" placeholder="Enter School Moto">
          </div>
          <span v-if="errors.moto" class="text-red-500 text-xs font-semibold">{{errors.moto[0]}}</span>
        </div>
      </div>
    </div>

    <div class="flex flex-col lg:flex-row">
      <div class="tw-form-group w-full lg:w-1/2">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="affiliated_by" class="tw-form-label">Affiliated By<span class="text-red-500">*</span></label>
          </div>
          <div class="w-full lg:w-3/4 my-2">
            <input type="text" name="affiliated_by" v-model="affiliated_by" id="affiliated_by" class="tw-form-control w-full" placeholder="Affiliated By">
          </div>
          <span v-if="errors.affiliated_by" class="text-red-500 text-xs font-semibold">{{errors.affiliated_by[0]}}</span>
        </div>
      </div>

      <div class="tw-form-group w-full lg:w-1/2">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="affiliation_no" class="tw-form-label">Affiliation No.<span class="text-red-500">*</span></label>
          </div>
          <div class="w-full lg:w-3/4 my-2">
            <input type="text" name="affiliation_no" v-model="affiliation_no" id="affiliation_no" class="tw-form-control w-full" placeholder="Affiliation No">
          </div>
          <span v-if="errors.affiliation_no" class="text-red-500 text-xs font-semibold">{{errors.affiliation_no[0]}}</span>
        </div>
      </div>
    </div>

    <div class="flex flex-col lg:flex-row">
      <div class="tw-form-group w-full lg:w-1/2">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="date_of_establishment" class="tw-form-label">Date Of Establishment<span class="text-red-500">*</span></label>
          </div>
          <div class="w-full lg:w-3/4 my-2">
            <input type="date" name="date_of_establishment" v-model="date_of_establishment" id="date_of_establishment" class="tw-form-control w-full">
          </div>
          <span v-if="errors.date_of_establishment" class="text-red-500 text-xs font-semibold">{{errors.date_of_establishment[0]}}</span>
        </div>
      </div>

      <div class="tw-form-group w-full lg:w-1/2">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2"> 
            <label for="board" class="tw-form-label">Board Of Education<span class="text-red-500">*</span></label>
          </div>
          <div class="w-full lg:w-3/4 my-2">
            <select name="board" v-model="board" id="board" class="tw-form-control w-full">
              <option value="" disabled="disabled">Select board</option>
              <option v-for="boards in boardlist" v-bind:value="boards.id">{{ boards.name }}</option>
            </select>
            <span v-if="errors.board" class="text-red-500 text-xs font-semibold">{{errors.board[0]}}</span>
          </div>
        </div>
      </div>
    </div>

    <div class="flex flex-col lg:flex-row">
    	<div class="tw-form-group w-full lg:w-1/2">
      	<div class="lg:mr-8 md:mr-8">
        	<div class="mb-2">
          	<label for="school_logo" class="tw-form-label">School Logo<span class="text-red-500">*</span></label>
        	</div>
        	<div class="w-full lg:w-3/4 my-2">
      			<input type="file" name="school_logo" @change="OnImageSelected" id="school_logo" class="tw-form-control w-full">
    		  </div>
    			<span v-if="errors.school_logo" class="text-red-500 text-xs font-semibold">{{errors.school_logo[0]}}</span>
      	</div>
    	</div>

      <div class="tw-form-group w-full lg:w-1/2">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="landline_no" class="tw-form-label">Landline No<span class="text-red-500">*</span></label>
          </div>
          <div class="w-full lg:w-3/4 my-2">
            <input type="text" name="landline_no" v-model="landline_no" id="landline_no" class="tw-form-control w-full" placeholder="Landline No">
          </div>
          <span v-if="errors.landline_no" class="text-red-500 text-xs font-semibold">{{errors.landline_no[0]}}</span>
        </div>
      </div>
    </div>

    <div id="school_address"></div>

    <div class="flex flex-col lg:flex-row">
      <div class="tw-form-group w-full lg:w-1/2">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="country" class="tw-form-label">Country<span class="text-red-500">*</span></label>
          </div>
          <div class="w-full lg:w-3/4 my-2">
            <select class="tw-form-control w-full" id="country_id" v-model="country_id" name="country_id">
              <option value="" disabled>Select Country</option>
              <option v-for="country in countrylist" v-bind:value="country.id">{{country.name}}</option>
            </select>
          </div>
          <span v-if="errors.country_id" class="text-red-500 text-xs font-semibold">{{errors.country_id[0]}}</span>
        </div>
      </div>

      <div class="tw-form-group w-full lg:w-1/2">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="state" class="tw-form-label">State<span class="text-red-500">*</span></label>
          </div>
          <div class="w-full lg:w-3/4 my-2">
            <select class="tw-form-control w-full" id="state_id" v-model="state_id" name="state_id">
              <option value="" disabled>Select State</option>
              <option v-for="state in statelist[this.country_id]" v-bind:value="state.id">{{state.name}}</option>
            </select>  
          </div>
          <span v-if="errors.state_id" class="text-red-500 text-xs font-semibold">{{errors.state_id[0]}}</span>
        </div>
      </div>
    </div>

    <div class="flex flex-col lg:flex-row">
      <div class="tw-form-group w-full lg:w-1/2">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="city" class="tw-form-label">City<span class="text-red-500">*</span></label>
          </div>
          <div class="w-full lg:w-3/4 my-2">
            <select class="tw-form-control w-full" id="city_id" v-model="city_id" name="city_id">
              <option value="" disabled>Select City</option>
              <option v-for="city in citylist [this.state_id]" v-bind:value="city.id">{{city.name}}</option>
            </select>   
          </div>
          <span v-if="errors.city_id" class="text-red-500 text-xs font-semibold">{{errors.city_id[0]}}</span>
        </div>
      </div>

      <div class="tw-form-group w-full lg:w-1/2">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="pincode" class="tw-form-label">Pincode<span class="text-red-500">*</span></label>
          </div>
          <div class="w-full lg:w-3/4 my-2">
            <input type="text" class="tw-form-control w-full" v-model="pincode" name="pincode" id="pincode"  placeholder="Enter Pincode">
          </div>
          <span v-if="errors.pincode" class="text-red-500 text-xs font-semibold">{{errors.pincode[0]}}</span>
        </div>
      </div>
    </div>

    <div class="flex flex-col lg:flex-row">
      <div class="tw-form-group w-full lg:w-1/2">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="about_us" class="tw-form-label">About Us<span class="text-red-500">*</span></label>
          </div>
          <div class="w-full lg:w-3/4 my-2">
            <textarea type="textarea" name="about_us" v-model="about_us" id="about_us" class="tw-form-control w-full" placeholder="Enter About Us"></textarea>
          </div>
          <span v-if="errors.about_us" class="text-red-500 text-xs font-semibold">{{errors.about_us[0]}}</span>
        </div>
      </div>
    </div>

    <div id="submit-btn"></div>
    <Teleport to="#submit-btn">
      <div class="my-6">
        <a href="#" dusk="submit-btn" class="btn btn-primary submit-btn" @click="submitForm()">Submit</a>
        <a href="#" class="btn btn-reset reset-btn" @click="resetForm()">Reset</a>
        <input type="submit" class="hidden" id="submit-btn">
      </div>
    </Teleport>
  </div>
</template>

<script>
	export default {
    props:[],

    data(){
      return{
        list:[],
        name:'',
        moto:'',
        affiliated_by:'',
        affiliation_no:'',
        date_of_establishment:'',
        board:'',
        school_logo:'',
        landline_no:'',
        about_us:'',
        country_id:100,
        state_id:'',
        city_id:'',
        pincode:'',
        countrylist:[],
        statelist:[],
        citylist:[],
        boardlist:[ {id:'stateboard' , name:'State Board'} , {id:'matric' , name:'Matriculation'} , {id:'cbse' , name:'CBSE'} , {id:'icse' , name:'ICSE'} , {id:'ib' , name:'IB'} ],
        errors:[],
        success:null,
      }
    },
        
    methods:
    {
      getData()
      {
        axios.get('/admin/schooldetails/list').then(response => {
          this.list = response.data;
          //console.log(this.list)
          this.setData();   
        });
      },

      setData()
      {
        if(Object.keys(this.list).length>0)
        {
          this.name         = this.list.school_name;
          this.countrylist  = this.list.countrylist;
          this.statelist    = this.list.statelist;
          this.citylist     = this.list.citylist;
        }
      },

      submitForm()
      {
        this.errors=[];
        this.success=null; 

        let formData=new FormData();

        formData.append('name',this.name);         
        formData.append('moto',this.moto);         
        formData.append('affiliated_by',this.affiliated_by);          
        formData.append('affiliation_no',this.affiliation_no);          
        formData.append('date_of_establishment',this.date_of_establishment);          
        formData.append('board',this.board);          
        formData.append('school_logo',this.school_logo);          
        formData.append('landline_no',this.landline_no);          
        formData.append('about_us',this.about_us);          
        formData.append('country_id',this.country_id);          
        formData.append('state_id',this.state_id);          
        formData.append('city_id',this.city_id);          
        formData.append('pincode',this.pincode);          
              
        axios.post('/admin/schooldetails/create/validationStore',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {            
          $('#submit-btn').click();
        }).catch(error => {
          this.errors = error.response.data.errors;
        });
      },

      OnImageSelected(event)
      {
        this.school_logo = event.target.files[0];
      },
    },
    
    created()
    {
      this.getData();
    }
  }
</script>