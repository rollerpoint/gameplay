<?php

include_once(ABSPATH . 'wp-includes/pluggable.php');


add_action('wp_ajax_yandex_payment_success', 'ruvod_yandex_payment_success');
add_action('wp_ajax_nopriv_yandex_payment_success', 'ruvod_yandex_payment_success');

function ruvod_yandex_payment_success() {
	$hash = sha1($_POST['notification_type'].'&'.$_POST['operation_id'].'&'.$_POST['amount'].
							'&'.$_POST['currency'].'&'.$_POST['datetime'].'&'.$_POST['sender'].'&'.
							$_POST['codepro'].'&'.get_option('ruvod_yandex_notify_secret').'&'.$_POST['label']);
	if (strtolower($hash) == strtolower($_POST['sha1_hash'])) {
		$label = $_POST['label'];
		if (strpos($label,'access_to_contacts_on_cv')) {
			$data = explode('_access_to_contacts_on_cv_',$label);
			$user_id = $data[0];
			update_user_meta($user_id,$label,'1');
			echo 'true';
		} else if ( strpos($label,'subscribe') ) {
			$data = explode('_subscribe_',$label);
			$user_id = $data[0];
			$s_type = $data[1];
			$subscribies = array(
				'one_month' => 1,
				'three_month' => 3,
				'half_year' => 6,
				'year' => 12
			);
			$months = $subscribies[$s_type];
			$start_date = get_user_meta($user_id,'subscribe_end',true);
			if ($start_date) {
				$start_date = new DateTime( '@'.$start_date );
			} else {
				$start_date = new DateTime('NOW');
			}
			$start_date->modify('+'.$months.' month');
			$end = $start_date->getTimestamp();
			update_user_meta($user_id,'subscribe_end',$end);
            echo 'Success subsribe to '.$start_date->format('d.m.Y');
            $user = get_user_by( 'id', $user_id );
            if ($user) {
                $user->remove_role('subscriber');
                $user->add_role('premium_subscriber');
            }
		} else {
			echo 'Undefined payment type';
		}
	} else {
		echo 'false';
	}
	wp_die();
};

add_role(
    'premium_subscriber',
    'Премиум подписчик',
    array(
        'read' => true
    )
);

function no_admin_access_for_subscriber() {
	if ($_POST['action'] || $_GET['action']) {
		return;
	}
	 $redirect = home_url( '/' );
	 global $current_user;
	 $user_roles = $current_user->roles;
	 $user_role = array_shift($user_roles);
	 if($user_role === 'premium_subscriber'){
			 exit( wp_redirect( $redirect ) );
	 }
}


add_action( 'admin_init', 'no_admin_access_for_subscriber', 100 );

add_action('after_setup_theme', 'remove_admin_bar_for_subscriber');
 
function remove_admin_bar_for_subscriber() {
	// global $current_user;
	$user_roles = wp_get_current_user()->roles;
	$user_role = array_shift($user_roles);
	if($user_role === 'premium_subscriber'){
			show_admin_bar(false);
	}
}


function can_manage_subscibers() {
    return current_user_can('administrator') || current_user_can('subscribe_manager');
}

function get_user_role( $user = null ) {
    $user = $user ? new WP_User( $user ) : wp_get_current_user();
    check_subscribe($user);
    return $user->roles ? array_values($user->roles)[0] : false;
}

function check_subscribe($user) {
    if (is_admin()) {
        return false;
    }
    if( current_user_can('editor') || current_user_can('administrator') ) {
        return false;
    }
    $subscribe_end = get_user_meta($user->ID,'subscribe_end',true);
    $active_subscribe = false;
    if ($subscribe_end) {
        $subscribe_end = new DateTime( '@'.$subscribe_end );
        if ($subscribe_end > new DateTime('NOW')) {
            $active_subscribe = true;
        }
    }
    if (!in_array('premium_subscriber', $user->roles) && $active_subscribe) {
        $user->remove_role( 'subscriber' );
        $user->add_role( 'premium_subscriber' );
        $user->roles = array('premium_subscriber');
    } else if (!in_array('subscriber', $user->roles) && !$active_subscribe) {
        $user->remove_role( 'premium_subscriber' );
        $user->add_role( 'subscriber' );
        $user->roles = array('subscriber');
    }
}

add_action( 'pre_get_posts', 'hide_premium_posts' );
function hide_premium_posts( $query ) {
    if ($query->is_main_query() && $query->get('post_type') == 'post') {
        $role = get_user_role();
        if (!$role || $role == 'subscriber') {
            $tax_query = $query->get('tax_query');
            $hide_query = array(
                'taxonomy' => 'post_tag',
                'field'    => 'slug',
                'terms'    => array('hidden_premium', 'hidden_premium-en'),
                'operator' => 'NOT IN'
            );
            if ($tax_query) {
                $tax_query = array(
                    'operator' => 'AND',
                    $tax_query,
                    $hide_query
                );
            } else {
                $tax_query = array($hide_query);
            }
            $query->set('tax_query',$tax_query);
        }
    }
    return $query;
}


function is_hide_premium($post_id) {
    $tags = wp_get_post_tags( $post_id, array( 'fields' => 'slugs' ) );
    return in_array('hidden_premium',$tags) || in_array('hidden_premium-en',$tags);
}

function is_premium($post_id) {
    $tags = wp_get_post_tags( $post_id, array( 'fields' => 'slugs' ) );
    return is_hide_premium($post_id) || in_array('premium',$tags) || in_array('premium-en',$tags);
}


add_filter( 'the_content', 'hide_premium_content', 20, 2);

function hide_premium_content($content) {
    if (is_admin()) {
        return $content;
    }
    if( current_user_can('editor') || current_user_can('administrator') ) {
        return $content;
    }
    global $post;
    if ( is_single() ) {
        $tags = wp_get_post_tags( $post->ID, array( 'fields' => 'slugs' ) );
        $role = get_user_role();
        if (
            (!$role || $role == 'subscriber') &&
            is_premium($post->ID)
        ) {
            $message = __('This material is available only by subscription', RUVOD_TEXT_DOMAIN).'<br>';
            // if (is_user_logged_in()) {
            //     $message=$message."Оформить подписку можно в <a href='/account/?tab=settings'>личном кабинете</a>";
            // } else {
            //     $message=$message."<a href='/login'>Войдите</a> для оформления подписки";
            // }
            $message = $message.__('To subscribe, please contact us by <a href="mailto:info@ruvod.com">info@ruvod.com</a>', RUVOD_TEXT_DOMAIN);
            return $message;
        }
    }
    return $content;
}


// убираем отправку скрытых материалов в социалки

add_filter( 'wptelegram_filter_post', 'check_hide_premium', 9);

function check_hide_premium($post) {
    if (is_hide_premium($post->ID)) {
        return false; 
    }
    return $post;
}

add_action( 'save_post', 'check_twitter_premium', 0 );

function check_twitter_premium($post_id) {
    if (is_hide_premium($post_id)) {
        remove_action( 'save_post', 'wpt_twit', 15 );
        remove_action( 'save_post', 'wpt_save_post', 10 );
    }
}

add_action( 'transition_post_status', 'check_vk_cross_post_hide_premium', 0, 3);

function check_vk_cross_post_hide_premium($new_status, $old_status, $post){
    global $wp_filter;
    if ( $new_status !== 'publish' ) {
        return;
    }
    if (is_hide_premium($post->ID)) {
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