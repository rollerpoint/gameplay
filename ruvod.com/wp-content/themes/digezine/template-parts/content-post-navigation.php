<?php
/**
 * Template part for single post navigation.
 *
 * @package Digezine
 */

if ( ! get_theme_mod( 'single_post_navigation', digezine_theme()->customizer->get_default( 'single_post_navigation' ) ) ) {
	return;
}

the_post_navigation();
