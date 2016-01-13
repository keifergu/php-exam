<?php if (!defined('THINK_PATH')) exit();?>

<table id="admin_rolelist_datagrid" class="easyui-datagrid" data-options='<?php $dataOptions = array_merge(array ( 'border' => false, 'fit' => true, 'fitColumns' => true, 'rownumbers' => true, 'singleSelect' => true, 'pagination' => true, 'pageList' => array ( 0 => 20, 1 => 30, 2 => 50, 3 => 80, 4 => 100, ), 'pageSize' => '20', ), $datagrid["options"]);if(isset($dataOptions['toolbar']) && substr($dataOptions['toolbar'],0,1) != '#'): unset($dataOptions['toolbar']); endif; echo trim(json_encode($dataOptions), '{}[]').((isset($datagrid["options"]['toolbar']) && substr($datagrid["options"]['toolbar'],0,1) != '#')?',"toolbar":'.$datagrid["options"]['toolbar']:null); ?>' style=""><thead><tr><?php if(is_array($datagrid["fields"])):foreach ($datagrid["fields"] as $key=>$arr):if(isset($arr['formatter'])):unset($arr['formatter']);endif;echo "<th data-options='".trim(json_encode($arr), '{}[]').(isset($datagrid["fields"][$key]['formatter'])?",\"formatter\":".$datagrid["fields"][$key]['formatter']:null)."'>".$key."</th>";endforeach;endif; ?></tr></thead></table>

<!-- 权限设置 -->
<div id="admin_rolelist_permission_dialog" class="easyui-dialog" title="权限设置" data-options="modal:true,closed:true,iconCls:'icons-lock-lock_edit',buttons:[{text:'确定',iconCls:'icons-other-tick',handler:function(){$('#admin_rolelist_permission_dialog_form').submit();}},{text:'取消',iconCls:'icons-arrow-cross',handler:function(){$('#admin_rolelist_permission_dialog').dialog('close');}}]" style="width:400px;height:300px;"></div>

<!-- 添加角色 -->
<div id="admin_rolelist_add_dialog" class="easyui-dialog" title="添加角色" data-options="modal:true,closed:true,iconCls:'icons-application-application_add',buttons:[{text:'确定',iconCls:'icons-other-tick',handler:function(){$('#admin_rolelist_add_dialog_form').submit();}},{text:'取消',iconCls:'icons-arrow-cross',handler:function(){$('#admin_rolelist_add_dialog').dialog('close');}}]" style="width:480px;height:300px;"></div>

<!-- 编辑角色 -->
<div id="admin_rolelist_edit_dialog" class="easyui-dialog" title="编辑角色" data-options="modal:true,closed:true,iconCls:'icons-application-application_edit',buttons:[{text:'确定',iconCls:'icons-other-tick',handler:function(){$('#admin_rolelist_edit_dialog_form').submit();}},{text:'取消',iconCls:'icons-arrow-cross',handler:function(){$('#admin_rolelist_edit_dialog').dialog('close');}}]" style="width:480px;height:300px;"></div>

<script type="text/javascript">
var admin_rolelist_datagrid_toolbar = [
	{ text: '添加角色', iconCls: 'icons-arrow-add', handler: adminRoleListAdd },
	{ text: '刷新', iconCls: 'icons-arrow-arrow_refresh', handler: adminRoleListRefresh },
	{ text: '排序', iconCls: 'icons-arrow-arrow_down', handler: adminRoleListOrder }
];
//排序格式化
function adminRoleListOrderFormatter(val, arr){
	return '<input class="admin_rolelist_order_input" type="text" name="order['+arr['roleid']+']" value="'+val+'" size="2" style="text-align:center" />';
}
//状态格式化
function adminRoleListStateFormatter(val){
	return val == 1 ? '<font color="red">未启用</font>' : '已启用';
}
//操作格式化
function adminRoleListOperateFormatter(val){
	var btn = [];
	if(val == 1){
		btn.push('权限设置');
		btn.push('编辑');
		btn.push('删除');
	}else{
		btn.push('<a href="javascript:void(0);" onclick="adminRoleListPermission('+val+')">权限设置</a>');
		btn.push('<a href="javascript:void(0);" onclick="adminRoleListEdit('+val+')">编辑</a>');
		btn.push('<a href="javascript:void(0);" onclick="adminRoleListDelete('+val+')">删除</a>');
	}
	return btn.join(' | ');
}
//刷新
function adminRoleListRefresh(){
	$('#admin_rolelist_datagrid').datagrid('reload');
}
//添加
function adminRoleListAdd(){
	$('#admin_rolelist_add_dialog').dialog({href:'<?php echo U('Admin/roleAdd');?>'});
	$('#admin_rolelist_add_dialog').dialog('open');
}
//编辑
function adminRoleListEdit(id){
	if(typeof(id) !== 'number'){
		$.messager.alert('提示信息', '未选择角色', 'error');
		return false;
	}
	var url = '<?php echo U('Admin/roleEdit');?>';
	url += url.indexOf('?') != -1 ? '&id='+id : '?id='+id;
	$('#admin_rolelist_edit_dialog').dialog({href:url});
	$('#admin_rolelist_edit_dialog').dialog('open');
}
//删除
function adminRoleListDelete(id){
	if(typeof(id) !== 'number'){
		$.messager.alert('提示信息', '未选择角色', 'error');
		return false;
	}
	$.messager.confirm('提示信息', '确定要删除吗？', function(result){
		if(!result) return false;
		$.post('<?php echo U('admin/roleDelete');?>', {id: id}, function(res){
			if(!res.status){
				$.messager.alert('提示信息', res.info, 'error');
			}else{
				$.messager.alert('提示信息', res.info, 'info');
				adminRoleListRefresh();
			}
		}, 'json');
	});
}
//排序
function adminRoleListOrder(){
	$.post('<?php echo U('Admin/roleOrder');?>', $('.admin_rolelist_order_input').serialize(), function(res){
		if(!res.status){
			$.messager.alert('提示信息', res.info, 'error');
		}else{
			$.messager.alert('提示信息', res.info, 'info');
			adminRoleListRefresh();
		}
	}, 'json');
}
//角色权限
function adminRoleListPermission(id){
	if(typeof(id) !== 'number'){
		$.messager.alert('提示信息', '未选择角色', 'error');
		return false;
	}
	var url = '<?php echo U('Admin/rolePermission');?>';
	url += url.indexOf('?') != -1 ? '&id='+id : '?id='+id;
	$('#admin_rolelist_permission_dialog').dialog({href: url});
	$('#admin_rolelist_permission_dialog').dialog('open');
}
</script>