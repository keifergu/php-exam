<?php if (!defined('THINK_PATH')) exit();?>

<table id="system_menulist_treegrid" class="easyui-treegrid" data-options='<?php $dataOptions = array_merge(array ( 'border' => false, 'fit' => true, 'fitColumns' => true, 'rownumbers' => true, 'singleSelect' => true, 'animate' => true, ), $treegrid["options"]);if(isset($dataOptions['toolbar']) && substr($dataOptions['toolbar'],0,1) != '#'): unset($dataOptions['toolbar']); endif; echo trim(json_encode($dataOptions), '{}[]').((isset($treegrid["options"]['toolbar']) && substr($treegrid["options"]['toolbar'],0,1) != '#')?',"toolbar":'.$treegrid["options"]['toolbar']:null); ?>' style=""><thead><tr><?php if(is_array($treegrid["fields"])):foreach ($treegrid["fields"] as $key=>$arr):if(isset($arr['formatter'])):unset($arr['formatter']);endif;echo "<th data-options='".trim(json_encode($arr), '{}[]').(isset($treegrid["fields"][$key]['formatter'])?",\"formatter\":".$treegrid["fields"][$key]['formatter']:null)."'>".$key."</th>";endforeach;endif; ?></tr></thead></table>

<!-- 添加菜单 -->
<div id="system_menu_add_dialog" class="easyui-dialog" title="添加菜单" data-options="modal:true,closed:true,iconCls:'icons-application-application_add',buttons:[{text:'确定',iconCls:'icons-other-tick',handler:function(){$('#system_menu_add_dialog_form').submit();}},{text:'取消',iconCls:'icons-arrow-cross',handler:function(){$('#system_menu_add_dialog').dialog('close');}}]" style="width:500px;height:270px;"></div>

<!-- 编辑菜单 -->
<div id="system_menu_edit_dialog" class="easyui-dialog" title="编辑菜单" data-options="modal:true,closed:true,iconCls:'icons-application-application_edit',buttons:[{text:'确定',iconCls:'icons-other-tick',handler:function(){$('#system_menu_edit_dialog_form').submit();}},{text:'取消',iconCls:'icons-arrow-cross',handler:function(){$('#system_menu_edit_dialog').dialog('close');}}]" style="width:500px;height:270px;"></div>

<script type="text/javascript">
//工具栏
var system_menulist_treegrid_toolbar = [
	{ text: '添加菜单', iconCls: 'icons-arrow-add', handler: systemMenuAdd },
	{ text: '刷新', iconCls: 'icons-arrow-arrow_refresh', handler: systemMenuRefresh },
	{ text: '排序', iconCls: 'icons-arrow-arrow_down', handler: systemMenuOrder }
];

//排序格式化
function systemMenuOrderFormatter(val, arr){
	return '<input class="system_menu_order_input" type="text" name="order['+arr['id']+']" value="'+ val +'" size="2" style="text-align:center">';
}
//操作格式化
function systemMenuOperateFormatter(id, arr){
	var btn = [];
	if(arr.is_system == 1){
		btn.push('<a href="javascript:;" onclick="systemMenuAdd('+id+')">添加子菜单</a>');
		btn.push('修改');
		btn.push('删除');
	}else{
		btn.push('<a href="javascript:;" onclick="systemMenuAdd('+id+')">添加子菜单</a>');
		btn.push('<a href="javascript:;" onclick="systemMenuEdit('+id+')">修改</a>');
		btn.push('<a href="javascript:;" onclick="systemMenuDelete('+id+')">删除</a>');
	}
	return btn.join(' | ');
}
//刷新
function systemMenuRefresh(){
	$('#system_menulist_treegrid').treegrid('reload');
}
//添加
function systemMenuAdd(parentid){
	if(typeof(parentid) !== 'number') parentid = 0;
	var url = '<?php echo U('System/menuAdd');?>';
	url += url.indexOf('?') != -1 ? '&parentid='+parentid : '?parentid='+parentid;
	var id = 'system_menu_add_dialog';
	$('#'+id).dialog({href:url});
	$('#'+id).dialog('open');
}
//编辑
function systemMenuEdit(id){
	if(typeof(id) !== 'number'){
		$.messager.alert('提示信息', '未选择菜单', 'error');
		return false;
	}
	var url = '<?php echo U('System/menuEdit');?>';
	url += url.indexOf('?') != -1 ? '&id='+id : '?id='+id;
	var id = 'system_menu_edit_dialog';
	$('#'+id).dialog({href:url});
	$('#'+id).dialog('open');
}
//删除
function systemMenuDelete(id){
	if(typeof(id) !== 'number'){
		$.messager.alert('提示信息', '未选择菜单', 'error');
		return false;
	}
	$.messager.confirm('提示信息', '确定要删除吗？', function(result){
		if(!result) return false;
		$.post('<?php echo U('System/menuDelete');?>', {id: id}, function(res){
			if(!res.status){
				$.messager.alert('提示信息', res.info, 'error');
			}else{
				$.messager.alert('提示信息', res.info, 'info');
				systemMenuRefresh();
			}
		}, 'json');
	});
}
//排序
function systemMenuOrder(){
	$.post('<?php echo U('System/menuOrder');?>', $('.system_menu_order_input').serialize(), function(res){
		if(!res.status){
			$.messager.alert('提示信息', res.info, 'error');
		}else{
			$.messager.alert('提示信息', res.info, 'info');
			systemMenuRefresh();
		}
	}, 'json');
}
</script>