<?php



function is_main_language($post_id) {
    if (function_exists ('pll_get_post_language')) {
        $lang = pll_get_post_language($post_id);
        if ($lang) {
            return $lang == 'ru';
        }
    }
    return true;
}

add_filter( 'wptelegram_filter_post', 'teltegramm_check_language_post', 10);

function teltegramm_check_language_post($post) {
    if ($post && !is_main_language($post->ID)) {
        return false;
    }
    return $post;
}

add_action( 'transition_post_status', 'check_vk_cross_post_check_language', 0, 3);

function check_vk_cross_post_check_language($new_status, $old_status, $post){
    global $wp_filter;
    if ( $new_status !== 'publish' ) {
        return;
    }
    if (!is_main_language($post->ID)) {
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

add_action( 'save_post', 'check_twitter_language', 0 );

function check_twitter_language($post_id) {
    if (!is_main_language($post_id)) {
        remove_action( 'save_post', 'wpt_twit', 15 );
        remove_action( 'save_post', 'wpt_save_post', 10 );
    }
}

add_action( 'pre_get_posts', 'search_only_current_language' );
function search_only_current_language( $query ) {
    if ($query->is_search() && $query->get('post_type') == 'post') {
        if (function_exists ('pll_get_post_language')) {
            $lang = pll_current_language();
            $query->set('lang',$lang);
        }
    }
    return $query;
}