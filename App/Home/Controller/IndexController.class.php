<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {

    public function test()
    {
        $sub = D('Count','Logic');
        $sub->countPaperGrade('201004','111');
        $this->display();
    }
 
    public function set()
    {
        $LoginLogic = D('Login','Logic');
        $studentID = $LoginLogic->apiLoginCheck();

        $data = I('post.');
        $StudentModel = D('Student','Logic');
        switch ($data['type']) {
            case 'course':
                $result = $StudentModel->addCourse($studentID,$data['data']);
                break;
            
            default:
                $result = false;
                break;
        }
        $this->ajaxReturn($result);
    }

    public function remove()
    {
        $LoginLogic = D('Login','Logic');
        $studentID = $LoginLogic->apiLoginCheck();

        $data = I("post.");
        //此处应该对数据的合法性进行检查
        $StudentModel = D('Student','Logic');
        switch ($data['type']) {
            case 'course':
                $result = $StudentModel->removeCourse($studentID,$data['data']);
                break;
            
            default:
                $result = 'false';
                break;
        }
        $this->ajaxReturn($result);
    }
    public function index(){
       /*   $hashids = new \Common\Util\Hashids('Your Keys', 12);*/
    	$v = session('student.id');
    	if ($v==null) {
    		$this->redirect('Index/login',array(),0,' ');
    	}else{
    		$this->redirect('Index/user',array(),0,' ');
    	}
    }

    public function login(){
    	$student_id = I('param.username','','htmlspecialchars');
    	$password 	= I('param.password');
    	if ($student_id != '' and $password != '') {
            $StudentModel = D('Student');
            $result = $StudentModel->loginUser($student_id,$password);
            if ($result) {
                session('student_id',$student_id);
                $this->success('登录成功',U('User/index'));
            }else{
                $this->error('登录失败',U('Index/login'));
            }
    	}else{
    		$this->display();
    	}
    }

    public function logout()
    {
        $LoginLogic = D('Login','Logic');
        $studentID = $LoginLogic->loginCheck();


    }
}