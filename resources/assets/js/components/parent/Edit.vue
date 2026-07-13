<template>
  <div class="">
    <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
    <div class="">
      <div class="flex">
        <div class="tw-form-group w-full lg:w-1/3 md:w-1/3">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="relation" class="tw-form-label">Relation<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2">
              <select class="tw-form-control w-full" id="relation" v-model="relation" name="relation">
                <option value="father">Father</option>
                <option value="mother">Mother</option>
                <option value="guardian">Guardian</option>
              </select>
            </div>
            <span v-if="errors.relation" class="text-red-500 text-xs font-semibold">{{errors.relation[0]}}</span>
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
              <input type="text" class="tw-form-control w-full" id="firstname" v-model="firstname" name="firstname">
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
              <input type="text" v-model="lastname" name="lastname" id="lastname" class="tw-form-control w-full">
            </div>
            <span v-if="errors.lastname" class="text-red-500 text-xs font-semibold">{{errors.lastname[0]}}</span>
          </div> 
        </div>

        <div class="tw-form-group w-full lg:w-1/3">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="alternate_no" class="tw-form-label">Alternate Number</label>
            </div>
            <div class="mb-2">
              <input type="text" v-model="alternate_no" name="alternate_no" id="alternate_no" class="tw-form-control w-full" placeholder="Alternate Number"> 
            </div>
            <span v-if="errors.alternate_no" class="text-red-500 text-xs font-semibold">{{errors.alternate_no[0]}}</span>
          </div>
        </div>
      </div>

      <div class="flex flex-col lg:flex-row">
        <div class="tw-form-group w-full lg:w-1/3" v-for="(input, index) in inputs">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="qualification_id" class="tw-form-label">Qualification</label>
            </div>
            <div class="mb-2">
              <select class="tw-form-control w-full" id="qualification_id" v-model="input.qualification_id" name="qualification_id[]">
                <option value="" disabled>Select Qualification</option>
                <option v-for="qualifications in qualificationlist" v-bind:value="qualifications.id">{{ qualifications.display_name }}</option>
              </select>
            </div>
            <span v-if="errors['qualification_id'+index]" class="text-red-500 text-xs font-semibold">{{errors['qualification_id'+index]}}</span>
          </div>
          <a href="#" class="btn-times" @click="deleteRow(index)">
                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-4 h-4 fill-current text-black-500 mx-1"><g><g><g><polygon points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804"></polygon><rect x="235.948" y="175.791" width="40.104" height="237.285"></rect><polygon points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804"></polygon> <path d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74 c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474 L380.665,471.896z"></path></g></g></g> <g><g><path d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42 C354.924,14.992,339.932,0,321.504,0z"></path></g></g></svg>
          </a> 
        </div>
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

      <div class="flex flex-col lg:flex-row">
        <div class="tw-form-group w-full lg:w-1/3">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="profession" class="tw-form-label">Occupation<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2">
              <select class="tw-form-control w-full" id="profession" v-model="profession" name="profession">
                <option value="" disabled>Occupation</option>
                <option v-for="profession in professions" v-bind:value="profession.num">{{ profession.name }}</option>
              </select>
            </div>
            <span v-if="errors.profession" class="text-red-500 text-xs font-semibold">{{errors.profession[0]}}</span>
          </div> 
        </div>

        <div class="tw-form-group w-full lg:w-1/3" v-if="checkInArray(this.occupationlist,this.profession)">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="sub_occupation" class="tw-form-label">Sub-Category</label>
            </div>
            <div class="mb-2">
              <input type="text" v-model="sub_occupation" name="sub_occupation" id="sub_occupation" class="tw-form-control w-full" placeholder="Sub-Category">
            </div>
            <span v-if="errors.sub_occupation" class="text-red-500 text-xs font-semibold">{{errors.sub_occupation[0]}}</span>
          </div> 
        </div>
      </div>

      <div class="flex flex-col lg:flex-row" v-if="checkInArray(this.occupationlist,this.profession)">
        <div class="tw-form-group w-full lg:w-1/3">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="designation" class="tw-form-label">Designation</label>
            </div>
            <div class="mb-2">
              <input type="text" class="tw-form-control w-full" id="designation" v-model="designation" name="designation" placeholder="Designation">
            </div>
            <span v-if="errors.designation" class="text-red-500 text-xs font-semibold">{{errors.designation[0]}}</span>
          </div> 
        </div>

        <div class="tw-form-group w-full lg:w-1/3">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="organization_name" class="tw-form-label">Organization Name</label>
            </div>
            <div class="mb-2">
              <input type="text" v-model="organization_name" name="organization_name" id="organization_name" class="tw-form-control w-full" placeholder="Organization Name">
            </div>
            <span v-if="errors.organization_name" class="text-red-500 text-xs font-semibold">{{errors.organization_name[0]}}</span>
          </div> 
        </div>

        <div class="tw-form-group w-full lg:w-1/3">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="annual_income" class="tw-form-label">Annual Income<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2">
              <input type="text" v-model="annual_income" name="annual_income" id="annual_income" class="tw-form-control w-full" placeholder="Annual Income">
            </div>
            <span v-if="errors.annual_income" class="text-red-500 text-xs font-semibold">{{errors.annual_income[0]}}</span>
          </div> 
        </div>
      </div>

      <GoogleMap
        v-model:address="address"
        v-model:latitude="latitude"
        v-model:longitude="longitude"
        field-name="official_address"
      />

      <div class="flex flex-col lg:flex-row">
        <div class="tw-form-group w-full lg:w-1/6">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="siblings" class="tw-form-label">Siblings<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2">
              <select class="tw-form-control w-full" id="siblings" v-model="siblings" name="siblings">
                <option value="" disabled>Select Sibling</option>
                <option value="yes">Yes</option>
                <option value="no">No</option>
              </select>
            </div>
            <span v-if="errors.siblings" class="text-red-500 text-xs font-semibold">{{errors.siblings[0]}}</span>
          </div>
        </div>

        <div class="tw-form-group w-full lg:w-1/6" v-if="this.siblings == 'yes'">
          <div class="lg:mr-8 md:mr-8">
            <div class="mb-2">
              <label for="siblings_count" class="tw-form-label">Siblings Count<span class="text-red-500">*</span></label>
            </div>
            <div class="mb-2">
              <input type="text" class="tw-form-control w-full" id="siblings_count" v-model="siblings_count" name="siblings_count" placeholder="Siblings Count">
            </div>
            <span v-if="errors.siblings_count" class="text-red-500 text-xs font-semibold">{{errors.siblings_count[0]}}</span>
          </div>
        </div>

        <div class="tw-form-group w-full lg:w-4/6" v-if="this.siblings == 'yes'">
          <div class="flex flex-col lg:flex-row" v-for="(input,index) in siblingInputs">
            <div class="w-full lg:w-1/4">
              <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                  <label for="sibling_relation" class="tw-form-label">Sibling Relation<span class="text-red-500">*</span></label>
                </div>
                <div class="mb-2">
                  <select v-model="input.sibling_relation" name="sibling_relation[]" id="sibling_relation" class="tw-form-control w-full">
                    <option value="" disabled>Select Relation</option>
                    <option v-for="sibling in siblinglist" v-bind:value="sibling.id">{{ sibling.name }}</option>
                  </select>
                </div>
                <span v-if="errors['sibling_relation'+index]" class="text-red-500 text-xs font-semibold">{{errors['sibling_relation'+index]}}</span>
              </div>
            </div>

            <div class="w-full lg:w-1/4">
              <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                  <label for="sibling_name" class="tw-form-label">Sibling Name<span class="text-red-500">*</span></label>
                </div>
                <div class="mb-2">
                  <input type="text" v-model="input.sibling_name" name="sibling_name[]" id="sibling_name" class="tw-form-control w-full" placeholder="Sibling Name">
                </div>
                <span v-if="errors['sibling_name'+index]" class="text-red-500 text-xs font-semibold">{{errors['sibling_name'+index]}}</span>
              </div>
            </div>

            <div class="w-full lg:w-1/4">
              <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                  <label for="sibling_date_of_birth" class="tw-form-label">Sibling Date Of Birth<span class="text-red-500">*</span></label>
                </div>
                <div class="mb-2">
                  <input type="date" v-model="input.sibling_date_of_birth" name="sibling_date_of_birth[]" id="sibling_date_of_birth" class="tw-form-control w-full">
                </div>
                <span v-if="errors['sibling_date_of_birth'+index]" class="text-red-500 text-xs font-semibold">{{errors['sibling_date_of_birth'+index]}}</span>
              </div>
            </div>

            <div class="w-full lg:w-1/4">
              <div class="lg:mr-8 md:mr-8">
                <div class="mb-2">
                  <label for="sibling_standard" class="tw-form-label">Sibling Class</label>
                </div>
                <div class="mb-2">
                  <select class="tw-form-control w-full" id="sibling_standard" v-model="input.sibling_standard" name="sibling_standard[]">
                    <option value="" disabled>Select Class</option>
                    <option value="not_studying">Not Studying In This School</option>
                    <option v-for="standardLink in standardLinklist" v-bind:value="standardLink.id">{{ standardLink.standard_section }}</option>
                  </select>
                </div>
                <span v-if="errors['sibling_standard'+index]" class="text-red-500 text-xs font-semibold">{{errors['sibling_standard'+index]}}</span>
              </div>
            </div>
            <a href="#" class="py-8" @click="deleteSiblingRow(index)">
              <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-4 h-4 fill-current text-black-600"><g><g><g><polygon points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804"></polygon><rect x="235.948" y="175.791" width="40.104" height="237.285"></rect><polygon points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804"></polygon> <path d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74 c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474 L380.665,471.896z"></path></g></g></g> <g><g><path d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42 C354.924,14.992,339.932,0,321.504,0z"></path></g></g></svg>
            </a>
          </div>

          <div class="w-full lg:w-1/4">
            <div class="lg:mr-8 md:mr-8">
              <div class="mb-2">
                <label for="add_siblings" class="tw-form-label">Add Sibling</label>
              </div>
              <div class="mb-2">
                <a href="#" class="tw-form-control w-full" @click="addSiblingRow">+ </a>
              </div>
            </div>
          </div>
        </div>
      </div>

        <div class="my-6">
          <a href="#" dusk="submit-btn" class="btn btn-primary submit-btn" @click="submitForm()">Submit</a>
          <input type="submit" class="hidden" id="submit-btn">
        </div>
    </div>
  </div>
</template>

<script>
import { bus } from "../../app";
import GoogleMap from '../GoogleMap.vue'
export default {
  props:['url' , 'name' , 'ref_name'],
  components: {
    GoogleMap
  },

  data(){
    return {
      user:[],
      childrenName:'',
      firstname:'',
      lastname:'',
      alternate_no:'',
      qualification_id:'',
      inputs: [{
        qualification_id:'',
      }],
      profession:'',
      sub_occupation:'',
      designation:'',
      organization_name:'',
      relation:'',
      annual_income:'',
      qualificationlist:[],
      occupationlist:['business','central_government_employee','private','state_government_employee','others'],
      professions:[{num:'business' , name:'Business'} , {num:'central_government_employee' , name:'Central Government Employee'} , {num:'private' , name:'Private'} , {num:'home_maker' , name:'Home Maker'} , {num:'state_government_employee' , name:'State Government Employee'} , {num:'others' , name:'Others'} ],
      siblings:'',
      siblings_count:'',
      siblingInputs: [{
        sibling_relation:'',
        sibling_name:'',
        sibling_date_of_birth:'',
        sibling_standard:'',
      }],
      siblinglist:[{id:'brother' , name:'Brother'} , {id:'sister' , name:'Sister'}],
      standardLinklist:[],
      errors:[],
      success:null,
      latitude:'',
      longitude:'',
      address:'',
    }
  },

    methods:
    {
      initMap() {
                const defaultLat = 9.9252007;
                const defaultLng = 78.1197754;

                const center = new google.maps.LatLng(defaultLat, defaultLng);

                this.map = new google.maps.Map(
                    document.getElementById("map_canvas"),
                    {
                        zoom: 15,
                        center: center
                    }
                );

                this.marker = new google.maps.Marker({
                    position: center,
                    map: this.map,
                    draggable: true
                });

                this.latitude = defaultLat;
                this.longitude = defaultLng;

                this.geocoder = new google.maps.Geocoder();

                google.maps.event.addListener(this.marker, 'mouseup', (event) => {
                    this.latitude = event.latLng.lat();
                    this.longitude = event.latLng.lng();
                });

                const autocomplete = new google.maps.places.Autocomplete(
                    document.getElementById("official_address")
                );

                autocomplete.addListener("place_changed", () => {
                    const place = autocomplete.getPlace();
                    if (!place.geometry) return;

                    const lat = place.geometry.location.lat();
                    const lng = place.geometry.location.lng();
                    this.updateLocation(lat, lng);
                });
            },

            updateLocation(lat, lng) {
                const latLng = new google.maps.LatLng(lat, lng);
                this.latitude = lat;
                this.longitude = lng;
                this.map.setCenter(latLng);
                this.marker.setPosition(latLng);
            },

            codeAddress() {
                this.geocoder.geocode(
                    { address: this.official_address },
                    (results, status) => {
                        if (status === "OK") {
                            const lat = results[0].geometry.location.lat();
                            const lng = results[0].geometry.location.lng();
                            this.updateLocation(lat, lng);
                        }
                    }
                );
            },
      getData()
      {
        axios.get('/admin/parent/editlist/'+this.name+'?ref_name='+this.ref_name).then(response => {
          this.user = response.data;
          //console.log(this.user)
          this.setData();
        });
      },

      setData()
      {
        if(Object.keys(this.user).length>0)
        {
          this.firstname          = this.user.firstname;
          this.lastname           = this.user.lastname;
          this.alternate_no       = this.user.alternate_no;
          this.profession         = this.user.profession;
          this.sub_occupation     = this.user.sub_occupation;
          this.designation        = this.user.designation;
          this.organization_name  = this.user.organization_name;
          this.annual_income      = this.user.annual_income;
          this.relation           = this.user.relation;
          this.address             = this.user.official_address;
          if(Object.keys(this.user.qualification_id).length > 0)
          {
            this.inputs             = this.user.qualification_id;
            }
          this.qualificationlist  = this.user.qualificationlist;
          this.standardLinklist   = this.user.standardLinklist;
          this.siblings            = this.user.siblings;
          this.siblings_count      = this.user.siblings_count;
          if((this.user.siblings == 'yes') && (this.user.sibling_details) && (this.user.sibling_details.length > 0))
          {
            this.siblingInputs       = this.user.sibling_details;
          }
        }
      },

      submitForm()
      {
        this.errors=[];
        this.success=null;

        let formData=new FormData();

        formData.append('firstname',this.firstname);
        formData.append('lastname',this.lastname);
        formData.append('alternate_no',this.alternate_no);
        formData.append('profession',this.profession);
        formData.append('sub_occupation',this.sub_occupation);
        formData.append('designation',this.designation);
        formData.append('organization_name',this.organization_name);
        formData.append('relation',this.relation);
        formData.append('annual_income',this.annual_income);
        formData.append('count',this.inputs.length);
        formData.append('official_address',this.address);
        formData.append('ref_name',this.ref_name);

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

        formData.append('siblings',this.siblings);
        formData.append('siblings_count',this.siblings_count);
        formData.append('sibling_row_count',this.siblingInputs.length);

        if(this.siblings == 'yes')
        {
          for(let i=0 ; i < this.siblingInputs.length ; i++)
          {
            if(typeof this.siblingInputs[i]['sibling_relation'] !== "undefined")
            {
              formData.append('sibling_relation'+i,this.siblingInputs[i]['sibling_relation']);
            }
            else
            {
              formData.append('sibling_relation'+i,'');
            }

            if(typeof this.siblingInputs[i]['sibling_name'] !== "undefined")
            {
              formData.append('sibling_name'+i,this.siblingInputs[i]['sibling_name']);
            }
            else
            {
              formData.append('sibling_name'+i,'');
            }

            if(typeof this.siblingInputs[i]['sibling_date_of_birth'] !== "undefined")
            {
              formData.append('sibling_date_of_birth'+i,this.siblingInputs[i]['sibling_date_of_birth']);
            }
            else
            {
              formData.append('sibling_date_of_birth'+i,'');
            }

            if(typeof this.siblingInputs[i]['sibling_standard'] !== "undefined")
            {
              formData.append('sibling_standard'+i,this.siblingInputs[i]['sibling_standard']);
            }
            else
            {
              formData.append('sibling_standard'+i,'');
            }
          }
        }

        axios.post('/admin/parent/edit/'+this.name,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
          $('#submit-btn').click();
        }).catch(error => {
          this.errors = error.response.data.errors;
        });
    },
    checkInArray(array,value) 
    {
      if( array.includes(value) )
      {
        return true;
      }
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

    addSiblingRow()
    {
      this.siblingInputs.push({
        sibling_relation:'',
        sibling_name:'',
        sibling_date_of_birth:'',
        sibling_standard:'',
      });
    },

    deleteSiblingRow(index)
    {
      this.siblingInputs.splice(index,1);
    }
  },

  created()
  {
    //
    this.getData();
  }
 }

</script>