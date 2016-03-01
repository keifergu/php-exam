<?php

namespace Admin\Model;

use Think\Model;

class StuImportModel extends Model {
	protected $tableName = 'student_test';
	public function delItem($studentID,$paperID){
		$ret = false;
		if(isset($studentID)&& isset($paperID)){
			$condition=array('student_id'=>$studentID,'finish_paper'=>$paperID);
			$ret = $this->where($condition)->delete();
		}
		return $ret;
	}

	

}
?>