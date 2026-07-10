<template>
    <div>
        <div class="bg-white shadow my-4 px-3 py-2">
            <div class="flex">
                <img class="w-24 h-24" :src="user.photo">
                <div class="mx-3 w-full flex flex-col justify-between">
                    <div>
                        <h2 class="text-3xl text-gray-800 font-bold">{{ user.name }}</h2>
                        <h4 class="text-base text-gray-700 font-bold">{{ user.passing_session }} Batch</h4>
                    </div>
                    <div class="mb-3">
                        <ul class="list-reset flex items-center justify-end">
                            <li class="mx-2" v-if="user.twitter != null">
                                <a :href="user.twitter">
                                    <svg class="w-6 h-6 fill-current text-blue-600 hover:text-blue-500" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 504.4 504.4" style="enable-background:new 0 0 504.4 504.4;" xml:space="preserve"><g><g><path d="M377.6,0.2H126.4C56.8,0.2,0,57,0,126.6v251.6c0,69.2,56.8,126,126.4,126H378c69.6,0,126.4-56.8,126.4-126.4V126.6 C504,57,447.2,0.2,377.6,0.2z M377.2,189c0,2.8,0,5.6,0,8.4c0,84-64.8,180.8-183.6,180.8c-36.4,0-70.4-10.4-98.8-28.4 c5.2,0.4,10,0.8,15.2,0.8c30.4,0,58-10,80-27.2c-28.4-0.4-52-18.8-60.4-44c4,0.8,8,1.2,12,1.2c6,0,12-0.8,17.2-2.4 c-28.8-6-50.8-31.6-50.8-62.4V215c8,4.8,18.4,7.6,28.8,8c-17.2-11.2-28.8-30.8-28.8-52.8c0-11.6,3.2-22.4,8.8-32 c32,38.4,79.2,63.6,132.8,66.4c-1.2-4.8-1.6-9.6-1.6-14.4c0-35.2,28.8-63.6,64.4-63.6c18.4,0,35.2,7.6,47.2,20 c14.8-2.8,28.4-8,40.8-15.6c-4.8,14.8-15.2,27.2-28.4,35.2c13.2-1.6,25.6-4.8,37.2-10C400.4,169,389.6,180.2,377.2,189z"/></g></g></svg>
                                </a>
                            </li>
                            <li class="mx-2" v-if="user.facebook != null">
                                <a :href="user.facebook">
                                    <svg class="w-6 h-6 fill-current text-purple-700 hover:text-purple-600" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><g><g><path d="M448,0H64C28.704,0,0,28.704,0,64v384c0,35.296,28.704,64,64,64h192V336h-64v-80h64v-64c0-53.024,42.976-96,96-96h64v80 h-32c-17.664,0-32-1.664-32,16v64h80l-32,80h-48v176h96c35.296,0,64-28.704,64-64V64C512,28.704,483.296,0,448,0z"/></g></g></svg>
                                </a>
                            </li>
                            <li class="mx-2" v-if="user.telegram != null">
                                <a :href="user.telegram">
                                    <svg class="w-6 h-6 fill-current text-blue-600 hover:text-blue-500" id="Bold" enable-background="new 0 0 24 24" height="512" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m12 24c6.629 0 12-5.371 12-12s-5.371-12-12-12-12 5.371-12 12 5.371 12 12 12zm-6.509-12.26 11.57-4.461c.537-.194 1.006.131.832.943l.001-.001-1.97 9.281c-.146.658-.537.818-1.084.508l-3-2.211-1.447 1.394c-.16.16-.295.295-.605.295l.213-3.053 5.56-5.023c.242-.213-.054-.333-.373-.121l-6.871 4.326-2.962-.924c-.643-.204-.657-.643.136-.953z"/></svg>
                                </a>
                            </li>
                            <li class="mx-2" v-if="user.linkedin != null">
                                <a :href="'https://'+user.linkedin">
                                    <svg class="w-6 h-6 fill-current text-red-500 hover:text-red-400" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 310 310" style="enable-background:new 0 0 310 310;" xml:space="preserve"><g id="XMLID_801_"><path id="XMLID_802_" d="M72.16,99.73H9.927c-2.762,0-5,2.239-5,5v199.928c0,2.762,2.238,5,5,5H72.16c2.762,0,5-2.238,5-5V104.73 C77.16,101.969,74.922,99.73,72.16,99.73z"/><path id="XMLID_803_" d="M41.066,0.341C18.422,0.341,0,18.743,0,41.362C0,63.991,18.422,82.4,41.066,82.4 c22.626,0,41.033-18.41,41.033-41.038C82.1,18.743,63.692,0.341,41.066,0.341z"/><path id="XMLID_804_" d="M230.454,94.761c-24.995,0-43.472,10.745-54.679,22.954V104.73c0-2.761-2.238-5-5-5h-59.599 c-2.762,0-5,2.239-5,5v199.928c0,2.762,2.238,5,5,5h62.097c2.762,0,5-2.238,5-5v-98.918c0-33.333,9.054-46.319,32.29-46.319 c25.306,0,27.317,20.818,27.317,48.034v97.204c0,2.762,2.238,5,5,5H305c2.762,0,5-2.238,5-5V194.995 C310,145.43,300.549,94.761,230.454,94.761z"/></g></svg>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white shadow my-4 px-3 py-2">
            <h3 class="text-lg text-gray-700 font-semibold">Contact Information</h3>
            <ul class="list-reset text-xs leading-loose my-2">
                <li class="flex py-1 items-center">
                    <svg class="fill-current w-3 h-3 text-gray-800" height="512" viewBox="0 0 58 58" width="512" xmlns="http://www.w3.org/2000/svg"><g id="Page-1" fill="none" fill-rule="evenodd"><g id="003---Call" fill="rgb(0,0,0)" fill-rule="nonzero" transform="translate(-1)"><path id="Shape" d="m25.017 33.983c-5.536-5.536-6.786-11.072-7.068-13.29-.0787994-.6132828.1322481-1.2283144.571-1.664l4.48-4.478c.6590136-.6586066.7759629-1.685024.282-2.475l-7.133-11.076c-.5464837-.87475134-1.6685624-1.19045777-2.591-.729l-11.451 5.393c-.74594117.367308-1.18469338 1.15985405-1.1 1.987.6 5.7 3.085 19.712 16.855 33.483s27.78 16.255 33.483 16.855c.827146.0846934 1.619692-.3540588 1.987-1.1l5.393-11.451c.4597307-.9204474.146114-2.0395184-.725-2.587l-11.076-7.131c-.7895259-.4944789-1.8158967-.3783642-2.475.28l-4.478 4.48c-.4356856.4387519-1.0507172.6497994-1.664.571-2.218-.282-7.754-1.532-13.29-7.068z"/><path id="Shape" d="m47 31c-1.1045695 0-2-.8954305-2-2-.0093685-8.2803876-6.7196124-14.9906315-15-15-1.1045695 0-2-.8954305-2-2s.8954305-2 2-2c10.4886126.0115735 18.9884265 8.5113874 19 19 0 1.1045695-.8954305 2-2 2z"/><path id="Shape" d="m57 31c-1.1045695 0-2-.8954305-2-2-.0154309-13.800722-11.199278-24.9845691-25-25-1.1045695 0-2-.8954305-2-2s.8954305-2 2-2c16.008947.01763587 28.9823641 12.991053 29 29 0 .530433-.2107137 1.0391408-.5857864 1.4142136-.3750728.3750727-.8837806.5857864-1.4142136.5857864z"/></g></g></svg>
                    <div class="w-full mx-2">
                        <a href="#" class="blue-text">{{ user.mobile_no }}</a>
                    </div>
                </li>
                <li class="flex py-1 items-center">
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-3 h-3 fill-current text-gray-800"><g><g><polygon points="339.392,258.624 512,367.744 512,144.896"></polygon></g></g> <g><g><polygon points="0,144.896 0,367.744 172.608,258.624"></polygon></g></g> <g><g><path d="M480,80H32C16.032,80,3.36,91.904,0.96,107.232L256,275.264l255.04-168.032C508.64,91.904,495.968,80,480,80z"></path></g></g> <g><g><path d="M310.08,277.952l-45.28,29.824c-2.688,1.76-5.728,2.624-8.8,2.624c-3.072,0-6.112-0.864-8.8-2.624l-45.28-29.856 L1.024,404.992C3.488,420.192,16.096,432,32,432h448c15.904,0,28.512-11.808,30.976-27.008L310.08,277.952z"></path></g></g></svg>
                    <div class="w-full mx-2">
                        <a href="#" class="blue-text" v-if="user.email != null">{{ user.email }}</a>
                        <a href="#" class="blue-text" v-else>--</a>
                    </div>
                </li>
            </ul>
        </div>

        <div class="bg-white shadow my-4 px-3 py-2">
            <h3 class="text-lg text-gray-700 font-semibold">About</h3>
            <div class="text-sm py-1">
                <p v-if="user.about_me != null">{{ user.about_me }}</p>
                <p v-else>--</p>
            </div>
        </div>
        <div class="bg-white shadow my-4 px-3 py-2">
            <h3 class="text-lg text-gray-700 font-semibold">Education Details</h3>
            <div class="flex flex-wrap">
                <ul class="list-reset leading-loose my-2 text-sm w-full lg:w-2/5 md:w-full" v-for="educationdetail in user.educationdetails" >
                    <li class="flex items-center py-1">
                        <span class="font-semibold w-1/2">Institution Name : </span>
                        <span class="w-1/2">{{ educationdetail[0] }}</span>
                    </li>
                    <li class="flex items-center py-1">
                        <span class="font-semibold w-1/2">Degree : </span>
                        <span class="w-1/2">{{ educationdetail[1] }}</span>
                    </li>
                    <li class="flex items-center py-1">
                        <span class="font-semibold w-1/2">Specilization : </span>
                        <span class="w-1/2">{{ educationdetail[2] }}</span>
                    </li>
                    <li class="flex items-center py-1">
                        <span class="font-semibold w-1/2">Start Year : </span>
                        <span class="w-1/2">{{ educationdetail[3] }}</span>
                    </li>
                    <li class="flex items-center py-1">
                        <span class="font-semibold w-1/2">End Year : </span>
                        <span class="w-1/2" v-if="educationdetail[4] != null">{{ educationdetail[4] }}</span>
                        <span class="w-1/2" v-else>Currently Studying</span>
                    </li>
                    <li class="flex items-center py-1" v-if="educationdetail[5] != null">
                        <span class="font-semibold w-1/2">Grade : </span>
                        <span class="w-1/2">{{ educationdetail[5] }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="bg-white shadow my-4 px-3 py-2">
            <h3 class="text-lg text-gray-700 font-semibold">Job Experiences</h3>
            <div v-if="user.work != null"> 
                <div class="list-reset py-4 text-xs" v-for="workdetail in user.workdetails">
                    <div class="flex">
                        <img class="w-16 h-16" :src="user.photo">
                        <div class="mx-5">
                            <h4 class="text-gray-800 text-base font-bold">{{ workdetail[1] }}</h4>
                            <h6 class="text-gray-800 text-sm font-semibold">{{ workdetail[0] }}</h6>
                            <p class="text-gray-700 text-xs font-medium">{{ workdetail[4] }} {{ workdetail[3] }} {{ workdetail[5] }}</p>
                            <p class="text-gray-700 text-xs font-medium">{{ workdetail[2] }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else>
                <p style="text-align: center;">No Records Found</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:['url','name'],
        data () {
            return {
                profile_tab:'1',
                user:[],
                institution_name:'',
                specialization:'',
                errors:[],
                success:null,
            }
        },

        methods:
        {
            getData()
            {
                axios.get('/admin/alumni/show/details/'+this.name).then(response => {
                    this.user = response.data.data[0];
                    //console.log(this.user);     
                });
            },
        },
  
        created()
        {      
            this.getData(); 
        }
    }
</script>