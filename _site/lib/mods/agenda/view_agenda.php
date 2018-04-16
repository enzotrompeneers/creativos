<ul id="agenda">
	<?php foreach ($agenda as $k=>$v) : ?>
	<li>
		<div class="leftAgenda">
			<p class="fecha"><?= date('d/m/Y', strtotime($v['fecha'])); ?></p>
			<a href="<?=first_image('agendas',$v['id'],'l')?>" class="colorbox"><img src="<?=first_image('agendas',$v['id'],'m')?>" alt="<?=$v['titulo']?>" /></a>
		</div>
		<div class="rightAgenda">
			<h2><?=$v['titulo']?></h2>
			<div class="txt"><?=$v['descripcion']?></div>
		</div>
	</li>
	<?php endforeach ?>
</ul>