<?php
$action = $_GET['action'] ? $_GET['action'] : 'view';
$actions = array('view','form');
if (in_array ( $action , $actions )) {
    include('main/'.$action.'.php');
}