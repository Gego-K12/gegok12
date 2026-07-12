@extends(request()->is('stock/*') ? 'layouts.stock.layout' : 'layouts.admin.layout')

@section('content')
    <edit-sales url="{{ url('/') }}" salesid="{{ $salesorder->id }}" mode="{{ request()->is('stock/*') ? 'stock' : 'admin' }}"></edit-sales>
 
 @endsection