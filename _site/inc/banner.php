<?php if($pagina == 'inicio' && !empty($panoramicas)) : ?>

<!-- ########## HOME PAGE SLIDER ########## -->

<div class="banner-outer">
  <ul class="banner-slider" data-cycle-fx="scrollHorz" data-cycle-speed="1000" data-cycle-manual-speed="1000" data-cycle-timeout="8000" data-slides=">li" data-cycle-prev="#prev" data-cycle-next="#next">
    <?php // show_panoramicas()?>
	<?php foreach ($panoramicas as $k=>$v) : ?>
	<li><img src="images/panoramicas/0/<?=$v['file_name']?>" alt="<?=webConfig('nombre');?>" title="<?=webConfig('nombre');?>" /></li>
	<?php endforeach ?>
  </ul>
  <div class="banner-navigation"> <span id="prev"></span> <span id="next"></span> </div>
  <div class="banner-content-outer">
    <div class="row">
      <div class="medium-12 columns">
        <div class="banner-content">
          <div class="banner-main-title">
            <h1><?=webconfig("nombre");?></h1>
          </div>
          <a href="tel:<?=webconfig("telefono");?>" class="button"><?=trad("oficina");?> <?=webconfig("telefono");?></a> <br>
          <a href="tel:<?=webconfig("movil");?>" class="button"><?=trad("movil");?> <?=webconfig("movil");?></a> </div>
		 <!--
        <div class="filter-outer">
          <div class="row">
            <div class="medium-12 columns">
              <div class="filter-box-home">
                <div class="filter-box">
                  
                  <?php //include("inc/buscador.php");?>
                </div>
              </div>
            </div>
          </div>
        </div>
		-->
      </div>
    </div>
  </div>
</div>
<?php else : ?>

<!-- ########## INNER PAGE SLIDER ########## -->

<div class="inner-page-banner"> 
<img src="images/panoramicas/0/<?=$panoramicas[0]['file_name']?>" alt="">
  <div class="inner-banner-content-outer">
    <div class="row">
      <div class="medium-12 columns">
        <div class="filter-box-inner">
          <div class="filter-box">
            
            <?php include("inc/buscador.php");?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php endif ?>