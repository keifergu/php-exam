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
					'id' => intval ( '' . $data [$key] ['A'] ),
					// belong_type=200
					'course' => intval ( '' . $data [$key] ['B'] ),
					// belong_type=100
					'type' => intval ( '' . $data [$key] ['C'] ),
					'keyword' => '' . $data [$key] ['D'],
					'title' => '' . $data [$key] ['E'],
					'answer' => '' . $data [$key] ['F'],
					'A' => '' . $data [$key] ['G'],
					'B' => '' . $data [$key] ['H'],
					'C' => '' . $data [$key] ['I'],
					'D' => '' . $data [$key] ['J'],
					'E' => '' . $data [$key] ['K'],
					'F' => '' . $data [$key] ['L'],
					'img' => '' . $data [$key] ['M'] 
			);
			$findid = array (
					'id' => $tempdata ['id'] 
			);
			if ($course_db->where ( $findid )->select () != NULL) {
				$this->Error [$this->ErrorNum ++] = '第' . $key . '行数据中题目id已存在！';
				continue;
			}
			if ($tempdata ['id'] < 1000000000 || $tempdata ['id'] > 9999999999) {
				$this->Error [$this->ErrorNum ++] = '第' . $key . '行数据中题目id命名长度不对，请检查';
				continue;
			}
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
}
?>