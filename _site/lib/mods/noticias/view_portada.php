<ul id="datos_contacto_side">
<?php foreach ($noticias as $k=>$v) : ?>
        <li>
        	<a href="<?=$v['link']?>"><img src="<?=$v['img']?>" /></a>
                <div class="news_title">
                    <a href="<?=$v['link']?>"><p><a href="<?=$v['link']?>"><?=$v['titulo']?></a></p>
                    <p class="date"><?=$v['fecha']?></p>
					<p><?=$v['descr']?></p>
                </div>
			
        </li>
<?php endforeach ?>
</ul>