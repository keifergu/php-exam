<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
	<meta charset="utf-8">
	<script type="text/javascript" src="/thinkphpcms/Public/static/js/jquery.min.js"></script>
<script type="text/javascript" src="/thinkphpcms/Public/static/js/jquery.cookie.js"></script>
<script type="text/javascript" src="/thinkphpcms/Public/static/js/jquery.json.min.js"></script>
<script type="text/javascript" src="/thinkphpcms/Public/static/js/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="/thinkphpcms/Public/static/js/easyui/locale/easyui-lang-zh_CN.js"></script>
<script type="text/javascript" src="/thinkphpcms/Public/static/js/easyui/jquery.edatagrid.js"></script>
<script type="text/javascript" src="/thinkphpcms/Public/static/js/easyui/datagrid-detailview.js"></script>

<script type="text/javascript" src="/thinkphpcms/Public/static/js/jquery.form.min.js"></script>

<link rel="stylesheet" type="text/css" href="/thinkphpcms/Public/static/css/icons.css" />
<link rel="stylesheet" type="text/css" href="/thinkphpcms/Public/static/js/easyui/themes/default/easyui.css" title="default" />
<link rel="stylesheet" type="text/css" href="/thinkphpcms/Public/static/js/easyui/themes/gray/easyui.css" title="gray" />
<link rel="stylesheet" type="text/css" href="/thinkphpcms/Public/static/js/easyui/themes/bootstrap/easyui.css" title="bootstrap" />
<link rel="stylesheet" type="text/css" href="/thinkphpcms/Public/static/js/easyui/themes/metro/easyui.css" title="metro" />
<link rel="stylesheet" type="text/css" href="/thinkphpcms/Public/static/css/admin/default.css" title="default" />
<link rel="stylesheet" type="text/css" href="/thinkphpcms/Public/static/css/admin/gray.css" title="gray" />
<link rel="stylesheet" type="text/css" href="/thinkphpcms/Public/static/css/admin/bootstrap.css" title="bootstrap" />
<link rel="stylesheet" type="text/css" href="/thinkphpcms/Public/static/css/admin/metro.css" title="metro" />
<link rel="stylesheet" type="text/css" href="/thinkphpcms/Public/static/js/easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="/thinkphpcms/Public/static/js/easyui/themes/icon.css">
<link rel="stylesheet" type="text/css" href="/thinkphpcms/Public/static/js/easyui/demo/demo.css">
<script type="text/javascript">
var theme = $.cookie('theme') || 'bootstrap';	//全局变量
$(document).ready(function(){
	$('link[rel*=style][title]').each(function(i){
		this.disabled = true;
		if (this.getAttribute('title') == theme) this.disabled = false;
	});
});
</script>
</head>
<body>
	<input id="hidden<?php echo ($hidden_paper_id); ?>_course_id"  type="hidden" value="<?php echo ($hidden_course_id); ?>"></input>
	<input id="hidden<?php echo ($hidden_paper_id); ?>_paper_id"  type="hidden" value="<?php echo ($hidden_paper_id); ?>"></input>
	<div id="div-paper<?php echo ($hidden_paper_id); ?>_add_dg" >
		<table id="paper<?php echo ($hidden_paper_id); ?>_add_dg" title="添加新题目到试卷" class="easyui-datagrid"  style="width: 700px; height: 450px" url="<?php echo U('Paperdata/AddOptionList');?>&paper_id=<?php echo ($hidden_paper_id); ?>" toolbar="#paper<?php echo ($hidden_paper_id); ?>_add_toolbar" pagination="true" pageSize='15' pageNumber='1' multiSort='true' sortOrder="desc" pageList="[10,15,20,25,30,40,50]" rownumbers="true" fitColumns="true" singleSelect="false">
			<thead>
				<tr>
					<th field="question_id" width="15%" sortable="true">ID</th>
					<th field="course" width="15%" sortable="true">课程名称</th>
					<th field="type" width="15%">题目类型</th>
					<th field="title" width="55%">题干</th>
				</tr>
			</thead>
		</table>
		<div id="paper<?php echo ($hidden_paper_id); ?>_add_toolbar">
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="paperAdd()">添加</a> 
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="paperEdit()">编辑</a> 
			<label>课程名称</label>
			<input id="paper<?php echo ($hidden_paper_id); ?>_add_toolbar_course_name" name="course_name" readonly="readonly">
			<label>题目类型</label> 
			<input id="paper<?php echo ($hidden_paper_id); ?>_add_toolbar_option_type" name="option_name">
		</div>
	</div>

	<script type="text/javascript">
		$('#paper<?php echo ($hidden_paper_id); ?>_add_dg').datagrid(
		{
			view : detailview,
			detailFormatter : function(index, row) {
				return '<div id="paper<?php echo ($hidden_paper_id); ?>-add-ddv-' + index + '" style="padding:5px 0"></div>';
			},
			onExpandRow : function(index, row) {
				$('#paper<?php echo ($hidden_paper_id); ?>-add-ddv-' + index).panel(
				{
					border : false,
					cache : false,
					href : 'App/Admin/View/Paperdata/getOptionDetail.php?itemid='+ row.question_id,
					onLoad : function() {
						$('#paper<?php echo ($hidden_paper_id); ?>_add_dg').datagrid('fixDetailRowHeight',index);
					}
				});
				$('#paper<?php echo ($hidden_paper_id); ?>_add_dg').datagrid('fixDetailRowHeight', index);
			}
		});
		var purl = "<?php echo U('Dictdata/getDictNameJson');?>";
		purl += purl.indexOf('?') != -1 ? '&id=200' : '?id=200';
		$('#paper<?php echo ($hidden_paper_id); ?>_add_toolbar_course_name').combobox({
			valueField : 'type_id',
			textField : 'type_name',
			panelHeight : 'auto',
			url : purl,
			onSelect : add_onselect,
			onLoadSuccess : add_onCourseComboSuccess
		});
		purl = "<?php echo U('Dictdata/getDictNameJson');?>";
		purl += purl.indexOf('?') != -1 ? '&id=100' : '?id=100';
		$('#paper<?php echo ($hidden_paper_id); ?>_add_toolbar_option_type').combobox({
			valueField : 'type_id',
			textField : 'type_name',
			panelHeight : 'auto',
			url : purl,
			onSelect : add_onselect,
			onLoadSuccess : add_onOptionComboSuccess
		});
		function add_onCourseComboSuccess() {
			var val = $('#paper<?php echo ($hidden_paper_id); ?>_add_toolbar_course_name').combobox("getData");
			// "\""+变量+"\""
			for ( var item in val[0]) {
				if (item == "type_id") {
					$('#paper<?php echo ($hidden_paper_id); ?>_add_toolbar_course_name').combobox("select", $("#hidden<?php echo ($hidden_paper_id); ?>_course_id").val());	
				}
			}
		}
		function add_onOptionComboSuccess() {
			var val = $('#paper<?php echo ($hidden_paper_id); ?>_add_toolbar_option_type').combobox("getData");
			for ( var item in val[0]) {
				if (item == "type_id") {
					$('#paper<?php echo ($hidden_paper_id); ?>_add_toolbar_option_type').combobox("select", val[0][item]);

				}
			}
		}
		function add_onselect() {
			var pCourse = $('#paper<?php echo ($hidden_paper_id); ?>_add_toolbar_course_name').combobox('getValue');
			var pType = $('#paper<?php echo ($hidden_paper_id); ?>_add_toolbar_option_type').combobox('getValue');
			var param = {
				"courseid" : pCourse,
				"typeid" : pType
			};
			$('#paper<?php echo ($hidden_paper_id); ?>_add_dg').datagrid('load', param);
		}
		function paperAdd(){
			var row=$('#paper<?php echo ($hidden_paper_id); ?>_add_dg').datagrid('getSelections');
			//只提交题号
			var result=new Array();
			for (var i = row.length - 1; i >= 0; i--) {
				result[i]=row[i].question_id;
			}
			$.post("index.php?m=Admin&c=Paperdata&a=paperAdd",{
				optionid:result,
				paperid:$('#hidden<?php echo ($hidden_paper_id); ?>_paper_id').val()
			},function(data){
				$('#paper<?php echo ($hidden_paper_id); ?>_add_dg').datagrid('reload');
			}
			);
		}
		function paperEdit(){
			var row = $('#paper<?php echo ($hidden_paper_id); ?>_add_dg').datagrid('getSelected');
        	window.open("<?php echo U('Optiondata/optionEdit');?>&id="+row.question_id);  //打开一个新的页面
        }
    </script>

</body>