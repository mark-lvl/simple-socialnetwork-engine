<?php
class MY_Encrypt extends CI_Encrypt {

    function MY_Encrypt()
    {
        parent::CI_Encrypt();
    }

	function my_encode($string, $key = '')
	{
		$string = parent::encode($string, $key);
		return str_replace('/', '*', $string);
	}

	function my_decode($string, $key = '')
	{
		$key = $this->get_key($key);

		$string = str_replace('*','/' , $string);

		if (preg_match('/[^a-zA-Z0-9\/\+=]/', $string))
		{
			return FALSE;
		}

		$dec = base64_decode($string);

		if ($this->_mcrypt_exists === TRUE)
		{
			if (($dec = $this->mcrypt_decode($dec, $key)) === FALSE)
			{
				return FALSE;
			}
		}

		return $this->_xor_decode($dec, $key);
	}
}

?>
