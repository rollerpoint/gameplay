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
		ЭКСПЕРТЫ
		<a href="https://goo.gl/forms/mBXFzpZDdsy9jf1z1" target="_blank" class="btn btn-xs btn-secondary to-expert-btn">СТАТЬ ЭКСПЕРТОМ</a>
	</h6>
	<div <?php digezine_posts_list_class(); ?>>

	<?php
	/* Start the Loop */
	while ( have_posts() ) : the_post();

		get_template_part( 'template-parts/expert/archive' );

	endwhile; ?>

	</div><!-- .posts-list -->

	<?php get_template_part( 'template-parts/content', 'pagination' );

else :

	get_template_part( 'template-parts/content', 'none' );

endif; ?>
