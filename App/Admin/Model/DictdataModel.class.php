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
    $ret = false;
    if(isset($belong) && isset($name)){
      $ret=$this->where(array('type_name'=>$name,'belong_type'=>$belong ))->find()['type_id'];
    }
    return $ret;
  }
  public function getName($id){
    $ret = false;
    if(isset($id)){
     $ret= $this->where(array('type_id'=>$id))->find()['type_name'];
   }

   return $ret;
 }
 public function getTypeList(){
  $ret  = $this->where(array('belong_type'=>100))->select();
  return $ret;
}
}