@extends('layouts.admin.layout')
@section('content')

<div class="w-full lg:mx-2">
    <h1 class="admin-h1 my-3 flex items-center">
        <a  href="{{ url('/admin/transport/stoppage') }}" class="rounded-full bg-gray-100 p-2" title="Back">
          <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" xml:space="preserve" width="512px" height="512px" class="w-3 h-3 fill-current text-gray-700"><g><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124    c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844    L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412    c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008    c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788    C492,219.198,479.172,207.418,464.344,207.418z" data-original="#000000" fill="" class="active-path"></path></g></g></g></svg>
        </a>
        <span class="mx-3">Edit Stoppage</span>
    </h1>
    @include('partials.message')
    <div class="bg-white shadow px-4 py-4">
        <form method="POST" action="{{url('/admin/transport/stoppage/'.$stoppage->id.'/update')}}" enctype="multipart/form-data">
            @csrf
           <div class="flex flex-col">
              <div class="w-full lg:w-1/4"> 
                <label for="name" class="tw-form-label">Name</label>
              </div>
              <div class="my-2 w-full">
                <input type="text" name="name" value="{{$stoppage->name}}" class="tw-form-control w-full"><br>
                <span ><p class="text-red-500 text-xs font-semibold">{{ $errors->first('name') }}</p></span>
              </div>
          </div>
        <!--   <div class="flex flex-col">
              <div class="w-full lg:w-1/4"> 
                <label for="distance" class="tw-form-label">Distance(in km)</label>
              </div>
              <div class="my-2 w-full">
                <input type="text" name="distance"  class="tw-form-control w-full" value="{{$stoppage->distance}}"><br>
                <span ><p class="text-red-500 text-xs font-semibold">{{ $errors->first('distance') }}</p></span>
              </div>
            </div> -->
             <div class="flex flex-col">
              <div class=""> 
                <label for="status" class="tw-form-label">Status</label>
              </div>
              <div class="my-2 ">
               <div class="flex items-center">
                <div class="flex items-center lg:w-1/6 md:w-1/6"><input type="radio" name="status" value="0"  class="tw-form-control" {{ $stoppage->status == 0 ? 'checked' : ''}}><span class="mx-2">Inactive</span></div>
                <div class="flex items-center lg:w-1/6 md:w-1/6"><input type="radio" name="status" value="1" class="tw-form-control" {{ $stoppage->status == 1 ? 'checked' : ''}}><span class="mx-2">Active</span></div>
                </div>
                <br>
                <span ><p class="text-red-500 text-xs font-semibold">{{ $errors->first('status') }}</p></span>
              </div>
            </div>
                <div class="flex flex-col lg:flex-row md:flex-row">
                    <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
                        <div class="lg:mr-8 md:mr-8">
                            <div class="mb-2">
                                <label for="address" class="tw-form-label">Address<span class="text-red-500">*</span> </label>
                            </div>
                            <div class="mb-2 w-full relative">
                                <input type="text" name="address" class="tw-form-control w-full" id="address" value="{{$stoppage->address}}" required="Address is required"  onkeyup="codeAddress()"> 
                                <span class="absolute m-2 top-0 right-0">
                                    <a href="#" onclick="codeAddress(); return false;" dusk="getCords" id="getCords">
                                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30.239px" height="30.239px" viewBox="0 0 30.239 30.239" xml:space="preserve" class="w-4 h-4 fill-current text-gray-600"><g><path d="M20.194,3.46c-4.613-4.613-12.121-4.613-16.734,0c-4.612,4.614-4.612,12.121,0,16.735 c4.108,4.107,10.506,4.547,15.116,1.34c0.097,0.459,0.319,0.897,0.676,1.254l6.718,6.718c0.979,0.977,2.561,0.977,3.535,0 c0.978-0.978,0.978-2.56,0-3.535l-6.718-6.72c-0.355-0.354-0.794-0.577-1.253-0.674C24.743,13.967,24.303,7.57,20.194,3.46z M18.073,18.074c-3.444,3.444-9.049,3.444-12.492,0c-3.442-3.444-3.442-9.048,0-12.492c3.443-3.443,9.048-3.443,12.492,0 C21.517,9.026,21.517,14.63,18.073,18.074z"></path></g></svg>
                                    </a>
                                </span>
                            </div>
                            <br>
                <span ><p class="text-red-500 text-xs font-semibold">{{ $errors->first('address') }}</p></span>
                        </div>   
                    </div>

                    <div class="tw-form-group w-full lg:w-1/2 md:w-1/2">
                        <div class="lg:mr-8 md:mr-8">
                            <div id="map_canvas" class="tw-form-control w-full" style="height: 250px;"></div>
                        </div>
                    </div> 

                    <div class="form-group" hidden>
                        <label for="latitude" class="col-md-4 control-label">Latitude</label>
                        <div class="col-md-6">
                            <input id="latitude" type="text" class="tw-form-control w-1/2" name="latitude" value="{{$stoppage->latitude}}"> 
                        </div>
                    </div>

                    <div class="form-group" hidden>
                        <label for="longitude" class="col-md-4 control-label">Longitude</label>
                        <div class="col-md-6">
                            <input id="longitude" type="text" class="tw-form-control w-1/2" name="longitude" value="{{$stoppage->longitude}}"> 
                        </div>
                    </div>
                </div>
                 <button type="submit" class="btn btn-submit blue-bg text-white rounded px-3 py-1 my-2 text-sm font-medium" onclick="codeAddress()">
               Submit
            </button>
        </form>
       </div>
           
       
</div>

@endsection

@push('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key=AIzaSyBO00niIGAyv2GkZZi-W26Ii6ff3YEyu_w"></script>
    <script type="text/javascript">

var map;

function initialize() 
{
    var address = (document.getElementById('address'));
    var autocomplete = new google.maps.places.Autocomplete(address);
    autocomplete.setTypes(['geocode']);
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            return;
        }

        var address = '';
        if (place.address_components) {
            address = [
                (place.address_components[0] && place.address_components[0].short_name || ''),
                (place.address_components[1] && place.address_components[1].short_name || ''),
                (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
        }
    });
    longlat({{$stoppage->latitude}}, {{$stoppage->longitude}});
}

function longlat(lat, lng) 
{
    //Map
    var myLatlng = new google.maps.LatLng(lat, lng);

    var myOptions = {
        zoom: 15,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }

    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);

    var marker = new google.maps.Marker({
        draggable: true,
        position: myLatlng,
        map: map,
        title: "Your location"
    });
    google.maps.event.addListener(marker, 'mouseup', function(event) {
        document.getElementById('latitude').value = event.latLng.lat()
        document.getElementById('longitude').value = event.latLng.lng()
    });

    //map
}

function codeAddress() 
{
    geocoder = new google.maps.Geocoder();
    var address = document.getElementById("address").value;
    geocoder.geocode({ 'address': address }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) 
        {
            //alert("Latitude: "+results[0].geometry.location.lat());
            // alert("Longitude: "+results[0].geometry.location.lng());
            document.getElementById('latitude').value = results[0].geometry.location.lat();
            document.getElementById('longitude').value = results[0].geometry.location.lng();
            longlat(results[0].geometry.location.lat(), results[0].geometry.location.lng());
        } 
        else 
        {
            //alert("Geocode was not successful for the following reason: " + status);
        }
    });
}
google.maps.event.addDomListener(window, 'load', initialize);
</script>
@endpush