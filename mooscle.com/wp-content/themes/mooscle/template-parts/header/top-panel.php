<?php
/**
 * Template part for top panel in header (default layout).
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Digezine
 */

// Don't show top panel if all elements are disabled.
if ( ! digezine_is_top_panel_visible() ) {
	return;
}
$message                  = get_theme_mod( 'top_panel_text', digezine_theme()->customizer->get_default( 'top_panel_text' ) );
$contact_block_visibility = get_theme_mod( 'header_contact_block_visibility', digezine_theme()->customizer->get_default( 'header_contact_block_visibility' ) );
?>

<div class="top-panel <?php echo digezine_get_invert_class_customize_option( 'top_panel_bg' ); ?>">
	<div class="top-panel__container container">
		<div class="top-panel__top">
			<div class="top-panel__left">
				<div class="site-branding">
					<?php digezine_header_logo() ?>
					<?php digezine_site_description(); ?>
				</div>
			</div>
			<div class="top-panel__right">
				
				<div class="ruvod-buttons">
					<?php
						if (function_exists('ruvod_rumor_button')) {
							echo ruvod_rumor_button();
						}
					?>
					<?php
						if (function_exists('ruvod_subscribe_button')) {
							echo ruvod_subscribe_button();
						}
					?>
					<?php
						if (function_exists('ruvod_donate_button')) {
							echo ruvod_donate_button();
						}
					?>
				</div>
				<?php digezine_top_menu(); ?>

				<?php digezine_header_search( '<div class="header-search"><span class="search-form__toggle"></span>%s<span class="search-form__close"></span></div>' ); ?>
			</div>
		</div>

		<?php if ( $contact_block_visibility && ! empty( $message ) ) : ?>
		<div class="top-panel__bottom">
			<?php digezine_contact_block( 'header' ); ?>
		</div>
		<?php endif; ?>
	</div>
</div><!-- .top-panel -->

<?php
	if (function_exists('ruvod_donate_modal')) {
		echo ruvod_donate_modal();
	}
	if (function_exists('ruvod_rumor_modal')) {
		echo ruvod_rumor_modal();
	}
	if (function_exists('ruvod_subscribe_modal')) {
		echo ruvod_subscribe_modal();
	}
?>
