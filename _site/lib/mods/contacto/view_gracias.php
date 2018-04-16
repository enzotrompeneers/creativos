<?php //Mensaje de gracias y código conversión si corresponde ?>
<?php $gracias = (art_sin('gracias')!='')?art_sin('gracias'):'Gracias por su interés, le responderemos en breve.';?>
<p><strong><?= $gracias ?></strong></p>