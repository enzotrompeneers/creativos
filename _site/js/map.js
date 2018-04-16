  jQuery(function () {
  
  // When the window has finished loading create our google map below
    google.maps.event.addDomListener(window, 'load', init);

    function init() {
        // Basic options for a simple Google Map
        // For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
		var zoom 	= parseInt($('#zoom').val());
		var lat 	= parseFloat ($('#lat').val());
		var lon 	= parseFloat ($('#lon').val());
		var title 	= $('#title').val();
		var maxzoom 	= $('#maxzoom').val();
		if (maxzoom=='false') {
			maxzoom = 21;
		} else {
			maxzoom = zoom;
		}
		
        var mapOptions = {
            // How zoomed in you want the map to start at (always required)
            zoom: zoom,
			maxZoom: maxzoom,
            panControl: true,
            zoomControl: true,
            streetViewControl: false,
            draggable: true,
            scrollwheel: false,
            // The latitude and longitude to center the map (always required)
            center: new google.maps.LatLng(lat, lon), // New York

            // How you would like to style the map. 
            // This is where you would paste any style found on Snazzy Maps.
			/*
            styles: [{
                "featureType": "administrative",
                "elementType": "labels.text.fill",
                "stylers": [{
                    "color": "#444444"
                }]
            }, {
                "featureType": "administrative.country",
                "elementType": "geometry",
                "stylers": [{
                    "visibility": "on"
                }]
            }, {
                "featureType": "administrative.country",
                "elementType": "geometry.stroke",
                "stylers": [{
                    "visibility": "on"
                }]
            }, {
                "featureType": "administrative.country",
                "elementType": "labels.text",
                "stylers": [{
                    "hue": "#fff600"
                }]
            }, {
                "featureType": "landscape",
                "elementType": "all",
                "stylers": [{
                    "color": "#f2f2f2"
                }]
            }, {
                "featureType": "poi",
                "elementType": "all",
                "stylers": [{
                    "visibility": "off"
                }]
            }, {
                "featureType": "road",
                "elementType": "all",
                "stylers": [{
                    "saturation": -100
                }, {
                    "lightness": 45
                }]
            }, {
                "featureType": "road.highway",
                "elementType": "all",
                "stylers": [{
                    "visibility": "simplified"
                }]
            }, {
                "featureType": "road.arterial",
                "elementType": "labels.icon",
                "stylers": [{
                    "visibility": "off"
                }]
            }, {
                "featureType": "transit",
                "elementType": "all",
                "stylers": [{
                    "visibility": "off"
                }]
            }, {
                "featureType": "water",
                "elementType": "all",
                "stylers": [{
                    "color": "#cad3d5"
                }, {
                    "visibility": "on"
                }]
            }]
			*/
        };

        // Get the HTML DOM element that will contain your map 
        // We are using a div with id="map" seen below in the <body>
        var mapElement = document.getElementById('map');

        // Create the Google Map using our element and options defined above
        var map = new google.maps.Map(mapElement, mapOptions);


        // Let's also add a marker while we're at it
        var marker = new google.maps.Marker({
            icon: 'images/marker.png',
            position: new google.maps.LatLng(lat, lon),
            map: map,
            title: title
        });
    }
});