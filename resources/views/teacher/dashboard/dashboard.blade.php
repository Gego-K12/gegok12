@extends('layouts.teacher.layout')

@section('content')
    <div class="w-full lg:mx-2 md:mx-2">
        <h1 class="admin-h1 my-3">Dashboard</h1>
        <!-- start -->
        <div class=" flex flex-col lg:flex-row my-2">
            <div class="w-full my-2">
                <div class="flex flex-col lg:flex-row my-2"> 
                    <div class="w-full lg:w-1/2 my-3 lg:my-0 md:my-0 px-1">
                        <div class="bg-white custom-shadow px-3 py-2 border">
                            <div>
                                <h1 class="text-gray-800 font-semibold text-lg border-b mx-2 py-1 pb-3">Notice Board</h1>
                            </div> 
                            <div class="notice-box">
                                @if(count($dashboard['noticeboard']) > 0)
                                    @foreach($dashboard['noticeboard'] as $noticeboard)
                                        <div class="notice-box-list py-3 mx-3 border-b">
                                            <div class="bg-teal-500 text-x rounded-full inline-block text-white px-2 py-1 my-1 mb-2">
                                                <p>{{ $noticeboard->title }}</p>
                                            </div>
                                            <div class="bg-purple-500 text-xs rounded-full inline-block text-white px-2 py-1 my-1 mb-2">
                                                <p>{{ date('d M Y',strtotime($noticeboard->publish_date)) }}</p>
                                            </div>
                                            <div class="bg-orange-500 text-xs rounded-full inline-block text-white px-2 py-1 my-1 mb-2">
                                                <p>{{ ucwords($noticeboard->type) }}</p>
                                            </div>
                                            <div class="my-1">
                                                <p class="text-sm text-gray-900 font-semibold">{!! $noticeboard->description !!}</p>
                                            </div>
                                            <div class="text-sm my-1">
                                                <p class="text-gray-500">
                                                    <span class="text-gray-500">{{ $noticeboard->created_at->diffForHumans() }}</span>
                                                </p>
                                                @if($noticeboard->attachment_file != '')
                                                    <div class="flex justify-end">
                                                        <a href="{{ $noticeboard->AttachmentPath }}" target="_blank">
                                                            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-5 h-5 fill-current mr-1 text-gray-600"><g><g><path d="M472,313v139c0,11.028-8.972,20-20,20H60c-11.028,0-20-8.972-20-20V313H0v139c0,33.084,26.916,60,60,60h392 c33.084,0,60-26.916,60-60V313H472z"></path></g></g> <g><g><polygon points="352,235.716 276,311.716 276,0 236,0 236,311.716 160,235.716 131.716,264 256,388.284 380.284,264"></polygon></g></g></svg>
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="notice-box-list py-3 mx-3 border-b">
                                        <p class="text-sm text-gray-900 font-semibold" style="text-align: center;">No Notice Found</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <!-- start -->
                    <div class="w-full lg:w-1/2 my-3 lg:my-0 md:my-2 px-1">
                        <div class="bg-white custom-shadow px-3 py-2 border">
                            <div>
                                <h1 class="text-gray-800 font-semibold text-lg border-b mx-2 py-1 pb-3">Subjects</h1>
                            </div> 
                            <div class="notice-box flex flex-wrap lg:flex-row items-center">
                                @foreach($dashboard['subject'] as $subject)
                                    <div class="w-1/3 lg:w-1/4 px-2 my-5 h-56 subject-box">
                                        <div class="bg-purple-300 px-2 py-3 rounded h-full flex flex-col justify-between">
                                            <div class="w-16 h-16 lg:w-20 lg:h-20 md:w-20 md:h-20 rounded-full ml-auto bg-purple-400 flex items-center p-1 my-2 mx-1">
                                                <svg class="w-10 h-10 mx-auto" height="511pt" viewBox="-30 1 511 511.9995" width="511pt" xmlns="http://www.w3.org/2000/svg"><path d="m261.652344 423.269531-51.871094-95.089843v-91.179688h-150v91.179688l-51.871094 95.089843c-21.785156 39.953125 7.078125 88.730469 52.671875 88.730469h148.398438c45.515625 0 74.496093-48.703125 52.671875-88.730469zm0 0" fill="#dcf1ff"/><path d="m261.652344 423.269531c-31.222656-57.234375-23.464844-43.011719-51.871094-95.089843v-91.179688h-75.128906v275h74.328125c45.515625 0 74.496093-48.703125 52.671875-88.730469zm0 0" fill="#bfe4fe"/><path d="m214.78125 242h-160c-8.285156 0-15-6.714844-15-15s6.714844-15 15-15h160c8.285156 0 15 6.714844 15 15s-6.714844 15-15 15zm0 0" fill="#dcf1ff"/><path d="m229.78125 227c0 8.28125-6.71875 15-15 15h-80.128906v-30h80.128906c8.28125 0 15 6.71875 15 15zm0 0" fill="#bfe4fe"/><path d="m428.8125 162.789062-141.460938-141.460937c-.457031.566406-105.507812 105.585937-106.019531 106.101563l141.417969 141.417968c29.289062 29.3125 76.757812 29.324219 106.0625 0 29.304688-29.285156 29.320312-76.757812 0-106.058594zm0 0" fill="#dcf1ff"/><path d="m428.8125 162.789062-141.460938-141.460937c-.457031.570313-52.636718 52.707031-53.152343 53.222656l194.453125 194.449219c.046875-.039062.109375-.101562.160156-.152344 29.304688-29.285156 29.320312-76.757812 0-106.058594zm0 0" fill="#bfe4fe"/><path d="m163.648438 130.964844c-5.859376-5.855469-5.859376-15.355469 0-21.210938l105.359374-105.359375c5.855469-5.859375 15.355469-5.859375 21.210938 0 5.859375 5.855469 5.859375 15.355469 0 21.210938l-105.355469 105.359375c-5.859375 5.859375-15.359375 5.859375-21.214843 0zm0 0" fill="#dcf1ff"/><path d="m290.21875 25.609375-52.476562 52.480469-21.210938-21.210938 52.480469-52.488281c5.847656-5.851563 15.347656-5.851563 21.207031 0 5.863281 5.859375 5.855469 15.371094 0 21.21875zm0 0" fill="#bfe4fe"/><path d="m236.410156 377h-203.261718l-25.238282 46.269531c-21.796875 39.960938 7.089844 88.730469 52.671875 88.730469h148.394531c45.515626 0 74.5-48.710938 52.671876-88.730469zm0 0" fill="#e33754"/><path d="m208.980469 512h-74.328125v-135h101.757812l25.242188 46.269531c21.785156 39.960938-7.085938 88.730469-52.671875 88.730469zm0 0" fill="#d1002d"/><path d="m428.8125 162.785156-26.785156-26.785156h-212.132813l132.851563 132.851562c29.3125 29.308594 76.753906 29.3125 106.066406 0s29.316406-76.753906 0-106.066406zm0 0" fill="#f6d401"/><path d="m428.8125 268.847656c-.050781.050782-.113281.113282-.160156.152344l-133-133h106.378906l26.78125 26.789062c29.304688 29.289063 29.320312 76.757813 0 106.058594zm0 0" fill="#fd9d34"/><path d="m150.152344 167c0 8.285156-6.71875 15-15 15-8.285156 0-15-6.714844-15-15s6.714844-15 15-15c8.28125 0 15 6.714844 15 15zm0 0" fill="#f6993c"/><path d="m150.152344 167c0 8.902344-7.707032 15.476562-15.5 14.988281v-29.980469c7.761718-.484374 15.5 6.058594 15.5 14.992188zm0 0" fill="#ec5b20"/><path d="m120.152344 107c0 8.285156-6.71875 15-15 15-8.285156 0-15-6.714844-15-15s6.714844-15 15-15c8.28125 0 15 6.714844 15 15zm0 0" fill="#2aeed0"/><path d="m120.152344 107c0 8.902344-7.707032 15.476562-15.5 14.988281v-29.980469c7.761718-.484374 15.5 6.058594 15.5 14.992188zm0 0" fill="#1bdeab"/></svg>
                                            </div>
                                            <div class="pt-5 lg:px-2 md:px-2">
                                                <p class="text-white text-base lg:text-lg md:text-lg font-bold">{{ $subject['subject'] }}</p>
                                                <p class="text-white text-sm font-semibold">{{ $subject['class'] }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <!-- <div class="w-1/3 lg:w-1/4 px-2 my-5 h-56">
                                    <div class="bg-teal-300 px-2 py-3 rounded h-full flex flex-col justify-between">
                                        <div class="w-20 h-20 rounded-full ml-auto bg-teal-400 flex items-center p-1 my-2 mx-1">
                                            <svg class="w-10 h-10 mx-auto" id="Capa_1" enable-background="new 0 0 510 510" height="512" viewBox="0 0 510 510" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m506.82 418.49-88.33 88.33c-4.17 4.17-10.95 4.17-15.12 0l-400.14-400.14c-4.17-4.17-4.17-10.95 0-15.12l44.17-44.16 44.16-44.17c4.17-4.17 10.95-4.17 15.12 0 11.294 11.294 65.512 65.512 73.55 73.55 8.693 8.693 244.016 244.016 252.99 252.99l73.6 73.6c4.17 4.17 4.17 10.95 0 15.12z" fill="#ffb655"/><path d="m462.651 462.651-44.165 44.165c-4.169 4.17-10.948 4.17-15.117 0l-400.136-400.136c-4.169-4.17-4.169-10.948 0-15.117l44.165-44.165z" fill="#ff9100"/><path d="m201.44 97.99-22.3 22.3c-5.83 5.83-15.33 5.87-21.21 0-5.86-5.86-5.86-15.35 0-21.21l22.3-22.3c1.711 1.711 18.244 18.244 21.21 21.21z" fill="#ff7c48"/><path d="m433.22 329.77-22.3 22.3c-5.86 5.86-15.34 5.87-21.21 0-5.86-5.86-5.86-15.35 0-21.21l22.3-22.3c2.974 2.974 19.354 19.354 21.21 21.21z" fill="#ff415b"/><path d="m139.563 464.893-79.36 26.845-53.145 17.972c-2.096.706-4.138.064-5.485-1.283-1.347-1.347-1.988-3.4-1.283-5.495l17.972-53.135 26.845-79.36 65.227 29.24z" fill="#ffb655"/><path d="m139.563 464.893-132.505 44.817c-2.096.706-4.138.064-5.485-1.283l108.761-108.75z" fill="#ff9100"/><path d="m60.203 491.738-53.145 17.972c-2.096.706-4.138.064-5.485-1.283-1.347-1.347-1.988-3.4-1.283-5.495l17.972-53.135 20.976 20.976z" fill="#736e6e"/><path d="m60.203 491.738-53.145 17.972c-2.096.706-4.138.064-5.485-1.283l37.665-37.654z" fill="#464141"/><path d="m459.709 144.736c-18.052 18.053-153.764 153.775-320.146 320.156l-35.879-35.879-11.343-11.343-11.343-11.343-35.89-35.89c4.446-4.446 315.354-315.354 320.146-320.146l46.218 25.552 14.604 8.072.021.021 8.072 14.593z" fill="#00ccb3"/><path d="m459.709 144.736-320.146 320.157-35.879-35.88-11.343-11.343 333.744-333.745.011.011 8.072 14.593z" fill="#00b6bd"/><path d="m434.168 98.529-330.484 330.484-11.343-11.343-11.344-11.343 330.474-330.484 14.604 8.072.021.021z" fill="#fff5f5"/><path d="m434.168 98.529-330.484 330.484-11.343-11.343 333.744-333.745.011.011z" fill="#ebe1dc"/><path d="m490.435 114.021-30.726 30.726-94.456-94.456 30.726-30.726c26.086-26.086 68.381-26.086 94.456 0 26.087 26.075 26.087 68.369 0 94.456z" fill="#ff7c48"/><path d="m490.435 114.021-30.726 30.726-47.223-47.223 77.949-77.96c26.087 26.076 26.087 68.37 0 94.457z" fill="#ff415b"/></svg>
                                        </div>
                                        <div class="pt-5 px-2">
                                            <p class="text-white text-lg font-bold">Mathematics</p>
                                            <p class="text-white text-sm font-semibold">7-C</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-1/3 lg:w-1/4 px-2 my-5 h-56">
                                    <div class="bg-red-300 px-2 py-3 rounded h-full flex flex-col justify-between">
                                        <div class="w-20 h-20 rounded-full ml-auto bg-red-400 flex items-center p-1 my-2 mx-1">
                                            <svg class="w-10 h-10 fill-current text-white mx-auto" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512.001 512.001" style="enable-background:new 0 0 512.001 512.001;" xml:space="preserve"><g><g><g><path d="M305.271,182.764c-7.789,0-15.578,2.797-15.578,9.387v67.9l-32.352-61.709c-7.59-14.579-11.783-15.578-22.967-15.578 c-7.789,0-15.577,2.996-15.577,9.586v126.813c0,6.391,7.788,9.586,15.577,9.586s15.577-3.195,15.577-9.586v-67.9l37.944,69.099 c3.795,6.99,10.186,8.388,17.375,8.388c7.788,0,15.576-3.195,15.576-9.586V192.15 C320.846,185.561,313.058,182.764,305.271,182.764z"/><path d="M195.834,209.924c5.991,0,9.387-6.39,9.387-13.779c0-6.391-2.797-13.381-9.387-13.381h-71.096 c-6.79,0-13.58,3.195-13.58,9.586v126.814c0,6.391,6.79,9.586,13.58,9.586h71.096c6.59,0,9.387-6.99,9.387-13.381 c0-7.388-3.396-13.779-9.387-13.779h-53.521v-33.95h29.956c6.591,0,9.386-6.39,9.386-11.782c0-6.391-3.395-12.183-9.386-12.183 h-29.956v-33.751H195.834z"/><path d="M438.142,256l71.924-151.419c2.943-6.196,2.506-13.469-1.159-19.267C505.241,79.516,498.859,76,492,76H20 C8.954,76,0,84.954,0,96v320c0,11.046,8.954,20,20,20h472c6.859,0,13.241-3.516,16.906-9.314 c3.665-5.798,4.103-13.07,1.159-19.267L438.142,256z M40,396V116h420.358l-62.424,131.419c-2.579,5.43-2.579,11.732,0,17.162 L460.358,396H40z"/></g></g></g></svg>
                                        </div>
                                        <div class="pt-5 px-2">
                                            <p class="text-white text-lg font-bold">English</p>
                                            <p class="text-white text-sm font-semibold">6-A</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-1/3 lg:w-1/4 px-2 my-5 h-56">
                                    <div class="bg-indigo-300 px-2 py-3 rounded h-full flex flex-col justify-between">
                                        <div class="w-20 h-20 rounded-full ml-auto bg-indigo-400 flex items-center p-1 my-2 mx-1">
                                            <svg class="w-10 h-10 mx-auto" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><path style="fill:#65B1FC;" d="M496,256c0,132.9-107.999,241-240,241C123.701,497,15,388.299,15,256C15,123.699,123.701,15,256,15 S496,123.699,496,256z"/><path style="fill:#1689FC;" d="M496,256c0,132.9-107.999,241-240,241V15C388.299,15,496,123.699,496,256z"/><path style="fill:#96EBE6;" d="M256,0C115.3,0,0,115.3,0,256s115.3,256,256,256s256-115.3,256-256c0-0.3,0-0.3,0-0.601s0-0.3,0-0.3 V254.2C510.801,114.399,396.099,0,256,0z M179.5,43.799c-18.001,23.101-33.001,55.6-43.2,92.501 c-36.899,10.199-69.401,25.199-92.5,43.2C66.301,117.1,117.1,66.299,179.5,43.799z M128.8,170.2c-5.099,26.699-7.8,55.499-7.8,85.8 s2.701,59.099,7.8,85.8C70.3,322.6,30,291.1,30,256C30,220.899,70.3,189.399,128.8,170.2z M43.801,332.5 c23.099,17.999,55.6,32.999,92.5,43.2c10.199,36.899,25.199,69.399,43.2,92.5C117.1,445.699,66.301,394.9,43.801,332.5z M241,479.299c-29.099-9.3-54.6-46-70.8-96.099c22.2,4.2,45.899,6.599,70.8,7.5V479.299z M241,360.7 c-28.2-0.901-55.201-4.501-79.799-9.901C154.9,321.7,151,289.6,151,256s3.9-65.7,10.201-94.801c24.598-5.4,51.599-9,79.799-9.899 V360.7z M241,121.3c-24.901,0.899-48.6,3.3-70.8,7.5c16.199-50.101,41.7-86.801,70.8-96.101V121.3z M467.199,179.5 c-23.099-18.301-54.6-33.001-91.5-43.2c-10.199-36.901-25.199-69.401-43.2-92.501C394.9,66.299,444.699,116.8,467.199,179.5z M271,32.699c29.099,9.3,54.6,46,70.8,96.101c-22.2-4.2-45.899-6.601-70.8-7.5V32.699z M271,151.3 c28.2,0.899,55.201,4.499,79.799,9.899C357.1,190.3,361,222.4,361,256c-0.3,34.2-3.9,66-10.499,94.799 c-24.901,5.7-51.601,8.701-79.501,9.6V151.3z M271,479.299v-88.9c24.6-0.601,48.3-3.3,70.8-7.5 C325.3,433.599,299.5,469.999,271,479.299z M331.901,468.5c18.3-23.101,33.3-55.6,43.499-93.1 c36.901-10.199,68.401-24.901,92.1-43.2C444.699,394.9,394.9,446,331.901,468.5z M481,256.3c0,14.399-6.899,28.799-20.4,42.299 c-17.701,17.701-45,32.401-77.701,43.2c5.101-26.7,7.8-55.201,8.101-85.8c0-30.3-2.701-59.101-7.8-85.8 c58.5,19.199,97.8,50.698,97.8,85.8L481,256.3L481,256.3z"/><path style="fill:#00C8C8;" d="M256,0v512c140.7,0,256-115.3,256-256c0-0.3,0-0.3,0-0.601s0-0.3,0-0.3V254.2 C510.801,114.399,396.099,0,256,0z M467.199,179.5c-23.099-18.301-54.6-33.001-91.5-43.2c-10.199-36.901-25.199-69.401-43.2-92.501 C394.9,66.299,444.699,116.8,467.199,179.5z M271,32.699c29.099,9.3,54.6,46,70.8,96.101c-22.2-4.2-45.899-6.601-70.8-7.5V32.699z M271,151.3c28.2,0.899,55.201,4.499,79.799,9.899C357.1,190.3,361,222.4,361,256c-0.3,34.2-3.9,66-10.499,94.799 c-24.901,5.7-51.601,8.701-79.501,9.6V151.3z M271,479.299v-88.9c24.6-0.601,48.3-3.3,70.8-7.5 C325.3,433.599,299.5,469.999,271,479.299z M331.901,468.5c18.3-23.101,33.3-55.6,43.499-93.1 c36.901-10.199,68.401-24.901,92.1-43.2C444.699,394.9,394.9,446,331.901,468.5z M481,256.3c0,14.399-6.899,28.799-20.4,42.299 c-17.701,17.701-45,32.401-77.701,43.2c5.101-26.7,7.8-55.201,8.101-85.8c0-30.3-2.701-59.101-7.8-85.8 c58.5,19.199,97.8,50.698,97.8,85.8L481,256.3L481,256.3z"/><path style="fill:#FF281D;" d="M406,241c-57.9,0-105,47.1-105,105c0,22.8,7.2,44.7,21,62.999l71.999,97c2.701,3.9,7.202,6,12.001,6 s9.3-2.1,12.001-6l71.999-97C503.8,390.7,512,368.8,512,346C512,288.1,463.9,241,406,241z M406,391c-24.901,0-45-20.102-45-45 c0-24.9,20.099-45,45-45s45,20.1,45,45C451,370.898,430.901,391,406,391z"/><path style="fill:#B80000;" d="M406,241v60c24.901,0,45,20.1,45,45c0,24.898-20.099,45-45,45v121c4.799,0,9.3-2.1,12.001-6L490,409 c13.8-18.3,22-40.2,22-63C512,288.1,463.9,241,406,241z"/></svg>
                                        </div>
                                        <div class="pt-5 px-2">
                                            <p class="text-white text-lg font-bold">Geography</p>
                                            <p class="text-white text-sm font-semibold">8-B</p>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <!-- end -->
                </div>
            </div>         
        </div>
        <!-- end -->

        <!-- start -->
        <div class="flex flex-wrap lg:flex-row md:flex-row">
           

            <!-- start -->
            <div class="w-full lg:w-1/4  md:w-1/2 my-3 lg:my-0 md:my-2 px-1">
                <div class="bg-white px-3 py-2 h-full">
                    <div class="border-b mx-2 py-1 pb-3">
                        <h1 class="text-gray-800 font-semibold text-lg">Activity Log</h1>
                    </div>
                    <div class="event-box">
                        <div class="notice-box-list py-3  mx-2">
                            <!-- ****** -->    
                            @foreach($dashboard['activitylog'] as $activitylog)
                                <div class="w-full border-l-2 border-purple-500 relative my-1">
                                    <div class="border py-2 px-2 activity-log ">
                                        <div class="">
                                            <div class="px-3">
                                                <p class="text-gray-800 text-sm font-semibold">{{ $activitylog->created_at->diffForHumans() }}</p>
                                                <p class="text-gray-500 text-xs">{{ $activitylog->description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <!-- ***** -->

                            <!-- ****** -->    
                            <!-- <div class="w-full border-l-2 border-teal-500 relative my-1">
                              <div class="border py-2 px-2 activity-log ">
                                <div class="">
                                  <div class="px-3">
                                    <p class="text-gray-800 text-sm font-semibold">10 mins ago</p>
                                    <p class="text-gray-500 text-xs">School Promote video sharing</p>
                                  </div>
                                </div>
                              </div>
                            </div> -->
                            <!-- ***** -->
            
                            <!-- ****** -->    
                            <!-- <div class="w-full border-l-2 border-orange-500 relative my-1">
                              <div class="border py-2 px-2 activity-log ">
                                <div class="">
                                  <div class="px-3">
                                    <p class="text-gray-800 text-sm font-semibold">30 mins ago</p>
                                    <p class="text-gray-500 text-xs">School Promote video sharing</p>
                                  </div>
                                </div>
                              </div>
                            </div> -->
                            <!-- ***** -->

                            <!-- ****** -->    
                            <!-- <div class="w-full border-l-2 border-indigo-500 relative my-1">
                              <div class="border py-2 px-2 activity-log ">
                                <div class="">
                                  <div class="px-3">
                                    <p class="text-gray-800 text-sm font-semibold">1 hr ago</p>
                                    <p class="text-gray-500 text-xs">School Promote video sharing</p>
                                  </div>
                                </div>
                              </div>
                            </div> -->
                            <!-- ***** -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- end -->

            <!-- start -->
            @if(config('gexam.enabled', false))
            <div class="w-full lg:w-2/4 md:w-full px-1">
                <div class="bg-white custom-shadow  py-1 border h-full">
                    <div>
                        <h1 class="text-gray-800 px-3 font-semibold text-xl py-2 pb-3">Upcoming Exams</h1>
                    </div> 
                    <div class="exam-box mt-2 overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr>
                                    <th class="font-semibold text-left px-3 pt-2 pb-3 text-base">Exam Name</th> 
                                    <th class="font-semibold text-left px-3 pt-2 pb-3 text-base w-40">Subject</th> 
                                    <th class="font-semibold text-left px-3 pt-2 pb-3 text-base">Class</th>
                                    <th class="font-semibold text-left px-3 pt-2 pb-3 text-base">Time</th>
                                </tr>
                            </thead> 
                            @if(count($dashboard['upcomingExam']) > 0)
                                <tbody>
                                    @foreach($dashboard['upcomingExam'] as $key => $upcomingExams)
                                        <tr>
                                            <td colspan="4">
                                                <p class="bg-gray-100 px-4 py-2 border-t border-b text-base font-semibold text-gray-700">{{ date('d-m-Y H:i:s',strtotime($key)) }}</p>
                                            </td>
                                        </tr>
                                        @foreach($upcomingExams as $upcomingExam)
                                            <tr class="border-t font-semibold">
                                                <td class="px-3 py-2">{{ $upcomingExam->exam->name }}</td>
                                                <td class="px-3 py-2">{{ $upcomingExam->subject->name }}</td>
                                                <td class="px-3 py-2">{{ $upcomingExam->standardLink->StandardSection }}</td>
                                                <td class="px-3 py-2">{{ date('H:i A',strtotime($upcomingExam->start_time)) }} - {{ date('H:i A',strtotime($upcomingExam->duration)) }}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                    <!-- <tr class="border-t font-semibold">
                                        <td class="px-3 py-2">Term-1</td>
                                        <td class="px-3 py-2">Mathematics</td>
                                        <td class="px-3 py-2">7-C</td>
                                        <td class="px-3 py-2">01.00PM - 3.00PM</td>
                                    </tr>
                                    <td colspan="4">
                                        <p class="bg-gray-100 px-4 py-2 border-t border-b text-base font-semibold text-gray-700">13-06-2020</p>
                                    </td>
                                    <tr class="border-t font-semibold">
                                      <td class="px-3 py-2">Term-1</td>
                                      <td class="px-3 py-2">Chemistry</td>
                                      <td class="px-3 py-2">8-B</td>
                                      <td class="px-3 py-2">01.00PM - 3.00PM</td>
                                    </tr>
                                    <tr class="border-t font-semibold">
                                      <td class="px-3 py-2">Term-1</td>
                                      <td class="px-3 py-2">Geography</td>
                                      <td class="px-3 py-2">8-B</td>
                                      <td class="px-3 py-2">01.00PM - 3.00PM</td>
                                    </tr> -->
                                </tbody>  
                            @else
                                <tbody>
                                    <tr>
                                        <td colspan="4">
                                            <p class="bg-gray-100 px-4 py-2 border-t border-b text-base font-semibold text-gray-700" style="text-align: center;">No Records Found</p>
                                        </td>
                                    </tr>
                                </tbody> 
                            @endif    
                        </table>
                    </div>
                </div>
            </div>
            @endif
            <!-- end -->  
        </div>
        <!-- end -->
        
        <!-- start -->
        <div class="flex flex-wrap">
            <!--Task Module-->
            <div class="w-full lg:w-1/2 md:w-1/2">
                <dashboard-task url="{{ url('/') }}" mode="teacher"></dashboard-task>
            </div>

            @if(config('gtimetable.enabled', false))
             <div class="w-full lg:w-1/2 md:w-1/2 my-3 lg:my-0 md:my-2 px-1">
                <div class="bg-white custom-shadow px-3 py-2 border">
                    <div class="mx-2 py-1">
                        <!-- <p class="text-sm text-gray-500">Monday, June</p>
                        <h1 class="text-gray-800 font-semibold text-3xl ">08</h1> -->
                        <h1 class="text-gray-800 font-semibold text-lg">TimeTable</h1>
                    </div> 

                    {{-- @if(config('gquiz.enabled', false)) --}}

                     <!-- new -->
                    <livewire:timetable.dashboard-timetable-teacher />
                    

                   

                    <!-- <div class="event-box"> -->
                        <!-- <div class="notice-box-list"> -->
                            <!-- ****** -->
                        {{-- @foreach($dashboard['timetable'] as $standard => $timetable)
                                <div class="flex items-start bg-purple-400 px-3 py-3 my-1">
                                    <div class="my-1">
                                        <svg class="w-4 h-4 fill-current text-white" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="124.813px" height="124.813px" viewBox="0 0 124.813 124.813" style="enable-background:new 0 0 124.813 124.813;" xml:space="preserve"><g><g><path d="M48.083,80.355l-1.915,11.374c-0.261,1.555,0.377,3.122,1.65,4.05c1.275,0.926,2.968,1.05,4.361,0.32l10.226-5.338 L72.631,96.1c0.605,0.314,1.268,0.472,1.924,0.472c0.859,0,1.716-0.269,2.439-0.792c1.274-0.928,1.914-2.495,1.651-4.05 l-1.913-11.374l8.234-8.077c1.126-1.103,1.527-2.749,1.044-4.247c-0.485-1.497-1.783-2.593-3.341-2.823l-11.41-1.692 l-5.139-10.329c-0.697-1.41-2.141-2.303-3.716-2.303c-1.572,0-3.015,0.893-3.718,2.303l-5.134,10.329l-11.41,1.691 c-1.561,0.23-2.853,1.326-3.339,2.823c-0.486,1.498-0.086,3.146,1.042,4.247L48.083,80.355z"/><path d="M111.443,13.269H98.378V6.022C98.378,2.696,95.682,0,92.355,0H91.4c-3.326,0-6.021,2.696-6.021,6.022v7.247H39.282V6.022 C39.282,2.696,36.586,0,33.261,0h-0.956c-3.326,0-6.021,2.696-6.021,6.022v7.247H13.371c-6.833,0-12.394,5.559-12.394,12.394 v86.757c0,6.831,5.561,12.394,12.394,12.394h98.073c6.832,0,12.394-5.562,12.394-12.394V25.663 C123.837,18.828,118.275,13.269,111.443,13.269z M109.826,110.803H14.988V43.268h94.838V110.803z"/></g></g></svg>
                                    </div>
                                    <div class="rounded px-2 w-full">
                                        <div class="px-3 text-white">
                                            <div class="flex items-center justify-between">
                                                <p class="text-sm font-semibold">{{ $timetable }}</p>
                                                <p class="text-sm font-semibold">{{ $standard }}</p>
                                            </div>
                                            <p class="text-xs">09 AM - 9.45 AM, B01</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach --}}
                            <!-- ***** -->

                            <!-- ****** -->
                            <!-- <div class="flex items-start bg-purple-400 px-3 py-3 my-1">
                                <div class="my-1">
                                    <svg class="w-4 h-4 fill-current text-white" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="124.813px" height="124.813px" viewBox="0 0 124.813 124.813" style="enable-background:new 0 0 124.813 124.813;" xml:space="preserve"><g><g><path d="M48.083,80.355l-1.915,11.374c-0.261,1.555,0.377,3.122,1.65,4.05c1.275,0.926,2.968,1.05,4.361,0.32l10.226-5.338 L72.631,96.1c0.605,0.314,1.268,0.472,1.924,0.472c0.859,0,1.716-0.269,2.439-0.792c1.274-0.928,1.914-2.495,1.651-4.05 l-1.913-11.374l8.234-8.077c1.126-1.103,1.527-2.749,1.044-4.247c-0.485-1.497-1.783-2.593-3.341-2.823l-11.41-1.692 l-5.139-10.329c-0.697-1.41-2.141-2.303-3.716-2.303c-1.572,0-3.015,0.893-3.718,2.303l-5.134,10.329l-11.41,1.691 c-1.561,0.23-2.853,1.326-3.339,2.823c-0.486,1.498-0.086,3.146,1.042,4.247L48.083,80.355z"/><path d="M111.443,13.269H98.378V6.022C98.378,2.696,95.682,0,92.355,0H91.4c-3.326,0-6.021,2.696-6.021,6.022v7.247H39.282V6.022 C39.282,2.696,36.586,0,33.261,0h-0.956c-3.326,0-6.021,2.696-6.021,6.022v7.247H13.371c-6.833,0-12.394,5.559-12.394,12.394 v86.757c0,6.831,5.561,12.394,12.394,12.394h98.073c6.832,0,12.394-5.562,12.394-12.394V25.663 C123.837,18.828,118.275,13.269,111.443,13.269z M109.826,110.803H14.988V43.268h94.838V110.803z"/></g></g></svg>
                                </div>
                                <div class=" rounded px-2 w-full">
                                    <div class="px-3 text-white">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-semibold">Mathematics</p>
                                            <p class="text-sm font-semibold">7-C</p>
                                        </div>
                                        <p class="text-xs">2.30 PM - 3.15 PM, HB1</p>
                                    </div>
                                </div>
                            </div> -->
                            <!-- ***** -->

                            <!-- ****** -->
                            <!-- <div class="flex items-start bg-purple-400 px-3 py-3 my-1">
                                <div class="my-1">
                                    <svg class="w-4 h-4 fill-current text-white" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="124.813px" height="124.813px" viewBox="0 0 124.813 124.813" style="enable-background:new 0 0 124.813 124.813;" xml:space="preserve"><g><g><path d="M48.083,80.355l-1.915,11.374c-0.261,1.555,0.377,3.122,1.65,4.05c1.275,0.926,2.968,1.05,4.361,0.32l10.226-5.338 L72.631,96.1c0.605,0.314,1.268,0.472,1.924,0.472c0.859,0,1.716-0.269,2.439-0.792c1.274-0.928,1.914-2.495,1.651-4.05 l-1.913-11.374l8.234-8.077c1.126-1.103,1.527-2.749,1.044-4.247c-0.485-1.497-1.783-2.593-3.341-2.823l-11.41-1.692 l-5.139-10.329c-0.697-1.41-2.141-2.303-3.716-2.303c-1.572,0-3.015,0.893-3.718,2.303l-5.134,10.329l-11.41,1.691 c-1.561,0.23-2.853,1.326-3.339,2.823c-0.486,1.498-0.086,3.146,1.042,4.247L48.083,80.355z"/><path d="M111.443,13.269H98.378V6.022C98.378,2.696,95.682,0,92.355,0H91.4c-3.326,0-6.021,2.696-6.021,6.022v7.247H39.282V6.022 C39.282,2.696,36.586,0,33.261,0h-0.956c-3.326,0-6.021,2.696-6.021,6.022v7.247H13.371c-6.833,0-12.394,5.559-12.394,12.394 v86.757c0,6.831,5.561,12.394,12.394,12.394h98.073c6.832,0,12.394-5.562,12.394-12.394V25.663 C123.837,18.828,118.275,13.269,111.443,13.269z M109.826,110.803H14.988V43.268h94.838V110.803z"/></g></g></svg>
                                </div>
                                <div class="rounded px-2 w-full">
                                    <div class="px-3 text-white">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-semibold">English</p>
                                            <p class="text-sm font-semibold">6-A</p>
                                        </div>
                                        <p class="text-xs">3.15 PM - 4.00 PM, HB1</p>
                                    </div>
                                </div>
                            </div> -->
                            <!-- ***** -->

                            <!-- ****** -->
                            <!-- <div class="flex items-start bg-purple-400 px-3 py-3 my-1">
                                <div class="my-1">
                                    <svg class="w-4 h-4 fill-current text-white" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="124.813px" height="124.813px" viewBox="0 0 124.813 124.813" style="enable-background:new 0 0 124.813 124.813;" xml:space="preserve"><g><g><path d="M48.083,80.355l-1.915,11.374c-0.261,1.555,0.377,3.122,1.65,4.05c1.275,0.926,2.968,1.05,4.361,0.32l10.226-5.338 L72.631,96.1c0.605,0.314,1.268,0.472,1.924,0.472c0.859,0,1.716-0.269,2.439-0.792c1.274-0.928,1.914-2.495,1.651-4.05 l-1.913-11.374l8.234-8.077c1.126-1.103,1.527-2.749,1.044-4.247c-0.485-1.497-1.783-2.593-3.341-2.823l-11.41-1.692 l-5.139-10.329c-0.697-1.41-2.141-2.303-3.716-2.303c-1.572,0-3.015,0.893-3.718,2.303l-5.134,10.329l-11.41,1.691 c-1.561,0.23-2.853,1.326-3.339,2.823c-0.486,1.498-0.086,3.146,1.042,4.247L48.083,80.355z"/><path d="M111.443,13.269H98.378V6.022C98.378,2.696,95.682,0,92.355,0H91.4c-3.326,0-6.021,2.696-6.021,6.022v7.247H39.282V6.022 C39.282,2.696,36.586,0,33.261,0h-0.956c-3.326,0-6.021,2.696-6.021,6.022v7.247H13.371c-6.833,0-12.394,5.559-12.394,12.394 v86.757c0,6.831,5.561,12.394,12.394,12.394h98.073c6.832,0,12.394-5.562,12.394-12.394V25.663 C123.837,18.828,118.275,13.269,111.443,13.269z M109.826,110.803H14.988V43.268h94.838V110.803z"/></g></g></svg>
                                </div>
                                <div class=" rounded px-2 w-full">
                                    <div class="px-3 text-white">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-semibold">Geography</p>
                                            <p class="text-sm font-semibold">8-B</p>
                                        </div>
                                        <p class="text-xs">4.00 PM - 4.45 PM, HB1</p>
                                    </div>
                                </div>
                            </div> -->
                            <!-- ***** -->
                        <!-- </div> -->
                    <!-- </div> -->
                </div>
            </div>
            @endif
            <!-- end -->

            <div class="w-full lg:w-1/2 px-1 my-3 lg:my-0 md:my-2">
    <div class="bg-white custom-shadow border rounded-lg overflow-hidden h-full">

        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-5 py-3 flex justify-between items-center">
            <h2 class="text-white font-semibold text-lg flex items-center gap-2">
                <svg class="w-5 h-5 fill-current text-white" viewBox="0 0 24 24"><path d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm10-2h2v2h-2v-2zm4 0h2v2h-2v-2zm-4 4h2v2h-2v-2zm4 0h2v2h-2v-2zm-4 4h2v2h-2v-2zm4 0h2v2h-2v-2z"/></svg>
                School QR Code
            </h2>

            <span class="bg-white/20 text-white px-3 py-1 rounded-full text-xs font-medium">
                Mobile App
            </span>
        </div>

        <div class="p-5 flex flex-col lg:flex-row md:flex-row items-stretch gap-5">

            <!-- left: QR code -->
            <div class="w-full lg:w-1/2 md:w-1/2 flex flex-col items-center justify-center text-center border-b lg:border-b-0 md:border-b-0 lg:border-r md:border-r border-dashed border-gray-200 pb-5 lg:pb-0 md:pb-0 lg:pr-5 md:pr-5">
                <div class="p-3 bg-white border-2 border-dashed border-indigo-200 rounded-xl">
                    {!! QrCode::size(150)->generate($qrCode) !!}
                </div>

                <h3 class="font-bold text-gray-800 mt-4">
                    {{ $school->school_name }}
                </h3>

                <p class="text-xs text-gray-400 mt-1 flex items-center justify-center gap-1">
                    <svg class="w-3.5 h-3.5 fill-current text-gray-400" viewBox="0 0 24 24"><path d="M7 2C5.9 2 5 2.9 5 4v16c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2H7zm5 18c-.83 0-1.5-.67-1.5-1.5S11.17 17 12 17s1.5.67 1.5 1.5S12.83 20 12 20zM17 16H7V5h10v11z"/></svg>
                    Scan with the GegoK12 Mobile App
                </p>
            </div>

            <!-- right: instructions -->
            <div class="w-full lg:w-1/2 md:w-1/2 flex flex-col justify-center">
                <p class="text-sm font-semibold text-gray-700 mb-3">How to scan</p>

                <div class="relative pl-9">
                    <span class="absolute left-3.5 top-1 bottom-1 border-l-2 border-dashed border-indigo-100"></span>

                    <div class="relative flex items-start gap-3 pb-4">
                        <span class="absolute text-black text-md font-bold" style="left:-35px">1)</span>
                        <p class="text-sm text-gray-600">Open the mobile app on your phone</p>
                    </div>

                    <div class="relative flex items-start gap-3 pb-4">
                        <span class="absolute text-black text-md font-bold" style="left:-35px">2)</span>
                        <p class="text-sm text-gray-600">Tap the <span class="font-semibold text-gray-800">QR Scanner</span> icon on the home screen</p>
                    </div>

                    <div class="relative flex items-start gap-3 pb-4">
                        <span class="absolute text-black text-md font-bold" style="left:-35px">3)</span>
                        <p class="text-sm text-gray-600">Point your camera at the QR code shown on the left</p>
                    </div>

                    <div class="relative flex items-start gap-3">
                        <span class="absolute flex items-center justify-center w-5 h-5 rounded-full bg-green-500 text-white" style="left:-35px">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4z"/></svg>
                        </span>
                        <p class="text-sm text-gray-600">You're all set! You'll be signed in to your school instantly</p>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
        </div>
        <!--end-->
    </div>
@endsection