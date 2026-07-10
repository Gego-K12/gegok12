<template>
    <div>
        <div v-if="this.success!=null" class="alert alert-success" id="success-alert">{{this.success}}</div>
        <div class="bg-white shadow border-b-8 p-4" v-for="post in posts">
            <div class="">
            <div class="flex items-center justify-end relative">
                <div class="bg-white  flex items-center justify-center cursor-pointer ml-5">
                    <div class="relative flex items-center w-full justify-end">
                        <p class="text-gray-600 text-xs flex items-center">
                            <span class="px-2">
                                <!-- <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" width="512px" height="512px" viewBox="0 0 510 510" style="enable-background:new 0 0 510 510;" xml:space="preserve"><g><g><g id="access-time"><path d="M255,0C114.75,0,0,114.75,0,255s114.75,255,255,255s255-114.75,255-255S395.25,0,255,0z M255,459c-112.2,0-204-91.8-204-204S142.8,51,255,51s204,91.8,204,204S367.2,459,255,459z" data-original="#000000" class="active-path" data-old_color="fill-opacity:0.9" fill="#718096"/><polygon points="267.75,127.5 229.5,127.5 229.5,280.5 362.1,362.1 382.5,328.95 267.75,260.1" data-original="#000000" class="active-path" data-old_color="fill-opacity:0.9" fill="#718096"/></g></g></g></svg> -->
                                <svg class="w-4 h-4 fill-current text-gray-600" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 60 60" style="enable-background:new 0 0 60 60;" xml:space="preserve"><g><path d="M30,0C13.458,0,0,13.458,0,30s13.458,30,30,30s30-13.458,30-30S46.542,0,30,0z M30,58C14.561,58,2,45.439,2,30 S14.561,2,30,2s28,12.561,28,28S45.439,58,30,58z"></path><path d="M30,6c-0.552,0-1,0.447-1,1v23H14c-0.552,0-1,0.447-1,1s0.448,1,1,1h16c0.552,0,1-0.447,1-1V7C31,6.447,30.552,6,30,6z"></path></g></svg>
                            </span>{{ post.post_created_at }}
                        </p>
                    </div>
                </div>
                <div class="bg-white flex items-center justify-center cursor-pointer ml-5">
                    <div class="relative flex items-center w-full justify-end">
                        <p class="text-gray-600 text-sm flex items-center">
                            <span class="px-2">
                               <!--  <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 511.999 511.999" style="enable-background:new 0 0 511.999 511.999;" xml:space="preserve" width="512px" height="512px"><g><g> <g><path d="M508.745,246.041c-4.574-6.257-113.557-153.206-252.748-153.206S7.818,239.784,3.249,246.035c-4.332,5.936-4.332,13.987,0,19.923c4.569,6.257,113.557,153.206,252.748,153.206s248.174-146.95,252.748-153.201 C513.083,260.028,513.083,251.971,508.745,246.041z M255.997,385.406c-102.529,0-191.33-97.533-217.617-129.418c26.253-31.913,114.868-129.395,217.617-129.395c102.524,0,191.319,97.516,217.617,129.418    C447.361,287.923,358.746,385.406,255.997,385.406z" data-original="#000000" class="active-path" fill="#fba33a"/></g></g><g><g><path d="M255.997,154.725c-55.842,0-101.275,45.433-101.275,101.275s45.433,101.275,101.275,101.275    s101.275-45.433,101.275-101.275S311.839,154.725,255.997,154.725z M255.997,323.516c-37.23,0-67.516-30.287-67.516-67.516 s30.287-67.516,67.516-67.516s67.516,30.287,67.516,67.516S293.227,323.516,255.997,323.516z" data-original="#000000" class="active-path" fill="#fba33a"/></g></g></g></svg> -->
                               <svg class="w-5 h-5 fill-current text-yellow-500" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 511.992 511.992" style="enable-background:new 0 0 511.992 511.992;" xml:space="preserve"><g><g><path d="M510.096,249.937c-4.032-5.867-100.928-143.275-254.101-143.275C124.56,106.662,7.44,243.281,2.512,249.105 c-3.349,3.968-3.349,9.792,0,13.781C7.44,268.71,124.56,405.329,255.995,405.329S504.549,268.71,509.477,262.886 C512.571,259.217,512.848,253.905,510.096,249.937z M255.995,383.996c-105.365,0-205.547-100.48-230.997-128 c25.408-27.541,125.483-128,230.997-128c123.285,0,210.304,100.331,231.552,127.424 C463.013,282.065,362.256,383.996,255.995,383.996z"/></g></g><g><g><path d="M255.995,170.662c-47.061,0-85.333,38.272-85.333,85.333s38.272,85.333,85.333,85.333s85.333-38.272,85.333-85.333 S303.056,170.662,255.995,170.662z M255.995,319.996c-35.285,0-64-28.715-64-64s28.715-64,64-64s64,28.715,64,64 S291.28,319.996,255.995,319.996z"/></g></g></svg>
                            </span>{{ post.visibility }}
                        </p>
                    </div>
                </div>
                <div @click="showsidebar(post.id)" class="bg-white rounded-full flex items-center justify-center cursor-pointer ml-5">
                    <svg id="Capa_1" enable-background="new 0 0 515.555 515.555" height="512" viewBox="0 0 515.555 515.555" width="512" xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 fill-current text-gray-600"><path d="m303.347 18.875c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138c25.166-25.167 65.97-25.167 91.138 0"></path><path d="m303.347 212.209c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138c25.166-25.167 65.97-25.167 91.138 0"></path><path d="m303.347 405.541c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138c25.166-25.167 65.97-25.167 91.138 0"></path></svg>
                </div>
                <div :id="post.id+'_post'" class="hidden absolute right-0 bg-white shadow top-0 rounded my-5">
                    <div class="flex flex-col text-xs w-20 my-1">
                        <a :href="url+'/'+mode+'/classwall/post/show/'+post.id" class="capitalize text-gray-700 rounded px-4 py-1 mr-2 font-medium" target="_blank">view</a>
                        <a :href="url+'/'+mode+'/classwall/post/edit/'+post.id" class="capitalize text-gray-700 rounded px-4 py-1 mr-2 font-medium" target="_blank" v-if="auth_id == post.created_by">edit</a>
                        <a href="#" @click="deletePost(post.id)" class="capitalize text-gray-700 rounded px-4 py-1 mr-2 font-medium" v-if="auth_id == post.created_by">delete</a>
                    </div>
                </div>
                </div>
                <div class="flex text-xs justify-between flex-col lg:flex-row w-11/12">
                    <div class="py-2 lg:py-2">
                        <p class="text-gray-800 text-sm" v-html="post.description"></p>
                    </div>   
                </div>

            </div>
            <div class="flex flex-wrap" v-if="post.attachments != null">
                <div class="w-full lg:w-1/3 md:w-1/3 my-2 relative" v-for="attachment in post.attachments">  
                    <img :src="attachment.path" class="w-40 h-40">
                </div>
            </div>
        </div>
        <div class="bg-white shadow border-b-8 p-4" v-if="Object.keys(posts).length == 0">
            <p class="flex items-center">No Records Found</p>
        </div>
    </div>
</template>

<script>
    export default{
        props:['url' , 'entity_id' , 'entity_name' , 'mode' , 'auth_id' , 'id' , 'hidecolumns' , 'type'],
        data(){
            return{
                posts:[],
                errors:[],
                success:null,
            }
        },
        methods:
        {
            getData(link)
            {
                axios.get(link).then(response => {
                    this.posts = response.data.data;
                    //console.log(this.posts)
                });
            },

            deletePost(id)
            {
                var thisswal = this;
                swal({
                    title: 'Are you sure',
                    text: 'Do you want to delete this Post ?',
                    icon: "info",
                    buttons: [
                      'No',
                      'Yes'
                    ],
                    dangerMode: true,
                  }).then(function(isConfirm) {
                    if (isConfirm) 
                    {
                      axios.delete(thisswal.url+'/'+thisswal.mode+'/classwall/post/delete/'+ id).then(response => {
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

            showsidebar(id)
            {
                if($('#'+id+'_post').hasClass('hidden'))
                {
                  $('#'+id+'_post').removeClass('hidden').addClass('block');
                }
                else
                {
                  $('#'+id+'_post').removeClass('block').addClass('hidden');
                }
            }
        },
        created()
        {
            if( (this.hidecolumns == true) || (this.hidecolumns == 'true') )
            {
                this.getData(this.url+'/'+this.mode+'/standardLink/show/classwall/'+this.id);
            } 
            else if( (this.hidecolumns == false) || (this.hidecolumns == 'false') )
            {
                if(this.type == 'page')
                {
                    this.getData(this.url+'/'+this.mode+'/classwall/post/list?entity_id='+this.entity_id+'&entity_name='+this.entity_name);
                }
                else
                {
                    this.getData(this.url+'/'+this.mode+'/classwall/post/list');
                }
            }
        }
    }
</script>