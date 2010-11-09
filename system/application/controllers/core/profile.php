<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Profile extends Base_Controller {

    private $_userTable;

    function Profile() {
        parent::__construct();
        $this->_userTable =  $this->config->item('_core_user_table_name');

        $this->lang->load('content','persian');
        $this->lang->load('labels','persian');
        $this->lang->load('core','persian');
    }

    function index()
    {
        $this->load->library('cf_authentication');
        $this->load->library('cf_user');
        $this->data['user'] = $this->cf_authentication->is_user();

        $this->data['friends'] = $this->cf_user->get_user_friends($this->data['user'], $this->config->item('_core_profile_number_freinds'));

        if(!$this->data['user'])
            redirect('core/registration/login');


        $this->data['lang'] = $this->lang->language;
        $this->data['title'] = $this->data['lang']['title_profile'];

        $this->render();
    }

    function view($hashedId)
    {
        $this->load->library('cf_authentication');
        $this->load->library('cf_user');
        $this->data['user'] = $this->cf_authentication->is_user();
        
        //if user_id to view is equal with logged user_id
        if(base64_encode($this->data['user']->id) == $hashedId)
            redirect('core/profile');

        //the partner user profile details
        $this->data['partner'] = $this->cf_user->get_user_by_id(base64_decode($hashedId),
                                                                $this->_userTable);

        $this->data['friends'] = $this->cf_user->get_user_friends($this->data['partner'],
                                                                  $this->config->item('_core_profile_number_freinds'));

        //status between the logged_user and partner
        $this->data['relation_status'] = $this->cf_user->get_relation_status($this->data['user']->id,
                                                                             base64_decode($hashedId));

        if(!$this->data['user'])
            redirect('core/registration/login');

        $this->data['lang'] = $this->lang->language;

        $this->data['title'] = str_replace('__NAME__',
                                           $this->data['partner']->first_name ,
                                           $this->data['lang']['title_profile_partner']);

        $this->render();
    }

}
