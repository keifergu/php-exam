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
    public function question()
    {
        $paperID = I('post.paperID');
        $num     = i('post.q');
        $examLogic     = D('Exam','Logic');
        $question      = $examLogic->getQuestion($paperID,$num);
        $this->ajaxReturn($question);
    }
    public function getOldAnswer()
    {
        $studentID = '123';
        $data      = I('post.');
        $paperID      = $data['paperID'];
        $num          = $data['num'];
        $examLogic    = D('Exam','Logic');
        $oldAnswer = $examLogic->getOldAnswer($studentID,$paperID,$num);
        $this->ajaxReturn($oldAnswer);
    }
    public function submit()
    {
        $studentID    = '123';
        $data         = I('post.');
        $paperID      = $data['paperID'];
        $answer       = $data['answer'];
        $num          = $data['num'];
        $examLogic    = D('Exam','Logic');
        $submitResult = $examLogic->submitAnswer($studentID,$paperID,$num,$answer);
        $this->ajaxReturn($submitResult);
    }
    public function set()
    {
        $studentID = '123';
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
        $studentID = '123';
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
                $this->success('登录成功',U('Index/user'));
            }else{
                $this->error('登录失败',U('Index/login'));
            }
    	}else{
    		$this->display();
    	}
    }
    public function user(){
    	/*$stuId = session('student.id');
    	if ($stuid==null) {
    		$this->error('用户未登录','login.html');
    	}*/
        $studentID = '123';
        $studentLogic = D('Student','Logic');
        $paperList    = $studentLogic->getPaperList($studentID);
        foreach ($paperList as $key => $value) {
            $testUrl    = U('Index/exam_start',array('paperID' => $key));
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

    public function exam_start($paperID){
        $paperModel = D('Exam','Logic');
        $paperInfo  = $paperModel->getPaperInfo($paperID);
        $dictModel  = D('Dict');
        $paperInfo['course_name'] = $dictModel->getName($paperInfo['course_id']);
        $paperInfo['url'] = U('Index/exam',array('paperID'=>$paperID)); //生成跳转url
        $this->assign('paper',$paperInfo);
        $this->display();
    }

    public function exam(){
        //$studentID = session('student.id');
        $studentID     = '123';
        $paperID       = I('get.paperID');
        $examLogic     = D('Exam','Logic');
        $paperInfo  = $examLogic->getPaperInfo($paperID);
        $this->assign('paper',$paperInfo);
        $this->display();
    }



    public function finish(){
    	$this->display();
    	$this->redirect('Index/user',array(),2,' ');
    }


    public function uploadOption(){
    	$this->display();
    }
    public function optionWrite(){
	    $upload = new \Think\Upload();// 实例化上传类
	    $upload->maxSize   =     3145728 ;// 设置附件上传大小
	    $upload->exts      =     array('xls', 'xlsx', 'png', 'jpeg');// 设置附件上传类型
	    $upload->rootPath  =     'public/'; // 设置附件上传根目录
	    $upload->savePath  =     ''; // 设置附件上传（子）目录
	    // 上传文件 
	    $info   =   $upload->upload();
	    dump($info);
	    if(!$info) {// 上传错误提示错误信息
	        $this->error($upload->getError());
	    }else{// 上传成功
	        $this->show('上传成功');
	    }

		$excel = new \Tools\Excel;
		$file_name='Public/'.$info['file_stu']['savepath'].$info['file_stu']['savename'];
		dump($file_name);
		$read = $excel->reader($file_name);
		dump($read);
	  	/*
	    重要代码 解决Thinkphp M、D方法不能调用的问题  
	    如果在thinkphp中遇到M 、D方法失效时就加入下面一句代码
		  */
	  	//spl_autoload_register ( array ('Think', 'autoload' ) );
	  	/*对生成的数组进行数据库的写入*/
	  	
  		foreach ( $read as $k => $v ) 
	  	{
	    	if ($k != 0) 
	   		{
	  		    $type = $v [0];
	  		    switch ($type) {
	  		    	case '单选'||'单项选择'||'单项选择题':
	  		    		$data['type'] ='101';
  		    			break;
	  		    	case '多选'||'多项选择'||'多项选择题':
	  		    		$data['type'] = '102';
	  		    		break;
	  		    	case '判断'||'判断题':
	  		    		$data['type'] = '103';
	  		    		break;
	  		    	default:
	  		    		$this->error('错误');
	  		    		break;
	  		    }

		      	$result = M ( 'user' )->add ( $data );
		     	if (! $result) 
			    {
			       $this->error ( '导入数据库失败' );
			    }
			}
  		}

	}

}