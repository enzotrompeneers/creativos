
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
<head> 
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" /> 
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/> 
<title>Google Maps JavaScript API v3 Example: Markers, Info Window and StreetView: Loading the data from an XML</title> 
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script> 
<script type="text/javascript" src="scripts/downloadxml.js"></script>
<style type="text/css">
html, body { height: 100%; } 
</style>
<script type="text/javascript"> 
//<![CDATA[
      // this variable will collect the html which will eventually be placed in the side_bar 
      var side_bar_html = ""; 
    
      // arrays to hold copies of the markers used by the side_bar 
      var gmarkers = []; 

     // global "map" variable
      var map = null;

  var sv = new google.maps.StreetViewService();
   var clickedMarker = null;
    var panorama = null;

    // Create the shared infowindow with three DIV placeholders
    // One for a text string, oned for the html content from the xml, one for the StreetView panorama.
    var content = document.createElement("DIV");
    var title = document.createElement("DIV");
    content.appendChild(title);
    var htmlContent = document.createElement("DIV");
    content.appendChild(htmlContent);
    var streetview = document.createElement("DIV");
    streetview.style.width = "200px";
    streetview.style.height = "200px";
    content.appendChild(streetview);
var infowindow = new google.maps.InfoWindow(
  { 
    size: new google.maps.Size(150,50),
   content: content
  });
// A function to create the marker and set up the event window function 
function createMarker(latlng, name, html) {
    var contentString = html;
    var marker = new google.maps.Marker({
        position: latlng,
        map: map,
        title: name,
        zIndex: Math.round(latlng.lat()*-100000)<<5
        });
    marker.myHtml = html;

/*
    google.maps.event.addListener(marker, 'click', function() {
        infowindow.setContent(contentString); 
        infowindow.open(map,marker);
        });
*/
    google.maps.event.addListener(marker, "click", function() {
       clickedMarker = marker;
       sv.getPanoramaByLocation(marker.getPosition(), 50, processSVData);
       // openInfoWindow(marker);
    });
    // save the info we need to use later for the side_bar
    gmarkers.push(marker);
    // add a line to the side_bar html
    side_bar_html += '<a href="javascript:myclick(' + (gmarkers.length-1) + ')">' + name + '<\/a><br>';
}
 
// This function picks up the click and opens the corresponding info window
function myclick(i) {
  google.maps.event.trigger(gmarkers[i], "click");
}

  function processSVData(data, status) {
    if (status == google.maps.StreetViewStatus.OK) {
      var marker = clickedMarker;
      openInfoWindow(clickedMarker);

      if (!!panorama  && !!panorama.setPano) {
      
      panorama.setPano(data.location.pano);
      panorama.setPov({
        heading: 270,
        pitch: 0,
        zoom: 1
      });
      panorama.setVisible(true);
      
      google.maps.event.addListener(marker, 'click', function() {
      
        var markerPanoID = data.location.pano;
        // Set the Pano to use the passed panoID
        panorama.setPano(markerPanoID);
        panorama.setPov({
          heading: 270,
          pitch: 0,
          zoom: 1
        });
        panorama.setVisible(true);
      });
      }
    } else {
      openInfoWindow(clickedMarker);
      title.innerHTML = clickedMarker.getTitle() + "<br>Street View data not found for this location";
      panorama.setVisible(false);
      // alert("Street View data not found for this location.");
    }
  }
  function initialize() {

    // Create the map 
    // No need to specify zoom and center as we fit the map further down.
    map = new google.maps.Map(document.getElementById("map_canvas"), {
      mapTypeId: google.maps.MapTypeId.ROADMAP,
      streetViewControl: false
    });
 

  google.maps.event.addListener(map, 'click', function() {
        infowindow.close();
        });
      // Read the data from example.xml
      downloadUrl("example.xml", function(doc) {
        var xmlDoc = xmlParse(doc);
        var markers = xmlDoc.documentElement.getElementsByTagName("marker");
        var bounds = new google.maps.LatLngBounds();
        for (var i = 0; i < markers.length; i++) {
          // obtain the attribues of each marker
          var lat = parseFloat(markers[i].getAttribute("lat"));
          var lng = parseFloat(markers[i].getAttribute("lng"));
          var point = new google.maps.LatLng(lat,lng);
          var html = markers[i].getAttribute("html");
          var label = markers[i].getAttribute("label");
          // create the marker
          var marker = createMarker(point,label,html);
          bounds.extend(point);

        }
        // put the assembled side_bar_html contents into the side_bar div
        document.getElementById("side_bar").innerHTML = side_bar_html;
        // Zoom and center the map to fit the markers
        map.fitBounds(bounds);
      });
    }


    // Handle the DOM ready event to create the StreetView panorama
    // as it can only be created once the DIV inside the infowindow is loaded in the DOM.
    var pin = new google.maps.MVCObject();
    google.maps.event.addListenerOnce(infowindow, "domready", function() {
      panorama = new google.maps.StreetViewPanorama(streetview, {
       navigationControl: false,
       enableCloseButton: false,
       addressControl: false,
       linksControl: false,
       visible: true
      });
      panorama.bindTo("position", pin);
    });
    
    // Set the infowindow content and display it on marker click.
    // Use a 'pin' MVCObject as the order of the domready and marker click events is not garanteed.
    function openInfoWindow(marker) {
       title.innerHTML = marker.getTitle();
       htmlContent.innerHtml = marker.myHtml;
       pin.set("position", marker.getPosition());
       infowindow.open(map, marker);
    }
    // This Javascript is based on code provided by the
    // Community Church Javascript Team
    // http://www.bisphamchurch.org.uk/   
    // http://econym.org.uk/gmap/
    // from the v2 tutorial page at:
    // http://econym.org.uk/gmap/basic3.htm 
//]]>
</script> 
</head> 
<body style="margin:0px; padding:0px;" onload="initialize()"> 
    <!-- you can use tables or divs for the overall layout --> 
    <table border="1"> 
      <tr> 
        <td> 
           <div id="map_canvas" style="width: 550px; height: 450px"></div> 
        </td> 
        <td valign="top" style="width:150px; text-decoration: underline; color: #4444ff;"> 
           <div id="side_bar"></div> 
        </td> 
      </tr> 
    </table> 
 
    <noscript><p><b>JavaScript must be enabled in order for you to use Google Maps.</b> 
      However, it seems JavaScript is either disabled or not supported by your browser. 
      To view Google Maps, enable JavaScript by changing your browser options, and then 
      try again.</p>
    </noscript> 
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"> 
</script> 
<script type="text/javascript"> 
_uacct = "UA-162157-1";
urchinTracker();
</script> 
<script type="text/javascript"><!--
google_ad_client = "pub-8586773609818529";
google_ad_width = 728;
google_ad_height = 90;
google_ad_format = "728x90_as";
google_ad_type = "text";
google_ad_channel ="";
google_color_border = "CCCCCC";
google_color_bg = "FFFFFF";
google_color_link = "000000";
google_color_url = "666666";
google_color_text = "333333";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script> 
<div id="w3valid">
    <a href="http://validator.w3.org/check?uri=referer" ><!-- target="validationresults" --><img
        src="http://www.w3.org/Icons/valid-xhtml10"
        alt="Valid XHTML 1.0 Transitional" height="31" width="88" /></a>
</div>   
</body> 
</html>
