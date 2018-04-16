<?php
function show_weather ($localidad,$number) {
	
	global $language;
	if (!$localidad) { $localidad = '03113'; }
	if (!$number) { $number = 4; }
	
	$url = 'http://www.aemet.es/xml/municipios/localidad_'.$localidad.'.xml';
	$sXML = simplexml_load_file($url);
	//$xml = new SimpleXMLElement(utf8_encode($sXML));
	// Loops XML 
	$count = 0;
	
	echo '<div id="weather'.$localidad.'">';

		foreach($sXML->prediccion as $prediccion) {
			foreach($prediccion->dia as $new) {
				switch ($new->estado_cielo['descripcion'])
				{
				case 'Nuboso':
					$img = 'partly_cloudy.png';
					break;
				case 'Intervalos nubosos':
					$img = 'partly_cloudy.png';
					break;
				case 'Poco nuboso':
					$img = 'mostly_sunny.png';
					break;
				case 'Muy nuboso':
					$img = 'fog.png';
					break;
				case 'Cubierto':
					$img = 'cloudy.png';
					break;
				case 'Soleado':
					$img = 'sunny.png';
					break;
				case 'Despejado':
					$img = 'sunny.png';
					break;
				case 'Nubes altas':
					$img = 'fog.png';
					break;
				case 'Intervalos nubosos con lluvia escasa':
					$img = 'chance_of_rain.png';
					break;
				default:
					$img = 'rain.png';
					break;
				}
				
				echo '<div id="weather">';
				$date = strtotime($new['fecha']);
				$t = date('l', $date);
				$d = date('d/m', $date);
				
				echo "<img src='images/weather/".$img. "' alt='' />";
				echo "<div class='cnt'><p class='date'>".$t." ".$d."</p>";
				
				foreach($new->temperatura as $temp){
					echo "<p class='temp'>".$temp->maxima.'&deg;C</p>';
				}
				echo '</div></div>';
				$count++;
				if ($count==$number) { break; }
				}
		}
	echo '</div>';
 }

// End file