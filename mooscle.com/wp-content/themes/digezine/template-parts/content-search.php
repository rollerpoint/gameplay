<?php
/**
 * The template part for displaying results in search pages.
 *
 * @package Digezine
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'posts-list__item card' ); ?>>

	<?php $utility = digezine_utility()->utility; ?>

	<div class="post-list__item-content">

		<header class="entry-header">
			<?php $title_html = ( is_single() ) ? '<h3 %1$s>%4$s</h3>' : '<h5 %1$s><a href="%2$s" rel="bookmark">%4$s</a></h5>';

			$utility->attributes->get_title( array(
				'class' => 'entry-title',
				'html'  => $title_html,
				'echo'  => true,
			) );
			?>
		</header><!-- .entry-header -->

		<div class="entry-content">
			<?php the_excerpt(); ?>
		</div><!-- .entry-content -->
	<?php if (get_post_format() != 'video') { ?>
      <div class="row">
        <div class="col-md-6">
          <?php get_template_part( 'template-parts/content-entry-meta-loop-without-cats' ) ?>
        </div>
        <div class="col-md-6">
          <footer class="entry-footer">
            <?php $btn_text = get_theme_mod( 'blog_read_more_text', digezine_theme()->customizer->get_default( 'blog_read_more_text' ) );
            $btn_text       = $btn_text ? $btn_text : esc_html__( 'Read more', 'digezine' );

            $utility->attributes->get_button( array(
                'class' => 'btn btn-primary',
                'text'  => $btn_text,
                'html'  => '<a href="%1$s" %3$s><span class="btn__text">%4$s</span>%5$s</a>',
                'echo'  => true,
              ) );
            ?>
          </footer>
        </div>
      </div>
      <?php } else { ?>
      <?php get_template_part( 'template-parts/content-entry-meta-loop-without-cats' ) ?>
      <?php } ?>


      <!-- .entry-footer -->
    </div><!-- .post-list__item-content -->
</article><!-- #post-## -->
