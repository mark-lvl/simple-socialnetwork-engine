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
	 * shows the number of request hatr send from logged user
	 * @param <OBJECT> $user logged in user object
	 * @return <INT> the request number
	 */
	function get_user_request_limitation($user)
        {
            $sqlCounter = "SELECT count(id) AS counter FROM `relations` WHERE `inviter` = ".$user->id.";";
            $resultCounter = $this->db->query($sqlCounter);
            $counter = $resultCounter->row()->counter;

            return $counter;
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
	function send_message($from_id, $to_id, $title, $body, $type, $secure = TRUE, $status = 1)
        {
            if ($secure)
                $sql = "INSERT INTO messages (`from`, `to`, `date`, `message`,`ip`,`title`, `status` , `type`)
                        VALUES(" .  $this->db->escape($from_id) . ", " .
                                    $this->db->escape($to_id) . ", '" .
                                    date("Y-m-d H:i:s") . "', " .
                                    $this->db->escape(strip_tags($body)) . ",'" .
                                    $_SERVER['REMOTE_ADDR'] . "'," .
                                    $this->db->escape($title) . "," .
                                    (int)$status . "," .
                                    $type .
                        ")";
            else
                $sql = "INSERT INTO messages (`from`, `to`, `date`, `message`,`ip`,`title`, `status`, `type`)
                        VALUES(" .  $this->db->escape($from_id) . ", " .
                                    $this->db->escape($to_id) . ", '" .
                                    date("Y-m-d H:i:s") . "', " .
                                    $this->db->escape($body) . ",'" .
                                    $_SERVER['REMOTE_ADDR'] . "'," .
                                    $this->db->escape($title) . "," .
                                    (int)$status . "," .
                                    $type.
                        ")";

            
            if ($this->db->query($sql)) 
                return TRUE;
            else
                return FALSE;
        }
}