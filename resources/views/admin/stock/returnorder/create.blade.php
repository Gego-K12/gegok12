@extends(request()->is('stock/*') ? 'layouts.stock.layout' : 'layouts.admin.layout')

@section('content')
    <add-return-order url="{{ url('/') }}" product_id="{{$product_id}}" mode="{{ request()->is('stock/*') ? 'stock' : 'admin' }}"  ></add-return-order>
 
 @endsection