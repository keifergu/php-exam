<?php
namespace Admin\Model;
use Think\Model;
class PaperdataModel extends Model{
	protected $tableName = 'paper_content';
	public function paperList($page, $rows, $courseid) {
		$limit = ($page - 1) * $rows . "," . $rows;
		$condition =array(
			'course_id' => $courseid
			);
		$list=$this->where ( $condition )->limit ( $limit )->select ();
		$total=$this->where ( $condition )->count ();
		if($total>0){
			foreach ($list as $key=>$value){
				foreach ($value as $key1=>$value1){
					$data[$key][$key1]=$value1;
				}
				$coursename = array ();
				$coursename=getDictName( $list [$key] ['course_id'] );
				$data [$key] ['course_id'] = $coursename [0] ['type_name'];
			}
			$result=array(
				'total' => $total,
				'rows' => $data
				);
		}else{
			$result=array(
				'total' =>0,
				'rows' => []
				);
		}
		return $result;
	}

	public function getPaperName($paperID){
		$ret = false;
		if(isset($paperID)){
			$condition=array('paper_id'=>$paperID);
			$ret=$this->where($condition)->getField('paper_name');
		}
		return $ret;
	}
	public function getCourseID($paperID){
		$ret = false;
		if(isset($paperID)){
			$condition=array('paper_id'=>$paperID);
			$ret=$this->where($condition)->getField('course_id');
		}
		return $ret;
	}
	public function getTotalGrade($paperID){
		$ret = false;
		if(isset($paperID)){
			$condition=array('paper_id'=>$paperID);
			$ret=$this->where($condition)->getField('tital_grade');
		}
		return $ret;
	}
	public function getQuestionCount($paperID){
		$ret = false;
		if(isset($paperID)){
			$condition=array('paper_id'=>$paperID);
			$ret=$this->where($condition)->getField('question_num');
		}
		return $ret;
	}
	public function getTestTime($paperID){
		$ret = false;
		if(isset($paperID)){
			$condition=array('paper_id'=>$paperID);
			$ret=$this->where($condition)->getField('test_time');
		}
		return $ret;
	}
	public function getDeadline($paperID){
		$ret = false;
		if(isset($paperID)){
			$condition=array('paper_id'=>$paperID);
			$ret=$this->where($condition)->getField('deadline');
		}
		return $ret;
	}
	public function getContent($paperID){
		$ret = false;
		if(isset($paperID)){
			$condition=array('paper_id'=>$paperID);
			$json=$this->where($condition)->getField('content');
			$result=json_decode($json,true);
		}
		return $ret;
	}
	public function setQuestionNum($paperID,$questionNum){
		$ret = false;
		if(isset($paperID)){
			$condition=array('paper_id'=>$paperID);
			$data=$this->where($condition)->find();
			$data['question_num']=$questionNum;
			$ret=$this->where($condition)->save($data);
		}
		return $ret;
	}
	public function setTotalGrade($paperID,$totalGrade){
		$ret = false;
		if(isset($paperID)){
			$condition=array('paper_id'=>$paperID);
			$data=$this->where($condition)->find();
			$data['total_grade']=$totalGrade;
			$ret=$this->where($condition)->save($data);
		}
		return $ret;
	}
	public function setContent($paperID,$contentArray){
		$ret = false;
		if(isset($paperID) && is_array($contentArray)){
			$condition=array('paper_id'=>$paperID);
			$data=$this->where($condition)->find();
			$data['content']=json_encode($contentArray);
			$data['question_num']=count($contentArray);
			$ret=$this->where($condition)->save($data);
		}
		return $ret;
	}
	
}
?>