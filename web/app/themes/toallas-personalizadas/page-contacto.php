<?php
/**
 * @package ToallasPersonalizaas
 * @subpackage ToallasPersonalizaas
 * @since Toallas Personalizadas 2.0
 */

get_header(); ?>

<div class="container-fluid contacto">
	<div class="row">
<?php
if(have_posts()):
	while(have_posts()): the_post();
		if(has_post_thumbnail()){
			$img = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())); ?>
		<img src="<?php echo $img; ?>" alt="<?php the_title(); ?>" style="width: 100%;">
<?php
		} ?>
		<div class="white-shadow-bg">
			<div class="container section-page">
				<h1 class="text-center text-uppercase"><?php the_title(); ?></h1>
<?php
		the_content(); ?>
			</div>
		</div>
		<div class="contact-map">
			<div class="container-fluid">
				<div class="row">
					<div class="container">
						<div class="col-xs-6 col-sm-5 col-md-4 info">
							<?php the_custom_logo(); ?><br>
						</div>
					</div>
				</div>
			</div>
<?php
		echo do_shortcode('[wpgmza id="1"]');?>
		</div>
<?php
		if(get_field('bloque_rojo') != ''){ ?>
		<div class="red-box">
			<div class="container">
<?php
			the_field('bloque_rojo'); ?>
			</div>
		</div>
<?php
		}
		if(get_field('bloque_gris') != ''){ ?>
		<div class="gray-shadow-bg">
			<div class="container">
<?php
			the_field('bloque_gris'); ?>
			</div>
		</div>
<?php
		}
	endwhile;
endif; ?>
	</div>
</div>

<?php get_footer(); ?>