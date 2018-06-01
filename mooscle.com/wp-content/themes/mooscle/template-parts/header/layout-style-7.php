<?php
/**
 * Template part for style-7 header layout.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Digezine
 */

$vertical_menu_slide = ( ! is_rtl() ) ? 'right' : 'left';
?>
<div class="header-container_wrap container">
	<?php digezine_vertical_main_menu( $vertical_menu_slide ); ?>
	<div class="row row-md-center">
		<div class="col-xs-12 col-md-4 col-lg-3">
			<?php digezine_vertical_menu_toggle( 'main-menu' ); ?>
		</div>
		<div class="col-xs-12 col-md-4 col-lg-6">
			<div class="site-branding">
				<?php digezine_header_logo() ?>
				<?php digezine_site_description(); ?>
			</div>
		</div>
		<div class="col-xs-12 col-md-4 col-lg-3">
			<div class="header-btn-wrap">
				<?php digezine_header_btn(); ?>
			</div>
		</div>
	</div>
</div>
