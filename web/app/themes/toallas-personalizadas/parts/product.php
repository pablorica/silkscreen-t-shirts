<?php
$current_query = get_queried_object();
$is_personalizados = false;
$terms = wp_get_post_terms($current_query->ID, 'product_cat');
foreach($terms as $term){
	if(term_is_ancestor_of(17, $term->term_id, 'product_cat')) $is_personalizados = true;
}
if(have_posts()):
	while(have_posts()): the_post(); ?>
<div class="white-shadow-bg">
	<div class="container">
		<h1 class="text-center text-uppercase"><?php the_title(); ?></h1>
		<div class="text-center center-block">
			<?php the_content(); ?>
		</div>
		<p>&nbsp;</p>
<?php
		if(!$is_personalizados){ ?>
		<p class="text-center"><a href="/contacto/" class="btn-red">Solicitar presupuesto</a></p>
<?php
		}
		if(get_field('tipo_de_pagina') == 2)	get_template_part('parts/product', 'tipo2');
		else									get_template_part('parts/product', 'tipo1'); ?>
	</div>
</div>
<?php
		$metodos = get_field('metodos_de_impresion');
		if(is_array($metodos) && !empty($metodos)){ ?>
<h1 class="text-center text-uppercase">Métodos de impresión</h1>
<div class="metodos">
<?php
			switch(count($metodos)){
				//case 4:
				//	$cols = ' col-md-3';
				//	break;
				//case 3:
				//	$cols = ' col-md-4';
				//	break;
				//case 2:
				//	$cols = ' col-sm-6';
				//	break;
				default:
					$cols = ' col-md-4';
					break;
			}
			foreach($metodos as $key => $value){ ?>
	<a class="col-xs-12<?php echo $cols; ?>">
		<img src="<?php echo wp_get_attachment_url(get_post_thumbnail_id($value->ID)); ?>" alt="">
		<div class="content">
			<div>
				<h4><b><?php echo $value->post_title; ?></b></h4>
				<?php echo do_shortcode($value->post_content); ?>
			</div>
		</div>
	</a>
<?php
			} ?>
</div>
<?php
		}
	endwhile;
endif;
if(get_field('pinterest', 2) != ''){ ?>
<a href="<?php echo get_field('pinterest', 2); ?>" target="_blank" class="red-box">
	<div class="container">
		<h3 class="text-center">Para ver más ejemplos entra en nuestro <i class="fa fa-pinterest"></i></h3>
	</div>
</a>
<?php
} ?>
<a href="/contacto/" class="white-box">
	<div class="container">
		<h3 class="text-center">Solicita presupuesto o pide más información</h3>
	</div>
</a>
