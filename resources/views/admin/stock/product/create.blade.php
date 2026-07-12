@extends(request()->is('stock/*') ? 'layouts.stock.layout' : 'layouts.admin.layout')

@section('content')
    <add-stock-product url="{{ url('/') }}" mode="{{ request()->is('stock/*') ? 'stock' : 'admin' }}" ></add-stock-product>
 
 @endsection