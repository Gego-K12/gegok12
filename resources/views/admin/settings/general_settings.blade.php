@extends('layouts.app')

@section('content')

<div class="container mx-auto">
    <div class="max-w-5xl mx-auto my-6">

    <div class="bg-white shadow-lg rounded-xl border overflow-hidden">

        <!-- Header -->
        <div class="px-6 py-4 border-b bg-gray-50">
            <h1 class="text-2xl font-semibold text-gray-800">
                General Settings
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Manage your website configuration and approval settings.
            </p>
        </div>
        @if(session('success'))
            <div class="mx-6 mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mx-6 mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="" enctype="multipart/form-data">
            @csrf

            <div class="p-6 space-y-6">

                <!-- Site Title -->
                {{-- <div class="tw-form-group">
                    <label class="tw-form-label font-medium">
                        Site Title
                    </label>
                    <input type="text"
                           name="sitetitle"
                           value="{{ config('settings.sitetitle') }}"
                           class="tw-form-control w-full lg:w-128">

                    <span class="text-danger">
                        {{ $errors->first('sitetitle') }}
                    </span>
                </div>

                <!-- Site Name -->
                <div class="tw-form-group">
                    <label class="tw-form-label font-medium">
                        Site Name
                    </label>
                    <input type="text"
                           name="sitename"
                           value="{{ config('settings.sitename') }}"
                           class="tw-form-control w-full lg:w-128">

                    <span class="text-danger">
                        {{ $errors->first('sitename') }}
                    </span>
                </div>

                <!-- Site Logo -->
                <div class="tw-form-group">
                    <label class="tw-form-label font-medium">
                        Site Logo
                    </label>

                    <input type="file"
                           name="sitelogo"
                           class="p-2 border border-dashed rounded lg:w-128">

                    <div class="mt-3">
                        <img src="{{ asset(config('settings.sitelogo')) }}"
                             class="w-48 h-auto rounded border p-2 bg-white">
                    </div>

                    <span class="text-danger">
                        {{ $errors->first('sitelogo') }}
                    </span>
                </div>

                <!-- Site Favicon -->
                <div class="tw-form-group">
                    <label class="tw-form-label font-medium">
                        Site Favicon
                    </label>

                    <input type="file"
                           name="favicon"
                           class="p-2 border border-dashed rounded lg:w-128">

                    <div class="mt-3">
                        <img src="{{ asset(config('settings.favicon')) }}"
                             class="w-20 h-20 rounded border p-2 bg-white">
                    </div>

                    <span class="text-danger">
                        {{ $errors->first('favicon') }}
                    </span>
                </div> --}}

                <div class="bg-gray-50 border rounded-xl p-5">

    <h2 class="text-lg font-semibold text-gray-800 mb-4">
        Approval Settings
    </h2>

    <div class="mb-4">
        <label class="flex items-center">
            <input type="checkbox"
                   name="homework_status"
                   value="1"
                   class="mr-3 h-5 w-5"
                   {{ config('settings.homework_status') == 1 ? 'checked' : '' }}>

            <div>
                <div class="font-medium text-gray-800">
                    Homework Approval
                </div>
                <div class="text-sm text-gray-500">
                    Require principal approval before publishing homework.
                </div>
            </div>
        </label>
    </div>

    <div class="border-t pt-4">
        <label class="flex items-center">
            <input type="checkbox"
                   name="assignment_status"
                   value="1"
                   class="mr-3 h-5 w-5"
                   {{ config('settings.assignment_status') == 1 ? 'checked' : '' }}>

            <div>
                <div class="font-medium text-gray-800">
                    Assignment Approval
                </div>
                <div class="text-sm text-gray-500">
                    Require principal approval before publishing assignments.
                </div>
            </div>
        </label>
    </div>

</div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit"
                            name="submit"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                        Save Settings
                    </button>
                </div>

            </div>
        </form>

    </div>

</div>

</div>

@endsection
