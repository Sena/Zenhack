<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Scape_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get(array $where = array(), $limit = null)
    {
        $this->db->order_by('date', 'DESC');
        return parent::get($where, $limit);
    }
}