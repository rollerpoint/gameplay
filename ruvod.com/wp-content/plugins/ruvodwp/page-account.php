<?php /* Template Name: Шаблон ЛК */ ?>


<div class="account-page">
  <?php 
    global $current_user;
    $user = $current_user;
    $subscribe_end = get_user_meta($current_user->ID,'subscribe_end',true);
    if ($subscribe_end) {
      $subscribe_end = new DateTime( '@'.$subscribe_end );
      if ($subscribe_end > new DateTime('NOW')) {
        $active_subscribe = true;
      }
    }
    $company_id = get_the_author_meta( 'company_id', $user->ID );
    $company  = $company_id ? get_post( $company_id, OBJECT ) : null;
    $tabs = array(
        'main' => __('Account', RUVOD_TEXT_DOMAIN),
        //'settings' => __('Subscription', RUVOD_TEXT_DOMAIN),
        'profile' => __('Profile CV', RUVOD_TEXT_DOMAIN),
        //'vakancies' => 'Вакансии'
    );
    $tabs_main_actions = array(
        'main' => 'view',
        'cvs' => 'list'
    );
    $action = $_GET['tab'] ? $_GET['tab'] : 'main';
    $tab_action = $_GET['action'] ? $_GET['action'] : $tabs_main_actions[$action];
    if (can_manage_subscibers()) {
      //$tabs['email_subscribers'] = __('Email subscribers', RUVOD_TEXT_DOMAIN);
    }
    echo '<ul class="nav nav-tabs">';
    foreach ($tabs as $key => $value) {
      echo '<li class="'.($action == $key && $tab_action == $tabs_main_actions[$action] ? 'active' : '').'"><a href="'.account_path(array('tab' => $key)).'">'.$value.'</a></li>';
      
    }
    echo '</ul>';
    
    ?>
  
  <div class="tab-content">
    <?php include('inc/account/'.$action.'.php'); ?>
  </div>
</div>