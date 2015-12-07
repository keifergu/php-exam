<?php 
function _strDataToArray($strData){
	$array  = array_filter(explode(';',$strData));
	return $array;
}

 ?>
