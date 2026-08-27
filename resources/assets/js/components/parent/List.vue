<template>
    <div>
        <Teleport to="#parent_index">
            <div class="flex flex-wrap lg:flex-row justify-between my-3">
                <div class="">
                    <h1 class="admin-h1 my-3">Parents</h1>
                </div>

                <div class="relative flex items-center w-full lg:w-2/5 md:w-2/5 gap-3 justify-end">
                    <!-- Filter by Class-Section Dropdown -->
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-semibold text-gray-700">Filter by Class-Section:</label>
                        <select
                            v-model="selectedStandardLink"
                            @change="onStandardLinkChange"
                            class="px-4 py-2 border border-gray-300 rounded bg-white text-sm font-medium text-gray-700 hover:border-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent cursor-pointer"
                        >
                            <option value="">✓ All Classes</option>
                            <option v-for="link in standardLinklist" :key="link.id" :value="link.id">
                                {{ link.standard_name }} - {{ link.section_name }}
                            </option>
                        </select>
                    </div>

                    <a :href="url+'/admin/parents'" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 text-sm font-medium hover:bg-gray-200">
                        <span class="mx-1 text-sm font-semibold">Reset</span>
                    </a>
                </div>
            </div>
        </Teleport>
        <vue-good-table  :columns="columns" :rows="rows" @column-filter="onColumnFilter" @page-change="onPageChange" :paginationOptions="{ enabled: true , perPageDropdownEnabled: false }" :totalRows="totalRecords" :isLoading="isLoading" mode="remote"> 
            <template #table-row="props">
                <div v-if="props.column.field == 'action'" class="w-full flex justify-between">
                    <p v-bind:class="[props.row.count==0?'hidden':'block']">
                        <a :href=props.row.editurl class="block btn px-2 py-1 bg-blue-600 text-white rounded text-sm">Edit</a>
                    </p>
                </div>
                <div v-if="props.column.field == 'fullname'" class="w-full flex justify-between">
                    <a :href=props.row.showurl class="">
                        <span v-if="props.row.prefix" class="font-semibold text-gray-700">{{ props.row.prefix }}</span>
                        {{ props.row.fullname }}
                        <span v-if="props.row.status != 'active'" class="bg-red-500 rounded px-1 text-xs text-white">InActive</span>
                    </a>
                </div>
                <div v-if="props.column.field == 'children.fullname'" class="w-full">
                    <div v-if="props.row.children" class="flex flex-col">
                        <a :href="props.row.children.name" class="font-semibold text-blue-600 hover:underline">
                            {{ props.row.children.fullname }}
                        </a>
                        <span class="text-xs text-gray-600 mt-1">
                            Roll Number: <span class="font-semibold">{{ props.row.children.roll_number }}</span>
                        </span>
                        <span class="text-xs text-gray-600">
                            {{ props.row.children.standard }} - {{ props.row.children.section }}
                        </span>
                    </div>
                    <div v-else class="text-gray-500 text-sm">No children</div>
                </div>
                <div v-if="props.column.field == 'mobile_no'" class="w-full flex justify-between">
                    <p>{{ props.row.mobile_no }}</p>
                </div>
            </template>
        </vue-good-table>     
    </div>
</template>

<script>

    import { VueGoodTable } from 'vue-good-table-next'
    import 'vue-good-table-next/dist/vue-good-table-next.css'
    export default {
        props:['url' , 'searchquery', 'standardlinklist'],
        components: {
            VueGoodTable,
        },
        data() {
            return{
                show_event_link:'',
                array:{},
                totalRecords:'',
                action:'',
                id:'',
                isLoading: false,
                selectedStandardLink: '',
                columns: [
                    {
                        label: 'Name',
                        field: 'fullname',
                        filterOptions: {
                            enabled: true,
                            placeholder: "Search",
                            // filterFn: this.myFunc,
                        }
                    },
                    {
                        label: 'Parent Of',
                        field: 'children.fullname',
                        filterOptions: {
                            enabled: true,
                            placeholder: "Search",
                            // filterFn: this.myFunc,
                        }
                    },
                    {
                        label: 'Mobile Number',
                        field: 'mobile_no',
                        filterOptions: {
                            enabled: true,
                            placeholder: "Search",
                            // filterFn: this.myFunc,
                        }
                    },
                    {
                        label: 'Action',
                        field:'action',
                        html:true,
                    },
                ],
                rows: [
          
                ], 
                total: 0,
                page: 1,
                page_count: 0,
                fullname:'',
                mobile_no:'',
                student_name:'',
            }
        },

        methods: 
        {
            getData()
            {
                let url = '/admin/parent/list?'+'fullname='+this.fullname+'&student_name='+this.student_name+'&mobile_no='+this.mobile_no+'&page='+this.page;

                // Add standard_link filter if selected
                if (this.selectedStandardLink) {
                    url += '&standardlink_id=' + this.selectedStandardLink;
                }

                axios.get(url).then(response => {
                    this.rows = response.data.data;
                    this.page_count = response.data.meta.last_page;
                    this.totalRecords = response.data.meta.total;
                    //console.log(this.rows)
                });
            },

            onStandardLinkChange()
            {
                this.page = 1;
                this.getData();
            },

            onColumnFilter(params) {

              const filters = params.columnFilters || {}

              this.fullname = filters.fullname || ''
              this.mobile_no = filters.mobile_no || ''
              this.student_name = filters['children.fullname'] || ''

              this.page = 1
              this.getData()
            },

    
            onColumnFilterOld(params) 
            {
                //console.log(params.columnFilters['fullname']);

                if(typeof params.columnFilters['fullname'] !== "undefined")
                {
                  this.fullname=params.columnFilters['fullname'];
                }
                if(typeof params.columnFilters['mobile_no'] !== "undefined")
                {
                   this.mobile_no=params.columnFilters['mobile_no'];
                }
                if(typeof params.columnFilters['children.fullname'] !== "undefined")
                {
                  this.student_name=params.columnFilters['children.fullname'];
                }
                this.page=1;
                
                
                 
               /* this.array = {
                    fullname:params.columnFilters['fullname'],
                    mobile_no:params.columnFilters['mobile_no'],
                    student_name:params.columnFilters['children.fullname'],
                };*/
    
                /*axios.get('/admin/parent/list?'+'firstname='+this.fullname+'&page='+this.page).then(response => {
                    this.rows = response.data.data;
                    this.page_count = response.data.meta.last_page;
                    this.totalRecords = response.data.meta.total;
                    //console.log(this.rows)
                });*/
                this.getData();

               /* this.final=this.url+'/admin/parents?'+this.searchquery;
          
                Object.keys(this.array).forEach(key => {
                    this.final = this.addParam(this.final, key, this.array[key])
                });

                window.location.href=this.final;*/
            },

            onPageChange(params) 
            {
                this.page = params.currentPage;
                this.getData(this.page);
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
        },

        created()
        {
            this.getData();
        },
    }
</script>