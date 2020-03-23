<?php
/**
 * @package toallas-personalizadas
 * @subpackage toallas-personalizadas
 * @since Toallas Personalizadas 2.0
 */

$category = get_queried_object(); 
get_header(); ?>

<div class="container-fluid">
	<div class="row">
		<div class="gray-shadow-bg">
			<div class="container section-page">
				<h1 class="text-center text-uppercase"><?php echo get_cat_name($category->term_id); ?></h1>
				<div class="text-center center-block" style="max-width: 800px;">
					<?php echo category_description($category->term_id); ?>
				</div>
				<div class="pinterestLike">
<?php
if(have_posts()):
	while(have_posts()): the_post(); ?>
					<a href="<?php the_permalink(); ?>" class="col-xs-12 col-sm-6 col-md-4">
<?php
		if(has_post_thumbnail())
			echo '						<img src="'.wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())).'" alt="'.get_the_title().'">
'; ?>
						<h4 class="text-uppercase"><?php the_title(); ?></h4>
						<?php the_excerpt(); ?>
					</a>
<?php
	endwhile; ?>
<?php
endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<?php get_footer(); ?>