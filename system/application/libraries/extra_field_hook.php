<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Extra_field_hook {
	private $ci;
	/**
	 * Constructor - Initializes and references CI
	 */
	function Extra_field_hook() {
		$this->ci = & get_instance();
	}

        function _hook_city($param, $user = "")
        {
            //run the hook
        }
}