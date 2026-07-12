@extends(request()->is('stock/*') ? 'layouts.stock.layout' : 'layouts.admin.layout')

@section('content')
    <edit-purchase url="{{ url('/') }}" purchaseid="{{$purchaseorder->id}}" mode="{{ request()->is('stock/*') ? 'stock' : 'admin' }}" ></edit-purchase>
 
 @endsection