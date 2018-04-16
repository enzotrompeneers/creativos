<div class="galleria">
	<?php foreach ($images as $k=>$v) : ?>
	<img src="images/<?=$folder?>/<?=$parent_id?>/g_<?=$v['file_name']?>" alt="<?=$v['descr_'.$language]?>" />
	<?php endforeach ?>
</div>
<script type="text/javascript" src="js/galleria/galleria-1.3.5.min.js"></script>
<script language="javascript" type="text/javascript">
	Galleria.loadTheme('js/galleria/themes/classic/galleria.classic.min.js');
	Galleria.run('.galleria', {
		autoplay : 7000, // will move forward every 7 seconds,
		lightbox : true,
		imageCrop : "landscape",
		responsive : true
});
</script>