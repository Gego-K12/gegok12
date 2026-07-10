<template>
    <div class="">
        <div class="font-semibold -mt-2 text-xl p-1  bg-red  flex justify-center items-center ml-1" v-if="this.message != null">{{ this.message }}</div>
    </div>
</template>

<script>
    export default {
        props:[],
        data(){
            return {
                message:null, 
            }
        },

        methods:
        {
            listenForNotification() 
            {
                window.Echo.channel('notification').listen('ChatJoinNotification', (data) => {
                     this.message = data.user_name+'joined to this conversation';
                });
            },
        },

        created() 
        {
            this.listenForNotification();
        },
    }
</script>

<style scoped="">
    .notification{
        font-size: 10px;
        width: 6%;
        margin-top: -12%;
        border-radius: 52%;
        float:right;
        font-size:10px;
        margin-right: 2%;
    }
</style>