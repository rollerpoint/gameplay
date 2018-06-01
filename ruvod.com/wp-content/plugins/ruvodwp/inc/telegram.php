<?php



add_action('init', 'telegram_custom_query_vars' );
add_action('template_redirect', 'telegram_template_redirect');


function telegram_custom_query_vars(){
    global $wp;
    
    $wp->add_query_var('ruvod_telegram');
    
    add_rewrite_rule('^ruvod_telegram/([^/]*)/?', 'index.php?ruvod_telegram=$matches[1]', 'top');
    
    if( !get_option('ruvod_permalinks_flushed') ) {
        flush_rewrite_rules(false);
        update_option('ruvod_permalinks_flushed', 1);           
    }
}
function telegram_template_redirect() {
    $qv = get_query_var('ruvod_telegram');
    if ($qv) {
        $token = get_option('ruvod_telegram_bot_token');
        if(!empty($token) && $qv == $token){
            error_log('start init bot',0);
            ruvod_init_bot();
            die();
        }
    }
}

function ruvod_init_bot() {
    $bot = new \TelegramBot\Api\Client(get_option('ruvod_telegram_bot_token'));
    $bot->command('ping', function ($message) use ($bot) {
        error_log('pong! your id is: '.$message->getChat()->getId(),0);
        $bot->sendMessage($message->getChat()->getId(), 'pong! your id is: '.$message->getChat()->getId());
    });

    $bot->command('start', function ($message) use ($bot) {
        error_log('started! your id is: '.$message->getChat()->getId(),0);
        $bot->sendMessage($message->getChat()->getId(), 'Приветствую, этот диалог будет использоваться для уведомлений, здесь RUVOD будет сообщать вам о статьях и новостях по вашему профилю.'.chr(10).''.chr(10).'Чтобы получать уведомления, сообщите этот идентификатор вашему контактному лицу из RUVOD: '.$message->getChat()->getId());
    });
    
    // $bot->on(function($Update) use ($bot){
    //     $message = $Update->getMessage();
    //     $mtext = $message->getText();
    //     $cid = $message->getChat()->getId();
    //     $bot->sendMessage($cid,$mtext);
    // });
    // $bot->on(function ($Update) use ($bot) {
    //     $message = $Update->getMessage();
    //     $cid = $message->getChat()->getId();
    //     $bot->sendMessage($cid, 'text');

    // }, function ($message) use ($name) {
    //     return true;
    // });
    $bot->run();
}

add_action( 'transition_post_status', 'ruvod_check_experts_for_telegram_notify', 10, 3);

function ruvod_check_experts_for_telegram_notify($new_status, $old_status, $post) {
	global $wp_filter;
    if ( $old_status == 'publish' || $new_status !== 'publish' || $post->post_type != 'post' ) {
        return;
    }
    if (get_option('ruvod_telegram_bot_token')) {
        $bot = new \TelegramBot\Api\Client(get_option('ruvod_telegram_bot_token'));
        $tag_ids = wp_get_post_tags( $post->ID, array( 'fields' => 'ids' ) );
        $q = array(
            'post_type' => 'expert',
            'numberposts' => 100, 
            'offset' => 0,
            'tax_query' => array(
                array(
                    'taxonomy' => 'post_tag',
                    'field'    => 'id',
                    'terms'    =>  $tag_ids,
                    'operator' => 'IN'
                )
            )
        );
        // TODO отмечать кому отправили
        $experts = get_posts( $q );
        foreach($experts as $expert) {
            ruvod_notify_expert_for_new_post($expert, $post, $bot);
            
        }
    }
	
}
function ruvod_notify_expert_for_new_post($expert, $post, $bot=null) {
    if (!is_object( $expert )) {
        $expert = get_post($expert);
    }
    if (!is_object( $post )) {
        $post = get_post($post);
    }
    if (!$bot) {
        $bot = new \TelegramBot\Api\Client(get_option('ruvod_telegram_bot_token'));
    }
    $chatId = get_post_meta( $expert->ID, 'wpcf-dialog_id' , true );
    if ($chatId) {
        $expert_token = uniqid();
        try {
            add_post_meta($post->ID, 'expert_token_'.$expert_token, $expert->ID,true);
            $bot->sendMessage($chatId, 'Здравствуйте, '.$expert->post_title.'!'.chr(10).''.chr(10).'Наши читатели с нетрепением ждут ваш экспертный комментарий к новому материалу: <a href="'.get_permalink($post->ID).'?expert_token='.$expert_token.'#expert-comment-form">'.get_the_title($post->ID).'</a>.'.chr(10).''.chr(10).'Для отправки комментария пройдите по ссылке выше.', 'HTML', true);
        } catch (Exception $e) {
            error_log('Errro notify expert: '.$expert->id);
            error_log($e->getMessage());
        }

    }
}

add_action( 'before_delete_post', 'ruvod_inc_counter' );
function ruvod_inc_counter( $post_id ){
    global $post_type;   
    $belong_post_id = get_post_meta($post_id, '_wpcf_belongs_post_id', true);
    if ($belong_post_id) {
        $count = get_post_meta($belong_post_id, 'expert_comments_count', true);
        update_post_meta($belong_post_id, 'expert_comments_count', $count-1);
    }
}


add_action( 'admin_post_remove_expert_comment', 'ruvod_remove_expert_comment' );
add_action( 'admin_post_nopriv_remove_expert_comment', 'ruvod_remove_expert_comment' );

function ruvod_remove_expert_comment() {
    $post_id = $_GET['post_id'];
    $expert_id = $_GET['expert_id'];
    $comment_id = $_GET['comment_id'];
    $expert_token = $_GET['expert_token'];
    $post = get_post($post_id);
    $expert = get_post($expert_id);
    $comment = get_post($comment_id);
    if ( !$comment || $expert_id != get_post_meta( $post_id, 'expert_token_'.$expert_token , true ) ) {
        wp_redirect(get_permalink($post).'?expert_token='.$expert_token.'&notify=error&message='.urlencode('Нет прав для данной операции'));
    } else {
        wp_delete_post($comment->ID,true);
        wp_redirect(get_permalink($post).'?expert_token='.$expert_token.'&notify=success&message='.urlencode("Ваш комментарий удален"));
    }
}

add_action('wp_ajax_create_expert_comment', 'ruvod_ajax_create_expert_comment');
add_action('wp_ajax_nopriv_create_expert_comment', 'ruvod_ajax_create_expert_comment');

function ruvod_ajax_create_expert_comment() {
    $post_id = $_POST['post_id'];
    $expert_id = $_POST['expert_id'];
    $expert_token = $_POST['expert_token'];
    $post = get_post($post_id);
    $expert = get_post($expert_id);
    if ($_POST['comment_id']) {
        $comment = get_post($_POST['comment_id']);
		if (!$comment) {
			$ferror = true;
		}
	}
    if ( $expert_id != get_post_meta( $post_id, 'expert_token_'.$expert_token , true ) ) {
        $ferror = true;
    }
    if ($ferror) {
        wp_send_json( array(
            'status' => 'error',
            'message' => 'Нет прав для редактирования данного объекта'
        ));
    }
    $notify_chat = get_option('ruvod_notify_dialog_id');
    $title = esc_attr($_POST['expert_title']);
    $content = esc_attr($_POST['content']);
    $meta_data = array(
        '_wpcf_belongs_post_id' => $post_id,
        '_wpcf_belongs_expert_id' => $expert_id
    );
    $comment_id = wp_insert_post( array(
		'ID' => $comment ? $comment->ID : null,
        'post_title' => $title,
        'post_content' => $content,
		'post_type' => 'expert_comment',
		'meta_input' => $meta_data,
		'post_status' => 'publish'
    ));
    $message = $comment ? 'Экспертный комментарий обновлен' : 'Экспертный комментарий отправлен';
    if (is_wp_error( $comment_id )) {
		wp_send_json( array(
			'status' => 'error',
			'message' => $comment_id->get_error_message()
		));
	} else {
        if (!$comment) {
            $count = get_post_meta($post_id, 'expert_comments_count', true);
            update_post_meta($post_id, 'expert_comments_count', $count+1);
            ruvod_notify_for_new_expert_comment($expert, $post);
        }
        add_post_meta( $post->ID, 'has_expert_comments' , '1', true );
        
        wp_send_json( array(
			'status' => 'ok',
			'message' => false,
            'id' => $comment_id,
			'redirect' => get_permalink($post).'?expert_token='.$expert_token.'&notify=success_modal&message='.urlencode($message)
		));
    }
}

function ruvod_notify_for_new_expert_comment($expert, $post) {
    if (!is_object( $expert )) {
        $expert = get_post($expert);
    }
    if (!is_object( $post )) {
        $post = get_post($post);
    }
    $notify_chat = get_option('ruvod_notify_dialog_id');
    $notify_channel = get_option('ruvod_notify_channel');
    $bot_token = get_option('ruvod_telegram_bot_token');
    if ($bot_token) {
        $bot = new \TelegramBot\Api\Client($bot_token);
        if (get_option('ruvod_telegram_proxy')) {
            $bot->setProxy(get_option('ruvod_telegram_proxy'));
        }
        $male = get_post_meta($expert->ID,'wpcf-sex',true) != 'woman';
        $text = 'ЭКСПЕРТ, '.mb_strtoupper($expert->post_title).' '.($male ? 'ПРОКОММЕНТИРОВАЛ' : 'ПРОКОММЕНТИРОВАЛА').' ПУБЛИКАЦИЮ: <a href="'.get_permalink($post->ID).'#expert-comments">'.mb_strtoupper(get_the_title($post->ID)).'</a>';
        if ($notify_chat) {
            // try {
            //     $bot->sendMessage($notify_chat, $text, 'HTML', true);
            // } catch (Exception $e) {
            //     error_log($e->getMessage());
            // }
        }
        if ($notify_channel) {
            try {
                $bot->sendMessage($notify_channel, $text, 'HTML', true);
            } catch (Exception $e) {
                error_log($e->getMessage());
            }
        }
    }
    
}


add_action( 'transition_post_status', 'ruvod_check_notify_for_company_posts', 10, 3);

function ruvod_check_notify_for_company_posts($new_status, $old_status, $post) {
	global $wp_filter;
    if ( 
        $old_status == $new_status ||
        $new_status !== 'pending' || 
        $post->post_type != 'post' 
    ) {
        return;
    }
    ruvod_notify_for_new_company_post($post);
}
function ruvod_notify_for_new_company_post($post) {
    if (!is_object( $post )) {
        $post = get_post($post);
    }
    $notify_chat = get_option('ruvod_notify_dialog_id');
    $bot_token = get_option('ruvod_telegram_bot_token');
    if ($bot_token && $notify_chat) {
        $bot = new \TelegramBot\Api\Client($bot_token);
        if (get_option('ruvod_telegram_proxy')) {
            $bot->setProxy(get_option('ruvod_telegram_proxy'));
        }
        $company_id = get_post_meta($post->ID,'company_id',true);
        $link = "<a href='".get_edit_post_link($post->ID)."'>".$post->post_title."</a>";
        $text = "Новая публикация от компании ". get_the_title($company_id) ." ожидает проверки: ".$link;
        try {
            $bot->sendMessage($notify_chat, $text, 'HTML', true);
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }

}
function ruvod_expert_comments($post) {
    
    
    if ($_GET['expert_token']) {
        // expert_form
        $expert_token = $_GET['expert_token'];
        $expert_id = get_post_meta( $post->ID, 'expert_token_'.$expert_token , true );
        $expert = get_post($expert_id);
        $success = $_GET['success'];
        if ($expert_id && $expert) {
            $comments = get_posts( 
                array(
                    'post_type' => 'expert_comment',
                    'numberposts' => -1,
                    'meta_query' => array(
                        array(
                            'key' => '_wpcf_belongs_post_id', 
                            'value' => $post->ID
                        ),
                        array(
                            'key' => '_wpcf_belongs_expert_id', 
                            'value' => $expert_id
                        ),
                        'relation' => 'AND'
                    )
                )
            );
            $comment = $comments[0];
            
            if ($_GET['notify'] == 'success_modal') {
                echo '<div class="modal-success hidden">'.($_GET['message'] ? $_GET['message'] : 'Операция выполнена').'</div>';
            } 
            if ($_GET['notify'] == 'success') {
                echo '<div class="alert alert-success on-load-scroll" role="alert">'.($_GET['message'] ? $_GET['message'] : 'Операция выполнена').'</div>';
            } 
            if ($_GET['notify'] == 'error') {
                echo '<div class="alert alert-danger on-load-scroll" role="alert">'.($_GET['message'] ? $_GET['message'] : 'Неизвестная ошибка, обратитесь в техподдержку').'</div>';
            }
            ?>
            
            
            <div class="modal fade" tabindex="-1" role="dialog" id="experCommentModal">
                <div class="modal-dialog" role="document">
                <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">
                                <?php echo $_GET['message'] ? $_GET['message'] : 'Операция выполнена' ?>
                            </h4>
                        </div>
                        <div class="modal-body text-center">
                            <div class="description">
                                Поделитесь этой новостью с вашими друзьями в одной из социальных сетей
                            </div>
                            <div class="modal-share-buttons">
                                <?php digezine_share_buttons( 'single' , array(), array('expert'=>true)); ?>
                            </div>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>
            <div class="form-wrapper">
                <form enctype="multipart/form-data" class="ajax-form has-nested expert-comment-form" id="expert-comment-form"  action="/wp-admin/admin-ajax.php" method="post">
                    <div style="display:none;" class="alert alert-danger" role="alert"></div>
                    <div style="display:none;" class="alert alert-success" role="alert">Успешно</div>
                    <h4>Здравствуйте, <?php echo get_the_title($expert) ?></h4>
        
                    <?php if ($comment) {
                        ?>
                            <p class="text-red">
                                Вы уже оставили комментарий к этой <a href="<?php echo get_permalink($post) ?>">новости</a>. Вы можете изменить или удалить его.
                            </p>
                        <?php
                    } else {
                        ?>
                            <p>
                            Опубликуйте ваш экспертный комментарий к данному материалу.
                            </p>
                        <?php
                    }
                    ?>
                    <input type="hidden" name="action" value="create_expert_comment">
                    <input type="hidden" name="expert_id" value="<?php echo $expert_id ?>">
                    <input type="hidden" name="post_id" value="<?php echo $post->ID ?>">
                    <input type="hidden" name="comment_id" value="<?php echo $comment ? $comment->ID : null ?>" class="object-id">
                    <input type="hidden" name="expert_token" value="<?php echo $expert_token ?>" class="object-id">

                    <div class="row">
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-group">
                                <textarea required name="content" style="resize:none;height:auto;min-height: auto;" rows="6" placeholder=""><?php echo $comment ? $comment->post_content : null ?></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        
                        <div class="col-sm-12 col-xs-12">
                            <button data-confirm="<?php echo $comment ? __('Save comment?', RUVOD_TEXT_DOMAIN ) : __('Send comment?', RUVOD_TEXT_DOMAIN )?>" type="submit" class="btn btn-primary submit">
                                <?php echo $comment ? __('Save', RUVOD_TEXT_DOMAIN ) : __('Send', RUVOD_TEXT_DOMAIN )?>
                            </button>
                            <?php
                                if ($comment) {
                                    ?>
                                    <a onclick='return confirm("<?php echo __('Remove comment?', RUVOD_TEXT_DOMAIN ) ?>");' href="/wp-admin/admin-post.php?action=remove_expert_comment&comment_id=<?php echo $comment->ID; ?>&expert_id=<?php echo $expert->ID; ?>&post_id=<?php echo $post->ID; ?>&expert_token=<?php echo $expert_token; ?>" class="btn btn-danger">
                                        <?php echo __('Remove', RUVOD_TEXT_DOMAIN ) ?>
                                    </a>
                                    <?php
                                }
                            ?>
                            <div class="loader inline loader-mini" style="display:none;">
                                <img src="<?php echo plugins_url('/assets/images/ajax-loader.gif', dirname(__FILE__)); ?>"/>
                            </div>
                        </div>
                    </div>
                    
                    
                </form>
            </div>

            <?php
            comments_list($post, 'КОММЕНТАРИИ ДРУГИХ ЭКСПЕРТОВ:', $comment);
        } else {
            // nothing
        }
    } else {
        comments_list($post, 'КОММЕНТАРИИ ЭКСПЕРТОВ:', null);
    }
}

function comments_list($post, $title, $skip_comment) {
    if (get_post_meta( $post->ID, 'has_expert_comments' , true )) {
        $q = array(
            'post_type' => 'expert_comment',
            'meta_query' => array(
                array(
                    'key' => '_wpcf_belongs_post_id', 
                    'value' => $post->ID
                )
            )
        );
        if ($skip_comment) {
            $q['post__not_in'] = array($skip_comment->ID);
        }
        $query = new WP_Query($q);
        $comments = $query->posts;
        if (count($comments) > 0) {
            echo '<h4 id="expert-comments">'.$title.'</h4>';
            echo '<div class="expert-comments">';
            foreach($comments as $comment) {
                $expert_id = get_post_meta($comment->ID,'_wpcf_belongs_expert_id',true)
                ?>
                    <div class="expert-comments-item clearfix">
                                    <div class="expert-thumbnail pull-left">
                                        <?php
                                            $t = get_the_post_thumbnail($expert_id, 'thumbnail');
                                            if ($t == '') {
                                            ?> 
                                                <img width="150" height="150" src="<?php echo plugins_url('/assets/images/no_avatar.png', dirname(__FILE__)); ?>" class="attachment-thumbnail size-thumbnail wp-post-image" alt="">
                                            <?php
                                            } else {
                                                echo $t;
                                            }
                                        ?>
                                    </div>
                                    <h4 class="entry-title">
                                        <a href="<?php echo get_permalink(get_post($expert_id)) ?>">
                                            <?php echo get_the_title($expert_id) ?>
                                        </a>
                                    </h4>
                                    <div class="content">
                                        <?php echo apply_filters('the_content', $comment->post_content); ?>
                                    </div>
                    </div>
                <?php
            }
            echo '</div>';
        }
    }
}


function ruvod_last_expert_comment( $atts ){
    $comments = wp_get_recent_posts(array(
        'post_type' => 'expert_comment',
        'numberposts' => 1
    ),OBJECT);
    ob_start();
    if ($comments[0]) {
        $comment = $comments[0];
        $expert_id = get_post_meta($comment->ID,'_wpcf_belongs_expert_id',true);
        $post_id = get_post_meta($comment->ID,'_wpcf_belongs_post_id',true);
        ?>
        <div class="last-expert-comment-widget clearfix">
            
            <a href="<?php echo get_permalink(get_post($post_id)) ?>#expert-comments">
                <div class="expert-thumbnail pull-left">
                                        <?php
                                            $t = get_the_post_thumbnail($expert_id, 'thumbnail');
                                            if ($t == '') {
                                            ?> 
                                                <img width="150" height="150" src="<?php echo plugins_url('/assets/images/no_avatar.png', dirname(__FILE__)); ?>" class="attachment-thumbnail size-thumbnail wp-post-image" alt="">
                                            <?php
                                            } else {
                                                echo $t;
                                            }
                                        ?>
                                    </div>
                <h4 class="entry-title">
                    <?php echo get_the_title($expert_id) ?>
                </h4>
                
                <div class="content">
                    <?php echo wp_trim_words($comment->post_content, 25) ?>
                </div>
            </a>
            
        </div>

        <?php
    }
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
add_shortcode( 'expert_comment', 'ruvod_last_expert_comment' );


function ruvod_recalc_expert_comments_counter($expert_id) {
    $mq = array(
        'post_type' => 'expert_comment',
        'date_query' => array(
            array(
                'column' => 'post_date_gmt',
                'after' => '1 month ago'
            )
        ),
        'meta_query' => array(
            array(
                'key' => '_wpcf_belongs_expert_id', 
                'value' => $expert_id
            ),
            'relation' => 'AND'
        )
    );
    $m_query = new WP_Query( $mq );
    add_post_meta($post->ID, 'day_expert_comments_count'.$expert_token, $expert->ID,true);
}


function ruvod_experts_need_sort() {
    return is_post_type_archive(array( 'expert' )) && !is_admin();
}


// join expert comments
function ruvod_join_comments_to_experts($join) {
    global $wp_query, $wpdb;
    if (ruvod_experts_need_sort()) {
        $join .= "  LEFT JOIN $wpdb->postmeta meta ON $wpdb->posts.ID = meta.meta_value AND 
                                                    meta.meta_key = \"_wpcf_belongs_expert_id\"
                    LEFT JOIN $wpdb->posts commented_posts ON commented_posts.ID = meta.post_id AND (DATE_SUB(NOW(), INTERVAL 1 MONTH)) < commented_posts.post_date";
    }
    // echo $join;
    return $join;
}

add_filter('posts_join', 'ruvod_join_comments_to_experts');

function ruvod_fields_in_experts($fields) {
    global $wp_query, $wpdb;
    if (ruvod_experts_need_sort()) {
        $fields="
            $wpdb->posts.ID,
            $wpdb->posts.post_date,
            $wpdb->posts.post_content,
            $wpdb->posts.post_title,
            $wpdb->posts.post_excerpt,
            $wpdb->posts.post_status,
            $wpdb->posts.post_name,
            $wpdb->posts.post_type, 
            COUNT(commented_posts.ID) day_comments_count";
    }
    // echo $fields;
    return $fields;
}

add_filter('posts_fields', 'ruvod_fields_in_experts');

function ruvod_group_in_experts($groupby) {
    global $wp_query, $wpdb;
    if (ruvod_experts_need_sort()) {
        $groupby .="
            $wpdb->posts.ID,
            $wpdb->posts.post_date,
            $wpdb->posts.post_content,
            $wpdb->posts.post_title,
            $wpdb->posts.post_excerpt,
            $wpdb->posts.post_status,
            $wpdb->posts.post_name,
            $wpdb->posts.post_type
        ";
    }
    return $groupby;
}
add_filter( 'posts_groupby', 'ruvod_group_in_experts' );

function ruvod_orderby_experts($orderby) {
    global $wp_query, $wpdb;
    if (ruvod_experts_need_sort()) {
        $orderby = "day_comments_count DESC, wp_posts.ID DESC";
    }
    return $orderby;
}

add_filter( 'posts_orderby', 'ruvod_orderby_experts' );