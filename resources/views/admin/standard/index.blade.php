@extends('layouts.admin.settings-layout')

@section('content')
<div class="relative">

   @include('partials.message')
   <livewire:admin.standard.standard-list />
</div>
@endsection