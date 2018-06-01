<?php





add_action( 'transition_post_status', 'ruvod_send_task_to_translate', 10, 3);

function ruvod_send_task_to_translate($new_status, $old_status, $post) {
	global $wp_filter;
    if ( $old_status == 'publish' || $new_status !== 'publish' || $post->post_type != 'post' ) {
        return;
    }
    if (get_option('ruvod_trello_task_translate_email') && get_post_meta( $post->ID, 'wpcf-translate' , true )) {
        $title = 'Перевод статьи '.$post->post_title;
        $message = get_permalink($post);
        wp_mail( get_option('ruvod_trello_task_translate_email'), $title, $message);
    }
	
}