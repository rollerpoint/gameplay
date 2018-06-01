<?php
$action = $_GET['action'] ? $_GET['action'] : 'list';
$actions = array('list');
if (in_array ( $action , $actions )) {
    include('members/'.$action.'.php');
}

