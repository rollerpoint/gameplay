<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Digezine
 */
while ( have_posts() ) : the_post();


	get_template_part( 'template-parts/company/single');

	get_template_part( 'template-parts/content', 'post-navigation' );


endwhile; // End of the loop.
