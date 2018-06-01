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

	<figure class="post-thumbnail hidden">
		<?php $size = digezine_post_thumbnail_size(); ?>

		<?php $utility->media->get_image( array(
			'size'        => $size['size'],
			'html'        => '<img class="post-thumbnail__img wp-post-image" src="%3$s" alt="%4$s">',
			'placeholder' => false,
			'echo'        => true,
			) );
		?>
	</figure><!-- .post-thumbnail -->

	<div class="entry-content">
		<div class="clearfix">
		<?php the_content(); ?>
		</div>
		<?php
		if (has_category( 'sluhi', get_the_ID()) && get_post_meta( get_the_ID(), 'wpcf-poll_id', true )) {
			echo "<div class='entry-loop-poll'>";
			echo do_shortcode('[totalpoll id="' . get_post_meta( get_the_ID(), 'wpcf-poll_id', true ) . '"]');
			echo "</div>";
		}
		?>
		<?php
			if (function_exists('ruvod_expert_comments')) {
				ruvod_expert_comments($post);
			}
		?>
		<?php 
			if ( shortcode_exists( 'mistape' ) ) { 
				echo do_shortcode('[mistape]'); 
			} 
			?> 
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
		<div class="to-telegramm">
			<p>
				Больше интересных новостей в нашем <a href="https://t.me/ruvod" target="_blank">Telegram-канале</a>. Подписывайся!
			</p>
		</div>
		<?php digezine_share_buttons( 'single' ); ?>
	</footer><!-- .entry-footer -->

</article><!-- #post-## -->
