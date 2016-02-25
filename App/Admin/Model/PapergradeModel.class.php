<?php
namespace Admin\Model;
use Think\Model;

class PapergradeModel extends Model
{
	protected $tableName = 'paper_grade';
	function getTypeInfo($paperId,$typeId){
		$condition=array(
			'paper_id'=>$paperId,
			'question_type'=>$typeId
			);
		$result=$this->where(condition)->select();
		return $result;
	}
	function setTypeInfo($paperId,$typeId,$grade=0){
		$condition=array(
			'paper_id'=>$paperId,
			'question_type'=>$typeId
			);
		$result=$this->where($condition)->select();
		$data=array(
			'paper_id'=>$paperId,
			'question_type'=>$typeId,
			'grade'=>$grade
			);
		if($result){
			$this->where($condition)->save($data);
		}else{
			$this->add($data);
		}
	}
	function deletePaperInfo($paperId){
		$condition['paper_id']=$paperId;
		$this->where($condition)->delete();
	}
	function test(){
		$data=array(
			'paper_name'=>rand(1,99),
			);
		$this->add($data);
	}
}