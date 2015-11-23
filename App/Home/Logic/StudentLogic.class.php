<?php
namespace Home\Logic;
class StudentLogic {


	// public function getPaperList($studentID)
	// {
	// 	if(!isset($studentID)){
	// 		return 'ID is not set';
	// 	}
	// 	$studentModel  = D('Student');
	// 	$teacherList   = $studentModel->getTeacherList($studentID);
	// 	$teacherModel  = D('Teacher');
	// 	$paperModel	   = D('Paper');
	// 	foreach ($teacherList as $key => $teacher) {
	// 		$paperID   = $teacherModel->getUsingPaper($teacher);
	// 		$paperName = $paperModel->getPaperName($paperID);
	// 		$paperList = array($paperID => $paperName);
	// 	}
	// 	return $paperList;
	// }

	//该类是通过数据库中的student_info中的belong_teacher字段获得所属教师,然后根据teacher_info中的信息获得试卷list
	/*public function getPaperList($studentID)
	{
		if(!isset($studentID)){
			return 'ID is not set';
		}
		$studentModel = D('Student');
		$teacherModel = D('Teacher');
		$paperModel	  = D('Paper');
		$queryStu	  = array('student_id' =>$studentID);
		//获得学生的教师列表
		$teacherList  = _strDataToArray($studentModel->where($queryStu)->getField('belong_teacher')); //此处教师字段为用分号分开的教师ID
		$finishList  = _strDataToArray($studentModel->where($queryStu)->getField('finish_paper'));
		//获得每个教师所设置的当前测试的试卷
		foreach ($teacherList as $key => $value) {
			$queryTea = array('teacher_id' => $value);
			$paperID  = $teacherModel->where($queryTea)->getField('using_test');
			if (in_array($paperID, $finishList)) {
				continue;
			}else{
				$queryPap = array('paper_id' => $paperID);
				$paperName= $paperModel->where($queryPap)->getField('paper_name'); 
				$paperList[$paperID] = $paperName;
			}
		}
		return $paperList; 
	}*/

	public function getPaperList($studentID)
	{
		if(!isset($studentID)){
			return 'getPaperlist,ID is not set';
		}
		$studentModel = D('Student');
		$paperModel	  = D('Paper');
		$queryStu	  = array('student_id' =>$studentID);
		//获得学生的课程列表
		$courseList  = json_decode($studentModel->where($queryStu)->getField('student_course')); //获得学生所选择的课程
		if (is_null($courseList) OR $courseList == '') {
			return false;
		}
		$finishList  = _strDataToArray($studentModel->where($queryStu)->getField('finish_paper'));
		//获得每个教师所设置的当前测试的试卷
		foreach ($courseList as $key => $value) {
			$queryPaper = array('course_id' => $value);
			$field 	          = array('paper_id','paper_name');
			$paperInfo    = $paperModel->where($queryPaper)->field($field)->select();
			foreach ($paperInfo as $key => $value) {
				$paperID = $value['paper_id'];
				$paperName = $value['paper_name'];
				if (in_array($paperID, $finishList)) {
					continue;
				}else{
					$paperList[$paperID] = $paperName;
				}
			}
		}
		return $paperList; 
	}

	public function getFinishPaper($studentID)
	{
		if(!isset($studentID)){
			return 'getpaper,ID is not set';
		}
		$studentModel = D('Student');
		$paperModel	  = D('Paper');
		$queryStu	  = array('student_id' =>$studentID);
		//获得学生的教师列表
		$paperNumList  = _strDataToArray($studentModel->where($queryStu)->getField('finish_paper')); //此处教师字段为用分号分开的教师ID
		//获得每个教师所设置的当前测试的试卷\
		foreach ($paperNumList as $key => $value) {
			$queryPap = array('paper_id' => $value);
			$paperName= $paperModel->where($queryPap)->getField('paper_name'); 
			$paperList[$value] = $paperName;
		}
		return $paperList; 
	}
	public function getStudentInfo($studentID)
	{
		if(!isset($studentID)){
			return 'getStudentInfo,ID is not set';
		}
		$studentModel = D('Student');
		$dictModel	= D('Dict');
		$query 		= array('student_id'=>$studentID);
		$field 		= array('student_name' , 'student_course');
		$info 		= $studentModel->where($query)->field($field)->find();
		$studentInfo['name']    = $info['student_name'];
		$courseArr = json_decode($info['student_course']);
		foreach ($courseArr as $key => $value) {
			$course[$value] = $dictModel->getName($value);
		}
		$studentInfo['course']  = $course;
		return $studentInfo;
	}

	public function getAllCourse()
	{
		$dictModel = D('Dict');
		$data          =  $dictModel->getAllCourse();
		return $data;
	}
	public function addCourse($studentID,$value)
	{
		if(!isset($studentID)){
			return 'addCourse,ID is not set';
		}
		$studentModel = D('Student');
		$query = array('student_id' => $studentID);
		$courseArr =json_decode($studentModel->where($query)->getField('student_course'));
		if ($courseArr == null) {
			$course['0'] = $value;
			$reslut   = $studentModel->where($query)->setField('student_course',json_encode($course));
		}elseif ( ! in_array($value, $courseArr)) {
			array_push($courseArr, $value);
			$course = json_encode($courseArr);
			$reslut   = $studentModel->where($query)->setField('student_course',$course);
		}else{
			$reslut = false;
		}
		return $reslut;
	}
	public function removeCourse($studentID,$value){
		if(!isset($studentID)){
			return 'addCourse,ID is not set';
		}
		$studentModel = D('Student');
		$query = array('student_id'=>$studentID);
		$courseArr =json_decode($studentModel->where($query)->getField('student_course'));
		//$value 可以为一个数组
		$value =array_values($value);
		$courseDiff = json_encode(array_diff($courseArr, $value));
		$reslut = $studentModel->where($query)->setField('student_course',$courseDiff);
		return $reslut;
	}
}