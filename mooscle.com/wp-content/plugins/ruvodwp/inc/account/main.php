<div class="account-main">

  <?php
  $action = $_GET['action'] ? $_GET['action'] : 'view';
  $actions = array('view','edit','change_password');
  if (in_array ( $action , $actions )) {
      include('main/'.$action.'.php');
  }
 ?>
</div>