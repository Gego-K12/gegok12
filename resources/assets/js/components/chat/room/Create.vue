<template>
    <div class="bg-white shadow border px-4 py-4">
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{ this.success }}</div>
    <flash-message :position="'right bottom'" :timeout="3000" class="myCustomClass"></flash-message>
        <div class="my-5">
            <div class="">
                <div class="w-full lg:w-1/4">
                    <label for="title" class="tw-form-label">Title<span class="text-red-500">*</span></label>
                </div>
                <div class="w-full lg:w-2/5 my-2">
                    <input type="text" name="title" v-model="title" id="title" class="tw-form-control w-full" placeholder="Title">
                </div>
                <span v-if="errors.title" class="text-red-500 text-xs font-semibold">{{ errors.title[0] }}</span>
            </div>
        </div>

        <div class="my-5">
            <div class="">
                <div class="w-full lg:w-1/4">
                    <label for="cover_image" class="tw-form-label">Cover Image<span class="text-red-500">*</span></label>
                </div>
                <div class="w-full lg:w-2/5 my-2">
                    <input type="file" name="cover_image" @change="OnImageSelected" id="cover_image" class="tw-form-control w-full">
                </div>
                <span v-if="errors.cover_image" class="text-red-500 text-xs font-semibold">{{ errors.cover_image[0] }}</span>
            </div>
        </div>

        <div class="my-5">
            <div class="">
                <div class="w-full lg:w-1/4">
                    <label for="status" class="tw-form-label">Active</label>
                </div>
                <div class="w-full lg:w-2/5 my-2">
                    <input type="checkbox" v-model="status" v-bind:true-value="1"  name="status" class="tw-form-control">
                    <span v-if="errors.status" class="text-red-500 text-xs font-semibold">{{ errors.status[0] }}</span>
                </div>
            </div>
        </div>
      
        <div class="my-6">
            <a href="#" id="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm()">Submit</a>
            <a href="#" class="btn btn-reset bg-gray-100 text-gray-700 border rounded px-3 py-1 mr-3 text-sm font-medium" @click="resetForm()">Reset</a>
        </div>
    </div>
</template>

<script>
    import 'vue-flash-message/dist/vue-flash-message.min.css';
    import VueFlashMessage from 'vue-flash-message';
    Vue.use(VueFlashMessage);
    export default {
        props:[],
        data(){
            return{
                room:[],
                title:'',
                cover_image:'',
                status:0,
                errors:[],
                success:null,
            }
        },
        
        methods:
        {
            resetForm()
            {
                this.cover_image='';
                this.status=0;
                this.title='';
                //window.location.reload();  
            }, 

            submitForm()
            {
                this.errors=[];
                this.success=null; 

                let formData=new FormData();
           
                formData.append('title',this.title);         
                formData.append('cover_image',this.cover_image);          
                formData.append('status',this.status);          
                      
                axios.post('/admin/chat/room/add',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                    this.success = response.data.success;
                    this.resetForm();
                    this.flash('Chat Room Created Successfully 👍','success',{timeout: 8000,
                    beforeDestroy() {
                    window.location.href = "/admin/chat";
                    }});
                    window.location.href = "/admin/chat";
                    //this.resetForm();
                }).catch(error => {
                    this.errors = error.response.data.errors;
                    this.flash('Please fill all fields ☹','error',{timeout: 3000});
                });
            },

            OnImageSelected(event)
            {
                this.cover_image = event.target.files[0];
            },
        },

        created()
        {
            //
        }
    }
</script>
<style scoped>
  .myCustomClass {
     margin-top:10px;
     bottom:0px;
     right:0px;
     position: fixed;
     z-index: 40;
}
</style>