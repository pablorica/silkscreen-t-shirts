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
$current_query = get_queried_object();
if(isset($current_query->term_id)){
	$term = get_term_by('id', $current_query->term_id, 'product_cat');
	$is_personalizados = term_is_ancestor_of(17, $current_query->term_id, 'product_cat');
}else{
	$is_personalizados = false;
	$terms = wp_get_post_terms($current_query->ID, 'product_cat');
	foreach($terms as $term){
		if(term_is_ancestor_of(17, $term->term_id, 'product_cat')) $is_personalizados = true;
	}
}
if(is_product_category('productos'))	get_template_part('parts/products');
else if(is_product_category())			get_template_part('parts/product', 'category');
else									get_template_part('parts/product', 'tipo3'); ?>
	</div>
</div>

<?php get_footer(); ?>