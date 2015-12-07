<?php
namespace Home\Model;
use Think\Model;
class PaperModel extends Model {
	protected $tableName = 'paper_content';


	public function getPaperInfo($paperID)
	{
		if (isset($paperID)) {
			$query = array('paper_id' => $paperID);
			$field = array('paper_id','paper_name','course_id','total_grade','question_num','test_time','deadline');
			$data  = $this->where($query)->field($field)->find();
			return $data;
		} else {
			return 'ID is not set';
		}
		
	}

	public function getPaperName($paperID)
	{
		if (isset($paperID)) {
			$query     = array('paper_id' => $paperID);
			$paperName = $this->where($query)->getField('paper_name');
			return $paperName;
		} else {
			return 'ID is not set';
		}
	}

	public function getCourse($paperID)
	{
		if (isset($paperID)) {
			$query = array('paper_id' => $paperID);
			$data  = $this->where($query)->getField('course_id');
			return $data;
		} else {
			return 'ID is not set';
		}
	}

	public function getTotalGrade($paperID)
	{
		if (isset($paperID)) {
			$query = array('paper_id' => $paperID);
			$data  = $this->where($query)->getField('total_grade');
			return $data;
		} else {
			return 'ID is not set';
		}
	}

	public function getQuestionNum($paperID)
	{
		if (isset($paperID)) {
			$query = array('paper_id' => $paperID);
			$data  = $this->where($query)->getField('question_num');
			return $data;
		} else {
			return 'ID is not set';
		}
	}

	public function getTotalTime($paperID)
	{
		if (isset($paperID)) {
			$query = array('paper_id' => $paperID);
			$data  = $this->where($query)->getField('test_time');
			return $data;
		} else {
			return 'ID is not set';
		}
	}

	public function getEndTime($paperID)
	{
		if (isset($paperID)) {
			$query = array('paper_id' => $paperID);
			$data  = $this->where($query)->getField('deadline');
			return $data;
		} else {
			return 'ID is not set';
		}
	}

	public function getPaperContent($paperID)
	{
		if (isset($paperID)) {
			$query 	  = array('paper_id' => $paperID);
			$data     = $this->where($query)->getField('content');
			$dataList =  array_filter(explode(';', $data));
			return $dataList;
		} else {
			return 'ID is not set';
		}
	}

}