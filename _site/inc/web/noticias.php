    <section  class="content-container interna">
    <div class="slider-outercon"></div>
    <div class="title-block">
        <h1><?= title($pagina) ?></h1>
    </div>
    <div class="featured-block-out">
		<div class="row collapse">
			<div class="large-4 columns">
				<?=show_lista_noticias(8);?>
			</div>
			<div class="large-8 columns">
				<?=show_noticia($id);?>
			</div>			
		</div>
	</div>
    <?php include('inc/circulos.php') ?>
    <?php include('inc/datos_contacto.php') ?>
    </section>