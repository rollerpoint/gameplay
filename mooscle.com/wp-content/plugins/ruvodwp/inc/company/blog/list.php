<div class="my-company-blog">
<?php

    
    $query = array(
        'post_type' => 'post',
        'posts_per_page' => 10,
        'paged' => $wp_query->query_vars['paged'],
        's' => $_GET['search'],
        'post_status' => 'any',
        'meta_query' => array(
            array(
                'key' => 'company_id',
                'value' => $company_id
            )
        )
    );
    $show_modes = array('all','draft','publish', 'on_main');
    $mode_names = array(
        'all' =>  __('All', RUVOD_TEXT_DOMAIN),
        'draft' => __('Drafts', RUVOD_TEXT_DOMAIN),
        'publish' => __('Publisheds', RUVOD_TEXT_DOMAIN),
        'on_main' => __('On main', RUVOD_TEXT_DOMAIN)
    );
    $mode = $_GET['mode'] ? $_GET['mode'] : 'all'; 
    if (!in_array ( $mode , $show_modes )) {
        $mode = 'all';
    }
    if ($mode != 'all') {
        if ($mode == 'on_main') {
            $query['meta_query'] = array(
                array(
                    'key'     => 'payment_publish',
                    'value'   => '1',
                    'compare' => '='
                )
            );
        } else {
            $query['post_status'] = $mode;
        }
        
    }
    query_posts($query);
    if (have_posts() || $mode != 'all') {
        ?>
        <h2 class="blog-title">
            <?php _e('Publications', RUVOD_TEXT_DOMAIN); ?>
            
            <a href="<?php echo companies_path(array('tab' => 'blog', 'action' => 'form')) ?>" class="btn btn-xs btn-black">
                <?php _e('New', RUVOD_TEXT_DOMAIN); ?>
            </a>
            <div class="dropdown company-blog-filter pull-right">
                <button class="btn btn-xs btn-secondary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <?php echo $mode_names[$mode] ?>
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <?php 
                        foreach ($show_modes as $key => $target_mode) {
                            echo '<li><a href="'.companies_path(array('tab' => 'blog', 'mode' => $target_mode)).'">'.$mode_names[$target_mode].'</a></li>';
                        }
                    ?>
                </ul>
            </div>
        </h2>
        <?php
            if ($_GET['notify'] == 'error') {
                echo '<div class="alert alert-danger" role="alert">'.($_GET['message'] ? $_GET['message'] : 'Неизвестная ошибка, обратитесь в техподдержку').'</div>';
            }
            if ($_GET['notify'] == 'success') {
                echo '<div class="alert alert-success" role="alert">'.($_GET['message'] ? $_GET['message'] : 'Операция выполнена').'</div>';
            } 
        ?>
        <?php if (!have_posts()) { ?>
            <div class="text-center empty-list-text">
                <h4>
                    <?php _e('Not found posts for query', RUVOD_TEXT_DOMAIN); ?>
                </h4>
            </div>
            
        <?php } ?>
        <div <?php digezine_posts_list_class(); ?>>
        <?php
        while (have_posts()) : the_post();         
            include( __DIR__ . '/../template_parts/content-company-post.php');
        endwhile;
        ?></div> <?php
        the_posts_pagination(apply_filters(
            'digezine_content_posts_pagination',
            array(
                'prev_text' => (!is_rtl()) ? '<i class="linearicon linearicon-arrow-left"></i>' : '<i class="linearicon linearicon-arrow-right"></i>',
                'next_text' => (!is_rtl()) ? '<i class="linearicon linearicon-arrow-right"></i>' : '<i class="linearicon linearicon-arrow-left"></i>',
            )
        ));
    } else {
        if ($_GET['search']) {
        ?>
            <div class="text-center empty-list-text">
                <h4>
                    <?php _e('Not found posts for query', RUVOD_TEXT_DOMAIN); ?>
                </h4>
            </div>
        <?php } else { ?>
            <div class="text-center empty-list-text">
                <h4>
                    <?php _e('Posts not found, create first post now', RUVOD_TEXT_DOMAIN); ?>
                </h4>
                <a href="<?php echo companies_path(array('tab' => 'blog', 'action' => 'form')) ?>" class="btn btn-primary">
                    <?php _e('Create', RUVOD_TEXT_DOMAIN); ?>
                </a>
            </div>
        <?php }
    }
    wp_reset_query();
    wp_reset_postdata();
?>
</div>