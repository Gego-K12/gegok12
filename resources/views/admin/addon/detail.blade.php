@extends('layouts.siteadmin.layout')

@section('content')
<div class="relative">

<livewire:admin.addon.detail :slug="$slug"/> 
   
</div>

 @endsection