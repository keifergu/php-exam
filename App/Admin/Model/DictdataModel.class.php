<?php
namespace Admin\Model;
use Think\Model;

class DictdataModel extends Model
{
    protected $tableName = 'data_dict';
    protected $pk        = 'dictid';
    public    $error;
    
    public function  getInfo($dictid)
    {
        $dict_db = D('Dictdata');
        $info = $this->where(array('dict_id'=>$dictid))->find();
        return $info;
    }

    public function getID($name,$belong){
    	$condition=array(
            'type_name'=>$name,
            'belong_type'=>$belong
            );
        $result=$this->where($condition)->find()['type_id'];
        return $result;
    }
    public function getName($id){
        $condition=array('type_id'=>$id);
        $result= $this->where($condition)->find()['type_name'];
        return $result;
    }
}
