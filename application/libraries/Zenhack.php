<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH . "/third_party/Zenhack/Zenhack.php";

class Zenhack extends \zh\Zenhack
{
    private $ci;
    private $use_db = true;

    public function __construct($param)
    {
        set_time_limit(-1);

        $param['log'] = isset($param['log']) ? $param['log'] : false;
        $this->use_db = isset($param['use_db']) ? $param['use_db'] : false;

        parent::__construct($param['subdomain'], $param['log']);
        $this->ci =& get_instance();

        $this->check_database();
        
    }    

    private function check_database()
    {
        $use_db = $this->use_db;
        $this->use_db = false;

        if($use_db === true) {
            $this->log('The class was set to use database!');

            if(isset($this->ci->db->conn_id)) {
                $this->log('The database libary was loaded!');
                
                if($this->ci->db->conn_id !== false) {
                    $this->log('The database is ready to use!');
                    
                    $this->use_db = true;

                }else{
                    $this->log('The database is\'nt ready to use!');                    
                }
            }else{
                $this->log('The database libary was\'nt loaded!');
            }

        }else{
            $this->log('The system is set to don\'t use database;');
        }
    }

    protected function write_file($text, $name = 'log.txt')
    {
        log_message('info', $text);
    }

    protected function unset_store($key) {
        if($this->use_db){
            $this->ci->load->model('zdb_model');
            $this->ci->zdb_model->delete(array('k' => $key));
        }else{
            $this->ci->session->unset_userdata($key);
        }
    }

    protected function store($key, $value = null)
    {

        if($value === null) {
            if($this->use_db){
                $this->ci->load->model('zdb_model');
                $data = $this->ci->zdb_model->get(array('k' => $key))->result();
                if(count($data) === 0) {
                    return null;
                }
                $data = current($data);
                $data = unserialize($data->v);
                return $data;
            }else{
                return $this->ci->session->userdata($key);
            }
        }        
        
        if($this->use_db){
            $this->ci->load->model('zdb_model');
            $this->ci->zdb_model->delete(array('k' => $key));
            $this->ci->zdb_model->insert(array(
                'k' => $key,
                'v' => serialize($value),
            ));
        }else{
            $this->ci->session->set_userdata($key, $value);
        }
    }
}