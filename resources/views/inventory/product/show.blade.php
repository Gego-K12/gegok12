@extends('layouts.admin.layout')

@section('content')
<div>
<h1 class="admin-h1 my-3 flex items-center">
<a href="{{url('/admin/product/show')}}" title="Back" class="rounded-full bg-gray-100 p-2">
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" xml:space="preserve" width="512px" height="512px" class="w-3 h-3 fill-current text-gray-700"><g><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124    c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844    L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412    c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008    c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788    C492,219.198,479.172,207.418,464.344,207.418z" data-original="#000000" fill="" class="active-path"></path></g></g></g></svg></a> 
<span class="mx-3">Product</span>
</h1>
</div>

    <div class="bg-white shadow my-5">
	<div class="flex border shadow py-3 px-5 text-white justify-between">
		
		<div class="flex-col">
			<p class="text-gray-700 text-2xl font-medium">{{ucfirst($product->name)}}</p>
			
			
		</div>
		<!-- <div class="flex">
		<div class="flex-col leading-relaxed">
			
			<p  class="text-gray-700 font-medium" ><span class="font-semibold"> </span></p>
			<p  class="text-gray-700 font-medium" ><span class="font-semibold"> </span></p>
			
		</div>
		</div> -->
       <div class="flex">
		<!-- qr code start -->
		  <div class="mx-4">
          <!-- <img src="https://chart.googleapis.com/chart?cht=qr&chl=Hello+World&chs=160x160&chld=L|0" class="qr-code img-thumbnail img-responsive w-16 h-16"> -->
          <product-qrcode url="{{ url('/') }}" productid="{{$product->id}}"></product-qrcode>
          </div>
		<!-- qr code end -->

		<!-- start -->
		<div class="relative">
			<div id="" class="w-6 h-6 bg-gray-200 rounded-full p-1 flex items-center cursor-pointer" onclick="showsidebar('product-option')">
				<svg class="w-3 h-3 fill-current text-gray-700 mx-auto" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 384 384" style="enable-background:new 0 0 384 384;" xml:space="preserve">
<g>
	<g>
		<circle cx="192" cy="42.667" r="42.667"/>
	</g>
</g>
<g>
	<g>
		<circle cx="192" cy="192" r="42.667"/>
	</g>
</g>
<g>
	<g>
		<circle cx="192" cy="341.333" r="42.667"/>
	</g>
</g>
</svg>
			</div>

			<div id="product-option" class="bg-white shadow rounded hidden absolute right-0 w-20">
				<ul class="list-reset text-sm text-gray-800 leading-relaxed">
					<li class="px-2 py-1"><a href="{{url('/admin/product/'.$product->id.'/edit')}}" class="hover:text-gray-600">Edit</a></li>
					<li class="px-2 py-1"><a href="#" rel="{{url('/admin/product/'.$product->id.'/delete')}}"  class="hover:text-gray-600 delete">Delete</a></li>
				</ul>
			</div>
		</div>
		<!-- end -->
		</div>
	</div>
</div>
<product-detail url="{{ url('/') }}" productid="{{$product->id}}" ownership_status="{{$product->ownership_tracking}}" track_status="{{$product->location_tracking}}" condition_status="{{$product->maintainence_tracking}}"></product-detail>
 <!--  <div class="bg-white shadow my-5">
   <product-tab url="{{ url('/') }}" productid="{{$product->id}}" ownership_status="{{$product->ownership_tracking}}" track_status="{{$product->location_tracking}}" condition_status="{{$product->maintainence_tracking}}" ></product-tab>
                   
     <div id="product"></div>
                </div> -->

   
 
 @endsection
 @push('scripts')

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">


   $(document).ready(function(){
      $('.delete').on('click', function(){
         var link = $(this).attr('rel');
         swal({
            icon: "info",
            text: "Do you want to delete this Product ?",
            buttons: {
               cancel: true,
               confirm: true,
            },
            allowOutsideClick: false,
         }).then((willChange) => {
            if (willChange) 
            {
               $.ajax({
                  url: link,
                  type: "delete",
                  headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                  success:function(data)
                  {
                     swal({
                        icon: "success",
                        text: "Product Deleted Successfully",
                     }).then(function(){
                        window.location.assign("{{ url('/admin/product/show') }}");
                     });
                  }
               })
            }
            else 
            {
               swal("Cancelled");
            } 
         });
      });
   });
</script>

@endpush 