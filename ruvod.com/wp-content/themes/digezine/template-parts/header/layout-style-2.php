<?php
/**
 * Template part for style-2 header layout.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Digezine
 */

$search       = get_theme_mod( 'header_search', digezine_theme()->customizer->get_default( 'header_search' ) );
?>
<div class="header-container_wrap container">
	<div class="header-container__top">
		<div class="header-container__flex">
			<div class="site-branding">
				<?php digezine_header_logo() ?>
				<?php digezine_site_description(); ?>
			</div>
			<div class="header-elements-wrap">
				<?php digezine_contact_block( 'header' ); ?>
				<?php digezine_header_btn(); ?>
			</div>
		</div>
	</div>

	<div class="header-container__bottom">
		<div class="header-container__flex">
			<?php digezine_main_menu(); ?>

			<?php if ( $search ) : ?>
			<div class="header-icons divider">
				<?php digezine_header_search( '<div class="header-search"><span class="search-form__toggle"></span>%s<span class="search-form__close"></span></div>' ); ?>
			</div>
			<?php endif; ?>

		</div>
	</div>
</div>
