<div class="account-main">

  <?php
  $action = $_GET['action'] ? $_GET['action'] : 'view';
  $actions = array('view','form');
  $cv_id = get_user_meta($user->ID,'cv_id',true);
  if (!$cv_id && $action == 'view') {
    $action = 'form';
  }
  if (in_array ( $action , $actions )) {
      include('profile/'.$action.'.php');
  }
 ?>
</div>