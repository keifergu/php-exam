<?php

namespace Admin\Model;

use Think\Model;

class OptiondataModel extends Model {
	protected $tableName = 'optiondata';
	protected $pk = 'id';
	public $error;
	public $Error = array ();
	public $ErrorNum = 0;
	
	public function addList($data) {
		$ret = false;
		if(is_array($data)){
			$Dict_db = D( 'Dictdata' );
			foreach ( $data as $key => $value ) {
				if($key==1)	continue;
				$tempdata = array (	// 强制转换成string	 //question 自增
					'course_id' =>   '' . $Dict_db->getID($value ['A'],200 ),
					'type' =>   '' . $Dict_db->getID($value ['B'],100 ),
					'title' => '' . $value ['C'],
					'a' => '' . $value ['D'],
					'b' => '' . $value ['E'],
					'c' => '' . $value ['F'],
					'd' => '' . $value ['G'],
					'e' => '' . $value ['H'],
					'f' => '' . $value ['I'],
					'g' => '' . $value ['J'],
					'h' => '' . $value ['K'],
					'answer' => '' . ord($value ['L'])-64
					);
				if ($Dict_db->getName($tempdata['course_id'])==NULL) {
					$this->Error [$this->ErrorNum ++] = '第' . $key . '行数据中课程id错误：不存在该课程';
					continue;
				}
				if ($Dict_db->getName($tempdata['type']) ==NULL){
					$this->Error [$this->ErrorNum ++] = '第' . $key . '行数据中题目类型id错误：不存在该种题目类型';
					continue;
				}
				$this->add ( $tempdata );
			}
			$SuccessNum = count ( $data ) - $this->ErrorNum-1;
			$ret= '导入成功' . $SuccessNum . '条数据！<br/>';
			if ($this->ErrorNum) {
				$ret=$ret. '错误信息:<br/>';
				foreach ( $this->Error as $key => $value ) {
					$ret= $ret.$value . '<br/>';
				}
			}
		}
		return $ret;
	}

	public function getCourseID($questionID){
		$ret = false;
		if(isset($questionID)){
			$condition=array('question_id'=>$questionID);
			$ret=$this->where($condition)->getField('course_id');
		}
		return $ret;
	}
	public function getTypeID($questionID){
		$ret = false;
		if(isset($questionID)){
			$condition=array('question_id'=>$questionID);
			$ret=$this->where($condition)->getField('type');
		}
		return $ret;
	}
	public function getTypeName($questionID){
		$ret = false;
		if(isset($questionID)){
			$condition=array('question_id'=>$questionID);
			$typeID=$this->where($condition)->getField('type');
			$dict_db=D('Dictdata');
			$ret = $dict_db->getName($typeID);
		}
		return $ret;
	}
	public function getKeyword($questionID){
		$ret = false;
		if(isset($questionID)){
			$condition=array('question_id'=>$questionID);
			$ret=$this->where($condition)->getField('keyword');
		}
		return $ret;
	}
	public function getTitle($questionID){
		$ret = false;
		if(isset($questionID)){
			$condition=array('question_id'=>$questionID);
			$ret=$this->where($condition)->getField('title');
		}
		return $ret;
	}
	public function getOptionA($questionID){
		$ret = false;
		if(isset($questionID)){
			$condition=array('question_id'=>$questionID);
			$ret=$this->where($condition)->getField('a');
		}
		return $ret;
	}
	public function getOptionB($questionID){
		$ret = false;
		if(isset($questionID)){
			$condition=array('question_id'=>$questionID);
			$ret=$this->where($condition)->getField('b');
		}
		return $ret;
	}
	public function getOptionC($questionID){
		$ret = false;
		if(isset($questionID)){
			$condition=array('question_id'=>$questionID);
			$ret=$this->where($condition)->getField('c');
		}
		return $ret;
	}
	public function getOptionD($questionID){
		$ret = false;
		if(isset($questionID)){
			$condition=array('question_id'=>$questionID);
			$ret=$this->where($condition)->getField('d');
		}
		return $ret;
	}
	public function getOptionE($questionID){
		$ret = false;
		if(isset($questionID)){
			$condition=array('question_id'=>$questionID);
			$ret=$this->where($condition)->getField('e');
		}
		return $ret;
	}
	public function getOptionF($questionID){
		$ret = false;
		if(isset($questionID)){
			$condition=array('question_id'=>$questionID);
			$ret=$this->where($condition)->getField('f');
		}
		return $ret;
	}
	public function getOptionG($questionID){
		$ret = false;
		if(isset($questionID)){
			$condition=array('question_id'=>$questionID);
			$ret=$this->where($condition)->getField('g');
		}
		return $ret;
	}
	public function getOptionH($questionID){
		$ret = false;
		if(isset($questionID)){
			$condition=array('question_id'=>$questionID);
			$ret=$this->where($condition)->getField('h');
		}
		return $ret;
	}
	public function getOptionImg($questionID){
		$ret = false;
		if(isset($questionID)){
			$condition=array('question_id'=>$questionID);
			$ret=$this->where($condition)->getField('img');
		}
		return $ret;
	}
	public function getOptionAnswer($questionID){
		$ret = false;
		if(isset($questionID)){
			$condition=array('question_id'=>$questionID);
			$ret=$this->where($condition)->getField('answer');
		}
		return $ret;
	}
	public function getCourseCount($courseID){
		$ret = false;
		if(isset($courseID)){
			$condition=array('course_id'=>$courseID);
			$ret=$this->where($condition)->count();
		}
		return $ret;
	}
	public function getCourseTypeCount($courseID,$typeID){
		$ret = false;
		if(isset($courseID)){
			$condition=array(
				'course_id'=>$courseID,
				'type'=>$typeID
				);
			$ret=$this->where($condition)->count();
		}
		return $ret;
	}
	public function getOptionInfo($questionID){
		$ret = false;
		if(isset($questionID)){
			$condition=array('question_id'=>$questionID);
			$ret=$this->where($condition)->find();
		}
		return $ret;
	}
	public function updateItem($questionID,$data){
		$ret = false;
		if(isset($data) && is_array($data) ){
			$ret = $this->where ( array('question_id' => $questionID))->save($data);
		}
	}
	public function deleteItem($questionID){
		$ret = false;
		if(isset($questionID)){
			$condition=array('question_id'=>$questionID);
			$ret=$this->where($condition)->delete();
		}
		return $ret;
	}
}
?>