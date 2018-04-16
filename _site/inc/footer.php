<footer class="footer-container">
		<div class="row">
			<div class="medium-12 columns">
				<div class="footer-inner">
					<div class="footer-left">
						<span><?=trad('conectamos')?></span>
						<h2><?=trad('diga_hola')?></h2>
					</div>
					<div class="footer-right">
						<div class="hello-blk">
							<?=trad('di_hola')?>
						</div>
						<div class="social-icons">
							<ul>
								<li>
									<a href="<?= webConfig('facebook') ?>"><span class="be-icon5"> </span></a>
								</li> 
								<li>
									<a href="<?= webConfig('instagram') ?>"><span class="be-icon6"> </span></a>
								</li>
								<li>
									<a href="<?= webConfig('behance') ?>"><span class="be-icon7"> </span></a>
								</li>
								<li><div class="gpartner">
							<script src="https://apis.google.com/js/platform.js"></script>
							<div class="g-partnersbadge" data-agency-id="5111205027"></div>
						</div></li>
							</ul>
						</div>

						
					</div>
					<div class="copyright">
						<p>Copyright <?= date('Y') ?> - 
							<?= webConfig('nombre') ?> -
							<a href="<?= $language ?>/<?= slugged ('aviso_legal') ?>/"><?= linkit('aviso_legal') ?></a> - 
							<a href="<?= $language ?>/<?= slugged ('politica_privacidad') ?>/"><?= linkit('politica_privacidad') ?></a> - 
							<a href="<?= $language ?>/<?= slugged ('politica_cookies') ?>/"><?= linkit('politica_cookies') ?></a>
						</p>
					</div>
				</div>
			</div>
		</div>
		<a href="#" id="back-to-top" title="Back to top"><img src="images/scroll.png" alt="" /></a> 
	</footer>
    <script src="js/vendor/jquery.min.js"></script>
    <script src="js/vendor/what-input.min.js"></script>
    <script src="js/vendor/foundation.min.js"></script>
    <script src="js/imgLiquid-min.js"></script>
    <script src="js/jquery.bxslider.min.js"></script>
    <script src="js/fix-header.min.js"></script>
	<script src='https://www.google.com/recaptcha/api.js'></script>
    <script src="js/main.min.js"></script>
	<div id="barraaceptacion">

</div>

<?php // if (!empty($log) && is_object($log) && pTEXT==false) echo '<br clear="all" />' . $log->showLog(); ?>
</body>

</html>