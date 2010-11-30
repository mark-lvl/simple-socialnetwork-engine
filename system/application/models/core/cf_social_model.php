<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cf_social_model extends Model {

        

	/**
	 * Constructor - Initializes and references CI
	 */
	function Cf_social_model() {
		parent::Model();
	}
	
	/**
	 * create user from registeration or ...
	 * @param <OBJECT> logged in user object
	 * @return <BOOL> success/failed process
	 */
	function get_user_request_limitation($user)
        {
            $sqlCounter = "SELECT count(id) AS counter FROM `relations` WHERE `inviter` = ".$user->id.";";
            $resultCounter = $this->db->query($sqlCounter);
            $counter = $resultCounter->row()->counter;
            $this->cf_cache->write($counter, 'userFriendRequest_' . $user->id, 1800);

            if($counter > self::FRIEND_REQUEST_LIMITAION)
                    return FALSE;
            else
                    return TRUE;
        }

	/**
	 * create a relation between 2 users
	 * @param <OBJECT> logged in user object
	 * @param <INT> guest user for relation
	 * @return <BOOL> success/failed process
	 */
	function set_user_relation($user, $guest_id)
        {

            $sql = "SELECT * FROM `relations` WHERE 
                        (`inviter` = " . $this->db->escape($user->id) . " AND `guest` = " . $this->db->escape($guest_id) . ")
                        OR (`inviter` = " . $this->db->escape($guest_id) . " AND `guest` = " . $this->db->escape($user->id) . ")";

            $result = $this->db->query($sql);
            $result = $result->result_array();

            if (count($result) > 0) 
                return FALSE;
            else {
                $sql = "INSERT INTO `relations` (`inviter`, `guest`, `invitation_date`, `status`)
                        VALUES (" . $this->db->escape($user->id) . ", " . $this->db->escape($guest_id) . ", '" . date('Y-m-d H:i:s') . "', '0')";
                if ($this->db->query($sql)) 
                    return TRUE;
                return FALSE;
            }
        }
}