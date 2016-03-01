<?php
namespace Admin\Controller;

use Admin\Controller\CommonController;
use Think\Log;

class OptiondataController extends CommonController {
	function optionList($page = 1, $rows = 10, $sort = 'dictid', $order = 'asc', $courseid = '', $typeid = '') {
		if (IS_POST && $courseid != '' && $typeid != '') {
			$option_db = D( 'Optiondata' );
			$dict_db=D('Dictdata');
			$order = $sort . ' ' . $order;
			$limit = ($page - 1) * $rows . "," . $rows;
			$condition ['course_id'] = $courseid;
			$condition ['type'] = $typeid;
			$total = $option_db->where($condition)->count ();
			$list = $option_db->where ( $condition )->limit ( $limit )->select ();
			// var_dump($option_db->getLastSql());
			$arr = object_array ( $list );
			//$total是datagrid理解的所有数据量
			if ($total > 0) {
				foreach ( $arr as $key => $value ) {
					foreach ( $value as $key1 => $value1 ) {
						$data [$key] [$key1] = $value1;
					}
					$data [$key] ["course"] =$dict_db->getName($value['course_id']);
					$data [$key] ["type"] = $dict_db->getName($value['type']);
				}
				$data2 = array ('total' => $total,'rows' => $data );
			} else {
				$data2 = array ('total' => 0,'rows' => [ ] );
			}
			echo json_encode ( $data2 );
		} else {
			$this->display ( 'option_index' );
		}
	}
	function optionEdit($id = 0) {
		if($id){
			$Optiondata_db=D('Optiondata');
			$this->assign ( 'optionid', $id );
			$this->assign('optioncourse',$Optiondata_db->getCourseID($id));
			$this->display ( 'option_edit' );
		}
		
	}
	function getOptionData() {
		$id = isset($_POST['id']) ? $_POST['id'] : 0 ;
		if($id){
			$Optiondata_db = D ( 'Optiondata' );
			$this->ajaxReturn ( $Optiondata_db->getOptionInfo($id));
		}
	}
	function update() {
		$id = isset($_POST['hidden_option_id']) ? $_POST['hidden_option_id'] : 0 ;
		if($id){
			$data = array (
				'question_id' => $_POST ['hidden_option_id'],
				'title' => $_POST ['option_title'],
				'answer' => $_POST ['answerRes'],
				'a' => $_POST ['optionA'],
				'b' => $_POST ['optionB'],
				'c' => $_POST ['optionC'],
				'd' => $_POST ['optionD'],
				'e' => $_POST ['optionE'],
				'f' => $_POST ['optionF'],
				'g' => $_POST ['optionG'],
				'h' => $_POST ['optionH'],
				);
			$Optiondata_db =D( 'optiondata' );
			$Optiondata_db->updateItem($data['question_id'],$data);
		}
		$this->display('Common/close');
	}
	function optionRemove() {
		$ret = false ;
		$id = isset($_POST['id']) ? $_POST['id'] : false ;
		if($id){
			$Optiondata_db = D( 'Optiondata' );
			$ret = $Optiondata_db->deleteItem($id);
		}
		echo json_encode($ret);
	}
	function Upload() {
		// 实例化thinkphp自带的上传功能
		$upload = new \Think\Upload ();
		// 设置附件上传大小3M
		$upload->maxSize = 3145728;
		// 设置附件上传类型
		$upload->exts = array ('xls' );
		// 设置附件上传根目录
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
		//$filename='Uploads/160229070422.xls';
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
		$option_db->addList ( $data );
		
	}
	function getOptionDetail($itemid){
		if($itemid){
			$Optiondata_db=D('Optiondata');
			$result=$Optiondata_db->getOptionInfo($itemid);
			$this->assign('detail_title',$result['title']);
			$this->assign('detail_type',$result['type']);
			$this->assign('detail_A',$result['a']);
			$this->assign('detail_B',$result['b']);
			$this->assign('detail_C',$result['c']);
			$this->assign('detail_D',$result['d']);
			$this->assign('detail_E',$result['e']);
			$this->assign('detail_F',$result['f']);
			$this->assign('detail_G',$result['g']);
			$this->assign('detail_H',$result['h']);
			$this->assign('detail_ans',ansToChar($result['answer'],$result['type']));
			$this->display('option_detail');
		}
		
	}
}

?>