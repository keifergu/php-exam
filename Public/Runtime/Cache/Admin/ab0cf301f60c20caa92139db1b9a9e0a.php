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
	<script type="text/javascript" src="/thinkphpcms/Public/static/js/jquery.form.min.js"></script>
</head>
<body>
	<table id="StuImport_index_dg" title="试卷列表" class="easyui-datagrid" style="width: 700px; height: 450px" url="<?php echo U('StuImport/paperList');?>" toolbar="#StuImport_index_toolbar" pagination="true" pageSize='15' pageNumber='1' multiSort='true' sortOrder="desc" pageList="[10,15,20,25,30,40,50]" rownumbers="true" fitColumns="true" singleSelect="true">
		<div id="StuImport_index_toolbar">
			<a href="<?php echo U('StuImport/download');?>" class="easyui-linkbutton" iconCls="icon-edit" plain="true" >下载模版</a>
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="$('#StuImport_new_dlg').dialog('open')">上传名单</a>
			<label>课程名称</label>
			<input id="StuImport_index_toolbar_course_name" name="course_name">
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
	<div id="StuImport_new_dlg" title="请选择要上传的文件" class="easyui-dialog" style="width:400px;height:150px;padding:10px 20px" closed="true" buttons="#StuImport_new_dlg-buttons" >
		<form id="StuImport_fileForm" method="post" enctype="multipart/form-data" action="index.php?m=admin&c=StuImport&a=upload">
			<input name="xlsfile" type="file"><br/>
		</form>
		<div id="StuImport_new_dlg-buttons" >
			<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="$('#StuImport_fileForm').submit()">上传</a>
		</div>
	</div>

	<script type="text/javascript">
		$(function() {
			 $("#StuImport_fileForm").ajaxForm({
                //定义返回JSON数据，还包括xml和script格式
                dataType:'json',
                beforeSend: function() {
                    //表单提交前做表单验证
                },
                success: function(data) {
                    //提交成功后调用
                    $.messager.alert('My Title',data , function(r){
                        if(r){

                        }
                    });
                }
            });
			$('#StuImport_index_dg').datagrid(
			{
				view : detailview,
				detailFormatter : function(index, row) {
					return '<div id="StuImport-index-ddv-' + index + '" style="padding:5px 0"></div>';
				},	
				onExpandRow : function(index, row) {
					$('#StuImport-index-ddv-' + index).panel(
					{
						border : false,
						cache : false,
						href : 'App/Admin/View/StuImport/getPaperDetail.php?itemid='+ row.paper_id,
						onLoad : function() {
							$('#StuImport_index_dg').datagrid('fixDetailRowHeight',index);
						}
					});
					$('#StuImport_index_dg').datagrid('fixDetailRowHeight', index);
				}
			});
		});
		var purl = "<?php echo U('Dictdata/getDictNameJson');?>";
		purl += purl.indexOf('?') != -1 ? '&id=200' : '?id=200';
		$('#StuImport_index_toolbar_course_name').combobox({
			valueField : 'type_id',
			textField : 'type_name',
			panelHeight : 'auto',
			url : purl,
			onSelect : StuImport_onselect,
			onLoadSuccess : StuImport_onCourseComboSuccess
		});
		function StuImport_onCourseComboSuccess() {
			var val = $('#StuImport_index_toolbar_course_name').combobox("getData");
			for ( var item in val[0]) {
				if (item == "type_id") {
					$('#StuImport_index_toolbar_course_name').combobox("select", val[0][item]);
				}
			}
		}
		function StuImport_onselect() {
			var pCourse = $('#StuImport_index_toolbar_course_name').combobox('getValue');
			var param = {
				"courseid" : pCourse,
			};
			$('#StuImport_index_dg').datagrid('load', param);
		}
			
	</script>
</body>




</html>