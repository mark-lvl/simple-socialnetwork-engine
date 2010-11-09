<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cf_user {
	private $ci;
        private $_userTable;
	/**
	 * Constructor - Initializes and references CI
	 */
	function Cf_user() {
		$this->ci = & get_instance();
                $this->_userTable =  $this->ci->config->item('_core_user_table_name');
	}

        /**
	 * create user from registeration or ...
	 * @param <ARRAY> $params user details
	 * @return <BOOL> success/failed process
	 */
        function create_user($params)
        {
            $this->ci->load->library("cf_security");
            $this->ci->load->model('core/cf_user_model');
            if($this->unique_email($params['email']))
            {
                $params['password'] = $this->ci->cf_security->generate_hash($params['password']);
                if($this->ci->cf_user_model->create_user($params,$this->_userTable))
                    return 'success';
                else
                    return 'faild';
            }
            else
                return 'notUnique';
        }

        /**
	 * check the user email is unique
	 * @param <STRING> $email
	 * @return <BOOL> unique/notUnique
	 */
        function unique_email($email)
        {
            $query = $this->ci->db->get_where($this->_userTable, array('email' => $email));
            if($query->num_rows() > 0)
                return FALSE;
            else
                return TRUE;
        }

        /**
	 * get user friends
	 * @param <OBJECT> $user
	 * @param <INT> $limit number of returned friends
	 * @return <ARRAY> list of returned friends
	 */
        function get_user_friends($user, $limit = "", $offset = "")
        {
            if(!is_object($user))
                return FALSE;

            $this->ci->load->model('core/cf_user_model');

            return $this->ci->cf_user_model->get_friends($user, $limit, $offset);
        }

        /**
	 * get user by the user_id
	 * @param <INT> $id
	 * @param <STRING> $tableName the name of user's table
	 * @return <ARRAY> the user object
	 */
        function get_user_by_id($id, $tableName)
        {
            $this->ci->load->model('core/cf_user_model');

            return $this->ci->cf_user_model->get_user_by_id($id, $tableName);
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