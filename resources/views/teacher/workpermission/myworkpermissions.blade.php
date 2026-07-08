@extends('layouts.teacher.layout')

@section('content')
    <div class="relative">
        <div class="flex flex-wrap lg:flex-row justify-between">
            <div>
                <h1 class="admin-h1 my-3">My Work Permissions</h1>
            </div>
            <div class="relative flex items-center w-8/12 lg:w-1/4 md:w-1/4 justify-end">
                <div class="flex items-center w-full justify-end">
                    <a href="{{ url('/teacher/workpermission/add') }}" class="no-underline text-white px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center">
                        <span class="mx-1 text-sm font-semibold whitespace-no-wrap">Apply Permission</span>
                    </a>
                </div>
            </div>
        </div>
        @include('partials.message')
        <workpermission-teacher-list url="{{ url('/') }}" type="apply" mode="mine"></workpermission-teacher-list>
    </div>
@endsection
