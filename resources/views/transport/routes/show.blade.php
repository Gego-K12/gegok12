@extends('layouts.admin.layout')

@section('content')


<div>
<h1 class="admin-h1 my-3 flex items-center">
<a href="{{url('/admin/transport/route')}}" title="Back" class="rounded-full bg-gray-100 p-2">
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" xml:space="preserve" width="512px" height="512px" class="w-3 h-3 fill-current text-gray-700"><g><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124    c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844    L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412    c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008    c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788    C492,219.198,479.172,207.418,464.344,207.418z" data-original="#000000" fill="" class="active-path"></path></g></g></g></svg></a> 
<span class="mx-3">Route</span>
</h1>
</div>

    <div class="bg-white shadow my-5">
	<div class="flex border shadow py-3 px-5 text-white justify-between items-center">
		
		<div class="flex-col">
			<p class="text-gray-700 text-2xl font-medium">{{ucfirst($route->name)}}</p>
			
			
		</div>
		<!-- <div class="flex">
		<div class="flex-col leading-relaxed">
			
			<p  class="text-gray-700 font-medium" ><span class="font-semibold"> </span></p>
			<p  class="text-gray-700 font-medium" ><span class="font-semibold"> </span></p>
			
		</div>
		</div> -->
       <div class="flex">
		<!-- qr code start -->
		  <div class="">

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
					<li class="px-2 py-1"><a href="{{url('/admin/transport/route/'.$route->id.'/edit')}}" class="hover:text-gray-600">Edit</a></li>
					<li class="px-2 py-1"><a href="#" rel="{{url('/admin/transport/route/'.$route->id.'/delete')}}"  class="hover:text-gray-600 delete">Delete</a></li>
				</ul>
			</div>
		</div>
		<!-- end -->
		</div>
	</div>
</div>
</div>
  <div class="bg-white shadow my-5">
   <route-tab url="{{ url('/') }}" routeid="{{$route->id}}"  ></route-tab>
                </div>
<div class="my-4 bg-white shadow px-4 py-3">
    <div class="tw-form-group w-full">
                        <div class="lg:mr-8 md:mr-8">
                            <div id="map" class="tw-form-control w-full" style="height: 250px;"></div>
                        </div>
                    </div> 
    <div id="directions-panel" class="mt-4 leading-loose"></div>
 </div>
 @endsection
 @push('scripts')
 <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key=AIzaSyBO00niIGAyv2GkZZi-W26Ii6ff3YEyu_w"></script>
    <script type="text/javascript">

var map;

function initializee() 
{
  /*@foreach($route->routestoppages as $stoppage)
    longlat({{$stoppage->stop->latitude}}, {{$stoppage->stop->longitude}});
    @endforeach*/
    
    longlat(9.9252007, 78.11977539999998);
}

function initialize() 
{
    //Map
   const directionsService = new google.maps.DirectionsService();
   const directionsRenderer = new google.maps.DirectionsRenderer();
    var myLatlng = new google.maps.LatLng(9.9252007, 78.11977539999998);

    var myOptions = {
        zoom: 13,
        center: myLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }
    const infowindow = new google.maps.InfoWindow();
    map = new google.maps.Map(document.getElementById("map"), myOptions);
    directionsRenderer.setMap(map);
    var locations =  [];
     var waypts =  [];
     var track =  [];
    //console.log(locations);
 @foreach($route->routestoppages as $stoppage)
 var late = {{$stoppage->stop->latitude}};
  var lnge = {{$stoppage->stop->longitude}};
  var stopname="{{$stoppage->stop->name}}";
 var arraynew = [stopname, late, lnge]; 
 locations.push(arraynew);
 @endforeach
 var marker, i;

   /* for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        map: map
      });


      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }*/
   for ( k = 1; k < locations.length-1; k++) {
   
      waypts.push({
        location:locations[k][1]+","+locations[k][2],
        stopover: true,
      });
    }
  /*  for ( k = 1; k < locations.length-1; k++) {
   
      track.push({
        lat:locations[k][1],
        lng: locations[k][2],
      });
    }*/
    var route_start=locations[0][1]+","+locations[0][2];
    var route_end=locations[locations.length-1][1]+","+locations[locations.length-1][2];
/*const flightPath = new google.maps.Polyline({
    path: track,
    geodesic: true,
    strokeColor: "#FF0000",
    strokeOpacity: 1.0,
    strokeWeight: 2,
  });
  flightPath.setMap(map);*/

    var request = {
origin: route_start,
destination: route_end,
waypoints: waypts,
//optimizeWaypoints: true,
travelMode: google.maps.DirectionsTravelMode.DRIVING
};
directionsService.route(request, function(response, status) {
if (status == google.maps.DirectionsStatus.OK) {
  directionsRenderer.setDirections(response);
  var route = response.routes[0];
   console.log(route);

 var summaryPanel = document.getElementById("directions-panel");
        summaryPanel.innerHTML = "";

        // For each route, display summary information.
        for (let i = 0; i < route.legs.length; i++) {
          var routeSegment = i + 1;
          summaryPanel.innerHTML +=
            "<b>Stop Segment: " + routeSegment + "</b><br>";
            summaryPanel.innerHTML +="Start point: ";
          summaryPanel.innerHTML += route.legs[i].start_address + " <br> ";
          summaryPanel.innerHTML +="End point: ";
          summaryPanel.innerHTML += route.legs[i].end_address + "<br>";
          summaryPanel.innerHTML +="Distance: ";
          summaryPanel.innerHTML += route.legs[i].distance.text + "<br>";
          summaryPanel.innerHTML +="Duration: ";
          summaryPanel.innerHTML += route.legs[i].duration.text + "<br><br>";
        }

     
 
}
});


   

   /* var late = {{$stoppage->stop->latitude}};
    var lnge = {{$stoppage->stop->longitude}};
    var marker = new google.maps.Marker({
        draggable: true,
        position: new google.maps.LatLng(late,lnge ),
        map: map,
        title: "Your location"
    });
    var content="{{$stoppage->stop->name}}";
    google.maps.event.addListener(marker, 'click', function () {
                    infowindow.setContent(content);
                    infowindow.open(map, marker);
                });*/
    
    console.log(locations);
   /* @foreach($route->routestoppages as $stoppage)
    //longlat({{$stoppage->stop->latitude}}, {{$stoppage->stop->longitude}});
    var lat = {{$stoppage->stop->latitude}};
    var lng = {{$stoppage->stop->longitude}};
    var myLatlngs = new google.maps.LatLng(lat, lng);
    var marker = new google.maps.Marker({
        draggable: true,
        position: myLatlngs,
        map: map,
        title: "Your location"
    });
    @endforeach*/


   /* google.maps.event.addListener(marker, 'mouseup', function(event) {
        document.getElementById('latitude').value = event.latLng.lat()
        document.getElementById('longitude').value = event.latLng.lng()
    });*/

    //map
}

/*function codeAddress() 
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
}*/
google.maps.event.addDomListener(window, 'load', initialize);
</script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script type="text/javascript">


   $(document).ready(function(){
      $('.delete').on('click', function(){
         var link = $(this).attr('rel');
         swal({
            icon: "info",
            text: "Do you want to delete this Route ?",
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
                        text: "Route Deleted Successfully",
                     }).then(function(){
                        window.location.assign('/admin/transport/route');
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