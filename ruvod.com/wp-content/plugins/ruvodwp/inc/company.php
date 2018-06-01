<?php


add_filter( 'page_template', 'ruvod_company_page_template' );

function ruvod_company_page_template( $page_template )
{	
    global $wp_query, $post;
    wp_enqueue_media();
    if ( is_page( 'my-company' ) ||  is_page( 'my-company-en' )) {
        if(is_user_logged_in()) {
            $page_template = dirname( __FILE__ ) . './../page-company.php';
        } else {
            wp_redirect(get_bloginfo('url'),307);
        }
    }
    return $page_template;
}

function ruvod_company_post_template($single_template) {
    global $post;

    if (get_post_meta($post->ID,'company_blog',true)) {
        $single_template = dirname( __FILE__ ) . './../post-company.php';
    }
    if ($post->post_type == 'vacancy') {
        $single_template = dirname( __FILE__ ) . './../vacancy-company.php';
    }
    return $single_template;

}

add_filter('body_class', 'add_category_class_single');
function add_category_class_single($classes){
    global $post;
    if ( is_singular( array( 'post' ) ) && get_post_meta($post->ID,'company_blog',true)) {
        $classes[] = 'company-blog-page';
    }
    if ( is_singular( array( 'company' ) ) ) {
        $classes[] = 'company-blog-page';
    }
    if ( is_singular( array( 'vacancy' ) ) ) {
        $classes[] = 'company-blog-page';
    }
    return $classes;
}
add_filter( 'single_template', 'ruvod_company_post_template' );
// custom sidebar
register_sidebar( array(
    'id'          => 'my-company-sidebar',
    'name'        => __( 'Companies sidebar', TEXT ),
    'description' => __( 'Calendar sidebar after vacancies and experts', 'digezine' ),
    'before_widget' => '<aside class="widget">',
    'after_widget' => '</aside>',
) );

add_filter( 'theme_mod_sidebar_position', 'ruvod_company_specific_sidebar_position' );


function ruvod_company_specific_sidebar_position( $value ) {
    global $post;
    if ( 
        is_page( 'my-company' ) ||  
        is_page( 'my-company-en' ) || 
        (is_single() && get_post_meta($post->ID,'company_blog',true)) ||
        (is_single() && $post->post_type == 'vacancy')
        ) {
        return 'fullwidth';
    }
	return $value;
}

add_filter( "digezine_content_classes", 'ruvod_company_specific_content_classess', 11 );

function ruvod_company_specific_content_classess($layout_classes) {
    global $post;
    if ( 
        is_page( 'my-company' ) ||  
        is_page( 'my-company-en' ) || 
        (is_single() && get_post_meta($post->ID,'company_blog',true)) ||
        (is_single() && $post->post_type == 'vacancy')
        ) {
        return array(
            'col-xs-12',
            'col-md-12',
            'col-xl-12'
        );
    }
    return $layout_classes;
}

function companies_path($q = array()) {
	return get_polylang_path('my-company').'?'.http_build_query($q);
}

add_filter( 'ajax_query_attachments_args', 'ruvod_show_current_user_attachments', 10, 1 );

function ruvod_show_current_user_attachments( $query = array() ) {
    $user_id = get_current_user_id();
    if( current_user_can('editor') || current_user_can('administrator') ) {
        return $query;
    }
    if( $user_id ) {
        $query['author'] = $user_id;
    }
    return $query;
}

// HIDE POSTS FROM FEED AND DISABLE NOTIFICATIONS
add_action( 'pre_get_posts', 'ruvod_hide_company_posts' );
function ruvod_hide_company_posts( $query ) {
    if (
        $query->is_main_query() && 
        !is_admin() && 
        !is_single() &&
        !$query->is_post_type_archive('expert') &&
        !is_tag( 'blogs' )
    ) {
        $meta_query = $query->get('meta_query');
        $hide_query = array(
            'relation' => 'OR',
            array(
                'key'     => 'payment_published',
                'value' => '1',
                'compare' => '='
            ),
            array(
                'key'     => 'company_blog',
                'compare' => 'NOT EXISTS'
            )
        );
        if ($meta_query) {
            $meta_query = array(
                'relation' => 'AND',
                $meta_query,
                $hide_query
            );
        } else {
            $meta_query = array($hide_query);
        }
        $query->set('meta_query',$meta_query);
    }
    return $query;
}


add_action( 'pre_get_posts', 'ruvod_custom_sort_for_companies' );

function ruvod_custom_sort_for_companies( $query ) {
    if (
        $query->is_main_query() && 
        !is_admin() && 
        !is_single() &&
        $query->is_post_type_archive('company')
    ) {
        $meta_query = $query->get('meta_query');
        $last_publication_query = array(
            'relation' => 'OR',
            'last_publication' => 
                array(
                    'key'     => 'last_publication',
                    // 'value' => '1',
                    'compare' => 'EXISTS'
            ),
            array(
                'key'     => 'last_publication',
                'compare' => 'NOT EXISTS'
            )
        );
        if ($meta_query) {
            $meta_query = array(
                'relation' => 'AND',
                $meta_query,
                $last_publication_query
            );
        } else {
            $meta_query = array($last_publication_query);
        }
        $query->set('meta_query',$meta_query);
        $query->set('orderby', array('last_publication' => 'DESC', 'date' => 'DESC'));
    }
    return $query;
}

function is_hide_company_post($post_id) {
    //disable for no payment_published posts
    return get_post_meta($post_id, 'company_blog', true) && !get_post_meta($post_id, 'payment_published', true);
}


// убираем отправку скрытых материалов в социалки

add_filter( 'wptelegram_filter_post', 'ruvod_check_hide_company_post', 9);

function ruvod_check_hide_company_post($post) {
    if (is_hide_company_post($post->ID)) {
        return false; 
    }
    return $post;
}

add_action( 'save_post', 'check_twitter_company_premium', 0 );

function check_twitter_company_premium($post_id) {
    if (is_hide_company_post($post_id)) {
        remove_action( 'save_post', 'wpt_twit', 15 );
        remove_action( 'save_post', 'wpt_save_post', 10 );
    }
}

add_action( 'transition_post_status', 'check_vk_cross_post_copmany_post_hide_premium', 0, 3);

function check_vk_cross_post_copmany_post_hide_premium($new_status, $old_status, $post){
    global $wp_filter;
    if ( $new_status !== 'publish' ) {
        return;
    }
    if (is_hide_company_post($post->ID)) {
        // удаляем хук fb
        remove_action( 'transition_post_status', 'xyz_link_fbap_future_to_publish' );
        //находим и удаляем хук от плагина vk
        foreach ( $wp_filter['transition_post_status']->callbacks as $callbacks ) {
            foreach( $callbacks as $callback) {
                if (
                    $callback['function'] && 
                    is_array($callback['function']) &&
                    is_a($callback['function'][0],'Darx_Crosspost')
                ) {
                    remove_action('transition_post_status', array($callback['function'][0],'transition_post_status'), 1);
                }
            };
        }
    }
}

function ruvod_create_or_update_transaction($post_id, $company_id, $amount=1, $type=null) {
    global $current_user;
    $post = get_post($post_id);
    $payment_published = get_post_meta($post_id, 'payment_published',true);
    if (!$payment_published) {
        return;
    }
    $transaction_id = get_post_meta($post_id, 'company_transaction_id',true);
    if (!$type) {
        $type = $post->post_type;
    }
    $types = array(
        'vacancy' => __('Payment publish vacancy',RUVOD_TEXT_DOMAIN),
        'post' => __('Payment publish post',RUVOD_TEXT_DOMAIN)
    );
    if (!$transaction_id || !get_post($transaction_id)) {
        $transaction_meta = array(
            'company_id' => $company_id,
            'post_id' => $post_id,
            'type' => $post->post_type,
            'amount' => $amount
        );
        $transaction_id = wp_insert_post( array(
            'post_type' => 'company_transaction',
            'post_status' => 'publish',
            'post_title' => $types[$type],
            'post_author' => $current_user->ID,
            'meta_input' => $transaction_meta
        ));
        add_post_meta($post_id,'company_transaction_id',$transaction_id,true) or update_post_meta($post_id,'company_transaction_id',$transaction_id);
    } else {
        // nothing
    }
}

add_action( 'wp_insert_post', 'ruvod_apply_company_transaction', 10, 3);

function ruvod_apply_company_transaction( $post_id, $post, $update ){
    global $wp_filter;
    if ( $post->post_type != 'company_transaction' || $post->post_status !== 'publish' ) {
        return;
    }
    $company_id = get_post_meta($post->ID, 'company_id', true);
    $amount = get_post_meta($post->ID, 'amount', true);
    $apply =  get_post_meta($post->ID, 'apply', true);
    if ($apply || !$company_id || !$amount) {
        return;
    }
    $balance = get_post_meta($company_id, 'balance', true);
    $amount = $amount ? $amount : 0;
    $balance = $balance  ? $balance : 0;
    $balance-= $amount;
    add_post_meta($company_id,'balance',$balance, true) or update_post_meta($company_id, 'balance', $balance);
    add_post_meta($post->ID,'apply',true,true);
}

// ADD NEW COLUMN
function ruvod_transactions_columns_head($defaults) {
    global $post;
    $defaults['company'] = 'Компания';
    $defaults['amount'] = 'Списание';
    $defaults['post'] = 'Запись';
    $defaults['apply'] = 'Проведено';
    return $defaults;
}

// SHOW THE FEATURED IMAGE
function ruvod_transactions_columns_content($column_name, $post_ID) {
    if ($column_name == 'company') {
        $company_id = get_post_meta(get_the_ID(),'company_id',true);
        if ($company_id) {
            $c = get_post($company_id);
            echo '<a href="'.get_edit_post_link($c->ID).'" target="_blank">';
            echo $c->post_title;
            echo '</a>';
        }
    }
    if ($column_name == 'amount') {
        echo get_post_meta(get_the_ID(),'amount',true);
    }
    if ($column_name == 'post') {
        $target_post = get_post(get_post_meta(get_the_ID(),'post_id',true));
        echo '<a href="'.get_edit_post_link($target_post->ID).'" target="_blank">';
        echo get_post(get_post_meta(get_the_ID(),'post_id',true))->post_title;
        echo '</a>';
    }
    if ($column_name == 'apply') {
        echo get_post_meta(get_the_ID(),'apply',true) ? 'Да' : 'Нет';
    }
}
add_filter('manage_company_transaction_posts_columns', 'ruvod_transactions_columns_head');
add_action('manage_company_transaction_posts_custom_column', 'ruvod_transactions_columns_content', 10, 2);

function ruvod_company_post_is_posible_payment_publish() {
    return ruvod_company_week_payment_posts_count() == 0;
}

function ruvod_company_week_payment_posts_count() {
    global $current_user;
    $company_id = get_user_meta($current_user->ID, 'company_id',true);
    if (!$company_id) {
        return 0;
    }
    $query =new WP_Query(
        array(
            'post_type' => 'post',
            'numberposts' => 1,
            'post_status' => 'any',
            'date_query' => array(
                'after'     => 'last Monday'
            ),
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key'     => 'payment_published',
                    'value' => '1',
                    'compare' => '='
                ),
                array(
                    'key'     => 'company_id',
                    'value'   => $company_id,
                    'compare' => '='
                )
            )  
        ) 
    );
    return $query->post_count;
}

function ruvod_company_vacancy_is_posible_payment_publish() {
    global $current_user;
    $company_id = get_user_meta($current_user->ID, 'company_id',true);
    if (!$company_id) {
        return false;
    }
    $balance = get_post_meta($company_id, 'balance', true);
    if ($balance < 1) {
        return false;
    }
    return true;
}



add_filter('wptelegram_post_title','ruvod_add_copmany_prefix_to_title_wptelegram_post_title', 10, 2);

add_action( 'aioseop_title', 'ruvod_add_copmany_prefix_to_title_aioseop_title', 10, 2);

add_filter('wpt_post_info','ruvod_wpt_add_copmany_prefix_to_title_wpt_post_info', 10, 2);


function ruvod_add_copmany_prefix_to_title_wptelegram_post_title($title,$post) {
    return ruvod_add_company_prefix($title,$post);
}
function ruvod_add_copmany_prefix_to_title_aioseop_title($title) {
    global $post;
    if (is_single()) {
        return ruvod_add_company_prefix($title,$post);
    }
}
function ruvod_wpt_add_copmany_prefix_to_title_wpt_post_info($values,$post_ID) {
    $post = get_post($post_ID);
    if ($values['postTitle']) {
		$values['postTitle'] = ruvod_add_company_prefix($values['postTitle'],$post);
	}
    return $values;
}

function ruvod_add_company_prefix($title,$post) {
    global $post;
    if (
        $post->post_type == 'vacancy'
    ) {
        $title = 'Требуется ';
        $title.= $post->post_title;
        $company_id = get_post_meta($post->ID,'company_id',true);
        $company = get_post($company_id);
        $title.= ' в '.$company->post_title;
        // $title.= " – RUVOD";
        return mb_strtoupper($title);
    }
    if (
        $post->post_type != 'post'
    ) {
        return $title;
    }
    $company_id = get_post_meta($post->ID,'company_id',true);
    if ($company_id) {
        $company_title = get_the_title($company_id);
        if ($company_title) {
            $title = "БЛОГ ".get_the_title($company_id).': '.$title;
        }
    }
    return $title;
}

add_action( 'aioseop_description', 'ruvod_vacancy_description_aioseop_description', 10, 2);

function ruvod_vacancy_description_aioseop_description($description) {
    global $post;
    if (
        is_single() &&
        $post->post_type == 'vacancy'
    ) {
        // $description = 'Компания ';
        // $company_id = get_post_meta($post->ID,'company_id',true);
        // $company = get_post($company_id);
        // $description.= $company->post_title.'. ';

        $employment = get_post_meta($post->ID,'employment',true);
        $employments = array(
            "Полный рабочий день",
            "Парт тайм"
        );
        $description.= $employments[$employment].'. ';
        $no_salary = get_post_meta($post->ID,'salary_by_contract',true);
        if ($no_salary) {
            $description.= 'Зарплата по результатам собеседования. ';
        } else {
            $salary_from = get_post_meta($post->ID,'salary_from',true);
            $salary_to = get_post_meta($post->ID,'salary_to',true);
            if ($salary_from || $salary_to) {
                $description.= 'Зарплата ';
                if ($salary_from) {
                    $description.= 'от '.$salary_from;
                }
                if ($salary_to) {
                    $description.= 'до '.$salary_to;
                }
            }

        }
        $skills = get_post_meta($post->ID,'skills',true);
        if ($skills) {
            $description.= 'Компетенции: '.implode(', ', array_map(function($skill) {
                return $skill;
            }, explode(',', $skills)));
        }
        return $description;
    }
    return $description;
}

add_action('aiosp_opengraph_meta', 'ruvod_aiosp_opengraph_meta_vacancy',10,3);

function ruvod_aiosp_opengraph_meta_vacancy($filtered_value, $type, $key) {
    global $post;
    if (
        is_single() &&
        (
            $key == 'thumbnail' ||
            $key == 'twitter_thumbnail'
        ) &&
        $post->post_type == 'vacancy'
        ) {
            $company_id = get_post_meta($post->ID,'company_id',true);
            if ($company_id) {
                $filtered_value = get_the_post_thumbnail_url($company_id,'ruvod-company-thumbnail');
            }
    }
    if (
        is_single() &&
        (
            $key == 'thumbnail' ||
            $key == 'twitter_thumbnail'
        ) &&
        $post->post_type == 'company'
        ) {
            $filtered_value = get_the_post_thumbnail_url($post->id,'ruvod-company-thumbnail');
    }
    return $filtered_value;
}

add_image_size('ruvod-company-thumbnail',400 , 400,true);

add_filter( 'image_size_names_choose', 'ruvod_custom_company_attachment_sizes' );
 
function ruvod_custom_company_attachment_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'ruvod-company-thumbnail' => __( 'Thumb for ruvod companies', RUVOD_TEXT_DOMAIN ),
    ) );
}

add_filter( 'intermediate_image_sizes', 'ruvod_company_thumbnail_intermediate_image_sizes_features', 999 );

function ruvod_company_thumbnail_intermediate_image_sizes_features( $image_sizes ){  
    
    if  (
        isset($_REQUEST['post_id']) && 
        get_post_type( $_REQUEST['post_id'] ) != 'company' 
    ) {
        $image_sizes = array_filter($image_sizes, function($e) {
            return ($e !== 'ruvod-company-thumbnail');
        });
    }
    return $image_sizes;  
}  


include('company/ajax.php');
include(dirname( __FILE__ ) . './../company-sidebars.php');
