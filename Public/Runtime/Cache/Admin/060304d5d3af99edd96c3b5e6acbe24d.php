<?php if (!defined('THINK_PATH')) exit();?><div class="easyui-panel" data-options="fit:true,title:'<?php echo ($currentpos); ?>',border:false">
	<div class="easyui-layout" data-options="fit:true">
		<div data-options="region:'east',split:true,border:false,href:'<?php echo U('Content/public_right');?>'" style="width:200px;border-left:1px solid #ddd"></div>
		<div id="content_index_center_panel" data-options="region:'center',border:false,href:'<?php echo U('Content/public_welcome');?>'" style="border-right:1px solid #ddd"></div>
	</div>
</div>
<script type="text/javascript">
//中间内容区域打开
function contentMainOpenUrl(url, iframe){
	if(!url){
		$.messager.alert('提示信息', '链接地址不存在', 'error');
		return false;
	}
	var data = iframe ? {href: null, content: '<iframe src="'+ url +'" frameborder="0" fit="true" border="none" width="100%" height="100%"></iframe>'} : {href:url};
	$('#content_index_center_panel').panel(data);
}
</script>