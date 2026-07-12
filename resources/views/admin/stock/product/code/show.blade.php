@extends(request()->is('stock/*') ? 'layouts.stock.layout' : 'layouts.admin.layout')

@section('content')
<div>
<h1 class="admin-h1 my-3 flex items-center">
<span class="mx-3">Product</span>
</h1>
</div>

    <div class="w-full flex bg-white shadow p-6">

		<div class=" w-1/2">
			<div>
<h1 class="admin-h1 my-3 flex ">
<span >Information</span>
</h1>
</div>
    <ul class="list-reset">
      @if($productcode->product->ownership_tracking==1)
      <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Current ownership member:</p>
         @if($productcode->currentowner()['username']!=null)
        <p class="mb-0 w-1/2">{{$productcode->currentowner()['username']}}</p>
         @else
         <p class="mb-0 w-1/2">-----</p>
        @endif
      </li>
      @endif
      @if($productcode->product->location_tracking==1)
       <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">current location:</p>
         @if($productcode->currentlocation()['location']!=null)
        <p class="mb-0 w-1/2">{{$productcode->currentlocation()['location']}}</p>
         @else
         <p class="mb-0 w-1/2">-----</p>
        @endif
      </li>
       @endif
      @if($productcode->product->maintainence_tracking==1)
      <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">current condition:</p>
        @if($productcode->currentcondition()['condition']!=null)
        <p class="mb-0 w-1/2">{{$productcode->currentcondition()['condition']}}</p>
        @else
         <p class="mb-0 w-1/2">-----</p>
        @endif
      </li>
       @endif
      <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Product:</p>
        <p class="mb-0 w-1/2">{{$productcode->product->name}}</p>
      </li>
       <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Purchased from:</p>
        <p class="mb-0 w-1/2">{{$productcode->product->vendor->name}}</p>
      </li>
     
      <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Purchased at:</p>
        <p class="mb-0 w-1/2">{{$productcode->product->purchased_date}}</p>
      </li>
     
      <li class="flex items-center text-sm py-2">
        <p class="mb-0 w-1/2 tw-form-label">Warranty end_at:</p>
         @if($productcode->product->warranty_enddate!=null)
        <p class="mb-0 w-1/2">{{$productcode->product->warranty_enddate}}</p>
         @else
          <p class="mb-0 w-1/2">No warranty</p>
          @endif
      </li>
      
     
     
    </ul>
			
  </div>
  <div class="w-1/2">
        <div>
<h1 class="admin-h1 my-3 flex items-center">
<span >Specification</span>
</h1>
</div>
        @foreach($specification as $detail)
        <div class="flex items-center text-sm py-2">
        <p class="mb-0  w-1/2 tw-form-label ">{{ucfirst($detail['title'])}}</p>
        <p class="mb-0 w-2">{{ucfirst($detail['detail'])}}</p>
        </div>
        @endforeach
</div>

      
		
	</div>


  
   
 
 @endsection
 