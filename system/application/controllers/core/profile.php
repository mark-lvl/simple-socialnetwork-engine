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

        //append css to layout
        $this->add_css('boxy');

        //append js to layout
        $this->add_js('jquery');
        $this->add_js('jquery.boxy');
		
    }

    function index()
    {
        $this->load->library('cf_user');
        $this->load->library('cf_social');
        $this->data['user'] = $this->userAcl();

        $this->data['friends'] = $this->cf_social->get_friends($this->data['user'], $this->config->item('_core_profile_number_freinds'));

        if(!$this->data['user'])
            redirect('core/registration/login');

		$this->load->model('core/cf_social_model');
/* for test
		$mess = $this->cf_social->get_message($this->data['user'],
											 $userField = 'first_name',
											 $extraField = '',
											 $id = 80,
											 $checkUpdate = TRUE);

		$mess = $this->cf_social->delete_all_message($this->data['user']);
*/
        $this->data['lang'] = $this->lang->language;
        $this->data['title'] = $this->data['lang']['title_profile'];

        $this->render();
    }

    function view($hashedId)
    {

        $this->load->library('cf_authentication');
        $this->load->library('cf_user');
        $this->load->library('cf_social');
        $this->data['user'] = $this->cf_authentication->is_user();
 
        //if user_id to view is equal with logged user_id
		$partner_id = $this->encrypt->my_decode($hashedId);

        if($this->data['user']->id == $partner_id)
            redirect('core/profile');

        //the partner user profile details
        $this->data['partner'] = $this->cf_user->get_user_by_id($partner_id,
                                                                $this->_userTable);

        $this->data['friends'] = $this->cf_social->get_friends($this->data['partner'],
                                                                  $this->config->item('_core_profile_number_freinds'));

        //status between the logged_user and partner
        $this->data['relation_status'] = $this->cf_social->get_relation_status($this->data['user']->id,$partner_id);

        if(!$this->data['user'])
            redirect('core/registration/login');

        $this->data['lang'] = $this->lang->language;

        $this->data['title'] = str_replace('__NAME__',
                                           $this->data['partner']->first_name ,
                                           $this->data['lang']['title_profile_partner']);

        $this->render();
    }

   function edit()
   {
        $this->lang->load('labels','persian');
        $this->lang->load('content','persian');
        $this->data['lang'] = $this->lang->language;

        $this->load->helper(array('form'));
        $this->load->library('form_validation');
        $this->load->library('cf_authentication');
        $this->load->library('cf_user');

        $this->data['user'] = $this->cf_authentication->is_user();

        if(!$this->data['user'])
            redirect('core/registration/login');

        //encrypt the user id for show in frontend
        $this->data['user']->id = $this->encryption->encrypt($this->data['user']->id);

        if($this->input->post('submit'))
        {
            $_POST['user_id'] = $this->encryption->decrypt($_POST['user_id']);

            $this->load->library('cf_user');
            $fb = $this->cf_user->update_user($this->input->xss_clean($_POST), $this->data['user']);

            redirect('core/profile');
        }


        if(!$this->data['user'])
            redirect('core/registration/login');

        $this->data['title'] = $this->data['lang']['title_profile_edit'];

        $this->render();
    }


   function search()
   {
        $this->load->library('cf_user');

		var_dump($this->cf_user->find_users('first',0,2));exit;

        $this->render();
    }

}
