@extends('layouts.admin.layout')

@section('content')
<div class="relative">

<div class="flex flex-col justify-between my-3">
   <h1 class="admin-h1 my-3">Non-Teaching Staff ( {{ $count }} )</h1>

   <div class="bg-white p-2 flex flex-row">
   <div class="pl-5" style="width: 42%;margin-right: auto;">
     <div id="search"></div>
     <div id="teacherfilter"></div>
   </div>

    <div class="relative flex items-center w-1/4 lg:justify-end">
    <div class="flex items-center" dusk="add-button">
   <a href="{{url('/admin/staff/add/')}}" class="no-underline text-white  px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center">
   <span class="mx-1 text-sm font-semibold">+ Add Non-Teaching Staff</span>
   </a>
   </div>

   <div class="relative">
      <button type="button" class="action-menu-toggle bg-gray-100 hover:bg-gray-200 rounded-full w-9 h-9 flex items-center justify-center" aria-label="More actions">
         <i class="fa-solid fa-ellipsis-vertical text-gray-600"></i>
      </button>
      <ul class="action-menu-dropdown hidden list-reset absolute right-0 top-full mt-1 w-44 bg-white shadow-lg rounded z-20 py-1">
         <li>
            <staff-export url="{{ url('/') }}" searchquery="{{ $query }}"></staff-export>
         </li>
         <li>
            <a href="{{ url('/admin/staffs/id-card/') }}" class="no-underline flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
               <i class="fa-solid fa-id-card w-4 text-center mr-2 text-gray-500"></i> Id Card
            </a>
         </li>
      </ul>
   </div>
   </div>
   </div>
</div>
<div class="bg-white p-2 flex flex-wrap my-2">
@include('partials.message')

<staff-list url="{{url('/')}}"  searchquery="{{$query}}" birthday="{{ $birthday }}" letter="{{ $alphabet }}"></staff-list>

<staff-filter url="{{url('/')}}" searchquery="{{$query}}"></staff-filter>
</div>
</div>

 @endsection