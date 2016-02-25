<?php
namespace Home\Logic;
class ExamLogic {

	//第一次进入考试界面时设置test_id的cookie,并储存当前时间到数据库summary表中的start_time字段,若已经设置该id值,则表示不是第一次访问,当前考试还没有结束
	public function setStartTime($studentID,$paperID)
	{
		$paperinfo = cookie('paperinfo');
		$test_id = $paperinfo['test_id'];
		if ($test_id != NULL) {
			return false;
		}else {
			$SummaryModel = D('Summary');
			$time['start_time'] = date("Y-m-d h:i:s");
			$id = $SummaryModel->add($time);
			if ($id == null) {
				return false;
			}
			$paperinfo['test_id'] = $id;
			cookie('paperinfo', $paperinfo);
		}
	}

	//提交试卷时直接从cookie中得到当前考试的save_id,将结束时间写入数据库,并清楚该cookie表示该次试卷测试结束.
	public function setEndTime()
	{
		$paperinfo = cookie('paperinfo');
		$test_id = $paperinfo['test_id'];
		if ($test_id == null) {
			return false;
		}
		$SummaryModel = D('Summary');
		$field = array('test_id' => $test_id);
		$time['submit_time'] = date("Y-m-d h:i:s");
		$id = $SummaryModel->where($field)->add($time);
		if ($id == null) {
			return false;
		}
		cookie('paperinfo', null);
	}
	public function getPaperInfo($paperID)
	{
		if (isset($paperID)) {
			$paperModel = D('Paper');
			$query = array('paper_id' => $paperID);
			$field = array('paper_id','paper_name','course_id','total_grade','question_num','test_time','deadline');
			$data  = $paperModel->where($query)->field($field)->find();
			return $data;
		} else {
			return 'ID is not set';
		}
		
	}


	public function getQuestion($paperID,$num)
	{
		if(!isset($paperID)){
			return 'ID is not set';
		}
		$paperModel  = D('Paper');
		$query=array('paper_id' => $paperID);
		$questionNum = $paperModel->where($query)->getField('question_num');
		if ($num <= 0 OR $num > $questionNum) {
			return false;
		}

		$nowQuestion = $this->getQuestionID($paperID,$num);		
		
		$questionModel= D('Question');
		$field 		  = array('title','type');
		$question = $questionModel->where('question_id='.$nowQuestion)->field($field)->find();
		$question['option'] = $questionModel->getOption($nowQuestion);
		$question['num']	= $num;
		return $question;
	}


	private function getQuestionID($paperID,$num)
	{
		$paperModel  = D('Paper');
		$query=array('paper_id' => $paperID);
		$questionList = $paperModel->where($query)->getField('content');
		$questionList = json_decode($questionList);
		$nowQuestion  = $questionList[$num-1];
		return $nowQuestion;
	}


	public function getStatus($paperID,$num)
	{
		if(!isset($num)){
			return 'ID is not set';
		}
		$paperModel  = D('Paper');
		$query=array('paper_id' => $paperID);
		$questionNum = $paperModel->where($query)->getField('question_num');
		// if ($num <= 0 OR $num > $questionNum) {
		// 	return false;
		// }
		switch ($num) {
    		case '1':
				$status = 'first';
    			break;
			case $questionNum:
				$status = 'end';
				break;
    		default:
    			$status = 'mid';
    			break;
    	}
    	return $status;
	}

	private function getQuestionInfo($paperID,$num) //此方法为submit服务
	{
		$questionId = $this->getQuestionID($paperID,$num);
		$questionModel = D('Question');
		$query = array('question_id' => $questionId);
		$field = array('question_id','type','course_id');
		$info  = $questionModel->where($query)->field($field)->find();
		return $info;
	}
	public function submitAnswer($studentID,$paperID,$num,$answer)
	{
		if(!isset($num)){
			return 'num is not set';
		}
		if(!isset($paperID)){
			return 'ID is not set';
		}
		if(!isset($answer) OR $answer==''){
			return 'answer is not set';
		}

		$question = $this->getQuestionInfo($paperID,$num);

		$paperinfo = cookie('paperinfo');
		$test_id = $paperinfo['test_id'];

		$submitModel = D('Submit');
		//$data['submit_id'] = $paperID.$studentID.$question['question_id'];
		$data['num'] = $num;
		$data['test_id'] = $test_id;
		$data['paper_id'] = $paperID;
		$data['student_id'] = $studentID;
		$data['course_id']  = $question['course_id'];
		$data['question_id'] = $question['question_id'];
		$data['type'] = $question['type'];
		//$data['answer'] = $answer;
		//$result = $submitModel->add($data,$options=array(),$replace=true);
		$oldanswer = $submitModel->where($data)->getField('answer');
		if ($oldanswer == null) {
			$data['answer'] = $answer;
			$result = $submitModel->add($data);
		}else{
			$result = $submitModel->where($data)->setField('answer', $answer);
		}
		return $result;
	}

	public function getOldAnswer($studentID,$paperID,$num)
	{
			$submitModel = D('Submit');
			$paperinfo = cookie('paperinfo');
			$test_id = $paperinfo['test_id'];
			$query = array('student_id' => $studentID,
						   'paper_id'   => $paperID,
						   'num'        => $num,
						   'test_id' 	=> $test_id);
			$oldAnswer = $submitModel->where($query)->getField('answer');
			return $oldAnswer;
	}

}