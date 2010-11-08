<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Registration extends Base_Controller {

    private $_userTable;

    function Registration() {
        parent::__construct();
        $this->load->helper(array('form'));
        $this->load->library('form_validation');
        $this->_userTable =  $this->config->item('_core_user_table_name');
    }

    function index() 
    {
        
        $this->lang->load('form_validation','persian');
        $this->lang->load('labels','persian');
        $this->lang->load('content','persian');
        $this->data['lang'] = $this->lang->language;

        $this->data['title'] = $this->data['lang']['title_register'];

        $this->form_validation->set_message('required', $this->lang->language['form_validation_required']);
        $this->form_validation->set_message('min_length', $this->lang->language['form_validation_min_length']);
        $this->form_validation->set_message('max_length', $this->lang->language['form_validation_max_length']);
        $this->form_validation->set_message('valid_email', $this->lang->language['form_validation_valid_email']);
        $this->form_validation->set_message('matches', $this->lang->language['form_validation_matches']);
        
        if ($this->form_validation->run('signup') != FALSE)
        {
            $this->load->library('cf_user');
            $crm = $this->cf_user->create_user($_POST);
            if($crm == 'success')
                $this->action_name = "registersuccess";
            elseif($crm == 'faild')
                $this->data['message'] = $this->data['lang']['error_database_insert_faild'];
            elseif($crm == 'notUnique')
                $this->data['message'] = $this->data['lang']['error_user_email_not_unique'];
        }
            
        $this->render();
    }

    function login()
    {
        $this->lang->load('labels','persian');
        $this->lang->load('form_validation','persian');
        $this->data['lang'] = $this->lang->language;

        $this->form_validation->set_message('required', $this->lang->language['form_validation_required']);
        $this->form_validation->set_message('valid_email', $this->lang->language['form_validation_valid_email']);
        $this->form_validation->set_message('min_length', $this->lang->language['form_validation_min_length']);

        if ($this->form_validation->run('login') != FALSE)
        {
            $this->load->library('cf_authentication');
            if($this->cf_authentication->login($_POST['email'], $_POST['password']))
                redirect('core/profile');
            else
                $this->data['message'] = $this->data['lang']['error_user_login_faild'];
        }

        $this->render();
    }
}

?>