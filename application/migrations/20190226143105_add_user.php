<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_user extends CI_Migration
{
    public function up()
    {
        $this->dbforge->add_field(array(
            'name' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'email' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'password' => array(
                'type' => 'VARCHAR',
                'constraint' => '32',
            ),
        ));
        $this->dbforge->create_table('user');
        $this->dbforge->add_column('user', "`id` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`)");
        $this->db->insert('user', array(
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => md5('admin'),
        ));
    }

    public function down()
    {
        $this->dbforge->drop_table('user');
    }
}