<?php
namespace Home\Logic;
class CountLogic {
	
	public function countPaperGrade($paperId,$studentId)
	{
		if (is_null($paperId) OR is_null($studentId)) {
			return "试卷id或学生id未设置";
		}
		$SubmitModel = D('Submit');
		$QuestionModel = D('Question');
		$PaperModel 	= D('Paper');
		$GradeModel 	= D('PaperGrade');
		//获得该试卷的所有试题
		$paper_query = array('paper_id' => $paperId);
		$paperContent = json_decode($PaperModel->where($paper_query)->getField('content'));

		//获得试题的答案
		/***
		array(4) {
			 [2011010001] => string(1) "1"
			 [2011020001] => string(2) "13"
			 [2011010003] => string(1) "1"
			 [2011010004] => string(1) "4"
		}
		***/
		$paperAnswer = array();
		foreach ($paperContent as $key => $value) {
			$paperAnswer[$value] = $QuestionModel->where('question_id='.$value)->getField('answer');
		}

		//获得该学生的所有答案
		/***
		array(4) {
			  [0] => array(3) {
			    ["num"] => string(1) "1"
			    ["paper_id"] => string(6) "201004"
			    ["answer"] => string(1) "4"
			  }
			  [1] => array(3) {
			    ["num"] => string(1) "3"
			    ["paper_id"] => string(6) "201004"
			    ["answer"] => string(1) "2"
			  }
			  [2] => array(3) {
			    ["num"] => string(1) "4"
			    ["paper_id"] => string(6) "201004"
			    ["answer"] => string(1) "4"
			  }
			  [3] => array(3) {
			    ["num"] => string(1) "2"
			    ["paper_id"] => string(6) "201004"
			    ["answer"] => string(2) "14"
			  }
			}
		***/
		$submitQuery     = array('student_id' => $studentId,'paper_id' =>$paperId);
		$submitField       = array('num' , 'question_id' , 'answer' ,'type');
		$studentAnswer = $SubmitModel->where($submitQuery)->order('num')->field($submitField)->select();
		if (!is_null($studentAnswer)) {
			;
		}
		//var_dump($paperAnswer);
		//var_dump($studentAnswer);
		//对获取到的答案进行比对
		$correctQuestion = array();
		$wrongQuestion  = array();
		foreach ($studentAnswer as $key => $value) {	
			if ($value['answer'] == $paperAnswer[$value['question_id']]) { //此处将该题目的question_id 放入正确答案的数组中作为健,直接得到值,即答案
				if ( !is_array($correctQuestion[$value['type']])) {
				 	$correctQuestion[$value['type']] = array();
				 } 
				//array_push($correctQuestion[$value['type']], array($value['num'] => $value['question_id']));
				 $correctQuestion[$value['type']][$value['question_id']] = $value['answer'];
				 //dump($correctQuestion);
			}else{
				if ( !is_array($wrongQuestion[$value['type']])) {
				 	$wrongQuestion[$value['type']] = array();
				 } 
				 //array_push($wrongQuestion[$value['type']], $value['question_id']);
				 $wrongQuestion[$value['type']][$value['question_id']] = $value['answer'];
			}
		}

		//进行分数统计
		$totalGrade = 0;
		foreach ($correctQuestion as $key => $value) {
			$gradeQuery = array('paper_id' => $paperId,'question_type' => $key);
			$typeGrade =  $GradeModel->where($gradeQuery)->getField('grade');
			if (is_null($typeGrade)) {
				return '试卷"'.$paperId.'"分数未设置,请设置分数后重试';
			}
			$totalGrade += $typeGrade*count($value);
		}

		//对需要储存的数据进行格式处理
		$correcrArray = array();
		$wrongArray  = array();
		foreach ($correctQuestion as $key => $value) {
			foreach ($value as $keyin => $valuein) {
				$correcrArray[$keyin] =$valuein;
			}
		};
		foreach ($wrongQuestion as $key => $value) {
			foreach ($value as $keyin => $valuein) {
				$wrongArray[$keyin] =$valuein;
			}
		};
		//dump($correcrArray);
		//dump($wrongArray);


		//将数据写入数据库
		$SummaryModel = D('Summary');
		$data = array(
			//'save_id'     => $paperId.$studentId,
			'paper_id'    => $paperId,
			'student_id' => $studentId,
			'course_id'   => substr($paperId, 0,3),
			'grade' 	        => $totalGrade,
			'question_correct' => json_encode($correcrArray),
			'question_wrong' => json_encode($wrongArray)
		);
		$result = $SummaryModel->data($data)->add();
		return $result;
		/*dump($SummaryModel->getLastSql());
		dump($restlt);
		dump($paperAnswer);
		dump($studentAnswer);
		dump($correctQuestion);
		var_dump($wrongQuestion);
		dump($totalGrade);*/
 	}
}