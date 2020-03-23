<?php
$current_query = get_queried_object();
$term = get_term_by('id', $current_query->term_id, 'product_cat');
$is_personalizados = term_is_ancestor_of(17, $current_query->term_id, 'product_cat');
echo do_shortcode('[rev_slider alias="personalizados"]');
$query = get_categories(array(
	'taxonomy'		=> 'product_cat',
	'parent'		=> 17,
	'hierarchical'	=> 1
));
if(count($query) > 0): ?>
<h1 class="text-center text-uppercase">Tienda / Personalizador</h1>
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
	}
	$last = get_post(303);
		$img = wp_get_attachment_url(get_post_thumbnail_id($last->ID)); ?>
			<a href="<?php echo get_permalink($last->ID); ?>" class="col-xs-12 col-sm-4">
				<h3><?php echo $last->post_title; ?></h3>
				<img src="<?php echo $img; ?>" alt="<?php echo $last->post_title; ?>">
			</a>
		</div>
	</div>
</div>
<?php
endif; ?>
<div class="white-shadow-bg">
	<h1 class="text-center text-uppercase">Nuevos diseños</h1>
	<div class="container woo-list3">
		<div class="row">
			<div class="woo-slide">
				<a href="#" class="arr"><i class="fa fa-angle-left"></i></a>
				<a href="#" class="arr right"><i class="fa fa-angle-right"></i></a>
				<div class="woo-slide-box" left="0" pages="2">
<?php
	$query = new WP_Query(array(
		'posts_per_page'	=> 10,
		'post_type'			=> 'product',
		'post_status'		=> 'publish',
		'post__not_in'		=> array(665, 1238),
		'tax_query'			=> array(
			array(
				'taxonomy'	=> 'product_cat',
				'field'		=> 'slug',
				'terms'		=> array('toallas-microfibra', 'toallas-microalgodon', 'mantas-polares'),
				'operator'	=> 'IN'
			),
			array(
				'taxonomy'	=> 'product_cat',
				'field'		=> 'slug',
				'terms'		=> array('toallas-microfibra', 'toallas-microalgodon', 'mantas-polares'),
				'operator'	=> 'IN'
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
	endif; ?>
				</div>
			</div>
		</div>
	</div>
	<h1 class="text-center text-uppercase">Nuevos productos</h1>
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
		'product_cat'		=> 'personalizados',
		'post__not_in'		=> array(665, 1238),
		'tax_query'			=> array(
			array(
				'taxonomy'	=> 'product_cat',
				'field'		=> 'slug',
				'terms'		=> array('toallas-microfibra', 'toallas-microalgodon', 'mantas-polares', 'banderolas'),
				'operator'	=> 'NOT IN'
			)
		)
	));
	if($query->have_posts()):
		while($query->have_posts()): $query->the_post(); ?>
					<a href="<?php the_permalink(); ?>" class="col-xs-12 col-sm-6 col-md-3">
	<?php
			$img = has_post_thumbnail() ? wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())) : '/wp-content/uploads/2018/05/default.jpg'; ?>
						<img src="<?php echo $img; ?>" alt="<?php the_title(); ?>">
						<h3><?php the_title(); ?></h3>
					</a>
	<?php
		endwhile;
	endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>