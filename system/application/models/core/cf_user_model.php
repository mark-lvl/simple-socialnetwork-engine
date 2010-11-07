<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cf_user_model extends Model {

	/**
	 * Constructor - Initializes and references CI
	 */
	function Cf_user_model() {
		parent::Model();
	}
	
	/**
	 * create user from registeration or ...
	 * @param <ARRAY> $params user details
	 * @param <STRING> table name for user transaction
	 * @return <BOOL> success/failed process
	 */
	function create_user($params,$tableName)
        {

            $data = array(
                          'first_name' => $params['first_name'] ,
                          'last_name' => $params['last_name'] ,
                          'email' => $params['email'] ,
                          'password' => $params['password'] ,
                          'sex' => $params['gender'] ,
                          'registration_ip' => $_SERVER['REMOTE_ADDR'] ,
                          'registration_date' => date('Y-m-d H:i:s')
                         );
            if($this->db->insert($tableName, $data))
                return $this->db->insert_id();
            else
                return FALSE;
	}
}