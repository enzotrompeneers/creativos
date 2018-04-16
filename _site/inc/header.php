     <header>
        <div class="header-outercon">
            <div class="logo-outer">
                <a class="logo1" href="<?= $language ?>/" title="<?= webConfig('nombre') ?> <?=trad("disenoweb_torrevieja")?>"><img src="images/be-creativos-white.svg" alt="<?= webConfig('nombre') ?>" /></a>
                <a class="logo2" href="<?= $language ?>/" title="<?= webConfig('nombre') ?> <?=trad("disenoweb_torrevieja")?>"><img src="images/be-creativos-color.svg" alt="<?= webConfig('nombre') ?>" /></a>
            </div>
            
            <div class="header-right-outer">
                <div class="language-outer lang-<?=$language?>">
					<?php echo getIdiomas('idiomas', true) ?>
                </div>
                
                <div class="contact-outer">
                    <ul>
                        <li class="tel"><a href="<?= webConfig('telefono') ?>"><?= webConfig('telefono') ?></a></li>
                        <li class="mail"><a href="<?= webConfig('email') ?>"><?= webConfig('email') ?></a></li>
                    </ul>
                </div>
            </div>
            
            <div class="contact-outer mob-view">
                <ul>
                    <li class="tel"><a href="<?= webConfig('telefono') ?>"><?= webConfig('telefono') ?></a></li>
                    <li class="mail"><a href="<?= webConfig('email') ?>"><?= webConfig('email') ?></a></li>
                </ul>
            </div>
        </div>
    </header>
<div class="sticky-head"></div>