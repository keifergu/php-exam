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
$rs = mysql_query("select * from app2_optiondata where question_id='$itemid'");
$item = mysql_fetch_array($rs);
$item=array_filter($item);

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
		<td><?php echo $item['a'];?></td>
		<td class="dv-label">B:</td>
		<td><?php echo $item['b'];?></td>
	</tr>
	<tr>
		<td class="dv-label">C: </td>
		<td><?php 
			echo $item['c'];
			?>
		</td>
		<td class="dv-label">D:</td>
		<td><?php echo $item['d'];?></td>
	</tr>
	<tr>
		<td><?php 
			if (!empty($item['e']))
			{
				echo '<td class="dv-label">E:</td>';
				echo '<td>'.$item['e'].'</td>';
			}
			?>
		</td>
		<td><?php 
			if (!empty($item['f']))
			{
				echo '<td class="dv-label">F:</td>';
				echo $item['f'];
			}
			?>
		</td>
	</tr>
	<tr>
		<td><?php 
			if (!empty($item['g']))
			{
				echo '<td class="dv-label">G:</td>';
				echo $item['g'];
			}
			?>
		</td>
		
		<td><?php 
			if (!empty($item['h']))
			{
				echo '<td class="dv-label">H:</td>';
				echo $item['h'];
			}
			?>
		</td>
	</tr>
	<tr>
		<?php 
		$ans="";
		switch ($item['type']) {
			case 101:
			$ans=$ans.chr($item['answer']+64);
			break;
			case 102:
			$tempans=intval($item['answer']);
			for($i=7;$i>=0;$i--){
				if(intval($tempans/(1<<$i))){
								//var_dump($i);	
					$ans=chr($i+65).$ans;
					$tempans=$tempans-(1<<$i);
				}
			}
			break;
			case 103:
			$ans=$ans.chr($item['answer']+64);
			break;
			default:
								# code...
			break;
		}

		echo"
		<tr>
			<td class='dv-label'>答案:</td>
			<td>".$ans."</td>
		</tr>
		";
		?>
	</tr>
</table>
<?php
mysql_close($conn);
?>