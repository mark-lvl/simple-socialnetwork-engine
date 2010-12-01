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
        $this->load->library('cf_authentication');
        
        $this->data['user'] = $this->cf_authentication->is_user();

        if(!$this->data['user'])
            redirect('core/registration/login');

        //first check for limitation of sending request friend for user
        $this->load->library('cf_social');
        if(!$this->cf_social->check_user_request_limitation($this->data['user']))
            redirect('message/success/112');

        
        if($this->cf_social->set_user_relation($this->data['user'], $id))
        {
            $this->cf_social->send_message( $this->data['user']->id,
                                            $id,
                                            $this->data['lang']['content_request_friend_mess_title'],
                                            $this->data['lang']['content_request_friend_mess_body'],
                                            1,
                                            FALSE);
            redirect('message/success/101');
        }
        else
            redirect('message/error/102');
    }

}
