<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Athentication class
 * 2010-11-6
 * M.R Kaghazgarian
 *
 */
class Cf_authentication {
	private $ci;
        private $_userTable;
        private $_extraTableName;
	/**
	 * Constructor - Initializes and references CI
	 */
	function Cf_authentication()
        {
		$this->ci = & get_instance();
                $this->_userTable =  $this->ci->config->item('_core_user_table_name');
                $this->_extraTableName = $this->ci->config->item('_core_user_profile_table_name');
	}
	/**
	 * Make a user logged in with the given email, password
	 * Sets user session and stores in session table
	 * @param <STRING> $email
	 * @param <STRING> $password
	 * @return <OBJECT> user object, <BOOLEAN> FALSE
	 */
	function login($username, $password)
        {
            //is user session is available reject login
            if($this->ci->session->userdata('logged_in'))
                    return TRUE;
            else
            {
                $this->ci->load->model("core/cf_authentication_model");
                $this->ci->load->library("cf_security");

                $user = $this->ci->cf_authentication_model->login($this->_userTable,
                                                                  $this->_extraTableName,
                                                                  $username,
                                                                  $this->ci->cf_security->generate_hash($password));

                if (!$user) 
                        return FALSE;

                $user->logged_in = TRUE;

                $user_data = (array) $user;

                $this->ci->session->set_userdata($user_data);

                return TRUE;
            }
	}
	/**
	 * Checks if the given username and password exists in the given table
	 * @param <STRING> $username
	 * @param <STRING> $password
	 * @return <OBJECT> User object, <BOOLEAN> FALSE if no user found
	 */
	function is_user($username = "", $password = "")
        {
            if (!$username && !$password)
            {
                if ($this->ci->session->userdata('logged_in'))
                    return (object) $this->ci->session->userdata;
            }
            if ($username == $this->ci->session->userdata('email') && $this->ci->session->userdata('logged_in'))
                return (object) $this->ci->session->userdata;
            else
            {
                $this->ci->load->library("cf_security");

                $sob = new Cf_security();
                if ($sob->check_hash($password, $this->ci->session->userdata('password')))
                    return (object) $this->ci->session->userdata;
                return FALSE; //Password is wrong!
            }
	}
}