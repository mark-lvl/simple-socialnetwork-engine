<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Base_Controller extends Controller {

    protected $data = array();
    protected $controller_name;
    protected $action_name;

    public function __construct()
    {
        parent::__construct();

        $this->lang->load('heading','persian');
        $this->lang->load('titles','persian');
        $this->lang->load('errors','persian');
        $this->data['lang'] = $this->lang->language;

        //create a object from core encryption class
        $this->encryption = new encryption();

        $this->load_defaults();
        }

    protected function load_defaults() {
        $this->data['heading'] = '';
        $this->data['content'] = '';
        $this->data['css'] = '';
        $this->data['title'] = 'Page Title';

        $this->add_css('main');

        $this->controller_name = $this->router->fetch_directory() . $this->router->fetch_class();
        $this->action_name = $this->router->fetch_method();

    }

    protected function render($template='main') {
        $view_path = $this->controller_name . '/' . $this->action_name . '.tpl.php';

        //if the request page from core files
        if(!strstr($view_path, 'core'))
                $view_path = 'controllers_body' . $view_path;

        if (file_exists(APPPATH  . 'views/' . $view_path)) {
            $this->data['content'] .= $this->load->view($view_path, $this->data, true);
        }

        $this->load->view("layouts/$template.tpl.php", $this->data);
    }

    protected function add_css($filename) {
        $this->data['css'] .= $this->load->view("partials/css.tpl.php", array('filename' => $filename), true);
    }

}
?>