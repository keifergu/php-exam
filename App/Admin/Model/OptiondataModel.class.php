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
		$Dict_db = D( 'Dictdata' );
		foreach ( $data as $key => $value ) {
			if($key==1)	continue;
			$tempdata = array (
				// 强制转换成string 
				//question 自增
				'course_id' =>   '' . $Dict_db->NameToId($data [$key] ['A'],200 ),
				'type' =>   '' . $Dict_db->NameToId($data [$key] ['B'],100 ),
				'title' => '' . $data [$key] ['C'],
				'a' => '' . $data [$key] ['D'],
				'b' => '' . $data [$key] ['E'],
				'c' => '' . $data [$key] ['F'],
				'd' => '' . $data [$key] ['G'],
				'e' => '' . $data [$key] ['H'],
				'f' => '' . $data [$key] ['I'],
				'g' => '' . $data [$key] ['J'],
				'h' => '' . $data [$key] ['K'],
				'answer' => '' . ord($data [$key] ['L'])-64
				);
			//var_dump($tempdata);
			/*
			var_dump($tempdata['course']);
			var_dump($tempdata['type']);
			var_dump($Dict_db->IdToName($tempdata['course']));
			var_dump($Dict_db->IdToName($tempdata['type']) );
			*/
			if ($Dict_db->IdToName($tempdata['course_id'])==NULL) {
				$this->Error [$this->ErrorNum ++] = '第' . $key . '行数据中课程id错误：不存在该课程';
				continue;
			}
			if ($Dict_db->IdToName($tempdata['type']) ==NULL){
				$this->Error [$this->ErrorNum ++] = '第' . $key . '行数据中题目类型id错误：不存在该种题目类型';
				continue;
			}
			$this->add ( $tempdata );
		}
		$SuccessNum = count ( $data ) - $this->ErrorNum-1;
		$result= '导入成功' . $SuccessNum . '条数据！<br/>';
		if ($this->ErrorNum) {
			$result=$result. '错误信息:<br/>';
			foreach ( $this->Error as $key => $value ) {
				$result= $result.$value . '<br/>';
			}
		}
		echo json_encode ($result);
	}
}
?>