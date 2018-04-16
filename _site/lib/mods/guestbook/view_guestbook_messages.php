                <div class="testimonial-block-out">
				<?php foreach ($aGuest as $k=>$v) : ?>
                    <div class="testimonial-block">
                        <div class="testimonial-image">
                            <img src="<?=first_image('guestbook',$v['id'],'s')?>" alt="">
                        </div>
                        <div class="testimonial-content">
                            <h6><?=$v['nombre']?></h6>
                            <?=$v['mensaje']?>
							<?php if ($pagina=='inicio') : ?>
                            <a href="<?=$language?>/<?=slugged('testimonios')?>/" class="button"><?=trad('mas_info')?></a>
							<?php endif ?>
                        </div>
                    </div>
				<?php endforeach ?>
                </div>
