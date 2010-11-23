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
	 * @param <STRING> table name for extra user information
	 * @param <ARRAY> extra field for user information
	 * @return <BOOL> success/failed process
	 */
	function create_user($params, $tableName, $extraTable, $extraFields)
        {

            $data = array(
                          'first_name' => $params['first_name'] ,
                          'last_name' => $params['last_name'] ,
                          'email' => $params['email'] ,
                          'password' => $params['password'] ,
                          'registration_ip' => $_SERVER['REMOTE_ADDR'] ,
                          'registration_date' => date('Y-m-d H:i:s')
                         );

            if($this->db->insert($tableName, $data))
                $ext_data['user_id'] = $this->db->insert_id();

            if(!empty ($extraTable))
            {
                if(is_array($extraFields))
                {
                    foreach ($extraFields as $field)
                        if($field['in_registration'])
                            $ext_data[$field['field_name']] = $params[$field['field_alter_name']];

                    if($this->db->insert($extraTable, $ext_data))
                        return TRUE;
                    else
                        return FALSE;
                }

            }
            if(isset ($ext_data['user_id']))
                return TRUE;
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
                        AND A.`status` = 1
                        UNION
                        SELECT A.id AS fake_id, B.* FROM `relations` A, `users` B
                        WHERE A.`guest` = " . $this->db->escape($user) . "
                        AND B.id = A.`inviter`
                        AND A.`status` = 1
                        ".$limitString;

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
	function get_user_by_id($id, $tableName, $extraTable)
        {
            $this->db->select('*');
            $this->db->from($tableName);

            if(!empty ($extraTable))
                $this->db->join($extraTable, $extraTable . '.id = ' . $tableName . '.id');

            $user = $this->db->where(array('id' => $id))
                             ->get()
                             ->row();

            if($user)
                return $user;
            else
                return FALSE;
	}

        /**
	 * get user status relation with other user
	 * @param <INT> $user id of authenticated user
	 * @param <INT> $anotherUser id of partner user
	 * @return <INT> 0:if request on wait,1:if req is accept,2:if req is rejected
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

            $statusHolder = '';

            if(count($result) > 0)
                foreach ($result as $res)
                {
                    switch ($res['status'])
                    {
                        case 0:
                                if($res['inviter'] == $user && $statusHolder != 'related')
                                    $statusHolder = 'waiting';
                                elseif($statusHolder != 'related')
                                    $statusHolder = 'waitForMe';
                        break;
                        case 1:
                            $statusHolder = 'related';
                        break;
                        case 2:
                            if($statusHolder != 'related')
                            {
                                if($res['inviter'] == $user)
                                    $statusHolder = 'reject';
                                elseif($res['guest'] == $user && $statusHolder != 'waiting' && $statusHolder != 'reject')
                                    $statusHolder = 'request';
                            }
                        break;
                    }
                }
                if($statusHolder == '')
                    $statusHolder = 'request';
            return $statusHolder;
        }
}