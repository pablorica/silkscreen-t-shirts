<?php
global $product;
$current_query = get_queried_object();
$is_personalizados = false;
$terms = wp_get_post_terms($current_query->ID, 'product_cat');
foreach($terms as $term){
	if(term_is_ancestor_of(17, $term->term_id, 'product_cat')) $is_personalizados = true;
} ?>
<div class="text-center">
	<div class="filtros">
		<a href="#" e="todos" class="current">Todos</a>
<?php
$filtros = array();
$nombres = array();
$imagenes = array();
$attachment_ids = $product->get_gallery_attachment_ids();
foreach($attachment_ids as $attachment_id){
	$img = get_post($attachment_id);
	$cats = '';
	$categories = explode(',', $img->post_content);
	foreach($categories as $key => $value){
		if(!in_array('c_'.sanitize_title($value), $filtros)){
			$filtros[] = 'c_'.sanitize_title($value);
			$nombres[] = $value;
		}
		$cats .= ' c_'.sanitize_title($value);
	}
	$imagenes[] = '	<a href="'.$img->guid.'" rel="prettyPhoto[gallery]" title="'.$img->post_title.'" class="col-xs-12 col-sm-6 col-md-4 col-lg-3 todos'.$cats.'">
		<img src="'.$img->guid.'" alt="'.$img->post_title.'">
	</a>
';
}
foreach($filtros as $key => $value){ ?>
		<a href="#" e="<?php echo $filtros[$key]; ?>"><?php echo $nombres[$key]; ?></a>
<?php
} ?>
	</div>
</div>
<div class="woo-list2">
<?php
foreach($imagenes as $img) echo $img; ?>
</div>
