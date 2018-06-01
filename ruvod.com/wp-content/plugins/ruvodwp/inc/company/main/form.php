<?php 
$data = get_post_meta($company->ID);
foreach ( $data  as $key => $value ) {
    if ((is_string($value[0]) || !$value[0] || $value[0] == '')) {
    $data[$key] = $value[0];
    }
}
$company_tags = array();
foreach (wp_get_post_tags($company_id, array('fields' =>'all')) as $key => $tag) {
    if (
        ( $tag->slug != get_post_meta($company_id,'wpcf-main_tag_slug',true) ) &&
        ( $tag->slug != 'blogs' )
    ) {
        $company_tags[]=$tag->term_id;
    }
}

?>
<div class="edit-my-company">
    
    <form class="ajax-form company-form company-blog-form" id="company-form" action="/wp-admin/admin-ajax.php" method="post">
        <div style="display:none;" class="alert alert-danger" role="alert"></div>
        <div style="display:none;" class="alert alert-success" role="alert"></div>
        <input type="hidden" name="action" value="update_company">
        <input type="hidden" name="company_id" value="<?php echo $company_id ?>">
        
        <div class="row">
            <div class="col-sm-12 company-top-thumbnail-wrap">
                <div class="wp-chose-image" data-size="full">
                    <div class="wp-chose-image-thumbnail company-top-thumbnail">
                        <?php
                            $url = RUVOD_PLUGIN_DIR.'/assets/images/no-image.jpg';
                            if ($data['wpcf-top_image']) {
                                $url = $data['wpcf-top_image'];
                            }
                            echo '<img src="'.$url.'" alt="">';
                        ?>
                        
                        <div class="action-info" data-change-text="<?php _e('Change', RUVOD_TEXT_DOMAIN) ?>">
                            <?php echo $data['wpcf-top_image'] ? __('Change', RUVOD_TEXT_DOMAIN) : __('Add', RUVOD_TEXT_DOMAIN) ?>
                            <div class="desc">
                                <?php _e('1440 x 250', RUVOD_TEXT_DOMAIN) ?>
                            </div>
                        </div>
                    </div>
                    <button type="button" title="<?php _e('Select file', RUVOD_TEXT_DOMAIN) ?>" class="btn btn-file btn-secondary hidden">
                        <i class="icon icon-paper-clip"></i>
                    </button>
                    <span class="small muted hidden">
                        <?php echo $data['wpcf-top_image'] ? __('Change', RUVOD_TEXT_DOMAIN) : __('Set top image', RUVOD_TEXT_DOMAIN) ?>
                    </span>
                    <input type="hidden" name="top_image_id" value="">
                </div>
            </div>
            <div class="col-sm-4  col-xs-12 form-file-wrap">
                
            </div>
            <div class="col-sm-12">
                <div class="wp-chose-image pull-left" data-min="400x400" data-size="full">
                    <div class="wp-chose-image-thumbnail company-thumbnail">
                        <?php 
                        if ($company_id && has_post_thumbnail($company_id)) {
                            echo get_the_post_thumbnail( $company_id, 'full' );
                        } else {
                            echo '<img src="'.RUVOD_PLUGIN_DIR.'/assets/images/no-image.jpg'.'" alt="">';
                        } ?>
                        <div class="action-info" data-change-text="<?php _e('Change', RUVOD_TEXT_DOMAIN) ?>">
                            <?php echo has_post_thumbnail($company_id) ? __('Change', RUVOD_TEXT_DOMAIN) : __('Add', RUVOD_TEXT_DOMAIN) ?>
                            <div class="desc">
                                <?php _e('400 x 400', RUVOD_TEXT_DOMAIN) ?>
                            </div>
                        </div>
                    </div>
                    <button type="button" title="<?php _e('Select file', RUVOD_TEXT_DOMAIN) ?>" class="btn btn-file btn-secondary hidden">
                        <i class="icon icon-paper-clip"></i>
                    </button>
                    <span class="small muted hidden">
                        <?php echo $post ? __('Change', RUVOD_TEXT_DOMAIN) : __('Add', RUVOD_TEXT_DOMAIN) ?>
                    </span>
                    <input type="hidden" name="attachment_id" value="<?php echo get_post_thumbnail_id($company_id)?>">
                </div>
                <div class="float-image-sibling">
                    
                    <div class="form-group">
                        <label><?php _e('Banner url', RUVOD_TEXT_DOMAIN) ?></label>
                        <input type="url" class="form-control" value="<?php echo $data['wpcf-banner-url'] ?>" name="wpcf-banner-url">
                    </div>
                    <div class="form-group">
                        <label><?php _e('Profile', RUVOD_TEXT_DOMAIN) ?></label>
                        <select data-no-results-text="<?php _e('No results match', RUVOD_TEXT_DOMAIN) ?>" name="post_tags[]" id="post_tags[]" class="chosen form-control" multiple data-placeholder="<?php _e('Select multiple tags', RUVOD_TEXT_DOMAIN) ?>">
                            <?php 
                                $tags = get_tags(); 
                                foreach ($tags as $key => $tag) {
                                    echo '<option value="'.$tag->slug.'" '.(in_array($tag->term_id, $company_tags) ? 'selected' : '').'>'.$tag->name.'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><?php _e('Description', RUVOD_TEXT_DOMAIN) ?></label>
                        <textarea name="post_content" cols="30" rows="6" class="form-control"><?php echo $company->post_content ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary submit">
                        <?php _e('Save', RUVOD_TEXT_DOMAIN) ?>
                    </button>
                    <div class="loader inline loader-mini" style="display:none;">
                        <img src="<?php echo RUVOD_PLUGIN_DIR.'/assets/images/ajax-loader.gif'; ?>"/>
                        <span><?php _e('Loading', RUVOD_TEXT_DOMAIN) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>