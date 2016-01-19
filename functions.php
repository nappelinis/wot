<?php

function pre($value, $return = false, $offset = NULL) {
	
	$str = '';
	if(is_numeric($offset)) {
		for($i = 0; $i <= $offset; $i++) {
			$str .= '<br  />';
		}
	}
	
	if ($value === null)
		$str .= '<pre>NULL</pre>';
	else if ($value === false)
		$str .= '<pre>false</pre>';
	else if ($value === true)
		$str .= '<pre>true</pre>';
	else if ($value === '')
		$str .= '<pre>(empty string)</pre>';
	else
		$str .= '<pre>'.print_r($value, true).'</pre>';
	
	if ($return)
		return $str;
	
	echo $str;
}

