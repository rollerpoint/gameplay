<?php
/**
 * Thumbnails configuration.
 *
 * @package Digezine
 */

add_action( 'after_setup_theme', 'digezine_register_image_sizes', 5 );
/**
 * Register image sizes.
 */
function digezine_register_image_sizes() {
	set_post_thumbnail_size( 418, 315, true );

	// Registers a new image sizes.
	add_image_size( 'digezine-thumb-s', 150, 150, true );
	add_image_size( 'digezine-slider-thumb', 158, 88, true );
	add_image_size( 'digezine-thumb-m', 400, 400, true );
	add_image_size( 'digezine-thumb-m-2', 650, 490, true );
	add_image_size( 'digezine-thumb-masonry', 418, 9999 );
	add_image_size( 'digezine-thumb-l', 886, 668, true );
	add_image_size( 'digezine-thumb-l-2', 886, 315, true );
	add_image_size( 'digezine-thumb-xl', 1920, 1080, true );
	add_image_size( 'digezine-thumb-xl-2', 1920, 820, true );
	add_image_size( 'digezine-author-avatar', 512, 512, true );

	add_image_size( 'digezine-thumb-301-226', 301, 226, true );
	add_image_size( 'digezine-thumb-480-362', 480, 362, true );
	add_image_size( 'digezine-thumb-1355-1020', 1355, 1020, true );
}
