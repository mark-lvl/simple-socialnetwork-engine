<?php
if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Athentication class
 * 2010-09-17
 * Fouad Amiri info@fouad.ir
 */
class Cf_security {
	private $ci;
	/**
	 * Constructor - Initializes and references CI
	 */
	function Cf_security() {
		$this->ci = & get_instance();
	}
	/**
	 * Generates a random hash using PHPass 0.3 library and returns the hash
	 * @param <STRING> $password
	 * @return <STRING>
	 */
	function generate_hash($password) {
		//Less secure hash, only md5
		return md5($password);

		/* More secure hash
		  $this->ci->load->library("core/passwordhash");

		  $t_hasher = new PasswordHash();
		  $t_hasher->HashStarter($this->ci->config->item('pass_hash_strength'), FALSE);

		  return $t_hasher->HashPassword($password);
		 */
	}
	/**
	 * Checks if the given password matches the given hash
	 * @param <STRING> $password
	 * @param <STRING> $hash
	 * @return <BOOLEAN>
	 */
	function check_hash($password, $hash) {

		//Less secure, only md5 hash
		if (md5($password) == $hash) {
			return TRUE;
		}
		return FALSE;

		/* Mor secure password
		  $this->ci->load->library("core/passwordhash");

		  $t_hasher = new PasswordHash();
		  $t_hasher->HashStarter($this->ci->config->item('pass_hash_strength'), FALSE);

		  if($t_hasher->CheckPassword($password, $hash)) {
		  return TRUE;
		  }

		  return FALSE;
		 */
	}
}