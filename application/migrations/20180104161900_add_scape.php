<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_scape extends CI_Migration {

        public function up()
        {
                $this->dbforge->add_field(array(
                        'hash' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '32',
                        ),
                        'dump' => array(
                                'type' => 'longtext',
                        ),
                ));
                $this->dbforge->create_table('scape');
        }

        public function down()
        {
                $this->dbforge->drop_table('scape');
        }
}