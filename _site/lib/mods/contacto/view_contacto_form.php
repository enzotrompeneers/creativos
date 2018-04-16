<?php // HTLM for form ?>
<form id="contactoForm" action="<?=$language?>/<?=slugged('contacto')?>/send/" method="post" class="form">
	<div class="row">
		<div class="large-6 medium-6 column">
			<input type="hidden" name="ref" value="<?=req('ref')?>" />
			<input type="hidden" name="link" value="<?=req('link')?>" />
			<label for="nombre"><?=trad('nombre')?>:</label>
			<input type="text" id="nombre"  class="form-control" value="<?php if (!empty($value['nombre'])) echo $value['nombre']; ?>" name="nombre"  required />
			<label for="email"><?=trad('email')?>:</label>
			<input type="text" id="email" name="email"   value="<?php if (!empty($value['email'])) echo $value['email']; ?>" class="form-control" required />
		</div>	
		<div class="large-6 medium-6 column">			
			<label for="telefono" ><?=trad('telefono')?>:</label>
			<input type="text" id="telefono" name="telefono"  value="<?php if (!empty($value['telefono'])) echo $value['telefono']; ?>" class="form-control" required />
			<label for="localidad"><?=trad('localidad')?>:</label>
			<input type="text" id="localidad" name="localidad"  value="<?php if (!empty($value['localidad'])) echo $value['localidad']?>" class="form-control" />
			<!--<label for="mensaje"><?=trad('mensaje')?></label>-->
		</div>	
		<div class="large-12 column">
			<textarea id="mensaje" rows="5" cols="5" name="mensaje" class="form-control"><?php if (!empty($value['mensaje'])) echo $value['mensaje']?></textarea>
        </div>
		<div class="large-6 medium-6 column">
			<span class="crypto"><?php dsp_crypt('cryptographp.cfg.php',1); ?></span>
		</div>	
        <div class="large-6 medium-6 column">
            <label for="code"><?=trad('captcha')?></label>
			<input id="code" class=" form-control" type="text" name="code" required="" value="">
            <br clear="all"	 />	
        </div>
        <div class="large-12 column">
            <input id="btnEnviar" name="submit" type="submit" value="<?=trad('enviar')?>"  class="button expand" />
		</div>
	</div>			
</form>




