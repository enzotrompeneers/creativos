	<div class="panoramicas">
	<?php if ($panoramicas) : ?>
	<?php foreach ($panoramicas as $k=>$v) : ?>
	<?php $active = ($nPan==1)?'active':''; ?>
		<div class="pano <?=$active?>" id="slider<?=$nPan?>" style="background: #f7f7f7 url(images/panoramicas/0/<?=$v['file_name']?>) center center no-repeat;">
			<div class="row wrapper">
			&nbsp; 
			</div>
		</div>
	<?php $nPan++;?>
	<?php endforeach ?>
	<?php endif ?>
	</div>