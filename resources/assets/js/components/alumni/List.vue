<template>
    <div>
        <Teleport to="#admin_alumni_count">
            <div class="">
                <h1 class="admin-h1 my-3">Alumni ( {{ Object.keys(this.users).length }} )</h1>
            </div>
        </Teleport>
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
        <div class="my-4 filter-alphabet">
            <ul class="list-reset flex flex-wrap">
                <li v-for="alphabet in alphabets">
                    <a href="#" id="filter" class="block font-bold p-2 bg-grey-light border border-grey mx-2 ni" v-bind:class="letter === alphabet?'active':'text-blue'" v-text="alphabet"  @click="sortMembers(alphabet)"> </a>   
                </li>
                <li>
                    <a href="#" class="block font-bold p-2 bg-grey-light border border-grey mx-2 ni" @click="clearAll()">Clear All</a>   
                </li>
            </ul>
            <div class="my-4" v-if="!filteredNames.length">No names for this letter</div>
            <div class="" v-if="filteredNames.length"></div>
        </div>
        <div>
            <alumnidetails :url="this.url"></alumnidetails>
            <div id="alumnidetail"></div>
            <div class="my-8">
                <div class="w-full flex flex-wrap items-center justify-between mb-4">
                    <div class="flex flex-wrap items-center text-sm">
                        <div class="px-3 border-r" v-if="this.selectedUsersCount > 0">
                            {{ parseInt(this.selectedUsersCount) }} alumni selected
                        </div>
                        <div class="px-3 border-r relative">
                            <input class="opacity-0 absolute w-full h-full cursor-pointer" type="checkbox" @click="selectAll($event)" v-model="allSelected"><span>Select All</span>
                        </div>
                        <div class="px-3 relative" v-if="this.selectedUsersCount > 0">
                            <input class="opacity-0 absolute w-full h-full cursor-pointer" type="checkbox" @click="selectNone($event)" v-model="noneSelected"><span>Select None</span>
                        </div>
                    </div> 
                    <div class="relative flex items-center w-full lg:w-1/4 md:w-1/4 lg:justify-end mx-3 lg:mx-0 md:mx-0 my-2 lg:my-0 md:my-0" v-if="this.selectedUsersCount > 0">
                        <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 text-sm font-medium" @click="sendMessage()">Send Message</a>
                    </div>
                </div>

                <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-4 flex flex-wrap">
                    <template class="" v-for="user in users">
                        <div class="w-full lg:w-full md:w-full my-2 relative p-2">
                            <div class="person-card bg-white border rounded flex justify-between relative">
                                <div class="flex-grow w-full flex p-2 cursor-pointer hover:shadow" :id="user['id']" @click="enableform(user['name'])">
                                    <img :src="user['photo']" class="w-16 h-16">
                                    <div class="flex-grow px-2">
                                        <h2 class="font-bold text-base text-gray-700">{{ user['name'] }}</h2>
                                        <p class="text-sm">{{ user['passing_session'] }}</p>
                                    </div>
                                </div>
                                <div class="student_select">
                                    <input class="absolute w-5 h-5" type="checkbox" v-model="selected" :value="user['id']" @click="selectedCount(user['id'],$event)" >
                                    <label></label>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>

        <div v-if="this.send == 1" class="modal modal-mask">
            <div class="modal-wrapper px-4">
                <div class="modal-container w-full  max-w-md px-8 mx-auto">
                    <div class="modal-header flex justify-between items-center">
                        <h2>Send Message</h2>
                        <button id="close-button" class="modal-default-button text-2xl py-1"  @click="closeModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="flex flex-col lg:flex-row md:flex-row  lg:items-center">
                            <div class="w-full lg:w-1/4"> 
                                <label for="subject" class="tw-form-label">Subject</label>
                            </div>
                            <div class="my-2 w-full lg:w-3/4">
                                <input type="text" name="subject" v-model="subject" class="tw-form-control w-full">
                                <span v-if="errors.subject" class="text-red-500 text-xs font-semibold">{{errors.subject[0]}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="flex flex-col lg:flex-row md:flex-row lg:items-center">
                            <div class="w-full lg:w-1/4">
                                <label for="message" class="tw-form-label">Message</label>
                            </div>
                            <div class="w-full lg:w-3/4">
                                <textarea type="text" name="message" v-model="message" class="tw-form-control w-full" rows="10"></textarea>
                                <span v-if="errors.message" class="text-red-500 text-xs font-semibold">{{errors.message[0]}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="flex items-center">
                            <div class="w-6">
                                <input type="checkbox" name="send_later" v-model="send_later" class="tw-form-control w-full" @click="enableDate($event)">
                            </div>
                            <div class="mx-1"> 
                                <label for="subject" class="tw-form-label">Send Later</label>
                            </div>            
                        </div>
                    </div>
                    <div class="modal-body hidden" id="show_date">
                        <div class="flex">
                            <div class="w-full lg:w-1/4">
                                <label for="executed_at" class="tw-form-label">Date Time</label>
                            </div>
                            <div class="w-full lg:w-3/4">
                                <VueDatePicker
                                  v-model="executed_at"
                                  format="dd-MM-yyyy HH:mm:ss"
                                  model-type="format"
                                  :enable-time-picker="true"
                                  :is-24="true"
                                  :auto-apply="true"
                                  input-class-name="w-full rounded"
                                />
                                <span v-if="errors.executed_at" class="text-red-500 text-xs font-semibold">{{errors.executed_at[0]}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="my-6">
                        <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submit()">Send</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { bus } from "../../app";
    import alumnidetails from './Detail';
    import { VueDatePicker } from '@vuepic/vue-datepicker'
    import '@vuepic/vue-datepicker/dist/main.css'
    export default {
        props:['url','searchquery'],
        data(){
            return{
                users:[],
                user:'',
                alphabets: ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'],
                selectedLetter: undefined,
                letter:'',
                active: false,
                selected: [],
                selectedUsers:[],
                selectedUsersCount:0,
                send_later:'',
                allSelected: false,
                noneSelected:false,
                subject:'',
                 message:'',
                executed_at:'',
                send:0,
                errors:[],
                success:null,
            }
        },

        created() 
        {
            axios.get('/admin/alumni/find?'+this.searchquery).then(response => {
                this.users = response.data.data;
                //console.log(this.users);
            });
            this.getUrl();
            this.letter = this.searchquery.slice(-1)[0];
        },

        computed: 
        {
            filteredNames () 
            {
                let users = this.users
                //console.log(users);
                if (this.selectedLetter)
                {
                    // users are objects, not name strings -- calling charAt
                    // on the object throws and freezes the component render.
                    users = users.filter((user) => {
                        let name = typeof user === 'string' ? user : (user.firstname || user.name || '')
                        return name.charAt(0).toUpperCase() === this.selectedLetter
                    })
                }
                return users
            }
        },

        components:
        {
            alumnidetails,
            VueDatePicker,
        },

        methods:
        {
            clearAll()
            {
                window.location.href = '/admin/alumni';
            },
      
            enableform(val)
            {
                this.success=null;
                $('#show-detail').removeClass('hide-menu').addClass('block');
                bus.$emit("dataAlumniName", val);
            },
        
            sortMembers(name)
            {
                this.selectedLetter= name; 
                this.active = true; 
                var q='alphabet='+this.selectedLetter;
                //var url = window.location.href; 
                var url = this.currenturl;  

                if (window.location.search.indexOf('alphabet=') > -1) 
                {
                    var href = new URL(url); 
                    href.searchParams.set('alphabet', this.selectedLetter);
                    url=href.toString();       
                } 
                else 
                {
                    if (url.indexOf('?') > -1)
                    {
                        url += '&'
                    }     
                    else
                    {
                        url += '?'
                    }
                    url += q;
                }

                window.location.href = url;
            },

            getUrl()
            {
                this.currenturl =  this.url+"/admin/alumni/"; 
                if(this.searchquery!='')
                {
                    this.currenturl =  this.currenturl+'?'+this.searchquery;
                }
            },

            selectAll(e) 
            { 
                var selected = [];
                if (e.target.checked) 
                {
                    $('.member-list').addClass('student_selected');
                    if(this.allSelected == false) 
                    {
                        this.users.forEach(function (user) 
                        {
                            selected.push(user.id);
                        });
                        this.selected = selected;
                        this.selectedUsersCount = selected.length;
                        this.allSelected = true;
                    } 
                }
                else
                {
                    this.users.forEach(function (user) 
                    {
                        selected.splice(user.id);
                    });
                    this.selected = selected;
                    this.selectedUsersCount = selected.length;
                    this.noneSelected = false;
                    $('.member-list').removeClass('student_selected');
                }
            },

            selectNone(e) 
            {      
                var selected = [];
                if (e.target.checked) 
                {
                    $('.member-list').removeClass('student_selected');
                    this.users.forEach(function (user) 
                    {
                        selected.splice(user.id);
                    });
                    this.selected = selected;
                    this.selectedUsersCount = selected.length;
                    this.noneSelected = false;
                }
            },
        
            sendMessage()
            {
                if(this.selectedUsersCount > 0)
                {
                    this.send = 1;
                }
                else
                {
                    alert("Select Users")
                }
            },

            submit()
            {
                this.errors=[];
                axios.post('/admin/teacher/sendMessageToAll',{
                    selected:this.selected, 
                    subject:this.subject,
                    message:this.message, 
                    send_later:this.send_later,
                    executed_at:this.executed_at,
                }).then(response => {
                    this.success = response.data.message;
                    this.send=0;
                    window.location.reload();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            closeModal()
            {
                this.send = 0;
            },

            enableDate(e)
            {
                if (e.target.checked) 
                {
                    this.send_later = 1;
                    if($('#show_date').hasClass('hidden'))
                    {
                        $('#show_date').removeClass('hidden').addClass('block');
                    }
                }
                else
                {
                    this.send_later = 0;
                    if($('#show_date').hasClass('block'))
                    {
                        $('#show_date').removeClass('block').addClass('hidden');
                    }
                }
            },

            selectedCount(id,e) 
            { 
                if (e.target.checked) 
                {
                    this.selectedUsersCount++;
                    $('#'+id).addClass('student_selected');
                }
                else
                {
                    this.selectedUsersCount--;
                    $('#'+id).removeClass('student_selected');
                }
            },
        }
    }
</script>

<style scoped>
    .modal-mask {
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, .5);
        display: table;
        transition: opacity .3s ease;
    }

    .modal-wrapper {
        display: table-cell;
        vertical-align: middle;
        overflow:auto;
    }

    .modal-container {
        margin: 0px auto;
        padding: 20px 30px;
        background-color: #fff;
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
        transition: all .3s ease;
        /*  height: 550px;*/
        overflow:auto;
    }

    .modal-header h3 {
        margin-top: 0;
        color: #42b983;
    }

    .modal-body {
        margin: 20px 0;
    }

    .modal-default-button {
        float: right;
    }

    /*
     * The following styles are auto-applied to elements with
     * transition="modal" when their visibility is toggled
     * by Vue.js.
     *
     * You can easily play with the modal transition by editing
     * these styles.
    */

    .modal-enter {
        opacity: 0;
    }

    .modal-leave-active {
        opacity: 0;
    }

    .modal-enter .modal-container,
    .modal-leave-active .modal-container {
        -webkit-transform: scale(1.1);
        transform: scale(1.1);
    }

    .text-danger
    {
        color:red;
    }
</style>