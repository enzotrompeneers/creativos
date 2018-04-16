function customFnRowCallback( nRow, aData, iDisplayIndex )
{
    $('td.thumbnailImage', nRow).html( '<img class="" src="'+ aData[1]+'" alt="" style="max-width: 120px;" />' );
    return nRow;
}



$(document).ready(function() { 



/*** Precio form to update prices on the fly ***/
var options = { 
	target:  '#message2',
	success: function(){
		$('#message2').fadeIn().delay(3000).fadeOut();
	}
} 
$('.precio').ajaxForm(options);

// Colourpicker
$('input.minicolors').minicolors();

// confirmation
$('#myTable tbody').on('click','.remove', function () { $(this).confirmation('show'); } );

// Data table
$(".table").dataTable({
	"bPaginate": true,
	"fnRowCallback": customFnRowCallback
});
		
/*** IMAGE GALLERY ***/
$fkAlbum = $('#formId');
$table=$('#table');
//on load	
$.get('dragdrop.php',{'id':$fkAlbum.attr('value'),'table':$table.attr('value')}, function(data){
	$('#album').html(data);
});
$.get('dragdrop_files.php',{'id':$fkAlbum.attr('value'),'table':$table.attr('value'),}, function(data){
	$('#fileList').html(data);
});
function slideout(){
	setTimeout(function(){
		$("#response").slideUp("slow", function () {
	});
}, 2000);}
$("#response").hide();
$(".sort ul").sortable({ opacity: 0.8, cursor: 'move', update: function() {
	var order = $(this).sortable("serialize") + '&update=update&table=' + $table.attr('value') + '&type=' + $(this).attr('role'); 
	$.post("updateList.php", order, function(theResponse){
		$("#response").html(theResponse);
		$("#response").slideDown('slow');
		slideout();
	}); 															 
	}								  
});

// Image rotation
$('body').on('click', '.rotate', function(event){
	$id 		= $(this).attr('data-id');
	$parentId 	= $(this).attr('data-parent-id');
	$rotate 	= $(this).attr('data-rotate');
	$folder 	= $(this).attr('data-table');
	$file 		= $(this).attr('data-file');
	
	$.get('imgRotate.php', { parent_id: $parentId, folder: $folder, file: $file, rotate: $rotate }, function(){
		$src = $('#img'+$id).attr('src');
		$date = new Date();
		$('#img'+$id).attr('src', $src+'?'+$date.getTime());
	})
	event.preventDefault();
})


						  
// Highlight selected
$('input, textarea').focus(function(){
	$(this).addClass('selected');
}); 
$('input, textarea').blur(function(){
	$(this).removeClass('selected');
}); 

// // Date picker
// $fecha = $('.fecha');
// if ($fecha.val()=='') { 
	// $fecha.val($today); 
// }
$.datepicker.setDefaults($.datepicker.regional['es']);
$('.date-picker').datepicker({
	format: 'yyyy-mm-dd'
});

// Get subcategorias when categories are changed
//$('#subcategoria_id').attr('disabled', 'disabled');
var $categoria = $('#categoria_id');
function getOptions() {
    $.get('sub_options.php',{'id':$categoria.attr('value')}, function(data){
		if (data) {
			$('#subcategoria_id').html(data);
		 } else {
			$('#subcategoria_id').empty();
			$('#subcategoria_id').append('<option value="0">No hay subcategorias</option>');
		 }
	});
}
$categoria.change(function() { 
	$('#subcategoria_id').attr('disabled', '');
	getOptions();
});
$('.colorbox').live('click', function() {
  $.colorbox({href:$(this).attr('href'), open:true, height: '90%'});
  return false;
});

// Ajaxify links: make sure they are sent via AJAX and update #message div
$('.borrar_imagen').live('click',function(e){
	$url = $(this).attr('href');
	if (!confirm('¿¿¿SEGURO???')) {   
		e.preventDefault();
	} else {
		$.get($url,function(data){})
			$.get('dragdrop.php',{'id':$fkAlbum.attr('value'),'table':$table.attr('value')}, function(data){
			$('#album').html(data);
		});	
	}
	return false;
});
// Ajaxify links: make sure they are sent via AJAX and update #message div
$('.borrar_file').live('click',function(e){
	$url = $(this).attr('href');
	if (!confirm('¿¿¿SEGURO???')) {   
		e.preventDefault();
	} else {
		$.get($url,function(data){})
			$.get('dragdrop_files.php',{'id':$fkAlbum.attr('value'),'table':$table.attr('value')}, function(data){
			$('#fileList').html(data);
		});	
	}
	return false;
});
// Ajaxify links: make sure they are sent via AJAX and update #message div
$('.remove_file').click(function(e){

	$url 	= $(this).attr('href');
	$name 	= $(this).attr('id').substr(4);
	if (!confirm('¿SEGURO?')) {   
		e.preventDefault();
	} else {
		$.get($url,function(data){
			$('#file_'+$name).fadeOut();
			$('#del_'+$name).fadeOut();
		})

	}
	return false;
});

$('.languageChange').live('click',function(e){
	$language 	= $(this).attr('id').substr(5);
	$imgId			=$(this).attr('role');
		$.get('dragdrop_files.php',{'id':$fkAlbum.attr('value'),'imgId':$imgId,'table':$table.attr('value'),'action':'language','language':$language}, function(data){
		$('#fileList').html(data);
		});	
	e.preventDefault();
	return false;
});

}); 