<?php

namespace Admin\Model;

use Think\Model;

class OptiondataModel extends Model {
	protected $tableName = 'optiondata';
	protected $pk = 'id';
	public $error;
	public $Error = array ();
	public $ErrorNum = 0;
	function ArrayToSql($data) {
		$course_db = M ( 'data_dict' );
		foreach ( $data as $key => $value ) {
			$tempdata = array (
					// 强制转换成string 
					//question 自增
					//'question_id' => intval ( '' . $data [$key] ['A'] ),
					// belong_type=200
					'course' => intval ( '' . $data [$key] ['B'] ),
					// belong_type=100
					'type' => intval ( '' . $data [$key] ['C'] ),
					'keyword' => '' . $data [$key] ['D'],
					'title' => '' . $data [$key] ['E'],
					'answer' => '' . $data [$key] ['F'],
					'a' => '' . $data [$key] ['G'],
					'b' => '' . $data [$key] ['H'],
					'c' => '' . $data [$key] ['I'],
					'd' => '' . $data [$key] ['J'],
					'e' => '' . $data [$key] ['K'],
					'f' => '' . $data [$key] ['L'],
					'img' => '' . $data [$key] ['M'] 
			);
			$findcourse = array (
					'type_id' => $tempdata ['course'],
					'belong_type=' => 200 
			);
			if ($course_db->where ( $findcourse )->select () == NULL) {
				$this->Error [$this->ErrorNum ++] = '第' . $key . '行数据中课程id错误：不存在该课程';
				continue;
			}
			
			$findtype = array (
					'type_id' => $tempdata ['type'],
					'belong_type=' => 100 
			);
			if ($course_db->where ( $findtype )->select () == NULL) {
				$this->Error [$this->ErrorNum ++] = '第' . $key . '行数据中题目类型id错误：不存在该种题目类型';
				continue;
			}
			$this->add ( $tempdata );
		}
		$SuccessNum = count ( $data ) - $this->ErrorNum;
		$result= '导入成功' . $SuccessNum . '条数据！<br/>';
		if ($this->ErrorNum) {
			$result=$result. '错误信息:<br/>';
			foreach ( $this->Error as $key => $value ) {
				$result= $result.$value . '<br/>';
			}
		}
		echo json_encode($result);
	}
	function test(){
		$data=array(
			'title'=>rand(1,99),
			'course_id'=>'201',
			'type'=>'101',
			'a'=>'123',
			'b'=>'456',
			'c'=>'789',
			'd'=>'asdf',
			);
		$this->add($data);


	}
}
?>