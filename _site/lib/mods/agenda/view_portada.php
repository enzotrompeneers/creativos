<ul id="datos_contacto_side">
<?php foreach ($noticias as $key=>$val) : ?>
        <li>
        	<a href="<?=$val['link']?>">
        		<img src="<?=$val['img']?>" />
                <div class="news_title">
                    <p><?=$val['titulo_'.$language]?></p>
                    <p class="date"><?=$val['fecha']?></p>
                </div>
			</a>
        </li>
<?php endforeach ?>
</ul>