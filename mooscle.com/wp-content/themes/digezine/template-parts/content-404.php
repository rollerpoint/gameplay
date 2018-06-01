<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Digezine
 */
?>
<section class="error-404 not-found">
	<header class="page-header">
		<h1 class="page-title screen-reader-text"><?php esc_html_e( '404', 'digezine' ); ?></h1>
	</header><!-- .page-header -->

	<div class="page-content invert">
		<h2><?php esc_html_e( 'Page Not Found', 'digezine' ); ?></h2>
		<p><?php esc_html_e( 'Unfortunately the page you were looking for could not be found. Maybe search can help.', 'digezine' ); ?></p>
		<p><a class="btn" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Go Back', 'digezine' ); ?></a></p>
	</div><!-- .page-content -->
</section><!-- .error-404 -->
