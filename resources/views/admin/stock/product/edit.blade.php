@extends(request()->is('stock/*') ? 'layouts.stock.layout' : 'layouts.admin.layout')

@section('content')
    <edit-stock-product url="{{ url('/') }}" productid={{$id}} mode="{{ request()->is('stock/*') ? 'stock' : 'admin' }}"></edit-stock-product>
 
 @endsection