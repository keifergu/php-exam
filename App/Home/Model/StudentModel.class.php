<?php
namespace Home\Model;
use Think\Model;
class StudentModel extends Model {
    protected $tableName = 'student_info'; // 默认打开的表，学生信息

    protected $tableAuth = 'student_auth'; //登录验证表

    public function loginUser($studentID,$password){
    	$t_auth = M($this->tableAuth);
    	$query = array('student_id' => $studentID);
		$data = $t_auth->where($query)->getField('student_password'); //根据ID获得信息
		if($data == null){       //没有ID信息，返回false
			return false;
		}elseif ($password == $data) {  // 验证成功，返回true
			return true;
		}else{                   //验证失败
			return false;
		}
    }

    public function getName($studentID){
    	if (isset($studentID)) {
    		$query = array('student_id' => $studentID);
    		$name = $this->where($query)->getField('student_name');
    		return $name;
    	}else{
    		return 'id is not set';
    	}
    }

    public function getCourseList($studentID)
    {
    	if (isset($studentID)) {
    		$query = array('student_id' => $studentID);
    		$course = $this->where($query)->getField('student_course');
    		$courseList  = array_filter(explode(',', $course));
    		return $courseList;
    	}else{
    		return 'id is not set';
    	}		
    }

    public function getTeacherList($studentID)
    {
    	if (isset($studentID)) {
    		$query = array('student_id' => $studentID);
    		$teacher = $this->where($query)->getField('belong_teacher');
    		$teacherList  = array_filter(explode(',', $teacher));
    		return $teacherList;
    	}else{
    		return 'id is not set';
    	}	    	
    }

    public function getFinishPaper($studentID)
    {
    	if (isset($studentID)) {
    		$query = array('student_id' => $studentID);
    		$paper = $this->where($query)->getField('finish_paper')->find();
    		$paperList  = array_filter(explode(',', $paper));
    		return $paperList;
    	}else{
    		return 'id is not set';
    	}	
    }
}