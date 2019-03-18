<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class User extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('user_model');
    }

    public function index()
    {
        $this->checkPermission();
        $this->loadFontawesome();
        $this->data['list'] = $this->user_model->get()->result();

        parent::renderer();
    }

    public function edit($id = NULL)
    {
        if($id != $this->data['me']->id) {
            $this->checkPermission();
        }

        if ($this->uri->segment(3) == 'editar' && (int)$id === 0) {
            redirect($this->uri->segment(1) . '/novo');
        } elseif ($id > 0) {
            $data = $this->user_model->get(array('id' => $id))->result();
            if (count($data) > 0) {
                $data = current($data);
                $this->data['data'] = $data;
            }
        }

        parent::renderer();
    }

    public function save($id = NULL)
    {
        if($id != $this->data['me']->id) {
            $this->checkPermission($this->router->class . '/edit');
        }
        $id = (int)$id;
        if ($this->input->post()) {

            $emailValidation = '|is_unique[user.email]';

            if ($id > 0) {
                $user = $this->getUser($id);
                if ($user) {
                    if($user->email == $this->input->post('email')) {
                        $emailValidation = '';
                    }
                } else {
                    $this->setError('Falha ao encontrar o usuário');
                    redirect($this->uri->segment(1));
                }
            }

            $this->load->library('form_validation');
            $this->form_validation->set_rules('name', 'Nome', 'trim|required|min_length[3]');
            $this->form_validation->set_rules('email', 'E-mail', 'trim|required|min_length[3]|valid_email' . $emailValidation);

            if ($this->form_validation->run() === FALSE) {
                $this->setError(validation_errors());
                if ($id === 0) {
                    $redirect = '/novo';
                } else {
                    $redirect = '/editar/' . $id;
                }
                redirect($this->uri->segment(1) . $redirect);
            } else {
                $data = array(
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email')
                );
                if ($this->input->post('password')) {
                    $data['password'] = md5($this->input->post('password'));
                    if ($id == $this->data['me']->id) {
                        $data['forcechange'] = 0;
                    }
                }
                if ($id === 0) {
                    $id = $this->user_model->insert($data, true);
                } else {
                    $this->user_model->update(array('id' => $id), $data);
                }
                if ($id === 0) {
                    $this->setError('Erro ao tentar gravar no banco de dados.');
                } else {
                    if ($id == $this->data['me']->id) {
                        $this->session->set_userdata('me', (object)array_merge((array)$this->data['me'], $data));
                    }
                    $this->setMsg('Registro salvo com sucesso.');
                }
            }
        } else {
            $this->setError('Ocorreu um erro ao processar o formulario, tente novamente mais tarde');
        }
        redirect($this->uri->segment(1));
    }

    public function delete($id)
    {
        $this->checkPermission();

        if ($this->user_model->get()->num_rows() > 1) {
            $this->load->model('userpermission_model');

            $this->user_model->delete(array('id' => $id));
            $this->userpermission_model->delete(array('user_id' => $id));
            $this->setMsg('Registro removido com sucesso.');
        } else {
            $this->setError('Não é possivel remover o único usuário do sistema');
        }
        redirect($this->uri->segment(1));
    }

    private function getUser($id)
    {
        $user = $this->user_model->get(array('id' => $id))->result();
        return current($user);
    }
}