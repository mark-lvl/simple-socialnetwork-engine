<?php
if ( ! function_exists('convert_number'))
{
	function convert_number($txt)
	{
		$new_txt = str_spliter($txt);
		$replace = array('&#1776;','&#1777;', '&#1778;','&#1779;','&#1780;','&#1781;','&#1782;','&#1783;','&#1784;','&#1785;');

		for($i = 0; $i <= 9; $i++)
		{
			$keys = array_keys($new_txt, '' . $i . '');
			foreach($keys as $x => $k)
			{
				$new_txt[$k] = str_replace('' . $i . '', $replace[$i], $new_txt[$k]);
			}
		}
		$new_txt = implode('', $new_txt);

		return $new_txt;
	}
}

if ( ! function_exists('str_spliter'))
{
	function str_spliter($str)
	{
		$str_array=array();
		$len=strlen($str);
		for($i=0; $i < $len; $i++)
		{
			$str_array[]=$str{$i};
		}
		return $str_array;
	}
}