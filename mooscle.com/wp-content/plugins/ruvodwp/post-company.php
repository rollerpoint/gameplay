<?php
    $company_id = get_post_meta(get_the_ID(),'company_id',true);
    $company = get_post($company_id);
    wp_reset_postdata();

    global $post;
?>
<div class="row">
    <?php if (get_post_meta($company_id,'wpcf-top_image',true)) { ?>
        <div class="col-xs-12 col-md-12 hidden-sm-down">
            <div class="top_bar_image">
                <?php 
                        echo '<img src="'.get_post_meta($company_id,'wpcf-top_image',true).'" alt="">';
                ?>
            </div>
        </div>
    <?php } ?>
    <div class="col-xs-12 col-lg-8">
        <?php 
        get_template_part( digezine_get_single_post_template_part_slug(), get_post_format() ); 
        if ( comments_open() || get_comments_number() ) :
            comments_template();
        endif;
        
        get_template_part( 'template-parts/content', 'post-navigation' );
    
        digezine_related_posts();
    
        digezine_post_author_bio();
    
        ?>
                
    </div>
    <div class="col-xs-12 col-lg-4 sidebar widget-area">
        <aside class="widget custom-posts">
            <div class="company-card">
                <div class="post-thumbnail pull-left">
                    <a href="<?php echo get_permalink($company_id) ?>" class="post-thumbnail__link">
                        <?php echo get_the_post_thumbnail($company_id,'thumbnail'); ?>
                    </a>
                </div>
                <div class="company-content">
                    <h5 class="entry-title">
                        <?php echo get_the_title($company_id); ?>
                    </h5>
                    <div class="entry-content">
                        <?php echo apply_filters('the_content', $company->post_content); ?>
                    </div>
                </div>
            </div>
        </aside>
        <aside class="widget">
            <h5 class="widget-title">
                <?php echo __('Profile', RUVOD_TEXT_DOMAIN) ?>
            </h5>
            <div class="widget-content">
                <?php  echo '<div class="company-tags-holder">'.get_the_term_list($company_id, 'post_tag', '<div class="company-tags">',', ','</div>').'</div>';  ?>
            </div>
        </aside>
        <?php
            $q = array(
                'post_type' => 'link',
                'numberposts' => 10, 
                'offset' => 0,
                'orderby'   => 'meta_value_num',
                'meta_key'  => 'position',
                'order'     => 'ASC',
                'meta_query' => array(
                    array(
                        'key' => 'company_id', 
                        'value' => $company_id
                    )
                )
            );
            $links = get_posts( $q );
            if (count($links) > 0) :
        ?>
                <aside class="widget company-links-widget">
                    <h5 class="widget-title"><?php echo __('Links', 'digezine') ?></h5>
                    <div class="widget-content">
                        <?php foreach($links as $link) { ?>
                            <?php $url =  get_post_meta($link->ID,'url',true); ?>
                            <div class="link-item">
                                <a href="<?php echo get_post_meta($link->ID,'url',true) ?>" target="_blank">
                                    <h4 class="entry-title">
                                        <?php echo $link->post_title ?>
                                    </h4>
                                    
                                    <div class="content">
                                        <?php echo $url ? parse_url($url)['host'] : '' ?>
                                    </div>
                                </a>
                                
                            </div>
                        <?php } ?>
                    </div>
                </aside>
            <?php endif; ?>
            <?php
        $vacancies = get_posts(array(
            'post_type' => 'vacancy',
            'numberposts' => 3,
            'meta_query' => array(
                array(
                    'key' => 'company_id',
                    'value' => $company_id
                )
            )
        ));
        if (count($vacancies) > 0) {

        ?>
        <aside class="widget custom-posts">
            <h5 class="widget-title">
                <?php echo __('Vacancies', RUVOD_TEXT_DOMAIN) ?>
            </h5>
            <div class="custom-posts__holder row">
            <?php
                    foreach ($vacancies as $i => $vacancy) { ?>
                        <div class="custom-posts__item post col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                            <div class="post-inner">
                                <div class="post-thumbnail">
                                    <a href="<?php echo get_permalink($vacancy->ID); ?>" class="post-thumbnail__link">
                                        <?php echo get_the_post_thumbnail($vacancy->ID,'thumbnail'); ?>
                                    </a>
                                </div>
                                <div class="post-content-wrap">
                                    <div class="entry-header">
                                        <h6 class="entry-title">
                                            <a href="<?php echo get_permalink($vacancy->ID); ?>">
                                                <?php echo get_the_title($vacancy->ID); ?>
                                            </a>
                                        </h6>
                                    </div>
                                    <div class="entry-content">
                                        <p>
                                        <?php echo wp_trim_words(get_post_meta($vacancy->ID,'duties',true),25); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <?php
                    }
                
                ?>
            </div>
        </aside>
        <?php
        }
            $q = array(
                'post_type' => 'expert',
                'numberposts' => 10, 
                'offset' => 0,
                'meta_query' => array(
                    array(
                        'key' => '_wpcf_belongs_company_id', 
                        'value' => $company_id
                    ),
                    'relation' => 'AND'
                )
            );
            $experts = get_posts( $q );
            $expert_ids = array();
            if (count($experts) > 0) :
        ?>
                <aside class="widget company-experts-widget">
                    <h5 class="widget-title"><?php echo __('Experts', 'digezine') ?></h5>
                    <div class="widget-content">
                        <?php foreach($experts as $expert) { ?>
                            <?php $expert_ids[] = $expert->ID ?>
                            <div class="single-expert-widget last-expert-comment-widget clearfix">
                                <a href="<?php echo get_permalink($expert) ?>">
                                    <div class="expert-thumbnail pull-left">
                                        <?php
                                            $t = get_the_post_thumbnail($expert->ID, 'thumbnail');
                                            if ($t == '') {
                                            ?> 
                                                <img width="150" height="150" src="<?php echo plugins_url('/assets/images/no_avatar.png', dirname(__FILE__)); ?>" class="attachment-thumbnail size-thumbnail wp-post-image" alt="">
                                            <?php
                                            } else {
                                                echo $t;
                                            }
                                        ?>
                                    </div>
                                    <h4 class="entry-title">
                                        <?php echo $expert->post_title ?>
                                    </h4>
                                    
                                    <div class="content">
                                        <p>
                                        <?php echo wp_trim_words(get_post_meta($expert->ID,'wpcf-description',true), 25) ?>
                                        </p>
                                    </div>
                                </a>
                                
                            </div>
                        <?php } ?>
                    </div>
                </aside>
            <?php endif; ?>
            <?php if (count($expert_ids) > 0) : ?>
            <?php
                    $q = array(
                        'post_type' => 'expert_comment',
                        'numberposts' => 1, 
                        'meta_query' => array(
                            array(
                                'key' => '_wpcf_belongs_expert_id', 
                                'value' => $expert_ids
                            )
                        )
                    );
                    $comments = get_posts( $q );
                    if ($comments[0]) { ?>
                        <aside class="widget company-experts-widget">
                            <h5 class="widget-title"><?php echo __('New comment', 'digezine') ?></h5>
                            <div class="widget-content">
                        <?php
                            $comment = $comments[0];
                            $expert_id = get_post_meta($comment->ID,'_wpcf_belongs_expert_id',true);
                            $post_id = get_post_meta($comment->ID,'_wpcf_belongs_post_id',true);
                            ?>
                            <div class="last-expert-comment-widget clearfix">
                                
                                <a href="<?php echo get_permalink(get_post($post_id)) ?>#expert-comments">
                                    <div class="expert-thumbnail pull-left">
                                                            <?php
                                                                $t = get_the_post_thumbnail($expert_id, 'thumbnail');
                                                                if ($t == '') {
                                                                ?> 
                                                                    <img width="150" height="150" src="<?php echo plugins_url('/assets/images/no_avatar.png', dirname(__FILE__)); ?>" class="attachment-thumbnail size-thumbnail wp-post-image" alt="">
                                                                <?php
                                                                } else {
                                                                    echo $t;
                                                                }
                                                            ?>
                                                        </div>
                                    <h4 class="entry-title">
                                        <?php echo get_the_title($expert_id) ?>
                                    </h4>
                                    
                                    <div class="content">
                                        <p>
                                        <?php echo wp_trim_words($comment->post_content, 25) ?>
                                        </p>
                                    </div>
                                </a>
                                
                            </div>
                            
                            </div>
                        </aside>
                
                        <?php
                    }
            ?>

            <?php endif; ?>
        <?php
        $q = array(
            'post_type' => 'vacancy',
            'numberposts' => 8, 
            'offset' => 0,
            'meta_query' => array(
                array(
                    'key' => '_wpcf_belongs_company_id', 
                    'value' => $company_id
                ),
                'relation' => 'AND'
            )
        );
        $vacancies = get_posts( $q );
        if (count($vacancies) > 0) : ?>
            <aside class="widget">
                <h5 class="widget-title"><?php echo __('Company vacancies', 'digezine') ?></h5>
                <div class="widget-content">
                </div>
            </aside>
        <?php endif; ?>
        <?php
            dynamic_sidebar('companies-sidebar');
        ?>
    </div>
</div>