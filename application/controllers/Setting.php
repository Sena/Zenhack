<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Setting extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->checkPermission();

        $this->load->model('setting_model');
    }

    public function index($id = NULL)
    {
        parent::renderer();
    }

    public function save()
    {
        if ($this->input->post()) {

            $post = $this->input->post();

            foreach ($post as $key => $row) {
                if (isset($this->data['setting'][$key])) {
                    $this->setting_model->update(array('id' => $this->data['setting'][$key]->id), array('value' => $row));
                }
            }
            $this->setMsg('Registro salvo com sucesso.');
        } else {
            $this->setError('Ocorreu um erro ao processar o formulario, tente novamente mais tarde');
        }
        redirect($this->uri->segment(1));
    }
}