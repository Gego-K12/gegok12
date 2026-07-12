@extends('layouts.parent.layout')

@section('content')
    <div class="flex items-center justify-center" style="min-height: 70vh;">
        <div class="bg-white custom-shadow border rounded px-8 py-10 text-center max-w-md">
            <i class="fa-solid fa-mobile-screen-button text-5xl text-blue-700 mb-4"></i>
            <h1 class="admin-h1 font-plex text-xl mb-3">Use the GegoK12 app</h1>
            <p class="text-gray-600">
                The parent portal is only available on the GegoK12 mobile app.
                Please log in there with the same email/mobile number and
                password you used here to see your child's activity, homework,
                attendance, and fees.
            </p>
        </div>
    </div>
@endsection
