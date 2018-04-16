<section  class="content-container">
		<div id="full-height-block" class="row expanded">
			<div class="image-block large-7 medium-12 small-12 columns" style="background-color:#<?= $aProject['colores'][1] ?>;">
				<img src="<?= $aProject['imagenes'][0]['g'] ?>" alt="<?= $aProject['nombre'] ?> in <?= webConfig('nombre') ?>">
			</div>
			<div class="description-block large-5 medium-12 small-12 columns">
				<div>					
					<a href="<?= LANGUAGE ?>/">Home</a>
					<object>
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="30" height="20" viewBox="0 0 30 20">
							<metadata>
							<x:xmpmeta xmlns:x="adobe:ns:meta/" x:xmptk="Adobe XMP Core 5.6-c138 79.159824, 2016/09/14-01:09:01        ">
								<rdf:RDF xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#">
								<rdf:Description rdf:about=""/>
								</rdf:RDF>
							</x:xmpmeta>               
							</metadata>
							<image id="left_arrow_icon" width="30" height="20" xlink:href="data:img/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAUCAQAAAAwokVPAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAAmJLR0QAAKqNIzIAAAAJcEhZcwAACxIAAAsSAdLdfvwAAAAHdElNRQfhCQgLITPmcrMNAAAA9ElEQVQ4y53UvyvEYRwH8Nf33GI6g8WfYFFGsxtMyoBJV0aKlKJkQDGwnPxY5BgMyo8yKbIYrLrBv2B1BnSlM1w6d0ee5/vePk/Pq+czfD5PUtOSRK+nRrni72TaTrY86hSUbEu9bM6E9zR4xqppJ2G0ue2CoiV7ofQnHnFo03o4beC8UwcWYug3HnDl3JRaPO5z7U7BZxwlqfUo63bjLVJWzWYiSVOyng2692FM9bcL/41n2ZC8ko7Yl+ttPxg2akcS23Y9t8adqVhMg7k06UjFRhrMsZyiF/tpMNtydr2G7lXrPq/pUnIRttHtQzKvP/Qz+AJCijKGmEknJQAAAABJRU5ErkJggg=="/>
						</svg>
					</object>
					<p class="clearfix"></p>
					<h1><?= $aProject['nombre'] ?></h1>
					<?= $aProject['descripcion'] ?>
					<a href="<?= $aProject['url'] ?>" class="button" target="_blank" ><?= trad('visit_website') ?></a>
				</div>
			</div>
		</div>
		<div class="social-icons-block">
			<a class="twitter" href="#">
				<i class="fa fa-twitter"></i>
			</a>
			<a class="facebook" href="#">
				<i class="fa fa-facebook-official"></i>
			</a>
			<a class="email" href="#">
				<i class="fa fa-envelope-o"></i>
			</a>			
		</div>
		<div class="full-project-content">
			<div class="row">
				<div class="project-large-image large-12 columns">                        
                         <picture>
                              <source srcset="<?= $aProject['imagenes'][1]['m'] ?>" media="(max-width:599px)">  
                              <source srcset="<?= $aProject['imagenes'][1]['g'] ?>"> 
                              <img src="<?= $aProject['imagenes'][1]['g'] ?>" alt="<?= $aProject['nombre'] ?> in <?= webConfig('nombre') ?>"> 
                         </picture>
				</div>				
			</div>		
			<div class="row">
				<div class="project-thumbnail-images large-12 columns">
                         <picture>
                              <source srcset="<?= $aProject['imagenes'][2]['m'] ?>" media="(max-width:599px)">  
                              <source srcset="<?= $aProject['imagenes'][2]['g'] ?>"> 
                              <img src="<?= $aProject['imagenes'][2]['g'] ?>" alt="<?= $aProject['nombre'] ?> in <?= webConfig('nombre') ?>"> 
                         </picture>
				</div>	
			</div>
			<div class="other-projects-block">
				<h3>Otros proyectos:</h3>
				<?php foreach($aProjects as $proj) : ?> 
				<a href="<?= LANGUAGE ?>/proyectos_ficha/?clave=<?= $proj['clave'] ?>">
					<div class="project-thumbnail" style="background-color:#<?= $proj['colores'][0] ?>;">
						<img src="images/proyectos/<?= $proj['id'] ?>/<?= $proj['thumbnail'] ?>" alt="">
						<span class="project-title">
							<?= $proj['nombre'] ?>
						</span>
					</div>
				</a>
				<?php endforeach ?>
	
			</div>
		</div>
    </section>