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
        
        $this->lang->load('form_validation','persian');
        $this->lang->load('labels','persian');
        $this->lang->load('content','persian');
        $this->data['lang'] = $this->lang->language;

        $this->data['title'] = $this->data['lang']['title_register'];

        //set custom message for form validation errors
        $this->form_validation->set_message('required', $this->lang->language['form_validation_required']);
        $this->form_validation->set_message('min_length', $this->lang->language['form_validation_min_length']);
        $this->form_validation->set_message('max_length', $this->lang->language['form_validation_max_length']);
        $this->form_validation->set_message('valid_email', $this->lang->language['form_validation_valid_email']);
        $this->form_validation->set_message('matches', $this->lang->language['form_validation_matches']);

        //append the extra field's rules to the form validation rules that used in signup condition
        if(!empty ($this->_extraTableName))
        {
            if(is_array($this->_extraFields))
            {
                foreach ($this->_extraFields as $field)
                {
                    if($field['in_registration'])
                        if(isset($field['rules']) && $field['rules'] != "")
                            array_push($this->form_validation->_config_rules['signup'], array(
                                        'field' => $field['field_alter_name'],
                                        'label' => 'lang:label_'.$field['field_label'],
                                        'rules' => implode("|", $field['rules'])
                                     ));
                }
            }
        }
        
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