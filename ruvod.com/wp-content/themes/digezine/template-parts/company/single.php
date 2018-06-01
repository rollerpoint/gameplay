<?php
/**
 * Template part for displaying posts.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Digezine
 */
?>
<?php
$company_id = get_the_ID();
?>
<div class="row">
    <?php if (get_post_meta(get_the_ID(),'wpcf-top_image',true)) { ?>
        
        <div class="col-xs-12 col-md-12 hidden-sm-down">
                <?php 
                if (get_post_meta(get_the_ID(),'wpcf-banner-url',true)) {
                    echo "<a href='".get_post_meta(get_the_ID(),'wpcf-banner-url',true)."' class='top_bar_image' target='_blank'>";
                } else {
                    echo '<div class="top_bar_image">';
                }
                ?>
                <?php 
                        echo '<img src="'.get_post_meta(get_the_ID(),'wpcf-top_image',true).'" alt="">';
                ?>
                <?php 
                if (get_post_meta(get_the_ID(),'wpcf-banner-url',true)) {
                    echo "</a>";
                } else {
                    echo '</div>';
                }
                ?>
        </div>
    <?php } ?>
    <div class="col-xs-12 col-lg-8">
        <article id="company-<?php the_ID(); ?>" <?php post_class(); ?>>

        <?php $utility = digezine_utility()->utility; ?>

        <?php
        $show_modes = array('all','blog','ment');
        $mode_names = array(
            'all' =>  __('All', 'digezine'),
            'blog' => __('Blog', 'digezine'),
            'ment' => __('Mentioning', 'digezine')
        );
        $mode = $_GET['mode'] ? $_GET['mode'] : 'all'; 
        if (!in_array ( $mode , $show_modes )) {
            $mode = 'all';
        }
        global $wp_query;
        $q = array(
            'post_type' => 'post',
            'posts_per_page' => 10,
            'paged' => $_GET['cpaged'] ? intval($_GET['cpaged']) : 1,//2,//$wp_query->query_vars['cpaged'],
            'tax_query' => array(
                array(
                    'taxonomy' => 'post_tag',
                    'field'    => 'slug',
                    'terms'    => get_post_meta(get_the_ID(),'wpcf-main_tag_slug',true),
                ),
            ),
        );
        if ($mode != 'all') {
            $q['meta_query'] = array(
                array(
                    'key'     => 'company_blog',
                    'value'   => '1',
                    'compare' => $mode == 'blog' ? '=' : 'NOT EXISTS'
                )
            );
        }
        query_posts( $q );
        ?>
        
        <div class="row">
            <div class="col-sm-12">
                <h6 class="page-title">
                    <?php echo __('Publications', 'digezine').' ('.$wp_query->found_posts.')'; ?>
                    <div class="dropdown company-blog-filter">
                        <button class="btn btn-xs btn-secondary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                            <?php echo $mode_names[$mode] ?>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                            <?php 
                                foreach ($show_modes as $key => $target_mode) {
                                    echo '<li><a href="'.get_permalink().'?mode='.$target_mode.'">'.$mode_names[$target_mode].'</a></li>';
                                }
                            ?>
                        </ul>
                    </div>
                </h6>
            </div>
        </div>
        <?php
            
            if ( have_posts() ) {
                //echo '<h4 class="entry-title">Комментарии эксперта:</h4>';
                ?>

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

                    </div>


                <?php
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

    </div>
    
    <?php
        ruvod_company_sidebar($company_id)
    ?>
</div>