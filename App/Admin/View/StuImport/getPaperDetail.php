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
$itemid = mysql_real_escape_string($_REQUEST['itemid']);
var_dump($itemid);
$rs = mysql_query("select * from app2_optiondata where question_id='$itemid'");
$item = mysql_fetch_array($rs);

?>
<?php
mysql_close($conn);
?>