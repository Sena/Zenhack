<?php if (!defined('BASEPATH')) exit ('No direct script access allowed');

/**
 * Class MY_Controller
 */
class  MY_Controller extends CI_Controller
{

    /**
     * @var string
     */
    public $template = 'tpl_default';

    /**
     * @var array
     */
    public $data = array();
    /**
     * @var string
     */
    public $content = '';
    /**
     * @var string
     */
    public $header = 'default/header';
    /**
     * @var string
     */
    public $footer = 'default/footer';
    /**
     * @var string
     */
    protected $assets = array();
    /**
     * @var string
     */
    protected $css = array();
    /**
     * @var string
     */
    protected $js = array();

    /**
     * @param mixed $value
     */
    public function debug($value = null)
    {
        echo '<pre>';
        print_r($value);
        exit();
    }

    public function __construct()
    {
        parent::__construct();

        $this->checkUser();
        $this->getSetting();

        $this->loadBootstrap();

        $this->content = file_exists(APPPATH . 'views/' . $this->template . '/' . $this->router->class . '/' . $this->router->method . '.php') ? $this->template . '/' . $this->router->class . '/' . $this->router->method : $this->template . '/default/content';

        $this->data['error'] = $this->session->flashdata('error') ? $this->session->flashdata('error') : null;
        $this->data['msg'] = $this->session->flashdata('msg') ? $this->session->flashdata('msg') : null;

    }

    private function checkUser()
    {
        $this->data['user'] = $this->session->userdata('user') ? $this->session->userdata('user') : null;

        if ($this->router->class != 'login' && isset($this->data['user']->id) === FALSE) {
            $this->setError('É necessário estar logado');
            $this->setPreviousUrl(base_url($this->uri->uri_string()));
            redirect(base_url('login'));
        }elseif ($this->router->class != 'user' && isset($this->data['user']->forcechange) && $this->data['user']->forcechange) {
            $this->setError('Você precisa alterar a sua senha');
            redirect('usuario/editar/' . $this->data['user']->id);
        }
    }

    protected function getSetting()
    {
        $this->load->model('setting_model');

        $setting = $this->setting_model->get()->result();

        $this->data['setting'] = array();

        foreach ($setting as $row) {
            $this->data['setting'][$row->key] = $row;
        }
        if ($this->router->class != 'setting') {
            foreach ($this->data['setting'] as $row) {
                if($row->required && !$row->value) {
                    $this->setError('É necessário inserir informações obrigatórias antes de prosseguir');
                    redirect(base_url('configuracao'));
                }
            }
        }
    }

    /**
     * Call the view
     *
     * @access  public
     */
    public function index()
    {
        $this->renderer();
    }

    /**
     * Set a generic error to show in the view
     *
     * @access  public
     * @param   string A generic error
     */
    public function setError($str)
    {
        if (strlen($str) > 0) {
            $error = '';
            if ($this->session->flashdata('error')) {
                $error .= $this->session->flashdata('error') . '<br>';
            }
            $this->session->set_flashdata('error', $error . $str);
        }
    }

    /**
     * Set a generic msg to show in the view
     *
     * @access  public
     * @param   string A generic msg
     */
    public function setMsg($str)
    {
        if (strlen($str) > 0) {
            $msg = '';
            if ($this->session->flashdata('msg')) {
                $msg .= $this->session->flashdata('msg') . '<br>';
            }
            $this->session->set_flashdata('msg', $msg . $str);
        }
    }

    /**
     * Checks if user is logged
     *
     * @access  public
     */
    public function renderer()
    {
        $this->loadCss(
            array(
                array(
                    'name' => 'main',
                ),
                array(
                    'name' => $this->router->class . '_' . $this->router->method,
                )
            )
        );

        $this->loadJs(
            array(
                array(
                    'name' => 'script',
                ),
                array(
                    'name' => $this->router->class . '_' . $this->router->method,
                )
            )
        );

        $this->data['js'] = implode(null, $this->js);
        $this->data['css'] = implode(null, $this->css);
        $this->data['assets'] = implode(null, $this->assets);

        $this->getPageTitle();

        if ($this->header !== NULL) {
            $this->load->view($this->template . '/' . $this->header, $this->data);
        }
        $this->load->view($this->content, $this->data);

        if ($this->footer !== NULL) {
            $this->load->view($this->template . '/' . $this->footer, $this->data);
        }
    }

    public function getPageTitle()
    {
        if (isset($this->data['pageTitle']) == false) {
            $this->setPageTitle(ucfirst($this->router->class));
        }
        return $this->data['pageTitle'];
    }

    /**
     * Set a window title
     *
     * @access  public
     * @param string $page
     * @param string $separator
     */
    public function setPageTitle($page = '', $separator = ' - ')
    {
        $this->data['pageTitle'] = $page . $separator . NAME_SITE;
    }

    /**
     * Set a previous URL
     *
     * @access  public
     * @param   string A valid address internal or external
     */
    public function setPreviousUrl($address)
    {
        if (strpos($address, 'login') === false) {
            $previousUrl = $this->session->userdata('previousUrl') ? $this->session->userdata('previousUrl') : array();
            $previousUrl[] = $address;
            $this->session->set_userdata('previousUrl', $previousUrl);
        }
    }

    /**
     * Redirect the user, to previous URL
     *
     * @access  public
     * @param bool $scape
     */
    public function goToPreviousUrl($scape = false)
    {
        $previousUrl = $this->session->userdata('previousUrl') ? $this->session->userdata('previousUrl') : array();

        if (is_array($previousUrl) && count($previousUrl) > 0) {
            $previous = array_pop($previousUrl);
            $this->session->set_userdata('previousUrl', $previousUrl);

            redirect(strpos($previous, $scape) === false ? $previous : null);

        } else {
            redirect();
        }
    }

    protected function loadBootstrap()
    {
        $this->loadCss(array(
            array(
                'name' => 'bootstrap.min',
                'path' => 'assets/bootstrap/css/'
            )
        ), true);

        $this->loadJquery();

        $this->loadJs(array(
            array(
                'name' => 'popper.min',
                'path' => 'assets/bootstrap/js/'
            ),
            array(
                'name' => 'bootstrap.min',
                'path' => 'assets/bootstrap/js/'
            )
        ), true);
    }

    protected function loadFontawesome()
    {
        $this->loadCss(array(
            array(
                'name' => 'all',
                'path' => 'assets/fontawesome/css/'
            )
        ), true);
    }

    protected function loadJquery()
    {
        $this->loadJs(array(
            array(
                'name' => 'jquery-3.3.1.slim.min',
                'path' => 'assets/jquery/'
            )
        ), true);
    }

    protected function loadChart()
    {
        $this->loadJs(array(
            array(
                'name' => 'Chart.min',
                'path' => 'assets/js/'
            )
        ), true);
    }

    protected function loadCss(array $files, $priority = false)
    {
        $version = 0;
        if (is_array(current($files)) === false) {
            $files = array($files);
        }
        foreach ($files as $row) {
            if (isset($row['name']) === false) {
                log_message('error', __METHOD__ . ROOT_PATH . DIRECTORY_SEPARATOR . 'css name not defined. Dump: ' . var_export($row, true));
                continue;
            }
            if (isset($row['path']) === false || $row['path'] === null) {
                $row['path'] = 'assets/' . $this->template . '/css/';
            }
            $fileMin = $row['path'] . $row['name'] . '.css';

            if (isset($row['key']) === false) {
                $row['key'] = md5($fileMin);
            }
            //Put as the last one if was defined before
            if (isset($this->css[$row['key']]) === true) {
                $data = $this->css[$row['key']];
                unset($this->css[$row['key']]);
                $this->css[$row['key']] = $data;
                continue;
            }
            if (file_exists(ROOT_PATH . DIRECTORY_SEPARATOR . $fileMin) === TRUE) {
                if ($priority === true) {
                    $this->css[$row['key']] = '';
                    $this->assets[$row['key']] = PHP_EOL . '    <link rel="stylesheet" type="text/css" href="./' . $fileMin . '?v=' . $version . '"/>';
                } else {
                    $this->css[$row['key']] = PHP_EOL . '    <link rel="stylesheet" type="text/css" href="./' . $fileMin . '?v=' . $version . '"/>';
                }
            } else {
                log_message('debug', __METHOD__ . ROOT_PATH . DIRECTORY_SEPARATOR . $fileMin . ' does not exists');
            }
        }
    }

    protected function loadJs(array $files, $priority = false)
    {
        $version = 0;
        if (is_array(current($files)) === false) {
            $files = array($files);
        }
        foreach ($files as $row) {
            if (isset($row['name']) === false) {
                log_message('error', __METHOD__ . ROOT_PATH . DIRECTORY_SEPARATOR . 'js name not defined. Dump: ' . var_export($row, true));
                continue;
            }
            if (isset($row['path']) === false || $row['path'] === null) {
                $row['path'] = 'assets/' . $this->template . '/js/';
            }
            $fileMin = $row['path'] . $row['name'] . '.js';

            if (isset($row['key']) === false) {
                $row['key'] = md5($fileMin);
            }
            //Put as the last one if was defined before
            if (isset($this->js[$row['key']]) === true) {
                $data = $this->js[$row['key']];
                unset($this->js[$row['key']]);
                $this->js[$row['key']] = $data;
                continue;
            }
            if (file_exists(ROOT_PATH . DIRECTORY_SEPARATOR . $fileMin) === TRUE) {
                if ($priority === true) {
                    $this->js[$row['key']] = '';
                    $this->assets[$row['key']] = PHP_EOL . '    <script type="text/javascript" src="./' . $fileMin . '?v=' . $version . '"></script>';
                } else {
                    $this->js[$row['key']] = PHP_EOL . '    <script type="text/javascript" src="./' . $fileMin . '?v=' . $version . '"></script>';
                }

            } else {
                log_message('debug', __METHOD__ . ROOT_PATH . DIRECTORY_SEPARATOR . $fileMin . ' does not exists');
            }
        }
    }
}