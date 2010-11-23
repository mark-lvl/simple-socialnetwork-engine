<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Athentication class
 * 2010-09-16
 * Fouad Amiri info@fouad.ir
 */
class Cf_authentication_model extends Model {

	/**
	 * Constructor - Initializes and references CI
	 */
	function Cf_authentication_model() {
		parent::Model();
	}
	
	function login($tableName, $extraTableName, $email, $password) {

                $this->db->select('*');
                $this->db->from($tableName);

                if(!empty ($extraTableName))
                    $this->db->join($extraTableName, $extraTableName . '.user_id = ' . $tableName . '.id');

                $user = $this->db->where(array('email'=> $email,
                                               'password'=>$password))
                                 ->get()
                                 ->row();
                
                if(count($user) > 0)
                {
                    $this->db->where('id', $user->id);
                    $this->db->update($tableName, 
                                      array('logins' => ($user->logins + 1),
                                            'last_login_date' => date('Y-m-d H:i:s')));
                    return $user;
                }
                else
                    return FALSE;
	}

	/**
	 * Gets the auth level of user
	 * @param <OBJECT> $user user object with the field of id
	 * @return <INT> level
	 */
	function get_user_level($user) {
		$sql = "SELECT `$this->admins_level_field` FROM `$this->admins_table`
                WHERE `$this->admins_user_field` = {$this->db->escape($user->id)}";

		$result = $this->db->query($sql);
		$result = $result->row();

		if (count($result) <= 0) { //No matches or user isn't an admin
			return 0;
		}

		eval("\$level = \$result->" . $this->admins_level_field . ";");
		return $level;
	}
}