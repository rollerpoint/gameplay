<?php
/**
 * Template part for style-3 header layout.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Digezine
 */

$vertical_menu_slide = ( ! is_rtl() ) ? 'left' : 'right';
?>
<div class="header-container_wrap container">
	<?php digezine_vertical_main_menu( $vertical_menu_slide ); ?>
	<div class="header-container__flex">
		<div class="site-branding">
			<?php digezine_header_logo() ?>
			<?php digezine_site_description(); ?>
		</div>

		<div class="header-icons">
			<?php digezine_header_search( '<div class="header-search"><span class="search-form__toggle"></span>%s<span class="search-form__close"></span></div>' ); ?>
			<?php digezine_vertical_menu_toggle( 'main-menu' ); ?>
			<?php digezine_header_btn(); ?>
		</div>

	</div>
</div>
