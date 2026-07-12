@extends(request()->is('stock/*') ? 'layouts.stock.layout' : 'layouts.admin.layout')

@section('content')
    <show-return-order url="{{ url('/') }}" mode="{{ request()->is('stock/*') ? 'stock' : 'admin' }}"></show-return-order>
@endsection