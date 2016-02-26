<?php if (!defined('THINK_PATH')) exit();?>

<table id="admin_memberlist_datagrid" class="easyui-datagrid" data-options='<?php $dataOptions = array_merge(array ( 'border' => false, 'fit' => true, 'fitColumns' => true, 'rownumbers' => true, 'singleSelect' => true, 'pagination' => true, 'pageList' => array ( 0 => 20, 1 => 30, 2 => 50, 3 => 80, 4 => 100, ), 'pageSize' => '20', ), $datagrid["options"]);if(isset($dataOptions['toolbar']) && substr($dataOptions['toolbar'],0,1) != '#'): unset($dataOptions['toolbar']); endif; echo trim(json_encode($dataOptions), '{}[]').((isset($datagrid["options"]['toolbar']) && substr($datagrid["options"]['toolbar'],0,1) != '#')?',"toolbar":'.$datagrid["options"]['toolbar']:null); ?>' style=""><thead><tr><?php if(is_array($datagrid["fields"])):foreach ($datagrid["fields"] as $key=>$arr):if(isset($arr['formatter'])):unset($arr['formatter']);endif;echo "<th data-options='".trim(json_encode($arr), '{}[]').(isset($datagrid["fields"][$key]['formatter'])?",\"formatter\":".$datagrid["fields"][$key]['formatter']:null)."'>".$key."</th>";endforeach;endif; ?></tr></thead></table>

<!-- 添加管理员 -->
<div id="admin_memberlist_add_dialog" class="easyui-dialog" title="添加管理员" data-options="modal:true,closed:true,iconCls:'icons-application-application_add',buttons:[{text:'确定',iconCls:'icons-other-tick',handler:function(){$('#admin_memberlist_add_dialog_form').submit();}},{text:'取消',iconCls:'icons-arrow-cross',handler:function(){$('#admin_memberlist_add_dialog').dialog('close');}}]" style="width:480px;height:300px;"></div>

<!-- 编辑管理员 -->
<div id="admin_memberlist_edit_dialog" class="easyui-dialog" title="编辑管理员" data-options="modal:true,closed:true,iconCls:'icons-application-application_edit',buttons:[{text:'确定',iconCls:'icons-other-tick',handler:function(){$('#admin_memberlist_edit_dialog_form').submit();}},{text:'取消',iconCls:'icons-arrow-cross',handler:function(){$('#admin_memberlist_edit_dialog').dialog('close');}}]" style="width:480px;height:300px;"></div>

<script type="text/javascript">
var admin_memberlist_datagrid_toolbar = [
	{ text: '添加管理员', iconCls: 'icons-arrow-add', handler: adminMemberAdd },
	{ text: '刷新', iconCls: 'icons-arrow-arrow_refresh', handler: adminMemberRefresh }
];
//时间格式化
function adminMemberListTimeFormatter(val){
	return val != '1970-01-01 08:00:00' ? val : '';
}
//操作格式化
function adminMemberListOperateFormatter(val){
	var btn = [];
	if(val == 1){
		btn.push('编辑')
		btn.push('删除')
	}else{
		btn.push('<a href="javascript:;" onclick="adminMemberEdit('+val+')">编辑</a>');
		btn.push('<a href="javascript:;" onclick="adminMemberDelete('+val+')">删除</a>');
	}
	return btn.join(' | ');
}

//刷新
function adminMemberRefresh(){
	$('#admin_memberlist_datagrid').datagrid('reload');
}
//添加
function adminMemberAdd(){
	$('#admin_memberlist_add_dialog').dialog({href:'<?php echo U('Admin/memberAdd');?>'});
	$('#admin_memberlist_add_dialog').dialog('open');
}
//编辑
function adminMemberEdit(id){
	if(typeof(id) !== 'number'){
		$.messager.alert('提示信息', '未选择管理员', 'error');
		return false;
	}
	var url = '<?php echo U('Admin/memberEdit');?>';
	url += url.indexOf('?') != -1 ? '&id='+id : '?id='+id;
	$('#admin_memberlist_edit_dialog').dialog({href:url});
	$('#admin_memberlist_edit_dialog').dialog('open');
}
//删除
function adminMemberDelete(id){
	if(typeof(id) !== 'number'){
		$.messager.alert('提示信息', '未选择管理员', 'error');
		return false;
	}
	$.messager.confirm('提示信息', '确定要删除吗？', function(result){
		if(!result) return false;
		$.post('<?php echo U('Admin/memberDelete');?>', {id: id}, function(res){
			if(!res.status){
				$.messager.alert('提示信息', res.info, 'error');
			}else{
				$.messager.alert('提示信息', res.info, 'info');
				adminMemberRefresh();
			}
		}, 'json');
	});
}
</script>