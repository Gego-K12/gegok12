<template>
    <div class="bg-white shadow px-4 py-3">
        <div>
            <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>

            <div class="my-5">
                <div class="">
                    <img :src="cover_image_display" class="img-responsive w-20 h-20 rounded-full">
                    <div class="w-full lg:w-1/4">              
                        <label class="tw-label cursor-pointer text-xs text-gray-600"> Change Cover Image
                            <input type="file" size="60" name="cover_image" id="cover_image" @change="OnImageSelected">
                            <span v-if="errors.cover_image" class="text-red-500 text-xs font-semibold">{{ errors.cover_image[0] }}</span>
                        </label> 
                    </div>
                </div>
            </div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="title" class="tw-form-label">Title<span class="text-red-500">*</span></label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <input type="text" name="title" id="title" v-model="title" class="tw-form-control w-full" placeholder="Title">
                            <span v-if="errors.title" class="text-red-500 text-xs font-semibold">{{ errors.title[0] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-5">
                <div class="tw-form-group w-full lg:w-3/4">
                    <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
                        <div class="mb-2 w-full lg:w-1/4 md:w-1/3">
                            <label for="status" class="tw-form-label">Active</label>
                        </div>
                        <div class="mb-2 w-full lg:w-1/2 md:w-2/3">
                            <input type="checkbox" v-model="status" v-bind:true-value="1"  name="status" class="tw-form-control">
                            <span v-if="errors.status" class="text-red-500 text-xs font-semibold">{{ errors.status[0] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="my-6">
                <a href="#" id="submit-btn" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm()">Submit</a>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['id','mode'],
        data(){
            return{
                room:[],
                title:'',
                cover_image:'',
                cover_image_display:'',
                status:'',
                errors:[],
                success:null,
            }
        },
        
        methods:
        {
            getData()
            {
                axios.get('/'+this.mode+'/room/showDetails/'+this.id).then(response => {
                    this.room= response.data.data[0]; 
                    this.setData();
                });
            },

            setData()
            {
                if(Object.keys(this.room).length > 0)
                {
                    this.title=this.room.title;                               
                    this.cover_image_display=this.room.cover_image;                               
                    this.status=this.room.status;                     
                }            
            },

            submitForm()
            {        
                this.errors=[];
                this.success=null; 

                let formData=new FormData();

                formData.append('title',this.title);                                  
                formData.append('cover_image',this.cover_image);                                  
                formData.append('status',this.status);                 
                                 
                axios.post('/'+this.mode+'/room/update/'+this.id,formData).then(response => {  
                    this.success = response.data.success;
                }).catch(error => {
                    this.errors = error.response.data.errors;
                });
            },

            OnImageSelected(event)
            {
                this.cover_image = event.target.files[0];
            },
        },

        created()
        {
            this.getData();
        }
    }
</script>