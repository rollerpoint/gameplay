<?php


function mooscle_custom_wp_enqueue_scripts($hook) {
    $uri = get_stylesheet_directory_uri();
    $dir = get_stylesheet_directory();
	//CUSTOM STYLES
    $styles_path = $dir . '/assets/css/mooscle.css';

	wp_enqueue_style('mooscle_custom_styles', $uri . '/assets/css/mooscle.css', array('digezine-theme-style','custom_styles'), filemtime( $styles_path ) );
	
	$scripts_path =  $dir . '/assets/js/mooscle.js';
	wp_enqueue_script('mooscle_custom_scripts', $uri . '/assets/js/mooscle.js', array( 'jquery' ), filemtime( $scripts_path ) );
}

add_action( 'wp_enqueue_scripts', 'mooscle_custom_wp_enqueue_scripts' );

function mooscle_ruvod_last_expert_comment( $atts ){
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
                <div class="content">
                    <?php echo wp_trim_words($comment->post_content, 25) ?>
				</div>
				<div class="expert-thumbnail pull-right">
					<?php
						$t = get_the_post_thumbnail($expert_id, 'thumbnail');
						if ($t == '') {
						?> 
							<img width="150" height="150" src="<?php echo get_stylesheet_directory_uri().'/assets/images/no_avatar.png'; ?>" class="attachment-thumbnail size-thumbnail wp-post-image" alt="">
						<?php
						} else {
							echo $t;
						}
					?>
				</div>
				<h4 class="entry-title">
                    <?php echo get_the_title($expert_id) ?>
				</h4>
				<div "expert-desc">
					<?php
					echo wp_trim_words(get_post_meta($expert_id,'wpcf-description',true),15);
					?>
				</div>
            </a>
            
        </div>

        <?php
    }
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
add_shortcode( 'expert_comment', 'mooscle_ruvod_last_expert_comment' );

remove_filter('the_title','ruvod_uppercase_title');
remove_filter('wptelegram_post_title','ruvod_uppercase_title');
remove_filter('aioseop_title', 'ruvod_uppercase_title');
remove_filter('wpt_post_info','ruvod_wpt_post_info');