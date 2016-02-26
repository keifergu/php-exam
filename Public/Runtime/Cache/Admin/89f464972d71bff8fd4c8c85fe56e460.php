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
    <script type="text/javascript">
        $(function(){
            $("#fileForm").ajaxForm({
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
        });
    </script>
    <div id="div-option_new_dlg" >
        <div id="option_new_dlg" title="请选择要上传的文件" class="easyui-dialog" style="width:400px;height:150px;padding:10px 20px" closed="true" buttons="#option_new_dlg-buttons" >
            <form id="fileForm" method="post" enctype="multipart/form-data" action="index.php?m=admin&c=Optiondata&a=Upload">
                <input name="xlsfile" type="file"><br/>
            </form>
            <div id="option_new_dlg-buttons" >
                <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="$('#fileForm').submit()">上传</a>
            </div>



        </div>
    </div>

    <table id="option_index_dg" title="题目列表" class="easyui-datagrid" style="width:700px;height:450px" url="<?php echo U('Optiondata/optionList');?>"  toolbar="#option_index_toolbar" pagination="true" pageSize='15' pageNumber='1' multiSort='true' sortOrder="desc" pageList="[10,15,20,25,30,40,50]" rownumbers="true" fitColumns="true" singleSelect="true">
        <thead>
            <tr>
                <!-- <th field="ck" checkbox="true"></th> -->
                <!-- 增加checkbox如果需要多选则需要将singleSelect设置为false -->
                <th field="question_id" width="15%" sortable="true">ID</th>
                <th field="course" width="15%"sortable="true">课程名称</th>
                <th field="type" width="15%">题目类型</th>
                <th field="title" width="55%">题目</th>
            </tr>
        </thead>
    </table>

    <div id="option_index_toolbar">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()">添加</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser()" >编辑</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="removeUser()" >删除</a>
        <label>课程名称</label>
        <input id="option_index_toolbar_course_name" name="course_name">
        <label>题目类型</label>
        <input id="option_index_toolbar_option_type" name="option_name">
    </div>
    <style type="text/css">
        #fm{
            margin:0;
            padding:10px;
        }
        #fitem
        {
         margin:10px;
         padding:10px;
     }
     #optiontitle
     {
        width:600px;
        height: 80px;
    }
    #option
    {
        width: 600px;
        height: 50px;
    }

</style>

<div id="option_index_dlg" class="easyui-dialog" style="width:400px;height:280px;padding:10px 20px"
closed="true" buttons="#dlg-buttons">

</div>
<div id="dlg-buttons">
	<a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveUser()">Save</a>
	<a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')">Cancel</a>
</div>
<script type="text/javascript">
    $(function(){
        $('#option_index_dg').datagrid({
            view: detailview,
            detailFormatter:function(index,row){
                return '<div id="ddv-' + index + '" style="padding:5px 0"></div>';
            },
            onExpandRow: function(index,row){
                $('#ddv-'+index).panel({
                    border:false,
                    cache:false,
                   // href:'App/Admin/View/Optiondata/getOptionDetail.php?itemid='+row.question_id,
                    href:'index.php?m=admin&c=Optiondata&a=test',
                    onLoad:function(){
                        $('#option_index_dg').datagrid('fixDetailRowHeight',index);

                    }
                });
                $('#option_index_dg').datagrid('fixDetailRowHeight',index);
            }
        }); 
    });
    var purl="<?php echo U('Dictdata/getDictNameJson');?>";
    purl+=purl.indexOf('?') != -1 ? '&id=200' : '?id=200';
    $('#option_index_toolbar_course_name').combobox({
        valueField:'type_id',
        textField:'type_name',
        panelHeight:'auto',
        url:purl,
        onSelect:onselect,
        onLoadSuccess:onCourseComboSuccess
    });
    purl="<?php echo U('Dictdata/getDictNameJson');?>";
    purl+=purl.indexOf('?') != -1 ? '&id=100' : '?id=100';
    $('#option_index_toolbar_option_type').combobox({
        valueField:'type_id',
        textField:'type_name',
        panelHeight:'auto',
        url:purl,
        onSelect:onselect,
        onLoadSuccess:onOptionComboSuccess

    });
        // $('#option_index_dg').datagrid({
        //         onLoadSuccess: function(data){
        //             if (data.total==0) {
        //                 alert("没有数据");
        //                 // $('#option_index_dg').datagrid('loadData',{total:0,rows:[]});
        //             }
        //         }
        //     });

        function newUser(){
           $('#option_new_dlg').dialog('open');
       }
       function editUser(){
           var row = $('#option_index_dg').datagrid('getSelected');
        	window.open("<?php echo U('Optiondata/optionEdit');?>&id="+row.question_id);  //打开一个新的页面
        }
        function removeUser(){
        	var row = $('#option_index_dg').datagrid('getSelected');
            $.post("index.php?m=Admin&c=Optiondata&a=optionRemove",{
                id:row.question_id
            },function(data){
                $('#option_index_dg').datagrid('reload');
            });
        }
        function onselect()
        {
            var pCourse=$('#option_index_toolbar_course_name').combobox('getValue');
            var pType=$('#option_index_toolbar_option_type').combobox('getValue');
            var param = {"courseid" :pCourse,"typeid":pType};
            $('#option_index_dg').datagrid('load',param);
        }
        function onOptionComboSuccess()
        {
            var val = $('#option_index_toolbar_option_type').combobox("getData");
            for (var item in val[0]) 
            {
                if (item == "type_id") 
                {
                    $('#option_index_toolbar_option_type').combobox("select", val[0][item]);
                }
            }
        }
        function onCourseComboSuccess()
        {
            var val = $('#option_index_toolbar_course_name').combobox("getData");
            // "\""+变量+"\""
            for (var item in val[0]) 
            {

                if (item == "type_id") 
                {
                    $('#option_index_toolbar_course_name').combobox("select", val[0][item]);
                }
            }
        }
    </script>
</body>
</html>