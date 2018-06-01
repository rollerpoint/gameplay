<div class="account-settings">
  <?php 
  global $current_user;
  $user = get_userdata($current_user->ID);
  $action = $_GET['action'] ? $_GET['action'] : 'view';
  if ($action == 'payment') { ?>
    <h5>
      <?php echo $active ? __('Subscription renewal') : __('Subscription Formation', RUVOD_TEXT_DOMAIN) ?>
    </h5>
    <div class="row subscribe-payment">
      <div class="col-xs-12">
        <div class="form-group">
            <label style="display:block;" for="current_password"><?php _e('Specify duration', RUVOD_TEXT_DOMAIN) ?></label>
            <?php
              $subscribies = array(
                'one_month' => array(
                  'label' => __('Month', RUVOD_TEXT_DOMAIN),
                  'cost' => get_option('ruvod_subscribe_cost_one_month')
                ),
                'three_month' => array(
                  'label' => __('Three months', RUVOD_TEXT_DOMAIN),
                  'cost' => get_option('ruvod_subscribe_cost_three_month')
                ),
                'half_year' => array(
                  'label' => __('Half a year', RUVOD_TEXT_DOMAIN),
                  'cost' => get_option('ruvod_subscribe_cost_half_year')
                ),
                'year' => array(
                  'label' =>  __('Year', RUVOD_TEXT_DOMAIN),
                  'cost' => get_option('ruvod_subscribe_cost_year')
                ),
              );
              $subscribies = array_filter($subscribies,function($val) {
                return $val['cost'];
              });
            ?>
            <div class="block">
              <select class="form-control subscribe-length" style="width:200px;">
                <?php
                  foreach ($subscribies as $code => $data) {
                    $p = plural_form((int) $data['cost'], array('рубль', 'рубля', 'рублей'));  
                    echo '<option data-cost="'.$data['cost'].'" value="'.$code.'">'.$data['label'].' ('.$data['cost'].' '.$p.')</option>';
                  }
                ?>
              </select>
            </div>
            <div class="block">
              <label class="" style="margin-left:10px;margin-top: 6px;">
                <input type="checkbox" value="1" name="apply_terms" id="apply-terms">
                <?php _e('By making payment you agree with', RUVOD_TEXT_DOMAIN) ?> 
                <?php
                  $terms_url = get_polylang_path(get_option('ruvod_subscribe_term_url'));
                ?>
                <a href="<?php echo $terms_url ?>" target="_blank"><?php _e('subscription terms', RUVOD_TEXT_DOMAIN) ?> </a>
              </label>
            </div>
            <form method="POST" class="" action="https://<?php if (get_option('ruvod_yandex_demo') == 1): ?>demo<?php endif; ?>money.yandex.ru/quickpay/confirm.xml">
                <input type="hidden" name="receiver" value="<?php echo get_option('ruvod_yandex_receiver') ?>">
                <input type="hidden" data-user-id="<?php echo $current_user->ID ?>" name="label" id="payment-label">
                <input type="hidden" name="quickpay-form" value="shop">
                <input type="hidden" name="targets" value="Оплата подписки">
                <input type="hidden" name="sum" value="" id="payment-cost" data-type="number">
                <input type="hidden" name="need-fio" value="false">
                <input type="hidden" name="need-email" value="false">
                <input type="hidden" name="need-phone" value="false">
                <input type="hidden" name="need-address" value="false">
                <input type="hidden" name="paymentType" value="AC">
                <input type="hidden" name="successURL" value="<?php echo home_url( '/account/?tab=settings' ) ?>">
                <input type="submit" id="payment-button" class="btn btn-default" value="<?php _e('Pay by card', RUVOD_TEXT_DOMAIN) ?>">
            </form>
        </div>
        
      </div>
      <div class="col-xs-6">
        
      </div>
    </div>
  <?php } else { ?>

    <h5>
       <?php _e('Subscription management', RUVOD_TEXT_DOMAIN) ?>
    </h5>
    <div class="row block">
      <div class="col-xs-12">
        <h6 class="sub-title">
          <?php _e('Account Type', RUVOD_TEXT_DOMAIN) ?>
        </h6>
      </div>
      <div class="col-xs-12">
      <?php if ($active_subscribe) { ?>
        <div class="inline">
          <strong>
            <?php _e('Premium', RUVOD_TEXT_DOMAIN) ?>
          </strong>
          <br>
          <small>
            <?php _e('Valid until', RUVOD_TEXT_DOMAIN) ?> <?php echo $subscribe_end->format('d.m.Y'); ?>
          </small>
        </div>
        <a href="<?php account_path() ?>?tab=settings&action=payment" class="btn pull-right  btn-primary">
          <?php _e('Extend', RUVOD_TEXT_DOMAIN) ?>
        </a>
        
      <?php } else { ?>
        <div class="inline">
          <?php _e('Simple account', RUVOD_TEXT_DOMAIN) ?>
          <a onclick="return false;" data-toggle="popover" data-placement="top" data-trigger="hover" data-content="<?php _e('A simple account has limited access to portal materials', RUVOD_TEXT_DOMAIN) ?>" href="#">(?)</a>
        </div>
        <a href="/account?tab=settings&action=payment" class="btn pull-right  btn-primary">
          <?php _e('Upgrade', RUVOD_TEXT_DOMAIN) ?>
        </a>
      <?php } ?>
      </div>
    </div>
  <?php } ?>
</div>
