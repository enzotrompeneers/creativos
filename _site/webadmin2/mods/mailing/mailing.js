$(document).ready(function() { 


	$tradGrupo = $('#trad_grupo').val();
	$tradEmail = $('#trad_email').val();
	$tradBorrar = $('#trad_borrar').val();

	// Adds property to list
	$('#addVivienda').click(function(e){
		$vivienda = $('#vivienda').val();
		$lang = $('#lang').val();
		$.post('mailing_getVivienda.php',{'id':$vivienda, 'lang':$lang}, function(data){
			if (data) {
				$('#viviendas').append(data);
				$viviendasContent = $('#viviendas').html();
				$('#viviendasInput').val($viviendasContent); 
				$('#datos').append('<input type="hidden" class="viviendaInput" name="vivienda[]" id="f'+$vivienda+'" value="'+$vivienda+'" />');
			}
		});
	e.preventDefault();
	});
	// Select filter
	$('#vivienda').filterByText($('#filter'), true);
	// Remove property or email
	$('body').on('click','.borrar',function(e){
		$thisId = $(this).attr('id').substr(1);
		$('#v'+$thisId).remove();
		$('#f'+$thisId).remove();
		e.preventDefault();
	});
	$('body').on('click','.borrarEmail',function(e){
		$thisId = $(this).attr('id').substr(1);
		$('#e'+$thisId).remove();
		$('#l'+$thisId).remove();
		e.preventDefault();
	});

	// Change language in form
	$('#lang').change(function(){ $('#idioma').val($('#lang').val()); });
	// Add email recipient
	$('#addEmail').click(function(e){

		$email = $('#email').val();
		if ($email != '' && validEmail($email)){
			$num = Math.floor((Math.random() * 10000) + 1);
			$('#emails').append('<div class="note note-success" id="e'+$num+'"><strong>'+$email+'</strong> <a href="#" id="d'+$num+'" class="borrarEmail right btn btn-sm red"><i class="fa fa-times"></i></a></div>');
			$('#datos').append('<input type="hidden" name="email[]" id="l'+$num+'" value="'+$email+'" />');
			$('#email').val('');
			$('#nombre').val('').focus();
		} else {
			alert('Invalid Email');
		}
	});
	// Add email group
	$('#addGroup').click(function(){
		$emailGroupName = $('#grupo_mailings option:selected').text();
		
		$emailGroupId = $('#grupo_mailings').val();
		if ($emailGroupId!='') {
			$('#emailGroupList').append('<div class="note note-info" id="g'+$emailGroupId+'">'+$tradGrupo+': '+$emailGroupName+'<a href="#"  class="right btn red btn-sm deleteEmailGroup" id="d'+$emailGroupId+'"><i class="fa fa-times"></i>&nbsp;'+$tradBorrar+'</a></div>');
			$('#datos').append('<input type="hidden" name="emailGroup[]" id="h'+$emailGroupId+'" value="'+$emailGroupId+'" />');
		}
		
	});
	// Delete email group
	$('body').on('click', '.deleteEmailGroup', function(){
		$emailGroupId = $(this).attr('id').substr(1);
		$('#g'+$emailGroupId).remove();
		$('#h'+$emailGroupId).remove();
	});
	
	// Pdf button
	$('#pdfForm').submit(function(){
		$pdfUrl 	= $(this).attr('href');
		$pdfData 	= $('input[name="vivienda[]"]');
		$('#pdfForm').append($pdfData);
		// event.preventDefault();
	});
	// Language change for pdf
	$('#lang').change(function(){
		$('#pdfIdioma').val($(this).val());
	});
	
	// Form validation
	$('#datos').submit(function(){
		if ($('#viviendas').html()=='') { alert($('#no_viviendas').val());return false; }
		if ($('#emails').html()=='' && $('#emailGroupList').html()=='') { alert($('#no_emails').val());return false; }
		// if ($('#asunto').val()=='') { alert('No ha introducido un asunto.');return false; }
	});
	
	// Search form
	var options = { target: '#searchList' };
	$('#searchForm').ajaxForm(options);
	
	
	// Colorbox
	$('.colorbox').colorbox({
		transition:"none",
		width: "90%",
	
		scrolling: 'true',
		onClosed:function(){}
	});
	
}); 

/***** VALID EMAIL *****/

function validEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}