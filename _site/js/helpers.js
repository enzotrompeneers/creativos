/***** FORMAT CURRENCY *****/	
	
function formatCurrency(num) {
    num = isNaN(num) || num === '' || num === null ? 0.00 : num;
    return parseFloat(num).toFixed(2);
}

$(document).ready(function(){



// Fancybox
$(".fancybox").fancybox();

// Toggle buscador
$('#buscadorToggle').click(function(e){
	$('#buscador').slideToggle('normal');
	e.preventDefault();
});

// Reset button
$('#reset').click(function(){
	$selects = $('select option');
	$.each($selects,function(){
		$(this).removeAttr('selected');
	});
});




function setCookie(c_name,value,exdays){
    var exdate=new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
    document.cookie=c_name + "=" + c_value;
}
function getCookie(c_name){
    var c_value = document.cookie;
    var c_start = c_value.indexOf(" " + c_name + "=");
    if (c_start == -1){
        c_start = c_value.indexOf(c_name + "=");
    }
    if (c_start == -1){
        c_value = null;
    }else{
        c_start = c_value.indexOf("=", c_start) + 1;
        var c_end = c_value.indexOf(";", c_start);
        if (c_end == -1){
            c_end = c_value.length;
        }
        c_value = unescape(c_value.substring(c_start,c_end));
    }
    return c_value;
} 

var $input_array = [];
 
/***** INPUT SELECT *****/
	$('form.input input,form textarea').focus(function(){
		$(this).addClass('selected');
	}); 
	$('form.input input,form textarea ').blur(function(){
		$(this).removeClass('selected');
	});

// Confirm action
$(document.body).on('click','.confirm', function(e) {
	if (confirm('¿¿¿SEGURO???')) {   
		var $link = $(this).attr('href');
		$.get($link,function(data){
			$('#album').html(data);
		});
		e.preventDefault();
	} else {
		return false;
	}
});
$('.borrar').click(function(){
	if (!confirm('¿¿¿SEGURO???')) {   
		return false;
	} 
});
/***** CLEAR INPUT FIELD *****/
	$('form .clear').click(function(){
		$input_id = $(this).attr('id');
		$input_array[$input_id] = $(this).attr('value');
		$(this).attr('value','');
		//alert($input_array[$input_id]);
	});
	$('form .clear').blur(function(){
		$input = $(this).attr('value');
		$input_id = $(this).attr('id');
		if ($input=='') {
			$(this).attr('value',$input_array[$input_id]);
		};
	});
	
/***** BORRAR *****/
	$('#borrar').submit(function(){
		return confirm('¿SEGURO?');  
	});

	
/***** LOGIN USER FOCUS *****/	
   	$('.focus').focus();

/***** CLICK TO EXPAND *****/
$('.click').next('div').hide();
$('.click').toggle(function(){
	$(this).next('div').slideDown();
},function(){
	$(this).next('div').slideUp();
})

	
	
$('#galeria a').click(function(){
$new_image = $(this).attr('href');
var $new_big_image = $(this).attr('href').replace('/l_','/g_');
$('#main_image').attr('src',$new_image);
$('#big_link').attr('href',$new_big_image);
return false;
});

/**** LANGUAGES HIDE/SHOW ****/

 $('#current').mouseover(
	function(){
		$('#languages li').show();
		$(this).addClass('borderB');
	});
	$('ul#languages').mouseleave(
	function(){
		$('#languages li').hide();
		$('#current').removeClass('borderB');
		$('#current').show();
	});
	//$('#current').click(function(){ return:false; });



/***** OPEN/CLOSE MENUS ******/
$('.submenu').hide();
		$('#sidebar ul#sideMenu li.categoria ol li a.has_sub').click(function() {
			$(this).siblings('ul').slideToggle('normal');
			if ($(this).attr('href')=='#') {
				return false;
			}
			return false;
	});

	
//**** Logic ****//
// If barra cookie is not set
if (getCookie('ocultarbarra')!="1"){
	document.getElementById("barraaceptacion").style.display="block";
}
// If aceptocookies cookie is not set
if(getCookie('aceptocookies')!="1"){
	// Disable analytics if not accepted and show banner
	// window['ga-disable-UA-5985541-41'] = true;
    
	// Enable analytics and set cookie if any link is clicked
	$('a').click(function(e){ PonerCookie(); });
	// Enable analytics and set cookie if scrolled more than 300px
	window.onscroll = function(){
		$number = window.pageYOffset;
		if ($number>300){ PonerCookie(); }
	}
	// Enable analytics and set cookie user on page longer than 10 seconds
	setTimeout(function(){ PonerCookie(); },10000);	
}
	
});



//**** cookie Functions ****/
function getCookie(c_name){
    var c_value = document.cookie;
    var c_start = c_value.indexOf(" " + c_name + "=");
    if (c_start == -1){
        c_start = c_value.indexOf(c_name + "=");
    }
    if (c_start == -1){
        c_value = null;
    }else{
        c_start = c_value.indexOf("=", c_start) + 1;
        var c_end = c_value.indexOf(";", c_start);
        if (c_end == -1){
            c_end = c_value.length;
        }
        c_value = unescape(c_value.substring(c_start,c_end));
    }
    return c_value;
} 
function setCookie(c_name,value,exdays){
    var exdate=new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
	c_value += ";domain="+$('meta[name=domain]').attr('content')+";path=/";
    document.cookie=c_name + "=" + c_value;
}
// Oculta barra, activa analytics y 
function acepto(){
	 $("#barraaceptacion").fadeOut();
	 setCookie('ocultarbarra','1',365);
	 PonerCookie();
}
// Pone cookie de aceptación y activa analytics
function PonerCookie(){
    setCookie('aceptocookies','1',365);
	// window['ga-disable-UA-5985541-41'] = false;
   // _gaq.push(['_trackPageview']);
}

/***** VALID EMAIL *****/

function isEmail(valor) {
	if (/^w+([.-]?w+)*@w+([.-]?w+)*(.w{2,3})+$/.test(valor)){
		//alert("La dirección de email " + valor + " es correcta.")
		return (true)
	} else {
		//alert("La dirección de email es incorrecta.");
		return (false);
	}
}