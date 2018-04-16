<?php
// Contact page

use \Brunelencantado\Formularios\FormularioContacto;
use Brunelencantado\Mail\Mailer;

// ID para el <body>
$bodyid = $pagina;
$bodyclass = '';

$view = 'contacto_form';
$errores = null;

if ($_POST) {
    $formContent = $_POST;
    $output = '';

    $required = [
                        'nombre' => 'texto',
                        'email' => 'texto',
                        'g-recaptcha-response' => 'recaptcha'
                        ];

    $formulario = new FormularioContacto($formContent, $required, $db);
    // $formulario->setRecaptchaClave($recaptchaSecretCode);

    $errores = $formulario->hasErrors();

    if (!$errores) {
        $ignores = ['g-recaptcha-response', 'submit'];

        $formulario->save($ignores);

        $mailer = Mailer::createMailer($db, $emailConfig);
        $mailer->to($formContent['email'], $formContent['nombre'])
                ->addDataTable($formContent, $ignores)
                ->addContentByKey('contacto')
                ->send();

        $mailer->to(webConfig('email'), webConfig('nombre'))
                ->from($formContent['email'], $formContent['nombre'])
                ->addDataTable($formContent, $ignores)
                ->addContentByKey('contacto')
                ->send();

        $view = 'contacto_gracias';
    }
}

// Cargamos las vistas
require_once dirname(__FILE__) . '/inc/html_head.php';
require_once dirname(__FILE__) . '/inc/web/contacto.php';
require_once dirname(__FILE__) . '/inc/footer.php';

// End file
?>
<script>  
    function myMap() {
        var myCenter = new google.maps.LatLng( <?=webconfig('lat');?>,<?=webconfig('lon');?>);
        var mapCanvas = document.getElementById("googleMap");
        var mapOptions = {
            center: myCenter,
            zoom: <?=webconfig('zoom');?>};
                var map = new google.maps.Map(mapCanvas, mapOptions);
            var marker = new google.maps.Marker({
                position: myCenter
            });
            marker.setMap(map);
        }
</script>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAXg4SxrjFWJB5RXPZ5bv7_PUwuVEQqp-M&callback=myMap" type="text/javascript"></script>

<style>
    .row .columns .row .column textarea, .row .columns .row .column input:not([type=submit]) {
        background-image: linear-gradient(-90deg, #c52762 0%, #882d85 100%);
        background-repeat: no-repeat;
        background-position: 0 calc(100% + 4px), 0 0;
        background-size: 100% 4px;
        box-shadow: none;
        border-bottom: 5px solid #d2205a;
        border-color: transparent;
        background: f5f5f5;
        background-color: rgb(245, 245, 245);
    }

    #contactoForm input:focus:required:invalid {
        border-bottom-color: #cc4b37;
    }
</style>