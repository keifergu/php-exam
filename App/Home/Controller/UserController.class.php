<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends Controller {
	public function index(){
	    	$LoginLogic = D('Login','Logic');
	        $studentID = $LoginLogic->loginCheck();
	        if ($studentID == null) {
	            $this->error('用户未登录',U('Index/login'));
	        }


	        $studentLogic = D('Student','Logic');
	        $paperList    = $studentLogic->getPaperList($studentID);
	        foreach ($paperList as $key => $value) {
	            $testUrl    = U('Exam/exam_start',array('paperID' => $key));
	            $testData[] = array('testId'=> $key,'testName'=>$value,'examUrl'=>$testUrl);
	        }
	        $finishPaper = $studentLogic->getFinishPaper($studentID);
	        foreach ($finishPaper as $key => $value) {
	            //$testUrl    = U('Index/exam_start',array('paperID' => $key));
	            $finishPaperData[] = array('testId'=> $key,'testName'=>$value);
	        }
	        $studentInfo = $studentLogic->getStudentInfo($studentID);
	        $courseInfo   = $studentLogic->getAllCourse();
	        $gradeInfo     = $studentLogic->getAllGrade($studentID);
	        $this->assign('grade',$gradeInfo);
	        $this->assign('course',$courseInfo);
	        $this->assign('user',$studentInfo);
	        $this->assign('testData',$testData);
	        $this->assign('finishPaper',$finishPaperData);
	        $this->display();
	    }
}