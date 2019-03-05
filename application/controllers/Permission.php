<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Permission extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->checkPermission();
    }

    private function getUser($id)
    {
        $this->load->model('user_model');

        $user = $this->user_model->get(array('id' => $id))->result();
        $this->data['user'] = current($user);
        if ($this->data['user']) {
            $this->data['user']->permission = $this->getUserPermission($id);
        }
    }

    private function getPermission()
    {
        $this->load->model('permission_model');
        $this->data['permission'] = $this->permission_model->get()->result();
    }

    public function edit($id)
    {
        $this->getUser($id);
        $this->getPermission();

        if (!$this->data['user']) {
            $this->setError('Usuário não encontrado');
            redirect($this->uri->segment(1));
        }

        $this->renderer();
    }

    public function save($id)
    {
        $this->load->model('userpermission_model');

        $this->userpermission_model->delete(array('user_id' => $id));

        $data = $this->input->post();

        foreach ($data as $row) {
            $this->userpermission_model->insert(array(
                'user_id' => $id,
                'permission_id' => $row,
            ), true);
        }
        $this->setMsg('Registro salvo com sucesso.');

        redirect($this->uri->segment(1));
    }
}