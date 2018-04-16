<?php
// Latest update 23/8/2010
// For: EasyOption.com - Added XML functionality.

 // $lat = ''; // Latitud
 // $lon = ''; // Longitud
 // $zoom = ''; // Zoom
 // $info = ''; // Info window stuff 
 // $type = ''; // type map
/* Para diferentes estilos de mapa cambiar "mapTypeId"
	ROADMAP, que muestra los mosaicos normales en 2D predeterminados de Google Maps.
	SATELLITE, que muestra imágenes de satélite.
	HYBRID, que muestra una mezcla de mosaicos fotográficos y una capa de mosaicos para los elementos del mapa más destacados (carreteras, nombres de ciudades, etc.).
	TERRAIN, que muestra mosaicos de relieve físico para indicar las elevaciones del terreno y las fuentes de agua (montañas, ríos, etc.).
 */
 
 // SHOW MAPA UN SOLO PUNTO
 
 function show_mapa ($lat,$lon,$zoom,$info, $type) {
 
 if (!$lat) { $lat = 37.997473; }
 if (!$lon) { $lon = -0.691463; }
 if (!$zoom) { $zoom = 9; }
 if (!$info) { $info = 'Brunel | Encantado'; }
 if (!$type) { $type = 'ROADMAP'; }

 
?>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">
  function initialize() {
    var latlng = new google.maps.LatLng(<?=$lat?>,<?=$lon?>);
    var myOptions = {
      zoom: <?=$zoom?>, 
      center: latlng,
      mapTypeId: google.maps.MapTypeId.<?= $type ?>
    };
    var map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
		
		var myLatlng = new google.maps.LatLng(<?=$lat?>,<?=$lon?>);
	    var marker = new google.maps.Marker({
        position: myLatlng, 
        map: map,
    }); 
	
	  var infowindow = new google.maps.InfoWindow({ content: '<?=$info?>' });
	  google.maps.event.addListener(marker, 'click', function() {
      infowindow.open(map,marker);
	  
    });
  }
</script>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	initialize();
});
</script>
<div id="map_canvas"></div>

<?php } 


// SHOW MAPA CON XML

 // $lat = ''; // Latitud del centro
 // $lon = ''; // Longitud del centro
 // $zoom = ''; // Zoom inicial
 // $xml =''; // XML file
 
function show_mapa_xml($lat,$lon,$zoom,$xml) {

 if ($lat=='') { $lat = 37.997473; } 
 if ($lon=='') { $lon = -0.691463; }
 if (!$zoom) { $zoom = 9; }
 if (!$xml) { $xml = 'easymap.xml'; }

?>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript">

    //<![CDATA[
    var map;
    var markers = [];
	var infoWindow = new google.maps.InfoWindow();
	var panorama;

    function load() {
	
	map = new google.maps.Map(document.getElementById("map_canvas"), {
		center: new google.maps.LatLng(<?=$lat?>,<?=$lon?>),
		zoom: <?=$zoom?>,
		mapTypeId: 'roadmap',
		mapTypeControlOptions: {}
	});
	  showMarkers();

	  
    }
	function showAlert(id) {
		google.maps.event.trigger(markers[id], 'click');
		map.setZoom(15);
		map.panTo(markers[id].getPosition());
	}
	function showPano(lat,lng){
		// alert(lng);
		var sv = new google.maps.LatLng(lat,lng);
	    panorama = map.getStreetView();
		panorama.setPosition(sv);
		panorama.setVisible(true);
		return false;
	}

	function showMarkers() {

     var searchUrl = '<?=$xml?>';
     downloadUrl(searchUrl, function(data) {
       var xml = parseXml(data);
       var markerNodes = xml.documentElement.getElementsByTagName("marker"); 
       var bounds = new google.maps.LatLngBounds();
       for (var i = 0; i < markerNodes.length; i++) {
			var name = markerNodes[i].getAttribute("name");
			var image = markerNodes[i].getAttribute("icon");
			var m_id = markerNodes[i].getAttribute("id"); 
			 // var address = markerNodes[i].getAttribute("address"); 
			 var $br = '<br />';
			 // if (address!='') {address = address.replace(/#/g, $br); }
			 // //alert (address);
			 // var telephone = markerNodes[i].getAttribute("telephone");
			 info = '<h3>'+name+'</h3>';
			 //alert(info); 
			 var latlng = new google.maps.LatLng( 
				 parseFloat(markerNodes[i].getAttribute("lat")),
				 parseFloat(markerNodes[i].getAttribute("lng"))
			 );
			 createMarker(latlng, info, image,m_id);
			 bounds.extend(latlng);
       }
       map.fitBounds(bounds);
      });
    }


function createMarker(latlng, info,image,m_id) {
	var imagen = 'images/'+image;
	var marker = new google.maps.Marker({
		map: map,
		position: latlng,
		icon: imagen
	});
	sid = m_id.substr(3);
	lat = latlng.k;
	lng = latlng.A;
	var html = '<div class="info_box"><div  style="width:260px;height:60px;">'+info+'<a style="color:#333;" class="svLink" href="#" id="s_'+sid+'" onclick="showPano('+lat+','+lng+');return false;">Streetview</a></div></div>';
;
	google.maps.event.addListener(marker, 'click', function() {
		infoWindow.setContent(html);
		infoWindow.open(map, marker);
	});
	markers[m_id.substr(2)] = marker;
}

function createOption(name, distance, num) {
  var option = document.createElement("option");
  option.value = num;
  option.innerHTML = name + "(" + distance.toFixed(1) + ")";
  locationSelect.appendChild(option);
}

function downloadUrl(url,callback) {
 var request = window.ActiveXObject ?
     new ActiveXObject('Microsoft.XMLHTTP') :
     new XMLHttpRequest;

 request.onreadystatechange = function() {
   if (request.readyState == 4) {
     request.onreadystatechange = doNothing;
     callback(request.responseText, request.status);
   }
 };

 request.open('GET', url, true);
 request.send(null);
}
	
    function parseXml(str) {
      if (window.ActiveXObject) {
        var doc = new ActiveXObject('Microsoft.XMLDOM');
        doc.loadXML(str);
        return doc;
      } else if (window.DOMParser) {
        return (new DOMParser).parseFromString(str, 'text/xml');
      }
    }

    function doNothing() {}

    //]]>

</script>
<script language="javascript" type="text/javascript">
$(document).ready(function(){
	load();
});
</script>
<div id="map_canvas"></div>
<?php }

$mapa_inc = TRUE;


// End of file