<?php
/**
 * The template for displaying the style-2 footer layout.
 *
 * @package Digezine
 */

?>
<div class="footer-container <?php echo digezine_get_invert_class_customize_option( 'footer_bg' ); ?>">
	<div class="site-info container">
		<?php
			digezine_footer_logo();
			digezine_footer_menu();
			digezine_contact_block( 'footer' );
			digezine_social_list( 'footer' );
			digezine_footer_copyright();
		?>
	</div><!-- .site-info -->
</div><!-- .container -->
