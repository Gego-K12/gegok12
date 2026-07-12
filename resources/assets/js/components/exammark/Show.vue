<template>
    <div class="">
        <div>
            <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
            <div class="flex flex-wrap custom-table my-3 overflow-auto">
                <table class="w-full">
                    <thead class="bg-grey-light">
                        <tr class="border-b">
                            <th class="text-left text-sm px-2 py-2 text-grey-darker"> Student Name</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker"> Exam</th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker"> Subject </th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker"> Mark </th>
                            <th class="text-left text-sm px-2 py-2 text-grey-darker"> Grade/Status </th>
                        </tr>
                    </thead>   
                    <tbody v-if="Object.keys(this.mark).length > 0">
                        <tr class="border-b" v-for="marks in mark">
                            <td class="py-3 px-2">
                                <p class="font-semibold text-xs">{{ marks['student_name'] }}</p>
                            </td>
                            <td class="py-3 px-2">
                                <p class="font-semibold text-xs">{{ marks['exam_name']}}</p>
                            </td>
                            <td class="py-3 px-2">
                                <p class="font-semibold text-xs">{{ marks['subject_name'] }}</p>
                            </td>
                            <td class="py-3 px-2">
                                <p class="font-semibold text-xs">{{ marks['obtained_marks'] }}</p>
                            </td>
                             <td class="py-3 px-2">
                                <p class="font-semibold text-xs">{{ marks['mark_status'] }}</p>
                            </td>
                        </tr>
                    </tbody>
                    <tbody v-else>
                        <tr class="border-b">
                            <td colspan="4">
                                <p class="font-semibold text-s" style="text-align: center">No Records Found</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['url','id','mode'],
        data(){
            return{
                mark:[],
                errors:[],
                success:null,
            }
        },
        
        methods:
        {   
            getData()
            {
                axios.get('/'+this.mode+'/exammarks/view/'+this.id).then(response => {
                    this.mark=response.data.data;
                    //console.log(this.mark);
                });
            },
        },
        created()
        {
            this.getData();
        }
    }
</script>