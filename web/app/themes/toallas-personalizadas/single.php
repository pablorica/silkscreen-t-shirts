<?php
/**
 * @package toallas-personalizadas
 * @subpackage toallas-personalizadas
 * @since Toallas Personalizadas 2.0
 */

get_header(); ?>

<div class="container-fluid">
	<div class="row">
<?php
if(have_posts()):
	while(have_posts()): the_post(); ?>
		<div class="gray-shadow-bg">
			<div class="container">
				<div class="row">
					<div class="col-xs-12 col-sm-8 col-md-9 single-content">
						<h1><?php the_title(); ?></h1>
						<p><b>Fecha de publicación:</b> <?php the_date(); ?></p>
<?php
		if(has_post_thumbnail()){
			$img = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())); ?>
						<img src="<?php echo $img; ?>" alt="<?php the_title(); ?>" class="img-blog">
<?php
		}
		the_content(); ?>
					</div>
					<ul class="col-xs-12 col-sm-4 col-md-3 sidebar">
<?php
		dynamic_sidebar('blog-sidebar'); ?>
					</ul>
				</div>
			</div>
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