<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "/third_party/Zenhack/Zenhack.php";

class Zenhack extends \zh\Zenhack
{
    public function __construct($param)
    {
        set_time_limit(-1);
        parent::__construct($param['subdomain']);
    }    

    protected function write_file($text, $name = 'log.txt')
    {
        log_message('info', $text);
    }
}