@extends('layouts.admin.layout')

@section('content')
    <div class="relative">
        <div class="flex flex-wrap lg:flex-row justify-between my-3">
            <div id="admin_alumni_count"></div>
            <!-- <div class="">
                <h1 class="admin-h1 my-3">Alumni ( {{ $count }} )</h1>
            </div> -->
            <div class="w-full lg:w-2/4">
                <div id="batch-filter"></div>
                <div id="teacherfilter"></div>
            </div>
            <div class="relative flex items-center w-1/4 lg:justify-end">
                <div class="flex items-center" dusk="add-button">
                    <a href="{{ url('/admin/alumni/add/') }}" class="no-underline text-white  px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center">
                        <span class="mx-1 text-sm font-semibold">Add</span>
                        <svg class="w-3 h-3 fill-current text-white" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 409.6 409.6" style="enable-background:new 0 0 409.6 409.6;" xml:space="preserve"><g><g><path d="M392.533,187.733H221.867V17.067C221.867,7.641,214.226,0,204.8,0s-17.067,7.641-17.067,17.067v170.667H17.067 C7.641,187.733,0,195.374,0,204.8s7.641,17.067,17.067,17.067h170.667v170.667c0,9.426,7.641,17.067,17.067,17.067 s17.067-7.641,17.067-17.067V221.867h170.667c9.426,0,17.067-7.641,17.067-17.067S401.959,187.733,392.533,187.733z"/></g></g></svg>
                    </a> 
                </div>
            </div>
        </div>
        @include('partials.message')
        <alumni-list url="{{ url('/') }}" searchquery="{{ $query }}"></alumni-list>
        <alumni-batch-filter url="{{ url('/') }}" searchquery="{{ $query }}" batch="{{ $batch }}"></alumni-batch-filter>
    </div>
@endsection