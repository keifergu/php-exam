<?php
namespace Home\Logic;
class StudentLogic {

	/*public function getPaperList($studentID)
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
	}*/

	public function getPaperList($studentID,$times=0)
	{
		if(!isset($studentID)){
			return 'getPaperlist,ID is not set';
		}

		$TestModel = D('Test');
		$PaperModel= D('Paper');
		$query = array('student_id'=>$studentID,
						'status'   =>$times);
		$finish_paper = $TestModel->where($query)->field('finish_paper')->select();
		foreach ($finish_paper as $key => $value) {
			$paperList[$value['finish_paper']] = $PaperModel->getPaperName($value['finish_paper']);
		}
		return $paperList;
	}

	public function getPaperName($value='')
	{
		# code...
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
	public function getAllGrade($studentID)
	{
		if(!isset($studentID)){
			return 'getCourse ID is not set';
		}
		$GradeModel = D('Summary');
		$PaperModel = D('Paper');
		$query = array('student_id' => $studentID);
		$field 	= array('paper_id','grade' , 'submit_time');
		$gradeInfo = $GradeModel->where($query)->field($field)->select();
		if($gradeInfo != null){

			foreach ($gradeInfo as $key => $value) {
				$gradeInfo[$key]['name'] = $PaperModel->getPaperName($value['paper_id']);
			}
		}
		return $gradeInfo;
	}
}