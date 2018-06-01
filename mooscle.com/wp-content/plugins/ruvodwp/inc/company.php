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
    return $single_template;

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
    if ( is_page( 'my-company' ) ||  is_page( 'my-company-en' ) || (is_single() && get_post_meta($post->ID,'company_blog',true))) {
        return 'fullwidth';
    }
	return $value;
}

add_filter( "digezine_content_classes", 'ruvod_company_specific_content_classess', 11 );

function ruvod_company_specific_content_classess($layout_classes) {
    global $post;
    if ( is_page( 'my-company' ) ||  is_page( 'my-company-en' ) || (is_single() && get_post_meta($post->ID,'company_blog',true))) {
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
        !$query->is_post_type_archive('expert')
    ) {
        $tax_query = $query->get('meta_query');
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


function is_hide_company_post($post_id) {
    //disable for all company posts
    return get_post_meta($post_id, 'company_blog', true); //&& !get_post_meta($post_id, 'payment_published', true);
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

include('company/ajax.php');
