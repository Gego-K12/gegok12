<template>
    <div>
        <div v-if="this.success!=null" class="alert alert-success mt-3" id="success-alert">{{this.success}}</div>
        <vue-good-table
              :columns="columns"
              :rows="rows"
              :totalRows="rows.length"
              :isLoading="isLoading"
              :pagination-options="{ enabled: true, perPage: 10, setCurrentPage: 1, mode: 'pages'}"
            >
            <template #table-row="props">
                <div class="w-full flex justify-between">
                    <div v-if="props.column.field == 'title'" class="w-full flex justify-between">
                        <p>{{ props.row.title }}</p>
                    </div>
                    <div v-if="props.column.field == 'amount'" class="w-full flex justify-between">
                        <p>{{ props.row.amount }}<span class="text-red-500"><sup>{{ props.row.concession_applied }}</sup></span></p>
                    </div>
                    <div v-if="props.column.field == 'student_fullname'" class="w-full flex justify-between">
                        <a :href="url+'/'+mode+'/student/show/'+props.row.student_name">{{ props.row.student_fullname }}</a>
                    </div>
                    <div v-if="props.column.field == 'action'" class="w-full flex items-center">
                        <a href="#" title="Update Payment Details" @click="paymentDetail(props.row.fee_id,props.row.id,props.row.student_name)">
                            <svg version="1.0" class="w-8 h-8 fill-current mx-1" xmlns="http://www.w3.org/2000/svg" width="225.000000pt" height="225.000000pt" viewBox="0 0 225.000000 225.000000" preserveAspectRatio="xMidYMid meet"><g transform="translate(0.000000,225.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none"><path d="M790 1578 l0 -113 -146 -76 -145 -76 145 -68 c109 -51 146 -73 146 -87 0 -10 -2 -18 -3 -18 -2 0 -73 33 -158 74 l-154 74 -3 -252 c-2 -173 1 -260 9 -279 23 -55 35 -57 429 -57 l362 0 -9 33 c-33 131 24 264 141 325 42 22 64 27 126 27 63 0 83 -4 123 -27 l47 -27 0 129 0 129 -37 -16 c-21 -8 -68 -31 -106 -49 -37 -19 -92 -45 -122 -59 -50 -22 -55 -22 -55 -6 0 13 38 36 145 86 l145 68 -145 76 -145 76 0 112 0 113 -295 0 -295 0 0 -112z m550 -194 l0 -265 -116 -55 c-63 -30 -128 -54 -142 -54 -15 0 -78 25 -139 55 l-113 54 0 265 0 266 255 0 255 0 0 -266z"/><path d="M970 1505 c0 -23 4 -24 62 -27 37 -2 63 -8 65 -15 3 -9 -13 -12 -61 -10 -65 2 -66 1 -66 -24 0 -25 1 -25 60 -21 38 3 60 0 60 -7 0 -13 -33 -31 -59 -31 -11 0 -22 -4 -26 -9 -4 -8 73 -163 90 -179 1 -2 12 2 23 9 20 12 20 13 -9 64 -35 62 -35 68 -6 84 13 7 31 25 41 41 11 20 23 28 36 25 16 -3 20 2 20 25 0 23 -4 27 -19 23 -11 -3 -21 2 -24 11 -5 12 0 16 18 16 20 0 25 5 25 25 l0 25 -115 0 -115 0 0 -25z"/><path d="M1455 1048 c-60 -22 -111 -66 -140 -120 -95 -173 45 -385 240 -365 246 25 310 356 90 469 -46 24 -147 33 -190 16z m95 -148 l0 -70 70 0 c63 0 70 -2 70 -20 0 -18 -7 -20 -70 -20 l-70 0 0 -70 c0 -63 -2 -70 -20 -70 -18 0 -20 7 -20 70 l0 70 -70 0 c-63 0 -70 2 -70 20 0 18 7 20 70 20 l70 0 0 70 c0 63 2 70 20 70 18 0 20 -7 20 -70z"/></g></svg>
                        </a>
                        <a href="#" title="Send Fees Reminder" @click="sendReminder(props.row.fee_id,props.row.id,props.row.student_name)">
                            <svg version="1.0" class="w-4 h-4 fill-current mx-1" xmlns="http://www.w3.org/2000/svg" width="128.000000pt" height="128.000000pt" viewBox="0 0 128.000000 128.000000" preserveAspectRatio="xMidYMid meet"><g transform="translate(0.000000,128.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none"><path d="M514 1246 c-24 -24 -34 -43 -34 -64 0 -28 -6 -34 -75 -67 -124 -59 -217 -170 -251 -299 -10 -35 -14 -115 -14 -262 l0 -212 -34 -6 c-82 -15 -113 -115 -57 -178 l29 -33 191 -3 c156 -3 191 -6 191 -17 0 -25 60 -83 97 -96 74 -24 165 14 193 80 l12 31 165 0 c176 0 208 7 231 52 36 69 3 147 -69 163 l-29 7 0 96 0 97 51 18 c134 48 194 200 129 327 -44 86 -144 139 -240 126 -49 -7 -53 -5 -98 35 -49 43 -111 78 -174 98 -33 11 -38 17 -38 43 0 45 -56 98 -105 98 -29 0 -45 -8 -71 -34z m249 -173 c34 -17 80 -45 100 -63 l37 -32 -36 -40 c-20 -22 -43 -56 -51 -76 -21 -51 -18 -142 6 -190 28 -54 93 -110 146 -124 l44 -12 3 -120 3 -121 44 -6 c67 -9 92 -58 51 -99 -19 -19 -33 -20 -512 -20 -443 0 -493 2 -510 17 -43 39 -10 103 53 103 32 0 40 4 44 23 2 12 6 128 7 257 l3 235 32 67 c52 111 158 199 270 228 21 5 75 8 120 6 65 -3 95 -10 146 -33z m340 -127 c66 -28 117 -108 117 -180 -1 -46 -33 -110 -71 -139 -116 -86 -274 -25 -304 120 -27 131 131 254 258 199z"/></g></svg>
                        </a>
                    </div>
                </div>
            </template>
        </vue-good-table>

        <div class="modal modal-mask" v-if="show == 1">
            <div class="modal-wrapper px-4">
                <div class="modal-container w-full max-w-md px-8 mx-auto">
                    <div class="modal-header flex justify-between items-center">
                        <h2>Add Fees Payment Detail</h2>
                        <button id="close-button" class="modal-default-button text-2xl py-1"  @click="closeModal()">&times;</button>
                    </div>
                    <div class="modal-body">
                        <ul class="flex flex-col lg:flex-row md:flex-row list-reset leading-loose my-2 text-sm">
                            <li class="flex lg:px-4 md:px-2 py-1">
                                <div class="flex items-center">
                                    <span class="text-gray-700 font-medium mx-2">Fee Group : </span>
                                </div>
                                <div>
                                    <p>{{ editfee.fee_group }}</p>
                                </div>
                            </li>
                            <li class="flex pr-4 py-1">
                                <div class="flex items-center">
                                    <span class="text-gray-700 font-medium mx-2">Title : </span>
                                </div>
                                <div>
                                    <p>{{ editfee.title }}</p>
                                </div>
                            </li>
                        </ul>
                        <ul class="flex flex-col lg:flex-row md:flex-row list-reset leading-loose my-2 text-sm">
                            <li class="flex lg:px-4 md:px-2 py-1">
                                <div class="flex items-center">
                                    <span class="text-gray-700 font-medium mx-2">Start Date : </span>
                                </div>
                                <div>
                                    <p>{{ editfee.start_date }}</p>
                                </div>
                            </li>
                            <li class="flex lg:px-4 md:px-2 py-1">
                                <div class="flex items-center">
                                    <span class="text-gray-700 font-medium mx-2">Term : </span>
                                </div>
                                <div>
                                    <p>{{ editfee.term }}</p>
                                </div>
                            </li>
                        </ul>
                        <ul class="flex flex-col lg:flex-row md:flex-row list-reset leading-loose my-2 text-sm">
                            <li class="flex lg:px-4 md:px-2 py-1">
                                <div class="flex items-center">
                                    <span class="text-gray-700 font-medium mx-2">End Date : </span>
                                </div>
                                <div>
                                    <p>{{ editfee.end_date }}</p>
                                </div>
                            </li>
                            <li class="flex lg:px-4 md:px-2 py-1">
                                <div class="flex items-center">
                                    <span class="text-gray-700 font-medium mx-2">Amount : </span>
                                </div>
                                <div>
                                    <p>{{ editfee.paid_amount }}</p>
                                </div>
                            </li>
                        </ul>
                    </div>

                    <div class="modal-body">
                        <div class="flex">
                            <div class="w-full lg:w-1/4">
                                <label for="paid_on" class="tw-form-label">Paid On</label>
                            </div>
                            <div class="w-full lg:w-3/4">
                                <input type="date" name="paid_on" v-model="paid_on" class="tw-form-control w-full" id="paid_on">
                                <span v-if="errors.paid_on" class="text-red-500 text-xs font-semibold">{{ errors.paid_on[0] }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-body">
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
                        <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="addPaymentDetail(editfee.fee_id,editfee.id)">Submit</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { VueGoodTable } from 'vue-good-table-next'
    import 'vue-good-table-next/dist/vue-good-table-next.css'
    export default {
        props:['url' , 'fee_id' , 'mode'],
        components: {
            VueGoodTable,
        },
        data () {
            return {
                isLoading: false,
                editfee:[], 
                amount:'',
                comments:'',
                paid_on:'',
                notify_parent:'',
                show:'',
                errors:[],
                success:null,
                columns: [
                    {
                        label: 'Fees Title',
                        field: 'title',
                        filterOptions: {
                            enabled: true,
                            placeholder: "Search",
                        }
                    },
                    {
                        label: 'Amount',
                        field: 'amount',
                        filterOptions: {
                            enabled: true,
                            placeholder: "Search",
                        }
                    },
                    {
                        label: 'Student Name',
                        field: 'student_fullname',
                        filterOptions: {
                            enabled: true,
                            placeholder: "Search",
                        }
                    },
                    {
                        label: 'Action',
                        field:'action',
                        html:true,
                    },
                ],
                rows: [],
            }
        },

        methods:
        {
            getData()
            {
                axios.get('/'+this.mode+'/dashboard/feeslist/'+this.fee_id).then(response => {
                    this.rows = response.data.unpaidList;
                    //console.log(this.rows);     
                });
            },

            paymentDetail(id,feePayment_id,name)
            {
                axios.get('/'+this.mode+'/student/feepayment/add/list/'+feePayment_id).then(response => {
                    this.editfee = response.data;
                    this.paid_on = response.data.paid_on;
                    this.notify_parent = response.data.notify_parent;
                    //console.log(this.editfee);   
                });
                this.show = 1;
                this.name = name;
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

            closeModal()
            {
              this.show = 0;
            },

            addPaymentDetail(id,feePayment_id)
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();

                formData.append('fee_id',id); 
                formData.append('name',this.name); 
                formData.append('paid_on',this.paid_on); 
                formData.append('notify_parent',this.notify_parent); 
                formData.append('feePayment_id',feePayment_id);

                axios.post('/admin/student/feepayment/add',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                    this.closeModal();
                    window.location.reload();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            sendReminder(id,feePayment_id,name)
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();

                formData.append('fee_id',id); 
                formData.append('name',name); 
                formData.append('feePayment_id',feePayment_id);

                axios.post('/admin/dashboard/send/reminder/'+feePayment_id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                }).catch(error => {
                    this.errors = error.response.data.errors;
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