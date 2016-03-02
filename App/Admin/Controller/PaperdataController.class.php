<?php

namespace Admin\Controller;

use Admin\Controller\CommonController;

class PaperdataController extends CommonController {
	function paperList($page = 1, $rows = 10, $sort = 'dictid', $order = 'asc', $courseid = '', $typeid = '') {
		if (IS_POST && $courseid != '' ) {
			$paper_db = D( 'Paperdata' );
			$dict_db=D('Dictdata');
			$limit = ($page - 1) * $rows . "," . $rows;
			$condition ['course_id'] = $courseid;
			$list = $paper_db->where ( $condition )->limit ( $limit )->select ();
			$total=$paper_db->where ( $condition )->count ();
			if($total>0){
				foreach ($list as $key=>$value){
					foreach ($value as $key1=>$value1){
						$data[$key][$key1]=$value1;
					}
					$data [$key] ["course_id"] = $dict_db->getName($value['course_id']);
				}
				$result=array('total' => $total,'rows' => $data);
			}else{
				$result=array('total' =>0,'rows' => []);
			}
			echo json_encode($result);
		} else {
			$this->display ( 'paper_index' );
		}
	} 

	//含有去重处理的添加列表
	function AddOptionList($page = 1, $rows = 10, $sort = 'dictid', $order = 'asc', $typeid = '' ,$courseid='',$paper_id='') {
		//$courseid=201;
		//$typeid=101;
		//$paper_id=201001;  
		//var_dump($paper_option);
		if (IS_POST && $courseid != '' && $typeid != '') {
			$paper_db=D('Paperdata');
			$paper_option=$paper_db->getContent($paper_id);
			if($paper_option){
				$condition=array(
					'course_id'=>$courseid,
					'type'=>$typeid,
					'question_id'=>array('NOT IN',$paper_option)
					);
			}else{
				$condition=array(
					'course_id'=>$courseid,
					'type'=>$typeid
					);
			};
			//var_dump($condition);
			$option_db = D( 'Optiondata' );
			$dict_db=D('Dictdata');
			$total = $option_db->where($condition)->count ();
			$order = $sort . ' ' . $order;
			$limit = ($page - 1) * $rows . "," . $rows;
			$list = $option_db->where ( $condition )->limit ( $limit )->select ();
			$arr = object_array ( $list );
			if ($total > 0) {
				foreach ( $arr as $key => $value ) {
					foreach ( $value as $key1 => $value1 ) {
						$data [$key] [$key1] = $value1;
					}
					$data [$key] ["course"] = $dict_db->getName($value['course_id']);
					$data [$key] ["type"] =$dict_db->getName($value['type']);
				}
				//假如所有题目都被添加到该试卷
				$data2 = array ('total' => $total,'rows' => $data); 
			} else {
				$data2 = array ('total' => 0,'rows' => [ ]);
			}
			echo json_encode ( $data2 );
		}else{
			$paper_db=D('Paperdata');
			$course_id=$paper_db->getCourseID($paper_id);
			$this->assign('hidden_course_id',$course_id);
			$this->assign('hidden_paper_id',$paper_id);
			$this->display('paper_add');
		}
	}
	//preview
	function EditOptionList($page = 1, $rows = 10, $sort = 'dictid', $order = 'asc', $typeid = '' ,$courseid='',$paper_id='') {
		/*$courseid=201;
		$typeid=101;
		$paper_id=201001;*/
		//只需要查询PaperData表进行数据导出
		if($paper_id!=''){
			$Paperdata_db=D('Paperdata');
			$Optiondata_db=D('Optiondata');
			$dict_db=D('Dictdata');
			$paper_option=$Paperdata_db->getContent($paper_id);
			var_dump($paper_id);
			$total=count($paper_option);
			if($total>0){
				//data：id-course-type-title数据数组
				echo '<script type="text/javascript" src="Public/static/js/Admin/paperdata/edit.js"></script>';
				echo '<h6>试卷名称:' .$Paperdata_db->getPaperName($paper_id).'</h6>';
				echo '<h6>所属科目:'.$Paperdata_db->getCourseName($paper_id).'</h6>';
				foreach ($paper_option as $key=>$value){
					//$value=$data['question_id']   使用value可以处理题目被删除之后的$data['question_id']查询不到没有数据的情况
					$urlEdit="\"index.php?m=admin&c=Optiondata&a=Optionedit&id=".$value."\" ";
					echo"<hr /><table width='700px'>
					<tr>
						<td colspan='3'>第".($key+1)."题:</td><td><a target='_blank' href=".$urlEdit.">修改</td>
						<td><a href='javascript:void(0)'  onclick='RemoveOption(".$paper_id.",".$value.")'>删除</a></td>
					</tr>
					<tr>
						<td width='25%'>".$value."</td>
						<td width='25%'>".$Optiondata_db->getTypeName($value)."</td>
						<td width='25%'>".$Optiondata_db->getKeyword($value)."</td>
					</tr>
					<tr><td colspan='3'>标题:".$Optiondata_db->getTitle($value)."</td></tr>";
					if($option=$Optiondata_db->getOptionA($value)) echo"<tr><td colspan='3'>A:".$option."</td></tr>";
					if($option=$Optiondata_db->getOptionB($value)) echo"<tr><td colspan='3'>B:".$option."</td></tr>";
					if($option=$Optiondata_db->getOptionC($value)) echo"<tr><td colspan='3'>C:".$option."</td></tr>";
					if($option=$Optiondata_db->getOptionD($value)) echo"<tr><td colspan='3'>D:".$option."</td></tr>";
					if($option=$Optiondata_db->getOptionE($value)) echo"<tr><td colspan='3'>E:".$option."</td></tr>";
					if($option=$Optiondata_db->getOptionF($value)) echo"<tr><td colspan='3'>F:".$option."</td></tr>";
					if($option=$Optiondata_db->getOptionG($value)) echo"<tr><td colspan='3'>G:".$option."</td></tr>";
					if($option=$Optiondata_db->getOptionH($value)) echo"<tr><td colspan='3'>H:".$option."</td></tr>";
					echo"<tr><td colspan='3'>答案:".ansToChar($Optiondata_db->getOptionAnswer($value),$Optiondata_db->getTypeID($value))."</td></tr>";
					echo "</table>";
				}
			}else{
				echo "没有数据！";
			}
		}
	}
	function paperNewCheckId(){
		$id=$_POST['id'];
		$Paperdata_db=D('Paperdata');
		$result=$Paperdata_db->where('paper_id='.$id)->select();
		echo json_encode($result);
	}
	function paperNewCheckName(){
		$name=$_POST['name'];
		$Paperdata_db=D('Paperdata');
		$result=$Paperdata_db->where('paper_name='.$name)->select();
		echo json_encode($result);
	}

//新建试卷
	function paperNew(){
		$Paperdata_db=M('paper_content');
		$data = array(
			'paper_name'=>$_POST['paper_name'],
			'course_id'=>$_POST['course_id'],
			'total_grade'=>0,
			'question_num'=>0,
			'test_time'=>$_POST['test_time'],
			'deadline'=>$_POST['deadline']
			);
		$Paperdata_db->add($data);
	}
	//增加试题
	function paperAdd(){
		$Paperdata_db=D('Paperdata');
		$Optiondata_db=D('Optiondata');
		//opptionid为array
		$optionid=$_POST['optionid'];
		$paperid=$_POST['paperid'];
		$paper_option=$Paperdata_db->getContent($paperid);
		//添加该题目
		$option_count=count($paper_option);
		foreach ($optionid as $key => $value) {
			$paper_option[$option_count+$key+1]=$value;
		}
		$paper_option = array_values($paper_option);
		$Paperdata_db->setContent($paperid,$paper_option);
	}
	//减少试题
	function paperEdit(){
		$Paperdata_db=D('Paperdata');
		$optionid=$_POST['optionid'];
		$paperid=$_POST['paperid'];
		//需要修改的试卷信息和需要添加的题目信息
		$paper_option=$Paperdata_db->getContent($paperid);
		foreach ($paper_option as $key => $value) {
			if($value==$optionid){
				unset($paper_option[$key]);
			}		
		}
		$paper_option=array_values($paper_option);
		$Paperdata_db->setContent($paperid,$paper_option);
	}
	//删除试卷
	function paperRemove(){
		$paper_id=$_POST['paper_id'];
		$Paperdata_db=D('Paperdata');
		$Paperdata_db->deleteItem($paper_id);
		$paperGrade_db=D('Paperdata');
		$paperGrade_db->deletePaperInfo($paper_id);
		echo json_encode($paper_id);

	}
	function getPaperNameJson(){
		$paper_db=D('Paperdata');
		$result=$paper_db->select();
		echo json_encode($result);
	}
	//统计有多少不同类型的题目
	function getOptionTypeCount(){
		$PaperId=$_POST['PaperId'];
		$Dictdata_db=D('Dictdata');
		$result=$Dictdata_db->where('belong_type=100')->select();
		$TypeCount=Array();
		foreach ($result as $key => $value) {
			$TypeCount[$value['type_id']]=0;
		}
		$Optiondata_db=D('Optiondata');
		$paper_db=D('Paperdata');
		$paper_option=$paper_db->getContent($paper_id);
		foreach ($array_content as $key => $value) {
			$optioninfo=$Optiondata_db->where(array('question_id'=>$value))->find();
			if($optioninfo){
				$TypeCount[$optioninfo['type']]++;
			}
		}
		echo json_encode($TypeCount);
	}
	function modifyPaperGrade(){
		$ret = false ;
		$paperGrade_db=D('Papergrade');
		$paperID=$_POST['testId'];
		$typeID=$_POST['typeId'];
		$typeGrade=intval($_POST['typeGrade']);
		$ret = $paperGrade_db->setTypeInfo($paperID,$typeID,$typeGrade);
		echo json_encode($ret);
	}

	function getPaperDetail($itemid){
		$paperID=$itemid;
		$Dictdata_db=D('Dictdata');
		$typeList=$Dictdata_db->getTypeList();
		foreach ($typeList as $key => $value) {
			$typeList[$key]['type_num']=0;
		}
		$Paperdata_db=D('Paperdata');
		$Optiondata_db=D('Optiondata');
		$contentList=$Paperdata_db->getContent($paperID);
		foreach ($contentList as $k => $optionID) {
			foreach ($typeList as $key => $value) {
				if($Optiondata_db->getTypeID($optionID)==$value['type_id']){
					$typeList[$key]['type_num']++;
				}
			}
		}
		$total=0;
		$Papergrade_db=D('Papergrade');
		foreach ($typeList as $key => $value) {
			if($value['type_num']==0){
				unset($typeList[$key]);
			}else{
				$typeList[$key]['type_grade']=$Papergrade_db->getPaperTypeGrade($paperID,$value['type_id']);
				$typeList[$key]['type_total']=$typeList[$key]['type_grade'] * $typeList[$key]['type_num'];
				$total +=$typeList[$key]['type_total'];
			}
		}
		$this->assign('total',$total);
		$this->assign('list',$typeList);
		$this->assign('paperID',$paperID);
		$this->display('paper_detial');
	}
}
?>