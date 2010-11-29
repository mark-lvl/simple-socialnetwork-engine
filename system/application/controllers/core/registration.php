<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Registration extends Base_Controller {

    private $_userTable;
    private $_extraTableName;
    private $_extraFields;

    function Registration() {
        parent::__construct();
        $this->load->helper(array('form'));
        $this->load->library('form_validation');
        $this->_userTable =  $this->config->item('_core_user_table_name');
        $this->_extraTableName = $this->config->item('_core_user_profile_table_name');
        $this->_extraFields = $this->config->item('_core_user_extra_field');
    }

    function index() 
    {
        $this->lang->load('labels','persian');
        $this->lang->load('content','persian');
        $this->data['lang'] = $this->lang->language;

        $this->data['title'] = $this->data['lang']['title_register'];

        if($this->input->post('submit'))
        {
            $this->load->library('cf_user');
            $fb = $this->cf_user->create_user($this->input->xss_clean($_POST));

            if($fb == 'success')
                $this->action_name = "success";
            elseif($fb == 'faild')
                $this->data['message'] = $this->data['lang']['error_database_insert_faild'];
            elseif($fb == 'notUnique')
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