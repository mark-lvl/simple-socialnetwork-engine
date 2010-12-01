<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Cf_social{
    
	private $ci;

        //this flag set the number of a user can send friend request for all time
	private $friend_request_limitation;

	/**
	 * Constructor - Initializes and references CI
	 */
	function Cf_social() {
		$this->ci = & get_instance();
		$this->friend_request_limitation = $this->ci->config->item('_friend_request_limitation');
	}

        /**
	 * check the limitation of sending request friend for each user
	 * @param <ONJECT> logged in user object
	 * @return <BOOL> over limitation and cant send request/can send
	 */
        function check_user_request_limitation($user)
        {
            if($this->ci->cf_cache->get('userFriendRequest_' . $user->id))
                if($this->ci->cf_cache->get('userFriendRequest_' . $user->id) > $this->friend_request_limitation)
                        return FALSE;
                else
                        return TRUE;
            else
            {
                $this->ci->load->model('core/cf_social_model');
                $user_request_counter = $this->ci->cf_social_model->get_user_request_limitation($user, $this->friend_request_limitation);

                $this->ci->cf_cache->write($user_request_counter, 'userFriendRequest_' . $user->id, 1800);

                if($user_request_counter > $this->friend_request_limitation)
                    return FALSE;
                else
                    return TRUE;
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
	 * send private message from one user to another
	 * @param <INT> $from_id the message sender's id
	 * @param <INT> $to_id the message recipient's id
	 * @param <STRING> $title title of the private message
	 * @param <STRING> $body main body of the private message
	 * @param <INT> $type type and kind of pm
	 * @param <BOOL> $secure for strip all html and php tags from message content
	 * @param <STATUS> $status status of message is 0 for unread and 1 for read
	 * @return <BOOL> success/failed process
	 */
        function send_message($from_id, $to_id, $title, $body, $type = 1, $secure = TRUE, $status = 1)
        {
            $this->ci->load->model('core/cf_social_model');
                return $this->ci->cf_social_model->send_message($from_id,
                                                                $to_id,
                                                                $title,
                                                                $body,
                                                                $type,
                                                                $secure,
                                                                $status);
        }
}