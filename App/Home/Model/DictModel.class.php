<?php
namespace Home\Model;
use Think\Model;
class DictModel extends Model {
	protected $tableName = 'sys';


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
}