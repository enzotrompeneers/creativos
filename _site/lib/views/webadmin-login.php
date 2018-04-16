<?php
// Show user login form 
?>
<div class="row">
	<form id="" method="post" class="form medium-4 columns" action="">
		<?=$errorMessage;?>
		<?= input('text','username','',TRUE);?>
		<?= input('password','password','',TRUE);?>
		<?= input('submit','enviar');?>

	</form>
</div>