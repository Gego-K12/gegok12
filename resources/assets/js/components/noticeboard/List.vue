<template>
    <div class="relative">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
        <Teleport to="#add_notice">
            <div class="flex flex-wrap lg:flex-row justify-between items-center">
                <div class="">
                    <h1 class="admin-h1 my-3">Notice Board</h1>
                </div>
                <div class="relative flex items-center w-full lg:w-1/2 md:w-1/4 justify-end">
                    <div class="flex items-center w-full justify-end">
                        <select v-if="mode !== 'student'" name="standardLink_id" v-model="standardLink_id" class="tw-form-control mx-2" v-on:change="selectClass()">
                            <option value="">Select Class</option>
                            <option v-for="list in standardLinklist" v-bind:value="list.id">{{ list.standard_section }}</option>
                        </select>
                        <div class="">
                            <div class="flex items-center mx-2">
                                <div class="search relative mx-2">
                                    <input type="text" name="search" v-model="search" class="border px-10 py-1 text-sm border-gray-400 rounded bg-white shadow" placeholder="Search">  
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
                        <div class="flex items-center mx-3">
                            <div class="w-8">
                                <input type="checkbox" name="showExpired" id="showExpired" v-model="showExpired" class="tw-form-control w-full" @change="showExpiredNotice($event)">
                            </div>
                            <div class="2">
                                <label for="showExpired" class="tw-form-label whitespace-no-wrap">Show Expired</label>
                            </div>
                        </div>
                        <div class="flex items-center" v-if="hidecolumns == 'false'">
                            <a :href="url+'/admin/notice/add'" class="no-underline text-white px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center">
                                <span class="mx-1 text-sm font-semibold">Add</span>
                                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 409.6 409.6" xml:space="preserve" class="w-3 h-3 fill-current text-white"><g><g><path d="M392.533,187.733H221.867V17.067C221.867,7.641,214.226,0,204.8,0s-17.067,7.641-17.067,17.067v170.667H17.067 C7.641,187.733,0,195.374,0,204.8s7.641,17.067,17.067,17.067h170.667v170.667c0,9.426,7.641,17.067,17.067,17.067 s17.067-7.641,17.067-17.067V221.867h170.667c9.426,0,17.067-7.641,17.067-17.067S401.959,187.733,392.533,187.733z"></path></g></g></svg>
                            </a> 
                        </div>
                    </div>
                </div>
            </div>
        </Teleport>
        <div class="">
            <div class="flex flex-wrap custom-table my-3 overflow-auto">
                <table class="w-full">
                    <thead class="bg-grey-light">
                        <tr class="border-b">
                            <th class="text-left text-sm px-2 py-2 text-grey-darker" v-if="hidecolumns == 'false'"> Type </th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker" v-if="hidecolumns == 'false'"> Class </th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker"> Title </th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker" width="40%"> Descripiton </th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker"> Publish Date </th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker"> Expire Date </th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker"> Attachment </th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker" v-if="hidecolumns == 'false'"> Actions </th>
                        </tr>
                    </thead>   
                    <tbody v-if="this.notices != ''">
                        <tr class="border-b" v-for="notice in notices">
                            <td class="py-3 px-2" v-if="hidecolumns == 'false'">
                                <p class="font-semibold text-xs">{{ notice.type }}</p>
                            </td>
                            <td class="py-3 px-2" v-if="hidecolumns == 'false'">
                                <p class="font-semibold text-xs">{{ notice.class_name }}</p>
                            </td>
                            <td class="py-3 px-2">
                                <p class="font-semibold text-xs">{{ notice.title }}</p>
                            </td>
                            <td class="py-3 px-2">
                                <div class="font-semibold text-xs" v-html="trim(notice.description)"></div>
                               <!--  <p class="font-semibold text-xs">{{ trim(notice.description) }}</p> -->
                            </td>
                            <td class="py-3 px-2">
                                <p class="font-semibold text-xs">{{ notice.publish_date }}</p>
                            </td>
                            <td class="py-3 px-2">
                                <p class="font-semibold text-xs">{{ notice.expire_date }}</p>
                            </td>
                            <td class="py-3 px-2">
                                <a :href="notice.attachment_file" target="_blank" v-if="notice.attachment_file != ''" title="Attachment">
                                    <svg id="Layer" enable-background="new 0 0 64 64" height="512" viewBox="0 0 64 64" width="512" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 fill-current text-black-500 mx-1"><path d="m30.586 45.414c.39.391.902.586 1.414.586s1.023-.195 1.414-.586l10-10c.781-.781.781-2.047 0-2.828s-2.047-.781-2.828 0l-6.586 6.586v-29.172c0-1.104-.896-2-2-2s-2 .896-2 2v29.172l-6.586-6.586c-.78-.781-2.048-.781-2.828 0-.781.781-.781 2.047 0 2.828z"></path><path d="m18 56h28c3.309 0 6-2.691 6-6v-8c0-1.104-.896-2-2-2s-2 .896-2 2v8c0 1.103-.897 2-2 2h-28c-1.103 0-2-.897-2-2v-8c0-1.104-.896-2-2-2s-2 .896-2 2v8c0 3.309 2.691 6 6 6z"></path></svg>
                                </a>
                                <p class="font-semibold text-xs" v-else> -- </p>
                            </td>
                            <td class="py-3 px-2 " v-if="hidecolumns == 'false'">
                                <div class="flex items-center">
                                    <a :href="url+'/admin/notice/edit/'+notice.id" title="Edit">
                                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 477.873 477.873" xml:space="preserve" class="w-4 h-4 fill-current text-black-500 mx-1"><g><g><path d="M392.533,238.937c-9.426,0-17.067,7.641-17.067,17.067V426.67c0,9.426-7.641,17.067-17.067,17.067H51.2 c-9.426,0-17.067-7.641-17.067-17.067V85.337c0-9.426,7.641-17.067,17.067-17.067H256c9.426,0,17.067-7.641,17.067-17.067 S265.426,34.137,256,34.137H51.2C22.923,34.137,0,57.06,0,85.337V426.67c0,28.277,22.923,51.2,51.2,51.2h307.2 c28.277,0,51.2-22.923,51.2-51.2V256.003C409.6,246.578,401.959,238.937,392.533,238.937z"></path></g></g> <g><g><path d="M458.742,19.142c-12.254-12.256-28.875-19.14-46.206-19.138c-17.341-0.05-33.979,6.846-46.199,19.149L141.534,243.937 c-1.865,1.879-3.272,4.163-4.113,6.673l-34.133,102.4c-2.979,8.943,1.856,18.607,10.799,21.585 c1.735,0.578,3.552,0.873,5.38,0.875c1.832-0.003,3.653-0.297,5.393-0.87l102.4-34.133c2.515-0.84,4.8-2.254,6.673-4.13 l224.802-224.802C484.25,86.023,484.253,44.657,458.742,19.142z M434.603,87.419L212.736,309.286l-66.287,22.135l22.067-66.202 L390.468,43.353c12.202-12.178,31.967-12.158,44.145,0.044c5.817,5.829,9.095,13.72,9.12,21.955 C443.754,73.631,440.467,81.575,434.603,87.419z"></path></g></g></svg>
                                    </a>

                                    <a href="#" @click="deleteNotice(notice.id)" title="Delete">
                                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-4 h-4 fill-current text-black-500 mx-1"><g><g><g><polygon points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804"></polygon><rect x="235.948" y="175.791" width="40.104" height="237.285"></rect><polygon points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804"></polygon><path d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74 c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474 L380.665,471.896z"></path></g></g></g> <g><g><path d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42 C354.924,14.992,339.932,0,321.504,0z"></path></g></g></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr class="border-b">
                            <td colspan="8">
                                <p class="font-semibold text-s" style="text-align: center">No Records Found</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div v-if="this.page_count>1">
                    <paginate v-model="page" :page-count="this.page_count" :page-range="3" :margin-pages="1" :click-handler="getData" :prev-text="'<'" :next-text="'>'" :container-class="'pagination'" :page-class="'page-item'" :prev-link-class="'prev'" :next-link-class="'next'"></paginate>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    export default {
        props:['url' , 'scope' , 'hidecolumns', 'searchquery', 'mode'],
        data () {
            return {
                notices:[],
                showExpired:'',
                standardLink_id:'',
                standardLinklist:[],
                search:'',
                params:{},
                errors:[],
                success:null,
                currentScope: this.scope,
                page_count: 0,
                page: 1, 
            }
        },

        methods:
        {
            getData()
            {
                axios.get('/'+this.mode+'/notice/show/list/?standardLink_id='+this.currentScope+'&search='+this.search+'&page='+this.page).then(response => {
                    this.notices    = response.data.data;
                    this.page_count     = response.data.meta.last_page;
                    this.total          = response.data.meta.total;
                    //console.log(this.notices);    
                    //console.log(this.hidecolumns);    
                });
                axios.get('/'+this.mode+'/notice/list').then(response => {
                    this.standardLinklist = response.data.standardLinklist;
                });
            },

            trim(string) 
            {
                return string.substring(0,140) + '...';
            },

            searchList()
            {
                this.getData();
            },

            resetForm()
            {
                this.search = '';
                this.currentScope = '';
                this.getData();
            },

            selectClass()
            {
                this.currentScope = this.standardLink_id;
                this.getData();
            },

            showExpiredNotice(e)
            {
                if (e.target.checked) 
                {
                    this.params = { showExpired:this.showExpired };

                    this.final = this.url+'/'+this.mode+'/notice/show/list/?'+this.searchquery;

                    Object.keys(this.params).forEach(key => {
                        this.final = this.addParam(this.final, key, this.params[key])
                    });

                    axios.get(this.final).then(response => {
                        this.notices = response.data.data;
                    });
                }
                else if (!e.target.checked)
                {
                    this.getData();
                }
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

            deleteNotice(id) 
            {
                var thisswal = this;
                swal({
                    title: 'Are you sure',
                    text: 'Do you want to delete this Notice ?',
                    icon: "info",
                    buttons: [
                        'No',
                        'Yes'
                    ],
                    dangerMode: true,
                }).then(function(isConfirm) {
                    if (isConfirm) 
                    {
                        axios.delete(thisswal.url+'/'+thisswal.mode+'/notice/delete/'+ id).then(response => {
                            thisswal.success = response.data.success;
                            window.location.reload();
                        }); 
                    }
                    else 
                    {
                        swal("Cancelled");
                    }
                });
            },
        },
  
        created()
        {    
            this.getData();
        }
    }
</script>