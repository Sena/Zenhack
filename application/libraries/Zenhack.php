<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "/third_party/Zenhack/Zenhack.php";

class Zenhack extends \zh\Zenhack
{
    private $ci;

    public function __construct($param)
    {
        set_time_limit(-1);
        parent::__construct($param['subdomain']);
        $this->ci =& get_instance();        
    }    

    protected function write_file($text, $name = 'log.txt')
    {
        log_message('info', $text);
    }

    protected function unset_store($key) {
        $this->ci->session->unset_userdata($key);
    }

    protected function store($key, $value = null)
    {
        if($value === null) {
            return $this->ci->session->userdata($key);
        }
        $this->ci->session->set_userdata($key, $value);
    }
}