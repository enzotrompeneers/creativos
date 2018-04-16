<section  class="content-container interna">
    <div class="slider-outercon"></div>
    <div class="title-block">
        <h1><?= title($pagina) ?></h1>
    </div>
    <div class="featured-block-out">
		<div class="row collapse">
			<div class="medium-4 columns">
				<?=show_login()?>
			</div>
		</div>
	</div>
    <div class="design-outer">
			<div class="design-inner">
				<div class="design-left-blk">
					<div class="common-blk">
						<h2><?=trad('be_visible')?></h2>
						<p><?= art_sin('be_visible'); ?></p>
					</div>
					<div class="common-blk">
						<h2><?=trad('be_creative')?></h2>
						<p><?= art_sin('be_creative'); ?></p>
					</div>
					
				</div>
				<div class="design-middle-blk">
				<div class="rotate-block">	<div class="middle-left middle-left1">
						<h2><?=trad('diseno_ux')?></h2>
						<p><?= art_sin('diseno_ux') ?></p>
					</div>
				     <svg class="left-circle"  viewBox="0 0 250 250"
     xmlns="http://www.w3.org/2000/svg" version="1.1"
     xmlns:xlink="http://www.w3.org/1999/xlink" >

    <path class="dashed" fill="none" stroke="#fff" stroke-width="1" stroke-linejoin="round" stroke-miterlimit="1" d="M75,0.05c10.118,0,19.934,1.982,29.174,5.89c8.925,3.775,16.94,9.179,23.823,16.063
		c6.884,6.883,12.288,14.899,16.063,23.824c3.909,9.24,5.891,19.056,5.891,29.174c0,10.118-1.981,19.933-5.891,29.173
		c-3.774,8.925-9.179,16.94-16.063,23.823c-6.883,6.884-14.898,12.288-23.823,16.063c-9.24,3.909-19.056,5.891-29.174,5.891
		s-19.934-1.981-29.174-5.891c-8.925-3.774-16.94-9.179-23.824-16.063c-6.883-6.883-12.288-14.898-16.063-23.823
		C2.031,94.934,0.049,85.118,0.049,75c0-10.118,1.982-19.934,5.89-29.174c3.775-8.925,9.179-16.94,16.063-23.824
		S36.901,9.715,45.826,5.94C55.066,2.032,64.882,0.05,75,0.05 M75,0C33.578,0,0,33.579,0,75c0,41.419,33.579,75,75,75
		c41.421,0,75-33.58,75-75C150,33.579,116.421,0,75,0L75,0z">
        <animateTransform attributeName="transform"
                          attributeType="XML"
                          type="rotate"
                          from="0 75 75"
                          to="360 75 75"
                          dur="15s"
                          repeatCount="indefinite"/>
    </path>
</svg>
      
      <div class="middle-left">
						<h2><?=trad('tecnologia')?></h2>
						<p><?= art_sin('tecnologia') ?></p>
					</div>
       
     <svg class="right-circle"  viewBox="0 0 250 250"
     xmlns="http://www.w3.org/2000/svg" version="1.1"
     xmlns:xlink="http://www.w3.org/1999/xlink" >

    <path class="dashed" fill="none" stroke="#fff" stroke-width="1" stroke-linejoin="round" stroke-miterlimit="1" d="M75,0.05c10.118,0,19.934,1.982,29.174,5.89c8.925,3.775,16.94,9.179,23.823,16.063
		c6.884,6.883,12.288,14.899,16.063,23.824c3.909,9.24,5.891,19.056,5.891,29.174c0,10.118-1.981,19.933-5.891,29.173
		c-3.774,8.925-9.179,16.94-16.063,23.823c-6.883,6.884-14.898,12.288-23.823,16.063c-9.24,3.909-19.056,5.891-29.174,5.891
		s-19.934-1.981-29.174-5.891c-8.925-3.774-16.94-9.179-23.824-16.063c-6.883-6.883-12.288-14.898-16.063-23.823
		C2.031,94.934,0.049,85.118,0.049,75c0-10.118,1.982-19.934,5.89-29.174c3.775-8.925,9.179-16.94,16.063-23.824
		S36.901,9.715,45.826,5.94C55.066,2.032,64.882,0.05,75,0.05 M75,0C33.578,0,0,33.579,0,75c0,41.419,33.579,75,75,75
		c41.421,0,75-33.58,75-75C150,33.579,116.421,0,75,0L75,0z">
        <animateTransform attributeName="transform"
                          attributeType="XML"
                          type="rotate"
                          from="360 75 75"
                          to="0 75 75"
                          dur="15s"
                          repeatCount="indefinite"/>
    </path>
</svg>
      
				</div>
				
					
				</div>
				<div class="design-left-blk right-con">
					<div class="common-blk">
						<h2><?=trad('be_effective')?></h2>
						<p><?= art_sin('be_effective'); ?></p>
					</div>
					<div class="common-blk">
						<h2><?=trad('be_profitable')?></h2>
						<p><?= art_sin('be_profitable'); ?></p>
					</div>
				</div>
			</div>
		</div>
	<div class="two-col-outer">
			<div class="single-block"></div>
			<div class="single-block second"></div>
			
			<div class="contact-blk">
				<div class="contact-top">
					<div class="contact-left">
						<h3>Torrevieja</h3>
						<address>
							<p><?= webConfig('calle') ?>,<br><?= webConfig('detalle_direccion') ?><br>03181 Torrevieja</p>
						</address>
					</div>
					<div class="contact-right">
						<a href="tel:<?= webConfig('telefono') ?>"><?= webConfig('telefono') ?></a>
						<a href="mailto:<?= webConfig('email') ?>" class="mail"><?= webConfig('email') ?></a>
					</div>
				</div>
				<div class="contact-top grey-blk">
					<div class="contact-left">
						<h3>Alicante</h3>
						<address>
							<p><?= webConfig('calle_alicante') ?><br><?= webConfig('codigo_postal_alicante') ?> Alicante</p>
						</address>
					</div>
					<div class="contact-right">
						<a href="tel:<?= webConfig('telefono_alicante') ?>"><?= webConfig('telefono_alicante') ?></a>
						<a href="mailto:<?= webConfig('email_alicante') ?>" class="mail"><?= webConfig('email_alicante') ?></a>
					</div>
				
				</div>
			</div>
		</div>
    </section>
	
</section>

