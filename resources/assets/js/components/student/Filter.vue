<template>
    <div>
        <Teleport to="#search">
            <div class="relative mt-2">
                <input type="text" class="border px-10 py-2 text-sm border-gray-400 w-full rounded bg-white shadow"  placeholder="Search query" readonly="" @click="showfilter()">
                <span class="input-group-btn absolute left-0 px-3 py-3 top-0">
                    <a href="#">
                        <svg class="w-4 h-4 fill-current text-gray-600" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30.239px" height="30.239px" viewBox="0 0 30.239 30.239" style="enable-background:new 0 0 30.239 30.239;" xml:space="preserve"><g><path d="M20.194,3.46c-4.613-4.613-12.121-4.613-16.734,0c-4.612,4.614-4.612,12.121,0,16.735 c4.108,4.107,10.506,4.547,15.116,1.34c0.097,0.459,0.319,0.897,0.676,1.254l6.718,6.718c0.979,0.977,2.561,0.977,3.535,0 c0.978-0.978,0.978-2.56,0-3.535l-6.718-6.72c-0.355-0.354-0.794-0.577-1.253-0.674C24.743,13.967,24.303,7.57,20.194,3.46z M18.073,18.074c-3.444,3.444-9.049,3.444-12.492,0c-3.442-3.444-3.442-9.048,0-12.492c3.443-3.443,9.048-3.443,12.492,0 C21.517,9.026,21.517,14.63,18.073,18.074z"/></g></svg>
                    </a>
                </span>
                <a v-if="this.show_filter==0" href="#" dusk="show" @click="showfilter()" class="absolute right-0 top-0 px-2 py-3">
                    <svg class="w-2 h-2 m-1 fill-current text-gray-700" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="292.362px" height="292.362px" viewBox="0 0 292.362 292.362" style="enable-background:new 0 0 292.362 292.362;" xml:space="preserve"><g><path d="M286.935,69.377c-3.614-3.617-7.898-5.424-12.848-5.424H18.274c-4.952,0-9.233,1.807-12.85,5.424 C1.807,72.998,0,77.279,0,82.228c0,4.948,1.807,9.229,5.424,12.847l127.907,127.907c3.621,3.617,7.902,5.428,12.85,5.428 s9.233-1.811,12.847-5.428L286.935,95.074c3.613-3.617,5.427-7.898,5.427-12.847C292.362,77.279,290.548,72.998,286.935,69.377z"/></g></svg>
                </a>
                <a v-if="this.show_filter==1" href="#" id="hide" @click="disablefilter()" class="absolute right-0 top-0 px-2 py-3">
                    <svg class="w-2 h-2 m-1 fill-current text-gray-700" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512.001 512.001" style="enable-background:new 0 0 512.001 512.001;" xml:space="preserve"><g><g><path d="M284.286,256.002L506.143,34.144c7.811-7.811,7.811-20.475,0-28.285c-7.811-7.81-20.475-7.811-28.285,0L256,227.717 L34.143,5.859c-7.811-7.811-20.475-7.811-28.285,0c-7.81,7.811-7.811,20.475,0,28.285l221.857,221.857L5.858,477.859 c-7.811,7.811-7.811,20.475,0,28.285c3.905,3.905,9.024,5.857,14.143,5.857c5.119,0,10.237-1.952,14.143-5.857L256,284.287 l221.857,221.857c3.905,3.905,9.024,5.857,14.143,5.857s10.237-1.952,14.143-5.857c7.811-7.811,7.811-20.475,0-28.285 L284.286,256.002z"/></g></g></svg>
                </a>
            </div>
        </Teleport>
        <Teleport to="#memberfilter">
            <div class="hide-menu" id="show-filter">
                <div class="absolute bg-white w-full lg:w-2/4 shadow-lg border border-r-0 z-40 h-auto py-3 lg:px-4 overflow-auto search_user_filter">  
                    <div id="search_filter">
                        <div>
                            <ul class="search_filter leading-loose">
                                <li id="filter-content-name" class="filter_container">
                                    <div class="advanced_search_option_container" id="name">
                                        <div class="person_address_container flex">
                                            <div class="flex flex-col lg:flex-row md:flex-row my-1 w-1/2 px-4 items-center">
                                                <div class="person_filter_name_title w-full lg:w-2/5 md:w-2/5">
                                                    <p class="text-gray-700">First Name</p>
                                                </div>
                                                <div class="person_filter_name_input w-full lg:w-3/5 md:w-3/5">
                                                    <input v-model="firstname" name="firstname" id="firstname" type="text" class="filter-form-control my-2 lg:my-0 md:my-0" placeholder="First Name">
                                                </div>
                                            </div>
                                            <div class="flex flex-col lg:flex-row md:flex-row my-1 w-1/2 px-4 items-center">
                                                <div class="person_filter_name_title w-full lg:w-2/5 md:w-2/5">
                                                    <p class="text-gray-700">Last Name</p>
                                                </div>
                                                <div class="person_filter_name_input w-full lg:w-3/5 md:w-3/5">
                                                    <input v-model="lastname" name="lastname" id="lastname" type="text" class="filter-form-control my-2 lg:my-0 md:my-0" placeholder="Last Name">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li id="filter-content-gender" class="filter_container flex">
                                    <div class="flex flex-col lg:flex-row md:flex-row items-center my-1 w-1/2 px-4">
                                        <div class="person_filter_name_title w-full lg:w-2/5 md:w-2/5">
                                            <p class="text-gray-700">Mobile No.</p>
                                        </div>
                                        <div class="advanced_search_option_container  my-1 w-full lg:w-3/5 md:w-3/5" >
                                            <input class="filter-form-control my-2 lg:my-0 md:my-0" v-model="mobile_no" id="mobile_no" name="mobile_no" type="text" placeholder="Mobile Number">
                                        </div>
                                    </div>
                                    <div class="flex flex-col lg:flex-row md:flex-row items-center my-1 w-1/2 px-4">
                                        <div class="person_filter_name_title w-full lg:w-2/5 md:w-2/5">
                                            <p class="text-gray-700">Email</p>
                                        </div>
                                        <div class="advanced_search_option_container my-1 w-full lg:w-3/5 md:w-2/5">
                                            <input class="filter-form-control my-2 lg:my-0 md:my-0" name="email" v-model="email" id="email" type="text" placeholder="Email">       
                                        </div>  
                                    </div> 
                                </li>

                                <li id="filter-content-age" class="filter_container hide flex">
                                    <div class="flex flex-col lg:flex-row md:flex-row items-center my-1 w-1/2 px-4">
                                        <div class="person_filter_name_title w-full lg:w-2/5 md:w-2/5">
                                            <p class="text-gray-700">Admisson Number</p>
                                        </div>
                                        <div class="advanced_search_option_container my-1 w-full lg:w-3/5 md:w-2/5">
                                            <input class="filter-form-control my-2 lg:my-0 md:my-0" name="admission_number" v-model="admission_number" id="admission_number" type="text" placeholder="Admission Number">       
                                        </div>  
                                    </div> 
                                
                                </li>

                                 <li id="filter-content-age" class="filter_container hide flex">
                                    <div class="flex flex-col lg:flex-row md:flex-row items-center my-1 w-1/2 px-4">
                                        <div class="person_filter_name_title w-full lg:w-2/5 md:w-2/5">
                                            <p class="text-gray-700">Status</p>
                                        </div>
                                        <div class="advanced_search_option_container my-1 w-full lg:w-3/5 md:w-2/5">
                                            <select name="status" v-model="status" id="status" class="filter-form-control" data-type="dropdown">
                                                <option value="" disabled="disabled">Status</option>
                                                <option value="active">Active</option>    
                                                <option value="inactive">Inactive</option>   
                                            </select>      
                                        </div>  
                                    </div> 
                                
                                </li>
                                <li id="filter-content-tag" class="filter_container hide flex">
                                    <div class="flex flex-col lg:flex-row md:flex-row items-center my-1 w-1/2 px-4">
                                        <div class="person_filter_name_title w-full lg:w-2/5 md:w-2/5">
                                            <p class="text-gray-700">Tag</p>
                                        </div>
                                        <div class="advanced_search_option_container my-1 w-full lg:w-3/5 md:w-2/5">
                                            <select name="tag" v-model="tag" id="tag" class="filter-form-control" data-type="dropdown">
                                                <option value="" disabled="disabled">Tag</option>
                                                <option v-for="tag in taglist" :key="tag.id" :value="tag.tag_name">
                                                    {{ tag.tag_name }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </li>

                                 <li id="filter-content-age" class="filter_container hide flex">
                                    <div class="flex flex-col lg:flex-row md:flex-row items-center my-1 w-1/2 px-4">
                                        <div class="person_filter_name_title w-full lg:w-2/5 md:w-2/5">
                                            <p class="text-gray-700">Gender</p>
                                        </div>
                                        <div class="advanced_search_option_container flex my-2 lg:my-1 md:my-1 w-full lg:w-3/5 md:w-3/5" id="gender">
                                            <select name="gender" v-model="gender" id="gender" class="filter-form-control" data-type="dropdown">
                                                <option value="" disabled="disabled">Gender</option>
                                                <option value="male">Boy</option>    
                                                <option value="female">Girl</option>   
                                            </select>     
                                        </div>
                                    </div>
                                    <div class="flex flex-col lg:flex-row md:flex-row items-center my-1 px-4 w-1/2">
                                        <div class="person_filter_name_title w-full  lg:w-2/5 md:w-2/5">
                                            <p class="text-gray-700">Blood Group</p>
                                        </div>
                                        <div class="advanced_search_option_container my-2 lg:my-1 md:my-1 flex w-full lg:w-3/5 md:w-3/5" id="blood_group"> 
                                            <select class="filter-form-control" id="blood_group" v-model="blood_group" name="blood_group" data-type="dropdown">
                                                <option value="" disabled="disabled">Blood Group</option>
                                                <option v-for="blood_group in blood_groups" v-bind:value="blood_group.num">{{ blood_group.name }}</option>
                                            </select>
                                        </div>      
                                    </div> 
                                </li>

                                <li id="filter-content-gender" class="filter_container hide flex">
                                    <div class="flex flex-col lg:flex-row md:flex-row items-center my-1 px-4 w-1/2">
                                        <div class="person_filter_name_title w-full  lg:w-2/5 md:w-2/5">
                                            <p class="text-gray-700">Transport</p>
                                        </div>
                                        <div class="advanced_search_option_container my-2 lg:my-1 md:my-1 flex w-full lg:w-3/5 md:w-3/5" id="transport"> 
                                            <select class="filter-form-control" id="transport" v-model="transport" name="transport" data-type="dropdown">
                                                <option value="" disabled="disabled">Transport</option>
                                                <option v-for="transports in transportlist" v-bind:value="transports.id">{{ transports.name }}</option>
                                            </select>
                                        </div>      
                                    </div> 
                                    <div class="flex flex-col lg:flex-row md:flex-row items-center my-1 px-4 w-1/2">
                                        <div class="person_filter_name_title w-full  lg:w-2/5 md:w-2/5">
                                            <p class="text-gray-700">Caste</p>
                                        </div>
                                        <div class="advanced_search_option_container my-2 lg:my-1 md:my-1 flex w-full lg:w-3/5 md:w-3/5" id="caste"> 
                                            <select class="filter-form-control" id="caste" v-model="caste" name="caste" data-type="dropdown">
                                                <option value="" disabled="disabled">Caste</option>
                                                <option v-for="castes in castelist" v-bind:value="castes.id">{{ castes.name }}</option>
                                            </select>
                                        </div>      
                                    </div>  
                                </li>

                                <li id="filter-content-gender" class="filter_container hide flex">
                                    <div class="flex flex-col lg:flex-row md:flex-row items-center my-1 px-4 w-1/2">
                                        <div class="person_filter_name_title w-full  lg:w-2/5 md:w-2/5">
                                            <p class="text-gray-700">Birthday</p>
                                        </div>
                                        <div class="advanced_search_option_container my-2 lg:my-1 md:my-1 flex w-full lg:w-3/5 md:w-3/5" id="date_of_birth"> 
                                            <select class="filter-form-control" id="date_of_birth" v-model="date_of_birth" name="date_of_birth" data-type="dropdown">
                                                <option value="" disabled="disabled">Month</option>
                                                <option v-for="month in months" v-bind:value="month.id">{{ month.name }}</option>
                                            </select>
                                        </div>      
                                    </div> 
                                    <div class="flex flex-col lg:flex-row md:flex-row items-center my-1 px-4 w-1/2" v-if="this.type == 'report'">
                                        <div class="person_filter_name_title w-full lg:w-2/5 md:w-2/5">
                                            <p class="text-gray-700">Class</p>
                                        </div>
                                        <div class="advanced_search_option_container my-2 lg:my-1 md:my-1 flex w-full lg:w-3/5 md:w-3/5" id="standard"> 
                                            <select name="standard" v-model="standard" id="standard" class="filter-form-control" data-type="dropdown">
                                                <option value="" disabled="disabled">Class</option>
                                                <option v-for="standard in standardLinklist" v-bind:value="standard.id">{{standard.standard_name}}-{{standard.section_name}}</option>
                                            </select>
                                        </div>      
                                    </div>  
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="tw-form-row my-4 flex justify-end">
                        <a href="#" dusk="reset-btn" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 ml-3 text-sm font-medium" @click="reset()">Reset</a>
                        <a href="#" dusk="search-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 ml-3 text-sm font-medium" @click="searchmember()">Search</a>
                    </div>
                </div>
            </div>
        </Teleport>
    </div>
</template>

<script>
    import { bus } from "../../app";
    export default {
        props:['url','searchquery','selected_standard','type'],
        data(){
            return{
                firstname:'',
                lastname:'',
                gender:'',
                date_of_birth:'',
                mobile_no:'',
                email:'',
                standard:'',
                standardLinklist:[],
                count:100,
                selectedLetter: undefined,
                active: false,
                errors:[],
                success:null,
                params:{},
                show_filter:0,
                blood_group:'' ,  
                transport:'', 
                caste:'',      
                standard:'',
                blood_groups:[],
                transportlist:[],  
                castelist:[], 
                months:[{id:'01' , name:'January'} , {id:'02' , name:'February'} , {id:'03' , name:'March'} , {id:'04' , name:'April'} , {id:'05' , name:'May'} , {id:'06' , name:'June'} , {id:'07' , name:'July'} , {id:'08' , name:'August'} , {id:'09' , name:'September'} , {id:'10' , name:'October'} , {id:'11' , name:'November'} , {id:'12' , name:'December'}],
                admission_number:'',
                status:'',
                tag: '',
                taglist: [],
            }
        },

        methods:
        {
            reset()
            {
                if(this.type == 'report')
                {
                    window.location.href=this.url+"/admin/reports";
                }
                else
                {
                    window.location.href=this.url+"/admin/students";
                }
            },

            enableDiv(id)
            {
                if($('#'+id).hasClass('hidden'))
                {
                    $('#'+id).removeClass('hidden').addClass('block');
                }
                else
                {
                    $('#'+id).removeClass('block').addClass('hidden');
                }
            },

            searchmember()
            {
                this.params = {
                    firstname:this.firstname,
                    lastname:this.lastname,
                    gender:this.gender,
                    date_of_birth:this.date_of_birth,
                    email:this.email,
                    mobile_no:this.mobile_no,
                    standard:this.selected_standard,
                    blood_group:this.blood_group,
                    transport:this.transport,
                    caste:this.caste,
                    admission_number:this.admission_number,
                    status:this.status,
                    tag:this.tag,
                };

                if(this.type == 'report')
                {
                    this.params = {
                        firstname:this.firstname,
                        lastname:this.lastname,
                        gender:this.gender,
                        date_of_birth:this.date_of_birth,
                        email:this.email,
                        mobile_no:this.mobile_no,
                        standard:this.selected_standard,
                        blood_group:this.blood_group,
                        transport:this.transport,
                        caste:this.caste,
                        standard:this.standard,
                        status:this.status,
                        tag:this.tag,
                    };
                }

                if(this.type == 'report')
                {
                    this.final=this.url+'/admin/reports?'+this.searchquery;
                }
                else
                {
                    this.final=this.url+'/admin/students?'+this.searchquery;
                }
          
                Object.keys(this.params).forEach(key => {
                    // this.final.searchParams.append(key, params[key])
                    this.final = this.addParam(this.final, key, this.params[key])
                });

                window.location.href=this.final;
            },

            addParam(url, param, value) 
            {
                param = encodeURIComponent(param);
                var r = "([&?]|&amp;)" + param + "\\b(?:=(?:[^&#]*))*";
                var a = document.createElement('a');
                var regex = new RegExp(r);
                var str = param + (value ? "=" + encodeURIComponent(value) : ""); 
                a.href = url;
                var q = a.search.replace(regex, "$1"+str);
                if (q === a.search) 
                {
                    a.search += (a.search ? "&" : "") + str;
                } 
                else 
                {
                    a.search = q;
                }
                return a.href ;
            },

            showfilter()
            {   
                this.show_filter =1;  
                $('#show-filter').removeClass('hide-menu').addClass('block');
                bus.emit("datashowFilter", '1');
            },

            disablefilter()
            {
                this.show_filter =0;  
                $('#show-filter').removeClass('block').addClass('hide-menu');
            },

            getData()
            {
                axios.get('/admin/student').then(response => {
                    this.user = response.data;
                    this.setData();   
                });
            },

            setData()
            {
                if(Object.keys(this.user).length>0)
                {
                    this.standardLinklist = this.user.standardLinklist;
                    this.transportlist    = this.user.transportlist;
                    this.blood_groups     = this.user.blood_groups;
                    this.castelist        = this.user.castelist;
                    //console.log(this.standardLinklist)
                }
            },
            getTags()
            {
                axios.get(this.url + '/admin/student-tags')
                    .then(response => {
                        this.taglist = response.data.tags;
                    });
            },
        },

        created()
        {
            bus.on("dataMemberName", data => {
                if(data!='')
                {
                     this.disablefilter();
                }
            });
            this.getData();
             this.getTags();
        },
    }
</script>