<?php
namespace Home\Model;
use Think\Model;
class DictModel extends Model {
	protected $tableName = 'data_dict';


	public function getName($id)
	{
		if (isset($id)) {
			$query = array('type_id' => $id);
			$name  = $this->where($query)->getField('type_name');
			return $name;
		} else {
			return 'ID is not set';
		}
		
	}

	public function getAllCourse()
	{
		$query = array('belong_type' => '200');
		$field  	= array('type_id','type_name');
		$data 	= $this->where($query)->field($field)->select();
		foreach ($data as $key => $value) {
			$typeID = $value['type_id'];
			$type_name = $value['type_name'];
			$returnData[$typeID] = $type_name; 
		}
		return $returnData;
	}
}