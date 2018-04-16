<?php
// Last upadate: 21/11/2014
// For Yes-Inmo

$not_inc = TRUE;

///***** SHOW NOTICIAS LISTING *****///
	
function show_lista_noticias($pagesize=6,$pag='noticias') {
	global $language,$xname,$languages,$pagina,$clave;
	
	// Pagination setup
	$start = (isset($_GET['clave'])) ? $_GET['clave'] : 1;
	$start = mysql_real_escape_string($start);
	$prev = $start - 1; $next = $start + 1;
	$limit = $start * $pagesize - $pagesize;		
	
	// Get list of noticias
	$query = "SELECT * FROM {$xname}_noticias ORDER BY fecha DESC,id DESC";
	$noticias = dataset($query);
	$noticiasLimit = dataset($query." LIMIT $limit, $pagesize");
	$results = count($noticias);

	// Pagination nitty gritty - pass $pagination to the view
	$pagination = '';
	if($start > 1) { 
		$pagination .= '<a href="'.$language.'/'.slugged('noticias').'/'.$prev.'/">&laquo; '.trad('anterior').'</a>&nbsp;';
	}
	$i = 1;
	$pagestr = (string) '';
	while($i*$pagesize-$pagesize < $results){
		$class = ($start == $i) ? 'class="active"' : '';
		// $start = (isset($_GET['start'])) ? $_GET['start'] : 1;
		$pagestr .= '<a href="'.$language.'/'.slugged('noticias').'/'.$i.'/" '.$class.'>'.$i.'</a>';
		$i++;
	}
	$pagination .= trim($pagestr,"");
	if($next * $pagesize - $pagesize < $results) { 
		$pagination .= '<a href="'.$language.'/'.slugged('noticias').'/'.$next.'/">'.trad('siguiente').' &raquo;</a>' ;
	}
	
	// Load view
	include('view_listado.php');
}

///***** SHOW INDIVIDUAL NOTICIA  *****///

function show_noticia ($id) {
	global $language,$xname;
	
	//If id is set, get it from the querystring, otherwise, get the latest id
	$id = ($id!='') ? $id : select_max("id","{$xname}_noticias");

	// Get content
	$query 		= "SELECT * FROM {$xname}_noticias WHERE id =".$id;
	$noticia	= record($query);
	$imagesQuery = "SELECT * FROM {$xname}_images_noticias WHERE parent_id = ".$id." ORDER by orden ASC";
	// echo $imagesQuery;
	$images 	= dataset($imagesQuery);
	// print_r($images);
	if ($images) {
		$first = array();
		$first['s'] = 'images/noticias/'.$id.'/s_'.$images[0]['file_name'];
		$first['m'] = 'images/noticias/'.$id.'/m_'.$images[0]['file_name'];
		$first['l'] = 'images/noticias/'.$id.'/l_'.$images[0]['file_name'];
		$first['g'] = 'images/noticias/'.$id.'/g_'.$images[0]['file_name'];
		
		array_shift($images); // Remove first image for gallery		
	}

	
	$titulo		= $noticia['titulo_'.$language];
	$fecha		= date('d/m/Y', strtotime($noticia['fecha']));
	$cuerpo 	= $noticia['descr_'.$language];
	
	// Get image gallery
	$galeria 	= '';
	if(!empty($images)){
		$n = 1;
		$galeria = "<ul id='fotogal'>";
		foreach($images as $k=>$v){
			$galeria .= '
			<li>
				<a href="images/noticias/'.$id.'/l_'.$v['file_name'].'" id="img_'.$n.'" title="'.$noticia['titulo_'.$language].'" rel="images" class="colorbox">
					<img src="images/noticias/'.$id.'/s_'.$v['file_name'].'" alt="'.$noticia['titulo_'.$language].'" />
				</a>
			</li>';
			$n = $n +1;
		}
		$galeria .= '</ul>';
	}		
	
	// Load view
	include('view_noticia.php');
}

///*****  GET TITLE *****///

function get_noticia_title($id) {
	global $language,$xname;
	if ($id!=''){
		$query = "SELECT titulo_{$language} AS titulo FROM {$xname}_noticias WHERE id =".$id;
		$noticia = record($query);
		$titulo = $noticia['titulo'];
	}
	$titulo = ($titulo!='')?$titulo:'Guardamar Spanish property news';
	return $titulo;
}

////***** FRONT PAGE NEWS *****////
function show_noticias_portada ($number=3, $pag='noticias') {
	global $xname,$language;
	
	// Get list of noticias
	$query = "SELECT *,DATE_FORMAT(fecha, '%d %m %y') FROM {$xname}_noticias ORDER BY fecha DESC,id DESC LIMIT {$number}";
	$noticiasArray = dataset($query);
	$n = 0;
	foreach ($noticiasArray as $k=>$v){
		$noticias[$n]['titulo'] = $v['titulo_'.$language];
		$noticias[$n]['link'] = $language.'/'.slugged($pag).'/'.slug($v['titulo_'.$language]).'-'.$v['id'].'.html';
		$noticias[$n]['img'] = first_image('noticias',$v['id'],'s');
		$noticias[$n]['fecha'] = date('d/m/Y',strtotime($v['fecha']));
		$noticias[$n]['descr'] = shorten_text($v['descr_'.$language],100);
		$n++;
	}
	 // printout($noticias);
	// Load view
	include('view_portada.php');
}





// End file

