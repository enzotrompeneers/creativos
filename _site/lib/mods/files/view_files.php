<?php // HTLM for file list ?>
<ul class="file_list">
	<?php foreach($oFiles as $k => $v) : ?>
	<li><img src="images/file_icons/<?=$v['icon']?>.png" alt="" />&nbsp;<a href="<?=$v['link']?>" target="_new" title="<?=$v['file_name']?>"><?=$v['file_name']?></a> (<?=$v['file_size']?>)</li>
	<?php endforeach ?>
</ul>




