<?php
/**
 * The template for displaying the default footer layout.
 *
 * @package Digezine
 */

$footer_contact_block_visibility = get_theme_mod( 'footer_contact_block_visibility', digezine_theme()->customizer->get_default( 'footer_contact_block_visibility' ) );
?>

<div class="footer-container <?php echo digezine_get_invert_class_customize_option( 'footer_bg' ); ?>">
	<div class="site-info container">
		<div class="site-info-wrap">
			<?php digezine_footer_logo(); ?>
			<?php digezine_footer_menu(); ?>

			<?php if ( $footer_contact_block_visibility ) : ?>
			<div class="site-info__bottom">
			<?php endif; ?>
				<?php digezine_footer_copyright(); ?>
				<?php digezine_contact_block( 'footer' ); ?>
			<?php if ( $footer_contact_block_visibility ) : ?>
			</div>
			<?php endif; ?>
			<div class="footer-flex-social">
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
				<?php digezine_social_list( 'footer' ); ?>
			</div>
		</div>

	</div><!-- .site-info -->
</div><!-- .container -->
