<?php if (!defined('THINK_PATH')) exit();?>

<table id="category_categorylist_treegrid" class="easyui-treegrid" data-options='<?php $dataOptions = array_merge(array ( 'border' => false, 'fit' => true, 'fitColumns' => true, 'rownumbers' => true, 'singleSelect' => true, 'animate' => true, ), $treegrid["options"]);if(isset($dataOptions['toolbar']) && substr($dataOptions['toolbar'],0,1) != '#'): unset($dataOptions['toolbar']); endif; echo trim(json_encode($dataOptions), '{}[]').((isset($treegrid["options"]['toolbar']) && substr($treegrid["options"]['toolbar'],0,1) != '#')?',"toolbar":'.$treegrid["options"]['toolbar']:null); ?>' style=""><thead><tr><?php if(is_array($treegrid["fields"])):foreach ($treegrid["fields"] as $key=>$arr):if(isset($arr['formatter'])):unset($arr['formatter']);endif;echo "<th data-options='".trim(json_encode($arr), '{}[]').(isset($treegrid["fields"][$key]['formatter'])?",\"formatter\":".$treegrid["fields"][$key]['formatter']:null)."'>".$key."</th>";endforeach;endif; ?></tr></thead></table>

<!-- 添加栏目 -->
<div id="category_category_add_dialog" class="easyui-dialog" title="添加栏目" data-options="modal:true,closed:true,iconCls:'icons-application-application_add',buttons:[{text:'确定',iconCls:'icons-other-tick',handler:function(){$('#category_category_add_dialog_form').submit();}},{text:'取消',iconCls:'icons-arrow-cross',handler:function(){$('#category_category_add_dialog').dialog('close');}}]" style="width:500px;height:300px;"></div>

<!-- 编辑栏目 -->
<div id="category_category_edit_dialog" class="easyui-dialog" title="编辑栏目" data-options="modal:true,closed:true,iconCls:'icons-application-application_edit',buttons:[{text:'确定',iconCls:'icons-other-tick',handler:function(){$('#category_category_edit_dialog_form').submit();}},{text:'取消',iconCls:'icons-arrow-cross',handler:function(){$('#category_category_edit_dialog').dialog('close');}}]" style="width:500px;height:300px;"></div>

<script type="text/javascript">
var category_categorylist_treegrid_toolbar = [
	{ text: '添加栏目', iconCls: 'icons-arrow-add', handler: categoryCategoryListAdd },
	{ text: '刷新', iconCls: 'icons-arrow-arrow_refresh', handler: categoryCategoryListRefresh },
	{ text: '排序', iconCls: 'icons-arrow-arrow_down', handler: categoryCategoryListOrder }
];
//排序格式化
function categoryCategoryListOrderFormatter(val, arr){
	return '<input class="category_categorylist_order_input" type="text" name="order['+arr['catid']+']" value="'+ val +'" size="2" style="text-align:center">';
}
//类型格式化
function categoryCategoryListTypeFormatter(key){
	var list = <?php echo json_encode(C('CONTENT_CATEGORY_TYPE'));?>;
	return list[key] || null;
}
//状态格式化
function categoryCategoryListStateFormatter(val){
	return val == '1' ? '启用' : '<font color="red">禁用</font>';
}
//操作格式化
function categoryCategoryListOperateFormatter(id){
	var btn = [];
	btn.push('<a href="javascript:;" onclick="categoryCategoryListAdd('+id+')">添加子栏目</a>');
	btn.push('<a href="javascript:;" onclick="categoryCategoryListEdit('+id+')">修改</a>');
	btn.push('<a href="javascript:;" onclick="categoryCategoryListDelete('+id+')">删除</a>');
	return btn.join(' | ');
}

//刷新
function categoryCategoryListRefresh(){
	$('#category_categorylist_treegrid').treegrid('reload');
}
//添加
function categoryCategoryListAdd(parentid){
	if(typeof(parentid) !== 'number') parentid = 0;
	var url = '<?php echo U('Category/categoryAdd');?>';
	url += url.indexOf('?') != -1 ? '&parentid='+parentid : '?parentid='+parentid;
	$('#category_category_add_dialog').dialog({href:url});
	$('#category_category_add_dialog').dialog('open');
}
//编辑
function categoryCategoryListEdit(id){
	if(typeof(id) !== 'number'){
		$.messager.alert('提示信息', '未选择栏目', 'error');
		return false;
	}
	var url = '<?php echo U('Category/categoryEdit');?>';
	url += url.indexOf('?') != -1 ? '&id='+id : '?id='+id;
	$('#category_category_edit_dialog').dialog({href:url});
	$('#category_category_edit_dialog').dialog('open');
}
//删除
function categoryCategoryListDelete(id){
	if(typeof(id) !== 'number'){
		$.messager.alert('提示信息', '未选择菜单', 'error');
		return false;
	}
	$.messager.confirm('提示信息', '确定要删除吗？', function(result){
		if(!result) return false;
		$.post('<?php echo U('Category/categoryDelete');?>', {id: id}, function(res){
			if(!res.status){
				$.messager.alert('提示信息', res.info, 'error');
			}else{
				$.messager.alert('提示信息', res.info, 'info');
				categoryCategoryListRefresh();
			}
		}, 'json');
	});
}
//排序
function categoryCategoryListOrder(){
	$.post('<?php echo U('Category/categoryOrder');?>', $('.category_categorylist_order_input').serialize(), function(res){
		if(!res.status){
			$.messager.alert('提示信息', res.info, 'error');
		}else{
			$.messager.alert('提示信息', res.info, 'info');
			categoryCategoryListRefresh();
		}
	}, 'json');
}
</script>