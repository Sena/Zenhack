<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_datescape extends CI_Migration {

        public function up()
        {
                $this->dbforge->add_column('scape', "`date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP");
        }

        public function down()
        {
                $this->dbforge->drop_column('scape', 'date');
        }
}