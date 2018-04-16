$(document).ready(function() { 

	/*** IMAGE GALLERY ***/
	$fkAlbum=$('#formId');
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
	$("#gal ul").sortable({ opacity: 0.8, cursor: 'move', update: function() {
		var order = $(this).sortable("serialize") + '&update=update&table=' + $table.attr('value'); 
		$.post("updateList.php", order, function(theResponse){
			$("#response").html(theResponse);
			$("#response").slideDown('slow');
			slideout();
		}); 															 
		}								  
	});
								  
	// Highlight selected
	$('input, textarea').focus(function(){
		$(this).addClass('selected');
	}); 
	$('input, textarea').blur(function(){
		$(this).removeClass('selected');
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

	
}); 