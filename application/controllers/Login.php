<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function index()
    {
        $this->session->sess_destroy();
        $this->data['email'] = $this->session->flashdata('email');

        parent::renderer();
    }

    public function auth()
    {
        if ($this->input->post()) {

            $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'E-mail', 'trim|required|min_length[3]');
            $this->form_validation->set_rules('password', 'Senha', 'trim|required');

            $this->session->set_flashdata('email', $this->input->post('email'));

            if ($this->form_validation->run() === FALSE) {
                $this->setError(validation_errors());
            } else {
                $me = $this->user_model->get(array(
                    'email' => $this->input->post('email')
                ))->result();


                if (count($me) === 0) {
                    $this->setError('E-mail não encontrado');
                } else {
                    $me = current($me);
                    if ($me->password != md5($this->input->post('password'))) {
                        $this->setError($me->name . ', a sua senha está errada');
                    } else {
                        $this->session->set_userdata('me', $me);
                        $this->goToPreviousUrl($this->uri->uri_string());
                    }
                }
            }
        } else {
            $this->setError('Ocorreu um erro ao processar o formulario, tente novamente mais tarde');
        }
        redirect($this->uri->segment(1));
    }
}