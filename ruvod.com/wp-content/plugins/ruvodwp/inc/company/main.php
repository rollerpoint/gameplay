<?php
$action = $_GET['action'] ? $_GET['action'] : 'form';
$actions = array('form');
if (in_array ( $action , $actions )) {
    include('main/'.$action.'.php');
}