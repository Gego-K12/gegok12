@extends('layouts.admin.layout')

@section('content')
 

<div>
<h1 class="admin-h1 my-3 flex items-center">
<a href="{{url('/admin/transport/service')}}" title="Back" class="rounded-full bg-gray-100 p-2">
<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 492 492" xml:space="preserve" width="512px" height="512px" class="w-3 h-3 fill-current text-gray-700"><g><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124    c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844    L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412    c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008    c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788    C492,219.198,479.172,207.418,464.344,207.418z" data-original="#000000" fill="" class="active-path"></path></g></g></g></svg></a> 
<span class="mx-3">Service</span>
</h1>
</div>
<input type="checkbox" id="myCheck"  onclick="displayMarkers();"> Show marker
 

    <div class="bg-white shadow my-5">
	
 <div class="tw-form-group w-full">
                        
                            <div id="map" class="tw-form-control w-full" style="height: 500px;"></div>
                       
                    </div> 
                     <!-- <div id="directions-panel"></div> -->
                </div>

   
 
 @endsection
 @push('scripts')
 <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key=AIzaSyBO00niIGAyv2GkZZi-W26Ii6ff3YEyu_w"></script>
    <script type="text/javascript">

var map;



function initialize(hide) 
{
  //alert(hide);
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
  //console.log(track);
 @foreach($service->servicedetails as $tracking)
 var late = {{$tracking->latitude}};
  var lnge = {{$tracking->longitude}};
  var time="{{date('d-m-yy g:i a', strtotime($tracking->created_at))}}";
 var arraynew = [time, late, lnge]; 
 locations.push(arraynew);
 @endforeach
 console.log(locations);
 var marker, i;
if(hide==1){
    for (i = 0; i < locations.length; i++) {  
      marker = new google.maps.Marker({
        //position: new google.maps.LatLng(locations[i][1], locations[i][2]),
        position:{ lat:locations[i][1], lng:locations[i][2] },
        map: map
      });


      google.maps.event.addListener(marker, 'click', (function(marker, i) {
        return function() {
          infowindow.setContent(locations[i][0]);
          infowindow.open(map, marker);
        }
      })(marker, i));
    }
  }
@if($service->currentlocation()!=null)    
var current_lat={{$service->currentlocation()->latitude}};
var current_lon={{$service->currentlocation()->longitude}};
var service_start="{{date('d-m-yy g:i a', strtotime($service->currentlocation()->created_at))}}";
//console.log(current_lat);
 

     marker = new google.maps.Marker({
        position: new google.maps.LatLng(current_lat,current_lon ),
        icon: {
    url:
      "https://images.vexels.com/media/users/3/134420/isolated/lists/cb5d873d48e56e33a6971ef927cf430d-school-bus-illustration.png",
        size: new google.maps.Size(70,40),
              origin: new google.maps.Point(0, 0),
             // anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(50, 60),
    },

        map: map
      });


      google.maps.event.addListener(marker, 'click', function() {
        
          infowindow.setContent(service_start);
          infowindow.open(map, marker);
        
      });

 @endif

   for ( k = 1; k < locations.length; k++) {
   
      waypts.push({
        location:locations[k][1]+","+locations[k][2],
        stopover: false,
      });
    }
       for ( k = 0; k < locations.length; k++) {
   
      track.push({
        lat:locations[k][1],
        lng: locations[k][2],
      });
    }

/*
    var route_start="{{$service->route->startpoint()->latitude}},{{$service->route->startpoint()->longitude}}";
    var route_end="{{$service->route->endpoint()->latitude}},{{$service->route->endpoint()->longitude}}";*/
   if(locations.length>0){
  // var route_start=locations[0][1]+","+locations[0][2];
   //var route_end=current_lat+","+current_lon;
    var route_start=current_lat+","+current_lon;
    var route_end="{{$service->route->endpoint()->latitude}},{{$service->route->endpoint()->longitude}}";
 }
 
if(hide==1){
 //console.log(route_start);
 const lineSymbol = {
    path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
  };
const poly = new google.maps.Polyline({
    path: track,
    geodesic: true,
    strokeColor: "#d742f5",
    strokeOpacity: 1.0,
    strokeWeight: 2,
    icons: [
      {
        icon: lineSymbol,
        offset: "100%",
      }],
  });
  poly.setMap(map);
  //animateCircle(poly);
}

    var request = {
origin: route_start,
destination: route_end,
//waypoints: waypts,
//optimizeWaypoints: true,
travelMode: google.maps.DirectionsTravelMode.DRIVING
};
directionsService.route(request, function(response, status) {
if (status == google.maps.DirectionsStatus.OK) {
  directionsRenderer.setDirections(response);
  var route = response.routes[0];
   console.log(response.routes[0].overview_path);
/*
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
     */

/*
                var pline = new google.maps.Polyline({
    geodesic: true,
    strokeColor: "#fc0324",
    strokeOpacity: 1.0,
    strokeWeight: 2,
    icons: [
      {
        icon: lineSymbol,
        offset: "100%",
      }],
  });
                var path = response.routes[0].overview_path;
                for (var x in path) {
                    pline.getPath().push(path[x]);
                }
                pline.setMap(map);
                //polylines.push(pline);*/
 
}
});


   

 
    
    console.log(locations);
   
}
function animateCircle(line) {
  let count = 0;
  window.setInterval(() => {
    count = (count + 1) % 200;
    const icons = line.get("icons");
    icons[0].offset = count / 2 + "%";
    line.set("icons", icons);
  }, 20);
}

google.maps.event.addDomListener(window, 'load', initialize);
</script>
<script type="text/javascript">
  function displayMarkers()
  {
    var checkBox = document.getElementById("myCheck");
    if (checkBox.checked == true){
    initialize(1);
  } else {
    initialize(0);
  }
  }
</script>

@endpush 