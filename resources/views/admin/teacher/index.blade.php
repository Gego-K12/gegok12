@extends('layouts.admin.layout')

@section('content')
<div id="teachers-list-page" class="relative">

<div class="flex flex-col justify-between my-3">
   <h1 class="admin-h1 mb-3 font-bold font-exo">Teaching Staff ( {{ $count }} )</h1>

   <div class="bg-white p-2 flex flex-row">
   <div class="pl-5" style="width: 42%;margin-right: auto;">
   	 <div id="search"></div>
      <div id="teacherfilter"></div>
   </div>


    <div class="relative flex items-center w-1/4 lg:justify-end">
    <div class="flex items-center" dusk="add-button">
   <a href="{{url('/admin/teacher/add/')}}" class="no-underline text-white  px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center">
   <span class="mx-1 text-sm font-semibold">+ Add Teaching Staff</span>
   </a>
   </div>

   <div class="relative">
      <button type="button" class="action-menu-toggle bg-gray-100 hover:bg-gray-200 rounded-full w-9 h-9 flex items-center justify-center" aria-label="More actions">
         <i class="fa-solid fa-ellipsis-vertical text-gray-600"></i>
      </button>
      <ul class="action-menu-dropdown hidden list-reset absolute right-0 top-full mt-1 w-44 bg-white shadow-lg rounded z-20 py-1">
         <li>
            <teacher-export url="{{ url('/') }}" searchquery="{{ $query }}"></teacher-export>
         </li>
         <li>
            <a href="{{ url('/admin/import/teacher') }}" id="import-button" class="no-underline flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
               <i class="fa-solid fa-file-import w-4 text-center mr-2 text-gray-500"></i> Import
            </a>
         </li>
         <li>
            <a href="{{ url('/admin/teacher/id-card/') }}" class="no-underline flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
               <i class="fa-solid fa-id-card w-4 text-center mr-2 text-gray-500"></i> Id Card
            </a>
         </li>
         <li>
            <a href="{{ url('/admin/attendance/staff/add') }}" class="no-underline flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
               <i class="fa-solid fa-calendar-check w-4 text-center mr-2 text-gray-500"></i> Attendance
            </a>
         </li>
      </ul>
   </div>
   </div>
</div>
<div class="bg-white p-2 flex flex-wrap my-2">
@include('partials.message')

<teacher-list url="{{url('/')}}"  searchquery="{{$query}}" letter="{{ $alphabet }}" birthday="{{ $birthday }}"></teacher-list>

<teacher-filter url="{{url('/')}}" searchquery="{{$query}}"></teacher-filter>
</div>
</div>

 @endsection
