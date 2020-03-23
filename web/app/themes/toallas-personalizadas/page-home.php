<?php
/**
 * @package toallas-presonalizadas
 * @subpackage toallas-presonalizadas
 * @since Toallas Personalizadas 2.0
 */

get_header(); ?>

<div class="container-fluid">
	<div class="row">
<?php
if(have_posts()):
	while(have_posts()): the_post();
		the_content();
	endwhile;
endif;
$query = get_categories(array(
	'taxonomy'		=> 'product_cat',
	'parent'		=> 299,
	'hierarchical'	=> 1
));
if(count($query) > 0): ?>
		<h1 class="text-center text-uppercase">Catálogo de productos</h1>
		<div class="gray-shadow-bg">
			<div class="container product-list-1">
				<div class="row">
<?php
	foreach($query as $key => $value){
		$img = wp_get_attachment_url(get_woocommerce_term_meta($value->term_id, 'thumbnail_id', true)); ?>
					<a href="<?php echo get_term_link($value->term_id, 'product_cat'); ?>" class="col-xs-12 col-sm-4">
						<h3><?php echo $value->cat_name; ?></h3>
						<img src="<?php echo $img; ?>" alt="<?php echo $value->cat_name; ?>">
					</a>
<?php
	} ?>
				</div>
			</div>
		</div>
<?php
endif; ?>
	</div>
</div>
<div class="white-shadow-bg">
	<h1 class="text-center text-uppercase">Top Ventas</h1>
	<div class="container woo-list4">
		<div class="row">
			<div class="woo-slide">
				<a href="#" class="arr"><i class="fa fa-angle-left"></i></a>
				<a href="#" class="arr right"><i class="fa fa-angle-right"></i></a>
				<div class="woo-slide-box" left="0" pages="2">
<?php
$query = new WP_Query(array(
	'posts_per_page'	=> 8,
	'post_type'			=> 'product',
	'post_status'		=> 'publish',
	'tax_query'			=> array(
		array(
			'taxonomy'	=> 'product_visibility',
			'field'		=> 'name',
			'terms'		=> 'featured',
		)
	)
));
if($query->have_posts()):
	while($query->have_posts()): $query->the_post(); ?>
					<a href="<?php the_permalink(); ?>" class="col-xs-6 col-sm-2 col-sm-x5">
	<?php
		$img = has_post_thumbnail() ? wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())) : '/wp-content/uploads/2018/05/default.jpg'; ?>
						<img src="<?php echo $img; ?>" alt="<?php the_title(); ?>">
					</a>
	<?php
	endwhile;
endif;
wp_reset_postdata(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
$query = get_posts(array(
	'category_name'		=> 'trabajos',
	'post_status'		=> 'publish',
	'post_type'			=> 'post',
	'posts_per_page'	=> 10
));
if(count($query) > 0): ?>
<div class="container-fluid">
	<div class="row">
		<h1 class="text-center text-uppercase">Últimos trabajos</h1>
		<div class="trabajos popup-links">
<?php
	foreach($query as $key => $value){
		$img = wp_get_attachment_url(get_post_thumbnail_id($value->ID)); ?>
	
			<a href="<?php echo $img; ?>" rel="prettyPhoto[trabajos]" title="<?php echo $value->post_title; ?>" class="col-xs-12 col-sm-2 col-sm-x5">
				<img src="<?php echo $img; ?>" alt="<?php echo $value->post_title; ?>">
			</a>
<?php
	} ?>
		</div>
	</div>
</div>
<?php
endif;
wp_reset_postdata(); ?>

<?php get_footer(); ?>
