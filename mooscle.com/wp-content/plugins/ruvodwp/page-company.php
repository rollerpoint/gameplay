<?php
global $current_user;
$user = $current_user;
$company_id = get_user_meta($current_user->ID, 'company_id', true);
$company = get_post($company_id);
$action = $_GET['tab'] ? $_GET['tab'] : 'main';

$tabs = array(
    'main' => __('Company Profile', RUVOD_TEXT_DOMAIN),
    'blog' => __('Blog', RUVOD_TEXT_DOMAIN),
    'vacancies' => __('Vacancies', RUVOD_TEXT_DOMAIN),
    'links' => __('Links', RUVOD_TEXT_DOMAIN),
    // 'billing' => __('Billing', RUVOD_TEXT_DOMAIN),
    'members' => __('Members', RUVOD_TEXT_DOMAIN),
);
$tabs_main_actions = array(
    'main' => 'view',
    'blog' => 'list',
    'vacancies' => 'list',
    'links' => 'list',
    'members' => 'list',
);
$tab_action = $_GET['action'] ? $_GET['action'] : $tabs_main_actions[$action];
?>

<div class="row my-company-page">
    <div class="col-xs-12 col-lg-8">
        <?php
        echo '<ul class="nav nav-tabs">';
        foreach ($tabs as $key => $value) {
            echo '<li class="'.($action == $key && $tab_action == $tabs_main_actions[$action] ? 'active' : '').'"><a href="'.companies_path().'?tab='.$key.'">'.$value.'</a></li>';
        }
        echo '</ul>';
        ?>

        <div class="tab-content">
        <?php include('inc/company/'.$action.'.php'); ?>
        </div>
    </div>
    <div class="col-xs-12 col-lg-4 sidebar widget-area">

        <aside class="widget widget-company-summary">
            <h5 class="widget-title"><?php echo __('Information', RUVOD_TEXT_DOMAIN) ?></h5>
            <div class="widget-content">
                <div class="row blog-info-item">
                    <div class="col-sm-7">
                        <?php echo __('Link to blog', RUVOD_TEXT_DOMAIN) ?>
                    </div>
                    <div class="col-sm-5 blog-info">
                        <a href="<?php echo get_permalink($company) ?>" target="_blank">
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
        <aside class="widget custom-posts">
            <h5 class="widget-title">
                <?php echo __('Latest publciations', RUVOD_TEXT_DOMAIN) ?>
                <a href="<?php echo companies_path(array('tab' => 'blog', 'action' => 'form')) ?>" class="btn btn-xs btn-black pull-right">
                 <?php echo __('Add', RUVOD_TEXT_DOMAIN) ?>
                </a>
            </h5>
            <div class="custom-posts__holder row">
                <?php
                $posts = wp_get_recent_posts(array(
                    'post_type' => 'post',
                    'numberposts' => 3,
                    'meta_query' => array(
                        array(
                            'key' => 'company_id',
                            'value' => $company_id
                        )
                    )
                ),OBJECT);
                if ($posts) {
                    foreach ($posts as $i => $post) { ?>
                        <div class="custom-posts__item post col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-12">
                            <div class="post-inner">
                                <div class="post-thumbnail">
                                    <a href="" class="post-thumbnail__link">
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
                $vacancies = wp_get_recent_posts(array(
                    'post_type' => 'vacancy',
                    'numberposts' => 3,
                    'meta_query' => array(
                        array(
                            'key' => 'company_id',
                            'value' => $company_id
                        )
                    )
                ),OBJECT);
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
</div>