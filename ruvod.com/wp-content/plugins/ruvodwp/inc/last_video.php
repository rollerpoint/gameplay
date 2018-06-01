<?php
function ruvod_last_video( $atts ){
    $videos = wp_get_recent_posts(array(
        'post_type' => 'video',
        'numberposts' => 1
    ),OBJECT);
    ob_start();
    if ($videos[0]) {
        $width = 100;
        $height = 100;
        $video = $videos[0];
        $post_content = $video->post_content;
        $content = apply_filters( 'the_content', $post_content );
        $types   = array( 'video', 'object', 'embed', 'iframe' );
        $embeds  = get_media_embedded_in_content( $content, $types );
        $types   = array( 'video', 'object', 'embed', 'iframe' );
        if ( has_shortcode( $post_content, 'video' ) ) {
            $result_format = '%s';
        } else {
            $result_format = '<div class="entry-video ">%s</div>';
        }
        foreach ( $types as $tag ) {
            if ( preg_match( "/<{$tag}[^>]*>(.*?)<\/{$tag}>/", $embeds[0], $matches ) ) {
                $result = $matches[0];
                break;
            }
        }
        if ( false === $result ) {
            return '';
        }
        $regex = array(
            '/width=[\'\"](\d+)[\'\"]/',
            '/height=[\'\"](\d+)[\'\"]/',
        );

        $replace = array(
            'width="' . $width . '"',
            'height="' . $height . '"',
        );

        $result = preg_replace( $regex, $replace, $result );
        $result = sprintf( $result_format, $result );
        ?>
        <div class="last-video-widget clearfix">
            <div class="video-wrap">
                <?php echo $result ?>
            </div>
        </div>

        <?php
    }
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
add_shortcode( 'last_video', 'ruvod_last_video' );