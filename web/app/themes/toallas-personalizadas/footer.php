	<footer>
		<div class="container">
			<div class="row">
				<div class="col-xs-12 col-md-6">
					<h3 class="text-uppercase">Contacto</h3>
					<div class="contact-info">
<?php
echo do_shortcode('[contact-info]'); ?>
					</div>
					<p class="text-center">
<?php
if(get_field('facebook', 6) != '')
	echo '						<a href="'.get_field('facebook', 6).'" target="_blank"><i class="fa fa-facebook"></i></a>
';
if(get_field('twitter', 6) != '')
	echo '						<a href="'.get_field('twitter', 6).'" target="_blank"><i class="fa fa-twitter"></i></a>
';
if(get_field('pinterest', 6) != '')
	echo '						<a href="'.get_field('pinterest', 6).'" target="_blank"><i class="fa fa-pinterest"></i></a>
';
if(get_field('google_plus', 6) != '')
	echo '						<a href="'.get_field('google_plus', 6).'" target="_blank"><i class="fa fa-google-plus"></i></a>
'; ?>
					</p>
				</div>
				<div class="col-xs-12 col-md-6">
					<h3 class="text-uppercase">Escríbenos</h3>
<?php
echo do_shortcode('[contact-form-7 id="35" title="Contact form 1"]'); ?>
				</div>
			</div>
		</div>
		<div class="copyright">
			<div class="container">
				© 2019 <a title="Camisetas serigrafiadas. Camisetas promocionales" href="https://camisetas-serigrafia.es/">Camisetas serigrafiadas. Camisetas promocionales</a> | <a href="https://toallas-personalizadas.es/product-category/productos/p-mantas/" target="_blank">Mantas personalizadas</a> | <a href="https://publiofertix.com/" target="_blank">Publiofertix</a>
<?php
wp_nav_menu(array(
	'container'			=> '',
	'theme_location'	=> 'footer',
	'menu_class'		=> 'menu-footer',
)); ?>
			</div>
		</div>
	</footer>
	<div class="text-center developer">© 2019 Desarrollado por <a href="https://doover.es/" rel="nofollow" target="_blank">DOOVER NETWORK</a></div>
<?php
wp_footer(); ?>
</body>
</html>