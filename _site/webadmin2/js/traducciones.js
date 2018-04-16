$(document).ready(function(){
	// Get trad form
	$('#clave .item').click(function(event){
		var $clave = $(this).attr('id').substring(6);
		//alert ($clave);
		$('.item').removeClass('active');
		$(this).addClass('active');
		$.get('trad_form.php',{clave: $clave},function(data){
			
			$('#traducciones').html(data);
			
		});
		event.preventDefault();
	});
	var options = { 
		success: function(){
			// $('#new').focus();
			toastr.success($('#guardado').val());
		}
	}; 
	$('#traducciones').ajaxForm(options);
	
	// Add clave
	$('#add').click(function(){
		$clave_nueva = $('#new').val();
		if ($clave_nueva!='') {
			$.get('trad_doit.php',{clave_nueva: $clave_nueva,act:'add'},function(data){
				$('#message').html(data);
			});
			$.get('trad_form.php',{clave: $clave_nueva},function(data){
				$('#traducciones').html(data);
			});
			
			$('#clave option').removeAttr('selected');
			$('#clave').append('<option value="'+$clave_nueva+'" id="sel_'+$clave_nueva+'">'+$clave_nueva+'</option>');
			$('#sel_'+$clave_nueva).attr('selected', 'selected');
		}
		$('#new').val('');

		return false;
	});
	
	// Filter languages
	$('a.languageFilter').click(function(event){
		$('.tradEntry').hide();
		$filterLanguage = $(this).attr('id').substring(7);
		$('.no-'+$filterLanguage).parent('a').parent('li').show();
		event.preventDefault();
	});
	
	// Show all
	$('#allTrads').click(function(){
		$('.tradEntry').show();
	});
	$('#new').keydown(function(event) {});

	// Upload button
	$('#file_name').change(function(event){
		if ($(this).val()!='') {
			$('#cvsUpload').prop('disabled', false);
		} else {
			alert('full');
			$('#cvsUpload').prop('disabled', true);
		}
	});
	
	
	
});