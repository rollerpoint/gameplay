<?php

register_sidebar( array(
    'id'          => 'companies-sidebar',
    'name'        => __( 'Companies sidebar', 'digezine' ),
    'description' => __( 'Calendar sidebar after vacancies and experts', 'digezine' ),
    'before_widget' => '<aside class="widget">',
    'after_widget' => '</aside>',
) );

add_filter( 'theme_mod_sidebar_position', 'ruvod_specific_sidebar_position' );
// Disable default sidebar
function ruvod_specific_sidebar_position( $value ) {
    if ( is_singular( array( 'company' ) ) ) {
        return 'fullwidth';
    }
	return $value;
}

add_filter( "digezine_content_classes", 'ruvod_specific_content_classess', 11 );

function ruvod_specific_content_classess($layout_classes) {
    if ( is_singular( array( 'company' ) ) ) {
        return array(
            'col-xs-12',
            'col-md-12',
            'col-xl-12'
        );
    }
    return $layout_classes;
}

add_action('template_redirect', 'ruvod_redirect_to_company', 9);
function ruvod_redirect_to_company() {
  if (is_tag()) {
    $tag = get_queried_object();
    $q = array(
        'post_type' => 'company',
        'numberposts' => 1, 
        'meta_query' => array(
            array(
                'key' => 'wpcf-main_tag_slug', 
                'value' =>  $tag->slug
            )
        )
    );
    $companies = get_posts( $q );
    if ($companies[0]) {
        wp_redirect(get_permalink($companies[0]));
    }
  }
}

add_filter( 'get_the_terms', 'ruvod_company_main_tag_sort', 10, 3);

function ruvod_company_main_tag_sort($terms, $post_id, $taxonomy) {
    if ( 
        is_post_type_archive(array( 'company' )) || 
        (is_singular( array( 'company' ) ) || is_page('my-company')) && $taxonomy == 'post_tag' 
    ) {
        $main_tag_slug = get_post_meta($post_id, 'wpcf-main_tag_slug', true);
        if ($main_tag_slug) {
            $main_tag = null;
            $terms = array_filter($terms, function($tag) use ($main_tag_slug, &$main_tag) {
                if ($tag->slug != $main_tag_slug) {
                    return true;
                } else {
                    $main_tag = $tag;
                    return false;
                }
            });
            if ($main_tag) {
                array_unshift($terms , $main_tag);
            }
        }
    }
    return $terms;
}
