<template>
  <div>
    <vue-good-table
  :columns="columns"
  :rows="rows"
  :search-options="{
    enabled: true,
    placeholder: 'Search this table',
  }"
/>
  
 
 
 
     
  </div>
</template>

<script>
import { VueGoodTable } from 'vue-good-table-next'
import 'vue-good-table-next/dist/vue-good-table-next.css'
export default {
 components: {
  VueGoodTable,
},
  data(){
    return {
      schedule:'',
      first_rows:[],
      errors:[],
      success:null,
      columns: [
        {
          label: 'Exam',
          field: 'exam_name',

        },
        {
          label: 'Class',
          field: 'standard_id',
          type:'number',
        },
        {
          label: 'Start Time',
          field: 'start_time',
          type: 'date',
          dateInputFormat: 'yyyy-MM-dd HH:mm:ss',
          dateOutputFormat: 'dd-MM-yyyy HH:mm:ss',
        },
        {
          label: 'Subject',
          field: 'subject_name',
        },

        {
          label: 'Total Marks',
          field: 'total_marks',
        },

       /* {
          label: 'Age',
          field: 'start_time',
        },*/
      ],
      rows:[
       /* { id:1, name:"fdgvbxc", class: 1, start_time: '2011-10-31',subject: "Tamil" },
        { id:2, name:"first-mid-term", class: 1, start_time: '2011-10-31', subject:"English" },
        { id:3, name:"first-mid-term", class: 1, start_time: '2011-10-30', subject: "Mathematics" },
        { id:4, name:"Quarterly", class: 1, start_time: '2011-10-11', subject:"Science" },
        { id:5, name:"Quarterly", class: 2, start_time: '2011-10-21', subject: "Social" },*/
       
        
        
      ],

    };
  },
 methods:{
   getData()
    {
      axios.get('/admin/examschedule/show').then(response => {
        //this.schedule    = response.data.data;
        this.rows=response.data.data;
        //console.log(this.rows);    
        //console.log(this.hidecolumns);    
      });
    },

  },

 created()
  {   
    this.getData();
  }
};
</script>