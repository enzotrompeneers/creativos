$language = $('html').attr('lang');
function getCalendar(year, pId){
	
	var url = $language+'/ajax/calendar/show/'+pId+'/?year='+year;
	
	$.ajax({ 'url': url, success: function(data){
		$('#year').val(year);
		$('#yearNaviCalendar').text(year);
		$('#cntCalendar').html(data);
	}});
}
function showResponse(responseText, statusText, xhr, $form){
	$('#bookingForm input, #bookingForm textarea').each(function(){
		$(this).removeClass('error');
	});	
	$jsonResponse = JSON.parse(responseText);
	if ($jsonResponse['message']=='error'){
		$errors = $jsonResponse['errors'];

		$.each($errors, function(k, v){
			$('#'+k).addClass('error'); // .attr('placeholder', v)
		});

	} else {

		$('#bookingForm').html($jsonResponse['html']);

	}

}

function calculateCosts(){
		$fecha_llegada 			= $('#arrival-picker-detail').val();
		$fecha_salida 			= $('#departure-picker-detail').val();
		$adultos 				= $('#adultos').val();
		$responseWrapper 		= $('#reservaCost');

		if ($fecha_llegada != '' && $fecha_salida != '' && $adultos != ''){
			$responseWrapper.slideUp();
			$.post($language+'/ajax/calendar-cost/', $('#reservationForm').serialize(), function(data){
				json = JSON.parse(data);
				if (json.message=='error'){
					formActive(true);
					$responseWrapper.html(json.html);
					$responseWrapper.slideDown();					
				} else {
					$responseWrapper.html(json.html);
					$responseWrapper.slideDown();
					formActive(false);
				}


			});
		}
	}

function formActive($prop){
	$('#bookingForm input, #bookingForm select, #bookingForm textarea, #bookingForm button').prop('disabled', $prop);
}
	
$(document).ready(function(){
	
	// Calculate costs
	$('.rentalOptions input, .rentalOptions select').change(calculateCosts);
	$('.rentalOptions input:checkbox').on('ifToggled', calculateCosts);
	$('input').on('ifToggled', function(){

		calculateCosts();

	});
	
	
	$('body').on('focus','.fecha', function(){
		$(this).fdatepicker();
	})
	
	$('body').on('focus','#reservationForm', function(){
		$('#reservationForm').ajaxForm({  success:showResponse });
	})
	
	$('#bookingForm input, #bookingForm select, #bookingForm textarea, #bookingForm button').prop('disabled', true);
	
	
	$('#bookingForm input, #bookingForm textarea').each(function(){
		$(this).prop('required', false);
	});
	
	$('#hora_llegada').timepicker();
	$('#hora_salida').timepicker();	
	
});





