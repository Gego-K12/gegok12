<template>
  <div class="">
  	<div>
  	  <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

      <!--radio button-->

   <div>
     
    <div>
      <div class="flex">
        <div class="w-48 flex items-center py-2"> 
          <input type="radio" name="media" id="uploadmedia" value="uploadmedia">
          <span class="text-sm mx-1">Media Upload</span>
        </div>
        <div class="w-48 flex items-center py-2"> 
          <input type="radio" name="media" id="studymedia" value="studymedia">
          <span class="text-sm mx-1">Study Material</span>
        </div>
      </div>
    </div>
    </div>

  <!--radio button-->

   <!--class drop down-->
  <div class="hidden" id="study">
      <div class="my-3">
       
            <div class="w-full lg:w-1/4">
              <label for="standardLink_id" class="tw-form-label">Select Class</label>
            </div>
            <div class="w-full lg:w-2/5 my-2">
              <select name="standardLink_id" class="tw-form-control">
                <option value="">Select Class</option>
                @foreach($standardLinks as $standardLink)
                 <option value="{{ $standardLink->id }}">{{ $standardLink->StandardSection }}</option>
               @endforeach
              </select>
               <span class="text-red-500 text-xs font-semibold">{{$errors->first('standardLink_id')}}</span>
            </div>
          </div>
        </div>
    
  <!--end class drop down-->


      <div class="my-3">
        <div class="flex items-center">
          <img :src="school_logo" class="img-responsive w-20 h-20 rounded-full">
          <div class="mx-2">
            <label class="tw-label cursor-pointer text-xs text-gray-600"> Change School Logo
              <input type="file" size="60" name="school_logo" id="school_logo" @change="OnFileSelected">
              <span v-if="errors.school_logo" class="text-red-500 text-xs font-semibold">{{errors.school_logo[0]}}</span>
            </label> 
          </div>
        </div>
      </div>

      <div class="flex flex-col lg:flex-row">
      <div class="tw-form-group w-full lg:w-1/2">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="title" class="tw-form-label">Title<span class="text-red-500">*</span></label>
          </div>
          <div class="w-full lg:w-3/4 my-2">
            <input type="text" name="title" v-model="title" id="title" class="tw-form-control w-full">
          </div>
          <span v-if="errors.title" class="text-red-500 text-xs font-semibold">{{errors.title[0]}}</span>
        </div>
      </div>


      <div class="tw-form-group w-full lg:w-1/2">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2">
            <label for="description" class="tw-form-label">Description<span class="text-red-500">*</span></label>
          </div>
          <div class="w-full lg:w-3/4 my-2">
            <input type="text" name="description" v-model="description" id="description" class="tw-form-control w-full">
          </div>
          <span v-if="errors.description" class="text-red-500 text-xs font-semibold">{{errors.description[0]}}</span>
        </div>
      </div>
    </div>


    <div class="flex flex-col lg:flex-row">
     
      <div class="tw-form-group w-full lg:w-1/2">
        <div class="lg:mr-8 md:mr-8">
          <div class="mb-2"> 
            <label for="type" class="tw-form-label">Type<span class="text-red-500">*</span></label>
          </div>
          <div class="w-full lg:w-3/4 my-2">
            <select name="type" v-model="type" id="type" class="tw-form-control w-full">
              <option value="" disabled="disabled">Select type</option>
              <option v-for="boards in boardlist" v-bind:id="boards.id">{{ boards.name }}</option>
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
            <label for="landline_no" class="tw-form-label">Landline No<span class="text-red-500">*</span></label>
          </div>
          <div class="w-full lg:w-3/4 my-2">
            <input type="text" name="landline_no" v-model="landline_no" id="landline_no" class="tw-form-control w-full" placeholder="Landline No">
          </div>
          <span v-if="errors.landline_no" class="text-red-500 text-xs font-semibold">{{errors.landline_no[0]}}</span>
        </div>
      </div>
    </div>

  

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

    <portal-target name="submit-btn"></portal-target>
    <portal to="submit-btn">
      <div class="my-6">
        <a href="#" dusk="submit-btn" class="btn btn-primary submit-btn" @click="updateDetails()">Submit</a>
        <a href="#" class="btn btn-reset reset-btn" @click="resetForm()">Reset</a>
        <input type="submit" class="hidden" id="submit-btn">
      </div>
    </portal>
  	</div>
  </div>
</template>


<script>
  export default {
    props:['url','school_id'],

    data(){
      return{
        details:[],
        name:'',
        moto:'',
        affiliated_by:'',
        affiliation_no:'',
        date_of_establishment:'',
        board:'',
        school_logo:'',
        landline_no:'',
        about_us:'',
        country_id:7,
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
      getDetails()
      {
        axios.get('/admin/schooldetails/edit/'+this.school_id).then(response => {
          this.details= response.data.details;
          this.setDetails();
          //console.log(this.details);               
        });      
      },

      setDetails()
      {
        if(Object.keys(this.details).length > 0)
        {
          this.moto                   = this.details.moto;
          this.affiliated_by          = this.details.affiliated_by;
          this.affiliation_no         = this.details.affiliation_no;
          this.date_of_establishment  = this.details.date_of_establishment;
          this.board                  = this.details.board;
          this.school_logo            = this.details.school_logo;
          this.landline_no            = this.details.landline_no;
          this.about_us               = this.details.about_us;
          this.country_id             = this.details.country_id;
          this.state_id               = this.details.state_id;
          this.city_id                = this.details.city_id;
          this.pincode                = this.details.pincode;
          this.name                   = this.details.name;

          this.countrylist            = this.details.countrylist;
          this.statelist              = this.details.statelist;
          this.citylist               = this.details.citylist;
        }
      },

      updateDetails()
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
              
        axios.post('/admin/schooldetails/update/validationUpdate/'+this.school_id,formData).then(response => {              
          $('#submit-btn').click();
        }).catch(error => {
          this.errors = error.response.data.errors;
        });
      },

      OnFileSelected(event)
      {
        this.school_logo = event.target.files[0];
      },
    },        

    created()
    {
      this.getDetails();
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