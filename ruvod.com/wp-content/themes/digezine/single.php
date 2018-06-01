<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Digezine
 */
while ( have_posts() ) : the_post();

	get_template_part( digezine_get_single_post_template_part_slug(), get_post_format() );

	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) :
		comments_template();
	endif;
	
	get_template_part( 'template-parts/content', 'post-navigation' );

	digezine_related_posts();

	digezine_post_author_bio();

	

endwhile; // End of the loop.
