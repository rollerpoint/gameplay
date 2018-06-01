<?php

add_action( 'wp_enqueue_scripts', 'ruvod_rumor_enqueue_scripts' );


function ruvod_rumor_enqueue_scripts() {
    $scripts_path = RUVOD_PLUGIN_DIRNAME . '/assets/rumor.js';
    wp_enqueue_script('ruvod_rumor', RUVOD_PLUGIN_DIR . 'assets/rumor.js', array( 'jquery' ), filemtime( $scripts_path ) );
}

function ruvod_rumor_modal() {
    $form_id =  get_option('ruvod_rumor_form_id');
    if ($form_id) {
        $form = do_shortcode('[contact-form-7 id="'.$form_id.'"]');
    }
    ob_start();
    ?>
        <div class="modal fade" tabindex="-1" role="dialog" id="rumor-modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content loader traditional">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">
                            <?php echo __('Share rumors anonymously',RUVOD_TEXT_DOMAIN) ?>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <?php 
                        if (!$form_id) {
                            echo __('Set form_id option in plugin settings',RUVOD_TEXT_DOMAIN);
                        } else {
                            echo $form;
                        }
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary pull-left submit">
                            <?php echo __('Send',RUVOD_TEXT_DOMAIN) ?>
                        </button>
                        <div class="inline info pull-left">
                            <?php echo __('100% anonymous',RUVOD_TEXT_DOMAIN) ?>
                        </div>
                        <div class="inline info info-file">
                            
                        </div>
                        <button type="button" title="<?php echo __('Add file',RUVOD_TEXT_DOMAIN) ?>" class="btn btn-file btn-secondary">
                            <i class="icon icon-paper-clip"></i>
                        </button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
function ruvod_rumor_button() {
    ob_start();
    ?>
         <div class="rumor-open-dialog-wrap">
            <button type="submit" href="#" id="rumor-open-dialog" class="rumor-open-dialog btn btn-xs btn-danger">
                <?php echo __('Share rumors',RUVOD_TEXT_DOMAIN) ?>
            </button>
         </div>
    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
