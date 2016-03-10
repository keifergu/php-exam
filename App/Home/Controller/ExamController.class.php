<?php
namespace Home\Controller;
use Think\Controller;
class ExamController extends Controller {
public function exam_start($paperID){
        $LoginLogic = D('Login','Logic');
        $studentID = $LoginLogic->loginCheck();
        if ($studentID == null) {
            $this->error('用户未登录',U('Index/login'));
        }

        //获得试卷信息
        $paperModel = D('Exam','Logic');
        $paperInfo  = $paperModel->getPaperInfo($paperID);
        $dictModel  = D('Dict');
        $paperInfo['course_name'] = $dictModel->getName($paperInfo['course_id']);
        $paperInfo['url'] = U('Exam/exam',array('paperID'=>$paperID)); //生成跳转url

        //将试卷信息存入cookie
        $paperinfo = cookie('paperinfo');
        $test_id = $paperinfo['test_id'];
        if ($test_id == null) {
            $cookieInfo = array('paper_id'   => $paperInfo['paper_id'],
                                'total_num'  => $paperInfo['question_num'],
                                'total_time' => $paperInfo['test_time']);
            cookie('paperinfo' , $cookieInfo);
        }
        $this->assign('paper',$paperInfo);
        $this->display();
    }

    public function exam(){
        $LoginLogic = D('Login','Logic');
        $studentID = $LoginLogic->loginCheck();
        if ($studentID == null) {
            $this->error('用户未登录',U('Index/login'));
        }

        $paperID       = I('get.paperID');
        $examLogic     = D('Exam','Logic');
        $examLogic->setStartTime($studentID,$paperID);

        $paperInfo  = $examLogic->getPaperInfo($paperID);
        $this->assign('paper',$paperInfo);
        $this->display();
    }



    public function finish(){
        $LoginLogic = D('Login','Logic');
        $studentID = $LoginLogic->loginCheck();
        if ($studentID == null) {
            $this->error('用户未登录',U('Index/login'));
        }

        $paperID   = I('get.paperID');
        $countLogic = D('Count','Logic');
        $result = $countLogic->countPaperGrade($paperID,$studentID);
        if ($result == false) {
            $resultStr = "分数统计失败";
        }else{
            $resultStr = "分数统计成功";
            $examLogic = D('Exam','Logic');
            $examLogic->setEndTime($studentID,$paperID);
        }
        $this->assign('grade',$resultStr);
    	$this->display();
    }



   public function question()
    {   
        $LoginLogic = D('Login','Logic');
        $studentID = $LoginLogic->apiLoginCheck();

        $paperID = I('post.paperID');
        $num     = i('post.q');
        $examLogic     = D('Exam','Logic');
        $question      = $examLogic->getQuestion($paperID,$num);
        $this->ajaxReturn($question);
    }
    public function getOldAnswer()
    {
        $LoginLogic = D('Login','Logic');
        $studentID = $LoginLogic->apiLoginCheck();

        $data      = I('post.');
        $paperID      = $data['paperID'];
        $num          = $data['num'];
        $examLogic    = D('Exam','Logic');
        $oldAnswer = $examLogic->getOldAnswer($studentID,$paperID,$num);
        $this->ajaxReturn($oldAnswer);
    }
    public function submit()
    {
        $LoginLogic = D('Login','Logic');
        $studentID = $LoginLogic->apiLoginCheck();

        $data         = I('post.');
        $paperID      = $data['paperID'];
        $answer       = $data['answer'];
        $num          = $data['num'];
        $examLogic    = D('Exam','Logic');
        $submitResult = $examLogic->submitAnswer($studentID,$paperID,$num,$answer);
        $this->ajaxReturn($submitResult);
    }
}