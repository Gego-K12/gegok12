@extends(request()->is('stock/*') ? 'layouts.stock.layout' : 'layouts.admin.layout')

@section('content')
   <product-list url="{{ url('/') }}" mode="{{ request()->is('stock/*') ? 'stock' : 'admin' }}"  ></product-list>
@endsection