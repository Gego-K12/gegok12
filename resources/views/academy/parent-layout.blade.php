@extends('layouts.app')
@section('base-navigation')
<nav class="navbar bg-white w-full flex px-4 py-2 justify-between items-center">
    <a class="text-xl font-semibold" href="{{ url('/learning/academy') }}">Accel Hub Academy</a>
    <form method="POST" action="{{ url('/logout') }}">@csrf<button type="submit" class="px-3 py-2">Logout</button></form>
</nav>
@endsection
@section('base-sidebar')
    @include('academy.menu')
@endsection
@section('base-content')
    @yield('content')
@endsection
