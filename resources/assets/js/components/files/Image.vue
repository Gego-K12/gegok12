<template>
    <div>
        <div class="my-6">
            <div class="w-full lg:w-1/4">
                <label class="tw-form-label">Upload<span class="text-red-500">*</span></label>
            </div>
            <uploader :options="options" class="uploader-example" id="uploadvideo"  type="file">
                <uploader-unsupport></uploader-unsupport>
                <uploader-drop>
                <uploader-btn  :attrs="attrs">Select File</uploader-btn>
                </uploader-drop>
                <uploader-list></uploader-list>
            </uploader>
        </div>
    </div>
</template>

<script>
    export default {
        props:['url','csrf'],
        data () {
            return {
                options: {
                    // https://github.com/simple-uploader/Uploader/tree/develop/samples/Node.js
                    target: this.url+'/admin/storeimage',
                    testChunks: false,
                    headers:{ 'X-CSRF-TOKEN': this.csrf },
                    multiple:true,
                },
                attrs:
                {
                    accept:'image/*',
                },
                single:false,
            }
        },

        computed: 
        {
            videoUrl() 
            {
                //return this.url + '/admin/storeimage/'
            }
        },

        created()
        {
            //
        },
    }
</script>

<style>
    .uploader-example {
        width: 880px;
        padding: 15px;
        font-size: 12px;
        box-shadow: 0 0 10px rgba(0, 0, 0, .4);
    }
    .uploader-example .uploader-btn {
        margin-right: 2px;
    }
    .uploader-example .uploader-list {
        max-height: 440px;
        overflow: auto;
        overflow-x: hidden;
        overflow-y: auto;
    }
</style>