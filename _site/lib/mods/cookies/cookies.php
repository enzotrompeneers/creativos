<link rel="stylesheet" href="lib/mods/cookies/cookies.css" />
<div id="barraaceptacion">
    <div class="inner">
        <div id="cookie_left">Utilizamos cookies propias y de terceros para realizar análisis de uso y de medición de nuestra web para mejorar nuestros servicios. <br />
		Si continua navegando, consideramos que acepta su uso. Puede cambiar la configuración u obtener más información <a href="<?=$language?>/<?=slugged('politica_cookies')?>/" class="info">aquí</a>.</div>
		<div id="cookie_right"><a href="javascript:void(0);" class="ok" onclick="acepto();"><img src="lib/mods/cookies/images/ok.png" alt="Estoy de acuerdo" id="ok_cookie" /></a></div>
    </div>
</div>
<script>
//**** Functions ****/
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
	window['ga-disable-UA-5985541-41'] = false;
   _gaq.push(['_trackPageview']);
}
//**** Logic ****//
// If barra cookie is not set
if (getCookie('ocultarbarra')!="1"){
	document.getElementById("barraaceptacion").style.display="block";
}
// If aceptocookies cookie is not set
if(getCookie('aceptocookies')!="1"){
	// Disable analytics if not accepted and show banner
	window['ga-disable-UA-5985541-41'] = true;
    
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
</script>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', '<?=webConfig('analytics')?>']);
  _gaq.push(['_trackPageview']);
  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>