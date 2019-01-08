<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_idscape extends CI_Migration {

        public function up()
        {
            $this->dbforge->add_column('scape', "`id` INT NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`id`)");
            
        }

        public function down()
        {
                $this->dbforge->drop_column('scape', 'id');
        }
}