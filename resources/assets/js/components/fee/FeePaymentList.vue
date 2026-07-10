<template>
    <div class="relative">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
        <div class="">
            <ul class="flex flex-col lg:flex-row md:flex-row list-reset leading-loose my-2 text-sm" v-if="Object.keys(this.fees).length > 0">
                <li class="flex pr-4 py-1">
                    <div class="flex items-center">
                        <span class="text-gray-700 font-medium mx-2">Title : </span>
                    </div>
                    <div>
                        <p>{{ fees.title }}</p>
                    </div>
                </li>
                <li class="flex lg:px-4 md:px-2 py-1" v-if="fees.standardLink_id != null">
                    <div class="flex items-center">
                        <span class="text-gray-700 font-medium mx-2">Class : </span>
                    </div>
                    <div>
                        <p>{{ fees.standardLink_name }}</p>
                    </div>
                </li>
                <li class="flex lg:px-4 md:px-2 py-1">
                    <div class="flex items-center">
                        <span class="text-gray-700 font-medium mx-2">Term : </span>
                    </div>
                    <div>
                        <p>{{ fees.term }}</p>
                    </div>
                </li>
                <li class="flex lg:px-4 md:px-2 py-1">
                    <div class="flex items-center">
                        <span class="text-gray-700 font-medium mx-2">Amount : </span>
                    </div>
                    <div>
                        <p>{{ fees.amount }}</p>
                    </div>
                </li>
                <li class="flex lg:px-4 md:px-2 py-1">
                    <div class="flex items-center">
                        <span class="text-gray-700 font-medium mx-2">Due Date : </span>
                    </div>
                    <div>
                        <p>{{ fees.end_date }}</p>
                    </div>
                </li>
                <li class="flex lg:px-4 md:px-2 py-1">
                    <div class="flex items-center">
                        <span class="text-gray-700 font-medium mx-2">Unpaid Count : </span>
                    </div>
                    <div>
                        <p>{{ fees.pending_count }}</p>
                    </div>
                </li>
                <li class="flex lg:px-4 md:px-2 py-1">
                    <div class="flex items-center">
                        <span class="text-gray-700 font-medium mx-2">Total Pending Amount : </span>
                    </div>
                    <div>
                        <p>Rs. {{ fees.pending_amount }}</p>
                    </div>
                </li>
            </ul>
            <div class="flex flex-wrap custom-table my-3 overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-grey-light">
                        <tr class="border-b">
                            <th class="text-left text-sm px-2 py-2 text-grey-darker"> User Name </th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker"> Paid Amount </th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker"> Concession Applied </th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker"> Paid On </th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker"> Notify Parents </th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker" width="5%"> Actions </th>
                        </tr>
                    </thead>   
                    <tbody v-if="Object.keys(this.feepaymentlist).length > 0">
                        <tr class="border-b" v-for="feepayment in feepaymentlist">
                            <td class="py-3 px-2">
                                <p class="font-semibold text-xs">
                                    <a :href="'/admin/student/show/'+feepayment.user_name" target="_blank" v-if="mode == 'admin'">{{ feepayment.user_fullname }}</a>
                                    <a href="#" v-else>{{ feepayment.user_fullname }}</a>
                                </p>
                            </td>
                            <td class="py-3 px-2">
                                <p class="font-semibold text-xs">{{ feepayment.paid_amount }}</p>
                            </td>
                            <td class="py-3 px-2">
                                <p class="font-semibold text-xs" v-if="feepayment.concession_applied == 1">Yes</p>
                                <p class="font-semibold text-xs" v-else>--</p>
                            </td>
                            <td class="py-3 px-2">
                                <p class="font-semibold text-xs">{{ feepayment.paid_on }}</p>
                            </td>
                            <td class="py-3 px-2">
                                <p class="font-semibold text-xs">{{ feepayment.notify_parent }}</p>
                            </td>
                            <td class="py-3 px-2">
                                <div class="flex items-center">
                                    <a href="#" @click="showEdit(feepayment.id)" title="Show">
                                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 477.873 477.873" xml:space="preserve" class="w-4 h-4 fill-current text-black-500 mx-1"><g><g><path d="M392.533,238.937c-9.426,0-17.067,7.641-17.067,17.067V426.67c0,9.426-7.641,17.067-17.067,17.067H51.2 c-9.426,0-17.067-7.641-17.067-17.067V85.337c0-9.426,7.641-17.067,17.067-17.067H256c9.426,0,17.067-7.641,17.067-17.067 S265.426,34.137,256,34.137H51.2C22.923,34.137,0,57.06,0,85.337V426.67c0,28.277,22.923,51.2,51.2,51.2h307.2 c28.277,0,51.2-22.923,51.2-51.2V256.003C409.6,246.578,401.959,238.937,392.533,238.937z"></path></g></g> <g><g><path d="M458.742,19.142c-12.254-12.256-28.875-19.14-46.206-19.138c-17.341-0.05-33.979,6.846-46.199,19.149L141.534,243.937 c-1.865,1.879-3.272,4.163-4.113,6.673l-34.133,102.4c-2.979,8.943,1.856,18.607,10.799,21.585 1.735,0.578,3.552,0.873,5.38,0.875c1.832-0.003,3.653-0.297,5.393-0.87l102.4-34.133c2.515-0.84,4.8-2.254,6.673-4.13 l224.802-224.802C484.25,86.023,484.253,44.657,458.742,19.142z M434.603,87.419L212.736,309.286l-66.287,22.135l22.067-66.202 L390.468,43.353c12.202-12.178,31.967-12.158,44.145,0.044c5.817,5.829,9.095,13.72,9.12,21.955 C443.754,73.631,440.467,81.575,434.603,87.419z"></path></g></g></svg>
                                    </a>

                                    <a href="#" @click="deleteFee(feepayment.id)" title="Delete">
                                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-4 h-4 fill-current text-black-500 mx-2"><g><g><g><polygon points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804"></polygon><rect x="235.948" y="175.791" width="40.104" height="237.285"></rect><polygon points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804"></polygon><path d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74 c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474 L380.665,471.896z"></path></g></g></g><g><g><path d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42 C354.924,14.992,339.932,0,321.504,0z"></path></g></g></svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr class="border-b">
                            <td colspan="6">
                                <p class="font-semibold text-s" style="text-align: center">No Records Found</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div v-if="tab == 'edit'" class="modal modal-mask">
            <div class="modal-wrapper px-4">
                <div class="modal-container w-full max-w-md px-8 mx-auto">
                    <div class="modal-header flex justify-between items-center">
                        <h2>Edit Fees Payment Detail</h2>
                        <button id="close-button" class="modal-default-button text-2xl py-1" @click="closeModal()">&times;</button>
                    </div>
                    <div class="modal-body" v-if="Object.keys(this.fees).length > 0">
                        <ul class="flex flex-col lg:flex-row md:flex-row list-reset leading-loose my-2 text-sm">
                            <li class="flex px-2 py-1">
                                <div class="flex items-center">
                                    <span class="text-gray-700 font-medium mx-2">Title : </span>
                                </div>
                                <div>
                                    <p>{{ fees.title }}</p>
                                </div>
                            </li>
                            <li class="flex lg:px-2 md:px-2 py-1" v-if="fees.standardLink_id != null">
                                <div class="flex items-center">
                                    <span class="text-gray-700 font-medium mx-2">Class : </span>
                                </div>
                                <div>
                                    <p>{{ fees.standardLink_name }}</p>
                                </div>
                            </li>
                            <li class="flex lg:px-2 md:px-2 py-1">
                                <div class="flex items-center">
                                    <span class="text-gray-700 font-medium mx-2">Term : </span>
                                </div>
                                <div>
                                    <p>{{ fees.term }}</p>
                                </div>
                            </li>
                        </ul>

                        <ul class="flex flex-col lg:flex-row md:flex-row list-reset leading-loose my-2 text-sm">
                            <li class="flex lg:px-2 md:px-2 py-1">
                                <div class="flex items-center">
                                    <span class="text-gray-700 font-medium mx-2">Amount : </span>
                                </div>
                                <div>
                                    <p>{{ fees.amount }}</p>
                                </div>
                            </li>
                            <li class="flex lg:px-2 md:px-2 py-1">
                                <div class="flex items-center">
                                    <span class="text-gray-700 font-medium mx-2">Due Date : </span>
                                </div>
                                <div>
                                    <p>{{ fees.end_date }}</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="modal-body">
                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full lg:w-1/4">
                                <label for="paid_on" class="tw-form-label">Paid On</label>
                            </div>
                            <div class="w-full lg:w-3/4">
                                <input type="date" name="paid_on" v-model="paid_on" class="tw-form-control w-full" id="paid_on">
                                <span v-if="errors.paid_on" class="text-red-500 text-xs font-semibold">{{errors.paid_on[0]}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body" v-if="this.notify_parent_value != 'Yes'">
                        <div class="flex items-center">
                            <div class="w-6">
                                <input type="checkbox" name="notify_parent" v-model="notify_parent" class="tw-form-control w-full" @click="addNotify($event)">
                            </div>
                            <div class="mx-1"> 
                                <label for="notify_parent" class="tw-form-label">Notify Parent</label>
                            </div>
                        </div>
                    </div>
                    <div class="my-6">
                        <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitFee()">Submit</a>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="this.page_count>1">
            <paginate v-model="page" :page-count="this.page_count" :page-range="3" :margin-pages="1" :click-handler="getData" :prev-text="'<'" :next-text="'>'" :container-class="'pagination'" :page-class="'page-item'" :prev-link-class="'prev'" :next-link-class="'next'"></paginate>
        </div>
    </div>
</template>

<script>
    export default {
        props:['url' , 'fee_id','mode'],
        data () {
            return {
                feepaymentlist:[],
                fees:[],
                tab:0,
                id:'',
                paid_on:'',
                notify_parent:'',
                notify_parent_value:'',
                errors:[],
                success:null, 
                total: 0,
                page: 1,
                page_count: 0,
            }
        },

        methods:
        {
            getData(page=1)
            {
                axios.get('/'+this.mode+'/fee/edit/list/'+this.fee_id+'/null').then(response => {
                    this.fees           = response.data;
                });

                axios.get('/'+this.mode+'/feedetail/show/list/'+this.fee_id+'?page='+page).then(response => {
                    this.feepaymentlist = response.data.data;
                    this.page_count     = response.data.meta.last_page;
                    this.total          = response.data.meta.total;
                    //console.log(this.feelist);     
                });
            },

            getFeeDetail(id)
            {
                axios.get('/'+this.mode+'/feedetail/edit/list/'+id).then(response => {
                    this.id             = response.data.id;
                    this.paid_on        = response.data.paid_on;
                    this.notify_parent  = response.data.notify_parent;
                    this.notify_parent_value  = response.data.notify_parent_value;
                    //console.log(this.paid_on);     
                    //console.log(this.notify_parent);     
                });
            },

            showEdit(id)
            {
                this.getFeeDetail(id);
                this.tab = 'edit';
            },

            closeModal()
            {
                this.tab = 0;
            },

            addNotify(e)
            {
                if (e.target.checked) 
                {
                    this.notify_parent = 1;
                }
                else
                {
                    this.notify_parent = 0;
                }
            },

            submitFee()
            {
                this.errors=[];
                this.success=null;

                let formData=new FormData();

                formData.append('paid_on',this.paid_on);                 
                formData.append('notify_parent',this.notify_parent);

                axios.post('/'+this.mode+'/feedetail/edit/'+this.id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                    this.tab=0;
                    window.location.reload();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            deleteFee(id) 
            {
                var thisswal = this;
                swal({
                    title: 'Are you sure',
                    text: 'Do you want to delete this Fee Payment Detail ?',
                    icon: "info",
                    buttons: [
                        'No',
                        'Yes'
                    ],
                    dangerMode: true,
                }).then(function(isConfirm) {
                    if (isConfirm) 
                    {
                        axios.get(thisswal.url+ '/'+this.mode+'/feedetail/delete/'+ id).then(response => {
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
        /* height: 550px;*/
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