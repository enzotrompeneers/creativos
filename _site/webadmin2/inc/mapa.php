<?php if ($mapa == TRUE) { 
$longitud = ($r['lon']!=0)?$r['lon']:'-0.691463';
$latitud = ($r['lat']!=0)?$r['lat']:'37.997473';
$apiKey = 'AIzaSyCvFXvhDZTpY2kA-qcFNFgEp5MScS9eNcE';
?>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?key=<?= $apiKey ?>&sensor=false"></script>
<script type="text/javascript">
  function initialize() {
    var latlng = new google.maps.LatLng(<?=$latitud?>,<?=$longitud?>);
    var myOptions = {
      zoom: 12, 
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
     var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
		
		var myLatlng = new google.maps.LatLng(<?=$latitud?>,<?=$longitud?>);
	    var marker = new google.maps.Marker({
        position: myLatlng, 
        map: map, 
        draggable:true
    }); 
	google.maps.event.addListener(marker,"dragend", function(){ 
		$lat = $("#lat");
		$lng = $("#lon");
		$lat.val(marker.getPosition().lat());
		$lng.val(marker.getPosition().lng());
		$z.val(map.getZoom());
	});
	google.maps.event.addListener(map,"zoom_changed", function(){ 
		$z = $("#zoom");
		$z.val(map.getZoom());
	});
	
  	//Bind click handlers - Here's the important part
	$('a[href=#tab_lat]').on('click', function() {
		setTimeout(function(){
			x = map.getZoom();
			c = map.getCenter();
			google.maps.event.trigger(map, 'resize');
			map.setZoom(x);
			map.setCenter(c);
		}, 50);
	});		
  }
  $('#mapButton').live('click', function() {
    // Obtenemos la dirección y la asignamos a una variable
    var address = $('#address').val();
    // Creamos el Objeto Geocoder
    var geocoder = new google.maps.Geocoder();
    // Hacemos la petición indicando la dirección e invocamos la función
    // geocodeResult enviando todo el resultado obtenido
    geocoder.geocode({ 'address': address}, geocodeResult);
});
  function geocodeResult(results, status) {
    // Verificamos el estatus
    if (status == 'OK') {
        // Si hay resultados encontrados, centramos y repintamos el mapa
        // esto para eliminar cualquier pin antes puesto
        var mapOptions = {
            center: results[0].geometry.location,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };
        map = new google.maps.Map($("#map_canvas").get(0), mapOptions);
        // fitBounds acercará el mapa con el zoom adecuado de acuerdo a lo buscado
        map.fitBounds(results[0].geometry.viewport);
        // Dibujamos un marcador con la ubicación del primer resultado obtenido
        var markerOptions = { position: results[0].geometry.location, draggable:true }
        var marker = new google.maps.Marker(markerOptions);
        marker.setMap(map);
		google.maps.event.addListener(marker,"dragend", function(){ 
			$lat = $("#lat");
			$lng = $("#lon");
			$lat.val(marker.getPosition().lat());
			$lng.val(marker.getPosition().lng());
			$z.val(map.getZoom());
		});
		google.maps.event.addListener(map,"zoom_changed", function(){ 
			$z = $("#zoom");
			$z.val(map.getZoom());
		});	
		google.maps.event.addListener(map,"bounds_changed", function(){ 
			$lat = $("#lat");
			$lng = $("#lon");
			$lat.val(marker.getPosition().lat());
			$lng.val(marker.getPosition().lng());
			google.maps.event.clearListeners(map, 'bounds_changed');
		});		
    } else {
        // En caso de no haber resultados o que haya ocurrido un error
        // lanzamos un mensaje con el error
        alert("Geocoding no tuvo éxito debido a: " + status);
    }
}
</script>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	initialize();
});
</script>

<?php
} 

// End of file