<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Social extends Base_Controller {

    private $_userTable;

    function Social() {
        parent::__construct();
        $this->_userTable =  $this->config->item('_core_user_table_name');

        $this->lang->load('content','persian');
        $this->lang->load('labels','persian');
        $this->lang->load('core','persian');
    }

    function add_friend($id = "")
    {
        $this->data['user'] = $this->cf_authentication->is_user();

        if(!$this->data['user'])
            redirect('core/registration/login');

        //first check for limitation of sending request friend for user
        $this->load->library('cf_social');
        if(!$this->cf_social->check_user_request_limitation($this->data['user']))
            redirect('message/success/112');

        
        if($this->cf_social->set_user_relation($this->data['user'], $id))
        {
            //az inja bebado baiad dorost konam
            Social_model::send_message($ob->id, $id, str_replace("XXX", $ob->name, $this->lang->language['add_request']), str_replace("XXX", $ob->id, $this->lang->language['add_requestbody']),FALSE);
            redirect('message/success/101');
        }
        else
            redirect('message/error/102');
    }

}
