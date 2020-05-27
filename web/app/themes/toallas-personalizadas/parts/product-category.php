<?php
$current_query = get_queried_object();
$term = get_term_by('id', $current_query->term_id, 'product_cat');
$is_personalizados = term_is_ancestor_of(17, $current_query->term_id, 'product_cat');
$list_type = in_array($current_query->slug, array('toallas-microfibra', 'toallas-microalgodon', 'mantas-polares')) ? 3 : 1; ?>
<div class="white-shadow-bg">
	<div class="container">
		<h1 class="text-center text-uppercase"><?php echo $term->name; ?></h1>
		<div class="text-center center-block">
			<?php echo $current_query->description; ?>
		</div>
		<p>&nbsp;</p>
		<div class="text-center">
			<div class="filtros">
				<a href="#" e="c_todos" class="current">Todos</a>
				<?php
				$filtros = array();
				$nombres = array();
				$productos = array();
				if (have_posts()) :
					while (have_posts()) : the_post();
						$class = ' c_todos';
						$posttags = get_the_terms(get_the_ID(), 'product_tag');
						if ($posttags) {
							foreach ($posttags as $tag) {
								$class .= ' c_' . $tag->slug;
								if (!in_array('c_' . $tag->slug, $filtros)) {
									$filtros[] = 'c_' . $tag->slug;
									$nombres[] = $tag->name;
								}
							}
						}
						$img = has_post_thumbnail() ? wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())) : '/wp-content/uploads/2018/05/default.jpg';
						$price = get_post_meta(get_the_ID(), '_regular_price', true);
						$productos[] = '
		<a href="' . get_permalink() . ($list_type == 3 ? '?material=' . $current_query->slug : '') . '" class="' . ($list_type == 3 ? 'col-xs-6 col-sm-2 col-sm-x5' : 'col-xs-12 col-sm-6 col-md-4 col-lg-3') . $class . '">
			<img src="' . $img . '" alt="' . get_the_title() . '">' . ($list_type == 1 && $current_query->term_id != 48 ? '
			<h3 class="text-uppercase">' . get_the_title() . '</h3>' : '') . '
		</a>';
					endwhile;
				endif;
				foreach ($filtros as $key => $value) { ?>
					<a href="#" e="<?php echo $filtros[$key]; ?>"><?php echo $nombres[$key]; ?></a>
				<?php
				} ?>
			</div>
		</div>
		<div class="woo-list<?php echo $list_type; ?>">
			<?php
			$subcategories = get_term_children($current_query->term_id, 'product_cat');
			foreach ($subcategories as $key => $value) {
				$child = get_term_by('id', $value, 'product_cat');
				$img = wp_get_attachment_url(get_woocommerce_term_meta($value, 'thumbnail_id', true)); ?>
				<a href="<?php echo get_term_link($value, 'product_cat'); ?>" class="<?php echo $list_type == 3 ? 'col-xs-6 col-sm-2 col-sm-x5' : 'col-xs-12 col-sm-6 col-md-4 col-lg-3'; ?>">
					<img src="<?php echo $img; ?>" alt="<?php echo $child->name; ?>">
					<?php
					if ($list_type == 1) { ?>
						<h3 class="text-uppercase"><?php echo $child->name; ?></h3>
					<?php
					} ?>
				</a>
			<?php
			}
			if ($current_query->term_id == 48 || $list_type == 3) {
				$first_post = $list_type == 3 ? get_post(1238) : get_post(665); ?>
				<a href="<?php echo get_permalink($first_post->ID);
							if ($list_type == 3) echo '?material=' . $current_query->slug; ?>" class="<?php echo $list_type == 3 ? 'col-xs-6 col-sm-2 col-sm-x5' : 'col-xs-12 col-sm-6 col-md-4 col-lg-3'; ?>">
					<?php
					$img = has_post_thumbnail() ? wp_get_attachment_url(get_post_thumbnail_id($first_post->ID)) : '/wp-content/uploads/2018/05/default.jpg'; ?>
					<img src="<?php echo $img; ?>" alt="<?php echo $first_post->post_title; ?>">
					<?php
					if ($list_type == 1 && $current_query->term_id != 48) { ?>
						<h3 class="text-uppercase"><?php echo $first_post->post_title; ?></h3>
					<?php
					} ?>
				</a>
			<?php
			}
			foreach ($productos as $key => $value) {
				echo $value;
			} ?>
		</div>
	</div>
</div>