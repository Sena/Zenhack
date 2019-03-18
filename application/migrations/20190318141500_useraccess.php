<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Useraccess extends CI_Migration
{
    public $table = 'useraccess';

    public function up()
    {
        $this->dbforge->add_field('id');
        $this->dbforge->add_field(array(
            'user_id' => array(
                'type' => 'INT',
                'constraint' => '11',
            ),
            'uri' => array(
                'type' => 'TEXT',
            )
        ));
        $this->dbforge->add_field("`date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");
        $this->dbforge->create_table($this->table);
    }

    public function down()
    {
        $this->dbforge->drop_table($this->table);
    }
}