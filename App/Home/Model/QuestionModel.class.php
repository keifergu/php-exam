<?php
namespace Home\Model;
use Think\Model;
class QuestionModel extends Model {

	protected $tableName = 'optiondata';

	public function getOption($questionID)
	{
		if (!isset($questionID)) {
			return false;
		}
		$query 		= array('question_id' => $questionID);
		$field 		= array('a','b','c','d','e','f','g','h');
		$question   = $this->where($query)->field($field)->find();
		/*$data		= array('A'=>$question['a'],'B'=>$question['b'],
    						'C'=>$question['c'],'D'=>$question['d'],
    						'E'=>$question['e'],'F'=>$question['f'],
    						'G'=>$question['g'],'H'=>$question['h']);*/
		$data		= array($question['a'],$question['b'],
    						$question['c'],$question['d'],
    						$question['e'],$question['f'],
    						$question['g'],$question['h']);
		$option 	= array_filter($data);	//删除空白字段
		return $option;
	}

	public function getTitle($questionID)
	{
		if (!isset($questionID)) {
			return false;
		}
		$query = array('question_id' => $questionID);
		$field = array('title');
		$data  = $this->where($query)->getField($field);
		return $data;
	}


	public function getType($questionID)
	{
		if (!isset($questionID)) {
			return false;
		}
		$query = array('question_id' => $questionID);
		$field = array('type');
		$data  = $this->where($query)->getField($field);
		return $data;
	}
}