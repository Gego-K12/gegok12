<template>
    <div>
        <vue-good-table
          mode="remote"
          :columns="columns"
          :rows="rows"
          :group-options="{ enabled: true , headerPosition: 'top' }"
          :search-options="{ enabled: false }"
        >
            <template #table-row="props">
                <div v-if="props.column.field == 'action'" class="w-full flex justify-between">
                    <p v-bind:class="[props.row.count==0?'block':'hidden']">
                        <a class="block btn px-2 py-1 bg-blue-600 text-white rounded text-sm" :href=props.row.addurl v-if="props.row.start_time < props.row.today_date">Upload Marks</a>
                    </p>
                    <a class="block btn px-2 py-1 bg-blue-600 text-white rounded text-sm" :href=props.row.view v-bind:class="[props.row.count>0?'block':'hidden']">View</a>
                    <a class="block btn px-2 py-1 bg-blue-600 text-white rounded text-sm" :href=props.row.downloadurl v-bind:class="[props.row.count>0?'block':'hidden']">Export</a>
                </div>
            </template>
            <template #table-header-row="props">
                <div v-if="props.row.action==''" class="w-full flex justify-between">
                    <div>{{props.row.name}}</div>
                    <!-- <div v-if="props.row['children']==''">
                        <a class="block btn px-2 py-1 bg-blue-600 text-white rounded text-sm" :href=props.row.url>Add Schedule</a> 
                    </div>
                    <div v-else>
                        <a class="block btn px-2 py-1 bg-blue-600 text-white rounded text-sm" :href=props.row.url v-bind:class="[props.row['children'][0]['start_time']<props.row['children'][0]['today_date']?'hidden':'block']">Add Schedule</a> 
                    </div> -->
                    <!-- <div class="w-32 relative" v-if="props.row['children']!=''">
                        <a class="text-sm rounded px-2 py-1 flex items-center justify-between btn btn-primary submit-btn w-full" @click="showeventlink(props.row.id)" id="show" v-bind:class="[props.row['children'][0]['markscount']==props.row['children'][0]['schedulecount']?'block':'hidden']">
                            <span >Report Card</span>
                            <svg data-v-54d5f170="" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="512px" height="512px" viewBox="0 0 451.847 451.847" xml:space="preserve" class="w-2 h-2 fill-current text-white"><g data-v-54d5f170=""><g data-v-54d5f170=""><path data-v-54d5f170="" d="M225.923,354.706c-8.098,0-16.195-3.092-22.369-9.263L9.27,151.157c-12.359-12.359-12.359-32.397,0-44.751   c12.354-12.354,32.388-12.354,44.748,0l171.905,171.915l171.906-171.909c12.359-12.354,32.391-12.354,44.744,0   c12.365,12.354,12.365,32.392,0,44.751L248.292,345.449C242.115,351.621,234.018,354.706,225.923,354.706z" data-original="#000000" data-old_color="#000000" fill="" class="active-path"></path></g></g></svg>
                        </a>
                        <div class="border create_event absolute z-40 w-40 right-0 bg-white" v-bind:class="[show_event_link=='action_'+props.row.id?'block':'hidden']">
                            <ul class="list-reset text-xs text-gray-700 leading-loose py-1">
                                <li class="px-2">
                                    <a :href=props.row.reporturl id="view" class="whitespace-no-wrap">View</a>
                                </li>
                                <li class="px-2">
                                    <a :href=props.row.downloadreporturl id="print">Print</a>
                                </li>
                                <li class="px-2">
                                    <a :href=props.row.sendreporturl id="send">Send</a>
                                </li>
                            </ul>
                        </div>
                    </div> -->    
                </div>
            </template>
        </vue-good-table>      
    </div>
</template>

<script>
    import { VueGoodTable } from 'vue-good-table-next'
    import 'vue-good-table-next/dist/vue-good-table-next.css'
    export default {
        props:['url','query'],
        components: {
            VueGoodTable,
        },
        data() {
            return{
                show_event_link:'',
                action:'',
                id:'',
                columns: [
                    {
                        label: 'Class',
                        field: 'class',
                    },
                    {
                        label: 'Exam Date',
                        field: 'exam_date',
                    },
                    {
                        label: 'Subject',
                        field: 'subject_name',
                    },
                    {
                        label: 'Portion',
                        field: 'portion',
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

        created()
        {
            this.getData();
        },

        methods: 
        {
            getData()
            {
                axios.get('/teacher/exam/show?'+this.query).then(response => {
                    console.log(response);
                    this.rows = response.data.data;
                });
            },

            getScheduleLink(id)
            {
                return 'href=id'
            },

            showeventlink(id)
            {
                let action='action_';
                this.show_event_link=action+id; 
            },
        },
    }
</script>