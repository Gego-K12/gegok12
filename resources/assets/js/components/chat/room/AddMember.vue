<template>
    <div class="bg-white shadow border px-4 py-4">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{ this.success }}</div>

        <div class="w-full lg:w-1/3 md:w-1/2">
            <div class="tw-form-group w-full lg:w-3/4 md:w-5/6">
                <div class="">
                    <div class="mb-2">
                        <label for="type" class="tw-form-label">Type<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <select class="tw-form-control w-full" id="type" v-model="type" name="type" v-on:change="getMember()">
                            <option value="" disabled>Select Type</option>
                            <option value="teacher">Teacher</option>
                            <option value="student">Student</option>
               
                        </select>
                        <span v-if="errors.type" class="text-red-500 text-xs font-semibold">{{ errors.type[0] }}</span>
                    </div>
                </div>     
            </div>
        </div>

        <div class="w-full lg:w-1/3 md:w-1/2" v-if="this.type=='student'">
            <div class="tw-form-group w-full lg:w-3/4 md:w-5/6">
                <div class="">
                    <div class="mb-2">
                        <label for="standardLink_id" class="tw-form-label">Class<span class="text-red-500">*</span></label>
                    </div>
                    <div class="mb-2">
                        <select name="standardLink_id" id="standardLink_id" v-model="standardLink_id" v-on:change="getstudent()" class="tw-form-control w-full">
                            <option value="" disabled>Select class</option>
                            <option v-for="list in classlists" v-bind:value="list.id">{{ list.standard_section }}</option>
                        </select>
                        <span v-if="errors.standardLink_id" class="text-red-500 text-xs font-semibold">{{ errors.standardLink_id[0] }}</span>
                    </div>
                </div> 
            </div>
        </div>

        <div class="w-full lg:w-1/2 md:w-3/4">
            <div class="mb-2">
                <label class="tw-form-label">Select User<span class="text-red-500">*</span></label>
            </div>
            <div class="w-full lg:w-2/4 md:w-2/4">
                <multiselect v-model="user_id" id="ajax" label="name" track-by="name" placeholder="Type to search" open-direction="bottom" :options="users" :custom-label="customLabel" :show-labels="true" :multiple="true" :searchable="true" :loading="isLoading" :internal-search="true" :clear-on-select="false" :close-on-select="false" :options-limit="200" :limit="5" :limit-text="limitText" :max-height="200" :show-no-results="true" :hide-selected="true" @search-change="asyncFind">

                    <template slot="tag" slot-scope="{ option, remove }">
                        <span class="custom__tag">
                            <span>{{ (option.fullname) }}</span>
                            <span class="custom__remove" @click="remove(option)">❌</span>
                        </span>
                    </template>

                    <template slot="clear" slot-scope="props">
                        <div class="multiselect__clear" v-if="user_id.length" @mousedown.prevent.stop="clearAll(props.search)"></div>
                    </template>
                    
                    <template slot="option" slot-scope="props">
                        <img class="option__image w-10 h-10" :src="props.option.avatar">
                        <div class="option__desc">
                            <span class="option__name">{{ props.option.fullname }}</span>
                        </div>
                    </template>

                    <span slot="noResult">Oops! No users found.</span>
                </multiselect>
            </div>

            <div class="my-6">
                <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm()">Submit</a>
                <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="resetForm()">Reset</a>
            </div>
        </div>
    </div>
</template>

<script>
    import Multiselect from 'vue-multiselect'
    // register globally
    Vue.component('multiselect', Multiselect) 
    export default {
        props:['url' , 'school_id' , 'room_id'],
        components: {
            Multiselect
        },
        data () {
            return {
                list:[],
                users:[],
                classlists:[],
                type:'',
                standardLink_id:'',
                user_id:[],
                isLoading: false,
                errors:[],
                success:null,
            }
        },

        methods: 
        {    
            getMember(query)
            {
                axios.get(this.url+'/admin/room/list/'+this.room_id+'?q='+query).then(response => {
                    this.list  = response.data;
                    this.setTeacher(query,this.type);
                });
            },

            setTeacher(query,type)
            {
                if(Object.keys(this.list).length>0)
                {
                    this.classlists = this.list.standardlist;
                    if(type == 'student')
                    {
                        this.getstudent(query);
                    }
                    else if(type == 'teacher')
                    {
                        this.users = this.list.teacherlist;
                        this.isLoading = false;
                    }
                }
            },

            getstudent(query)
            { 
                axios.get(this.url+'/admin/room/list/'+this.room_id+'?q='+query+'&standardLink_id='+this.standardLink_id).then(response=>{
                    this.users = response.data.studentlist;
                    this.isLoading = false;
                });
            },

            limitText (count) 
            {
                return `and ${count} other users`
            },

            customLabel ({ fullname, label }) 
            {
                return `${fullname} – ${label}`
            },

            clearAll () 
            {
                this.selectedUsers = []
            },

            asyncFind (query) 
            {
                this.isLoading = true;
                this.setTeacher(query,this.type);
            },

            resetForm()
            {
                window.location.reload();
            },

            submitForm()
            {
                this.errors=[];
                this.success=null;                 
                  
                axios.post('/admin/room/addMember/'+this.room_id,{
                    school_id: this.school_id,
                    room_id: this.room_id,
                    user_id:this.user_id,
                }).then(response => {   
                    this.success = response.data.success;
                    this.resetForm();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },
        },

        created()
        {
            //
        }
    }
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>