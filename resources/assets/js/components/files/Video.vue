<template>
    <div>
        <div class="my-6">
            <div class="w-full lg:w-1/4 md:w-1/3">
                <label class="tw-form-label">Upload<span class="text-red-500">*</span></label>
            </div>
            <uploader :options="options" class="uploader-example" id="uploadvideo" name="uploadvideo" type="file">
                <uploader-unsupport></uploader-unsupport>
                <uploader-drop>
                <uploader-btn :single="single" :attrs="attrs">Select File</uploader-btn>
                </uploader-drop>
                <uploader-list></uploader-list>
            </uploader>
        </div>
        <span v-if="errors.file" class="text-red-500 text-xs font-semibold">{{ errors.file[0] }}</span>
    </div>
</template>

<script>
    export default {
        props:['url','csrf'],
        data () {
            return {
                options: {
                    // https://github.com/simple-uploader/Uploader/tree/develop/samples/Node.js
                    target: this.url+'/admin/storevideos',
                    testChunks: false,
                    headers:{ 'X-CSRF-TOKEN': this.csrf },
                    multiple:false,
                },
                attrs:
                {
                    accept:'video/*',
                },
                single:true,
                success:null,
                errors:[],
                file:'',
            }
        },

        computed: 
        {
            videoUrl() 
            {
                return this.url + '/admin/storevideos/'
            },

            test()
            {
                const uploaderInstance = this.$refs.uploader.uploader
                // now you can call all uploader methods
                // https://github.com/simple-uploader/Uploader#methods
                var a = uploaderInstance.extension();
                console.log(a)
            },
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