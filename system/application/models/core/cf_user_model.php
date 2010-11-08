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

        /**
	 *this function create a array of user friends list
	 * @param <OBJECT> $user
	 * @param <INT> $limit limitation of freinds return
	 * @param <INT> $offset offset for sql select query
	 * @return <ARRAY> user friends
	 */
        function get_friends($user, $limit = "", $offset = "")
        {
            if (is_object($user))
                $user = $user->id;
            else
                return FALSE;

            $result = $this->cf_cache->get('friends_' . $user);

            if (!$result) {
                if($limit)
                    $limitString = " LIMIT ".$limit;
                if($offset)
                    $limitString .= " OFFSET ".$offset;

                $sql = "SELECT A.id AS fake_id, B.* FROM `relations` A, `users` B
                        WHERE A.`inviter` = " . $this->db->escape($user) . "
                        AND B.id = A.`guest`
                        UNION
                        SELECT A.id AS fake_id, B.* FROM `relations` A, `users` B
                        WHERE A.`guest` = " . $this->db->escape($user) . "
                        AND B.id = A.`inviter` ".$limitString;

                $query = $this->db->query($sql);
                $result = $query->result();
                
                if ($query->num_rows() > 0)
                {
                    $this->cf_cache->write($result, 'friends_' . $user, 3600);
                    return $result;
                }
                else
                    return FALSE;
            }
            return $result;
        }

        /**
	 * get user by id
	 * @param <INT> $id
	 * @param <STRING> $tableName the name of user's table
	 * @return <BOOL> user object/FALSE
	 */
	function get_user_by_id($id, $tableName)
        {
            $query = $this->db->get_where($tableName, array('id' => $id));

            $user = $query->row();
            if($user)
                return $user;
            else
                return FALSE;
	}

        /**
	 * get user user status relation
	 * @param <INT> $user id of first user
	 * @param <INT> $anotherUser id of another user
	 * @return <BOOL> true if is related
	 */
	function get_relation_status($user, $anotherUser)
        {
            if($user == $anotherUser)
                return TRUE;

            $sql = "SELECT * FROM `relations` 
                    WHERE (`inviter` = " . $this->db->escape($user) . "
                        AND `guest` = " . $this->db->escape($anotherUser) . ")
                    OR (`inviter` = " . $this->db->escape($anotherUser) . "
                        AND `guest` = " . $this->db->escape($user) . ")";

            $result = $this->db->query($sql);
            $result = $result->result_array();

            if(count($result) > 0)
                return $result->status;
        }
}