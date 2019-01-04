<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_zdb extends CI_Migration {

        public function up()
        {
                $this->dbforge->add_field(array(
                        'k' => array(
                                'type' => 'VARCHAR',
                                'constraint' => '255',
                        ),
                        'v' => array(
                                'type' => 'longtext',
                        ),
                ));
                $this->dbforge->add_field("`date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");

                $this->dbforge->create_table('zdb');
        }

        public function down()
        {
                $this->dbforge->drop_table('zdb');
        }
}