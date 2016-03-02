<?php
namespace Admin\Model;
use Think\Model;

class PapergradeModel extends Model
{
	protected $tableName = 'paper_grade';
	function getTypeInfo($paperId,$typeId){
		$ret = false ;
		if(isset($typeId) && isset($paperId)){
			$condition=array('paper_id'=>$paperId,'question_type'=>$typeId);
			$ret=$this->where(condition)->select();
		}
		return $ret;
	}
	function setTypeInfo($paperId,$typeId,$grade=0){
		$ret = false;
		if(isset($paperId)  && isset($typeId) && isset($grade)){
			$result=$this->where(array('paper_id'=>$paperId,'question_type'=>$typeId))->find()['test_type_id'];
			$data=array('paper_id'=>$paperId,'question_type'=>$typeId,'grade'=>$grade);
			if($result){
				$ret = $this->where(array('test_type_id'=>$result))->save($data);
			}else{
				$ret = $this->add($data);
			}
		}
		return $ret ;
	}
	function deletePaperInfo($paperId){
		$ret = false;
		if(isset($paperId)){
			$condition['paper_id']=$paperId;
			$ret = $this->where($condition)->delete();
		}
		return $ret ;
	}
	function getPaperTypeGrade($paperID,$typeID){
		$ret = false ;
		if(isset($paperID)&&isset($typeID)){
			$ret = $this->where(array('paper_id'=>$paperID,'question_type'=>$typeID))->find()['grade'];
		}
		return $ret;
	}
}