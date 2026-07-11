@extends('layouts.admin.layout')

@section('content')
    <stoppage-list url="{{ url('/') }}" ></stoppage-list>
 
 @endsection
 @push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key=AIzaSyBO00niIGAyv2GkZZi-W26Ii6ff3YEyu_w"></script>

 @endpush  	