<template>
    <div class="relative">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
        
        <Teleport to="#add_media_file">
            <div class="flex flex-wrap lg:flex-row justify-between">
                <div class="">
                    <h1 class="admin-h1 my-3">Files ( {{ this.mediafiles.length }} )</h1>
                </div>
                <div class="relative flex items-center w-8/4 lg:w-1/2 md:w-1/3 justify-end">
                    <div class="flex items-center w-full justify-end">
                    <div class="w-full lg:w-1/3 md:w-1/3">
                        <select name="standardLink_id" v-model="standardLink_id" class="tw-form-control w-full mx-2" v-on:change="searchList()">
                            <option value="">Select Class</option>
                            <option v-for="list in standardLinklist" v-bind:value="list.id">{{ list.standard_section }}</option>
                        </select>
                    </div>
                        <div class="">
                            <div class="flex items-center mx-2">
                                <div class="search relative mx-2">
                                    <input type="text" name="search" v-model="search" class="border px-10 py-1 text-sm border-gray-400 w-full rounded bg-white shadow" placeholder="Search">  
                                    <span class="input-group-btn absolute left-0 px-3 py-2 top-0">                  
                                        <a href="#" @click="searchList()">
                                            <svg class="w-4 h-4 fill-current text-gray-600" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30.239px" height="30.239px" viewBox="0 0 30.239 30.239" style="enable-background:new 0 0 30.239 30.239;" xml:space="preserve"><g><path d="M20.194,3.46c-4.613-4.613-12.121-4.613-16.734,0c-4.612,4.614-4.612,12.121,0,16.735 c4.108,4.107,10.506,4.547,15.116,1.34c0.097,0.459,0.319,0.897,0.676,1.254l6.718,6.718c0.979,0.977,2.561,0.977,3.535,0 c0.978-0.978,0.978-2.56,0-3.535l-6.718-6.72c-0.355-0.354-0.794-0.577-1.253-0.674C24.743,13.967,24.303,7.57,20.194,3.46z M18.073,18.074c-3.444,3.444-9.049,3.444-12.492,0c-3.442-3.444-3.442-9.048,0-12.492c3.443-3.443,9.048-3.443,12.492,0 C21.517,9.026,21.517,14.63,18.073,18.074z"/></g></svg>
                                        </a>
                                    </span>
                                </div>
                                <div class="date-select date-select_none dashboard-reset mx-1 lg:mx-0 md:mx-0">
                                    <a href="#" @click="resetForm()" id="do-reset" class="text-sm border bg-gray-100 text-grey-darkest py-1 px-4">Reset</a>
                                </div>
                            </div>
                        </div>
                        <a :href="url+'/admin/file/add'" class="no-underline text-white px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center">
                            <span class="mx-1 text-sm font-semibold">Add</span>
                            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 409.6 409.6" xml:space="preserve" class="w-3 h-3 fill-current text-white"><g><g><path d="M392.533,187.733H221.867V17.067C221.867,7.641,214.226,0,204.8,0s-17.067,7.641-17.067,17.067v170.667H17.067 C7.641,187.733,0,195.374,0,204.8s7.641,17.067,17.067,17.067h170.667v170.667c0,9.426,7.641,17.067,17.067,17.067 s17.067-7.641,17.067-17.067V221.867h170.667c9.426,0,17.067-7.641,17.067-17.067S401.959,187.733,392.533,187.733z"></path></g></g></svg>
                        </a> 
                    </div>
                </div>
            </div>
        </Teleport>
        <div class="flex flex-wrap">
            <div class="w-full md:w-1/2 lg:w-1/4 px-1 my-2" v-for="mediafile in mediafiles">
                <div class="w-full py-2">
                    <div class="shadow-md p-3">
                        <div class="flex justify-between">
                             <img v-if="mediafile.type!='video' || mediafile.media_type=='url'" class="card-img-top w-16 h-16" :src="mediafile.thumb_file">
                              <video v-if="mediafile.type=='video' && mediafile.media_type!='url'" class="card-img-top w-16 h-16">
                               <source :src="mediafile.url" type="video/mp4">
                               Your browser does not support the video tag.
                              </video>

                            <div class="w-11/12 flex justify-between">
                                <div class="px-3">    
                                    <p class="font-bold text-base text-gray-700 capitalize">{{ mediafile.name }}</p>    
                                    <p class="font-medium text-xs text-gray-600 capitalize flex items-center py-1">{{ mediafile.description }}</p>
                                    <a :href="url+'/admin/file/viewers/'+mediafile.id"><p class="font-medium text-xs text-gray-600 capitalize flex items-center py-1">{{ mediafile.viewers }} Views</p> </a>     
                                    <p class="font-medium text-xs text-gray-600 capitalize flex items-center py-1">{{ mediafile.media }}</p>    
                                    <p class="font-medium text-xs text-gray-600 capitalize flex items-center py-1" v-if="mediafile.standard != null">{{ mediafile.standard }}</p>

                                    <p class="font-medium text-xs text-gray-500 capitalize flex items-center py-1">
                                        <a :href="mediafile.url" target="_blank" v-if="mediafile.type == 'audio'">Listen</a> 
                                        <a :href="mediafile.url" target="_blank" v-else>View</a> 
                                    </p>    
                                </div>
                                <div class="flex items-right">
                                    <a href="#" @click="viewFile(mediafile.id)" title="View" class="left-auto mx-1">
                                       <svg height="512pt" viewBox="-27 0 512 512" width="512pt" xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 fill-current text-black-500 mx-0"><path d="m188 492c0 11.046875-8.953125 20-20 20h-88c-44.113281 0-80-35.886719-80-80v-352c0-44.113281 35.886719-80 80-80h245.890625c44.109375 0 80 35.886719 80 80v191c0 11.046875-8.957031 20-20 20-11.046875 0-20-8.953125-20-20v-191c0-22.054688-17.945313-40-40-40h-245.890625c-22.054688 0-40 17.945312-40 40v352c0 22.054688 17.945312 40 40 40h88c11.046875 0 20 8.953125 20 20zm117.890625-372h-206c-11.046875 0-20 8.953125-20 20s8.953125 20 20 20h206c11.042969 0 20-8.953125 20-20s-8.957031-20-20-20zm20 100c0-11.046875-8.957031-20-20-20h-206c-11.046875 0-20 8.953125-20 20s8.953125 20 20 20h206c11.042969 0 20-8.953125 20-20zm-226 60c-11.046875 0-20 8.953125-20 20s8.953125 20 20 20h105.109375c11.046875 0 20-8.953125 20-20s-8.953125-20-20-20zm355.472656 146.496094c-.703125 1.003906-3.113281 4.414062-4.609375 6.300781-6.699218 8.425781-22.378906 28.148437-44.195312 45.558594-27.972656 22.324219-56.757813 33.644531-85.558594 33.644531s-57.585938-11.320312-85.558594-33.644531c-21.816406-17.410157-37.496094-37.136719-44.191406-45.558594-1.5-1.886719-3.910156-5.300781-4.613281-6.300781-4.847657-6.898438-4.847657-16.097656 0-22.996094.703125-1 3.113281-4.414062 4.613281-6.300781 6.695312-8.421875 22.375-28.144531 44.191406-45.554688 27.972656-22.324219 56.757813-33.644531 85.558594-33.644531s57.585938 11.320312 85.558594 33.644531c21.816406 17.410157 37.496094 37.136719 44.191406 45.558594 1.5 1.886719 3.910156 5.300781 4.613281 6.300781 4.847657 6.898438 4.847657 16.09375 0 22.992188zm-41.71875-11.496094c-31.800781-37.832031-62.9375-57-92.644531-57-29.703125 0-60.84375 19.164062-92.644531 57 31.800781 37.832031 62.9375 57 92.644531 57s60.84375-19.164062 92.644531-57zm-91.644531-38c-20.988281 0-38 17.011719-38 38s17.011719 38 38 38 38-17.011719 38-38-17.011719-38-38-38zm0 0"></path></svg>
                                    </a>

                                    <a :href="url+'/admin/file/edit/'+mediafile.id" class="mx-1" title="Edit">
                                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 477.873 477.873" xml:space="preserve" class="w-3 h-3 fill-current text-black"><g><g><path d="M392.533,238.937c-9.426,0-17.067,7.641-17.067,17.067V426.67c0,9.426-7.641,17.067-17.067,17.067H51.2 c-9.426,0-17.067-7.641-17.067-17.067V85.337c0-9.426,7.641-17.067,17.067-17.067H256c9.426,0,17.067-7.641,17.067-17.067 S265.426,34.137,256,34.137H51.2C22.923,34.137,0,57.06,0,85.337V426.67c0,28.277,22.923,51.2,51.2,51.2h307.2 c28.277,0,51.2-22.923,51.2-51.2V256.003C409.6,246.578,401.959,238.937,392.533,238.937z"></path></g></g> <g><g><path d="M458.742,19.142c-12.254-12.256-28.875-19.14-46.206-19.138c-17.341-0.05-33.979,6.846-46.199,19.149L141.534,243.937 c-1.865,1.879-3.272,4.163-4.113,6.673l-34.133,102.4c-2.979,8.943,1.856,18.607,10.799,21.585 c1.735,0.578,3.552,0.873,5.38,0.875c1.832-0.003,3.653-0.297,5.393-0.87l102.4-34.133c2.515-0.84,4.8-2.254,6.673-4.13 l224.802-224.802C484.25,86.023,484.253,44.657,458.742,19.142z M434.603,87.419L212.736,309.286l-66.287,22.135l22.067-66.202 L390.468,43.353c12.202-12.178,31.967-12.158,44.145,0.044c5.817,5.829,9.095,13.72,9.12,21.955 C443.754,73.631,440.467,81.575,434.603,87.419z"></path></g></g></svg>
                                    </a>

                                    <a href="#" @click="deleteFile(mediafile.id)" class="left-auto delete-video" title="Delete">
                                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-3 h-3 fill-current text-black"><g><g><g><polygon points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804"></polygon><rect x="235.948" y="175.791" width="40.104" height="237.285"></rect><polygon points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804"></polygon><path d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74 c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474 L380.665,471.896z"></path></g></g></g> <g><g><path d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42 C354.924,14.992,339.932,0,321.504,0z"></path></g></g></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="show == mediafile.id" class="modal modal-mask">
                    <div class="modal-wrapper px-4">
                        <div class="modal-container w-full max-w-md px-4 mx-auto">
                            <div class="modal-header flex justify-between items-center">
                                <h2>View File</h2>
                                <button id="close-button" class="modal-default-button text-2xl py-1" @click="closeModal()">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="flex flex-row">
                                    <div class="w-full lg:w-1/3">
                                        <label for="name" class="tw-form-label">Name</label>
                                    </div>
                                    <div class="w-full">
                                        <p class="text-xs text-gray-700 flex flex-col">{{ file.name }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <div class="flex flex-row">
                                    <div class="w-full lg:w-1/3">
                                        <label for="description" class="tw-form-label">Description</label>
                                    </div>
                                    <div class="w-full">
                                        <p class="text-xs text-gray-700 flex flex-col">{{ file.description }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <div class="flex flex-row">
                                    <div class="w-full lg:w-1/3">
                                        <label for="url" class="tw-form-label">Media Category</label>
                                    </div>
                                    <div class="w-full">
                                        <p class="text-xs text-gray-700 flex flex-col">{{ file.media }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body" v-if="file.standard != null">
                                <div class="flex flex-row">
                                    <div class="w-full lg:w-1/3">
                                        <label for="url" class="tw-form-label">Class</label>
                                    </div>
                                    <div class="w-full">
                                        <p class="text-xs text-gray-700 flex flex-col">{{ file.standard }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-body">
                                <div class="flex flex-row">
                                    <div class="w-full lg:w-1/3">
                                        <label for="url" class="tw-form-label">File</label>
                                    </div>
                                    <div class="w-full">
                                        <p class="text-xs text-gray-700 flex flex-col">
                                            <a :href="file.url" target="_blank" v-if="file.type == 'audio'">Listen</a>
                                            <a :href="file.url" target="_blank" v-else>View</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <p class="font-medium text-sm text-gray-600 capitalize flex items-center py-2" v-if="Object.keys(mediafiles).length == 0">No records found</p>
        </div>
    </div>
</template>

<script>
    import { bus } from "../../app";
    export default {
        props:['url'],
        data () {
            return {
                mediafiles:[],
                file:[],
                type:'audio',
                show:false,
                search:'',
                standardLink_id:'',
                standardLinklist:[],
                errors:[],
                success:null,
            }
        },

        methods:
        {
            getData()
            {
                axios.get('/admin/file/list/'+this.type+'?search='+this.search+'&standardLink_id='+this.standardLink_id).then(response => {
                    this.mediafiles = response.data.data;
                    //console.log(this.mediafiles);    
                });
                axios.get('/admin/homework/list').then(response => {
                    this.standardLinklist = response.data.standardlist;
                });
            },

            viewFile(id)
            {
                this.show = id;
                axios.get('/admin/file/show/'+id).then(response => {
                    this.file = response.data;
                    //console.log(this.file);
                });
            },

            closeModal()
            {
                this.show = false;
            },

            searchList()
            {
                this.getData();
            },

            resetForm()
            {
                this.search = '';
                this.scope = '';
                this.getData();
            },

            deleteFile(id) 
            {
                var thisswal = this;
                swal({
                    title: 'Are you sure',
                    text: 'Do you want to delete this media file ?',
                    icon: "info",
                    buttons: [
                        'No',
                        'Yes'
                    ],
                    dangerMode: true,
                }).then(function(isConfirm) {
                    if (isConfirm) 
                    {
                        axios.get(thisswal.url+ '/admin/file/delete/'+ id).then(response => {
                            thisswal.success = response.data.success;
                            window.location.reload();
                        });
                    }
                    else 
                    {
                        swal("Cancelled");
                    }
                });
            }
        },
  
        created()
        {   
            this.getData(); 
            bus.$on("typeTab", data => {
                if(data!='')
                {
                    this.type=data;      
                    this.getData();             
                }
            });
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

    .modal-container-new {
        margin: 0px auto;
        /*padding: 20px 30px;*/
        background-color: #fff;
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
        transition: all .3s ease;
        height: 500px;
        overflow:auto;
    }

    .modal-container {
        margin: 0px auto;
        /*padding: 20px 30px;*/
        background-color: #fff;
        border-radius: 2px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
        transition: all .3s ease;
        /*height: 500px;*/
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
</style>