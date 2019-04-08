<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Scape extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('scape_model');
    }

    public function index()
    {
        $this->checkPermission();
        $this->loadFontawesome();
        $this->getUser();

        $this->data['list'] = $this->scape_model->get()->result();

        parent::renderer();
    }

    public function delete($id)
    {
        $this->checkPermission();

        $this->scape_model->delete(array('id' => $id));
        $this->setMsg('Registro removido com sucesso.');
        redirect($this->uri->segment(1));
    }

    private function getUser()
    {
        $this->load->model('user_model');
        $user = $this->user_model->get()->result();

        foreach ($user as $row) {
            $this->data['user'][$row->id] = $row;
        }
    }
}