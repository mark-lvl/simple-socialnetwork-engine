<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Base_Controller extends Controller {

    protected $data = array();
    protected $controller_name;
    protected $action_name;

    public function __construct()
    {
        parent::__construct();
        $this->load_defaults();
        }

    protected function load_defaults() {
        $this->data['heading'] = 'Page Heading';
        $this->data['content'] = '';
        $this->data['css'] = '';
        $this->data['title'] = 'Page Title';

        $this->controller_name = $this->router->fetch_directory() . $this->router->fetch_class();
        $this->action_name = $this->router->fetch_method();

    }

    protected function render($template='main') {
        $view_path = $this->controller_name . '/' . $this->action_name . '.tpl.php';
        if (file_exists(APPPATH . 'views/' . $view_path)) {
            $this->data['content'] .= $this->load->view($view_path, $this->data, true);
        }

        $this->load->view("layouts/$template.tpl.php", $this->data);
    }

    protected function add_css($filename) {
        $this->data['css'] .= $this->load->view("partials/css.tpl.php", array('filename' => $filename), true);
    }

}
?>