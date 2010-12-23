<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Cf_social{
    
	private $ci;
	private $_userTable;
	private $_extraTableName;

        //this flag set the number of a user can send friend request for all time
	private $friend_request_limitation;

	/**
	 * Constructor - Initializes and references CI
	 */
	function Cf_social()
	{
		$this->ci = & get_instance();
		$this->friend_request_limitation = $this->ci->config->item('_friend_request_limitation');
		$this->_userTable =  $this->ci->config->item('_core_user_table_name');
		$this->_extraTableName = $this->ci->config->item('_core_user_profile_table_name');

		$this->ci->load->model('core/cf_social_model');
	}

	/**
	 * check the limitation of sending request friend for each user
	 * @param <ONJECT> logged in user object
	 * @return <BOOL> FALSE over limitation and cant send request/TRUE can send
	 */
        function check_user_request_limitation($user)
        {
            $cache = $this->ci->cf_cache->get('userFriendRequest_' . $user->id);
            if(isset($cache))
                if($cache > $this->friend_request_limitation)
                        return FALSE;
                else
                        return TRUE;
            else
            {
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

            return $this->ci->cf_social_model->set_user_relation($user, $guest_id);
        }

        function remove_relation($user, $guest_id)
        {
            if ($user->id == $guest_id)
                return FALSE;

            return $this->ci->cf_social_model->remove_relation($user, $guest_id);
        }

        /**
	 * ignore or accept the friend request from another user
	 * @param <ONJECT> logged in user object
	 * @param <INT> $demandant_id the user that send request
	 * @param <BOOL> $cond true for accept request and FALSE for reject that
	 * @return <BOOL> success/failed process
	 */
	function request_apply($user, $demandant_id, $cond)
	{
		if ($user->id == $demandant_id)
			return FALSE;

		if($this->ci->cf_social_model->request_apply($user, $demandant_id, $cond))
		{
			//remove friend cache for both user for regererate that
			$this->ci->cf_cache->delete('friends_' . $user->id.'_*');
			$this->ci->cf_cache->delete('friends_' . $demandant_id.'_*');
			return TRUE;
		}
		return FALSE;
	}

        /**
	 * get user friends and set limitation for get or select all
	 * @param <ONJECT> logged in user object
	 * @param <INT> $limit limitation
	 * @param <INT> $offset offset for begin of limitation
	 * @return <ARRAY> returned friends objects
	 */
        function get_friends($user, $limit = "", $offset = "")
        {
            $result = $this->ci->cf_cache->get('friends_' . $user->id.'_'.$limit.'_'.$offset);
        
            if (!$result)
            {
                $result = $this->ci->cf_social_model->get_friends($user, $limit);
                if (count($result) > 0)
                {
                    $this->ci->cf_cache->write($result, 'friends_' . $user->id.'_'.$limit.'_'.$offset, 3600);
                    return $result;
                }
                else
                    return FALSE;
            }
            return $result;
        }
        /**
	 * send private message from one user to another
	 * @param <INT> $from_id the message sender's id
	 * @param <INT> $to_id the message recipient's id
	 * @param <STRING> $title title of the private message
	 * @param <STRING> $body main body of the private message
	 * @param <INT> $type type and kind of pm
	 * @param <BOOL> $secure for strip all html and php tags from message content
	 * @param <STATUS> $status status of message for example add star for mess or ...
	 * @return <BOOL> success/failed process
	 */
        function send_message($from_id, $to_id, $title, $body, $type = 1, $secure = TRUE, $status = 0)
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


	/**
	 * get all user's messages or specific message with desire message id and also
     * desire field from sender and reciever user
	 * @param <OBJECT> $from_id the message sender's id
     * @param <STRING> $tableName the name of user's table
	 * @param <STRING> $extraTable the name of user's table extra fields
	 * @param <STRING/ARRAY> one field or more field to select form sender information
	 * @param <STRING/ARRAY> one or more field selected form extra field from sender information
	 * @param <STRING/INT> $id determine fetch all message or only fetch one message with specific id
	 * @param <BOOL> $checkUpdate with this parameter can update message's check falg to read message
	 * @return <ARRAY> result
	 */
	function get_message($user,$userField = 'first_name', $extraField = '', $id = 'all', $checkUpdate = TRUE)
	{
		$messages =  $this->ci->cf_social_model->get_message(	$user,
																$this->_userTable,
																$this->_extraTableName,
																$userField,
																$extraField,
																$id);

		if($messages)
			if($id != 'all' && $checkUpdate == TRUE)
				$this->ci->cf_social_model->check_readed_message($messages->id);

		return $messages;
	}

	/**
	 * get count of unreaded messages
	 * @param <OBJECT> $user the object of logged user
	 * @return <INT> count of unread messages
	 */
    function get_unread_message($user)
	{
		return $this->ci->cf_social_model->get_unread_message($user);
    }

	/**
	 * delete desire message with id
	 * @param <OBJECT> $user logged in user
	 * @param <INT> $id desire id for fetch message
	 * @return <BOOL> success/failed process
	 */
    function delete_message($user, $id)
	{
		return $this->ci->cf_social_model->delete_message($user, $id);
    }

	/**
	 * delete all message from a user
	 * @param <OBJECT> $user logged in user
	 * @return <BOOL> success/failed process
	 */
    function delete_all_message($user)
	{
		return $this->ci->cf_social_model->delete_all_message($user);
    }

	/**
	 * get user status relation with other user
	 * @param <INT> $user id of authenticated user
	 * @param <INT> $anotherUser id of partner user
	 * @return <BOOL> true if is related
	 */
	function get_relation_status($user, $anotherUser)
	{
		$this->ci->load->model('core/cf_user_model');

		return $this->ci->cf_user_model->get_relation_status($user, $anotherUser);
	}
}