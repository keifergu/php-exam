<?php if (!defined('THINK_PATH')) exit();?>
<table id="system_loglist_datagrid" class="easyui-datagrid" data-options='<?php $dataOptions = array_merge(array ( 'border' => false, 'fit' => true, 'fitColumns' => true, 'rownumbers' => true, 'singleSelect' => true, 'pagination' => true, 'pageList' => array ( 0 => 20, 1 => 30, 2 => 50, 3 => 80, 4 => 100, ), 'pageSize' => '20', ), $datagrid["options"]);if(isset($dataOptions['toolbar']) && substr($dataOptions['toolbar'],0,1) != '#'): unset($dataOptions['toolbar']); endif; echo trim(json_encode($dataOptions), '{}[]').((isset($datagrid["options"]['toolbar']) && substr($datagrid["options"]['toolbar'],0,1) != '#')?',"toolbar":'.$datagrid["options"]['toolbar']:null); ?>' style=""><thead><tr><?php if(is_array($datagrid["fields"])):foreach ($datagrid["fields"] as $key=>$arr):if(isset($arr['formatter'])):unset($arr['formatter']);endif;echo "<th data-options='".trim(json_encode($arr), '{}[]').(isset($datagrid["fields"][$key]['formatter'])?",\"formatter\":".$datagrid["fields"][$key]['formatter']:null)."'>".$key."</th>";endforeach;endif; ?></tr></thead></table>

<div id="system_loglist_datagrid_toolbar" style="padding:5px;height:auto">
	<form>
		用户名: 
		<select name="search[username]" class="easyui-combobox" panelHeight="auto" style="width:100px">
			<option value="">全部用户</option>
			<?php if(is_array($list["admin"])): foreach($list["admin"] as $key=>$username): ?><option value="<?php echo ($username); ?>"><?php echo ($username); ?></option><?php endforeach; endif; ?>
		</select>
		模块: 
		<select name="search[controller]" class="easyui-combobox" panelHeight="auto" style="width:100px">
			<option value="">所有模块</option>
			<?php if(is_array($list["module"])): foreach($list["module"] as $key=>$module): ?><option value="<?php echo ($module); ?>"><?php echo ($module); ?></option><?php endforeach; endif; ?>
		</select>
		时 间: <input name="search[begin]" class="easyui-datebox" style="width:100px">
		至: <input name="search[end]" class="easyui-datebox" style="width:100px">
		
		<a href="javascript:;" onclick="systemLogSearch(this);" class="easyui-linkbutton" iconCls="icons-map-magnifier">搜索</a>
		<a href="javascript:;" onclick="systemLogDelete();" class="easyui-linkbutton" iconCls="icons-other-delete">删除一月前数据</a>
	</form>
</div>

<!-- 查看详细信息 -->
<div id="system_loglist_detail_dialog" class="easyui-dialog word-wrap" title="详细参数" data-options="modal:true,closed:true,iconCls:'icons-other-information',buttons:[{text:'关闭',iconCls:'icons-arrow-cross',handler:function(){$('#system_loglist_detail_dialog').dialog('close');}}]" style="width:400px;height:260px;padding:5px"></div>

<script type="text/javascript">
var system_loglist_datagrid_id = 'system_loglist_datagrid';
//搜索
function systemLogSearch(that){
	var queryParams = $('#'+system_loglist_datagrid_id).datagrid('options').queryParams;
	$.each($(that).parent('form').serializeArray(), function() {
		queryParams[this['name']] = this['value'];
	});
	$('#'+system_loglist_datagrid_id).datagrid('reload');
}
//删除日志
function systemLogDelete(){
	$.post('<?php echo U('System/logDelete');?>', {week: 4}, function(res){
		if(!res.status){
			$.messager.alert('提示信息', res.info, 'error');
		}else{
			$('#'+system_loglist_datagrid_id).datagrid('reload');
			$.messager.alert('提示信息', res.info, 'info');
		}
	}, 'json');
}
//参数格式化
function systemLogViewFormatter(val){
	return '<a href="javascript:;" onclick="systemLogDetailDialog(this);">'+val+'</a>';
}
//查看详细信息
function systemLogDetailDialog(that){
	var id = 'system_loglist_detail_dialog';
	$('#'+id).dialog({content: $(that).html()});
	$('#'+id).dialog('open');
}
</script>