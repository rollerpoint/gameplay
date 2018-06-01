<div class="company-info single-company single-company-lk">
    
    <div class="row">
        <div class="col-xs-12 col-md-12 ">
            <?php 
            if (get_post_meta($company_id,'wpcf-banner-url',true)) {
                echo "<a href='".get_post_meta($company_id,'wpcf-banner-url',true)."' target='_blank'>";
            }    
            ?>
            <div class="company-top-thumbnail">
                <?php 
                    if (get_post_meta($company_id,'wpcf-top_image',true)) {
                        echo '<img src="'.get_post_meta($company_id,'wpcf-top_image',true).'" alt="">';
                    } else {
                        echo '<img src="'.RUVOD_PLUGIN_DIR.'/assets/images/no-image.jpg" alt="">';
                    }
                ?>
            </div>
            <?php 
            if (get_post_meta($company_id,'wpcf-banner-url',true)) {
                echo "</a>";
            }    
            ?>
        </div>
    </div>
    <div class="hentry company has-thumb">
    <div class="entry-header company-header clearfix">
        <figure class="post-thumbnail">
            
                    
            <?php
            $utility = digezine_utility()->utility;
            $url =  get_post_meta($company_id,'wpcf-site',true);
            if ($url) {
                echo '<a href="'.$url.'" target="_blank">';
            }
            $size = digezine_post_thumbnail_size();
            ?>
            
            <?php 
            if (has_post_thumbnail($company_id)) {
                $utility->media->get_image( array(
                    'size'        => 'digezine-thumb-l',
                    'mobile_size' => 'digezine-thumb-l',
                    'html'        => '<img class="post-thumbnail__img wp-post-image" src="%3$s" alt="%4$s">',
                    'placeholder' => false,
                    'echo'        => true,
                ), 'post', $company_id);
            } else {
                echo '<img class="post-thumbnail__img wp-post-image" src="'.RUVOD_PLUGIN_DIR.'/assets/images/no-image.jpg">';
            }
            

            if ($url) {
                echo '</a>';
            }
            ?>

        </figure>
        <div class="company-content-wrap">
                    <h4 class="entry-title">
                        <?php echo $company->post_title ?>
                        <a href="<?php echo companies_path(array('tab'=>'main', 'action' => 'form')) ?>" class="btn pull-right btn-primary">
                                <?php _e('Change', RUVOD_TEXT_DOMAIN) ?>
                        </a>
                    </h4>
                <?php
                    $tag_ids = wp_get_post_tags( $company_id, array( 'fields' => 'ids' ) );
                    echo '<div class="company-tags-holder">'.get_the_tag_list('<div class="company-tags"><div class="company-tags-title">'.__('Company Profile','digezine').':</div>',', ','</div>').'</div>';
                    ?>
                <div class="company-content">
                    <?php
                        if ($company->post_content) {
                            echo $company->post_content;
                        } else {
                            ?>
                                <div class="text-muted">
                                    <?php _e('The company does not have a description, add it ', RUVOD_TEXT_DOMAIN) ?>
                                    <a href="<?php echo companies_path(array('tab'=>'main', 'action' => 'form')) ?>">
                                        <?php _e('now', RUVOD_TEXT_DOMAIN) ?>
                                    </a>
                                </div>
                            <?php
                        }
                    ?>
                </div>
        </div>

    </div>


    </div>
    
    
</div>