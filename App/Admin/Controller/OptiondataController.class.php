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

	function test(){
		$this->exportXls($this->jsonToItem());
	}
	function  jsonToItem(){
		$str = '{"a":{"a":"","b":"","c":"","d":"0","e":"0"},"b":[{"id":"114297","a":"浙江省中小学（初中）信息技术等级考试（二级）试题第一套","b":"102810","c":"黄海","d":"理工学院","e":"50","f":"1307177297","g":"1307177297","h":"45","i":"1","j":"1","k":"1","l":"2","m":"0","n":"0","o":"","p":"0","q":""},{"id":"b","c":[{"c":"一、判断题","p":"1"}]},{"id":"s3_473347","a":"在计算机的存储器中，内存的存取速度比外存储器要快。（&nbsp; &nbsp; &nbsp; ）","c":"1","d":"2","e":"0","f":0},{"id":"s3_473348","a":"一个完整的计算机系统应包括系统软件和应用软件。（&nbsp; &nbsp; &nbsp; ）","c":"2","d":"2","e":"0","f":0},{"id":"s3_473349","a":"Windows 98中，窗口被最大化后，最大化按钮会变为还原按钮。（&nbsp; &nbsp; &nbsp; ）","c":"1","d":"2","e":"0","f":0},{"id":"s3_473350","a":"计算机网络不会传播计算机病毒。（&nbsp; &nbsp; &nbsp; ）","c":"2","d":"2","e":"0","f":0},{"id":"s3_473351","a":"删除后的文件一定能从回收站中恢复。（&nbsp;&nbsp; ）","c":"2","d":"2","e":"0","f":0},{"id":"b","c":[{"c":"二、选择题","p":"2"}]},{"id":"s1_473352","a":"办公自动化是计算机的一项应用，按计算机应用的分类，它属于（&nbsp; ）","b":"[{\"o\":\"科学计算\"},{\"o\":\"实时控制\"},{\"o\":\"数据处理\"},{\"o\":\"辅助设计\"}]","c":"4","d":"4","e":"0","f":0},{"id":"s1_473353","a":"Windows 98操作系统是一个","b":"[{\"o\":\"单用户多任务操作系统\"},{\"o\":\"单用户单任务操作系统\"},{\"o\":\"多用户单任务操作系统\"},{\"o\":\"多用户多任务操作系统\"}]","c":"1","d":"4","e":"0","f":0},{"id":"s1_473354","a":"设Windows 98桌面上已经有某应用程序的图标，在系统默认方式下，要运行该程序，可以（&nbsp; ）","b":"[{\"o\":\"用鼠标左键单击该图标\"},{\"o\":\"用鼠标右键单击该图标\"},{\"o\":\"用鼠标左键双击该图标\"},{\"o\":\"用鼠标右键双击该图标\"}]","c":"4","d":"4","e":"0","f":0},{"id":"s1_473355","a":"在只有一个软驱的计算机中，软驱的盘符通常用（&nbsp; ）。","b":"[{\"o\":\"A：\"},{\"o\":\"B：\"},{\"o\":\"C：\"},{\"o\":\"D：\"}]","c":"1","d":"4","e":"0","f":0},{"id":"s1_473356","a":"微型计算机的键盘上用于输入上档字符和转换英文大小写字母输入的键是（&nbsp; ）","b":"[{\"o\":\"&lt;Alt&gt;键\"},{\"o\":\"&lt;Ctrl&gt;键\"},{\"o\":\"&lt;Shift&gt;键\"},{\"o\":\"&lt;Tab&gt;键\"}]","c":"4","d":"4","e":"0","f":0},{"id":"s1_473357","a":"下列存储器中，存取速度最快的是（&nbsp; ）。","b":"[{\"o\":\"软盘\"},{\"o\":\"硬盘\"},{\"o\":\"光盘\"},{\"o\":\"内存\"}]","c":"8","d":"4","e":"0","f":0},{"id":"s1_473358","a":"对计算机软件正确的认识应该是（&nbsp; ）。","b":"[{\"o\":\"计算机软件受法律保护是多余的\"},{\"o\":\"正版软件太贵，软件能复制就不必购买\"},{\"o\":\"受法律保护的计算机软件不能随便复制\"},{\"o\":\"正版软件只要能解密就能随意使用\"}]","c":"4","d":"4","e":"0","f":0},{"id":"s1_473359","a":"Word的\"文件\"命令菜单底部显示的文件名所对应的文件是（&nbsp; ）","b":"[{\"o\":\"当前被操作的文件\"},{\"o\":\"当前已经打开的所有文件\"},{\"o\":\"最近被操作过的文件\"},{\"o\":\"扩展名是.doc的所有文件\"}]","c":"4","d":"4","e":"0","f":0},{"id":"s1_473360","a":"在Windows网络环境中，要访问其他计算机，可以打开（&nbsp; ）","b":"[{\"o\":\"我的电脑\"},{\"o\":\"控制面板\"},{\"o\":\"网上邻居\"},{\"o\":\"我的文档\"}]","c":"4","d":"4","e":"0","f":0},{"id":"s1_473361","a":"电子邮件地址一般的格式是（&nbsp; ）","b":"[{\"o\":\"用户名@域名\"},{\"o\":\"域名@用户名\"},{\"o\":\"IP@域名\"},{\"o\":\"域名@IP\"}]","c":"1","d":"4","e":"0","f":0}],"c":"[{\"id\":\"s3_473347\"},{\"id\":\"s3_473348\"},{\"id\":\"s3_473349\"},{\"id\":\"s3_473350\"},{\"id\":\"s3_473351\"},{\"id\":\"s1_473352\"},{\"id\":\"s1_473353\"},{\"id\":\"s1_473354\"},{\"id\":\"s1_473355\"},{\"id\":\"s1_473356\"},{\"id\":\"s1_473357\"},{\"id\":\"s1_473358\"},{\"id\":\"s1_473359\"},{\"id\":\"s1_473360\"},{\"id\":\"s1_473361\"}]"}';
		$arr = json_decode($str,true)[b];
		for ($i=0; $i <10 ; $i++) { 
			array_shift($arr);
		}
		//echo json_encode($arr);
		$data = array();
		foreach ($arr as $key => $value) {
			$data[$key]['title']=$value['a'];
			$data[$key]['type']=101;
			$data[$key]['course_id']=201;
			$option = json_decode($value['b'],true);
			for ($i=0; $i <count($option) ; $i++) { 
				$data[$key][chr(97+$i)]=$option[$i]['o'];
			}
			$data[$key]['answer']=$value['c'];
		}
		return $data;
	}
	function exportXls($data){
		vendor ( 'PHPExcel.PHPExcel' );
		$resultPHPExcel = new \PHPExcel ();
		$Dict_db=D('Dictdata');
		//设置参数 
		$resultPHPExcel->getActiveSheet()->setCellValue('A1', '课程名称'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('B1', '题目类型'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('C1', '题目名称'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('D1', 'a'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('E1', 'b'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('F1', 'c'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('G1', 'd'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('H1', 'e'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('I1', 'f'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('J1', 'g'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('K1', 'h'); 
		$resultPHPExcel->getActiveSheet()->setCellValue('L1', '答案'); 
		$i = 2; 
		foreach($data as $item){ 
			$resultPHPExcel->getActiveSheet()->setCellValue('A' . $i, $Dict_db->getName($item['course_id'])); 
			$resultPHPExcel->getActiveSheet()->setCellValue('B' . $i, $Dict_db->getName($item['type'])); 
			$resultPHPExcel->getActiveSheet()->setCellValue('C' . $i, $item['title']); 
			$resultPHPExcel->getActiveSheet()->setCellValue('D' . $i, $item['a']); 
			$resultPHPExcel->getActiveSheet()->setCellValue('E' . $i, $item['b']); 
			$resultPHPExcel->getActiveSheet()->setCellValue('F' . $i, $item['c']); 
			$resultPHPExcel->getActiveSheet()->setCellValue('G' . $i, $item['d']); 
			$resultPHPExcel->getActiveSheet()->setCellValue('H' . $i, $item['e']); 
			$resultPHPExcel->getActiveSheet()->setCellValue('I' . $i, $item['f']); 
			$resultPHPExcel->getActiveSheet()->setCellValue('J' . $i, $item['g']); 
			$resultPHPExcel->getActiveSheet()->setCellValue('K' . $i, $item['h']); 
			$resultPHPExcel->getActiveSheet()->setCellValue('L' . $i, chr(64+$item['answer'])); 
			$i ++; 
			//var_dump($item);
		}
		//设置导出文件名 
		$outputFileName = 'total.xls'; 
		$xlsWriter = new \PHPExcel_Writer_Excel5($resultPHPExcel); 
		header("Content-Type: application/force-download"); 
		header("Content-Type: application/octet-stream"); 
		header("Content-Type: application/download"); 
		header('Content-Disposition:inline;filename="'.$outputFileName.'"'); 
		header("Content-Transfer-Encoding: binary"); 
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
		header("Pragma: no-cache"); 
		$xlsWriter->save( "php://output" );
	}
}

?>