<?php
//$User = new UserModel();
// $itemid = mysql_real_escape_string($_REQUEST['itemid']);
$conn = @mysql_connect('127.0.0.1','root','pwd123456');
$res=mysql_query("SET NAMES 'UTF8'");//在连接数据库的时候需要加上这样三句话避免出现???的情况
$res=mysql_query("SET CHARACTER SET UTF8");
$res=mysql_query("SET CHARACTER_SET_RESULTS=UTF8'");
if (!$conn) {
	die('Could not connect: ' . mysql_error());
}
mysql_select_db('app', $conn);
$paperid= mysql_real_escape_string($_REQUEST['itemid']);
//var_dump($paperid);

$userArray=array();
$mysqlResult=mysql_query("select * from app2_student_info where finish_paper='$paperid'");
while ($row=mysql_fetch_array($mysqlResult,MYSQL_ASSOC)) {
	array_push($userArray, $row);
}
//var_dump($userArray);

if($userArray){
	echo '<table width="700"><tr><th>学号</th><th>姓名</th><th>操作</th>';

	foreach ($userArray as $key => $value) {
		$url='<a href="javascript:remove('.$paperid.','.$value['student_id'].')")>从该课程移除</a>';
		echo '<tr><td>'.$value['student_id'].'</td><td>'.$value['student_name'].'</td><td>'.$url.'</td></tr>';
	}
	echo '</tr></table>';
}
?>
<include file="Common:head"/>
<script type="text/javascript">
	function remove(paperid,studentid){
		$.post("index.php?m=admin&c=stuImport&a=removeStudent",{
			'studentid':studentid,
			'paperid':paperid
		},function(data){
			//
		});
	}
</script>


<?php
mysql_close($conn);
?>