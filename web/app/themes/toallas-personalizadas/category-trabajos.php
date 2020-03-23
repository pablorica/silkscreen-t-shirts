<?php
/**
 * @package ToallasPersonalizaas
 * @subpackage ToallasPersonalizaas
 * @since Toallas Personalizadas 2.0
 */

get_header(); ?>

<div class="container-fluid">
	<div class="row">
		<div class="white-shadow-bg">
			<div class="container section-page">
				<h1 class="text-center text-uppercase">Últimos trabajos</h1>
				<div class="text-center center-block">
					<?php $category = get_queried_object(); echo category_description($category->term_id); ?>
				</div>
				<div class="text-center">
					<div class="filtros">
						<a href="#" e="c_trabajos" class="current">Todos</a>
<?php
if(have_posts()):
	$filtros = array();
	$nombres = array();
	$trabajos = array();
	while(have_posts()): the_post();
		$cats = '';
		$categories = wp_get_post_categories(get_the_ID());
		foreach($categories as $key => $value){
			$cat = get_category($value);
			if(!in_array('c_'.$cat->slug, $filtros) && $cat->slug != 'trabajos'){
				$filtros[] = 'c_'.$cat->slug;
				$nombres[] = $cat->name;
			}
			$cats .= ' c_'.$cat->slug;
		}
		$img = has_post_thumbnail() ? wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())) : '/wp-content/uploads/2018/05/default.jpg';
		$trabajos[] = '
					<a href="'.$img.'" rel="prettyPhoto[trabajos]" title="'.get_the_title().'" class="col-xs-12 col-sm-2 col-sm-x5'.$cats.'">
						<img src="'.$img.'" alt="'.get_the_title().'">
					</a>';
	endwhile;
	foreach($filtros as $key => $value){ ?>
						<a href="#" e="<?php echo $filtros[$key]; ?>"><?php echo $nombres[$key]; ?></a>
<?php
	} ?>
					</div>
				</div>
				<div class="trabajos">
<?php
	foreach($trabajos as $key => $value) echo $value; ?>
				</div>
<?php
endif; ?>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>