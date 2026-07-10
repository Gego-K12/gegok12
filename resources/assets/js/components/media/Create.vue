<template>
    <div>
       <!-- loading screen -->
       <loading v-model:active="isLoading"
        :can-cancel="true"
        :is-full-page="fullPage"></loading>
        <!-- loading screen -->

         <!--radio button-->

   <div class="my-5">
          <div class="tw-form-group w-full lg:w-3/4 md:w-3/4">
            <div class="lg:mr-8 md:mr-8 flex flex-col lg:fle-row md:flex-row lg:items-center md:items-center w-full">
              <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                <label for="type" class="tw-form-label">Type</label>
              </div>
              <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
                <select class="tw-form-control w-full" id="media" v-model="media" name="media">
                  <option value="" disabled>Select Category</option>
                  <option value="media_upload">Media</option>
                  <option value="study_material">Study Material</option>
                   <option value="value_education">Value education</option>
                </select>
                <span v-if="errors.media" class="text-red-500 text-xs font-semibold">{{errors.media[0]}}</span>
              </div>     
            </div> 
          </div>
      </div>  

  <!--radio button-->
<div v-show="this.media!='media_upload' && this.media!='' " class="" id="study_material">
      <div class="my-5">
        <div class="tw-form-group w-full lg:w-3/4 md:w-3/4">
          <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row mf:flex-row lg:items-center md:items-center w-full">
            <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
              <label for="standardLink_id" class="tw-form-label">Select Class</label>
            </div>
            <div class="mb-2 w-full lg:w-1/2 md:w-1/2">
              <select name="standardLink_id" id="standardLink_id" v-model="standardLink_id" class="tw-form-control w-full">
                <option value="" disabled>Select Class</option>
                <option v-for="standardLink in standardLinklist" v-bind:value="standardLink.id">{{ standardLink.standard_name }} - {{ standardLink.section_name }}</option>
              </select>
               <span v-if="errors.standardLink_id" class="text-red-500 text-xs font-semibold">{{errors.standardLink_id[0]}}</span>
            </div>
          </div>
        </div>
      </div>
</div>


      <div class="my-5">
        <div class="tw-form-group w-full lg:w-3/4 md-3/4">
          <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
            <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
              <label for="title" class="tw-form-label">Title</label>
            </div>
            <div class="mb-2 w-full lg:w-1/2 md:w-1/2">
              <input type="text" name="title"  v-model="title" class="tw-form-control w-full">
              <span v-if="errors.name" class="text-red-500 text-xs font-semibold">{{errors.name[0]}}</span>
            </div>
          </div>
        </div>
      </div>



      <div class="my-5">
        <div class="tw-form-group w-full lg:w-3/4 md:w-3/4">
          <div class="lg:mr-8 md:mr-8 flex flex-col lg:flex-row md:flex-row lg:items-center md:items-center w-full">
            <div class="mb-2 w-full lg:w-1/4 md:w-1/4">
              <label for="description" class="tw-form-label">Description</label>
            </div>
            <div class="mb-2 w-full lg:w-1/2 md:w-1/2">
              <textarea type="text" name="description"  v-model="description" class="tw-form-control w-full" rows="3"></textarea>
              <span v-if="errors.description" class="text-red-500 text-xs font-semibold">{{errors.description[0]}}</span>
            </div>
          </div>
        </div>
      </div>

                            <div>
                                <div class="mb-2">
                                    <label for="type" class="tw-form-label">Media Type<span class="text-red-500">*</span></label>
                                </div>
                                <div class="flex">
                                    <div class="w-48 flex items-center py-2"> 
                                        <input type="radio" v-model="type" id="image" value="image" >
                                        <span class="text-sm mx-1">Image</span>
                                    </div>
                                    <div class="w-48 flex items-center py-2"> 
                                        <input type="radio" v-model="type" id="audio" value="audio" >
                                        <span class="text-sm mx-1">Audio</span>
                                    </div>
                                    <div class="w-48 flex items-center py-2"> 
                                        <input type="radio" v-model="type" id="video" value="video" >
                                        <span class="text-sm mx-1">Video</span>
                                    </div>
                                </div>
                                <span class="text-danger text-xs"></span>
                            </div>

<!-- image upload -->
<div v-show="this.type=='image'">
        <div class="flex flex-col lg:flex-row w-full lg:w-full">
                <div class="tw-form-group w-full lg:w-full">
                    <div class="lg:mr-8 md:mr-8">
                        <div class="mb-2">
                            <label for="attachment" class="tw-form-label">Images</label>
                        </div>
                        <div class="mb-2">
                            <file-pond
                                ref="myFilePond"
                                name="file"
                                allow-multiple
                                :max-files="10"
                                :instant-upload="false"
                                accepted-file-types="image/jpeg,image/png"
                                @updatefiles="handleImageFiles"
                            />
                            <a href="#" class="btn btn-reset reset-btn" @click="removeAllFiles()">Remove All Files</a>
                        </div>
                        <span v-if="errors.images" class="text-red-500 text-xs font-semibold">{{ errors.images[0] }}</span>
                    </div> 
                </div>
            </div>

            <div class="my-6">
                <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitForm()">Submit</a>
            </div>
</div>
<!-- image upload -->


<!-- Audio upload -->
<div v-show="this.type=='audio'">
   <div class="w-full lg:w-2/5 md:w-2/5 lg:mr-8 md:mr-8" >
                            <div>
                                <div class="my-3">
                                    <div class="w-full lg:w-1/4">
                                        <label class="tw-form-label">Audio Type<span class="text-red-500">*</span></label>
                                    </div>
                                </div>
                                <div class="flex">
                                    <div class="w-1/2 lg:w-1/2 md:w-1/2 flex items-center tw-form-control mr-1 lg:mr-8 md:mr-8"> 
                                        <input type="radio" v-model="audio_type"  value="attach" >
                                        <span class="text-sm mx-1">Attach</span>
                                    </div>
                                    <div class="w-1/2 lg:w-1/2 md:w-1/2 flex items-center tw-form-control"> 
                                        <input type="radio" v-model="audio_type"  value="record" >
                                        <span class="text-sm mx-1">Record</span>
                                    </div>
                                </div>
                            <span class="text-danger text-xs"></span>
                            </div>

                            <div v-show="this.audio_type=='attach'">
                                <div class="my-6">
                                    <div class="w-full lg:w-1/4">
                                        <label class="tw-form-label">Upload Audio<span class="text-red-500">*</span></label>
                                   <uploader :options="audiooptions" ref="audiouploader" @file-complete="AudiofileComplete"  class="uploader-example" id="uploadaudio" name="uploadaudio" type="file">
                                    <uploader-unsupport></uploader-unsupport>
                                    <uploader-drop>
                                    <uploader-btn :single="single" :attrs="audioattrs">Select File</uploader-btn>
                                    </uploader-drop>
                                    <uploader-list></uploader-list>
                                </uploader>     
                                    </div>
                                    <span v-if="errors.audios" class="text-red-500 text-xs font-semibold">{{ errors.audios[0] }}</span>
                                </div>
                            </div>
                          <div v-show="this.audio_type=='record'">
                            <div class="my-6">
                            <audio-recorder
                                    upload-url="this.api_url"
                                    :attempts="1"
                                    :time="10"
                                    :headers="this.record_header"
                                    :show-upload-button="false"
                                    :before-recording="callback"
                                    :pause-recording="callback"
                                    :after-recording="recordfile"
                                    :before-upload="bfupload"
                                    :successful-upload="callback"
                                    :failed-upload="callback"/>
                                    <span v-if="errors.recordingfile" class="text-red-500 text-xs font-semibold">{{ errors.recordingfile[0] }}</span>
                                  </div>
                                </div>

                        </div> 
        <div class="my-6">
                <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitaudio()">Submit</a>
            </div>
</div>
<!-- Audio upload -->


<!-- video upload -->
<div v-show="this.type=='video'">
     <div class="w-full lg:w-2/5 md:w-2/5 lg:mr-8 md:mr-8  my-3 " >
                            <div class="my-3">
                                <div class="w-full lg:w-1/4">
                                    <label class="tw-form-label">Video Type<span class="text-red-500">*</span></label>
                                </div>
                            </div>
                            <div class="flex">
                                <div class="w-1/2 lg:w-1/2 md:w-1/2 flex items-center tw-form-control mr-1 lg:mr-8 md:mr-8"> 
                                    <input type="radio" v-model="video_type"  value="url">
                                    <span class="text-sm mx-1">Url</span>
                                </div>
                                <div class="w-1/2 lg:w-1/2 md:w-1/2 flex items-center tw-form-control"> 
                                    <input type="radio" v-model="video_type" value="upload" >
                                    <span class="text-sm mx-1">Upload</span>
                                </div>
                            </div>
                            <span class="text-danger text-xs"></span>
                            <div class="" v-if="this.video_type=='url'">
                                <div class="my-6">
                                    <div class="w-full lg:w-1/4">
                                        <label class="tw-form-label">URL<span class="text-red-500">*</span></label>
                                    </div>
                                    <div class="my-2 w-full lg:w-2/5 flex flex-col">
                                        <input type="text" v-model="video_url"  class="tw-form-control w-full" >
                                        <span v-if="errors.url" class="text-red-500 text-xs font-semibold">{{errors.url[0]}}</span>
                                    </div>
                                </div>
                            </div>

        <div class="my-6" v-if="this.video_type!='url'">
            <div class="w-full lg:w-1/4 md:w-1/3">
                <label class="tw-form-label">Upload Videos<span class="text-red-500">*</span></label>
            </div>
            <uploader :options="options" ref="uploader" @file-complete="fileComplete"  class="uploader-example" id="uploadvideo" name="uploadvideo" type="file">
                <uploader-unsupport></uploader-unsupport>
                <uploader-drop>
                <uploader-btn :single="single" :attrs="attrs">Select File</uploader-btn>
                </uploader-drop>
                <uploader-list></uploader-list>
            </uploader>
        </div>
         <span v-if="errors.videos" class="text-red-500 text-xs font-semibold">{{errors.videos[0]}}</span>
        <div class="my-6">
                <a href="#" class="btn btn-submit blue-bg text-white rounded px-3 py-1 mr-3 text-sm font-medium" @click="submitvideo">Submit</a>
            </div>
</div>
<!-- video upload -->

        </div>
    </div>
</template>

<script>
    import Loading from 'vue-loading-overlay';
    import 'vue-loading-overlay/dist/css/index.css';
    import vueFilePond from 'vue-filepond'
    import FilePondPluginImagePreview from 'filepond-plugin-image-preview'
    import 'filepond/dist/filepond.min.css'
    import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css'

    const FilePond = vueFilePond(FilePondPluginImagePreview)

    export default {
        props:['url' , 'csrf' ],
        components:{
            FilePond, Loading
        },
        data () {
            return {
                errors:[],
                success:null,
                isLoading: false,
                fullPage: true, 
                //video
                options: {
                    // https://github.com/simple-uploader/Uploader/tree/develop/samples/Node.js
                    chunkSize: 1000  * 1024 * 1024,
                    target: this.url+'/admin/media/storevideos',
                    testChunks: false,
                    headers:{ 'X-CSRF-TOKEN': this.csrf },
                    multiple:false,
                    //query :{status:"hjhj"},
                },
                attrs:
                {
                    accept:'video/*',
                },
                single:true,
                video_type:'url',
                video_url:'',
                videos:'',
                //video
                //audio
                audiooptions: {
                    // https://github.com/simple-uploader/Uploader/tree/develop/samples/Node.js
                    chunkSize: 1000  * 1024 * 1024,
                    target: this.url+'/admin/media/storeaudios',
                    testChunks: false,
                    headers:{ 'X-CSRF-TOKEN': this.csrf },
                    multiple:false,
                    //query :{status:"hjhj"},
                },
                audioattrs:
                {
                    accept:'audio/*',
                },
                api_url:this.url+'/admin/media/audio/record',
                record_header:{ 'X-CSRF-TOKEN': this.csrf },
                audio_type:'attach',
                audios:'',
                recordingfile:'',
                //audio
                images: [],
                submitUrl:'',
                 standardLinklist:[],
                 title:'',
                 description:'',
                 standardLink_id:'',
                 media:'',
                 type:'image',
            }
        },

        methods:
        {
          getList()
            {
              axios.get('/admin/videos/list').then(response => {
                this.standardLinklist = response.data.data;
               // console.log(response.data.data)
              })
            },          
            handleImageFiles(fileItems)
            {
                this.images = fileItems.map(item => item.file);
            },
            callback()
            {
                   navigator.mediaDevices.getUserMedia({audio: true, video:false});
            },
            recordfile(data)
            {
                //console.log(data);
                //console.log('audio complete', arguments)
                this.recordingfile=arguments[0].blob;
                /*console.log('audio complete', data)
                this.recordingfile=data.blob;
                 console.log(recordingfile);*/
            },

            submitForm()
            {
              this.errors=[];
              this.success=null;
              this.isLoading=true;

              let formData=new FormData();

              formData.append('standardLink_id',this.standardLink_id);
              formData.append('name',this.title);
              formData.append('media',this.media);
              formData.append('type',this.type);
              formData.append('description',this.description);
              formData.append('images',this.images.length);

              axios.post('/admin/image/validation',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
                let uploadData = new FormData();
                uploadData.append('standardLink_id',this.standardLink_id);
                uploadData.append('name',this.title);
                uploadData.append('media',this.media);
                uploadData.append('type',this.type);
                uploadData.append('description',this.description);
                this.images.forEach(file => {
                    uploadData.append('file[]', file);
                });

                axios.post('/admin/media/storeimage',uploadData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
                    this.isLoading=false;
                    window.location.href = "/admin/files";
                }).catch(error => {
                    this.isLoading=false;
                    this.errors = error.response.data.errors;
                });
              }).catch(error => {
                this.isLoading=false;
                this.errors = error.response.data.errors;
              });

            },
            submitaudio()
            {

               this.errors=[];
              this.success=null; 
               this.isLoading=true;

              let formData=new FormData();

              formData.append('standardLink_id',this.standardLink_id);                 
              formData.append('name',this.title);                 
              formData.append('media',this.media);                 
              formData.append('type',this.type); 
              formData.append('audio_type',this.audio_type);                 
              formData.append('description',this.description);
              formData.append('audios',this.audios); 
              formData.append('recordingfile',this.recordingfile); 

                             
              axios.post('/admin/media/audio/add',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                this.success = response.data.success;
                 this.isLoading=false;
                 window.location.href = "/admin/files";

              }).catch(error => {
                this.errors = error.response.data.errors;
                 this.isLoading=false;
                //console.log(this.errors);
              });  

            },

            bfupload(data)
            {
              //this.recordingfile=data.blob;
              //console.log(recordingfile);

            },

             fileComplete() {
                //console.log('file complete', arguments)
                this.videos=arguments[0].file;
                //const file = arguments[0].file;
                //console.log(this.videos);
            },

            AudiofileComplete()
            {
              // console.log('audio complete', arguments)
                this.audios=arguments[0].file;
                //const file = arguments[0].file;
               // console.log(this.audios);
            },
            submitvideo()
            {

                this.errors=[];
              this.success=null; 
              this.isLoading=true;

              let formData=new FormData();

              formData.append('standardLink_id',this.standardLink_id);                 
              formData.append('name',this.title);                 
              formData.append('media',this.media);                 
              formData.append('type',this.type); 
              formData.append('video_type',this.video_type);                 
              formData.append('description',this.description);
              formData.append('url',this.video_url);
              formData.append('videos',this.videos); 

                             
              axios.post('/admin/media/video/add',formData,{headers: {'Content-Type': 'multipart/form-data'}}).then(response => {     
                this.success = response.data.success;
                this.isLoading=false;
                 window.location.href = "/admin/files";

              }).catch(error => {
                this.isLoading=false;
                this.errors = error.response.data.errors;
                //console.log(this.errors);
              });  
             

            },


            removeAllFiles()
            {
                this.$refs.myFilePond.removeFiles();
                this.images = [];
            },
        },
  
        created()
        {   
            this.getList();
        }
    }
</script>
<style scoped>
  .myCustomClass {
     margin-top:10px;
     bottom:0px;
     right:0px;
     position: fixed;
}
</style>