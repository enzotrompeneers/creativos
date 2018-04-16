<?php
// Al rollo
$clave = req('clave');

// Añadir clave

if (req('act')=='add') {
	
	$clave_nueva 	= slug(req("nueva_pagina"));
	$parent_id 		= req("parent_new");
	$parent_id 		= ($parent_id)?$parent_id:0;
	$query 			= "INSERT INTO ".$xname."_articulos (clave,parent_id) VALUES ('{$clave_nueva}',{$parent_id})";
	if ($clave_nueva!='') {
		run_query($query);
		$clave 		= $clave_nueva;
		header('location: articulos.php?clave='.$clave);
	}
}

// Editar contenido

if (req('act')=='edit') {
	$parent_id 			= req("parent_id");
	$album_id 			= req("album_id");
	$parent_id 			= ($parent_id)?$parent_id:0;
	$album_id 			= ($album_id)?$album_id:0;
	$header_menu		= (req('header_menu')!='')?1:0;
	$footer_menu		= (req('footer_menu')!='')?1:0;
	$privado			= (req('privado')!='')?1:0;
	$query 				= "UPDATE ".$xname."_articulos SET ";
	foreach ($languages as $k => $v) { 
		$query = $query."titulo_".$v." = '".req('titulo_'.$v)."',";	
		$slug = (req('slug_'.$v)=='')?slug(req('titulo_'.$v)):req('slug_'.$v);
		$link = (req('link_'.$v)=='')?req('titulo_'.$v):req('link_'.$v);
		$query = $query."link_".$v." = '".$link."',";	
		$query = $query."slug_".$v." = '".$slug."',";	
		$query = $query."art_".$v." = '".req('art_'.$v)."',";	
		$query = $query."meta_descr_".$v." = '".req('meta_descr_'.$v)."',";	
		$query = $query."meta_key_".$v." = '".req('meta_key_'.$v)."',";	
		$query .= " header_menu =  '".$header_menu."',";
		$query .= " footer_menu =  '".$footer_menu."',";
		$query .= " privado =  '".$privado."',";
	}
	$query .= " parent_id = ".$parent_id.",";
	$query .= " album_id = ".$album_id.",";
	$query = rtrim($query,',');
	$query = $query." WHERE clave = '".$clave."'";
	// echo '<span style="color:#000;">'.$query.'</span>';
	run_query($query);
}

// Borrar clave 

if (req('act')=='delete') {
	$id=req('id');
	$query = "DELETE  FROM ".$xname."_articulos WHERE id= '".$id."'";
	//echo $query;
	run_query($query);
	header('location: articulos.php');
}

// Get info for clave
if ($clave) {
	$query = "SELECT * FROM ".$xname."_articulos WHERE clave = '".$clave."'";
	//echo $query;
	$value = record($query);
	
	$id= $value['id'];
	$value['parent_id'] 	= $value['parent_id'];
	$value['clave_saltar'] 	= $value['clave'];
	$value['header_menu'] 	= $value['header_menu'];
	$value['footer_menu'] 	= $value['footer_menu'];

}

// Select array for parent
$parentQuery = "SELECT id, clave AS nombre FROM {$xname}_articulos WHERE parent_id < 1 ORDER BY orden,id";
$parents = dataset($parentQuery);
array_unshift ($parents,array('id' => '0','nombre'=>show_label('ninguno')));


// Select array for cambiar a 
$cambiarQuery = "
				SELECT id, clave, parent_id, titulo_{$language} AS titulo
				FROM {$xname}_articulos
				WHERE parent_id < 1
				ORDER BY orden,id
				";
$cambiarSql = dataset($cambiarQuery);
$n = 0;
$cambiar = array();
foreach ($cambiarSql as $k=>$v) {
	$cambiar[$n]['id'] 		= $v['clave'];
	$cambiar[$n]['nombre'] 	= $v['clave'];
	$cambiar[$n]['titulo'] 	= $v['titulo'];
	$parentQuery = "SELECT id,parent_id, clave, titulo_{$language} AS titulo FROM {$xname}_articulos WHERE parent_id = {$v['id']}";
	$parentSql = dataset($parentQuery);
	// printout($parentSql);
	foreach ($parentSql as $x=>$y) {
		$n++;
		$cambiar[$n]['id'] 		= $y['clave'];
		$cambiar[$n]['nombre'] 	= ' <i class="fa fa-share vertical_flip"> </i> '.$y['clave'];
		$cambiar[$n]['titulo'] 	= $y['titulo'];
	}
	$n++;
}

// array_unshift ($cambiar,array('id' => '','nombre'=>show_label('cambiar')));
// printout($cambiar);

// Select array for album
$albumQuery = "SELECT id,clave AS nombre FROM {$xname}_albumes";
$albumes = dataset($albumQuery);
$hasAlbum = (!empty($albumes))?TRUE:FALSE;
// printout($albumes);
array_unshift ($albumes,array('id' => '0','nombre'=>show_label('ninguno')));


// End file