<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Digezine
 */
?>
<article id="expert-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php $utility = digezine_utility()->utility; ?>

	<header class="entry-header clearfix">

        <figure class="post-thumbnail">
            <?php 
            $size = digezine_post_thumbnail_size();
            ?>
            
            <?php $utility->media->get_image( array(
                'size'        => $size['size'],
                'mobile_size' => $size['size'],
                'html'        => '<img class="post-thumbnail__img wp-post-image" src="%3$s" alt="%4$s">',
                'placeholder' => false,
                'echo'        => true,
                ) );
            ?>
        </figure>
		<div class="expert-content desktop-limin-height desktop-closed">
            <div class="desktop-open-wrap">
                <a href="#" class="btn btn-secondary desktop-open"><i class="icon-arrow-down"></i></a>
            </div>
            <?php $utility->attributes->get_title( array(
                    'class' => 'entry-title',
                    'html'  => '<h4 %1$s>%4$s</h4>',
                    'echo'  => true,
                ) );
            ?>
            <?php
                echo '<div class="expert-tags-holder">'.get_the_tag_list('<div class="expert-tags"><div class="expert-tags-title">Экспертиза:</div>',', ','</div>').'</div>';
                echo '<p class="expert-desc">'. get_post_meta(get_the_ID(),'wpcf-description',true);
                echo '</p>';
            ?> 
        </div>

	</header><!-- .entry-header -->

    <?php
        global $wp_query;
        $q = array(
            'post_type' => 'expert_comment',
            'posts_per_page' => 10,
            'paged' => $_GET['cpaged'] ? intval($_GET['cpaged']) : 1,//2,//$wp_query->query_vars['cpaged'],
            'meta_query' => array(
                array(
                    'key' => '_wpcf_belongs_expert_id', 
                    'value' => get_the_ID()
                ),
                'relation' => 'AND'
            )
        );
        query_posts( $q );
        global $wp_query; 
        if ( have_posts() ) {
            echo '<h4 class="entry-title">Комментарии ('.$wp_query->found_posts.')</h4>';
            echo '<div class="expert-comments-posts">';
            while ( have_posts() ) : the_post();
                $comment = get_post();
                $expert_id = get_post_meta($comment->ID,'_wpcf_belongs_expert_id',true);
                $belong_post_id = get_post_meta($comment->ID,'_wpcf_belongs_post_id',true);
                $belong_post = get_post($belong_post_id);
                ?>
                    <div class="expert-comments-item">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="title">
                                <h4>
                                    <a href="<?php echo get_permalink($belong_post) ?>">
                                        <?php echo $belong_post->post_title ?>
                                    </a>
                                </h4>
                                </div>
                                <div class="content">
                                    <?php echo $comment->post_content ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php
            endwhile;
            echo '</div>';
            the_posts_pagination( apply_filters( 'digezine_content_posts_pagination',
                array(
                        'base'    => add_query_arg( 'cpaged', '%#%' ),
                        'prev_text' => ( ! is_rtl() ) ? '<i class="linearicon linearicon-arrow-left"></i>' : '<i class="linearicon linearicon-arrow-right"></i>',
                        'next_text' => ( ! is_rtl() ) ? '<i class="linearicon linearicon-arrow-right"></i>' : '<i class="linearicon linearicon-arrow-left"></i>',
                )
            ));
        }
        wp_reset_query(); 
        
    ?>

	<?php digezine_ads_post_before_content() ?>

	<!-- .post-thumbnail -->

	<div class="entry-content">
		<?php the_content(); ?>
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
