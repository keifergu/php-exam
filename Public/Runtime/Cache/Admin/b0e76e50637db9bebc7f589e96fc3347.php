<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE html>
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
	<input id="hidden_course_id"  type="hidden"></input>
	<input id="hidden_paper_id"  type="hidden"></input>
	<div id="div-paper_index_dg">
		<table id="paper_index_dg" title="试卷列表" class="easyui-datagrid" style="width: 700px; height: 450px" url="<?php echo U('Paperdata/paperList');?>" toolbar="#paper_index_toolbar" pagination="true" pageSize='15' pageNumber='1' multiSort='true' sortOrder="desc" pageList="[10,15,20,25,30,40,50]" rownumbers="true" fitColumns="true" singleSelect="true">
			<div id="paper_index_toolbar">
				<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newPaper()">创建</a>
				<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="addPaper()">添题</a>
				<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editPaper()">预览</a>
				<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="removePaper()">删除</a>
				<label>课程名称</label>
				<input id="paper_index_toolbar_course_name" name="course_name">
			</div>
			<thead> 
				<tr>
					<th field="paper_id" width="15%" sortable="true">ID</th>
					<th field="paper_name" width="35%" sortable="true">试卷名称</th>
					<th field="question_num" width="10%">题目数量</th>
					<th field="total_grade" width="10%">试卷总分</th>
					<th field="test_time" width="10%">考试时间</th>
					<th field="deadline" width="20%">截止时间</th>
				</tr>
			</thead>
		</table>
	</div>
	<div id="div-paper_new_dlg" type="hidden">
		<div id="paper_new_dlg" title="添加试卷" class="easyui-dialog" style="width:400px;height:280px;padding:10px 20px" closed="true" buttons="#paper_new_dlg-buttons" >
			<table>
				<form id="paper_new_form" method="post" novalidate>
					<tr>
						<td>
							<label>试卷名称:</label>
						</td>
						<td>
							<input name="paper_name" id="paper_new_paper_name" class="easyui-validatebox" required="true"  style="width:200px">
						</td>

					</tr>
					<tr>
						<td>
							<label>课程名称：</label>
						</td>
						<td>
							<input name="course_id" id="paper_new_course_id" class="easyui-validatebox" required="true"  style="width:200px">
						</td>
					</tr>
					
					<tr>
						<td>
							<label>考试时间:</label>
						</td>
						<td>
							<input name="test_time" id="paper_new_test_time" class="easyui-timespinner" value="0:0" required="true"   style="width:200px">
						</td>
					</tr>
					<tr>
						<td>
							<label>截止日期:</label>
						</td>
						<td>
							<input name="deadline" id="paper_new_deadline" class="easyui-datetimebox" value="2015-01-01 0:0:0"required="true"  style="width:200px">
						</td>
					</tr>
				</form>
			</table>
		</div>
		<div id="paper_new_dlg-buttons" >
			<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="paperNew()">新建</a>
			<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#paper_new_dlg').dialog('close')">取消</a>
		</div>
	</div>
	

	<script type="text/javascript" >

		$(function() {
			$('#paper_index_dg').datagrid(
			{
				view : detailview,
				detailFormatter : function(index, row) {
					return '<div id="paper-index-ddv-' + index + '" style="padding:5px 0"></div>';
				},	
				onExpandRow : function(index, row) {
					$('#paper-index-ddv-' + index).panel(
					{
						border : false,
						cache : false,
						href : 'App/Admin/View/Paperdata/getPaperDetail.php?itemid='+ row.paper_id,
						onLoad : function() {
							$('#paper_index_dg').datagrid('fixDetailRowHeight',index);
						}
					});
					$('#paper_index_dg').datagrid('fixDetailRowHeight', index);
				}
			});
		});
		var purl = "<?php echo U('Dictdata/getDictNameJson');?>";
		purl += purl.indexOf('?') != -1 ? '&id=200' : '?id=200';
		$('#paper_index_toolbar_course_name').combobox({
			valueField : 'type_id',
			textField : 'type_name',
			panelHeight : 'auto',
			url : purl,
			onSelect : index_onselect,
			onLoadSuccess : index_onCourseComboSuccess
		});
		$('#paper_new_course_id').combobox({
			valueField : 'type_id',
			textField : 'type_name',
			panelHeight : 'auto',
			url : purl,
			onSelect : index_onselect,
			onLoadSuccess : index_onCourseComboSuccess
		});
		
		function index_onCourseComboSuccess() {
			var val = $('#paper_index_toolbar_course_name').combobox("getData");
			// "\""+变量+"\""
			for ( var item in val[0]) {
				if (item == "type_id") {
					$('#paper_index_toolbar_course_name').combobox("select", val[0][item]);
				}

			}
		}
		function index_onselect() {
			var pCourse = $('#paper_index_toolbar_course_name').combobox('getValue');
			var param = {
				"courseid" : pCourse,
			};
			$('#paper_index_dg').datagrid('load', param);
		}

		function newPaper(){
			$('#paper_new_dlg').dialog('open');
			$('#paper_new_dlg').form('clear');
		}
		function paperNew(){
			var name=$('#paper_new_paper_name').val();
			var course=$('#paper_new_course_id').combobox('getValue');
			var time=$('#paper_new_test_time').val();
			var deadline=$('#paper_new_deadline').datetimebox('getValue');
			$.post("index.php?m=Admin&c=Paperdata&a=paperNew",{
				paper_name:$('#paper_new_paper_name').val(),
				course_id:$('#paper_new_course_id').combobox('getValue'),
				test_time:$('#paper_new_test_time').val(),
				deadline:$('#paper_new_deadline').datetimebox('getValue')
			},function(data){
				$('#paper_index_dg').datagrid('reload');
				$('#paper_new_dlg').dialog('close')
			}
			);
		}
		function openUrl(url, title){
			if($('#pagetabs').tabs('exists', title)){
				$('#pagetabs').tabs('select', title);
			}else{
				$('#pagetabs').tabs('add',{
					title: title,
					href: url,
					closable: true,
					cache: false
				});
			}
		}
		function addPaper() {
			var row=$('#paper_index_dg').datagrid('getSelected');
			$('#hidden_course_id').val(row.course_id);
			$('#hidden_paper_id').val(row.paper_id);
			var urls="<?php echo U('Paperdata/AddOptionList');?>&paper_id="+row.paper_id;
			var title="添题"+row.paper_id;
			openUrl(urls,title);
		}
		function editPaper(){
			var row=$('#paper_index_dg').datagrid('getSelected');
			$('#hidden_course_id').val(row.course_id);
			$('#hidden_paper_id').val(row.paper_id);
			var urls="<?php echo U('Paperdata/EditOptionList');?>&paper_id="+row.paper_id;
			var title="预览"+row.paper_id;
			openUrl(urls,title);
		}
		function removePaper() {
			var row=$('#paper_index_dg').datagrid('getSelected');
			if (row){
				$.messager.confirm('警告','确定删除？',function(r){
					if (r){
						$.post("index.php?m=Admin&c=Paperdata&a=paperRemove",{
							paper_id:row.paper_id
						},function(data){
							$('#paper_index_dg').datagrid('reload');
						});
					}
				});
			}
		}
	</script>
</body>
</html>