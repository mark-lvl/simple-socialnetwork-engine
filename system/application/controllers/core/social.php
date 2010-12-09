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
        $this->data['lang'] = $this->lang->language;

        $this->load->library('cf_social');
    }

    function add_friend($id)
    {
        //first decrypt the guest id
        $id = $this->encryption->decrypt($id);
        
        $this->data['user'] = $this->userAcl();

        if(!$this->data['user'])
            redirect('core/registration/login');

        //first check for limitation of sending request friend for user
        
        if(!$this->cf_social->check_user_request_limitation($this->data['user']))
            redirect('message/success/112');


        //set the relation between 2 user
        if($this->cf_social->set_user_relation($this->data['user'], $id))
        {
            //send message for guest from requester user
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

    function remove_friend($id)
    {
        //first decrypt the guest id
        $id = $this->encryption->decrypt($id);

        $this->data['user'] = $this->userAcl();

        if($this->cf_social->remove_relation($this->data['user'], $id))
            redirect('message/success/103');
        else
            redirect('message/error/104');
    }

    function request_apply($id, $cond)
    {
        //first decrypt the guest id
        $id = $this->encryption->decrypt($id);

        $this->data['user'] = $this->userAcl();

        if($this->cf_social->request_apply($this->data['user'], $id, $cond))
            redirect('message/success/105');
        else
            redirect('message/error/106');
    }


}
