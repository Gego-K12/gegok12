@extends('layouts.admin.layout')

@section('content')
    <div class="">
        @include('partials.message')
        <div>
            <h1 class="admin-h1 my-3 flex items-center">
                <a href="{{ $prev_url }}" class="rounded-full bg-gray-100 p-2" title="Back">
                    <svg class="w-3 h-3 fill-current text-gray-700" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" style="enable-background:new 0 0 492 492;" xml:space="preserve" width="512px" height="512px"><g><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124 c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844 L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412 c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008 c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788 C492,219.198,479.172,207.418,464.344,207.418z" data-original="#000000" class="active-path" fill=""/></g></g></g> </svg>
                </a>
                <span class="mx-3">Student Profile</span>
            </h1>
        </div>
        <div class="flex flex-col lg:flex-row md:flex-row">
            <div class="w-full lg:w-1/5 md:w-1/5 py-3">
                <!-- start -->
                <div class="bg-white  rounded leading-relaxed">
                    <div class="relative">
                        <div>
                            <img src="{{ $user->userprofile->AvatarPath }}" class="w-full max-h-48 w-auto object-cover">
                        </div>
                        <div class=" mx-auto py-2 ">
                            <ul class="flex justify-center">
                                <li class="mx-2">
                                    <a href="{{url('/admin/student/edit/'.$user->name)}}" title="Edit Member" class="text-white text-xs flex items-center blue-bg rounded p-1" id="edit">
                                        <svg class="w-3 h-3 fill-current text-white" id="Capa_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg"><g><path d="m128.285 260.925h319.073v75h-319.073z" transform="matrix(.707 -.707 .707 .707 -126.717 290.929)"/><path d="m29.021 422.521-29.021 89.479 89.481-29.02z"/><path d="m54.039 186.679h319.073v75h-319.073z" transform="matrix(.707 -.707 .707 .707 -95.964 216.682)"/><path d="m371.541 5.46h90v180h-90z" transform="matrix(.707 -.707 .707 .707 54.502 322.498)"/><path d="m57.148 335.796-17.737 54.689 82.106 82.105 54.689-17.737z"/></g></svg>
                                        <span class="mx-1">Edit</span>
                                    </a>
                                </li>

                                 <li class="mx-2">
                                    

                                    <form action="{{ url('/admin/student/delete', ['name'=>$user->name]) }}" method="POST" class="text-white text-xs flex items-center bg-red-600 rounded p-1" id="delete">
                                        @csrf
                                        @method('delete')
                                   
                                       <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-3 h-3 fill-current text-white"><g><g><g><polygon points="353.574,176.526 313.496,175.056 304.807,412.34 344.885,413.804"></polygon><rect x="235.948" y="175.791" width="40.104" height="237.285"></rect><polygon points="207.186,412.334 198.497,175.049 158.419,176.52 167.109,413.804"></polygon> <path d="M17.379,76.867v40.104h41.789L92.32,493.706C93.229,504.059,101.899,512,112.292,512h286.74 c10.394,0,19.07-7.947,19.972-18.301l33.153-376.728h42.464V76.867H17.379z M380.665,471.896H130.654L99.426,116.971h312.474 L380.665,471.896z"></path></g></g></g> <g><g><path d="M321.504,0H190.496c-18.428,0-33.42,14.992-33.42,33.42v63.499h40.104V40.104h117.64v56.815h40.104V33.42 C354.924,14.992,339.932,0,321.504,0z"></path></g></g></svg>
                                        <button type="submit" class="mx-1">Delete</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="my-2 px-2">
                        <p class="capitalize text-gray-600 text-sm">basic information :</p>
                        <ul class="list-reset text-xs leading-relaxed my-2">
                            <li class="flex py-1">
                                <div class="flex items-center">
                                   <svg class="w-3 h-3 fill-current text-gray-800" id="Capa_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg"><g><path d="m144 249h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"/><path d="m144 313h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"/><path d="m144 377h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"/><path d="m272 249h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"/><path d="m272 313h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"/><path d="m272 377h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"/><path d="m400 249h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"/><path d="m400 313h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"/><path d="m400 377h-32c-8.284 0-15 6.716-15 15s6.716 15 15 15h32c8.284 0 15-6.716 15-15s-6.716-15-15-15z"/><path d="m467 65h-36v-25c0-8.284-6.716-15-15-15s-15 6.716-15 15v25h-130v-25c0-8.284-6.716-15-15-15s-15 6.716-15 15v25h-130v-25c0-8.284-6.716-15-15-15s-15 6.716-15 15v25h-36c-24.813 0-45 20.187-45 45v332c0 24.813 20.187 45 45 45h422c24.813 0 45-20.187 45-45 0-9.682 0-323.575 0-332 0-24.813-20.187-45-45-45zm-437 45c0-8.271 6.729-15 15-15h36v25c0 8.284 6.716 15 15 15s15-6.716 15-15v-25h130v25c0 8.284 6.716 15 15 15s15-6.716 15-15v-25h130v25c0 8.284 6.716 15 15 15s15-6.716 15-15v-25h36c8.271 0 15 6.729 15 15v59h-452zm437 347h-422c-8.271 0-15-6.729-15-15v-243h452v243c0 8.271-6.729 15-15 15z"/></g></svg>
                                    <span class="mx-2 text-gray-700 font-medium">Date Of Birth  :</span>
                                </div>
                                <div class="">
                                    <p>{{ date('d-m-Y',strtotime(optional($user->userprofile)->date_of_birth)) }}</p>
                                </div>
                            </li>
                            <li class="flex py-1">
                                <div class="flex items-center">
                                   <svg class="w-3 h-3 fill-current text-gray-800" id="Layer_1" enable-background="new 0 0 511.805 511.805" height="512" viewBox="0 0 511.805 511.805" width="512" xmlns="http://www.w3.org/2000/svg"><g><path d="m218.3 133.026c-7.04-9.11-13.171-16.409-18.224-21.694-7.182-7.512-13.125-12.168-20.55-12.168s-13.368 4.656-20.55 12.167c-5.053 5.285-11.185 12.584-18.224 21.695-14.86 19.23-31.528 43.594-46.934 68.601-24.292 39.432-65.043 112.577-65.043 159.428 0 83.124 67.626 150.75 150.75 150.75s150.75-67.626 150.75-150.75c0-46.85-40.751-119.996-65.043-159.428-15.405-25.007-32.073-49.37-46.932-68.601z"/><path d="m447.426 57.784c-8.311-13.491-17.317-26.653-25.359-37.061-12.422-16.075-18.291-20.723-26.165-20.723s-13.743 4.648-26.164 20.723c-8.042 10.408-17.048 23.569-25.359 37.061-16.242 26.365-35.603 63.103-35.603 88.395 0 48.042 39.085 87.126 87.126 87.126 48.042 0 87.127-39.085 87.127-87.126 0-25.293-19.361-62.03-35.603-88.395z"/></g></svg>
                                    <span class="mx-2 text-gray-700 font-medium">Blood Group :</span>
                                </div>
                                <div class="">
                                    <p class="capitalize">{{ strtoupper($user->userprofile->blood_group) }}</p>
                                </div>
                            </li>
                            <li class="flex py-1">
                                <div class="flex items-center">
                                    <svg class="w-3 h-3 fill-current text-gray-800" xmlns="http://www.w3.org/2000/svg" id="Layer_1" enable-background="new 0 0 512 512" height="512px" viewBox="0 0 512 512" width="512px"><g><path d="m181 356.391v34.609h-60v60h60v61h60v-61h60v-60h-60v-34.609c60.07-13.678 105-67.18 105-131.391 0-26.389-7.87-50.817-20.958-71.614l50.958-50.959v17.573h60v-120h-120v60h17.573l-50.958 50.958c-20.798-13.088-45.226-20.958-71.615-20.958-74.559 0-135 60.441-135 135 0 64.211 44.93 117.713 105 131.391zm30-206.391c41.353 0 75 33.647 75 75s-33.647 75-75 75-75-33.647-75-75 33.647-75 75-75z" data-original="#000000" class="active-path" fill=""/></g> </svg>
                                    <span class="mx-2 text-gray-700 font-medium">Gender :</span>
                                </div>
                                <div class="">
                                    <p class="capitalize">
                                        @if($user->userprofile->gender == 'male')
                                            Boy
                                        @elseif($user->userprofile->gender == 'female')
                                            Girl
                                        @endif
                                    </p>
                                </div>
                            </li>
                            <li class="flex py-1">
                                <div class="flex items-center">
                                   <svg class="w-3 h-3 fill-current text-gray-800" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><g><g><path d="M411.631,190.282h-111.72c-9.899,0-17.923,8.023-17.923,17.923s8.023,17.923,17.923,17.923h111.72 c9.905,0,17.923-8.023,17.923-17.923S421.531,190.282,411.631,190.282z"/></g></g><g><g><path d="M411.631,285.872h-111.72c-9.899,0-17.923,8.023-17.923,17.923s8.023,17.923,17.923,17.923h111.72 c9.905,0,17.923-8.024,17.923-17.923S421.531,285.872,411.631,285.872z"/></g></g><g><g><path d="M494.077,55.263H17.923C8.023,55.263,0,63.286,0,73.185v365.629c0,9.899,8.023,17.923,17.923,17.923h476.154 c9.899,0,17.923-8.023,17.923-17.923V73.185C512,63.286,503.977,55.263,494.077,55.263z M476.154,420.891H35.846V91.109h440.308 V420.891z"/></g></g><g><g><path d="M155.93,143.683c-28.659,0-51.977,23.318-51.977,51.977s23.318,51.977,51.977,51.977 c28.659,0,51.977-23.318,51.977-51.977S184.589,143.683,155.93,143.683z M155.93,211.79c-8.896,0-16.131-7.235-16.131-16.131 c0-8.896,7.235-16.131,16.131-16.131c8.896,0,16.131,7.235,16.131,16.131C172.061,204.555,164.826,211.79,155.93,211.79z"/></g></g><g><g><path d="M155.93,248.837c-46.779,0-84.835,38.056-84.835,84.83v16.728c0,9.899,8.023,17.923,17.923,17.923h133.825 c9.899,0,17.923-8.018,17.923-17.917v-16.734C240.766,286.893,202.709,248.837,155.93,248.837z M106.952,332.471 c0.633-26.46,22.362-47.795,48.978-47.795c26.61,0,48.338,21.328,48.972,47.795H106.952z"/></g></g></svg>
                                    <span class="mx-2 text-gray-700 font-medium whitespace-no-wrap">Aadhaar Number :</span>
                                </div>
                                <div class="">
                                    <p class="capitalize">
                                        @if(optional($user->userprofile)->aadhar_number != '')
                                            {{ optional($user->userprofile)->aadhar_number }}
                                        @else
                                            --
                                        @endif
                                    </p>
                                </div>
                            </li>
                            <li class="flex py-1">
                                <div class="flex items-center">
                                    <svg class="w-3 h-3 fill-current text-gray-800" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><g><g><path d="M256,0C156.748,0,76,80.748,76,180c0,33.534,9.289,66.26,26.869,94.652l142.885,230.257 c2.737,4.411,7.559,7.091,12.745,7.091c0.04,0,0.079,0,0.119,0c5.231-0.041,10.063-2.804,12.75-7.292L410.611,272.22 C427.221,244.428,436,212.539,436,180C436,80.748,355.252,0,256,0z M384.866,256.818L258.272,468.186l-129.905-209.34 C113.734,235.214,105.8,207.95,105.8,180c0-82.71,67.49-150.2,150.2-150.2S406.1,97.29,406.1,180 C406.1,207.121,398.689,233.688,384.866,256.818z"/></g></g><g><g><path d="M256,90c-49.626,0-90,40.374-90,90c0,49.309,39.717,90,90,90c50.903,0,90-41.233,90-90C346,130.374,305.626,90,256,90z M256,240.2c-33.257,0-60.2-27.033-60.2-60.2c0-33.084,27.116-60.2,60.2-60.2s60.1,27.116,60.1,60.2 C316.1,212.683,289.784,240.2,256,240.2z"/></g></g></svg>
                                    <span class="mx-2 text-gray-700 font-medium">Birth Place :</span>
                                </div>
                                <div class="">
                                    <p>{{ $user->userprofile->birth_place }}</p>
                                </div>
                            </li>
                            <li class="flex py-1">
                                <div class="flex items-center">
                                    <svg class="w-3 h-3 fill-current text-gray-800" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><g><g><path d="M256,0C156.748,0,76,80.748,76,180c0,33.534,9.289,66.26,26.869,94.652l142.885,230.257 c2.737,4.411,7.559,7.091,12.745,7.091c0.04,0,0.079,0,0.119,0c5.231-0.041,10.063-2.804,12.75-7.292L410.611,272.22 C427.221,244.428,436,212.539,436,180C436,80.748,355.252,0,256,0z M384.866,256.818L258.272,468.186l-129.905-209.34 C113.734,235.214,105.8,207.95,105.8,180c0-82.71,67.49-150.2,150.2-150.2S406.1,97.29,406.1,180 C406.1,207.121,398.689,233.688,384.866,256.818z"/></g></g><g><g><path d="M256,90c-49.626,0-90,40.374-90,90c0,49.309,39.717,90,90,90c50.903,0,90-41.233,90-90C346,130.374,305.626,90,256,90z M256,240.2c-33.257,0-60.2-27.033-60.2-60.2c0-33.084,27.116-60.2,60.2-60.2s60.1,27.116,60.1,60.2 C316.1,212.683,289.784,240.2,256,240.2z"/></g></g></svg>
                                    <span class="mx-2 text-gray-700 font-medium">Native Place :</span>
                                </div>
                                <div class="">
                                    <p class="capitalize">{{ $user->userprofile->native_place }}</p>
                                </div>
                            </li>
                            <li class="flex py-1">
                                <div class="flex items-center">
                                    <svg class="w-3 h-3 fill-current text-gray-800" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512.001 512.001" style="enable-background:new 0 0 512.001 512.001;" xml:space="preserve"><g><g><g><path d="M305.271,182.764c-7.789,0-15.578,2.797-15.578,9.387v67.9l-32.352-61.709c-7.59-14.579-11.783-15.578-22.967-15.578 c-7.789,0-15.577,2.996-15.577,9.586v126.813c0,6.391,7.788,9.586,15.577,9.586s15.577-3.195,15.577-9.586v-67.9l37.944,69.099 c3.795,6.99,10.186,8.388,17.375,8.388c7.788,0,15.576-3.195,15.576-9.586V192.15 C320.846,185.561,313.058,182.764,305.271,182.764z"/><path d="M195.834,209.924c5.991,0,9.387-6.39,9.387-13.779c0-6.391-2.797-13.381-9.387-13.381h-71.096 c-6.79,0-13.58,3.195-13.58,9.586v126.814c0,6.391,6.79,9.586,13.58,9.586h71.096c6.59,0,9.387-6.99,9.387-13.381 c0-7.388-3.396-13.779-9.387-13.779h-53.521v-33.95h29.956c6.591,0,9.386-6.39,9.386-11.782c0-6.391-3.395-12.183-9.386-12.183 h-29.956v-33.751H195.834z"/><path d="M438.142,256l71.924-151.419c2.943-6.196,2.506-13.469-1.159-19.267C505.241,79.516,498.859,76,492,76H20 C8.954,76,0,84.954,0,96v320c0,11.046,8.954,20,20,20h472c6.859,0,13.241-3.516,16.906-9.314 c3.665-5.798,4.103-13.07,1.159-19.267L438.142,256z M40,396V116h420.358l-62.424,131.419c-2.579,5.43-2.579,11.732,0,17.162 L460.358,396H40z"/></g></g></g></svg>
                                    <span class="mx-2 text-gray-700 font-medium">Mother Tongue :</span>
                                </div>
                                <div class="">
                                    <p class="capitalize">{{ $user->userprofile->mother_tongue }}</p>
                                </div>
                            </li>
                            <li class="flex py-1">
                                <div class="flex items-center">
                                   <svg class="w-3 h-3 fill-current text-gray-800" id="Capa_1" enable-background="new 0 0 511.999 511.999" height="512" viewBox="0 0 511.999 511.999" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m498.875 220.645-.001-.001c-3.64-3.641-7.804-6.577-12.323-8.741l7.168-7.168c17.502-17.502 17.504-45.983.002-63.489-3.69-3.69-7.868-6.603-12.328-8.737l11.591-11.591c17.5-17.505 17.5-45.985 0-63.489-7.862-7.862-18.162-12.436-29.227-13.04-.59-10.646-4.935-21.121-13.049-29.237-8.456-8.456-19.73-13.113-31.746-13.113-12.015.001-23.29 4.658-31.745 13.114l-11.591 11.591c-2.134-4.459-5.046-8.638-8.734-12.327-17.505-17.504-45.987-17.504-63.491 0l-49.757 49.756c-2.142-4.495-5.072-8.707-8.789-12.424-17.462-17.462-45.876-17.463-63.339 0l-82.274 82.273c-29.734 29.731-46.754 69.395-47.926 111.683-.382 13.79-5.987 26.779-15.783 36.575l-35.532 35.531-.001 192.9h187.744l38.112-38.112c9.795-9.795 22.784-15.4 36.575-15.782 42.287-1.173 81.949-18.193 111.679-47.925l124.763-124.758c17.501-17.502 17.502-45.984.002-63.489zm-79.912-188.606c4.002 0 7.742 1.536 10.531 4.325 5.8 5.801 5.805 15.237.019 21.046-.006.006-.013.012-.02.019l-11.591 11.591c-2.133-4.459-5.045-8.638-8.734-12.327-3.691-3.691-7.874-6.594-12.335-8.728l11.598-11.598c2.79-2.791 6.53-4.328 10.532-4.328zm-94.349 3.591c5.808-5.806 15.257-5.807 21.063-.001 5.807 5.808 5.806 15.257 0 21.064l-49.757 49.756c-2.142-4.497-5.071-8.707-8.79-12.425-3.663-3.664-7.813-6.548-12.238-8.674zm-204.16 119.605 82.274-82.273c2.793-2.793 6.507-4.331 10.457-4.331s7.664 1.538 10.457 4.331c5.845 5.844 5.848 15.352.009 21.203l-82.134 82.133c-29.734 29.731-46.755 69.395-47.926 111.684-.24 8.673-2.55 17.028-6.666 24.473l-20.526-20.526c9.233-13.288 14.45-28.968 14.905-45.393.958-34.59 14.862-67.014 39.15-91.301zm-90.453 175.002 16.141-16.141 21.063 21.063-37.204 37.204zm145.316 150.474h-145.317v-65.921l58.418-58.417 105.619 105.619zm302.342-217.79-124.763 124.757c-24.285 24.286-56.708 38.19-91.297 39.149-16.425.455-32.106 5.672-45.394 14.906l-107.53-107.53c9.233-13.288 14.449-28.968 14.904-45.393.958-34.589 14.862-67.014 39.15-91.3l82.125-82.125.159-.159c2.792-2.787 6.501-4.322 10.447-4.322 3.95 0 7.664 1.538 10.457 4.331 5.845 5.845 5.848 15.353.009 21.204l-53.395 53.395 21.213 21.213 133.146-133.141c5.808-5.808 15.257-5.809 21.063-.002 5.795 5.796 5.807 15.218.036 21.029l-104.768 104.768 21.213 21.213s146.287-146.285 146.293-146.291c2.787-2.777 6.519-4.308 10.511-4.308 4.002 0 7.742 1.537 10.531 4.326 5.806 5.808 5.806 15.258 0 21.065l-146.271 146.272 21.213 21.213 104.732-104.733c5.808-5.806 15.257-5.807 21.063 0 5.807 5.808 5.806 15.258 0 21.064l-118.874 118.874 21.213 21.213 81.751-81.751c2.79-2.79 6.53-4.327 10.532-4.327 4.001 0 7.741 1.536 10.531 4.325 5.806 5.809 5.806 15.258 0 21.065z"/></svg>
                                    <span class="mx-2 text-gray-700 font-medium">Caste :</span>
                                </div>
                                <div class="">
                                    <p class="capitalize">{{ $user->userprofile->caste }}</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="my-4 px-2">
                        <p class="capitalize text-gray-600 text-sm">contact information :</p>
                        <ul class="list-reset text-xs leading-loose my-2">
                            <li class="flex items-baseline py-1">
                                <svg class="w-3 h-3 fill-current text-gray-800" height="682pt" viewBox="-119 -21 682 682.66669" width="682pt" xmlns="http://www.w3.org/2000/svg"><path d="m216.210938 0c-122.664063 0-222.460938 99.796875-222.460938 222.460938 0 154.175781 222.679688 417.539062 222.679688 417.539062s222.242187-270.945312 222.242187-417.539062c0-122.664063-99.792969-222.460938-222.460937-222.460938zm67.121093 287.597656c-18.507812 18.503906-42.8125 27.757813-67.121093 27.757813-24.304688 0-48.617188-9.253907-67.117188-27.757813-37.011719-37.007812-37.011719-97.226562 0-134.238281 17.921875-17.929687 41.761719-27.804687 67.117188-27.804687 25.355468 0 49.191406 9.878906 67.121093 27.804687 37.011719 37.011719 37.011719 97.230469 0 134.238281zm0 0"/></svg>
                                <div class="w-full mx-2">
                                    <p class="text-gray-700 leading-normal" style="word-break: break-all;">
                                        @if($user->userprofile->address==null)
                                            --
                                        @else
                                            {{ optional($user->userprofile)->address }}
                                        @endif
                                    </p>
                                </div>
                            </li>
                            <li class="flex py-1 items-center">
                                <svg class="fill-current w-3 h-3 text-gray-800" height="512" viewBox="0 0 58 58" width="512" xmlns="http://www.w3.org/2000/svg"><g id="Page-1" fill="none" fill-rule="evenodd"><g id="003---Call" fill="rgb(0,0,0)" fill-rule="nonzero" transform="translate(-1)"><path id="Shape" d="m25.017 33.983c-5.536-5.536-6.786-11.072-7.068-13.29-.0787994-.6132828.1322481-1.2283144.571-1.664l4.48-4.478c.6590136-.6586066.7759629-1.685024.282-2.475l-7.133-11.076c-.5464837-.87475134-1.6685624-1.19045777-2.591-.729l-11.451 5.393c-.74594117.367308-1.18469338 1.15985405-1.1 1.987.6 5.7 3.085 19.712 16.855 33.483s27.78 16.255 33.483 16.855c.827146.0846934 1.619692-.3540588 1.987-1.1l5.393-11.451c.4597307-.9204474.146114-2.0395184-.725-2.587l-11.076-7.131c-.7895259-.4944789-1.8158967-.3783642-2.475.28l-4.478 4.48c-.4356856.4387519-1.0507172.6497994-1.664.571-2.218-.282-7.754-1.532-13.29-7.068z"/><path id="Shape" d="m47 31c-1.1045695 0-2-.8954305-2-2-.0093685-8.2803876-6.7196124-14.9906315-15-15-1.1045695 0-2-.8954305-2-2s.8954305-2 2-2c10.4886126.0115735 18.9884265 8.5113874 19 19 0 1.1045695-.8954305 2-2 2z"/><path id="Shape" d="m57 31c-1.1045695 0-2-.8954305-2-2-.0154309-13.800722-11.199278-24.9845691-25-25-1.1045695 0-2-.8954305-2-2s.8954305-2 2-2c16.008947.01763587 28.9823641 12.991053 29 29 0 .530433-.2107137 1.0391408-.5857864 1.4142136-.3750728.3750727-.8837806.5857864-1.4142136.5857864z"/></g></g></svg>
                                <div class="w-full mx-2">
                                    <a href="#" class="blue-text">
                                        @if($user->mobile_no != null)
                                            {{ $user->mobile_no }}
                                        @else
                                            --
                                        @endif
                                    </a>
                                </div>
                            </li>
                            <li class="flex py-1 items-center">
                                <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-3 h-3 fill-current text-gray-800"><g><g><polygon points="339.392,258.624 512,367.744 512,144.896        "></polygon></g></g> <g><g><polygon points="0,144.896 0,367.744 172.608,258.624        "></polygon></g></g> <g><g><path d="M480,80H32C16.032,80,3.36,91.904,0.96,107.232L256,275.264l255.04-168.032C508.64,91.904,495.968,80,480,80z"></path></g></g> <g><g><path d="M310.08,277.952l-45.28,29.824c-2.688,1.76-5.728,2.624-8.8,2.624c-3.072,0-6.112-0.864-8.8-2.624l-45.28-29.856 L1.024,404.992C3.488,420.192,16.096,432,32,432h448c15.904,0,28.512-11.808,30.976-27.008L310.08,277.952z"></path></g></g></svg>
                                <div class="w-full mx-2">
                                    @if($user->email == '')
                                        --
                                    @else
                                        <a href="#" class="blue-text">{{ $user->email }}</a>
                                    @endif
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- end -->
            </div>
            <div class="w-full lg:w-4/5 md:w-4/5 lg:mx-8 md:mx-8">
                <div class="flex lg:items-center lg:justify-between flex-col lg:flex-row relative">
                    <h3 class="font-semibold text-3xl text-gray-700">{{ ucwords($user->FullName) }}</h3>
                    <div class="flex items-center">
                        <div class="py-4 mr-5">
                            <a href="{{ url('/admin/student/showbuspass/show/'.$user->name) }}" class="text-xs">
                                <div class="bg-gray-200 rounded-full w-8 h-8 lg:w-10 md:h-10 md:w-10 md:h-10 flex items-center justify-center mx-auto hover:bg-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 lg:w-5 lg:h-5 md:w-5 md:h-5 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                </svg>
                              </div>
                            <span class="hover:font-semibold hidden lg:block md:block">Bus Pass<span>
                          </a>
                            </div>
                     <div onclick="showsidebar('student-profile-menu')" class="bg-white rounded-full w-6 h-6 ml-auto flex items-center justify-center cursor-pointer">
                     <svg id="Capa_1" enable-background="new 0 0 515.555 515.555" height="512" viewBox="0 0 515.555 515.555" width="512" xmlns="http://www.w3.org/2000/svg" class="w-3 h-3 fill-current text-gray-600"><path d="m303.347 18.875c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138c25.166-25.167 65.97-25.167 91.138 0"></path><path d="m303.347 212.209c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138c25.166-25.167 65.97-25.167 91.138 0"></path><path d="m303.347 405.541c25.167 25.167 25.167 65.971 0 91.138s-65.971 25.167-91.138 0-25.167-65.971 0-91.138c25.166-25.167 65.97-25.167 91.138 0"></path></svg>
                     </div>
                     <div id="student-profile-menu" class="hidden absolute top-0 right-0 bg-white shadow mt-10 rounded">
                    <div class="flex flex-col text-xs w-40 my-1">
                    
                        @if(optional($user)->status == "inactive")
                            <a href="#" rel="{{ url('/admin/user/updateStatus/'.$user->name) }} " class="capitalize text-teal-500 rounded px-4 py-1 font-medium activate my-1 lg:my-0 md:my-0 mr-2 " value="active" id="status">Activate</a>
                        @else

                            <a href="#" rel="{{ url('/admin/user/updateStatus/'.$user->name) }}" class="capitalize text-red-600 rounded px-4 py-1  font-medium activate my-1 lg:my-0 md:my-0 mr-2 " value="inactive" id="status">Deactivate</a>
                        @endif

                         @if(optional($user)->status != "exit")
                            <a href="#" rel="{{ url('/admin/user/updateStatus/'.$user->name) }} " class="capitalize text-teal-500 rounded px-4 py-1 font-medium activate my-1 lg:my-0 md:my-0 mr-2 " value="exit" id="status">Exit</a>
                        @endif
                    
                        @if($user->email != null)
                            @if($user->email_verified == 1)
                                <a href="#" rel="{{ url('/admin/user/resetPassword/'.$user->id) }}" class="capitalize text-gray-700 rounded px-4 py-1 mr-2 font-medium reset my-1 lg:my-0 md:my-0">reset Password</a>
                            @endif

                            @if($user->email_verified != 1)
                                <a href="#" rel="{{ url('/admin/user/'.$user->id.'/verificationcode') }}" class="capitalize text-gray-700 rounded px-4 py-1 mr-2 font-medium verify" id="verify_mail">verify email</a>
                            @endif
                        @endif

                        <a href="{{url('/admin/parent/add?ref_name='.$user->name)}}" class="capitalize text-gray-700 rounded px-4 py-1 mr-2 font-medium my-1 lg:my-0 md:my-0">add parents</a>

                        <a href="{{url('/admin/discipline/add?ref_name='.$user->name)}}" class="capitalize text-gray-700 rounded px-4 py-1 mr-2 font-medium my-1 lg:my-0 md:my-0">add discipline</a>
                       <a href="{{ url('/student/'.$user->id.'/impersonate') }}" target="_blank" class="capitalize text-gray-700 rounded px-4 py-1 mr-2 font-medium my-1 lg:my-0 md:my-0">Login as Student</a>
                    </div>
                    </div>
                </div>
                </div>
                <div class="leading-relaxed">
                    <p class="text-lg text-gray-700 font-semibold">ID: {{ $user->id }}</p>
                    <change-credential url="{{('/')}}" name="{{$user->name}}"  ></change-credential>
                </div>
                <div class="bg-white shadow my-5">
                    <profile-tab url="{{url('/')}}" entity_id="{{ $user->id }}" school_id="{{ $user->school_id }}" name="{{ $user->name }}" mode="admin"></profile-tab>

                    <div id="profile"></div>
                    <div id="notes"></div>
                </div>

                @livewire('admin.profile-extra-tabs', ['entityId' => $user->id, 'scope' => 'student'])
            </div>
        </div>
    </div>

@endsection

@push('scripts')

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('.activate').on('click', function(){
            var link = $(this).attr('rel');
            var status = $(this).attr('value');
            //alert(status);
            swal({
                icon: "info",
                text: "Do you want to change the status ?",
                buttons: {
                    cancel: true,
                    confirm: true,
                },
                allowOutsideClick: false,
            }).then((willChange) => {
                if (willChange) 
                {
                    $.ajax({
                        url: link,
                        data: { status: status },
                        type: "POST",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success:function(data)
                        {
                            //alert(ans);
                            swal({
                                icon: "success",
                                text: "Student Status Updated Successfully",
                            }).then(function(){
                                window.location.reload();
                            });
                        }
                    })
                } 
                else 
                {
                    swal("Cancelled");
                } 
            });
        });
    });


    $(document).ready(function(){
        $('.reset').on('click', function(){
            var link = $(this).attr('rel');
            //alert(link);
            swal({
                icon: "info",
                text: "Do you want to reset password for this student ?",
                buttons: {
                    cancel: true,
                    confirm: true,
                },
                allowOutsideClick: false,
            }).then((willChange) => {
                if (willChange) 
                {
                    $.ajax({
                        url: link,
                        type: "GET",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success:function(data)
                        {
                            //alert(ans);
                            swal({
                                icon: "success",
                                text: "Check your email to reset the password",
                                showConfirmButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                            }).then(function(){
                                window.location.reload();
                            });
                        }
                    })
                }
                else 
                {
                    swal("Cancelled");
                } 
            });
        });
    });

    $(document).ready(function(){
        $('.verify').on('click', function(){
            var link = $(this).attr('rel');
            //alert(link);
            swal({
                icon: "info",
                text: "Do you want to verify email for this student ?",
                buttons: {
                    cancel: true,
                    confirm: true,
                },
                allowOutsideClick: false,
            }).then((willChange) => {
                if (willChange) 
                {
                    $.ajax({
                        url: link,
                        type: "GET",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success:function(data)
                        {
                            //alert(ans);
                            swal({
                                icon: "success",
                                text: "Verification code sent Successfully",
                                showConfirmButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                            }).then(function(){
                                window.location.reload();
                            });
                        }
                    })
                }
                else 
                {
                    swal("Cancelled");
                } 
            });
        });
    });

    $(document).ready(function(){
        $('.exit-member').on('click', function(){
            var link = $(this).attr('rel');
            var name = {!! json_encode($user->name) !!};
            //alert(link);
            swal({
                icon: "info",
                text: "Do you want to exit this student ?",
                buttons: {
                    cancel: true,
                    confirm: true,
                },
                allowOutsideClick: false,
            }).then((willChange) => {
                if (willChange) 
                {
                    $.ajax({
                        url: link,
                        type: "GET",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        success:function(data)
                        {
                            //alert(name);
                            window.location.href="/admin/student/exit/"+name;
                        }
                    })
                }
                else 
                {
                    swal("Cancelled");
                } 
            });
        });
    });
</script>

@endpush
