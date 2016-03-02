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

	public function getPaperID($studentID,$courseID){
		$ret = false;
		if(isset($studentID) && isset($courseID)){
			$ret = $this -> where(array('student_id'=>$student_id,'studentt_course'=>$courseID))->find()['finish_paper'];
		}
		return $ret;
	}
	public function addItem($studentID,$studentName,$studentCourse,$finishPaper){
		$ret = false;
		if(isset($studentID) && isset($studentName) && isset($studentCourse) $$ isset($finishPaper)){
			$data= array('student_id'=>$studentID,
				'student_name'=>$studentName,
				'student_course'=>$studentCourse,
				'finish_paper'=>$finishPaper);
			$ret = $this->add($data);
		}
	}

}
?>