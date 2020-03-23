<?php
$current_query = get_queried_object();
$term = get_term_by('id', $current_query->term_id, 'product_cat');
$is_personalizados = term_is_ancestor_of(17, $current_query->term_id, 'product_cat');
$query = get_categories(array(
	'taxonomy'		=> 'product_cat',
	'parent'		=> 299,
	'hierarchical'	=> 1
));
if(count($query) > 0): ?>
<h1 class="text-center text-uppercase" style="padding-top: 70px;">Catálogo de productos</h1>
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
endif;