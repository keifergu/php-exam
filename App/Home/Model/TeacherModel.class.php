<?php
namespace Home\Model;
use Think\Model;
class TeacherModel extends Model {
	protected $tableName = 'teacher_info';

	public function getName($teacherID)
	{
		if (!isset($teacherID)) {
			return false;
		}
		$query = array('teacher_id' => $teacherID);
		$data = $this->where($query)->getField('teacher_name');
		return $data;
	}


	public function getCourse($teacherID)
	{
		if (!isset($teacherID)) {
			return false;
		}
		$query = array('teacher_id' => $teacherID);
		$data = $this->where($query)->getField('course_id');
		return $data;
	}

	public function getUsingPaper($teacherID)
	{
		if (!isset($teacherID)) {
			return false;
		}
		$query = array('teacher_id' => $teacherID);
		$data = $this->where($query)->getField('using_test');
		return $data;
	}
}