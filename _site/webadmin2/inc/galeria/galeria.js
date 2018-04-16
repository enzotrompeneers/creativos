$(document).ready(function(){

    /*** ALBUM EDITOR ***/
	$fkAlbum=$('#fk_album')
	//on load
	$.get('inc/galeria/dragdrop.php',{'id':$fkAlbum.attr('value')}, function(data){
              $('#albumHolder').html(data);
    });
	//on change
	$fkAlbum.change(function() {
        $.get('inc/galeria/dragdrop.php',{'id':$fkAlbum.attr('value')}, function(data){
              $('#albumHolder').html(data);
        });
		$('#test').setValue($fkAlbum.attr('value'));
    });
            	
	function slideout(){
  		setTimeout(function(){
  			$("#response").slideUp("slow", function () {
      	});
    
	}, 2000);}
	
    $("#response").hide();
	$(function() {
	$("#gal ul").sortable({ opacity: 0.8, cursor: 'move', update: function() {
			
			var order = $(this).sortable("serialize") + '&update=update'; 
			$.post("inc/galeria/updateList.php", order, function(theResponse){
				$("#response").html(theResponse);
				$("#response").slideDown('slow');
				slideout();
			}); 															 
		}								  
		});
	});
	
});	