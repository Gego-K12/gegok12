@extends('layouts.admin.layout')

@section('content')
    <div class="relative">
        <div class="flex flex-col justify-between my-3">

        <div id="student_count"></div>

        <div class="bg-white p-2 flex flex-wrap items-center lg:flex-row justify-between">
            <div class="flex items-center flex-wrap gap-2">
                <div id="search"></div>
                <div id="memberfilter"></div>
            </div>
            <div class="relative flex items-center w-1/4 lg:justify-end">
                <div class="flex items-center" dusk="add-button">
                    <a href="{{url('/admin/student/add/')}}" class="no-underline text-white  px-4 my-3 mx-1 flex items-center custom-green py-1 justify-center">
                        <span class="mx-1 text-sm font-semibold">+ Add Student</span>
                    </a>
                </div>

                <div class="relative">
                    <button type="button" class="action-menu-toggle bg-gray-100 hover:bg-gray-200 rounded-full w-9 h-9 flex items-center justify-center" aria-label="More actions">
                        <i class="fa-solid fa-ellipsis-vertical text-gray-600"></i>
                    </button>
                    <ul class="action-menu-dropdown hidden list-reset absolute right-0 top-full mt-1 w-44 bg-white shadow-lg rounded z-20 py-1">
                        <li>
                            <student-export url="{{ url('/') }}" searchquery="{{ $query }}"></student-export>
                        </li>
                        <li>
                            <a href="{{ url('/admin/import') }}" id="import-button" class="no-underline flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fa-solid fa-file-import w-4 text-center mr-2 text-gray-500"></i> Import
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        </div>

        @include('partials.message')

        {{-- A selected class already bounds the (unpaginated) load, so drop the
             letter filter and show the whole class; only "All Class" keeps the
             letter (default A) to avoid loading every student in the school. --}}
        <form action="{{ url('/admin/students') }}" enctype="multipart form-data">
            <div class="flex flex-wrap items-center mt-3">
                <input type="hidden" name="alphabet" value="{{ $alphabet ?: 'A' }}">
                <select class="tw-form-control text-xs" name="standard"
                        onchange="if (this.value) this.form.alphabet.disabled = true; this.form.submit();">
                    <option value="">All Class</option>
                    @foreach($standardLinks as $standardLink)
                        <option value="{{ $standardLink->id }}" {{ $standardLink->id == request()->query('standard') ? 'selected' : '' }} {{ $standardLink->id == $standard ? 'selected' : '' }}>{{ $standardLink->StandardSection }}</option>
                    @endforeach
                </select>
            </div>
        </form>

        <member-list url="{{ url('/') }}" searchquery="{{ $query }}" letter="{{ $alphabet }}" standard="{{ $standard }}" birthday="{{ $birthday }}" selected_standard="{{ $selected_standard }}"></member-list>
        <search-filter url="{{ url('/') }}" searchquery="{{ $query }}" selected_standard="{{ $selected_standard }}"></search-filter>
    </div>
@endsection