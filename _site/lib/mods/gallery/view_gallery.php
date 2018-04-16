<div id="comment">
	<?=$album['descr_'.$language]?>
</div>
<ul class="small-block-grid-5">
	<?php foreach ($images as $k=>$v) : ?>
	<li><a href="images/albumes/<?=$v['parent_id']?>/g_<?=$v['file_name']?>" class="colorbox" rel="colorbox"><img src="images/albumes/<?=$v['parent_id']?>/m_<?=$v['file_name']?>" alt="<?=$album['titulo_'.$language]?>" /></a></li>
	<?php endforeach ?>
</ul>