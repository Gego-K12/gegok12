<template>
    <div class="bg-white shadow px-3 py-3">

          <!-- teacher list -->
                <div class="my-8">
        

         <div class="flex flex-wrap">
                    <div class="w-full lg:w-1/2 md:w-1/2 my-2 relative" v-for="user,k1 in examoptions">
                        <!-- list -->
                        <div class="flex items-center member-list">
                            <div class="flex items-center student_select">
                                <input class="absolute w-5 h-5" type="checkbox"  :value="user.status" @click="updatestatus(k1,$event)" >
                                <label></label>
                            </div>
                            <div class="flex p-2 w-full active">
                                <div class="px-2">
                                     <h2 class="font-bold text-base text-gray-700">{{user['name']}}</h2>
                                </div>
                            </div>
                            <div v-if="user['status']=='present'" class="flex p-2 w-full active">
                                <div class="px-2">
                                   <input type="number" name="marks" min="0" max ="200" maxlength="4" v-model="user.mark" class="tw-form-control w-full" placeholder="Marks">
                                </div>
                                 <span v-if="errors['mark'+k1]" class="text-red-500 text-xs font-semibold whitespace-no-wrap">{{errors['mark'+k1][0]}}</span>
                            </div>
                            <div v-else="" class="flex p-2 w-full active">
                               <button class="bg-red-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full">Absent</button>
                            </div>
                        </div>
                        <!-- list -->
                    

                    </div>
                </div>

    <div class="py-3">
      <a href="#" dusk="submit-btn" class="btn btn-primary submit-btn" @click="submitmark()">Submit</a>
    </div>

      </div>
       
    </div>
</template>


<script>
    export default {
        props:['url','id'],
        data(){
            return{
               
                users:[],
                 examoptions: [],
                errors:[],
                success:null,
            }
        },
        
        methods:
        {   
            
            getData()
            {
                axios.get('/teacher/exam/marks/list/'+this.id).then(response => {
                    this.users = response.data.students;
                    this.schedule=response.data.schedule;
                     for(var i=0 ; i < this.users.length ; i++)
                        {
                          this.examoptions.push({
                            name:this.users[i].name,
                            user_id:this.users[i].id,
                            mark:'',
                            status:'present'
                          });
                        }
                    console.log(this.users);    
                });
            },

            updatestatus(index,e)
            {
                if (e.target.checked) 
                {
                 this.examoptions[index].status='absent';
                 this.examoptions[index].mark='0';
                }
                else
                {
                  this.examoptions[index].status='present'; 
                }
            },

            submitmark()
            {
              this.errors=[];
              this.success=null;

              let formData=new FormData(); 

              formData.append('examoptions',this.examoptions);
              formData.append('count',this.examoptions.length);

              for(let i=0; i<this.examoptions.length;i++)
              {
                if(typeof this.examoptions[i]['user_id'] !== "undefined")
                {
                  formData.append('user_id'+i,this.examoptions[i]['user_id']);
                }
                else
                {
                  formData.append('user_id'+i,'');
                }

                if(typeof this.examoptions[i]['mark'] !== "undefined")
                {
                  formData.append('mark'+i,this.examoptions[i]['mark']);
                }
                else
                {
                  formData.append('mark'+i,'');
                }

                if(typeof this.examoptions[i]['status'] !== "undefined")
                {
                  formData.append('status'+i,this.examoptions[i]['status']);
                }
                else
                {
                  formData.append('status'+i,'');
                }
              }


              axios.post('/teacher/exam/marks/add/'+this.id,formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                  this.success = response.data.success; 
                  //this.show = 0;
                  window.location.href="/teacher/exam";
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