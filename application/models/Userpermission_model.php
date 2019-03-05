<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Userpermission_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get(array $where = array(), $limit = null)
    {
        $this->db->join('permission', $this->getAlias() . '.permission_id = permission.id');
        return parent::get($where, $limit);
    }
}