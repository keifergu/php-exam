<?php
//$User = new UserModel();
// $itemid = mysql_real_escape_string($_REQUEST['itemid']);
$conn = @mysql_connect('127.0.0.1','root','');
$res1=mysql_query("SET NAMES 'UTF8'");//在连接数据库的时候需要加上这样三句话避免出现???的情况
$res1=mysql_query("SET CHARACTER SET UTF8");
$res1=mysql_query("SET CHARACTER_SET_RESULTS=UTF8'");
if (!$conn) {
	die('Could not connect: ' . mysql_error());
}
mysql_select_db('app', $conn);
//得到试卷id
$itemid = mysql_real_escape_string($_REQUEST['itemid']);

?>
<style type="text/css">
	.dv-table td{
		border:0;
	}
	.dv-label{
		font-weight:bold;
		color:#15428B;
		width:100px;
	}
</style>
<include file="Common:head"/>

<script type="text/javascript">
	$(function(){
		autoSumTotal(<?php echo $itemid;?>)
	})
	function modify(typeId,testId){
		//alert(typeId);
		//alert(testId);
		$.post("index.php/admin/Paperdata/modifyPaperGrade",{
			testId:testId,
			typeId:typeId,
			typeGrade:$('#input-grade'+testId+typeId).val()
		},function(data){
			var totalGrade=$('#lable-number'+testId+typeId).text()*data;
			$('#lable-grade'+testId+typeId).text(totalGrade);
			autoSumTotal(testId);
		}
		);
	}
	function autoSumTotal(testId){
		var ans=0;
		//alert(testId);
		$(".gradeOnly"+testId).each(function(){
			ans=ans+parseInt($(this).text());
			//alert(parseInt($(this).text()));
		});
		$('#lable-totalGrade'+testId).text(ans);
	}
</script>
<table class="dv-table" border="0" style="width:100%;">
	<tr>
		<th>题型</th>
		<th>数量</th>
		<th>分值</th>
		<th>总分</th>
		<th></th>
	</tr>
	<?php 
	$optionTypeArray=Array();
	$mysqlResult=mysql_query("select * from app2_data_dict  where belong_type=100");
	while ($row=mysql_fetch_array($mysqlResult,MYSQL_ASSOC)) {
		array_push($optionTypeArray, $row);
	}
	foreach ($optionTypeArray as $key => $value) {
		$optionTypeArray[$key]['number']=0;
	}
	//var_dump($optionTypeArray);
	$paperInfo=	mysql_fetch_array( mysql_query("select * from app2_paper_content  where paper_id=".$itemid));
	//var_dump($paperInfo);
	$paperContent=$paperInfo['content'];
	//$paperContentArray=array_filter( explode(';', $paperContent));
	$paperContentArray=json_decode($paperContent);
	if($paperContentArray){
		foreach ($paperContentArray as $key => $value) {
			$optionInfo=mysql_fetch_array( mysql_query("select * from app2_optiondata  where question_id=".$value));
			foreach ($optionTypeArray as $key1 => $value1) {
				if($optionTypeArray[$key1]['type_id']==$optionInfo['type']){
					$optionTypeArray[$key1]['number']++;
				}
			}
		}
	}

	//var_dump($optionTypeArray);
	/*
	用group无法确定是否有数据
	$paperGradeArray=Array();
	$mysqlResult=mysql_query("select * from app2_paper_grade group by paper_id having paper_id=".$itemid);
	while ($row=mysql_fetch_array($mysqlResult,MYSQL_ASSOC)) {
		array_push($paperGradeArray, $row);
	}
	var_dump($paperGradeArray);
	*/
	foreach ($optionTypeArray as $key => $value) {
		if($optionTypeArray[$key]['number']){
			$paperGrade=mysql_fetch_array( mysql_query("select * from app2_paper_grade  where test_type_id=".$itemid.$optionTypeArray[$key]['type_id']));
			if(!$paperGrade){
				mysql_query("insert into app2_paper_grade(test_type_id,paper_id,question_type,grade) values('".$itemid.$optionTypeArray[$key]['type_id']."','".$itemid."','".$optionTypeArray[$key]['type_id']."','
					0')");
				$paperGrade=mysql_fetch_array( mysql_query("select * from app2_paper_grade  where test_type_id=".$itemid.$optionTypeArray[$key]['type_id']));
			}
			//var_dump($optionTypeArray);
			echo "<tr>
			<td>".$optionTypeArray[$key]['type_name']."</td>
			<td>
				<lable id='lable-number".$itemid.$optionTypeArray[$key]['type_id']."'>".$optionTypeArray[$key]['number']."</lable>
			</td>
			<td>
				<input type='number' id='input-grade".$itemid.$optionTypeArray[$key]['type_id']."' value=".$paperGrade['grade']." onblur='modify(".$optionTypeArray[$key]['type_id'].",".$itemid.")'>
			</td>
			<td>
				<lable id='lable-grade".$itemid.$optionTypeArray[$key]['type_id']."' class='gradeOnly".$itemid."'>".$paperGrade['grade']*$optionTypeArray[$key]['number']."</lable>
			</td>
			";
		}
	}

	?>
</table>
<?php 
echo "试卷总分：";
echo "<lable id='lable-totalGrade".$itemid."'></lable>"
?>
<?php 
mysql_close($conn);
?>