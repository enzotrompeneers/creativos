<div id="noticia">
	<h2 class="titulo"><?=$titulo?></h2>
	<div class="fecha"><?=$fecha?></div>
	<div class="cuerpo">
	<?php if(!empty($first)) : ?>
	<a href="<?=$first['l']?>" class="colorbox" rel="images"><img src="<?=$first['m']?>" alt="<?=$titulo?>" class="img-right" /></a>
	<?php endif ?>
	<?=$cuerpo?>
	</div>
</div>
<?=$galeria?>