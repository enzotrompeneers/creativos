<div id="v<?=$id?>" class="note note-success row">
	<div class="col-md-4">
		<a href="<?= $link?>" target="_new">
			<img src="<?= $img ?>" alt="" class="viviendaThumbnail"  />
		</a>
	</div>
	<div class="col-md-6">
		<h5><a href="<?=$link?>" target="_new"><?=$aVivienda['referencia']?> - <?=$aVivienda['titulo']?></a></h5>
		<h4><?= $localizacion?></h4>
		<p><strong><?= $precio ?>&euro;</strong></p>
	</div>
	<div class="col-md-1 text-right pull-right">
			
			<a href="#" class="borrar btn red" id="b<?= $id ?>"><i class="fa fa-times"></i></a>
		
	</div>
</div>