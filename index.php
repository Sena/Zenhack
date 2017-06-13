<?php
require 'source/Zenhack.php';
//set your zendesk's subdomain in construction parameter
//$var = new \zh\Zenhack('itau');
//$var = new \zh\Zenhack('pagsegurodev');
$var = new \zh\Zenhack('groupon');

//you may set the user that should write the last comments
$var->filter_author('6018277318');//Ocamoto
$var->filter_author('5989545817');//abraçoS
$var->filter_author('5979349268');//PR
$var->filter_author('114222767674');//Sena

//an your request should be like it.
$post_unread = $var->get_post_unread(array(
    'title',
    'details',
    'comment_count',
    'html_url',
), 100);

include 'view.php';