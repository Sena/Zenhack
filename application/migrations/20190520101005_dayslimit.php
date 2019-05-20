<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Dayslimit extends CI_Migration
{
    public $table = 'setting';

    public function up()
    {
        $this->db->insert($this->table, array(
            'key' => 'dayslimit',
            'label' => 'Verificar posts de até: Limite em dias',
            'value' => 7,
            'required' => 1,
        ));

    }

    public function down()
    {
        $this->db->delete($this->table, array(
            'key' => 'dayslimit',
        ));
    }
}