<?php 
namespace Admin\Controller;

use Admin\Controller\CommonController;

class StuImportController extends CommonController
{
	public function paperList($page = 1, $rows = 10, $sort = 'dictid', $order = 'asc', $courseid = '', $typeid = '') {
		if (IS_POST && $courseid != '' ) {
			$Paperdata_db=D('Paperdata');
			$result=$Paperdata_db->paperList($page,$rows,$courseid);
			echo json_encode($result);
		} else {
			$this->display ( 'stuImport_index' );
		}
	}

	public function download(){
		header("Content-type:application/-excel;charset=utf-8"); 
		$file_name='StuImport_Demo.xls' ;
		//用以解决中文不能显示出来的问题 
		$file_name=iconv("utf-8","gb2312",$file_name); 
		//绝对路径
		$file_sub_path=$_SERVER['DOCUMENT_ROOT']."/thinkphpcms/Public/Downloads/"; 	
		$file_path=$file_sub_path.$file_name; 
		//首先要判断给定的文件存在与否 
		if(!file_exists($file_path)){ 
			echo "没有该文件"; 
			return ; 
		} 
		$fp=fopen($file_path,"r"); 
		$file_size=filesize($file_path); 
		//下载文件需要用到的头 
		Header("Content-type: application/octet-stream"); 
		Header("Accept-Ranges: bytes"); 
		Header("Accept-Length:".$file_size); 
		Header("Content-Disposition: attachment; filename=".$file_name); 
		$buffer=1024; 
		$file_count=0; 
		//向浏览器返回数据 
		while(!feof($fp) && $file_count<$file_size){ 
			$file_con=fread($fp,$buffer); 
			$file_count+=$buffer; 
			echo $file_con; 
		} 
		fclose($fp); 
	}

	function upload() {
		$upload = new \Think\Upload ();
		$upload->maxSize = 3145728;
		$upload->exts = array (
			'xls' 
			);
		$upload->rootPath = './Uploads/';
		$upload->saveName = date ( 'ymdhis' );
		$info = $upload->upload ();
		$info = $info ['xlsfile'];   
		if (!$info) {
			echo json_encode($upload->getError());
		} else {
			$filename = 'Uploads/' . $info ['savepath'] . $info ['savename'];
			$ext = $info ['ext'];
			$this->XlsToSql( $filename, $ext );

		}
	}
	function XlsToSql($filename, $exts = 'xls') {
		//excel  学号姓名课程
		vendor ( 'PHPExcel.PHPExcel' );
		$PHPExcel = new \PHPExcel ();
		if ($exts == 'xls') {
			$PHPReader = new \PHPExcel_Reader_Excel5 ();
		} else if ($exts == 'xlsx') {
			$PHPReader = new \PHPExcel_Reader_Excel2007 ();
		}
		$PHPExcel = $PHPReader->load ( $filename );
		
		$currentSheet = $PHPExcel->getSheet ( 0 );
		$allColumn = $currentSheet->getHighestColumn ();
		$allRow = $currentSheet->getHighestRow ();
		for($currentRow = 1; $currentRow <= $allRow; $currentRow ++) {
			for($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn ++) {
				$address = $currentColumn.$currentRow;
				$data [$currentRow] [$currentColumn] = $currentSheet->getCell ( $address )->getValue ();
			}
		}

		 //
		$Dictdata_db=D('Dictdata');
		$Paperdata_db=D('Paperdata');
		$StuImport_db=D('StuImport');
		$count=0;
		$error='';
		foreach ($data as $key => $value){
			$courseId=$Dictdata_db->getID($value['C'],200);
			$check=$StuImport_db->getPaperID($value['A'],$courseId);
			if(!$check){
				$paperlist=$Paperdata_db->getCourseInfo($courseid);
				$total=count($paperlist);
				$paperId=$paperlist[rand(0,$total-1)]['paper_id'];
				$StuImport_db->addItem($value['A'],$value['B'],$courseId,$paperId);
				$count++;
			}else{
				$error.='学号:'.$value['A'].' 姓名:'.$value['B'].' 课程:'.$value['C'].' 数据已存在！<br/>';
			}
		}
		$susses='成功导入'.$count.'条数据！<br/>';
		echo json_encode ($susses.$error);
	}
	function removeStudent(){
		if(IS_POST){
			$studentid=$_POST['studentid'];
			$paperid=$_POST['paperid'];
			$StuImport_db=D('StXuImport');
			$result=$StuImport_db->delItem($studentID,$paperID);
			echo json_encode($result);
		}

	}
}
?>