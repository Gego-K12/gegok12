@extends('layouts.admin.settings-layout')
@section('content')
    <div class="school-info-page">
        <div class="flex justify-between">
            <h1 class="admin-h1 font-plex my-3">School Info</h1>
            <div class="flex items-center text-white font-thin">
                <a href="{{ url('/admin/schooldetails/editdetail/'.Auth::user()->school_id) }}" class="no-underline text-white px-4 py-1 my-3 mx-1 custom-green rounded">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 56 56"><g fill-rule="evenodd" transform="translate(4 4)"><path fill-rule="nonzero" d="M17.9017783 30.2372721L22.4720783 28.2450721 43.1675783 7.57317211 39.9564783 4.40907211 19.2845783 25.0809721 17.1751783 29.4872721C16.9876783 29.8856721 17.4564783 30.4247721 17.9017783 30.2372721zM44.8782783 5.86227211L46.5659783 4.12787211C47.3862783 3.30757211 47.3862783 2.15907211 46.5890783 1.38567211L46.0499783.823172108C45.3237783.0965721083 44.1750783.190372108 43.4018783.940372108L41.6907783 2.62787211 44.8782783 5.86227211z"/><path d="M36.3084998,6.14879759 L18.5719558,23.8853416 L15.4434606,29.6870807 C14.8987119,30.8445627 16.2607289,32.4108241 17.5544706,31.8660754 L23.8326833,29.0780844 L42.1306479,10.8003141 C42.2717925,11.3241648 42.3470783,11.8750265 42.3470783,12.4434721 L42.3470783,41.3746721 C42.3470783,44.854066 39.5264722,47.6746721 36.0470783,47.6746721 L7.11587829,47.6746721 C3.63648436,47.6746721 0.815878286,44.854066 0.815878286,41.3746721 L0.815878286,12.4434721 C0.815878286,8.96407818 3.63648436,6.14347211 7.11587829,6.14347211 L36.0470783,6.14347211 C36.1346476,6.14347211 36.2217996,6.14525876 36.3084998,6.14879759 L36.3084998,6.14879759 Z"/></g></svg>
                        <div class="mx-1 text-sm font-semibold tracking-wide font-exo">Edit</div> 
                    </div>
                </a>
            </div>
        </div>

        <div class="flex my-3 flex-col lg:flex-row">
            <div class="bg-white shadow leading-loose px-4 w-full"> 
                <ul class="list-reset">
                    <li class="flex pb-2 flex-col lg:flex-row py-3">
                        <p class="font-bold text-base text-gray-800 capitalize w-full lg:w-1/4">School Name</p>
                        <p class="font-bold text-xl text-black capitalize flex items-center w-full lg:w-1/2">{{ $school->name }}</p>
                    </li>
                    @foreach($details as $key => $value)
                        <li class="flex pb-2 flex-col lg:flex-row py-3">
                            @if($key == 'admission_open')
                                <p class="font-bold text-base text-gray-800 capitalize w-full lg:w-1/4">Admission Open Status</p>
                            @else
                                <p class="font-bold text-base text-gray-800 capitalize w-full lg:w-1/4">{{ str_replace('_' , ' ' , ucwords($key)) }}</p>
                            @endif
                            <p class="font-medium text-sm text-black capitalize flex items-center w-full lg:w-1/2">
                                @if($key == 'admission_open')
                                    @if( $value->meta_value == '0' )
                                        <p class="text-white px-4 mx-1 bg-red-500 rounded">Closed</p>
                                    @else
                                        <p class="text-white px-4 mx-1 custom-green rounded">Open</p>
                                        <a href="{{ url('/'.Auth::user()->school->slug.'/admission-form') }}" class="text-white px-4 mx-1 blue-bg rounded">View</a>
                                    @endif
                                @elseif($key != 'school_logo')
                                    @if( ($value->meta_value != null) && ($value->meta_value != '-') )
                                        {{ $value->meta_value }}
                                    @else
                                        NULL
                                    @endif

                                @else
                                    @if( ($value->meta_value != null) && ($value->meta_value != '-') )
                                        <img src="{{ $value->LogoPath }}" class="img-responsive w-32">
                                    @else
                                        NULL
                                    @endif
                                @endif
                            </p>
                        </li>
                    @endforeach
                    <li class="flex pb-2 flex-col lg:flex-row py-3">
                        <p class="font-bold text-base text-gray-800 capitalize w-full lg:w-1/4">Address</p>
                        <p class="font-medium text-sm text-black capitalize flex items-center w-full lg:w-1/2">
                            @if($school->address != null)
                                {{ $school->address }}
                            @else
                                NULL
                            @endif
                        </p>
                    </li>
                    <li class="flex pb-2 flex-col lg:flex-row py-3">
                        <p class="font-bold text-base text-gray-800 capitalize w-full lg:w-1/4">City</p>
                        <p class="font-medium text-sm text-black capitalize flex items-center w-full lg:w-1/2">
                            @if($school->city_id != null)
                                {{ $school->city->name }}
                            @else
                                NULL
                            @endif
                        </p>
                    </li>
                    <li class="flex pb-2 flex-col lg:flex-row py-3">
                        <p class="font-bold text-base text-gray-800 capitalize w-full lg:w-1/4">State</p>
                        <p class="font-medium text-sm text-black capitalize flex items-center w-full lg:w-1/2">
                            @if($school->state_id != null)
                                {{ $school->state->name }}
                            @else
                                NULL
                            @endif
                        </p>
                    </li>
                    <li class="flex pb-2 flex-col lg:flex-row py-3">
                        <p class="font-bold text-base text-gray-800 capitalize w-full lg:w-1/4">Country</p>
                        <p class="font-medium text-sm text-black capitalize flex items-center w-full lg:w-1/2">
                            @if($school->country_id != null)
                                {{ $school->country->name }}
                            @else
                                NULL
                            @endif
                        </p>
                    </li>
                    <li class="flex pb-2 flex-col lg:flex-row py-3">
                        <p class="font-bold text-base text-gray-800 capitalize w-full lg:w-1/4">Pincode</p>
                        <p class="font-medium text-sm text-black capitalize flex items-center w-full lg:w-1/2">
                            @if($school->pincode != null)
                                {{ $school->pincode }}
                            @else
                                NULL
                            @endif
                        </p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection