<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Scape_details extends CI_Migration {

    public function up()
    {
        $fields = array(
            'html_url' => array(
                'type' => 'TEXT',
                'before' => 'dump',
            ),
            'title' => array(
                'type' => 'TEXT',
                'before' => 'dump',
            ),
            'details' => array(
                'type' => 'LONGTEXT',
                'before' => 'dump',
            ),
            'last_comment' => array(
                'type' => 'LONGTEXT',
                'before' => 'dump',
            ),
        );
        $this->dbforge->add_column('scape', $fields);

        $this->db->insert_batch('permission', array(
            array(
                'route' => 'scape/index',
                'label' => 'Postagem - Lista',
            ),
            array(
                'route' => 'scape/delete',
                'label' => 'Postagem - excluir',
            ),
        ));

    }

    public function down()
    {
        $this->dbforge->drop_column('scape', 'title');
        $this->dbforge->drop_column('scape', 'details');
        $this->dbforge->drop_column('scape', 'last_comment');
        $this->dbforge->drop_column('scape', 'html_url');

        $this->db->delete('permission', array(
            'route' => 'scape/index',
        ));

        $this->db->delete('permission', array(
            'route' => 'scape/delete',
        ));
    }
}