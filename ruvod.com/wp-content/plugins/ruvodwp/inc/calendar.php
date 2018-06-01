<?php

add_action( 'wp_enqueue_scripts', 'ruvod_calendar_enqueue_scripts' );


function ruvod_calendar_enqueue_scripts() {
    wp_enqueue_script('moment', RUVOD_PLUGIN_DIR.'assets/vendor/moment/moment-with-locales.min.js', array( 'jquery' ));

    wp_enqueue_style('calendar', RUVOD_PLUGIN_DIR.'assets/vendor/bootstrap-year-calendar/css/bootstrap-year-calendar.min.css');
    wp_enqueue_script('calendar', RUVOD_PLUGIN_DIR.'assets/vendor/bootstrap-year-calendar/js/bootstrap-year-calendar.js', array( 'jquery' ));
    wp_enqueue_script('calendar-langs', RUVOD_PLUGIN_DIR.'assets/vendor/bootstrap-year-calendar/js/languages/bootstrap-year-calendar.ru.js', array( 'calendar' ));

    wp_enqueue_style('whirl', RUVOD_PLUGIN_DIR.'assets/vendor/whirl/dist/whirl.min.css');
    wp_enqueue_style('whirl-traditional', RUVOD_PLUGIN_DIR.'assets/vendor/whirl/dist/whirl.min.css');

    wp_enqueue_style('chosen', RUVOD_PLUGIN_DIR.'assets/vendor/chosen/chosen.css');
    wp_enqueue_script('chosen', RUVOD_PLUGIN_DIR.'assets/vendor/chosen/chosen.jquery.js', array( 'jquery' ));

    wp_enqueue_script('ruvod_calendar', RUVOD_PLUGIN_DIR.'assets/calendar.js', array( 'jquery' ), filemtime( RUVOD_PLUGIN_DIRNAME.'/assets/calendar.js' ) );
    wp_enqueue_style('ruvod_calendar', RUVOD_PLUGIN_DIR.'assets/calendar.css', array(), filemtime( RUVOD_PLUGIN_DIRNAME.'/assets/calendar.css' ) );
    wp_enqueue_script('bootstrap-datepicker', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js', array('jquery'));
    wp_enqueue_style('bootstrap-datepicker', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.css');
    if (get_locale() == 'ru_RU') {
        wp_enqueue_script('bootstrap-datepicker-locale','https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/locales/bootstrap-datepicker.ru.min.js', array('bootstrap-datepicker'));
    }
}

// API CUSTOMIZE
function api_allow_meta_query( $query_vars, $request ) {
    if ( $request['meta_key'] ) {
        $query_vars['meta_key'] = $request['meta_key'];
    }
    if ( $request['meta_value'] ) {
        $query_vars['meta_value'] = $request['meta_value'];
    }
    if ( $request['meta_query'] ) {
        $query_vars['meta_query'] = $request['meta_query'];
    }
    return $query_vars;
}
add_filter( 'rest_release_query', 'api_allow_meta_query',10,2);

add_action( 'rest_api_init', 'register_api_custom_fields' );

function register_api_custom_fields() {    
    $taxonomies = get_taxonomies( '', 'objects' );
     
    foreach( $taxonomies as $taxonomy ) {
        $taxonomy->show_in_rest = true;
    }

    register_rest_field( 'release',
        'release_date',
        array(
            'get_callback'    => 'ruvod_get_meta_for_api',
            'update_callback' => null,
            'schema'          => null,
        )
    );
    register_rest_field( 'release',
        'description',
        array(
            'get_callback'    => 'ruvod_get_meta_for_api',
            'update_callback' => null,
            'schema'          => null,
        )
    );

    register_rest_field( 'release',
        'pirates_index',
        array(
            'get_callback'    => 'ruvod_get_meta_for_api',
            'update_callback' => null,
            'schema'          => null,
        )
    );

    register_rest_field( 'release',
        'pirates_formats',
        array(
            'get_callback'    => 'ruvod_get_meta_for_api',
            'update_callback' => null,
            'schema'          => null,
        )
    );

    register_rest_field( 'rightholders',
        'color',
        array(
            'get_callback'    => 'ruvod_get_term_meta_for_api',
            'update_callback' => null,
            'schema'          => null,
        )
    );

    $multilang_title_tems = array('genre','rightholders','customers');

    foreach($multilang_title_tems as $tax_slug) {
        register_rest_field( $tax_slug,
            'name_en',
            array(
                'get_callback'    => 'ruvod_get_term_meta_for_api',
                'update_callback' => null,
                'schema'          => null,
            )
        );
    }

}

function ruvod_get_meta_for_api( $object, $field_name, $request ) {
    return get_post_meta( $object[ 'id' ], 'wpcf-'.$field_name, true );
}
function ruvod_get_term_meta_for_api( $object, $field_name, $request ) {
    return get_term_meta( $object[ 'id' ], 'wpcf-'.$field_name, true );
}


// ADMIN CUSTOMIZE

add_action( 'load-edit.php', 'no_category_dropdown_and_date' );
function no_category_dropdown_and_date() {
    add_filter( 'wp_dropdown_cats', '__return_false' );
    add_filter('months_dropdown_results', '__return_empty_array');
}


add_filter( 'manage_release_posts_columns', 'hide_releases_date_column' );

function hide_releases_date_column($columns) {
    unset( $columns['date'] );
    return $columns;
}

// CALENDAR SIDEBAR
register_sidebar( array(
    'id'          => 'calendar-sidebar',
    'name'        => __( 'Releases calendar sidebar', RUVOD_TEXT_DOMAIN ),
    'description' => __( 'Calendar sidebar after calendar filter', RUVOD_TEXT_DOMAIN ),
) );

function digital_releases_calendar_modal() {
    return 
    '<div class="modal fade" tabindex="-1" role="dialog" id="digitalCalendarModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <h4 class="modal-title" data-title="'.__('Digital releases', RUVOD_TEXT_DOMAIN).'">'.__('Digital releases', RUVOD_TEXT_DOMAIN).'</h4>
                </div>
                <div class="item-template hidden">
                    <div class="event-modal-content">
                        <div style="display:none;" class="event-name"><span class="content"></span></div>
                        <div style="display:none;" class="event-rightholders"><b>'.__('Distributor', RUVOD_TEXT_DOMAIN).': </b><span class="content"></span></div>
                        <div style="display:none;" class="event-genre"><b>'.__('Genre', RUVOD_TEXT_DOMAIN).': </b><span class="content"></span></div>
                        <div style="display:none;" class="event-licence_type"><b>'.__('Monetization model', RUVOD_TEXT_DOMAIN).': </b><span class="content"></span></div>
                        <div style="display:none;" class="event-customers"><b>'.__('Marketplace', RUVOD_TEXT_DOMAIN).': </b><span class="content"></span></div>
                        <div style="display:none;" class="event-pirates_index">
                            <b>'.__('Piracy index', RUVOD_TEXT_DOMAIN).': </b> 
                            <span class="content"></span>
                            <b> 
                                <a href="#" tabindex="1" data-toggle="popover" title="" data-content="'.__('Reflects the percentage of links with illegal copies on the first page of search engines',RUVOD_TEXT_DOMAIN).'.<br>'.__('Data provided',RUVOD_TEXT_DOMAIN).' <a href=\'https://www.group-ib.ru/antipiracy.html?utm_medium=cpc&amp;utm_source=%7Bsource%7D\' target=\'_blank\'>Group-IB</a>" data-original-title="Индекс пиратируемости">
                                    (?)
                                </a>
                            </b>
                        </div>
                        <div style="display:none;" class="event-pirates_formats">
                            <b>
                                '.__('Formats of pirated copies', RUVOD_TEXT_DOMAIN).':
                            </b> 
                            <span class="content"></span>
                            <b> 
                                <a href="#" tabindex="1" data-toggle="popover" data-content="'.__('Reflects the quality of illegal copies on the network',RUVOD_TEXT_DOMAIN).'.<br>'.__('Data provided',RUVOD_TEXT_DOMAIN).' <a href=\'https://www.group-ib.ru/antipiracy.html?utm_medium=cpc&amp;utm_source=%7Bsource%7D\' target=\'_blank\'>Group-IB</a>" data-original-title="" title="">
                                    (?)
                                </a>
                            </b>
                        </div>
                    </div>
                </div>
                <div class="modal-body">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">'.__('Close',RUVOD_TEXT_DOMAIN).'</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>';
}
// SHORTCUT
function digital_releases_calendar_content( $atts, $content = null){
    ob_start();
    dynamic_sidebar('calendar-sidebar');
    $sidebar = ob_get_contents();
    ob_end_clean();
    $modal = digital_releases_calendar_modal();
    $filters_form_elements = '
        <div class="filter-item">
            <label>'.__('Movie',RUVOD_TEXT_DOMAIN).'</label>
            <div><input class="form-control" type="search" name="title" placeholder="'.__('Title',RUVOD_TEXT_DOMAIN).'"></div>
        </div>
        <div class="filter-item">
            <label>'.__('Marketplace',RUVOD_TEXT_DOMAIN).'</label>
            <select name="customers" multiple=""  data-placeholder="'.__('Select some option',RUVOD_TEXT_DOMAIN).'"> 
            </select>
        </div>
        <div class="filter-item">
            <label>'.__('Distributor',RUVOD_TEXT_DOMAIN).'</label>
            <select name="rightholders" multiple=""  data-placeholder="'.__('Select some option',RUVOD_TEXT_DOMAIN).'">
            </select>
        </div>
        <div class="filter-item">
            <label>'.__('Genre',RUVOD_TEXT_DOMAIN).'</label>
            <select name="genre" multiple=""  data-placeholder="'.__('Select some option',RUVOD_TEXT_DOMAIN).'">
            </select>
        </div>
        <div class="filter-item">
            <label>'.__('Monetization model',RUVOD_TEXT_DOMAIN).'</label>
            <select name="licence_type" multiple="" data-placeholder="'.__('Select some option',RUVOD_TEXT_DOMAIN).'">
            </select>
        </div>
        <div class="submit-container">
            <a href="http://ruvod.com/wp-content/uploads/2017/10/RELEASE-PLAN-FOR-RUVOD.xlsx" class="btn btn-secondary download-template">
                '.__('Download template',RUVOD_TEXT_DOMAIN).'
            </a>
            <button class="btn btn-primary pull-right">
                '.__('Search',RUVOD_TEXT_DOMAIN).'
            </button>
            <button type="button" data-toggle="tooltip" title="'.__('Download',RUVOD_TEXT_DOMAIN).'" class="btn download-calendar  pull-right btn-secondary">
                <i class="icon-cloud-download"></i>
            </button>
        </div>
    ';
    $filters='
	<div class="col-xs-12 col-lg-4">
	    <div class="filters">
            <form class="filters-form">
            '.$filters_form_elements.'
            </form>
	    </div>
	    <ul class="sidebar">'.$sidebar.'</ul>
	</div>';
    $holder= '
	<div class="col-xs-12 col-lg-8 calendar-wrapper">
	    <div id="calendar"></div>
	    <div id="open-releases-holder"></div>
	</div>';
    return '<div class="calendar-holder row">'.$modal.$holder.$filters.'</div>';
}
add_shortcode( 'digital_releases_calendar', 'digital_releases_calendar_content' );


function ruvod_small_releases_calendar( $atts ){
    ob_start();
        echo digital_releases_calendar_modal();
        ?>
        <div class="small-releases-calendar-widger text-center loader traditional">
            <div class="small-calendar">
            
            </div>
        </div>
        <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
add_shortcode( 'small_releases_calendar', 'ruvod_small_releases_calendar' );