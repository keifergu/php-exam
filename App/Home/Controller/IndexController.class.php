<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
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
       /* $c = D('Paper');
        dump($c->getPaperName('201001'));
        dump($c->getPaperInfo('201001'));
        dump($c->getPaperContent('201001'));
        $a = D('Student');
        dump($a->getName('123'));
        dump($a->getTeacherList('123'));
        $c = D('Teacher');
        dump($c->getName('112'));
        $d = D('Student','Logic');
        $d->getPaperList('123');
        $hashids = new \Common\Util\Hashids('Your Keys', 12);*/
    	$v = session('student.id');
    	if ($v==null) {
    		$this->redirect('Index/login',array(),0,' ');
    	}else{
    		$this->redirect('Index/user',array(),0,' ');
    	}
    	//$this->display();
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
        $this->assign('course',$courseInfo);
        $this->assign('user',$studentInfo);
        dump($studentInfo);
        $this->assign('testData',$testData);
        $this->assign('finishPaper',$finishPaperData);
        $this->display();
    	/*$stuId = '123';
    	$t_stuInfo  = M(C('STUDENT_INFO'));  		//打开学生信息表
    	$t_teaInfo  = M(C('TEACHER_INFO'));	 	//打开教师信息表
    	$t_testInfo = M(C('TESTPAPER_CONTENT'));	//打开试卷表
    	$stuData    = $t_stuInfo->where('student_id='.$stuId)->find();	//根据session中的id获得信息
    	$teacherId  = array_filter(explode(';', $stuData['belong_teacher']));
    	/**
    	 * 方法一：使用foreach，对每一个teacherId进行一次查询，
    	 * 		   获得using_test
    	 */
    	/*foreach ($teacherId as $key => $value) {
    		$testId 	= $t_teaInfo ->where('teacher_id='.$value)->getField('using_test');
    		$testName   = $t_testInfo->where('paper_id='.$testId)->getField('paper_name');
    		$testUrl	= U('index/exam_start','exam_id='.$testId);
    		$testData[] = ['testId'=> $testId,'testName'=>$testName,'examUrl'=>$testUrl];  //testData数组保存了获得的 试卷id
    	 }
    	/**
    	 * 方法二：使用array_map函数，调用下面自己写的回调函数
    	 * 			对每一个传入的teacherId值进行一次查询
    	 *          失败，无法调用回调函数
    	 */
    	//$testData = array_map($this->getPaper(), $teacherId);


    	
 
    }

    public function exam_start($paperID){
    	//$exam_id = 201001;

        $paperModel = D('Exam','Logic');
        $paperInfo  = $paperModel->getPaperInfo($paperID);
        $dictModel  = D('Dict');
        $paperInfo['course_name'] = $dictModel->getName($paperInfo['course_id']);
        /*
    	$t_testPaper = M(C('TESTPAPER_CONTENT'));
    	$t_sysdict	 = M(C('SYS_DICT'));
    	//获取除了答案和课程id之外的所有信息
    	$paperInfo	 = $t_testPaper->where('paper_id='.$exam_id)->field('answer',true)->find();
    	$course_name = $t_sysdict->where('type_id='.$paperInfo['course_id'])->find();*/
    	$paperInfo['url'] = U('Index/exam',array('paperID'=>$paperID)); //生成跳转url
    	$this->assign('paper',$paperInfo);
    	$this->display();
    	/*//将获取到的试卷内题目的id以数组的形式缓存下来
    	$questionId  = explode(';', $paperInfo['content']);
    	F($exam_id.'questionId',$questionId);
    	//dump($paperInfo);*/
    	
    }

    public function exam(){
        //$studentID = session('student.id');
        $studentID     = '123';
        $paperID       = I('get.paperID');
        //$num           = I('get.q',1);
        $examLogic     = D('Exam','Logic');
        $paperInfo  = $examLogic->getPaperInfo($paperID);
        //$question      = $examLogic->getQuestion($paperID,$num);
        // $nextAndLast   = $examLogic->getQuestionID($paperID,$num);
        //$oldAnswer = $examLogic->getOldAnswer($studentID,$paperID,$num);
        //$question['old_answer'] = $oldAnswer;
        /*$Url['status'] = $examLogic->getStatus($paperID,$num);

        $Url['next']   = U('Index/exam',array('paperID' => $paperID,
                                              'q'       => $num+1));
        $Url['last']   = U('Index/exam',array('paperID' => $paperID,
                                              'q'       => $num-1));
        
        $answer = I('get.answer');
        echo "answer";
        dump($answer);
        $submitResult = $examLogic->submitAnswer($studentID,$paperID,$num-1,$answer);*/
        //$this->assign('url',$Url);
        //$this->assign('question',$question);
        //$this->assign('num',$num);
        
        $this->assign('paper',$paperInfo);
        $this->display();

















/*
    	//$stuId = session('student.id');
    	$student_id = '123';
    	//get方法传递的试卷的第几题，num
    	//所属试卷的id,exam_id
    	$num 	 = I('get.num',1);
    	$exam_id = I('get.exam_id');
    	$get_type= I('get.type');
    	switch ($get_type) {
    		case '101':  //单选
    			$answer = I('post.answer');
    			break;
    		case '102':    //多选题的多个答案
    			$getanswer = I('post.');
    			$answer    = implode(',', $getanswer);
    			break;
			case '103':
				$answer = I('post.answer');
				break;
    		default:
    			
    			break;
    	}
    	$cache_id   = F($exam_id.'questionId'); //获得缓存的该试卷所有题目id
    	$total_num  = count($cache_id,0);       //获得题目的总数
    	$t_submit   = M(C('TEST_SUBMIT')); // 试卷提交的表
    	if (isset($answer) and $answer != '' and $answer!= array()) {
	    	$course  = I('get.course');
	    	//将post的答题信息保存到数据库
	    	$submitdata = array('submit_id'   => $exam_id.$student_id.$cache_id[$num-2],
	    						'paper_id'    => $exam_id,
	    						'course_id'   => $course,
	    						'student_id'  => $student_id,
	    						'question_id' => $cache_id[$num-2],
	    						'type'		  => $get_type,
	    						'num'         => $num-1,
	    						'answer'	  => $answer);
	    	$t_submit = M(C('TEST_SUBMIT'));
	    	$t_submit->add($data=$submitdata,$option=array(),$replace=true);
	    	//最后一题时的情况
	    	if ($total_num == $num-1) {
	    		$t_stuInfo = M(C('STUDENT_INFO'));
	    		$data['finish_paper'] = $exam_id.';';
	    		$t_stuInfo->where('student_id='.$student_id)->field('finish_paper')->save($data);
		    	$this->redirect('Index/finish');
		    }
	    }
   		

    	//从数据库题目表中获取试题信息
	    $t_question = M(C('QUESTION_CHOICE'));
    	$question 	= $t_question->where('question_id='.$cache_id[$num-1])->field('answer',true)->find();


    	//检查试卷提交的表中是否有该题
    	$condition  = array('submit_id'    => $exam_id.$student_id.$cache_id[$num-1],
    						'paper_id'     => $exam_id,
    						'student_id'   => $student_id,
    						'question_id'  => $cache_id[$num-1]);
    	//dump($condition);
    	$is_save    = $t_submit->where($condition)->find();
    	if (isset($is_save) and $is_save != null) {
    			$question['old_answer'] = $is_save['answer'];  //如果有 该题，则添加old_answer值表示曾经的答案
    	}
    	

    	$question = array_filter($question); //删除空数组
    	$question['option'] = array('A'=>$question['a'],'B'=>$question['b'],
    								'C'=>$question['c'],'D'=>$question['d'],
    								'E'=>$question['e'],'F'=>$question['f'],
    								'G'=>$question['g'],'H'=>$question['h']);
    	$question['option'] =array_filter($question['option']);
    	$question['num']	= $num;
    	//dump($question);
    	//构建下一题，上一题的提交地址
    	switch ($num) {
    		case '1':
				$Url['status'] = 'first';
    			break;
			case $total_num:
				$Url['status'] = 'end';
				break;
    		default:
    			$Url['status'] = 'mid';
    			break;
    	}
    	$Url['next'] = U('Index/exam',array('exam_id'    => $exam_id,
    										'type'		 => $question['type'],
    										'num'	     => $num+1,
    										'course'     => $question['course_id']));

    	$Url['last'] = U('Index/exam',array('exam_id'    => $exam_id,
    										'type'		 => $question['type'],
    										'num'	     => $num-1,
    										'course'     => $question['course_id']));
    	//dump($Url);*/

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