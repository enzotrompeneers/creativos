<?php if ($viewForm==TRUE) : ?>
<form action="" class="guestbook" method="post">
	<fieldset class="">
		<?=input('text','nombre',TRUE,TRUE);?>
		<?=input('text','email_guest',TRUE,TRUE);?>
		<?=textarea('mensaje',TRUE,TRUE);?>
		<?=input('submit','send');?>
	</fieldset>
</form>

<?php elseif ($thankyou==TRUE) : ?>
<?=art_sin('gracias_guestbook');?>
<?php else : ?>
<?=trad('error_guestbook');?>
<?php endif ?>