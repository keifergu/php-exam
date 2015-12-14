<?php
namespace Admin\Model;
use Think\Model;

class PapergradeModel extends Model
{
	protected $tableName = 'paper_grade';
	function getTypeInfo($paperId,$typeId){
		$testTypeId=$paperId.$typeId;
		$result=$this->where('test_type_id='.$testTypeId)->select();
		return $result;
	}
	function setTypeInfo($paperId,$typeId,$grade=0){
		$testTypeId=$paperId.$typeId;
		$result=$this->where('test_type_id='.$testTypeId)->select();
		$data=array(
			'test_type_id'=>$testTypeId,
			'paper_id'=>$paperId,
			'question_type'=>$typeId,
			'grade'=>$grade
			);
		
		$result=$this->where('test_type_id='.$testTypeId)->select();
		if($result){
			echo $grade;
			$this->where('test_type_id='.$testTypeId)->save($data);
		}else{
			$this->add($data);
		}
	}
	function deletePaperInfo($paperId){
		$this->where('paper_id='.$paperId)->delete();
	}
}