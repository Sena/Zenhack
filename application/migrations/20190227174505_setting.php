<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Setting extends CI_Migration
{
    public $table = 'setting';
    public function up()
    {
        $this->dbforge->add_field('id');
        $this->dbforge->add_field(array(
            'key' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'label' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'value' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => TRUE,
            ),
            'required' => array(
                'type' => 'int',
                'constraint' => '1',
                'default' => 0,
            ),
        ));
        $this->dbforge->create_table($this->table);


        $this->db->insert($this->table, array(
            'key' => 'subdomain',
            'label' => 'Subdominio do fórum',
            'required' => 1,
        ));

        $this->db->insert($this->table, array(
            'key' => 'sladealdate',
            'label' => 'Contar SLA apartir do dia',
            'value' => date('Y-m-d'),
        ));

        $this->db->insert($this->table, array(
            'key' => 'slagoal',
            'label' => 'Meta de SLA',
            'value' => 1,
            'required' => 1,
        ));

        $this->db->insert($this->table, array(
            'key' => 'slareegular',
            'label' => 'SLA aceito como regular',
            'value' => 24,
            'required' => 1,
        ));

        $this->db->insert($this->table, array(
            'key' => 'slabad',
            'label' => 'SLA em estado crítico',
            'value' => 48,
            'required' => 1,
        ));
    }

    public function down()
    {
        $this->dbforge->drop_table($this->table);
    }
}