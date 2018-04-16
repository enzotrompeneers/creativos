        <ul id="listadoNoticias">
		<?php if ($noticiasLimit) : ?>
        <?php foreach($noticiasLimit as $k => $v):  ?>
            <li>
				<h3>
					<a href="<?=$language?>/<?=slugged($pagina)?>/<?=slug($v['titulo_'.$language])?>-<?= $v['id']; ?>.html?clave=<?=$clave?>" class="title" title="<?= $v['titulo_'.$language]; ?>"><?= $v['titulo_'.$language]; ?></a>
					<em class="fecha"><?= date('d/m/Y', strtotime($v['fecha'])); ?></em>
				</h3>
            </li>
		<?php endforeach ?>
		<?php else : ?>
		<p>Sin noticias</p>
		<?php endif ?>
         </ul>
		 
		 <?php if ($results>$pagesize) : ?> 
			<br clear="both"/>
			 <div id="pagingBar">
				<?=$pagination?>
			 </div>
		 <?php endif ?>