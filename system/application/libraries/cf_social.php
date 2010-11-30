<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Cf_social {
    
	private $ci;

        //this flag set the number of a user can send friend request for all time
	const FRIEND_REQUEST_LIMITAION = $this->ci->config->item('_friend_request_limitation');

	/**
	 * Constructor - Initializes and references CI
	 */
	function Cf_security() {
		$this->ci = & get_instance();
	}

        /**
	 * check the limitation of sending request friend for each user
	 * @param <ONJECT> logged in user object
	 * @return <BOOL> over limitation and cant send request/can send
	 */
        function check_user_request_limitation($user)
        {
            if($this->ci->cf_cache->get('userFriendRequest_' . $user->id))
                if($this->ci->cf_cache->get('userFriendRequest_' . $user->id) > self::FRIEND_REQUEST_LIMITAION)
                        return FALSE;
                else
                        return TRUE;
            else
            {
                $this->ci->load->model('core/cf_social_model');
                return $this->ci->cf_social_model->get_user_request_limitation($user);
            }
        }

        function set_user_relation($user, $guest_id)
        {
            if ($user->id == $guest_id)
                return FALSE;

            $this->ci->load->model('core/cf_social_model');
                return $this->ci->cf_social_model->get_user_request_limitation($user, $guest_id);
        }

        /**
	 * check the user email is unique
	 * @param <STRING> $email
	 * @return <BOOL> unique/notUnique
	 */
	function add_friend($param) {

        }
}