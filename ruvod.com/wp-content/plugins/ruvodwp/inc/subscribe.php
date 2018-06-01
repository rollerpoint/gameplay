<?php


function ruvod_subscribe_modal() {
    $form_id =  get_option('ruvod_subscribe_form_id');
    if ($form_id) {
        $form = do_shortcode('[contact-form-7 id="'.$form_id.'"]');
    }
    ob_start();
    ?>
        <div class="modal fade" tabindex="-1" role="dialog" id="subscribe-modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content loader traditional">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">
                            <?php echo __('Subscribe',RUVOD_TEXT_DOMAIN) ?>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <?php 
                        if (!$form_id) {
                            echo __('Set form_id option for shordcode(contact-form-7)',RUVOD_TEXT_DOMAIN);
                        } else {
                            echo $form;
                        }
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary pull-left submit">
                            <?php echo __('Send',RUVOD_TEXT_DOMAIN) ?>
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
function ruvod_subscribe_button() {
    ob_start();
    ?>
         <button id="subscribe-open-dialog" class="subscribe-open-dialog btn btn-xs btn-primary">
            <?php echo __('Subscribe',RUVOD_TEXT_DOMAIN) ?>
         </button>
    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
