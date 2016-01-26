
function initializeGoogleStreetMap(mapcanvas, latitude, longitude) {
	var latlng = new google.maps.LatLng(latitude, longitude);
	var panoramaOptions = {
    position:latlng,
    pov: {
      heading: 270,
      pitch:0,
      zoom:1
    },
    visible:true
  };
  var panorama = new google.maps.StreetViewPanorama(document.getElementById(mapcanvas), panoramaOptions);
}

function initializeGoogleMap(mapcanvas) {
	// set latitude and longitude to center the map around
	var latlng = new google.maps.LatLng(latitude, 
										longitude);
	
	// set up the default options
	var myOptions = {
	  zoom: 15,
	  center: latlng,
	  navigationControl: true,
	  navigationControlOptions: 
		{style: google.maps.NavigationControlStyle.DEFAULT,
		 position: google.maps.ControlPosition.TOP_LEFT },
	  mapTypeControl: true,
	  mapTypeControlOptions: 
		{style: google.maps.MapTypeControlStyle.DEFAULT,
		 position: google.maps.ControlPosition.TOP_RIGHT },
	
	  scaleControl: true,
	   scaleControlOptions: {
			position: google.maps.ControlPosition.BOTTOM_LEFT
	  }, 
	  mapTypeId: google.maps.MapTypeId.ROADMAP,
	  draggable: true,
	  disableDoubleClickZoom: false,
	  keyboardShortcuts: true
	};
	var map = new google.maps.Map(document.getElementById(mapcanvas), myOptions);
		addMarker(map,latitude,longitude,"We are here");
}

// Add a marker to the map at specified latitude and longitude with tooltip
function addMarker(map,lat,long,titleText) {
	var markerLatlng = new google.maps.LatLng(lat,long);
	var marker = new google.maps.Marker({
	position: markerLatlng, 
	map: map, 
	title:"We are here",
	icon: ""});
}