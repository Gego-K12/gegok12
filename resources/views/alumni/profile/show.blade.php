@extends('layouts.alumni.layout')

@section('content')
    <div class="relative">
        @include('partials.message')
        <div class="flex flex-wrap lg:flex-row justify-between">
            <div class="py-2">
                <h1 class="admin-h1 flex items-center">

                    <a href="{{ url('/alumni/dashboard') }}" class="rounded-full bg-gray-100 p-2" title="Back">
                        <svg class="w-3 h-3 fill-current text-gray-700" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" style="enable-background:new 0 0 492 492;" xml:space="preserve" width="512px" height="512px"><g><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124 c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844 L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412 c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008 c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788 C492,219.198,479.172,207.418,464.344,207.418z" data-original="#000000" class="active-path" fill=""/></g></g></g> </svg>
                    </a>
                    <span class="mx-3">Alumni Profile</span>
                </h1>
            </div>
            @if( \Request('name') == \Auth::user()->name )
                <div class="relative flex items-center w-8/12 lg:w-1/4 md:w-1/4 justify-end">
                    <div class="flex items-center w-full justify-end">
                        <a href="{{ url('/alumni/edit') }}" class="no-underline text-white px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center">
                            <span class="mx-1 text-sm font-semibold">Edit</span>
                            <svg class="w-4 h-4 fill-current" xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 56 56"><g fill-rule="evenodd" transform="translate(4 4)"><path fill-rule="nonzero" d="M17.9017783 30.2372721L22.4720783 28.2450721 43.1675783 7.57317211 39.9564783 4.40907211 19.2845783 25.0809721 17.1751783 29.4872721C16.9876783 29.8856721 17.4564783 30.4247721 17.9017783 30.2372721zM44.8782783 5.86227211L46.5659783 4.12787211C47.3862783 3.30757211 47.3862783 2.15907211 46.5890783 1.38567211L46.0499783.823172108C45.3237783.0965721083 44.1750783.190372108 43.4018783.940372108L41.6907783 2.62787211 44.8782783 5.86227211z"/><path d="M36.3084998,6.14879759 L18.5719558,23.8853416 L15.4434606,29.6870807 C14.8987119,30.8445627 16.2607289,32.4108241 17.5544706,31.8660754 L23.8326833,29.0780844 L42.1306479,10.8003141 C42.2717925,11.3241648 42.3470783,11.8750265 42.3470783,12.4434721 L42.3470783,41.3746721 C42.3470783,44.854066 39.5264722,47.6746721 36.0470783,47.6746721 L7.11587829,47.6746721 C3.63648436,47.6746721 0.815878286,44.854066 0.815878286,41.3746721 L0.815878286,12.4434721 C0.815878286,8.96407818 3.63648436,6.14347211 7.11587829,6.14347211 L36.0470783,6.14347211 C36.1346476,6.14347211 36.2217996,6.14525876 36.3084998,6.14879759 L36.3084998,6.14879759 Z"/></g></svg>
                        </a> 
                    </div>
                </div> 
            @endif
        </div>
        <div class="">
            <alumni-profile-details url="{{ url('/') }}"  entity_id="{{ $user->id }}" school_id="{{ $user->school_id }}" name="{{ $user->name }}"></alumni-profile-details>  
        </div>
    </div>
@endsection