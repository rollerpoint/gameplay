<?php get_header( digezine_template_base() ); ?>

	<?php digezine_site_breadcrumbs(); ?>

	<div <?php digezine_content_wrap_class(); ?>>

		<div class="row">

			<div id="primary" <?php digezine_primary_content_class(); ?>>

				<main id="main" class="site-main" role="main">

					<?php include digezine_template_path(); ?>

				</main><!-- #main -->

			</div><!-- #primary -->

		</div><!-- .row -->

	</div><!-- .container -->

<?php get_footer( digezine_template_base() ); ?>
