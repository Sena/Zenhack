<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_User_forcechange extends CI_Migration {

    public function up()
    {
        $fields = array(
            'forcechange' => array(
                'type' => 'INT',
                'constraint' => 1,
                'default' => 1,
            )
        );
        $this->dbforge->add_column('user', $fields);

    }

    public function down()
    {
        $this->dbforge->drop_column('user', 'forcechange');
    }
}