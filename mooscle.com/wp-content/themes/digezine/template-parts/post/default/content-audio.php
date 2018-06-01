<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Digezine
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'posts-list__item card' ); ?>>

	<?php $utility       = digezine_utility()->utility;
	$blog_featured_image = get_theme_mod( 'blog_featured_image', digezine_theme()->customizer->get_default( 'blog_featured_image' ) );
	?>

	<?php if ( 'small' === $blog_featured_image ) : ?>
		<div class="post-featured-content">
			<?php do_action( 'cherry_post_format_audio' ); ?>
		</div><!-- .post-featured-content -->
	<?php endif; ?>

	<div class="post-list__item-content">
		<?php if ( 'small' === $blog_featured_image ) :
			get_template_part( 'template-parts/content-entry-meta-loop' );
		endif; ?>

		<header class="entry-header">
			<?php digezine_sticky_label(); ?>

			<?php $title_html = ( is_single() ) ? '<h3 %1$s>%4$s</h3>' : '<h4 %1$s><a href="%2$s" rel="bookmark">%4$s</a></h4>';

			$utility->attributes->get_title( array(
				'class' => 'entry-title',
				'html'  => $title_html,
				'echo'  => true,
			) );
			?>
		</header><!-- .entry-header -->

		<?php if ( 'small' !== $blog_featured_image ) : ?>
			<div class="post-featured-content">
				<?php do_action( 'cherry_post_format_audio' ); ?>
			</div><!-- .post-featured-content -->
		<?php endif; ?>

		<div class="entry-content">
			<?php $embed_args = array(
				'fields' => array( 'soundcloud' ),
				'height' => 310,
				'width'  => 310,
			);

			$embed_content = apply_filters( 'cherry_get_embed_post_formats', false, $embed_args );

			if ( false === $embed_content ) {

				$blog_content = get_theme_mod( 'blog_posts_content', digezine_theme()->customizer->get_default( 'blog_posts_content' ) );
				$length             = ( 'full' === $blog_content ) ? -1 : 55;
				$content_visible    = ( 'none' !== $blog_content ) ? true : false;
				$content_type       = ( 'full' !== $blog_content ) ? 'post_excerpt' : 'post_content';

				$utility->attributes->get_content( array(
					'visible'      => $content_visible,
					'length'       => $length,
					'content_type' => $content_type,
					'echo'         => true,
				) );

			} else {
				printf( '<div class="embed-wrapper">%s</div>', $embed_content );
			}
			?>
		</div><!-- .entry-content -->

		<footer class="entry-footer">
			<?php if ( 'small' !== $blog_featured_image ) :
				get_template_part( 'template-parts/content-entry-meta-loop' );
			endif; ?>

			<?php digezine_share_buttons( 'loop' ); ?>

			<?php $btn_text = get_theme_mod( 'blog_read_more_text', digezine_theme()->customizer->get_default( 'blog_read_more_text' ) );
			$btn_visible    = $btn_text ? true : false;

			$utility->attributes->get_button( array(
				'visible' => $btn_visible,
				'class'   => 'link',
				'text'    => $btn_text,
				'icon'    => '<i class="linearicon linearicon-arrow-right"></i>',
				'html'    => '<a href="%1$s" %3$s><span class="link__text">%4$s</span>%5$s</a>',
				'echo'    => true,
			) );
			?>
		</footer><!-- .entry-footer -->
	</div><!-- .post-list__item-content -->

</article><!-- #post-## -->
