@extends('layouts.admin.settings-layout')

@section('content')
   	<admission-list url="{{ url('/') }}"  slug="{{ $slug }}"></admission-list>
@endsection