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


	public function getPaperList($studentID)
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
	}
	public function getFinishPaper($studentID)
	{
		if(!isset($studentID)){
			return 'ID is not set';
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
	public function getStudentInfo($studnetID)
	{
		if(!isset($studentID)){
			return 'ID is not set';
		}
		$studentModel = D('Student');
		$teacherModel = D('Teacher');
	}
}