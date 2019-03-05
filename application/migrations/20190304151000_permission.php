<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Permission extends CI_Migration
{
    public $table = 'permission';

    public function up()
    {
        $this->dbforge->add_field('id');
        $this->dbforge->add_field(array(
            'route' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'label' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
        ));
        $this->dbforge->create_table($this->table);

        $this->dbforge->add_field(array(
            'user_id' => array(
                'type' => 'int',
                'constraint' => '11',
            ),
            $this->table . '_id' => array(
                'type' => 'int',
                'constraint' => '11',
            ),
        ));
        $this->dbforge->create_table('user' . $this->table);

        $this->insertPermission();
        $this->insertUserPermission();
    }

    private function insertPermission()
    {
        $this->db->insert_batch($this->table, array(
            array(
                'route' => 'scratch/index',
                'label' => 'Pendentes',
            ),
            array(
                'route' => 'scratch/listing',
                'label' => 'Lista de posts',
            ),
            array(
                'route' => 'scratch/next',
                'label' => 'Próximo',
            ),
            array(
                'route' => 'report/index',
                'label' => 'Relatórios',
            ),
            array(
                'route' => 'user/index',
                'label' => 'Usuário - Listar',
            ),
            array(
                'route' => 'user/edit',
                'label' => 'Usuário - Criar/Editar',
            ),
            array(
                'route' => 'user/delete',
                'label' => 'Usuário - Excluir',
            ),
            array(
                'route' => 'setting/index',
                'label' => 'Configurações - Visualizar',
            ),
            array(
                'route' => 'setting/save',
                'label' => 'Configurações - Editar',
            ),
            array(
                'route' => 'permission/edit',
                'label' => 'Permissões - Visualizar',
            ),
            array(
                'route' => 'permission/save',
                'label' => 'Permissões - Editar',
            )
        ));
    }

    private function insertUserPermission()
    {
        $userPermission = array();

        $users = $this->db->get('user')->result();
        $permissions = $this->db->get($this->table)->result();

        foreach ($users as $user) {
            foreach ($permissions as $permission) {
                $userPermission[] = array(
                    'user_id' => $user->id,
                    'permission_id' => $permission->id,
                );
            }
        }
        $this->db->insert_batch('user' . $this->table, $userPermission);

    }

    public function down()
    {
        $this->dbforge->drop_table($this->table);
        $this->dbforge->drop_table('user' . $this->table);
    }
}