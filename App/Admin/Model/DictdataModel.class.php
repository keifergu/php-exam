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
}