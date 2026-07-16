<template>
	<div class=" overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3 bg-white shadow px-3" v-bind:class="[this.profile_tab==4?'block' :'hidden']">
    <div v-if="this.type == 'add'">
      <GoogleMap
          v-model:address="address"
          v-model:latitude="latitude"
          v-model:longitude="longitude"
        />
    </div>
    <div v-if="this.type == 'edit'">
      <GoogleMap
        v-model:address="address"
        v-model:latitude="latitude"
        v-model:longitude="longitude"
      />
    </div>

      <div class="tw-form-group">
        <div class="flex flex-col lg:flex-row">
          <div class="w-full lg:w-1/4 lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="country" class="tw-form-label">Country<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2">
              <select class="tw-form-control w-full" id="country_id" v-model="country_id" name="country_id">
               <option
                v-for="country in countrylist"
                :key="country.id"
                :value="country.id">
                {{ country.name }}
              </option>
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
               <option
                  v-for="state in statelist[country_id]"
                  :key="state.id"
                  :value="state.id">
                  {{ state.name }}
                </option>
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
               <option
                  v-for="city in citylist[state_id]"
                  :key="city.id"
                  :value="city.id">
                  {{ city.name }}
                </option>
              </select>   
            </div>
            <span v-if="errors.city_id" class="text-red-500 text-xs font-semibold">{{errors.city_id[0]}}</span>
          </div>

          <div class="w-full lg:w-1/4 lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="pincode" class="tw-form-label">Pincode<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2">
              <input type="text" class="tw-form-control w-full" v-model="pincode" name="pincode" id="pincode"  placeholder="Enter Pincode">
            </div>
            <span v-if="errors.pincode" class="text-red-500 text-xs font-semibold">{{errors.pincode[0]}}</span>
          </div>
        </div>
      </div>

        <div class="my-6">
          <a href="#" dusk="submit-btn" class=" btn-primary submit-btn blue-bg text-sm text-white px-2 py-1 rounded mx-1" @click="previous(2)">Previous</a>
          <a href="#" dusk="submit-btn" class="btn-primary submit-btn blue-bg text-sm text-white px-2 py-1 rounded mx-1" @click="submitForm()">Submit</a>
          <a href="#" class="btn-reset reset-btn" @click="resetForm()">Reset</a>
          <input type="submit" class="hidden" id="real-submit-btn">
        </div>
	</div>
</template>

<script> 
	import { bus } from "../../app";
  import GoogleMap from '../GoogleMap.vue'
	export default {
    props:['type' , 'teacher_name'],
    components: {
        GoogleMap
      },
	  	data(){
	    	return {
	      	profile_tab:'',
	      	user:[],
	      	city_id:'',
	      	state_id:'',
	      	country_id:100,
	      	pincode:'',
	      	countrylist:[],
	      	statelist:[],
	      	citylist:[],
	      	errors:[],
	      	success:null,
          address:'',
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
	        		this.countrylist        = this.user.countrylist;
	        		this.statelist          = this.user.statelist;
	        		this.citylist           = this.user.citylist;
	      		}
            else
            {
              this.country_id         = this.user.country_id;
              this.state_id           = this.user.state_id;
              this.city_id            = this.user.city_id;
              this.pincode            = this.user.pincode;
          
              this.countrylist        = this.user.countrylist;
              this.statelist          = this.user.statelist;
              this.citylist           = this.user.citylist;
              
            }
          }
	    	}, 

	    	setProfileTab(val)
	    	{
	      		this.profile_tab=val;
	      		bus.emit("dataProfileTab", this.profile_tab);
	    	},

        resetForm()
      {
          this.city_id='';
          this.state_id='';
          this.country_id=100;
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

	      		formData.append('city_id',this.city_id);
	      		formData.append('state_id',this.state_id);          
	      		formData.append('country_id',this.country_id);          
	      		formData.append('pincode',this.pincode);

            if(this.type == 'add')
            {
              axios.post('/admin/teacher/add/validationAddress',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => { 
                $('#real-submit-btn').click();  
              }).catch(error => {
                this.errors = error.response.data.errors;
              });
            }
	      		else if(this.type == 'edit')
            {
              axios.post('/admin/teacher/edit/validationAddress/'+this.teacher_name,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => { 
                $('#real-submit-btn').click();  
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