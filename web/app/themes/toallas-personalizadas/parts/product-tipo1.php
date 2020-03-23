<?php
global $product;
$current_query = get_queried_object();
$is_personalizados = false;
$terms = wp_get_post_terms($current_query->ID, 'product_cat');
foreach($terms as $term){
	if(term_is_ancestor_of(17, $term->term_id, 'product_cat')) $is_personalizados = true;
} ?>
<div class="row woo-product1">
	<div class="col-xs-12 col-md-5 col-md-push-7">
<?php
$attributes = $product->get_attributes();
foreach($attributes as $attribute):
	echo '<h4 class="text-uppercase">'.$attribute['name'].'</h4>';
	if($attribute['is_taxonomy'])	$values = wc_get_product_terms($product->id, $attribute['name'], array('fields' => 'names'));
	else							$values = array_map('trim', explode(WC_DELIMITER, $attribute['value']));
	echo apply_filters('woocommerce_attribute', wpautop(wptexturize(implode(', ', $values))), $attribute, $values);
endforeach; ?>
	</div>
	<div class="col-xs-12 col-md-7 col-md-pull-5">
<?php
$attachment_ids = $product->get_gallery_attachment_ids();
$first = true;
foreach($attachment_ids as $attachment_id){
	$img = get_post($attachment_id);
	if($first){
		$first = false; ?>
		<div class="big" e="<?php echo $attachment_id; ?>">
			<a href="#" class="arr"><i class="fa fa-angle-left"></i></a>
			<a href="#" class="arr right"><i class="fa fa-angle-right"></i></a>
			<img src="<?php echo $img->guid; ?>" alt="<?php echo $img->post_title; ?>">
			<p><?php echo $img->post_title; ?></p>
		</div>
		<div class="list">
<?php
	} ?>
			<a href="#" e="<?php echo $attachment_id; ?>"><img src="<?php echo $img->guid; ?>" alt="<?php echo $img->post_title; ?>"></a>
<?php
} ?>
		</div>
	</div>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<?php
the_field('texto_inferior_productos', 2);