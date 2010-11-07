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

        function unique_email($email)
        {
            $query = $this->ci->db->get_where($this->_userTable, array('email' => $email));
            if($query->num_rows() > 0)
                return FALSE;
            else
                return TRUE;
        }

}