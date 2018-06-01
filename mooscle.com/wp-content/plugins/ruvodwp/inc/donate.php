<?php

$ruvod_donate_modal_rendered = false;
function ruvod_donate_button() {
    $button = '
    <button type="submit" href="#" class="donate-open-dialog uppercase btn btn-xs btn-primary">
        '.__('Donate',RUVOD_TEXT_DOMAIN).'
    </button>
    ';

    return $button;    
}

function ruvod_donate_modal() {
    global $wp; 
    ob_start();
        ?>
        <div class="modal fade" tabindex="-1" role="dialog" id="donateModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content loader traditional">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">
                            <?php echo __('Donate',RUVOD_TEXT_DOMAIN) ?>
                        </h4>
                    </div>
                    <div class="modal-body">
                        <form method="POST" class="" action="https://<?php if (get_option('ruvod_yandex_demo') == 1): ?>demo<?php endif; ?>money.yandex.ru/quickpay/confirm.xml">
                            <input type="hidden" name="receiver" value="<?php echo get_option('ruvod_yandex_receiver') ?>">
                            <input type="hidden" name="quickpay-form" value="shop">
                            <input type="hidden" name="targets" value="Поддержка RUVOD">
                            
                            <input type="hidden" name="need-fio" value="false">
                            <input type="hidden" name="need-email" value="false">
                            <input type="hidden" name="need-phone" value="false">
                            <input type="hidden" name="need-address" value="false">
                            <input type="hidden" name="paymentType" value="AC">
                            <input type="hidden" name="successURL" value="<?php echo home_url( $wp->request ) ?>">
                            <input type="submit" id="payment-button" class="btn submit btn-default hidden" value="<?php _e('Pay by card', RUVOD_TEXT_DOMAIN) ?>">
                            <div class="form-group">
                                <label for="">
                                    <small>
                                        <?php echo __('Specify the amount. Your funds will be used to develop the resource.',RUVOD_TEXT_DOMAIN) ?>
                                    </small>
                                </label>
                                <div class="money-input">
                                    <input type="number" class="form-control money" reqired name="sum" value="<?php echo get_option('ruvod_yandex_default_donate') ?>" data-type="number">
                                </div>                     
                            </div>
                        </form>
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
    $modal = ob_get_contents();
    ob_end_clean();
    echo  $modal;
}