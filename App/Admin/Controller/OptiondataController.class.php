<?php
namespace Admin\Controller;

use Admin\Controller\CommonController;
use Think\Log;

class OptiondataController extends CommonController {
	function test(){
		echo "asdfasdf";
	}
	function optionList($page = 1, $rows = 10, $sort = 'dictid', $order = 'asc', $courseid = '', $typeid = '') {
		if (IS_POST && $courseid != '' && $typeid != '') {
			$option_db = M ( 'Optiondata' );
			$order = $sort . ' ' . $order;
			$limit = ($page - 1) * $rows . "," . $rows;
			$condition ['course_id'] = $courseid;
			$condition ['type'] = $typeid;
			$total = $option_db->where($condition)->count ();
			$list = $option_db->where ( $condition )->limit ( $limit )->select ();
			// if ($courseid=='' && $typeid=='') {
			// $list = $option_db->limit($limit)->select();
			// }
			// var_dump($option_db->getLastSql());
			$arr = object_array ( $list );
			//$total是datagrid理解的所有数据量
			//$total = count ( $arr );
			if ($total > 0) {
				foreach ( $arr as $key => $value ) {
					foreach ( $value as $key1 => $value1 ) {
						$data [$key] [$key1] = $value1;
					}
					$coursename = array ();
					$coursename = object_array ( getDictName ( $arr [$key] ["course_id"] ) );
					$data [$key] ["course"] = $coursename [0] ["type_name"];
					$coursename = object_array ( getDictName ( $arr [$key] ["type"] ) );
					$data [$key] ["type"] = $coursename [0] ["type_name"];
				}
				$data2 = array (
					'total' => $total,
					'rows' => $data 
				); // 这句话可以控制显示的内容
			} else {
				$data2 = array (
					'total' => 0,
					'rows' => [ ] 
					);
			}
			echo json_encode ( $data2 );
		} else {
			$this->display ( 'option_index' );
		}
	}
	function optionAdd() {
		$this->display ( 'option_add' );
	}
	function optionEdit($id = 0) {
		$Optiondata_db=D('Optiondata');
		$optioninfo=$Optiondata_db-> where ('question_id='.$id)->select();
		$this->assign ( 'optionid', $id );
		$this->assign('optioncourse',$optioninfo[0]['course_id']);
		$this->display ( 'option_edit' );
	}
	function getOptionData() {
		$id = $_POST ['id'];
		$db = M ( 'optiondata' );
		$condition= array ('question_id' => $id );
		$result = $db->where ( $condition)->select ();
		$this->ajaxReturn ( $result [0] );
	}
	function update() {
		$data = array (
			'question_id' => $_POST ['hidden_option_id'],
			'course_id' => $_POST ['hidden_option_course'],
			'type' => $_POST ['hidden_option_type'],
			'keyword' => $_POST ['hidden_option_keyword'],
			'title' => $_POST ['option_title'],
			'answer' => $_POST ['answerRes'],
			'a' => $_POST ['optionA'],
			'b' => $_POST ['optionB'],
			'c' => $_POST ['optionC'],
			'd' => $_POST ['optionD'],
			'e' => $_POST ['optionE'],
			'f' => $_POST ['optionF'],
			'img' => $_POST ['hidden_option_img'] 
			);
		$Optiondata_db = M ( 'optiondata' );
		$result = $Optiondata_db->where ( 'question_id=' . $data ['question_id'] )->delete ();
		$Optiondata_db->add ( $data );
		$this->display('Common/close');
	}
	function optionRemove($id = 0) {
		if ($id) {
			$Optiondata_db = M ( 'optiondata' );
			$result = $Optiondata_db->where ( 'question_id=' . $id )->delete ();
		}
	}
	function Upload() {
		// 实例化thinkphp自带的上传功能
		$upload = new \Think\Upload ();
		// 设置附件上传大小3M
		$upload->maxSize = 3145728;
		// 设置附件上传类型
		$upload->exts = array ('xls' );
		// 设置附件上传根目录
		// 请建立文件夹 C:\wamp\www\Demo\Uploads，否则会提示失败
		$upload->rootPath = './Uploads/';
		// 取消自动使用子目录保存上传文件，否则会按时间建立文件夹
		$upload->autoSub = false;
		// 上传文件，并按年月日时分秒的命名方式保存
		$upload->saveName = date ( 'ymdhis' );
		// 上传文件
		$info = $upload->upload ();
		$info = $info ['xlsfile'];
		if (! $info) { // 上传错误提示错误信息
			//$this->error ( $upload->getError () );
			echo json_encode($upload->getError());
		} else { // 上传成功
			$filename = 'Uploads/' . $info ['savepath'] . $info ['savename'];
			chmod($filename, 0777);
			$ext = $info ['ext'];
			$this->XlsToArray ( $filename, $ext );
		}
	}
	function XlsToArray($filename='', $exts = 'xls') {
		//$filename='Uploads/160225043124.xls';
		// import导入library目录下文件
		// 导入PHPExcel文件
		// 创建PHPExcel对象
		
		vendor ( 'PHPExcel.PHPExcel' );
		
		$PHPExcel = new \PHPExcel ();
		// 根据文件格式实例化对应的对象
		if ($exts == 'xls') {
			$PHPReader = new \PHPExcel_Reader_Excel5 ();
		} else if ($exts == 'xlsx') {
			$PHPReader = new \PHPExcel_Reader_Excel2007 ();
		}
		// 载入文件
		$PHPExcel = $PHPReader->load ( $filename );
		// 获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推

		$currentSheet = $PHPExcel->getSheet ( 0 );
		// 获取总列数
		$allColumn = $currentSheet->getHighestColumn ();
		// 获取总行数
		$allRow = $currentSheet->getHighestRow ();
		// 循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始

		for($currentRow = 1; $currentRow <=$allRow; $currentRow ++) {
			// 从哪列开始，A表示第一列
			for($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn ++) {
				// 数据坐标
				$address = $currentColumn . $currentRow;
				// 读取到的数据，保存到数组$arr中
				$data [$currentRow] [$currentColumn] = $currentSheet->getCell ( $address )->getValue ();
			}
		}
		
		$option_db = D ( 'optiondata' );
		$option_db->ArrayToSql ( $data );
		
	}
}

?>