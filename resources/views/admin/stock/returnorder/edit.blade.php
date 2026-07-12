@extends(request()->is('stock/*') ? 'layouts.stock.layout' : 'layouts.admin.layout')

@section('content')
    <edit-return-order url="{{ url('/') }}" returnorderid="{{ $returnorder->id }}" mode="{{ request()->is('stock/*') ? 'stock' : 'admin' }}"></edit-return-order>
 
 @endsection