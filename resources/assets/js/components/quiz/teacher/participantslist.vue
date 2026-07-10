<template>
  <div class="px-3 overflow-x-scroll lg:overflow-x-auto md:overflow-x-auto py-3" v-bind:class="[this.quiz_tab==2?'block' :'hidden']">
    <div class="relative">
      <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
      <div class="flex flex-wrap lg:flex-row justify-between items-center my-1">
        <div class="">
          <h1 class="admin-h1">Participants</h1>
        </div>
        <div class="relative flex items-center w-8/12 lg:w-1/4 md:w-1/4 justify-end">
          <div class="flex items-center"></div>
        </div>
      </div>
      <div class="">
        <div class="flex flex-wrap custom-table my-3 overflow-auto">
          <table class="w-full">
            <thead class="bg-grey-light">
              <tr class="border-b">
                <th class="text-left text-sm px-2 py-2 text-grey-darker"> No </th>
                <th class="text-left text-sm px-2 py-2 text-grey-darker"> Name </th>
                <th class="text-left text-sm px-2 py-2 text-grey-darker"> Score </th>
                <th class="text-left text-sm px-2 py-2 text-grey-darker"> Attempt at </th>
                <th class="text-left text-sm px-2 py-2 text-grey-darker"> Action </th>
              </tr>
            </thead>   
            <tbody v-if="this.users != ''">
              <tr class="border-b" v-for="(user,k1) in users" :key="k=k1+1"   >
                <td class="py-3 px-2">
                  <p class="font-semibold text-xs">{{k}}</p>
                </td>
                <td class="py-3 px-2">
                  <p class="font-semibold text-xs">{{user.name}}</p>
                </td>
                 <td class="py-3 px-2">
                  <p v-if="user.score!=null" class="font-semibold text-xs">{{user.score}}</p>
                  <p v-else="" class="font-semibold text-xs">Incomplete</p>
                </td>
                <td class="py-3 px-2">
                  <p class="font-semibold text-xs">{{user.attempt_at}}</p>
                </td>
                <td class="py-3 px-2">
                  <a :href="url+'/teacher/quiz/test/'+user.id+'/details'" title="Show">
                    <svg height="512pt" viewBox="-27 0 512 512" width="512pt" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-current text-black-500 mx-2"><path d="m188 492c0 11.046875-8.953125 20-20 20h-88c-44.113281 0-80-35.886719-80-80v-352c0-44.113281 35.886719-80 80-80h245.890625c44.109375 0 80 35.886719 80 80v191c0 11.046875-8.957031 20-20 20-11.046875 0-20-8.953125-20-20v-191c0-22.054688-17.945313-40-40-40h-245.890625c-22.054688 0-40 17.945312-40 40v352c0 22.054688 17.945312 40 40 40h88c11.046875 0 20 8.953125 20 20zm117.890625-372h-206c-11.046875 0-20 8.953125-20 20s8.953125 20 20 20h206c11.042969 0 20-8.953125 20-20s-8.957031-20-20-20zm20 100c0-11.046875-8.957031-20-20-20h-206c-11.046875 0-20 8.953125-20 20s8.953125 20 20 20h206c11.042969 0 20-8.953125 20-20zm-226 60c-11.046875 0-20 8.953125-20 20s8.953125 20 20 20h105.109375c11.046875 0 20-8.953125 20-20s-8.953125-20-20-20zm355.472656 146.496094c-.703125 1.003906-3.113281 4.414062-4.609375 6.300781-6.699218 8.425781-22.378906 28.148437-44.195312 45.558594-27.972656 22.324219-56.757813 33.644531-85.558594 33.644531s-57.585938-11.320312-85.558594-33.644531c-21.816406-17.410157-37.496094-37.136719-44.191406-45.558594-1.5-1.886719-3.910156-5.300781-4.613281-6.300781-4.847657-6.898438-4.847657-16.097656 0-22.996094.703125-1 3.113281-4.414062 4.613281-6.300781 6.695312-8.421875 22.375-28.144531 44.191406-45.554688 27.972656-22.324219 56.757813-33.644531 85.558594-33.644531s57.585938 11.320312 85.558594 33.644531c21.816406 17.410157 37.496094 37.136719 44.191406 45.558594 1.5 1.886719 3.910156 5.300781 4.613281 6.300781 4.847657 6.898438 4.847657 16.09375 0 22.992188zm-41.71875-11.496094c-31.800781-37.832031-62.9375-57-92.644531-57-29.703125 0-60.84375 19.164062-92.644531 57 31.800781 37.832031 62.9375 57 92.644531 57s60.84375-19.164062 92.644531-57zm-91.644531-38c-20.988281 0-38 17.011719-38 38s17.011719 38 38 38 38-17.011719 38-38-17.011719-38-38-38zm0 0"></path></svg>
                  </a>
                </td>
              </tr>
            </tbody>
            <tbody v-else="">
              <tr class="border-b">
                <td colspan="8">
                  <p class="font-semibold text-s" style="text-align: center">No Records Found</p>
                </td>
              </tr>
            </tbody>
          </table>
          <paginate
            v-model="page"
            :page-count="this.page_count"
            :page-range="3"
            :margin-pages="1"
            :click-handler="getlist"
            :prev-text="'<'"
            :next-text="'>'"
            :container-class="'pagination'"
            :page-class="'page-item'"
            :prev-link-class="'prev'"
            :next-link-class="'next'">
          </paginate>
        </div>
      </div>   
    </div>
  </div>
</template>

<script>
  import { bus } from "../../../app";

  export default {
    props:['url','topic'],
    data () {
      return {
        page_count:0,
        page:0,
        total :'',
        quiz_tab:'',
        users:[],
        errors:[],
        success:null,
      }
    },

    methods:
    {
      getlist()
      {
        axios.get('/teacher/quiz/'+this.topic+'/participants?page='+this.page).then(response => {
          this.users    = response.data.data;
          this.page_count = response.data.meta.last_page;
          this.total = response.data.meta.total;
          //console.log(response.data.data);    
        });
      },
    },
  
    created()
    {   
      this.getlist();
      bus.$on("dataQuizTab", data => {
        if(data!='')
        {
          this.quiz_tab=data;                   
        }
      });  
    }
  }
</script>