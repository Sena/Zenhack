<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_User_scape extends CI_Migration {

    public function up()
    {
        $fields = array(
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
            )
        );
        $this->dbforge->add_column('scape', $fields);

    }

    public function down()
    {
        $this->dbforge->drop_column('scape', 'id');
    }
}