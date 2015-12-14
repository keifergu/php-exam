<?php

namespace Admin\Controller;

use Admin\Controller\CommonController;

class PaperdataController extends CommonController {
	function paperList($page = 1, $rows = 10, $sort = 'dictid', $order = 'asc', $courseid = '', $typeid = '') {
		if (IS_POST && $courseid != '' ) {
			$paper_db = D( 'Paperdata' );
			$limit = ($page - 1) * $rows . "," . $rows;
			$condition ['course_id'] = $courseid;
			$list = $paper_db->where ( $condition )->limit ( $limit )->select ();
			$total=count($list);
			if($total>0){
				foreach ($list as $key=>$value){
					foreach ($value as $key1=>$value1){
						$data[$key][$key1]=$value1;
					}
					$coursename = array ();
					$coursename=getDictName( $list [$key] ["course_id"] );
					$data [$key] ["course_id"] = $coursename [0] ["type_name"];
				}
				$result=array(
					'total' => $total,
					'rows' => $data
					);
			}else{
				$result=array(
					'total' =>0,
					'rows' => []
					);
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
		if (IS_POST && $courseid != '' && $typeid != '') {
			$option_db = M ( 'Optiondata' );
			$total = $option_db->count ();
			$order = $sort . ' ' . $order;
			$limit = ($page - 1) * $rows . "," . $rows;
			$condition ['course'] = $courseid;
			$condition ['type'] = $typeid;
			$list = $option_db->where ( $condition )->limit ( $limit )->select ();
			$arr = object_array ( $list );
			$total = count ($arr);
			//去重处理
			$paper_db=D('Paperdata');
			$paperinfo=$paper_db->where('paper_id='.$paper_id)->select();
			$paper_option=json_decode($paperinfo[0]['content']);
			//$paper_option=explode(';',$paperinfo[0]['content']);
			if ($total > 0) {
				$existNum=0;
				foreach ( $arr as $key => $value ) {
					$flag=0;
					foreach ($paper_option as $keys => $values) {
						if($values==$value['question_id']){
							$existNum++;
							$flag=1;
						}
					}
					if(!$flag){
						$keyresult=$key-$existNum;
						foreach ( $value as $key1 => $value1 ) {
							$data [$keyresult] [$key1] = $value1;
						}
						$coursename = array ();
						$coursename = object_array ( getDictName ( $arr [$keyresult] ["course_id"] ) );
						$data [$keyresult] ["course"] = $coursename [0] ["type_name"];
						$coursename = object_array ( getDictName ( $arr [$keyresult] ["type"] ) );
						$data [$keyresult] ["type"] = $coursename [0] ["type_name"];
					}
				}
				//假如所有题目都被添加到该试卷
				if($total-$existNum){
					$data2 = array (
						'total' => $total-$existNum,
						'rows' => $data
						); 
				}else{
					$data2 = array (
						'total' => 0,
						'rows' => [ ]
						);
				}
				
			} else {
				$data2 = array (
					'total' => 0,
					'rows' => [ ]
					);
			}
			echo json_encode ( $data2 );
		}else{
			$Paperdata_db=D('Paperdata');
			$paperinfo=$Paperdata_db->where('paper_id='.$paper_id)->select();
			$course_id=$paperinfo[0]['course_id'];
			$this->assign('hidden_course_id',$course_id);
			$this->assign('hidden_paper_id',$paper_id);
			$this->display('paper_add');
		}
	}

	function EditOptionList($page = 1, $rows = 10, $sort = 'dictid', $order = 'asc', $typeid = '' ,$courseid='',$paper_id='') {
		/*$courseid=201;
		$typeid=101;
		$paper_id=201001;*/
		//只需要查询PaperData表进行数据导出
		if($paper_id!=''){
			$Paperdata_db=D('Paperdata');
			$Optiondata_db=D('Optiondata');
			$paperinfo=$Paperdata_db->where('paper_id='.$paper_id)->select();
			$paper_option=json_decode($paperinfo[0]['content']);
			//$paper_option=array_filter(explode(';', $paperinfo[0]['content']));
			$total=count($paper_option);
			if($total>0){
				//data：id-course-type-title数据数组
				/*foreach ($paper_option as $key=>$value){
					$optioninfo=$Optiondata_db->where('question_id='.$value)->select();
					$data[$key]['question_id'] = $optioninfo[0]['question_id'];
					$data[$key]['course'] = getDictName($optioninfo[0]['course_id'])[0]['type_name'];
					$data[$key]['type'] = getDictName($optioninfo[0]['type'])[0]['type_name'];
					$data[$key]['title'] = $optioninfo[0]['title'];
				}*/
				//echo "<table width='700px'>";
				echo "
				<script type=\"text/javascript\" >
					function openUrl(url, title){
						if($('#pagetabs').tabs('exists', title)){
							$('#pagetabs').tabs('select', title);
						}else{
							$('#pagetabs').tabs('add',{
								title: title,
								href: url,
								closable: true,
								cache: false
							});
						}
					}
					function RemoveOption(paperid,optionid){
						$.post('index.php?m=Admin&c=Paperdata&a=paperEdit',{
							paperid:paperid,
							optionid:optionid
						},function(data){
							var tab=$('#pagetabs').tabs('getSelected');
							$('#tt').tabs('update',{
								tab: tab,
							});
							tab.panel('refresh');
						});
					}
				</script>
				";
				foreach ($paper_option as $key=>$value){
					$optioninfo=$Optiondata_db->where('question_id='.$value)->select();
					$data['question_id'] = $optioninfo[0]['question_id'];
					$data['course'] = getDictName($optioninfo[0]['course_id'])[0]['type_name'];
					$data['typeid']=$optioninfo[0]['type'];
					$data['type'] = getDictName($optioninfo[0]['type'])[0]['type_name'];
					$data['title'] = $optioninfo[0]['title'];
					$data['keyword']=$optioninfo[0]['keyword'];
					$data['A']=$optioninfo[0]['a'];
					$data['B']=$optioninfo[0]['b'];
					$data['C']=$optioninfo[0]['c'];
					$data['D']=$optioninfo[0]['d'];
					$data['E']=$optioninfo[0]['e'];
					$data['F']=$optioninfo[0]['f'];
					$data['G']=$optioninfo[0]['g'];
					$data['H']=$optioninfo[0]['h'];
					$data['img']=$optioninfo[0]['img'];
					$data['ans']=$optioninfo[0]['answer'];
					$urlEdit="\"index.php?m=admin&c=Optiondata&a=Optionedit&id=".$data['question_id']."\" ";
					//<td><a href='javascript:void(0)'  onclick='openUrl(".$url.",".$data['question_id'].")'>修改</a></td>
					//<td><a href=".$url.">修改</td>
					echo"<table width='700px'>
					<tr>
						<td colspan='3'>第".($key+1)."题:</td>
						<td><a target='_blank' href=".$urlEdit.">修改</td>
						<td><a href='javascript:void(0)'  onclick='RemoveOption(".$paper_id.",".$data['question_id'].")'>删除</a></td>
					</tr>
					<tr>
						<td width'25%'>".$data['question_id']."</td>
						<td width='25%'>".$data['course']."</td>
						<td width='25%'>".$data['type']."</td>
						<td width='25%'>".$data['keyword']."</td>
					</tr>
					<tr>
						<td colspan='3'>标题:".$data['title']."</td>
					</tr>
					";
					if($data['A']){
						echo"
						<tr>
							<td colspan='3'>A:".$data['A']."</td>
						</tr>
						";
					}
					if($data['B']){
						echo"
						<tr>
							<td colspan='3'>B:".$data['B']."</td>
						</tr>
						";
					}
					if($data['C']){
						echo"
						<tr>
							<td colspan='3'>C:".$data['C']."</td>
						</tr>
						";
					}
					if($data['D']){
						echo"
						<tr>
							<td colspan='3'>D:".$data['D']."</td>
						</tr>
						";
					}
					if($data['E']){
						echo"
						<tr>
							<td colspan='3'>E:".$data['E']."</td>
						</tr>
						";
					}
					if($data['F']){
						echo"
						<tr>
							<td colspan='3'>F:".$data['F']."</td>
						</tr>
						";
					}
					if($data['G']){
						echo"
						<tr>
							<td colspan='3'>G:".$data['G']."</td>
						</tr>
						";
					}
					if($data['H']){
						echo"
						<tr>
							<td colspan='3'>H:".$data['H']."</td>
						</tr>
						";
					}
					$ans="";
					switch ($data['typeid']) {
						case 101:
						$ans=$ans.chr($data['ans']+64);
						break;
						case 102:
						$tempans=intval($data['ans']);
						for($i=7;$i>=0;$i--){
							if(intval($tempans/(1<<$i))){
								//var_dump($i);	
								$ans=chr($i+65).$ans;
								$tempans=$tempans-(1<<$i);
							}
						}
						break;
						case 103:
						$ans=$ans.chr($data['ans']+64);
						break;
						default:
								# code...
						break;
					}

					echo"
					<tr>
						<td colspan='3'>答案:".$ans."</td>
					</tr>
					";
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
		$result=$Paperdata_db->where('paper_id='.$id)->select();;
		echo $result;
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
			'paper_id'=>$_POST['paper_id'],
			'paper_name'=>$_POST['paper_name'],
			'course_id'=>$_POST['course_id'],
			'total_grade'=>0,
			'question_num'=>0,
			'test_time'=>$_POST['test_time'],
			'deadline'=>$_POST['deadline']
			);
		var_dump($data);
		$Paperdata_db->add($data);
	}
	//增加试题
	function paperAdd(){
		$Paperdata_db=D('Paperdata');
		$Optiondata_db=M('Optiondata');
		//opptionid为araay
		$optionid=$_POST['optionid'];
		$paperid=$_POST['paperid'];
		//需要修改的试卷信息和需要添加的题目信息
		$paperinfo=$Paperdata_db->where('paper_id='.$paperid)->select();
		//拆包
		$paper_option=json_decode($paperinfo[0]['content']);
			//$paper_option=explode(';', $paperinfo[0]['content']);
		$option_count=count($paper_option);
		if(($option_count==1)&&($paper_option[0]=="")){
			$option_count=0;
		}
		//添加该题目
		foreach ($optionid as $key => $value) {
			//var_dump($value);
			$optioninfo=$Optiondata_db->where('question_id='.$value)->select();
			$paper_option[$option_count+$key]=$optioninfo[0]['question_id'];
		}
		//组包
		$paperinfo['content']=json_encode($paper_option);
			//$paperinfo['content']=implode(';',$paper_option);
		$Paperdata_db->where('paper_id='.$paperid)->save($paperinfo);
		//echo json_encode($Paperdata_db->getlastsql());
	}
	//减少试题
	function paperEdit(){
		$Paperdata_db=D('Paperdata');
		$optionid=$_POST['optionid'];
		$paperid=$_POST['paperid'];
		//需要修改的试卷信息和需要添加的题目信息
		$paperinfo=$Paperdata_db->where('paper_id='.$paperid)->select()[0];
		//拆包
		$paper_option=json_decode($paperinfo['content']);
		//$paper_option=explode(';', $paperinfo['content']);
		foreach ($paper_option as $key => $value) {
			if($value==$optionid){
				unset($paper_option[$key]);
			}		
		}
		//组包,implode会产生多余分号
		//$paperinfo['content']=implode(';',$paper_option);
		/*$paperinfo['content']='';
		var_dump($paper_option);
		foreach ($paper_option as $key => $value) {
			var_dump($value);
			if($value){
				if($paperinfo['content']){
					$paperinfo['content']=$paperinfo['content'].';'.$paper_option[$key];
				}else{
					$paperinfo['content']=$paperinfo['content'].$paper_option[$key];
				}
			}
		}*/
		$paperinfo['content']=json_encode($paper_option);
		$Paperdata_db->where('paper_id='.$paperid)->save($paperinfo);
	}
	//删除试卷
	function paperRemove(){
		$paper_id=$_POST['paper_id'];
		$Paperdata_db=D('Paperdata');
		$Paperdata_db->where('paper_id='.$paper_id)->delete();
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
		//$PaperId=201001;
		$PaperId=$_POST['PaperId'];
		$Dictdata_db=D('Dictdata');
		$result=$Dictdata_db->where('belong_type=100')->select();
		$TypeCount=Array();
		foreach ($result as $key => $value) {
			$TypeCount[$value['type_id']]=0;
		}
		$Optiondata_db=D('Optiondata');
		$Paperdata_db=D('Paperdata');
		$paperinfo=$Paperdata_db->where('paper_id='.$PaperId)->select()[0];
		$paper_content=$paperinfo['content'];
		$paper_option=json_decode($paperinfo[0]['content']);
		//$array_content=explode(';', $paper_content);
		foreach ($array_content as $key => $value) {
			$optioninfo=$Optiondata_db->where('question_id='.$value)->select()[0];
			if($optioninfo){
				$TypeCount[$optioninfo['type']]++;
			}
		}
		echo json_encode($TypeCount);
		//var_dump($TypeCount);
	}
	function modifyPaperGrade(){
		$paperGrade_db=D('Papergrade');
		$paperId=$_POST['testId'];
		$typeId=$_POST['typeId'];
		$typeGrade=$_POST['typeGrade'];
		$data=array(
			'test_type_id'=>$PaperId.$typeId,
			'paper_id'=>$paperId,
			'question_type'=>$typeId,
			'grade'=>$grade
			);
		$paperGrade_db->setTypeInfo($paperId,$typeId,$typeGrade);

	}
}
?>