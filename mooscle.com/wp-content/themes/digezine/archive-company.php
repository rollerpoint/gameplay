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
	    КОМПАНИИ
	</h6>

	<div <?php digezine_posts_list_class(); ?>>

	<?php
	/* Start the Loop */
	while ( have_posts() ) : the_post();

		/*
		 * Include the Post-Format-specific template for the content.
		 * If you want to override this in a child theme, then include a file
		 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
		 */
		get_template_part( 'template-parts/company/archive' );

	endwhile; ?>

	</div><!-- .posts-list -->

	<?php get_template_part( 'template-parts/content', 'pagination' );

else :

	get_template_part( 'template-parts/content', 'none' );

endif; ?>
