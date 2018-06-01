<?php

add_action('wp_ajax_blogs_feedback', 'ruvod_ajax_blogs_feedback');
add_action('wp_ajax_nopriv_blogs_feedback', 'ruvod_ajax_blogs_feedback');


function ruvod_ajax_blogs_feedback() {
    $admin = get_option('admin_email');
    $email = $_POST['email'];
    $headers = array('Content-Type: text/html; charset=UTF-8');
    $headers[] = 'From: ' . get_option('blogname').' <'.get_option('admin_email').'>';
    $headers[] = 'Reply-To: ' . $email;
    $message = "Отклик с посадочной страницы блогов компаний";
    $message.="<br>";
    $message.="Email: ".$email;
    wp_mail($admin, "Обратная связь RUVOD", $message, $headers);
    wp_send_json( array(
        'status' => 'ok',
        'message' => 'Спасибо, мы свяжемся с вами в ближайшее время'
    ));
}