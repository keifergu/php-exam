<?php
namespace Home\Logic;
class ExamLogic {

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
		// $question['title']  = $questionModel->getTitle($nowQuestion);
		$question['option'] = $questionModel->getOption($nowQuestion);
		// $question['type']	= $questionModel->getType($nowQuestion);
		$question['num']	= $num;
		//$question['next']   = $questionList[$num];
		//$question['last']   = $questionList[$num-2];
		return $question;
	}


	private function getQuestionID($paperID,$num)
	{
		$paperModel  = D('Paper');
		$query=array('paper_id' => $paperID);
		$questionList = $paperModel->where($query)->getField('content');
		$questionList = _strDataToArray($questionList);
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
		dump($question);
		$submitModel = D('Submit');
		$data['submit_id'] = $paperID.$studentID.$question['question_id'];
		$data['num'] = $num;
		$data['paper_id'] = $paperID;
		$data['student_id'] = $studentID;
		$data['course_id']  = $question['course_id'];
		$data['question_id'] = $question['question_id'];
		$data['type'] = $question['type'];
		$data['answer'] = $answer;
		$result = $submitModel->add($data,$options=array(),$replace=true);
		return $result;
	}

	public function getOldAnswer($studentID,$paperID,$num)
	{
			$submitModel = D('Submit');
			$query = array('student_id' => $studentID,
							'paper_id'  => $paperID,
							'num'       => $num);
			$oldAnswer = $submitModel->where($query)->getField('answer');
			return $oldAnswer;
	}

}