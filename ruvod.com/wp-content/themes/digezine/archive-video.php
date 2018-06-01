<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Digezine
 */

if ( have_posts() ) : ?>
	<header class="page-header">
		<?php
			the_archive_title( '<h1 class="page-title screen-reader-text">', '</h1>' );
			the_archive_description( '<div class="taxonomy-description">', '</div>' );
		?>
	</header><!-- .page-header -->
	<h6 class="page-title">
	    ВИДЕО
	    <?php echo do_shortcode( '[cherry_search_form search_placeholder_text="Поиск" search_source="video" limit_query="5" results_order_by="date" results_order="asc" title_visible="true" limit_content_word="50" author_visible="false" author_prefix="Сообщение от:" thumbnail_visible="false" enable_scroll="true" result_area_height="500" more_button="Показать ещё"]'); ?>
	</h6>
	<div <?php digezine_posts_list_class(); ?>>

	<?php
	/* Start the Loop */
	while ( have_posts() ) : the_post();
		/*
		 * Include the Post-Format-specific template for the content.
		 * If you want to override this in a child theme, then include a file
		 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
		 */
		get_template_part( digezine_get_post_template_part_slug(), get_post_format() );

	endwhile; ?>

	</div><!-- .posts-list -->

	<?php get_template_part( 'template-parts/content', 'pagination' );

else :

	get_template_part( 'template-parts/content', 'none' );

endif; ?>
