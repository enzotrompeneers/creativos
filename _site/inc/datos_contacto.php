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