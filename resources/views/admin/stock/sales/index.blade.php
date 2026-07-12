@extends(request()->is('stock/*') ? 'layouts.stock.layout' : 'layouts.admin.layout')

@section('content')
    <show-sales url="{{ url('/') }}" mode="{{ request()->is('stock/*') ? 'stock' : 'admin' }}"></show-sales>
@endsection