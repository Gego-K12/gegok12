@extends('layouts.student.layout')

@section('content') 
    <!-- start -->
    <div class="w-full">
        <h1 class="admin-h1 my-3">Dashboard</h1>
        <!-- start -->
        <div class=" flex flex-col lg:flex-row my-2">
            <div class="w-full lg:w-3/4 md:w-full my-2">
                <div class="flex flex-wrap">
                    <div class="w-full lg:w-1/3 px-1 my-1">
                        <div class="bg-white custom-shadow px-4 py-3 border flex items-center">
                            <div class="w-20 h-20 rounded-full bg-light-blue flex items-center justify-center text-blue-500">
                                <svg class="w-10 h-10 fill-current" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 15.692 15.692" style="enable-background:new 0 0 15.692 15.692;" xml:space="preserve"><g><g><path  d="M2.996,5.11c0.037,0.223,0.123,0.364,0.208,0.453C3.406,6.909,4.531,8.158,5.56,8.158 c1.199,0,2.291-1.352,2.501-2.592c0.087-0.088,0.174-0.23,0.212-0.456c0.068-0.252,0.156-0.69,0.002-0.896 C8.267,4.204,8.258,4.193,8.25,4.185c0.145-0.529,0.328-1.623-0.327-2.368C7.865,1.743,7.497,1.304,6.712,1.072L6.337,0.943 C5.719,0.752,5.331,0.709,5.314,0.707c-0.028-0.002-0.057,0-0.084,0.007C5.209,0.72,5.135,0.74,5.078,0.732 c-0.148-0.021-0.37,0.055-0.409,0.07c-0.051,0.021-1.248,0.5-1.611,1.615c-0.034,0.09-0.179,0.564,0.014,1.726 c-0.029,0.02-0.055,0.044-0.077,0.073C2.839,4.42,2.927,4.858,2.996,5.11z"/><path  d="M7.784,13.594c-0.221-0.124-0.461-0.243-0.717-0.356c-0.124-0.055-0.25-0.107-0.375-0.156 c-0.098-0.037-0.214-0.085-0.295-0.106l-1.186-0.32L7.43,8.138l0.951,0.6C8.582,8.864,8.73,8.971,8.892,9.09l0.034,0.025 C9.087,9.234,9.245,9.356,9.4,9.482c0.337,0.272,0.635,0.538,0.912,0.813c0.021,0.021,0.041,0.04,0.062,0.061 c0.093-0.103,0.184-0.195,0.275-0.294c-0.116-0.345-0.257-0.664-0.429-0.92c0,0-0.244-0.333-0.823-0.555 c0,0-0.049-0.015-0.124-0.04C8.758,8.306,8.269,8.151,8.269,8.151C8.164,8.113,8.072,8.076,7.989,8.04 c-0.35-0.173-0.641-0.368-0.701-0.552c0,0,0.202,1.955-1.507,2.001L5.543,9.478C3.994,9.34,3.891,7.484,3.891,7.484 c-0.162,0.509-2.11,1.101-2.11,1.101C1.202,8.807,0.957,9.141,0.957,9.141C0.101,10.411,0,13.237,0,13.237 c0.011,0.646,0.29,0.713,0.29,0.713c1.969,0.879,5.058,1.034,5.058,1.034c0.167,0.004,0.322-0.005,0.477-0.014l0.004,0.016 c0,0,1.508-0.077,3.089-0.423L8.725,14.31C8.568,14.103,8.217,13.836,7.784,13.594z"/><path  d="M7.222,7.571c0.021-0.027,0.044-0.054,0.066-0.084C7.283,7.469,7.282,7.46,7.282,7.46 C7.263,7.499,7.241,7.532,7.222,7.571z"/><path  d="M3.9,7.481L3.895,7.46L3.891,7.482C3.892,7.478,3.896,7.474,3.897,7.47 C3.898,7.471,3.899,7.475,3.9,7.481z"/><path  d="M13.882,8.388c-0.561,0.396-1.084,0.844-1.582,1.315c-0.499,0.474-0.972,0.973-1.427,1.488 c-0.169,0.192-0.333,0.386-0.496,0.581c-0.002-0.003-0.004-0.006-0.005-0.009c-0.24-0.32-0.5-0.605-0.77-0.872 c-0.27-0.266-0.55-0.512-0.838-0.746c-0.145-0.116-0.291-0.23-0.44-0.342C8.169,9.691,8.033,9.59,7.843,9.47l-1.182,2.405 c0.108,0.029,0.265,0.09,0.398,0.142c0.141,0.054,0.279,0.112,0.417,0.173c0.276,0.122,0.545,0.255,0.802,0.398 c0.508,0.284,0.981,0.63,1.251,0.983l0.909,1.192l0.523-1.134c0.263-0.568,0.578-1.162,0.901-1.728 c0.326-0.57,0.674-1.129,1.051-1.668s0.781-1.06,1.233-1.54c0.452-0.477,0.951-0.921,1.546-1.236 C15.046,7.649,14.442,7.996,13.882,8.388z"/></g></g></svg>
                            </div>
                            <div class=" py-1 mx-5">
                                <p class="text-3xl font-semibold text-gray-800">{{ $dashboard['presentPercentage'] }}%</p> 
                                <p class="text-base  item-title">Attendance </p>
                            </div>
                        </div>
                    </div>
                    <div class="w-full lg:w-1/3 px-1 my-1">
                        <div class="bg-white custom-shadow px-4 py-3 border flex items-center">
                            <div class="w-20 h-20 rounded-full bg-light-red flex items-center justify-center text-red-500">
                                <svg class="w-10 h-10 fill-current" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 397.061 397.061" style="enable-background:new 0 0 397.061 397.061;" xml:space="preserve"><g><g><g><path d="M86.204,67.918h31.347c1.443,0,2.612-1.17,2.612-2.612V2.612c0-1.443-1.169-2.612-2.612-2.612H86.204 c-1.443,0-2.612,1.17-2.612,2.612v62.694C83.592,66.749,84.761,67.918,86.204,67.918z"/><path d="M367.804,47.02h-38.661v16.718c0,9.927-7.314,19.853-17.241,19.853h-30.824c-10.637-0.754-19.099-9.216-19.853-19.853 V47.02H135.837v16.718c-0.754,10.637-9.216,19.099-19.853,19.853H85.159c-9.927,0-17.241-9.927-17.241-19.853V47.02H29.257 C15.9,47.305,5.221,58.216,5.224,71.576v64.261h386.612V71.576C391.84,58.216,381.161,47.305,367.804,47.02z"/><path d="M279.51,67.918h31.347c1.443,0,2.612-1.17,2.612-2.612V2.612c0-1.443-1.17-2.612-2.612-2.612H279.51 c-1.443,0-2.612,1.17-2.612,2.612v62.694C276.898,66.749,278.067,67.918,279.51,67.918z"/><path d="M159.347,291.527l-7.314,42.318l37.616-19.853l3.657-1.045l3.657,1.045l37.616,19.853l-7.314-42.318 c-0.279-2.456,0.478-4.917,2.09-6.792l30.825-29.257l-42.318-6.269c-2.474-0.395-4.609-1.948-5.747-4.18l-18.808-38.139 l-18.808,38.139c-1.138,2.232-3.273,3.785-5.747,4.18l-42.318,6.269l30.825,29.257 C158.869,286.609,159.626,289.07,159.347,291.527z"/><path d="M5.224,373.551c0.284,13.068,10.961,23.513,24.033,23.51h338.547c13.071,0.003,23.748-10.442,24.033-23.51V151.51H5.224 V373.551z M102.4,247.641c0.834-2.854,3.312-4.919,6.269-5.224l53.812-8.359l24.033-48.588c2.159-3.751,6.951-5.041,10.702-2.882 c1.198,0.69,2.192,1.684,2.882,2.882l24.033,48.588l53.812,8.359c2.957,0.305,5.436,2.371,6.269,5.224 c0.948,2.795,0.124,5.885-2.09,7.837l-38.661,37.616l9.404,53.812c0.363,2.827-0.838,5.628-3.135,7.314 c-1.373,0.986-3.012,1.532-4.702,1.567c-1.307,0.124-2.613-0.249-3.657-1.045l-48.065-25.078l-48.065,25.078 c-2.62,1.596-5.958,1.388-8.359-0.522c-2.297-1.687-3.497-4.488-3.135-7.314l9.404-53.812l-38.661-37.616 C102.276,253.526,101.452,250.435,102.4,247.641z"/></g></g></g></svg>
                            </div>
                            <div class="py-1 mx-5">
                                <p class="text-3xl font-semibold text-gray-800">{{ $dashboard['upcomingeventCount'] }}</p> 
                                <p class="text-base  item-title">Upcoming Events</p>
                            </div>
                        </div>
                    </div>

                    <!--upcoming holidays-->
                    <div class="w-full lg:w-1/3 px-1 my-1">
                        <a href="{{ url('/student/holidays') }}" target="_blank">
                            <div class="bg-white custom-shadow px-4 py-3 border flex items-center">
                                <div class="w-20 h-20 rounded-full bg-light-red flex items-center justify-center text-red-500">
                                    <svg class="w-10 h-10 fill-current" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 397.061 397.061" style="enable-background:new 0 0 397.061 397.061;" xml:space="preserve"><g><g><g><path d="M86.204,67.918h31.347c1.443,0,2.612-1.17,2.612-2.612V2.612c0-1.443-1.169-2.612-2.612-2.612H86.204 c-1.443,0-2.612,1.17-2.612,2.612v62.694C83.592,66.749,84.761,67.918,86.204,67.918z"/><path d="M367.804,47.02h-38.661v16.718c0,9.927-7.314,19.853-17.241,19.853h-30.824c-10.637-0.754-19.099-9.216-19.853-19.853 V47.02H135.837v16.718c-0.754,10.637-9.216,19.099-19.853,19.853H85.159c-9.927,0-17.241-9.927-17.241-19.853V47.02H29.257 C15.9,47.305,5.221,58.216,5.224,71.576v64.261h386.612V71.576C391.84,58.216,381.161,47.305,367.804,47.02z"/><path d="M279.51,67.918h31.347c1.443,0,2.612-1.17,2.612-2.612V2.612c0-1.443-1.17-2.612-2.612-2.612H279.51 c-1.443,0-2.612,1.17-2.612,2.612v62.694C276.898,66.749,278.067,67.918,279.51,67.918z"/><path d="M159.347,291.527l-7.314,42.318l37.616-19.853l3.657-1.045l3.657,1.045l37.616,19.853l-7.314-42.318 c-0.279-2.456,0.478-4.917,2.09-6.792l30.825-29.257l-42.318-6.269c-2.474-0.395-4.609-1.948-5.747-4.18l-18.808-38.139 l-18.808,38.139c-1.138,2.232-3.273,3.785-5.747,4.18l-42.318,6.269l30.825,29.257 C158.869,286.609,159.626,289.07,159.347,291.527z"/><path d="M5.224,373.551c0.284,13.068,10.961,23.513,24.033,23.51h338.547c13.071,0.003,23.748-10.442,24.033-23.51V151.51H5.224 V373.551z M102.4,247.641c0.834-2.854,3.312-4.919,6.269-5.224l53.812-8.359l24.033-48.588c2.159-3.751,6.951-5.041,10.702-2.882 c1.198,0.69,2.192,1.684,2.882,2.882l24.033,48.588l53.812,8.359c2.957,0.305,5.436,2.371,6.269,5.224 c0.948,2.795,0.124,5.885-2.09,7.837l-38.661,37.616l9.404,53.812c0.363,2.827-0.838,5.628-3.135,7.314 c-1.373,0.986-3.012,1.532-4.702,1.567c-1.307,0.124-2.613-0.249-3.657-1.045l-48.065-25.078l-48.065,25.078 c-2.62,1.596-5.958,1.388-8.359-0.522c-2.297-1.687-3.497-4.488-3.135-7.314l9.404-53.812l-38.661-37.616 C102.276,253.526,101.452,250.435,102.4,247.641z"/></g></g></g></svg>
                                </div>
                                <div class="py-1 mx-5">
                                    <p class="text-3xl font-semibold text-gray-800">{{ $dashboard['upcomingholidayCount'] }}</p> 
                                    <p class="text-base  item-title">Upcoming Holidays</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    <!--upcoming holidays-->        
                </div>
                <div class="flex my-2 flex-col lg:flex-row md:flex-row">
                    <div class="w-full lg:w-1/3 md:w-1/3 px-1 my-3">
                        <div class="bg-white custom-shadow px-5 py-3 border">
                            <div class="mb-3">
                                <h1 class="text-gray-800 font-semibold text-xl">Attendance</h1>
                            </div>
                            <canvas id="graph"></canvas>
                            <div class="flex items-center justify-between my-1">
                                <div class="border-r w-1/2 mt-4 bar-bg-blue relative student_count">
                                    <p>
                                        <p class="text-sm item-title font-semibold">Present</p>
                                        <!-- <p class="text-lg font-semibold text-gray-800">{{ $dashboard['presentPercentage'] }}%</p> -->
                                        <p class="text-lg font-semibold text-gray-800">{{ $dashboard['presentDay'] }} Days</p>
                                    </p>
                                </div>
                                <div class="w-1/2 text-right mt-4 bar-bg-orange relative student_count student_male_count">
                                    <p>
                                        <p class="text-sm item-title font-semibold ">Absent</p>
                                        <!-- <p class="text-lg font-semibold text-gray-800">{{ $dashboard['absentPercentage'] }}%</p> -->
                                        <p class="text-lg font-semibold text-gray-800">{{ $dashboard['absentDay'] }} Days</p>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="w-full lg:w-2/3 md:w-2/3 my-3 px-1">
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
                                                <p>
                                                    @if($noticeboard->type == 'class')
                                                        {{ $noticeboard->standardLink->StandardSection }}
                                                    @else
                                                        {{ ucwords($noticeboard->type) }}
                                                    @endif
                                                </p>
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
                                                            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" xml:space="preserve" class="w-5 h-5 fill-current mr-1 text-gray-600"><g><g><path d="M472,313v139c0,11.028-8.972,20-20,20H60c-11.028,0-20-8.972-20-20V313H0v139c0,33.084,26.916,60,60,60h392 c33.084,0,60-26.916,60-60V313H472z"></path></g></g><g><g><polygon points="352,235.716 276,311.716 276,0 236,0 236,311.716 160,235.716 131.716,264 256,388.284 380.284,264"></polygon></g></g></svg>
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
                </div>
            </div>
            <div class="w-full lg:w-1/4 md:w-full my-2">
                <div class="bg-white custom-shadow  border my-1 mx-1 rounded">
                    <div class="student_bg px-4 py-3 rounded-t flex">
                        <img src="{{asset(Auth::user()->userprofile->AvatarPath)}}" class="w-20 h-20">
                        <div class="mx-5">
                            <p class="text-xl text-white font-semibold whitespace-no-wrap">{{ Auth::user()->FullName }}</p>
                            <p class="text-white  text-sm py-1">{{ Auth::user()->studentAcademicLatest->roll_number }}</p>
                            <div class="py-1 text-white flex py-1">
                                <div class="my-1">
                                    <svg id="Layer_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 fill-current text-gray-200"><g><path d="m407.579 87.677c-31.073-53.624-86.265-86.385-147.64-87.637-2.62-.054-5.257-.054-7.878 0-61.374 1.252-116.566 34.013-147.64 87.637-31.762 54.812-32.631 120.652-2.325 176.123l126.963 232.387c.057.103.114.206.173.308 5.586 9.709 15.593 15.505 26.77 15.505 11.176 0 21.183-5.797 26.768-15.505.059-.102.116-.205.173-.308l126.963-232.387c30.304-55.471 29.435-121.311-2.327-176.123zm-151.579 144.323c-39.701 0-72-32.299-72-72s32.299-72 72-72 72 32.299 72 72-32.298 72-72 72z"></path></g></svg>
                                </div>
                                <div class="text-sm mx-2">
                                    <p>{{ Auth::user()->userprofile->address }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="">
                        <ul class="list-reset text-sm  item-title">
                            <li class="flex items-center border-b px-3 py-4">
                                <div class="flex items-center w-1/2">
                                    <svg class="w-4 h-4 fill-current" style="color: #a8a8a8;" id="Layer_1" enable-background="new 0 0 480.001 480.001" height="512" viewBox="0 0 480.001 480.001" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m319.427 342.172c-11.782 0-22.472-6-28.594-16.051-2.299-3.774-7.222-4.97-10.994-2.671-3.772 2.298-4.969 7.221-2.67 10.994 4.258 6.99 10.022 12.634 16.732 16.673l-8.291 37.627-19.989-23.017c3.629-4.172 2.753-9.046-.373-11.764-3.332-2.898-8.386-2.547-11.286.787l-14.066 16.176-35.432-39.369c3.015-5.79 4.843-12.146 5.365-18.77 68.574 20.186 137.171-31.569 137.171-102.662v-66.125c0-4.418-3.582-8-8-8s-8 3.582-8 8v66.125c0 50.177-40.822 91-91 91s-91-40.822-91-91v-67.893c19.503-1.107 36.092-13.352 42.004-35.544 33.31 21.332 70.648 35.153 109.832 40.638 4.392.612 8.422-2.451 9.032-6.814.612-4.375-2.438-8.419-6.813-9.032-39.9-5.585-78.836-20.518-113.433-45.01-5.289-3.746-12.654.065-12.622 6.581.001.199-.074 20.04-15.124 29.094-9.517 5.726-19.143 3.877-20.799 3.859-4.089-.039-7.554 3.029-8.02 7.076-.088.774-.057-1.861-.057 64.016-5.344-2.772-9-8.288-9-14.636v-97.46c0-10.477 8.523-19 19-19 3.271 0 6.213-1.992 7.428-5.029 10.928-27.319 37-44.971 66.424-44.971h68.607c39.449 0 71.541 32.093 71.541 71.541v94.919c0 4.418 3.582 8 8 8s8-3.582 8-8v-94.919c0-48.27-39.27-87.541-87.541-87.541h-68.607c-34.32 0-64.917 19.63-79.285 50.421-16.728 2.619-29.567 17.128-29.567 34.579v97.46c0 15.305 10.708 28.168 25.081 31.621 1.493 40.859 26 75.943 60.919 92.639v2.11c0 8.913-3.476 17.287-9.787 23.581-11.193 11.161-25.298 9.761-23.642 9.761-45.636 0-82.571 36.93-82.571 82.571v47.257c0 4.418 3.582 8 8 8s8-3.582 8-8v-47.257c0-36.795 29.775-66.572 66.573-66.571 3.501 0 6.996-.372 10.452-1.111l11.163 50.66c1.413 6.415 9.551 8.476 13.853 3.524l15.761-18.148-9.739 77.911c-.548 4.384 2.563 8.382 6.946 8.93 4.392.547 8.383-2.568 8.931-6.946l11.255-90.041c.509.565 4.956 5.552 5.526 6.055 3.302 2.898 8.332 2.604 11.268-.707 1.582-1.811-2.51 2.892 4.798-5.512l11.275 90.204c.547 4.378 4.537 7.494 8.931 6.946 4.384-.548 7.494-4.546 6.946-8.93l-9.739-77.911 15.761 18.148c4.308 4.961 12.442 2.88 13.853-3.524l11.163-50.662c3.389.72 6.883 1.112 10.451 1.112 36.794.001 66.572 29.775 66.572 66.573v47.257c0 4.418 3.582 8 8 8s8-3.582 8-8v-47.257c0-45.637-36.931-82.573-82.573-82.571zm-125.037 46.572-8.293-37.638c3.05-1.842 5.911-4.035 8.548-6.543l19.396 21.552z"/></svg>
                                    <p class="mx-3">Name</p>
                                </div>
                                <p class="w-1/2 text-gray-800 font-semibold">{{ Auth::user()->userprofile->firstname }}</p>
                            </li>
                
                            <li class="flex items-center border-b px-3 py-4">
                                <div class="flex items-center w-1/2">
                                    <svg class="w-4 h-4 fill-current" style="color: #a8a8a8;" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><g><g><path d="M403.921,0v31.347h35.36l-68.982,65.409c-24.421-24.99-58.474-40.53-96.092-40.53c-50.603,0-94.759,28.112-117.687,69.535 c-1.964-0.086-3.938-0.138-5.924-0.138c-74.118,0-134.417,60.299-134.417,134.418c0,68.816,51.984,125.71,118.743,133.498v41.657 H87.995v31.347h46.929V512h31.347v-45.458h48.977v-31.347h-48.977v-41.657c43.948-5.127,81.488-31.533,102.013-68.616 c1.964,0.086,3.937,0.138,5.922,0.138c74.119,0,134.418-60.299,134.418-134.417c0-25.187-6.969-48.774-19.071-68.944 l74.919-71.038v38.933h31.347V0H403.921z M150.598,363.11c-56.833,0-103.07-46.237-103.07-103.071 c0-54.619,42.705-99.442,96.477-102.853c-2.751,10.7-4.215,21.91-4.215,33.457c0,60.464,40.132,111.726,95.157,128.562 C216.281,345.738,185.432,363.11,150.598,363.11z M249.044,290.6c-44.709-11.26-77.906-51.802-77.906-99.957 c0-10.636,1.62-20.901,4.625-30.561c44.709,11.26,77.906,51.803,77.906,99.958C253.669,270.676,252.048,280.94,249.044,290.6z M280.801,293.495c2.751-10.7,4.215-21.909,4.215-33.456c0-60.464-40.132-111.726-95.156-128.563 c18.666-26.532,49.516-43.905,84.349-43.905c56.834,0,103.071,46.237,103.071,103.071 C377.278,245.261,334.573,290.085,280.801,293.495z"/></g></g></svg>
                                    <p class="mx-3">Gender</p>
                                </div>
                                <p class="w-1/2 text-gray-800 font-semibold">{{ ucwords(Auth::user()->userprofile->gender) }}</p>
                            </li>

                            <li class="flex items-center border-b px-3 py-4">
                                <div class="flex items-center w-1/2">
                                    <svg class="w-4 h-4 fill-current" style="color: #a8a8a8;" id="Capa_1" enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg"><g id="XMLID_2374_"><g id="XMLID_707_"><g id="XMLID_708_"><path id="XMLID_713_" d="m482 108h-14v-14c0-16.542-13.458-30-30-30h-24v-12c0-17.645-14.355-32-32-32s-32 14.355-32 32v12h-84v-12c0-17.645-14.355-32-32-32s-32 14.355-32 32v12h-84v-12c0-17.645-14.355-32-32-32s-32 14.355-32 32v12h-24c-16.542 0-30 13.458-30 30v324c0 16.542 13.458 30 30 30h14v14c0 16.542 13.458 30 30 30h408c16.542 0 30-13.458 30-30v-324c0-16.542-13.458-30-30-30zm-112-56c0-6.617 5.383-12 12-12s12 5.383 12 12v44c0 6.617-5.383 12-12 12s-12-5.383-12-12zm-148 0c0-6.617 5.383-12 12-12s12 5.383 12 12v44c0 6.617-5.383 12-12 12s-12-5.383-12-12zm-148 0c0-6.617 5.383-12 12-12s12 5.383 12 12v44c0 6.617-5.383 12-12 12s-12-5.383-12-12zm-44 32h24v12c0 17.645 14.355 32 32 32s32-14.355 32-32v-12h84v12c0 17.645 14.355 32 32 32s32-14.355 32-32v-12h84v12c0 17.645 14.355 32 32 32s32-14.355 32-32v-12h24c5.514 0 10 4.486 10 10v90h-428v-90c0-5.514 4.486-10 10-10zm462 378c0 5.514-4.486 10-10 10h-408c-5.514 0-10-4.486-10-10v-14h125c5.523 0 10-4.478 10-10s-4.477-10-10-10h-159c-5.514 0-10-4.486-10-10v-214h428v214c0 5.514-4.486 10-10 10h-159c-5.523 0-10 4.478-10 10s4.477 10 10 10h159c16.542 0 30-13.458 30-30v-290h14c5.514 0 10 4.486 10 10z"/><path id="XMLID_710_" d="m271.214 389.279c1.463.769 3.06 1.148 4.652 1.148 2.073 0 4.137-.645 5.879-1.91 3.08-2.237 4.622-6.028 3.978-9.78l-7.107-41.439 30.107-29.347c2.726-2.657 3.707-6.631 2.531-10.251s-4.306-6.259-8.073-6.807l-41.607-6.045-18.607-37.703c-1.685-3.413-5.161-5.574-8.967-5.574-3.807 0-7.283 2.161-8.967 5.574l-18.607 37.703-41.607 6.045c-3.767.548-6.896 3.187-8.073 6.807-1.176 3.62-.195 7.594 2.531 10.251l30.107 29.347-7.107 41.439c-.644 3.752.898 7.543 3.978 9.78s7.163 2.532 10.531.762l37.214-19.565zm-65.8-27.132 4.571-26.65c.557-3.244-.519-6.554-2.876-8.851l-19.362-18.873 26.758-3.888c3.257-.474 6.073-2.52 7.529-5.471l11.966-24.246 11.966 24.247c1.457 2.951 4.272 4.997 7.529 5.471l26.758 3.888-19.362 18.873c-2.357 2.298-3.433 5.607-2.876 8.851l4.571 26.65-23.933-12.583c-1.456-.766-3.055-1.149-4.653-1.149-1.599 0-3.197.383-4.653 1.149z"/><path id="XMLID_709_" d="m234 428c-2.63 0-5.21 1.069-7.07 2.93-1.86 1.86-2.93 4.44-2.93 7.07s1.07 5.21 2.93 7.069c1.86 1.86 4.44 2.931 7.07 2.931s5.21-1.07 7.07-2.931c1.86-1.859 2.93-4.439 2.93-7.069s-1.07-5.21-2.93-7.07c-1.86-1.861-4.44-2.93-7.07-2.93z"/></g></g></g></svg>
                                    <p class="mx-3">D.O.B</p>
                                </div>
                                <p class="w-1/2 text-gray-800 font-semibold">{{ date('d M Y',strtotime(Auth::user()->userprofile->date_of_birth)) }}</p>
                            </li>

                            <li class="flex items-center border-b px-3 py-4">
                                <div class="flex items-center w-1/2">
                                    <svg class="w-4 h-4 fill-current" style="color: #a8a8a8;" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><g><g><path d="M135,0c-33.358,0-60,28.006-60,61c0,33.084,26.916,60,60,60s60-26.916,60-60C195,27.98,168.324,0,135,0z M135,91 c-16.542,0-30-13.458-30-30c0-16.804,13.738-31,30-31s30,14.196,30,31C165,77.542,151.542,91,135,91z"/></g></g><g><g><path d="M467,0H255c-24.813,0-45,20.187-45,45v102.365l-13.183-13.182C188.317,125.682,177.017,121,165,121h-30 c-6.359,0-53.641,0-60,0c-41.355,0-75,33.645-75,75c0,8.529,0,77.68,0,90c0,24.813,20.187,45,45,45c5.257,0,10.307-0.906,15-2.57 V467c0,24.813,20.187,45,45,45c11.515,0,22.033-4.347,30-11.486c7.967,7.139,18.485,11.486,30,11.486c24.813,0,45-20.187,45-45 V268.444c11.469,4.045,24.174,3.266,35.126-2.191L295.631,241H467c24.813,0,45-20.187,45-45V45C512,20.187,491.813,0,467,0z M291.709,209.419l-59.982,29.991c-5.627,2.804-12.616,1.91-17.333-2.806l-8.786-8.787c-9.276-9.276-25.567-2.967-25.607,10.56 c0,0.015-0.002,0.029-0.002,0.044V467c0,8.271-6.729,15-15,15s-15-6.729-15-15V316c0-8.284-6.716-15-15-15s-15,6.716-15,15v151 c0,8.271-6.729,15-15,15s-15-6.729-15-15V286c0-11.31,0-80.756,0-90c0-8.284-6.716-15-15-15s-15,6.716-15,15c0,8.529,0,77.68,0,90 c0,8.271-6.729,15-15,15c-8.271,0-15-6.729-15-15C30,274.69,30,205.244,30,196c0-24.813,20.187-45,45-45c11.31,0,80.756,0,90,0 c4.004,0,7.77,1.561,10.604,4.396l44.707,44.707c4.564,4.565,11.54,5.696,17.314,2.81l40.669-20.335 c7.444-3.725,16.415-0.714,20.124,6.712C302.128,196.711,299.13,205.709,291.709,209.419z M482,196c0,8.271-6.729,15-15,15 H327.419c0.091-0.257,0.191-0.509,0.277-0.768c2.16-6.48,2.808-13.218,1.982-19.8l82.03-41.015 c7.41-3.705,10.413-12.715,6.708-20.125c-3.705-7.41-12.716-10.414-20.125-6.708l-82.025,41.013 c-13.142-12.712-33.613-16.747-51.393-7.849L240,168.185V45c0-8.271,6.729-15,15-15h212c8.271,0,15,6.729,15,15V196z"/></g></g></svg>
                                    <p class="mx-3">Class</p>
                                </div>
                                <p class="w-1/2 text-gray-800 font-semibold">{{ Auth::user()->studentAcademicLatest->standardLink->StandardSection }}</p>
                            </li>

                            <li class="flex items-center border-b px-3 py-4">
                                <div class="flex items-center w-1/2">
                                    <svg class="w-4 h-4 fill-current" style="color: #a8a8a8;" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><g><g><g><circle cx="386" cy="210" r="20"/><path d="M432,40h-26V20c0-11.046-8.954-20-20-20c-11.046,0-20,8.954-20,20v20h-91V20c0-11.046-8.954-20-20-20 c-11.046,0-20,8.954-20,20v20h-90V20c0-11.046-8.954-20-20-20s-20,8.954-20,20v20H80C35.888,40,0,75.888,0,120v312 c0,44.112,35.888,80,80,80h153c11.046,0,20-8.954,20-20c0-11.046-8.954-20-20-20H80c-22.056,0-40-17.944-40-40V120 c0-22.056,17.944-40,40-40h25v20c0,11.046,8.954,20,20,20s20-8.954,20-20V80h90v20c0,11.046,8.954,20,20,20s20-8.954,20-20V80h91 v20c0,11.046,8.954,20,20,20c11.046,0,20-8.954,20-20V80h26c22.056,0,40,17.944,40,40v114c0,11.046,8.954,20,20,20 c11.046,0,20-8.954,20-20V120C512,75.888,476.112,40,432,40z"/><path d="M391,270c-66.72,0-121,54.28-121,121s54.28,121,121,121s121-54.28,121-121S457.72,270,391,270z M391,472 c-44.663,0-81-36.336-81-81s36.337-81,81-81c44.663,0,81,36.336,81,81S435.663,472,391,472z"/><path d="M420,371h-9v-21c0-11.046-8.954-20-20-20c-11.046,0-20,8.954-20,20v41c0,11.046,8.954,20,20,20h29 c11.046,0,20-8.954,20-20C440,379.954,431.046,371,420,371z"/><circle cx="299" cy="210" r="20"/><circle cx="212" cy="297" r="20"/><circle cx="125" cy="210" r="20"/><circle cx="125" cy="297" r="20"/><circle cx="125" cy="384" r="20"/><circle cx="212" cy="384" r="20"/><circle cx="212" cy="210" r="20"/></g></g></g></svg>
                                    <p class="mx-3">Admission Date</p>
                                </div>
                                {{-- <p class="w-1/2 text-gray-800 font-semibold">{{ date('d M Y',strtotime(Auth::user()->userprofile->joining_date)) }}</p> --}}
                                <p class="w-1/2 text-gray-800 font-semibold">{{ Auth::user()->userprofile->joining_date 
        ? date('d M Y', strtotime(Auth::user()->userprofile->joining_date)) 
        : '' }}</p>
                            </li>

                            <li class="flex items-center border-b px-3 py-4">
                                <div class="flex items-center w-1/2">
                                    <svg class="w-4 h-4 fill-current" style="color: #a8a8a8;" height="512" viewBox="0 0 58 58" width="512" xmlns="http://www.w3.org/2000/svg"><g id="Page-1" fill="" fill-rule="evenodd"><g id="003---Call" fill="" fill-rule="nonzero" transform="translate(-1)"><path id="Shape" d="m25.017 33.983c-5.536-5.536-6.786-11.072-7.068-13.29-.0787994-.6132828.1322481-1.2283144.571-1.664l4.48-4.478c.6590136-.6586066.7759629-1.685024.282-2.475l-7.133-11.076c-.5464837-.87475134-1.6685624-1.19045777-2.591-.729l-11.451 5.393c-.74594117.367308-1.18469338 1.15985405-1.1 1.987.6 5.7 3.085 19.712 16.855 33.483s27.78 16.255 33.483 16.855c.827146.0846934 1.619692-.3540588 1.987-1.1l5.393-11.451c.4597307-.9204474.146114-2.0395184-.725-2.587l-11.076-7.131c-.7895259-.4944789-1.8158967-.3783642-2.475.28l-4.478 4.48c-.4356856.4387519-1.0507172.6497994-1.664.571-2.218-.282-7.754-1.532-13.29-7.068z"/><path id="Shape" d="m47 31c-1.1045695 0-2-.8954305-2-2-.0093685-8.2803876-6.7196124-14.9906315-15-15-1.1045695 0-2-.8954305-2-2s.8954305-2 2-2c10.4886126.0115735 18.9884265 8.5113874 19 19 0 1.1045695-.8954305 2-2 2z"/><path id="Shape" d="m57 31c-1.1045695 0-2-.8954305-2-2-.0154309-13.800722-11.199278-24.9845691-25-25-1.1045695 0-2-.8954305-2-2s.8954305-2 2-2c16.008947.01763587 28.9823641 12.991053 29 29 0 .530433-.2107137 1.0391408-.5857864 1.4142136-.3750728.3750727-.8837806.5857864-1.4142136.5857864z"/></g></g></svg>
                                        <p class="mx-3">Phone</p>
                                </div>
                                @if(Auth::user()->mobile_no == '')
                                    <p class="w-1/2 text-gray-800 font-semibold"> -- </p>
                                @else
                                    <p class="w-1/2 text-gray-800 font-semibold">+91 {{ Auth::user()->mobile_no }}</p>
                                @endif
                            </li>

                            <li class="flex items-center border-b px-3 py-4">
                                <div class="flex items-center w-1/2">
                                    <svg class="w-4 h-4 fill-current" style="color: #a8a8a8;" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"><g><g><path d="M467,61H45c-6.927,0-13.412,1.703-19.279,4.51L255,294.789l51.389-49.387c0,0,0.004-0.005,0.005-0.007 c0.001-0.002,0.005-0.004,0.005-0.004L486.286,65.514C480.418,62.705,473.929,61,467,61z"/></g></g><g><g><path d="M507.496,86.728L338.213,256.002L507.49,425.279c2.807-5.867,4.51-12.352,4.51-19.279V106 C512,99.077,510.301,92.593,507.496,86.728z"/></g></g><g><g><path d="M4.51,86.721C1.703,92.588,0,99.073,0,106v300c0,6.923,1.701,13.409,4.506,19.274L173.789,256L4.51,86.721z"/></g></g><g><g><path d="M317.002,277.213l-51.396,49.393c-2.93,2.93-6.768,4.395-10.605,4.395s-7.676-1.465-10.605-4.395L195,277.211 L25.714,446.486C31.582,449.295,38.071,451,45,451h422c6.927,0,13.412-1.703,19.279-4.51L317.002,277.213z"/></g></g></svg>
                                    <p class="mx-3">Email</p>
                                </div>
                                @if(Auth::user()->email == '')
                                    <p class="w-1/2 text-gray-800 font-semibold text-xs"> -- </p>
                                @else
                                    <p class="w-1/2 text-gray-800 font-semibold text-xs">{{ Auth::user()->email }}</p>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <!-- end -->

        <!-- start -->
        @if(config('gexam.enabled', false))
        <div class="mx-2">
            <h1 class="text-gray-800 font-semibold text-xl py-1">Exam Results</h1>
            <div class="bg-white custom-shadow px-2 py-2 my-3">
                <form  method="GET" action="{{ url('/student/dashboard') }}" role="search" enctype="multipart/form-data" class="mb-0">
                    <div class="flex items-center">
                        <div class="w-1/4 px-3 py-2">
                            <input type="text" name="subject" class="rounded bg-gray-200 text-sm px-2 py-2 w-full focus:outline-none" placeholder="Search by Subject...">
                        </div>
                        <div class="w-1/4 px-3 py-2">
                            <input type="text" name="exam" class="rounded bg-gray-200 text-sm px-2 py-2 w-full focus:outline-none" placeholder="Search by Exam...">
                        </div>
                        <div class="w-1/4 px-3 py-2">
                            <input type="text" name="mark" class="rounded bg-gray-200 text-sm px-2 py-2 w-full focus:outline-none" placeholder="Search by Mark...">
                        </div>
                        <!-- <div class="w-1/4 px-3 py-2">
                            <input type="date" name="" class="rounded bg-gray-200 text-sm px-2 py-2 w-full focus:outline-none " placeholder="Search by Exam...">
                        </div> --> <!-- have to work -->
                        <div class="w-1/4 lg:w-1/6 px-3 py-2">
                            <input type="submit" name="search" value="Search" class="w-full bg-blue-500 px-2 py-2 text-center rounded text-white cursor-pointer text-xs lg:text-base md:text-base">
                        </div>
                    </div>
                    <div class="mx-3 py-4 overflow-x-auto">
                        <table class="w-full text-sm custom-table exam_table">
                            <thead>
                                <tr>
                                    <th>Exam Name</th>
                                    <th>Subject</th>
                                    <th>Grade</th>
                                    <th>Mark</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            @if(count($dashboard['marks']) > 0)
                                <tbody>
                                    @foreach($dashboard['marks'] as $mark)
                                        <tr>
                                            <td>{{ $mark->exam->name }}</td>
                                            <td>{{ $mark->subject->name }}</td>
                                            <td>{{ $mark->grade->grades }}</td>
                                            <td>{{ $mark->obtained_marks }} / {{ $mark->total_marks }}</td>
                                            <td>{{$mark->schedule->start_time}}</td>
                                            <!-- <td>{{ $mark->exam->schedule }}</td> --> <!-- have to work -->
                                        </tr>
                                    @endforeach
                                </tbody>
                            @else
                                <tbody>
                                    <td colspan="5">
                                        <p class="text-sm text-gray-900 font-semibold" style="text-align: center;">No Records Found</p>
                                    </td>
                                </tbody>
                            @endif
                        </table>
                    </div>
                </form>
            </div>
        </div>
        @endif
        <!-- end -->
        <!-- start -->
        <div class="flex flex-wrap">
            <!--Task Module-->
            <div class="w-full lg:w-1/3 md:w-full">
                <dashboard-task url="{{ url('/') }}" mode="student"></dashboard-task>
            </div>
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
    <!--end-->

    {{-- Plugin dashboard-widget hook: any installed plugin with has_dashboard_widget=true
         and portal=student gets its resources/views/plugins/{slug}/dashboard-widget.blade.php
         included here automatically, so new plugins never require editing this file. --}}
    @foreach(\App\Models\Plugin::cachedHook('withDashboardWidgetFor', 'student') as $installedPlugin)
        @includeIf($installedPlugin->dashboardWidgetViewName('student'))
    @endforeach
@endsection
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.min.js"></script>
    <script>
        var ctx = document.getElementById('graph').getContext('2d');
    {{-- var present = {!! trans($dashboard['presentPercentage']) !!};
        var absent = {!! trans($dashboard['absentPercentage']) !!}; --}}
        var present = {!! trans($dashboard['presentDay']) !!};
        var absent = {!! trans($dashboard['absentDay']) !!};
        var chart = new Chart(ctx, {
            // The type of chart we want to create
            type: 'doughnut',
            // The data for our dataset
            data: {
                labels: ["Present", "Absent"],
                datasets: [{
                    label: "Attendance",
                    backgroundColor: [
                        "#ffa601", "#304ffe"
                    ],
                    data: [absent , present],
                }]
            },
            // Configuration options go here
            options: {
                legend: {
                    display: false,
                },
                tooltips: {
                    enabled: true,
                    mode: 'index',
                    callbacks: {
                        label: function (tooltipItems, data) {
                            var i, label = [], l = data.datasets.length;
                            for (i = 0; i < l; i += 1) {
                                label[i] = data.datasets[i].label + ': ' + data.datasets[i].data[tooltipItems.index] + '%';
                            }
                            return label;
                        }
                    }
                }
            }
        });
    </script>
@endpush