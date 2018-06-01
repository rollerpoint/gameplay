
<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Digezine
 */
?>

<article id="company-<?php the_ID(); ?>" <?php post_class( 'posts-list__item card' ); ?>>

	<?php $utility = digezine_utility()->utility; ?>
    <figure class="post-thumbnail">
        <?php $utility->media->get_image( array(
            'size'        => 'full',
            'mobile_size' => 'full',
            'html'        => '<a href="%1$s" %2$s><img class="post-thumbnail__img wp-post-image" src="%3$s" alt="%4$s" %5$s></a>',
            'placeholder' => false,
            'echo'        => true,
            ) );
        ?>
    </figure>

    <div class="post-list__item-content">
        <div class="content-wrapper">
            <header class="entry-header">
                <?php if ( !has_post_thumbnail() ) :
                    get_template_part( 'template-parts/content-entry-meta-loop-cats' );
                endif; ?>

                <?php digezine_sticky_label(); ?>

                <?php $title_html = ( is_single() ) ? '<h3 %1$s>%4$s</h3>' : '<h4 %1$s><a href="%2$s" rel="bookmark">%4$s</a></h4>';

                $utility->attributes->get_title( array(
                        'class' => 'entry-title',
                        'html'  => $title_html,
                        'echo'  => true,
                ) );
                ?>
            </header><!-- .entry-header -->
            <div class="entry-content elipsize-wrapper">
                
                <?php
                    echo '<div class="expert-tags-holder">'.get_the_tag_list('<div class="expert-tags"><div class="expert-tags-title">'.__('Company Profile','digezine').':</div>',', ','</div>').'</div>';
                    $blog_content = get_theme_mod( 'blog_posts_content', digezine_theme()->customizer->get_default( 'blog_posts_content' ) );
                    $length = ('full' === $blog_content) ? -1 : 26;
                    $content_visible = ('none' !== $blog_content) ? true : false;
                    $content_type = ('full' !== $blog_content) ? 'post_excerpt' : 'post_content';

                    $utility->attributes->get_content( array(
                            'visible'      => $content_visible,
                            'length'       => $length,
                            'content_type' => $content_type,
                            'echo'         => true,
                            'class' => 'elipsize'
                    ) );
                ?> 

            </div><!-- .entry-content -->
            <?php get_template_part( 'template-parts/content-entry-meta-loop-without-cats' ) ?>
            <footer class="entry-footer">

                <?php $btn_text = get_theme_mod( 'blog_read_more_text', digezine_theme()->customizer->get_default( 'blog_read_more_text' ) );


                $utility->attributes->get_button( array(
                    'visible' => true,
                    'class'   => 'link',
                    'text'    => $btn_text,
                    'icon'    => '<i class="linearicon linearicon-arrow-right"></i>',
                    'html'    => '<a href="%1$s" %3$s><span class="link__text">%4$s</span>%5$s</a>',
                    'echo'    => true,
                ) );
                
                
                ?>
                <?php digezine_share_buttons( 'loop' ); ?>
            </footer><!-- .entry-footer -->
        </div><!-- .post-list__item-content -->
    </div>
</article><!-- #post-## -->

