<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Cf_user {
	private $ci;
        private $_userTable;
        private $_extraTableName;
        private $_extraFields;
	/**
	 * Constructor - Initializes and references CI
	 */
	function Cf_user() {
		$this->ci = & get_instance();
                $this->_userTable =  $this->ci->config->item('_core_user_table_name');
                $this->_extraTableName = $this->ci->config->item('_core_user_profile_table_name');
                $this->_extraFields = $this->ci->config->item('_core_user_extra_field');
	}

        /**
	 * create user from registeration or ...
	 * @param <ARRAY> $params user details
	 * @return <BOOL> success/failed process
	 */
        function manage_user($params, $user = "")
        {
            $this->ci->load->library("cf_security");
            $this->ci->load->model('core/cf_user_model');

            //include the form validation language
            $this->ci->lang->load('form_validation','persian');
            $lang = $this->ci->lang->language;
        
            //set custom message for form validation errors
            $this->ci->form_validation->set_message('required', $lang['form_validation_required']);
            $this->ci->form_validation->set_message('min_length', $lang['form_validation_min_length']);
            $this->ci->form_validation->set_message('max_length', $lang['form_validation_max_length']);
            $this->ci->form_validation->set_message('valid_email', $lang['form_validation_valid_email']);
            $this->ci->form_validation->set_message('matches', $lang['form_validation_matches']);

            //append the extra field's rules to the form validation rules that used in signup condition
            if(!empty ($this->_extraTableName))
            {
                if(is_array($this->_extraFields))
                {
                    foreach ($this->_extraFields as $field)
                    {
                            if(isset($field['rules']) && $field['rules'] != "")
                                array_push($this->ci->form_validation->_config_rules['user_info'], array(
                                            'field' => $field['field_alter_name'],
                                            'label' => 'lang:label_'.$field['field_label'],
                                            'rules' => implode("|", $field['rules'])
                                         ));
                    }
                }
            }
            //check the form validation status
            if ($this->ci->form_validation->run('user_info') != FALSE)
            {
                //check for new user or udpate a exist user
                if(!isset($params['user_id']))
                {
                    if($this->unique_email($params['email']))
                    {
                        $params['password'] = $this->ci->cf_security->generate_hash($params['password']);
                        if($this->ci->cf_user_model->create_user($params,
                                                                 $this->_userTable,
                                                                 $this->_extraTableName,
                                                                 $this->_extraFields))
                            return 'success';
                        else
                            return 'faild';
                    }
                    else
                        return 'notUnique';
                }
                //update the user
                else
                {
                    //create extrafields array of name and altername of fields
                    foreach ($this->_extraFields as $value)
                        $extraFields[$value['field_name']] = $value['field_alter_name'];

                    //remove user_id field for change user record
                    $user_id = $params['user_id'];
                    unset($params['user_id']);
                    
                    foreach($params AS $alterFieldName => $value)
                    {
                        
                        $realFieldName = array_keys($extraFields, $alterFieldName);
                        if(!empty($realFieldName[0]))
                        {
                            if(isset($user->$realFieldName[0]))
                            if($user->$realFieldName[0] != $value)
                                $extraChanged[$realFieldName[0]] = $value;
                        }
                        else
                        {
                            if(isset($user->$alterFieldName))
                            if($user->$alterFieldName != $value)
                                    $fieldChanged[$alterFieldName] = $value;
                        }
                    }
                }
            }
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
        function get_user_by_id($id)
        {
            $this->ci->load->model('core/cf_user_model');

            return $this->ci->cf_user_model->get_user_by_id($id, $this->_userTable, $this->_extraTableName);
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