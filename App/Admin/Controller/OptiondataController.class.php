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
		$str = '{"a":{"a":"","b":"","c":"","d":"0","e":"0"},"b":[{"id":"151576","a":"2012年外科主治医师颅内压增高测试题","b":"295185","c":"tinalu","d":"","e":"20","f":"1357723593","g":"1357723593","h":"30","i":"1","j":"1","k":"2","l":"2","m":"0","n":"0","o":"","p":"0","q":""},{"id":"b","c":[{"c":"一、单项选择题","p":"1"}]},{"id":"s1_1848145","a":"有一名颅内压增高病人，病情有加剧表现，处理的关键措施是","b":"[{\"o\":\"头颅CT，明确病变的性质和部位\"},{\"o\":\"安静卧床，头高30度\"},{\"o\":\"保持便通\"},{\"o\":\"20%甘露醇250ml，一日二次，静点\"},{\"o\":\"限制水，盐入量\"}]","c":"1","d":"1","e":"0","f":0},{"id":"s1_1848146","a":"颅内压增高病人的一般处理中，下列哪项是不正确的","b":"[{\"o\":\"注意观察意识，瞳孔及生命体征的变化\"},{\"o\":\"频繁呕吐时，予以禁食，用脱水剂\"},{\"o\":\"意识不清痰多者作气管切开吸痰\"},{\"o\":\"作高位灌肠以疏通大便\"},{\"o\":\"静脉补液以保持尿量\"}]","c":"8","d":"1","e":"0","f":0},{"id":"s1_1848147","a":"颅内压增高病人昏迷，治疗呼吸道梗阻最有效措施是","b":"[{\"o\":\"通过鼻腔口腔导管吸痰\"},{\"o\":\"气管插管，呼吸机辅助呼吸\"},{\"o\":\"环甲膜穿刺\"},{\"o\":\"气管切开\"},{\"o\":\"用开口器侧卧位引流\"}]","c":"8","d":"1","e":"0","f":0},{"id":"s1_1848148","a":"有一颅内压增高病人，其原理是","b":"[{\"o\":\"颅内新生物生长\"},{\"o\":\"脑积水\"},{\"o\":\"颅腔内有出血\"},{\"o\":\"颅腔内有感染\"},{\"o\":\"颅内压的生理调节功能失调\"}]","c":"16","d":"1","e":"0","f":0},{"id":"s1_1848149","a":"有一名颅内压增高病人，哪项不是其头颅X线平片的改变","b":"[{\"o\":\"颅缝增宽\"},{\"o\":\"脑回压迹增多\"},{\"o\":\"蝶鞍扩大，前后床突骨质吸收\"},{\"o\":\"蛛网膜颗粒压迹扩大\"},{\"o\":\"一侧或双侧内听道扩大\"}]","c":"16","d":"1","e":"0","f":0},{"id":"s1_1848150","a":"有一名颅内压增高病人，持续颅内压增高导致病理生理紊乱，但应除外","b":"[{\"o\":\"脑血管自动调节功能失调\"},{\"o\":\"胃肠道出血，溃疡\"},{\"o\":\"脑疝形成\"},{\"o\":\"肺水肿\"},{\"o\":\"早期血压下降，脉搏变快，呼吸增快\"}]","c":"16","d":"1","e":"0","f":0},{"id":"s1_1848151","a":"有一名30岁男患，病程4个月，头痛发病，入院前出现左侧肢体无力和呕吐，入院检查，意识清，眼底视乳头水肿，左上下肢肌力Ⅳ级，腱反射活跃，病理征（+）。诊断是","b":"[{\"o\":\"脑梗死\"},{\"o\":\"脑出血\"},{\"o\":\"蛛网膜下腔出血\"},{\"o\":\"脑水肿\"},{\"o\":\"颅内压增高\"}]","c":"16","d":"1","e":"0","f":0},{"id":"s1_1848152","a":"有一名30岁男患，病程4个月，头痛发病，入院前出现左侧肢体无力和呕吐，入院检查，意识清，眼底视乳头水肿，左上下肢肌力Ⅳ级，腱反射活跃，病理征（+）。应采用的检查，是","b":"[{\"o\":\"X线颅片\"},{\"o\":\"脑电图\"},{\"o\":\"脑血管造影\"},{\"o\":\"CT\"},{\"o\":\"ECT\"}]","c":"8","d":"1","e":"0","f":0},{"id":"s1_1848153","a":"有一名30岁男患，病程4个月，头痛发病，入院前出现左侧肢体无力和呕吐，入院检查，意识清，眼底视乳头水肿，左上下肢肌力Ⅳ级，腱反射活跃，病理征（+）。根本治疗原则，是","b":"[{\"o\":\"脱水治疗\"},{\"o\":\"给予镇痛剂\"},{\"o\":\"冬眠物理降温\"},{\"o\":\"去病因治疗\"},{\"o\":\"去骨开减压。\"}]","c":"8","d":"1","e":"0","f":0},{"id":"s1_1848154","a":"有一名40岁男患，病程4个月，右侧肢体无力发病，逐渐出现头痛和语言笨拙，入院检查眼底视乳头水肿，不全运动失语，右上下肢肌力Ⅳ级，右下肢病理征（+）。考虑病变部位是","b":"[{\"o\":\"右额部\"},{\"o\":\"左额部\"},{\"o\":\"右顶部\"},{\"o\":\"左顶部\"},{\"o\":\"右小脑\"}]","c":"2","d":"1","e":"0","f":0},{"id":"s1_1848155","a":"有一名40岁男患，病程4个月，右侧肢体无力发病，逐渐出现头痛和语言笨拙，入院检查眼底视乳头水肿，不全运动失语，右上下肢肌力Ⅳ级，右下肢病理征（+）。采用的辅助检查是","b":"[{\"o\":\"X线颅平\"},{\"o\":\"脑电图\"},{\"o\":\"脑血管造影\"},{\"o\":\"CT\"},{\"o\":\"ESCT\"}]","c":"8","d":"1","e":"0","f":0},{"id":"s1_1848156","a":"处理颅内压增高，哪一项是错误的","b":"[{\"o\":\"频繁呕吐时宜禁食\"},{\"o\":\"限制输液量及速度\"},{\"o\":\"便秘4天以上给予高位，高压灌肠\"},{\"o\":\"静点地塞米松\"},{\"o\":\"早期行病因治疗\"}]","c":"4","d":"1","e":"0","f":0},{"id":"s1_1848157","a":"诊断颅内占位病变，无痛、安全、准确的方法是","b":"[{\"o\":\"头颅CT\"},{\"o\":\"头颅X线平片\"},{\"o\":\"脑电图\"},{\"o\":\"脑血管造影\"},{\"o\":\"气脑造影\"}]","c":"1","d":"1","e":"0","f":0},{"id":"s1_1848158","a":"排除颅内占位病变，哪一项是准确的","b":"[{\"o\":\"无视乳头水肿\"},{\"o\":\"颅平片无颅内压增高表现\"},{\"o\":\"叩诊小儿头颅无破壶音\"},{\"o\":\"脑超声中线波无移位\"},{\"o\":\"CT扫描无异常改变\"}]","c":"16","d":"1","e":"0","f":0},{"id":"s1_1848159","a":"颅内压增高的容积代偿（即空间代偿）主要依靠","b":"[{\"o\":\"脑组织的压缩\"},{\"o\":\"颅腔的扩大\"},{\"o\":\"脑脊液被排出颅外\"},{\"o\":\"血压的下降\"},{\"o\":\"脑血流量减少\"}]","c":"4","d":"1","e":"0","f":0},{"id":"s1_1848160","a":"颅内压增高“三主症”包括","b":"[{\"o\":\"偏瘫，偏盲，偏身感觉障碍\"},{\"o\":\"头痛，呕吐，偏瘫\"},{\"o\":\"头痛，抽搐，意识障碍\"},{\"o\":\"头痛，呕吐，视乳头水肿\"},{\"o\":\"头痛，呕吐，血压增高\"}]","c":"8","d":"1","e":"0","f":0},{"id":"s1_1848161","a":"慢性颅内压增高的主要临床表现是","b":"[{\"o\":\"头痛，呕吐，肢体运动障碍\"},{\"o\":\"头痛，瞳孔异常和肢体运动障碍\"},{\"o\":\"血压，呼吸和脉搏改变\"},{\"o\":\"头痛，呕吐，视乳头水肿\"},{\"o\":\"进行性意识障碍\"}]","c":"8","d":"1","e":"0","f":0},{"id":"s1_1848162","a":"关于颅内压增高，下列哪项是错误的","b":"[{\"o\":\"喷射性呕吐多见\"},{\"o\":\"后期常伴视力障碍\"},{\"o\":\"阵发性头痛是主要症状之一\"},{\"o\":\"某些病例可始终不出现“三主症”\"},{\"o\":\"在婴幼儿头痛出现较早且较重\"}]","c":"16","d":"1","e":"0","f":0},{"id":"s1_1848163","a":"视乳头水肿在临床诊断颅内病变的意义是","b":"[{\"o\":\"出现视乳头水肿，可肯定颅内有占位病变\"},{\"o\":\"无视乳头水肿，可排除颅内占位病变\"},{\"o\":\"视乳头水肿，对颅内占位病变性质有鉴别价值\"},{\"o\":\"无视乳头水肿，可排除颅内压增高\"},{\"o\":\"视乳头水肿，是颅内压增高的重要体征之一\"}]","c":"16","d":"1","e":"0","f":0},{"id":"s1_1848164","a":"降低颅内压增高的最有效易行的方法是","b":"[{\"o\":\"腰穿大量引流脑脊液\"},{\"o\":\"施行人工冬眠物理降温\"},{\"o\":\"进行控制性过度换气\"},{\"o\":\"使用脱水剂或利尿剂\"},{\"o\":\"将病员置于高压氧仓内\"}]","c":"8","d":"1","e":"0","f":0}],"c":"[{\"id\":\"s1_1848145\"},{\"id\":\"s1_1848146\"},{\"id\":\"s1_1848147\"},{\"id\":\"s1_1848148\"},{\"id\":\"s1_1848149\"},{\"id\":\"s1_1848150\"},{\"id\":\"s1_1848151\"},{\"id\":\"s1_1848152\"},{\"id\":\"s1_1848153\"},{\"id\":\"s1_1848154\"},{\"id\":\"s1_1848155\"},{\"id\":\"s1_1848156\"},{\"id\":\"s1_1848157\"},{\"id\":\"s1_1848158\"},{\"id\":\"s1_1848159\"},{\"id\":\"s1_1848160\"},{\"id\":\"s1_1848161\"},{\"id\":\"s1_1848162\"},{\"id\":\"s1_1848163\"},{\"id\":\"s1_1848164\"}]"}';



		$arr = json_decode($str,true)[b];
		for ($i=0; $i <10 ; $i++) { 
			array_shift($arr);
		}
		//echo json_encode($arr);
		$data = array();
		foreach ($arr as $key => $value) {
			$data[$key]['title']=$value['a'];
			$data[$key]['answer']=$value['c'];
			$data[$key]['course_id']=204;
			$option = json_decode($value['b'],true);
			if(count($option)==0){
				$data[$key]['type']=103;
				$data[$key]['a']='正确';
				$data[$key]['b']='错误';
				continue;
			}
			else if(isRadioAns($data[$key]['answer'])){
				$data[$key]['type']=101;
			} else{
				$data[$key]['type']=102;
			}
			for ($i=0; $i <count($option) ; $i++) { 
				$data[$key][chr(97+$i)]=$option[$i]['o'];
			}
		}
		foreach ($data as $key => $value) {
			if($value['title']==''){
				unset($data[$key]);
			}
		}
		array_values($data);
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
			$resultPHPExcel->getActiveSheet()->setCellValue('L' . $i, ansToChar($item['answer'])); 
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