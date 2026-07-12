@extends(request()->is('stock/*') ? 'layouts.stock.layout' : 'layouts.admin.layout')

@section('content')
   <list-purchase url="{{ url('/') }}" mode="{{ request()->is('stock/*') ? 'stock' : 'admin' }}" ></list-purchase>
@endsection