<?php
// Gets proper icon file
function get_icon($ext){
	$extensionArray = array(
		'zip' 		=> 'zip',
		'jpg' 		=> 'img',
		'jpeg' 		=> 'img',
		'png' 		=> 'img',
		'gif' 		=> 'img',
		'pdf' 		=> 'pdf',
		'doc' 		=> 'word',
		'docx' 		=> 'word'
	);
	$icon		= (!empty($extensionArray[$ext]))?$extensionArray[$ext]:'file';
	return 		$icon;
}

function formatBytes($bytes, $precision = 2) { 
    $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

    $bytes = max($bytes, 0); 
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
    $pow = min($pow, count($units) - 1); 

    // Uncomment one of the following alternatives
    $bytes /= pow(1024, $pow);
    // $bytes /= (1 << (10 * $pow)); 

    return round($bytes, $precision) . ' ' . $units[$pow]; 
} 


// End of file