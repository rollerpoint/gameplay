<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Digezine
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php $utility = digezine_utility()->utility; ?>

	<header class="entry-header">

		<?php $utility->attributes->get_title( array(
				'class' => 'entry-title',
				'html'  => '<h3 %1$s>%4$s</h3>',
				'echo'  => true,
			) );
		?>

	</header><!-- .entry-header -->

	<?php get_template_part( 'template-parts/content-entry-meta-single' ); ?>

	<?php digezine_ads_post_before_content() ?>

	<div class="post-format-wrap">
		<?php $size = digezine_post_thumbnail_size(); ?>

		<?php do_action( 'cherry_post_format_image', array( 'size' => $size['size'] ) ); ?>
	</div><!-- .post-thumbnail -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php wp_link_pages( array(
				'before'      => '<div class="page-links"><span class="page-links__title">' . esc_html__( 'Pages:', 'digezine' ) . '</span>',
				'after'       => '</div>',
				'link_before' => '<span class="page-links__item">',
				'link_after'  => '</span>',
				'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'digezine' ) . ' </span>%',
				'separator'   => '<span class="screen-reader-text">, </span>',
			) );
		?>
	</div><!-- .entry-content -->

	<footer class="entry-footer">
		<?php digezine_share_buttons( 'single' ); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
