<?php
/**
 * Menus configuration.
 *
 * @package Digezine
 */

add_action( 'after_setup_theme', 'digezine_register_menus', 5 );
/**
 * Register menus.
 */
function digezine_register_menus() {

	register_nav_menus( array(
		'top'          => esc_html__( 'Top', 'digezine' ),
		'main'         => esc_html__( 'Main', 'digezine' ),
		'footer'       => esc_html__( 'Footer', 'digezine' ),
		'social'       => esc_html__( 'Social', 'digezine' ),
	) );
}
