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
                $this->success('登录成功',U('Index/user'));
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
    public function user(){
    	$LoginLogic = D('Login','Logic');
        $studentID = $LoginLogic->loginCheck();
        if ($studentID == null) {
            $this->error('用户未登录',U('Index/login'));
        }


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
        $paperInfo['url'] = U('Index/exam',array('paperID'=>$paperID)); //生成跳转url

        //将试卷信息存入cookie
        $paperinfo = cookie('paperinfo');
        $test_id = $paperinfo['test_id'];
        if ($test_id == null) {
            $cookieInfo = array('id'    => $paperInfo['paper_id'],
                                'total' => $paperInfo['question_num'],
                                'time'  => $paperInfo['test_time']);
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
            $examLogic     = D('Exam','Logic');
            $examLogic->setEndTime($studentID,$paperID);
        }
        $this->assign('grade',$resultStr);
    	$this->display();
    	//$this->redirect('Index/user',array(),2,' ');
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