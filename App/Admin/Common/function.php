<?php
/**
 * 获取数据字典
 * @param $key      //键值，方便查找数据
 * @param $fileName //字典文件名 目录Common/Dict/
 * @return mixed
 */
function dict($key = '', $fileName = 'Setting') {
    static $_dictFileCache  =   array();
    $file = MODULE_PATH . 'Common' . DS . 'Dict' . DS . $fileName . '.php';
    if (!file_exists($file)){
    	unset($_dictFileCache);
    	return null;
    }
    if(!$key && !empty($_dictFileCache)) return $_dictFileCache;
    if ($key && isset($_dictFileCache[$key])) return $_dictFileCache[$key];
    $data = require_once $file;
    $_dictFileCache = $data;
    return $key ? $data[$key] : $data;
}
function object_array($array){
    if(is_object($array)){
        $array = (array)$array;
    }
    if(is_array($array)){
        foreach($array as $key=>$value){
            $array[$key] = object_array($value);
        }
    }
    return $array;
}
function getDictName($id)
{
    $dict_db=D('Dictdata');
    $field=array('type_id','type_name');
    $condition['type_id']=$id;
    $result=$dict_db->field($field)->where($condition)->select();
    return $result;
}

///str表示需要补零的数字，bit表示形成多少位编码
function strFormat($str,$bit)
{
    $num_len = strlen($str);
    $zero = '';
    for($i=$num_len; $i<$bit; $i++){
        $zero .= "0";
    }
    $real_num = $zero.$str;
    return $real_num;
}

//二进制数转字符串
function ansToChar($num,$type){
    if($type==101||$type==103){
        return chr(64+$num);
    }
    $ans='';
    for ($i=0; $i < 8; $i++) { 
        if(($num&(1<<$i))==(1<<$i)){
            $ans=$ans.chr(65+$i);
        }
    }
    return $ans;
}


function isRadioAns($ans){
    for ($i=0; $i < 8; $i++) { 
        if($ans == 1<<$i)  return true;
        else return false;
    }
}