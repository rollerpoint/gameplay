<?php get_header( digezine_template_base() ); ?>

	<?php digezine_site_breadcrumbs(); ?>

	<?php do_action( 'digezine_render_widget_area', 'full-width-header-area' ); ?>

	<?php digezine_single_modern_header(); ?>

	<div <?php digezine_content_wrap_class(); ?>>

		<?php do_action( 'digezine_render_widget_area', 'before-content-area' ); ?>

		<div class="row">

			<div id="primary" <?php digezine_primary_content_class(); ?>>
				<?php do_action( 'digezine_render_widget_area', 'before-loop-area' ); ?>

				<main id="main" class="site-main" role="main">

					<?php include digezine_template_path(); ?>

				</main><!-- #main -->

				<?php do_action( 'digezine_render_widget_area', 'after-loop-area' ); ?>

			</div><!-- #primary -->
			<?php $name = apply_filters( 'ruvod_get_sidebar_name', $name ); ?>
			<?php get_sidebar(); // Loads the sidebar.php. ?>

		</div><!-- .row -->

		<?php do_action( 'digezine_render_widget_area', 'after-content-area' ); ?>

	</div><!-- .container -->

	<?php do_action( 'digezine_render_widget_area', 'after-content-full-width-area' ); ?>

<?php get_footer( digezine_template_base() ); ?>
