<?php
//$User = new UserModel();
// $itemid = mysql_real_escape_string($_REQUEST['itemid']);
$conn = @mysql_connect('127.0.0.1','root','');
$res=mysql_query("SET NAMES 'UTF8'");//在连接数据库的时候需要加上这样三句话避免出现???的情况
$res=mysql_query("SET CHARACTER SET UTF8");
$res=mysql_query("SET CHARACTER_SET_RESULTS=UTF8'");
if (!$conn) {
	die('Could not connect: ' . mysql_error());
}
mysql_select_db('app', $conn);
$itemid = mysql_real_escape_string($_REQUEST['itemid']);
$rs = mysql_query("select * from app2_optiondata where id='$itemid'");
$item = mysql_fetch_array($rs);
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
<table class="dv-table" border="0" style="width:100%;">
	<tr>
		<td class="dv-label">title</td>
		<td colspan="5"><?php echo $item['title'];?></td>
	</tr>
	<tr>
		<td class="dv-label">A: </td>
		<td><?php echo $item['A'];?></td>
		<td class="dv-label">B:</td>
		<td><?php echo $item['B'];?></td>
	</tr>
	<tr>
		<td class="dv-label">C: </td>
		<td><?php 
		    echo $item['C'];
		    ?></td>
		<td class="dv-label">D:</td>
		<td><?php echo $item['D'];?></td>
	</tr>
	<tr>
		<td class="dv-label">E: </td>
		<td><?php 
        if (!empty($item['E']))
		{
		    echo $item['E'];
		}
		?></td>
		<td class="dv-label">F:</td>
		<td><?php 
        if (!empty($item['F']))
		{
		    echo $item['F'];
		}
		?></td>
	</tr>
	<tr>
		<td class="dv-label">ans</td>
		<td ><?php echo chr(64+$item['answer']);?></td>
	</tr>
</table>