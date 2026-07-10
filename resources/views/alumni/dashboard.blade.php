@extends('layouts.alumni.layout')

@section('content') 
    <div class="w-full">
        <h1 class="admin-h1 font-plex my-3">Dashboard</h1>
        @include('partials.message')
        <!-- start -->
        <div class=" flex flex-col lg:flex-row my-2">
            <div class="w-full lg:w-3/4 md:w-3/4 my-2">
                <!--profile-->
                <div class="bg-white shadow my-4 px-3 py-2">
                    <div class="flex">   
                        <div class="mx-3 w-full flex flex-col justify-between">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-3xl text-gray-800 font-bold">Welcome {{ ucwords($user->name) }}</h2>
                                    @if($user->alumniprofile->passing_session != '')
                                        <h4 class="text-base text-gray-700 font-bold">{{ $user->alumniprofile->passing_session }} Batch</h4>
                                    @endif
                                </div>
                                @if($user->alumniprofile->passing_session == '')
                                    <div class="">
                                        <a href="{{ url('/alumni/add') }}" class="no-underline text-white  px-4 mx-1 flex items-center custom-green py-1">
                                            <span class="mx-1 text-sm font-semibold">Create My Profile</span>
                                        </a> 
                                    </div>
                                @else
                                    <div class="">
                                        <a href="{{ url('/alumni/show/'.\Auth::user()->name) }}" class="no-underline text-white  px-4 mx-1 flex items-center custom-green py-1">
                                            <span class="mx-1 text-sm font-semibold">Show My Profile</span>
                                        </a> 
                                    </div>
                                @endif
                            </div>             
                        </div>
                    </div>        
                </div>
                <!--profile-->

                <div class="flex flex-wrap">
                    <div class="w-full lg:w-1/2 px-1 my-1">
                        <a href="{{ url('/alumni/index') }}">
                            <div class="bg-white custom-shadow px-4 py-3 border flex items-center">
                                <div class="w-20 h-20 rounded-full bg-light-blue flex items-center justify-center text-blue-500">
                                    <svg class="w-10 h-10 fill-current" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 15.692 15.692" style="enable-background:new 0 0 15.692 15.692;" xml:space="preserve"><g><g><path  d="M2.996,5.11c0.037,0.223,0.123,0.364,0.208,0.453C3.406,6.909,4.531,8.158,5.56,8.158 c1.199,0,2.291-1.352,2.501-2.592c0.087-0.088,0.174-0.23,0.212-0.456c0.068-0.252,0.156-0.69,0.002-0.896 C8.267,4.204,8.258,4.193,8.25,4.185c0.145-0.529,0.328-1.623-0.327-2.368C7.865,1.743,7.497,1.304,6.712,1.072L6.337,0.943 C5.719,0.752,5.331,0.709,5.314,0.707c-0.028-0.002-0.057,0-0.084,0.007C5.209,0.72,5.135,0.74,5.078,0.732 c-0.148-0.021-0.37,0.055-0.409,0.07c-0.051,0.021-1.248,0.5-1.611,1.615c-0.034,0.09-0.179,0.564,0.014,1.726 c-0.029,0.02-0.055,0.044-0.077,0.073C2.839,4.42,2.927,4.858,2.996,5.11z"/><path  d="M7.784,13.594c-0.221-0.124-0.461-0.243-0.717-0.356c-0.124-0.055-0.25-0.107-0.375-0.156 c-0.098-0.037-0.214-0.085-0.295-0.106l-1.186-0.32L7.43,8.138l0.951,0.6C8.582,8.864,8.73,8.971,8.892,9.09l0.034,0.025 C9.087,9.234,9.245,9.356,9.4,9.482c0.337,0.272,0.635,0.538,0.912,0.813c0.021,0.021,0.041,0.04,0.062,0.061 c0.093-0.103,0.184-0.195,0.275-0.294c-0.116-0.345-0.257-0.664-0.429-0.92c0,0-0.244-0.333-0.823-0.555 c0,0-0.049-0.015-0.124-0.04C8.758,8.306,8.269,8.151,8.269,8.151C8.164,8.113,8.072,8.076,7.989,8.04 c-0.35-0.173-0.641-0.368-0.701-0.552c0,0,0.202,1.955-1.507,2.001L5.543,9.478C3.994,9.34,3.891,7.484,3.891,7.484 c-0.162,0.509-2.11,1.101-2.11,1.101C1.202,8.807,0.957,9.141,0.957,9.141C0.101,10.411,0,13.237,0,13.237 c0.011,0.646,0.29,0.713,0.29,0.713c1.969,0.879,5.058,1.034,5.058,1.034c0.167,0.004,0.322-0.005,0.477-0.014l0.004,0.016 c0,0,1.508-0.077,3.089-0.423L8.725,14.31C8.568,14.103,8.217,13.836,7.784,13.594z"/><path  d="M7.222,7.571c0.021-0.027,0.044-0.054,0.066-0.084C7.283,7.469,7.282,7.46,7.282,7.46 C7.263,7.499,7.241,7.532,7.222,7.571z"/><path  d="M3.9,7.481L3.895,7.46L3.891,7.482C3.892,7.478,3.896,7.474,3.897,7.47 C3.898,7.471,3.899,7.475,3.9,7.481z"/><path  d="M13.882,8.388c-0.561,0.396-1.084,0.844-1.582,1.315c-0.499,0.474-0.972,0.973-1.427,1.488 c-0.169,0.192-0.333,0.386-0.496,0.581c-0.002-0.003-0.004-0.006-0.005-0.009c-0.24-0.32-0.5-0.605-0.77-0.872 c-0.27-0.266-0.55-0.512-0.838-0.746c-0.145-0.116-0.291-0.23-0.44-0.342C8.169,9.691,8.033,9.59,7.843,9.47l-1.182,2.405 c0.108,0.029,0.265,0.09,0.398,0.142c0.141,0.054,0.279,0.112,0.417,0.173c0.276,0.122,0.545,0.255,0.802,0.398 c0.508,0.284,0.981,0.63,1.251,0.983l0.909,1.192l0.523-1.134c0.263-0.568,0.578-1.162,0.901-1.728 c0.326-0.57,0.674-1.129,1.051-1.668s0.781-1.06,1.233-1.54c0.452-0.477,0.951-0.921,1.546-1.236 C15.046,7.649,14.442,7.996,13.882,8.388z"/></g></g></svg>
                                </div>
                                <div class=" py-1 mx-5">
                                    <p class="text-3xl font-semibold text-gray-800">{{ $alumnicount }}</p> 
                                    <p class="text-base item-title">Total Alumni</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="w-full lg:w-1/2 px-1 my-1">
                        <a href="{{ url('/alumni/index?passing_session='.$user->alumniprofile->passing_session) }}">
                            <div class="bg-white custom-shadow px-4 py-3 border flex items-center">
                                <div class="w-20 h-20 rounded-full bg-light-red flex items-center justify-center text-red-500">
                                    <svg class="w-10 h-10 fill-current" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 397.061 397.061" style="enable-background:new 0 0 397.061 397.061;" xml:space="preserve"><g><g><g><path d="M86.204,67.918h31.347c1.443,0,2.612-1.17,2.612-2.612V2.612c0-1.443-1.169-2.612-2.612-2.612H86.204 c-1.443,0-2.612,1.17-2.612,2.612v62.694C83.592,66.749,84.761,67.918,86.204,67.918z"/><path d="M367.804,47.02h-38.661v16.718c0,9.927-7.314,19.853-17.241,19.853h-30.824c-10.637-0.754-19.099-9.216-19.853-19.853 V47.02H135.837v16.718c-0.754,10.637-9.216,19.099-19.853,19.853H85.159c-9.927,0-17.241-9.927-17.241-19.853V47.02H29.257 C15.9,47.305,5.221,58.216,5.224,71.576v64.261h386.612V71.576C391.84,58.216,381.161,47.305,367.804,47.02z"/><path d="M279.51,67.918h31.347c1.443,0,2.612-1.17,2.612-2.612V2.612c0-1.443-1.17-2.612-2.612-2.612H279.51 c-1.443,0-2.612,1.17-2.612,2.612v62.694C276.898,66.749,278.067,67.918,279.51,67.918z"/><path d="M159.347,291.527l-7.314,42.318l37.616-19.853l3.657-1.045l3.657,1.045l37.616,19.853l-7.314-42.318 c-0.279-2.456,0.478-4.917,2.09-6.792l30.825-29.257l-42.318-6.269c-2.474-0.395-4.609-1.948-5.747-4.18l-18.808-38.139 l-18.808,38.139c-1.138,2.232-3.273,3.785-5.747,4.18l-42.318,6.269l30.825,29.257 C158.869,286.609,159.626,289.07,159.347,291.527z"/><path d="M5.224,373.551c0.284,13.068,10.961,23.513,24.033,23.51h338.547c13.071,0.003,23.748-10.442,24.033-23.51V151.51H5.224 V373.551z M102.4,247.641c0.834-2.854,3.312-4.919,6.269-5.224l53.812-8.359l24.033-48.588c2.159-3.751,6.951-5.041,10.702-2.882 c1.198,0.69,2.192,1.684,2.882,2.882l24.033,48.588l53.812,8.359c2.957,0.305,5.436,2.371,6.269,5.224 c0.948,2.795,0.124,5.885-2.09,7.837l-38.661,37.616l9.404,53.812c0.363,2.827-0.838,5.628-3.135,7.314 c-1.373,0.986-3.012,1.532-4.702,1.567c-1.307,0.124-2.613-0.249-3.657-1.045l-48.065-25.078l-48.065,25.078 c-2.62,1.596-5.958,1.388-8.359-0.522c-2.297-1.687-3.497-4.488-3.135-7.314l9.404-53.812l-38.661-37.616 C102.276,253.526,101.452,250.435,102.4,247.641z"/></g></g></g></svg>
                                </div>
                                <div class="py-1 mx-5">
                                    <p class="text-3xl font-semibold text-gray-800">{{ $batchcount }}</p> 
                                    <p class="text-base item-title">Batch Mates</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="flex flex-wrap">
                    <!--upcoming events-->
                    <div class="w-full lg:w-full md:w-1/3 px-1 my-3">
                        <div class="bg-white custom-shadow px-3 py-2 border">
                            <div>
                                <h1 class="text-gray-800 font-semibold text-lg border-b mx-2 py-1 pb-3">Upcoming Events</h1>
                            </div>
                            <div class="notice-box">
                                @if(count($events) > 0)
                                    @foreach($events as $events)
                                        <div class="notice-box-list py-3 mx-3 border-b">
                                            <div class="bg-teal-500 text-xs rounded-full inline-block text-white px-2 py-1 my-1 mb-2">
                                                <p>{{ $events->title }}  - {{ $events->batch }}</p>
                                            </div>
                                            <div class="bg-purple-500 text-xs rounded-full inline-block text-white px-2 py-1 my-1 mb-2">
                                                <p>{{ date('d M Y H:i',strtotime($events->start_date)) }}</p>
                                            </div>
                                            <div class="bg-orange-500 text-xs rounded-full inline-block text-white px-2 py-1 my-1 mb-2">
                                                <p>{{ date('d M Y H:i',strtotime($events->end_date)) }}</p>
                                            </div>
                                            <div class="my-1">
                                                <p class="text-sm text-gray-900 font-semibold">{{ $events->description }}</p>
                                            </div>                
                                        </div>
                                    @endforeach
                                @else
                                    <div class="notice-box-list py-3 mx-3 border-b">
                                        <p class="text-sm text-gray-900 font-semibold" style="text-align: center;">No Records Found</p>
                                    </div>
                                @endif
                            </div>   
                        </div>
                    </div>
                    <!--upcoming events-->        
                </div>
            </div>
        </div>
        <!-- end -->
    </div>    
@endsection