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

	function setMessageForValidation()
	{
		//include the form validation language
		$this->ci->lang->load('form_validation','persian');
		$lang = $this->ci->lang->language;

		//set custom message for form validation errors
		$this->ci->form_validation->set_message('required', $lang['form_validation_required']);
		$this->ci->form_validation->set_message('min_length', $lang['form_validation_min_length']);
		$this->ci->form_validation->set_message('max_length', $lang['form_validation_max_length']);
		$this->ci->form_validation->set_message('valid_email', $lang['form_validation_valid_email']);
		$this->ci->form_validation->set_message('matches', $lang['form_validation_matches']);
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

		$this->setMessageForValidation();

		//append the extra field's rules to the form validation rules that used in signup condition
		if(!empty ($this->_extraTableName))
		{
			if(is_array($this->_extraFields))
			{
				foreach ($this->_extraFields as $field)
				{
						if(isset($field['rules']) && $field['rules'] != "")
							array_push($this->ci->form_validation->_config_rules['signup'], array(
										'field' => $field['field_alter_name'],
										'label' => 'lang:label_'.$field['field_label'],
										'rules' => implode("|", $field['rules'])
									 ));
				}
			}
		}
		//check the form validation status
		if ($this->ci->form_validation->run('signup') != FALSE)
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
	}

	/**
	 * update the user
	 * @param <ARRAY> $params user details
	 * @param <OBJECT> $user user object for edit attributes
	 * @return <BOOL> success/failed process
	 */
	function update_user($params, $user)
	{
		//set the validation message for this form
		$this->setMessageForValidation();

		//hold changed data user field
		$fieldChanged = array();
		//hold changed data user extra field
		$extraChanged = array();

		//create extrafields array from name and altername of fields
		foreach ($this->_extraFields as $value)
			$extraFields[$value['field_name']] = $value['field_alter_name'];

		//remove user_id field for change user record
		$user_id = $params['user_id'];
		unset($params['user_id']);

		//fetch the changed fields
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

		//run hook for each field
		if(is_array($extraChanged) && !empty($extraChanged))
			foreach($extraChanged AS $extraName => $postValue)
			{
				$hook = $this->_extraFields[$extraName]['hook'];
				if($hook != "")
				{
					$this->ci->load->library('extra_field_hook');
					$this->ci->extra_field_hook->$hook($params[$this->_extraFields[$extraName]['field_alter_name']], $user);
				}

			}

		$this->ci->load->model('core/cf_user_model');

		if(!empty($fieldChanged) || !empty ($extraChanged))
			if($this->ci->cf_user_model->update_user($user_id,
												  $this->_userTable,
												  $this->_extraTableName,
												  $fieldChanged,
												  $extraChanged))
			{
				$user = $this->ci->cf_user_model->get_user_by_id($user_id, $this->_userTable, $this->_extraTableName);


				$this->ci->session->set_userdata($user, (array) $user);
				return TRUE;
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
     * find or search users with desired name (with limitation)
	 * @param <STRING> $tableName the name of user's table
	 * @param <STRING> $extraTable the name of user's table extra fields
	 * @param <INT> $user id of authenticated user
	 * @param <INT> $anotherUser id of partner user
	 * @param <STRING> $filter a slice of user's name
	 * @return <ARRAY> return desired users by filter
	 */
	function find_users($filter = "", $offset = 0, $limit = 8 )
	{
		$this->ci->load->model('core/cf_user_model');

		return $this->ci->cf_user_model->get_desire_users($this->_userTable, $this->_extraTableName, $offset, $limit, $filter);

	}

}