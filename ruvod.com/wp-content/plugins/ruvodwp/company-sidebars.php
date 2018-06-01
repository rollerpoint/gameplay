<?php

function ruvod_my_company_sidebar($company_id) {
    $company = get_post($company_id);
?>

<div class="col-xs-12 col-lg-4 sidebar widget-area">

    <aside class="widget widget-company-summary">
        <h5 class="widget-title"><?php echo __('Information', RUVOD_TEXT_DOMAIN) ?></h5>
        <div class="widget-content">
            <div class="row blog-info-item">
                <div class="col-sm-7">
                    <?php echo __('Link to blog', RUVOD_TEXT_DOMAIN) ?>
                </div>
                <div class="col-sm-5 blog-info">
                    <a href="<?php echo get_permalink($company_id) ?>" target="_blank">
                        <?php echo __('Link', RUVOD_TEXT_DOMAIN) ?>
                    </a>
                </div>
            </div>
            <div class="row blog-info-item">
                <div class="col-sm-7">
                    <?php echo __('Members', RUVOD_TEXT_DOMAIN) ?>
                </div>
                <div class="col-sm-5 blog-info">
                    <?php 
                    $query =new WP_User_Query(
                        array(
                            'meta_query' => array(
                                array(
                                    'key' => 'company_id',
                                    'value' => $company_id
                                )
                            )
                        )
                    ); ?>
                    <a href="<?php echo companies_path(array('tab' => 'members')) ?>">
                        <?php
                            echo $query->total_users; 
                        ?>
                    </a>
                </div>
            </div>
            <div class="row blog-info-item">
                <div class="col-sm-7">
                    <?php echo __('Blog records', RUVOD_TEXT_DOMAIN) ?>
                </div>
                <div class="col-sm-5 blog-info">
                    <?php 
                    $query =new WP_Query(
                        array(
                            'post_type' => 'post', 
                            'post_status' => 'any',
                            'meta_query' => array(
                                array(
                                    'key' => 'company_id',
                                    'value' => $company_id
                                )
                            )
                        )
                    );
                    ?>
                    <a href="<?php echo companies_path(array('tab' => 'blog')) ?>">
                        <?php
                            echo $query->post_count; 
                        ?>
                    </a>
                </div>
            </div>
            <div class="row blog-info-item">
                <div class="col-sm-7">
                    <?php echo __('Vacancies', RUVOD_TEXT_DOMAIN) ?>
                </div>
                <div class="col-sm-5 blog-info">
                    <?php 
                    $query =new WP_Query(
                        array(
                            'post_type' => 'vacancy', 
                            'post_status' => 'any',
                            'meta_query' => array(
                                array(
                                    'key' => 'company_id',
                                    'value' => $company_id
                                )
                            )
                        )
                    );
                    ?>
                    <a href="<?php echo companies_path(array('tab' => 'vacancies')) ?>">
                        <?php
                            echo $query->post_count; 
                        ?>
                    </a>
                </div>
            </div>
            <div class="row blog-info-item">
                <div class="col-sm-7">
                    Подписка
                </div>
                <div class="col-sm-5 blog-info">
                    <?php
                    $subscribe_at_str = get_post_meta($company_id, 'subscribe_at', true);   
                    if ($subscribe_at_str) {
                        $subscribe_at = DateTime::createFromFormat('Y-m-d', $subscribe_at_str)->modify('monday this week');
                        $subscribe_end = DateTime::createFromFormat('Y-m-d', $subscribe_at_str)->modify('+1 year')->modify('sunday this week');
                        $now = new DateTime();
                        $weeks = floor($now->diff($subscribe_end)->days/7);
                        $weeks-=ruvod_company_week_payment_posts_count();
                        echo __('To',RUVOD_TEXT_DOMAIN).' '.$subscribe_end->format('d.m.Y');
                        $p = plural_form( $weeks, array('публикацию', 'публикации', 'публикаций')); 
                        $msg = "До конца подписки, вы можете отправить на главную еще ".$weeks." ".$p;
                        ?>
                            <a class="" 
                            href="#" 
                            onclick="return false"
                            data-html="true"
                            data-toggle="popover"
                            data-placement="top"
                            data-trigger="hover"
                            data-content="<?php echo $msg ?>">(?)</a>
                        <?php
                    } else {
                        echo '—';
                    }                     
                    ?>
                </div>
            </div>
            <div class="row blog-info-item">
                <div class="col-sm-7">
                    <?php echo __('Balance', RUVOD_TEXT_DOMAIN) ?>
                </div>
                <div class="col-sm-5 blog-info">
                    <?php
                    $balance = get_post_meta($company_id, 'balance', true);                        
                    ?>
                    <a href="<?php echo companies_path(array('tab' => 'billing')) ?>">
                        <?php
                            echo $balance ? $balance : 0; ?> RVD
                    </a> 
                </div>
            </div>
            <div class="row blog-info-item">
                <div class="col-sm-7">
                    <?php echo __('Support', RUVOD_TEXT_DOMAIN) ?>
                </div>
                <div class="col-sm-5 blog-info">
                    <a href="mailto:support@ruvod.com">
                        support@ruvod.com
                    </a>
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
                                    <?php 
                                    if ($url) {
                                        if (strpos($url, 'tg://') === 0) {
                                            $url = "http://t.me";
                                        } else if (strpos($url, 'http') === false) {
                                            $url = "http://".$url; 
                                        }
                                        echo parse_url($url)['host'];
                                    }
                                    ?>
                                </div>
                            </a>
                            
                        </div>
                    <?php } ?>
                </div>
            </aside>
        <?php endif; ?>
    <aside class="widget custom-posts">
        <h5 class="widget-title">
            <?php echo __('Latest publciations', RUVOD_TEXT_DOMAIN) ?>
            <a href="<?php echo companies_path(array('tab' => 'blog', 'action' => 'form')) ?>" class="btn btn-xs btn-black pull-right">
            <?php echo __('Add', RUVOD_TEXT_DOMAIN) ?>
            </a>
        </h5>
        <div class="custom-posts__holder row">
            <?php
            $posts = get_posts(array(
                'post_type' => 'post',
                'numberposts' => 2,
                'meta_query' => array(
                    array(
                        'key' => 'company_id',
                        'value' => $company_id
                    )
                )
            ));
            if ($posts) {
                foreach ($posts as $i => $post) { ?>
                    <div class="custom-posts__item post col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-12">
                        <div class="post-inner">
                            <div class="post-thumbnail">
                                <a href="<?php echo get_permalink($post->ID); ?>" class="post-thumbnail__link">
                                    <?php echo get_the_post_thumbnail($post->ID,'thumbnail'); ?>
                                </a>
                            </div>
                            <div class="post-content-wrap">
                                <div class="entry-header">
                                    <h6 class="entry-title">
                                        <a href="<?php echo get_permalink($post->ID); ?>">
                                            <?php echo get_the_title($post->ID); ?>
                                        </a>
                                    </h6>
                                </div>
                                <div class="entry-content">
                                    <?php echo wp_trim_words(get_the_excerpt($post),10); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php
                }
            }
            ?>
            
        </div>
    </aside>
    <aside class="widget custom-posts">
        <h5 class="widget-title">
            <?php echo __('Latest vacancies', RUVOD_TEXT_DOMAIN) ?>
            <a href="<?php echo companies_path(array('tab' => 'vacancies', 'action' => 'form')) ?>" class="btn btn-xs btn-black pull-right">
                <?php echo __('Add', RUVOD_TEXT_DOMAIN) ?>
            </a>
        </h5>
        <div class="custom-posts__holder row">
        <?php
            $vacancies = get_posts(array(
                'post_type' => 'vacancy',
                'numberposts' => 2,
                'meta_query' => array(
                    array(
                        'key' => 'company_id',
                        'value' => $company_id
                    )
                )
            ));
            if ($vacancies) {
                foreach ($vacancies as $i => $vacancy) { ?>
                    <div class="custom-posts__item post col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-12">
                        <div class="post-inner">
                            <div class="post-thumbnail">
                                <a href="<?php echo get_permalink($vacancy->ID); ?>" class="post-thumbnail__link">
                                    <?php echo get_the_post_thumbnail($company_id,'thumbnail'); ?>
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
                                    <?php echo wp_trim_words(get_post_meta($vacancy->ID,'duties',true),10); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php
                }
            }
            
            ?>
        </div>
    </aside>

    <?php
        dynamic_sidebar('my-company-sidebar');
    ?>
</div>

<?php
}

function ruvod_company_sidebar($company_id) {
    $company = get_post($company_id);
?>
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
                                    <?php 
                                    if ($url) {
                                        if (strpos($url, 'tg://') === 0) {
                                            $url = "http://t.me";
                                        } else if (strpos($url, 'http') === false) {
                                            $url = "http://".$url; 
                                        }
                                        echo parse_url($url)['host'];
                                    }
                                    ?>
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
        'numberposts' => 2,
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
                                    <?php echo get_the_post_thumbnail($company_id,'thumbnail'); ?>
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
            'numberposts' => 3, 
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

        $posts = get_posts(array(
            'post_type' => 'post',
            'numberposts' => 2,
            'meta_query' => array(
                array(
                    'key' => 'company_id',
                    'value' => $company_id
                )
            )
        ));
                    
        if ($posts) { ?>
            <aside class="widget custom-posts">
                <h5 class="widget-title">
                    <?php echo __('Latest publciations', RUVOD_TEXT_DOMAIN) ?>
                </h5>
                <div class="custom-posts__holder row">
                    <?php

                        foreach ($posts as $i => $post) { ?>
                            <div class="custom-posts__item post col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-12">
                                <div class="post-inner">
                                    <div class="post-thumbnail">
                                        <a href="<?php echo get_permalink($post->ID); ?>" class="post-thumbnail__link">
                                            <?php echo get_the_post_thumbnail($post->ID,'thumbnail'); ?>
                                        </a>
                                    </div>
                                    <div class="post-content-wrap">
                                        <div class="entry-header">
                                            <h6 class="entry-title">
                                                <a href="<?php echo get_permalink($post->ID); ?>">
                                                    <?php echo get_the_title($post->ID); ?>
                                                </a>
                                            </h6>
                                        </div>
                                        <div class="entry-content">
                                            <?php echo wp_trim_words(get_the_excerpt($post),10); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php
                        }

                    ?>
                    
                </div>
            </aside>
        <?php } ?>
    <?php
        dynamic_sidebar('companies-sidebar');
    ?>
</div>
<?php
}