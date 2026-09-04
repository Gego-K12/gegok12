@extends('layouts.admin.layout')

@section('content')
    <div class="">
        <h1 class="admin-h1 my-3 flex items-center">
            <span class="mx-3">Admission Payment Codes</span>
        </h1>
        @livewire('admin.admission-payment-code.payment-code-list')
    </div>
@endsection
