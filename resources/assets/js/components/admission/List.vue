<template>
    <div class="relative">
        <div v-if="this.success!=null" class="alert alert-success mt-2" id="success-alert">{{this.success}}</div>
        <div v-if="this.error!=null" class="alert alert-danger mt-2" id="error-alert">{{this.error}}</div>
        <div class="flex flex-wrap lg:flex-row justify-between items-center">
            <div class="">
                <h1 class="admin-h1 my-3">Admission</h1>
            </div>
            <div class="flex items-center gap-2">
                <div v-if="statusFilter=='Approved' && approvedCount > 0" class="flex items-center gap-2">
                    <div v-if="!showImportConfirm">
                        <a href="#" @click="showImportConfirm=true" class="btn btn-submit blue-bg text-white rounded px-3 py-1 text-sm font-medium">
                            Import Approved ({{ approvedCount }})
                        </a>
                    </div>
                    <div v-else class="flex items-center gap-2 bg-gray-50 border rounded px-3 py-2">
                        <label class="text-sm font-medium">Joining Date:</label>
                        <input type="date" v-model="joiningDate" class="tw-form-control py-1 px-2 text-sm">
                        <a href="#" @click="doImport()" class="btn btn-submit blue-bg text-white rounded px-3 py-1 text-sm font-medium">Confirm</a>
                        <a href="#" @click="showImportConfirm=false" class="text-sm text-gray-500">Cancel</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- STATUS FILTER TABS -->
        <div class="flex flex-wrap gap-2 my-3">
            <a href="#" @click="setFilter('all')" class="px-3 py-1 rounded text-sm font-medium" :class="statusFilter=='all' ? 'blue-bg text-white' : 'bg-gray-200 text-gray-700'">
                All ({{ totalCount }})
            </a>
            <a href="#" @click="setFilter('Draft')" class="px-3 py-1 rounded text-sm font-medium" :class="statusFilter=='Draft' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700'">
                Draft
            </a>
            <a href="#" @click="setFilter('Pending')" class="px-3 py-1 rounded text-sm font-medium" :class="statusFilter=='Pending' ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700'">
                Pending
            </a>
            <a href="#" @click="setFilter('Approved')" class="px-3 py-1 rounded text-sm font-medium" :class="statusFilter=='Approved' ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-700'">
                Approved
            </a>
            <a href="#" @click="setFilter('Rejected')" class="px-3 py-1 rounded text-sm font-medium" :class="statusFilter=='Rejected' ? 'bg-red-500 text-white' : 'bg-gray-200 text-gray-700'">
                Rejected
            </a>
        </div>

        <div class="">
            <div class="flex flex-wrap custom-table my-3 overflow-auto">
                <table class="w-full">
                    <thead class="bg-grey-light">
                        <tr class="border-b">
                            <th class="text-left text-sm px-2 py-2 text-grey-darker">Application Number</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker">Name</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker">Class Applied For</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker">Status</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker">Actions</th>
                        </tr>
                    </thead>   
                    <tbody v-if="Object.keys(this.admissions).length > 0">
                        <tr class="border-b" v-for="admission in admissions">
                            <td class="py-3 px-2">
                                <p class="font-semibold text-xs">{{ admission.application_no }}</p>
                            </td>
                            <td class="py-3 px-2">
                                <p class="font-semibold text-xs">{{ admission.name }}</p>
                            </td>
                            <td class="py-3 px-2">
                                <p class="font-semibold text-xs">{{ admission.standard_name || admission.standard_id }}</p>
                            </td>
                            <td class="py-3 px-2">
                                <span class="text-xs font-semibold px-2 py-1 rounded" :class="statusClass(admission.application_status)">{{ admission.application_status }}</span>
                            </td>
                            <td class="py-3 px-2">
                                <div class="flex items-center">     
                                    <a :href="url+'/admin/admission/edit/'+admission.id" class="cursor-pointer" title="View / Edit">
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 477.873 477.873" class="w-4 h-4 fill-current text-black-500 mx-1"><g><g><path d="M392.533,238.937c-9.426,0-17.067,7.641-17.067,17.067V426.67c0,9.426-7.641,17.067-17.067,17.067H51.2 c-9.426,0-17.067-7.641-17.067-17.067V85.337c0-9.426,7.641-17.067,17.067-17.067H256c9.426,0,17.067-7.641,17.067-17.067 S265.426,34.137,256,34.137H51.2C22.923,34.137,0,57.06,0,85.337V426.67c0,28.277,22.923,51.2,51.2,51.2h307.2 c28.277,0,51.2-22.923,51.2-51.2V256.003C409.6,246.578,401.959,238.937,392.533,238.937z"></path></g></g><g><g><path d="M458.742,19.142c-12.254-12.256-28.875-19.14-46.206-19.138c-17.341-0.05-33.979,6.846-46.199,19.149L141.534,243.937 c-1.865,1.879-3.272,4.163-4.113,6.673l-34.133,102.4c-2.979,8.943,1.856,18.607,10.799,21.585 c1.735,0.578,3.552,0.873,5.38,0.875c1.832-0.003,3.653-0.297,5.393-0.87l102.4-34.133c2.515-0.84,4.8-2.254,6.673-4.13 l224.802-224.802C484.25,86.023,484.253,44.657,458.742,19.142z M434.603,87.419L212.736,309.286l-66.287,22.135l22.067-66.202 L390.468,43.353c12.202-12.178,31.967-12.158,44.145,0.044c5.817,5.829,9.095,13.72,9.12,21.955 C443.754,73.631,440.467,81.575,434.603,87.419z"></path></g></g></svg>
                                    </a>

                                    <a href="#" @click="deleteAdmission(admission.id)" title="Delete">
                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-4 h-4 fill-current text-black-500 mx-1"><g><g><g><polygon points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804"></polygon><rect x="235.948" y="175.791" width="40.104" height="237.285"></rect><polygon points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804"></polygon><path d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74 c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474 L380.665,471.896z"></path></g></g></g><g><g><path d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42 C354.924,14.992,339.932,0,321.504,0z"></path></g></g></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr class="border-b">
                            <td colspan="5">
                                <p class="font-semibold text-s" style="text-align: center">No Records Found</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-if="this.page_count>1">
                <paginate v-model="page" :page-count="this.page_count" :page-range="3" :margin-pages="1" :click-handler="getData" :prev-text="'<'" :next-text="'>'" :container-class="'pagination'" :page-class="'page-item'" :prev-link-class="'prev'" :next-link-class="'next'"></paginate>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['url','slug'],
        data () {
            return {
                admissions:[],
                errors:[],
                success:null, 
                error:null,
                total: 0,
                page: 1,
                page_count: 0,
                statusFilter: 'all',
                showImportConfirm: false,
                joiningDate: new Date().toISOString().slice(0,10),
                totalCount: 0,
                approvedCount: 0,
            }
        },

        methods:
        {
            getData(page=1)
            {
                let url = this.url+'/admin/admissionlist?page='+page;
                if (this.statusFilter != 'all') {
                    url += '&status='+this.statusFilter;
                }
                axios.get(url).then(response => {
                    this.admissions = response.data.data;
                    this.page_count = response.data.meta.last_page;
                    this.total      = response.data.meta.total;
                });
            },

            getCounts()
            {
                // Get total count
                axios.get(this.url+'/admin/admissionlist?page=1').then(response => {
                    this.totalCount = response.data.meta.total;
                });
                // Get approved count
                axios.get(this.url+'/admin/admissionlist?page=1&status=Approved').then(response => {
                    this.approvedCount = response.data.meta.total;
                });
            },

            setFilter(status)
            {
                this.statusFilter = status;
                this.page = 1;
                this.getData(1);
            },

            statusClass(status)
            {
                switch(status) {
                    case 'Draft': return 'bg-yellow-100 text-yellow-800';
                    case 'Pending': return 'bg-blue-100 text-blue-800';
                    case 'Approved': return 'bg-green-100 text-green-800';
                    case 'Rejected': return 'bg-red-100 text-red-800';
                    default: return 'bg-gray-100 text-gray-800';
                }
            },

            doImport()
            {
                var self = this;
                self.errors = []; self.success = null; self.error = null;
                axios.post(self.url+'/admin/admission/import-approved', {
                    joining_date: self.joiningDate
                }).then(response => {
                    self.success = response.data.success;
                    self.showImportConfirm = false;
                    self.getData(1);
                    self.getCounts();
                }).catch(error => {
                    if (error.response && error.response.data) {
                        self.error = error.response.data.error || 'An error occurred';
                    }
                });
            },
            deleteAdmission(id) 
            {
                var thisswal = this;
                swal({
                    title: 'Are you sure',
                    text: 'Do you want to delete this admission ?',
                    icon: "info",
                    buttons: [
                        'No',
                        'Yes'
                    ],
                    dangerMode: true,
                }).then(function(isConfirm) {
                    if (isConfirm) 
                    {
                        axios.get(thisswal.url+ '/admin/admission/delete/'+ id).then(response => {
                            thisswal.success = response.data.success;
                            thisswal.getData(1);
                            thisswal.getCounts();
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
            this.getCounts();
        }
    }
</script>
