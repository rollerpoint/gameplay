<?php

add_action('init', 'ruvod_rambler_rss');
function ruvod_rambler_rss(){
    add_feed('rambler', 'render_ruvod_rambler_rss');
}
function render_ruvod_rambler_rss(){
    include(__DIR__.'/../rambler-feed.php');
}
