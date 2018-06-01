<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Digezine
 */

if ( have_posts() ) : ?>

	<h6 class="page-title">
	    ВАКАНСИИ
	</h6>
	<?php
		$classes = apply_filters('digezine_posts_list_class',array());
		$classes = join( ' ', $classes );
	?>
	<div class="<?php echo $classes; ?> posts-list posts-list--default content-excerpt one-right-sidebar featured-image--small">

	<?php
	/* Start the Loop */
	while ( have_posts() ) : the_post();

		/*
		 * Include the Post-Format-specific template for the content.
		 * If you want to override this in a child theme, then include a file
		 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
		 */
		get_template_part( 'template-parts/vacancy/archive' );

	endwhile; ?>

	</div><!-- .posts-list -->

	<?php get_template_part( 'template-parts/content', 'pagination' );

else :

	//get_template_part( 'template-parts/content', 'none' );
	?>
		<div class="text-center">
			<h6>
				<?php _e('Vacancies not found', 'digezine'); ?>
			</h6>
		</div>
	<?php
endif; ?>
