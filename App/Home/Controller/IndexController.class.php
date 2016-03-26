<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {

    public function index(){
       /*   $hashids = new \Common\Util\Hashids('Your Keys', 12);*/
    	$v = session('student.id');
    	if ($v==null) {
    		$this->redirect('Index/login',array(),0,' ');
    	}else{
    		$this->redirect('User/index',array(),0,' ');
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
                $this->redirect('User/index',array(),0,' ');
            }else{
                $this->assign('error','true');
                $this->display();
            }
    	}else{
            $this->assign('error','none');
    		$this->display();
    	}
    }

    public function logout()
    {
        $LoginLogic = D('Login','Logic');
        $studentID = $LoginLogic->loginCheck();
        session('student_id', null);
        $this->redirect('logout',U('Index/login'));
        
    }
}