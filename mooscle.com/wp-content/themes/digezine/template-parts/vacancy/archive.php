
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

$data = get_post_meta(get_the_ID());
foreach ( $data  as $key => $value ) {
    if ((is_string($value[0]) || !$value[0] || $value[0] == '')) {
    $data[$key] = $value[0];
    }
}
$company_id = get_post_meta( get_the_ID(), 'company_id', true );
      
?>
<article id="vacancy-<?php the_ID(); ?>" <?php post_class( 'posts-list__item card' ); ?>>

	<?php $utility = digezine_utility()->utility; ?>
    <figure class="post-thumbnail">
        <?php $utility->media->get_image( array(
            'size'        => 'digezine-thumb-l',
            'mobile_size' => 'digezine-thumb-l',
            'html'        => '<a href="%1$s" %2$s><img class="post-thumbnail__img wp-post-image" src="%3$s" alt="%4$s" %5$s></a>',
            'placeholder' => false,
            'echo'        => true,
            ), 'post', get_post_meta(get_the_ID(),'company_id',true));
        ?>
    </figure>

    <div class="post-list__item-content single-vacancy">
        <div class="content-wrapper">
            <div class="entry-content elipsize-wrapper">
                <div class="main-info row">
                    <div class="col-xs-12 col-sm-12">
                        <h3>
                            <a href="<?php echo get_permalink() ?>">
                                <?php echo get_the_title($company_id); ?>: 
                                <span class="uppercase">
                                <?php echo get_the_title() ?>
                                </span>
                            </a>
                        </h3>
                    </div>
                    <div class="col-labels">
                        Компетенции:
                    </div>
                    <div class="col-data">
                        <?php if ($data['skills']) { ?>
                            <div class="skills-list">
                                <?php 
                                    $skills = get_post_meta(get_the_ID(),'skills',true);
                                    echo implode(' ', array_map(function($skill) {
                                    return "<span class='skill'>".$skill."</span>";
                                    }, explode(',', $skills)))  
                                ?>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="col-labels">
                        <?php _e('Employment', RUVOD_TEXT_DOMAIN); ?>:
                    </div>
                    <div class="col-data">
                        <?php 
                        $employment = get_post_meta(get_the_ID(),'employment',true);
                        $employments = array(
                            __('Full employment', RUVOD_TEXT_DOMAIN),
                            __('Part-time employment', RUVOD_TEXT_DOMAIN)
                        );
                        echo $employments[$employment];
                        ?>
                    </div>
                    <div class="col-labels">
                        Город:
                    </div>
                    <div class="col-data">
                        <?php echo $data['city']; ?>
                        <?php if ($data['accept_remote']) { ?>
                            <span class="text-muted">
                            (Возможна удаленная работа)
                            </span>
                        <?php } ?>
                    </div>
                    <div class="col-labels">
                        <?php _e('Salary', RUVOD_TEXT_DOMAIN); ?>:
                    </div>
                    <div class="col-data">
                        <strong>
                            <?php if (get_post_meta(get_the_ID(),'salary_by_contract',true)) { ?>
                            <?php _e('Based on the results of the interview', RUVOD_TEXT_DOMAIN); ?>
                            <?php } else { ?>
                            <?php echo __('From', RUVOD_TEXT_DOMAIN).' '.get_post_meta(get_the_ID(),'salary_from',true).' ' ?> 
                            <?php if (get_post_meta(get_the_ID(),'salary_to',true) && get_post_meta(get_the_ID(),'salary_from',true) != '')
                            echo __('To', RUVOD_TEXT_DOMAIN).' '.get_post_meta(get_the_ID(),'salary_to',true);
                            echo ' р.';
                            } ?>
                        </strong>
                    </div>
                    <div class="col-labels">
                        Опубликована:
                    </div>
                    <div class="col-data">
                        <?php echo get_the_date(); ?>
                    </div>
                </div>
                

            </div><!-- .entry-content -->
            <?php get_template_part( 'template-parts/content-entry-meta-loop-without-cats' ) ?>
            <footer class="entry-footer">

                <?php $btn_text = __('More','digezine');


                $utility->attributes->get_button( array(
                    'visible' => true,
                    'class'   => 'link',
                    'text'    => $btn_text,
                    'icon'    => '<i class="linearicon linearicon-arrow-right"></i>',
                    'html'    => '<a href="%1$s" %3$s><span class="link__text">%4$s</span>%5$s</a>',
                    'echo'    => true,
                ) );
                
                
                ?>
                <?php digezine_share_buttons( 'loop', array(), array('without_like' => true) ); ?>
            </footer><!-- .entry-footer -->
        </div><!-- .post-list__item-content -->
    </div>
</article><!-- #post-## -->

