<?php
$action = $_GET['action'] ? $_GET['action'] : 'list';
$actions = array('list','form');
if (in_array ( $action , $actions )) {
    include('blog/'.$action.'.php');
}

