<?php if (!defined('THINK_PATH')) exit();?>
<table id="setting_site_propertygrid" class="easyui-propertygrid" data-options='<?php $dataOptions = array_merge(array ( 'border' => false, 'fit' => true, 'showHeader' => true, 'columns' => array ( 0 => array ( 0 => array ( 'field' => 'name', 'title' => '属性名称', 'width' => 80, 'sortable' => true, ), 1 => array ( 'field' => 'value', 'title' => '属性值', 'width' => 200, ), ), ), 'showGroup' => true, 'scrollbarSize' => 0, ), $propertygrid["options"]);if(isset($dataOptions['toolbar']) && substr($dataOptions['toolbar'],0,1) != '#'): unset($dataOptions['toolbar']); endif; echo trim(json_encode($dataOptions), '{}[]').((isset($propertygrid["options"]['toolbar']) && substr($propertygrid["options"]['toolbar'],0,1) != '#')?',"toolbar":'.$propertygrid["options"]['toolbar']:null); ?>' style=""></table>

<script type="text/javascript">
var setting_site_propertygrid_id = 'setting_site_propertygrid';
var setting_site_propertygrid_toolbar = [
	{ text: '保存', iconCls: 'icons-other-disk', handler: settingSiteSave },
	{ text: '刷新', iconCls: 'icons-arrow-arrow_refresh', handler: settingSiteRefresh },
	{ text: '恢复默认', iconCls: 'icons-other-cog', handler: settingSiteDefault }
];
//保存
function settingSiteSave(){
	var data = [];
	var rows = $('#'+setting_site_propertygrid_id).propertygrid('getChanges');
	for(var i=0; i<rows.length; i++){
		data.push({'key': rows[i]['key'], 'value': rows[i]['value']});
	}
	$.post('<?php echo U('Setting/site?dosubmit=1');?>', {data: data}, function(res){
		if(!res.status){
			$.messager.alert('提示信息', res.info, 'error');
		}else{
			$.messager.alert('提示信息', res.info, 'info');
		}
	}, 'json');
}
//刷新
function settingSiteRefresh(){
	$('#'+setting_site_propertygrid_id).propertygrid('reload');
}
//恢复默认
function settingSiteDefault(){
	$.messager.confirm('提示信息', '确定要恢复出厂设置吗？', function(result){
		if(!result) return true;
		$.post('<?php echo U('Setting/siteDefault');?>', function(res){
			if(!res.status){
				$.messager.alert('提示信息', res.info, 'error');
			}else{
				$.messager.alert('提示信息', res.info, 'info');
				settingSiteRefresh();
			}
		}, 'json');
	})
}
</script>