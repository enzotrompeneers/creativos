<?php if ($viewForm==TRUE) : ?>
<form action="" class="guestbook" method="post">
	<fieldset class="">
	<?=$code?>
		<?=input('text','nombre',TRUE,TRUE);?>
		<?=input('text','email_guest',TRUE,TRUE);?>
		<?=textarea('mensaje',TRUE,TRUE);?>
		<?php dsp_crypt('cryptographp.cfg.php',1); ?>
		<label for="code"><?=trad('captcha')?></label>
		<input id="code" class="form-control" type="text" name="code" required="" value="">
		<?=input('submit','send');?>
	</fieldset>
</form>
<?php elseif ($thankyou==TRUE) : ?>
<p class="info success"><?=art_sin('gracias_guestbook');?></p>
<?php else : ?>
<?=trad('error_guestbook');?>
<?php endif ?>