@extends(request()->is('stock/*') ? 'layouts.stock.layout' : 'layouts.admin.layout')

@section('content')
    <add-sales url="{{ url('/') }}" product_id="{{$product_id}}" mode="{{ request()->is('stock/*') ? 'stock' : 'admin' }}" ></add-sales>
 
 @endsection