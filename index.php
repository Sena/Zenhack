<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
set_time_limit(-1);
date_default_timezone_set('America/Sao_Paulo');

require 'source/Zenhack.php';
//set your zendesk's subdomain in construction parameter
//$var = new \zh\Zenhack('itau');
$var = new \zh\Zenhack('pagsegurodev');
//$var = new \zh\Zenhack('groupon');

$var->log_active();
//you may set the user that should write the last comments
$var->filter_author('6018311238');
$var->filter_author('13468838768');
$var->filter_author('6018277318');
$var->filter_author('5989545817');
$var->filter_author('5979349268');
$var->filter_author('114222767674');
$var->filter_author('114314801294');


//an your request should be like it.
$post_unread = $var->get_post_unread(array(
    'title',
    'details',
    'comment_count',
    'html_url',
    'vote_sum',
    'updated_at',
), 200);

foreach ($post_unread as $key => $row) {
    $row->br_updated_at = date('d-m-Y H:i:s', strtotime($row->updated_at));
    $row->br_updated_at = date('d-m-Y H:i:s', strtotime($row->updated_at));
    $row->dead = date('Y-m-d H:i:s', strtotime("+30 days")) <= strtotime($row->updated_at);
    $post_unread[$key] = $row;
}

include 'view.php';