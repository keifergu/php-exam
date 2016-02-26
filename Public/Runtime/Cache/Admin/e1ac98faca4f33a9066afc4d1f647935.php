<?php if (!defined('THINK_PATH')) exit();?><body>
<table id="dict_list_dg" title="数据字典" class="easyui-datagrid" style="width:700px;height:450px" url="<?php echo U('Dictdata/dictList');?>"  toolbar="#toolbar" pagination="true" pageSize='15' pageNumber='1' multiSort='true' sortOrder="desc" pageList="[2,5,10,15,20,25,30,40,50]" rownumbers="true" fitColumns="true" singleSelect="true" >
        <thead>
            <tr>
                <th field="dict_id" width="10%" sortable="true">ID</th>
                <th field="fname" width="40%"sortable="true">名称</th>
                <th field="value1" width="25%">值1</th>
                <th field="value2" width="25%">值2</th>
            </tr>
        </thead>
    </table>

    <div id="toolbar">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newUser()">添加</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="editUser()">编辑</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-remove" plain="true" onclick="destroyUser()">删除</a>	
        <form id="fmSearch" method="post" novalidate style="display:inline-block;margin-left:50px;">
             <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon icon-all" plain="true" onclick="showAll()">全部</a>  	
			     <input class="easyui-combobox" 
					name="language"
					data-options="
                    url:'<?php echo U('Dictdata/getFirstDict');?>',
                    method:'get',
                    valueField:'dict_id',
                    textField:'fname',
                    panelHeight:'auto'
				 ">
       		<input id='searchBox' class="easyui-searchbox" data-options="height:30,menu:'#searchboxSelect',searcher:doSearch"  style="width:100px"/>
			    <div id="searchboxSelect">
			        <div data-options="name:'fname'">名称</div>
		    	</div>
	    </form>
    </div>
	
	<!-- 会员添加 -->
	<div id="dlg" class="easyui-dialog" title="添加数字字典" style="width:400px;padding:10px 20px"
            closed="true" buttons="#dlg-buttons"  modal="true">
        <form id="fm" method="post" novalidate>
		 <table>
		    <tr>
           
                <th><label>名称</label></th>
                <th><input name="fname" class="easyui-textbox"/></th>
            </tr>
            <tr>
                <th><label>值1</label></th>
                <th><input name="value1" id="value1" type="text"></th>
            </tr>
            <tr>
                <th><label>值2</label></th>
                <th><input name="value2" id="value2" type="text"></th>
            </tr>
		</table>
        </form>
    </div>
	
	<!-- 会员编辑 -->
	<div id="dlgEdit" class="easyui-dialog" style="padding:10px 20px;width:500px;"
            closed="true" buttons="#dlgEdit-buttons" modal="true">
        <form id="fmEdit" method="post" novalidate>
	          <table>
		    <tr>
           
                <th><label>名称</label></th>
                <th><input name="fname" class="easyui-textbox"/></th>
            </tr>
            <tr>
                <th><label>值1</label></th>
                <th><input name="value1" id="value1" type="text"></th>
            </tr>
            <tr>
                <th><label>值2</label></th>
                <th><input name="value2" id="value2" type="text"></th>
            </tr>
		</table>
        </form>
    </div>

	<!-- 添加会员 -->
    <div id="dlg-buttons">
        <a href="#" class="easyui-linkbutton" iconCls="icon-ok" onclick="addUser()" style="width:90px">添加</a>
        <a href="#" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlg').dialog('close')" style="width:90px">取消</a>
    </div>
	<!-- /添加会员 -->	

	<!-- 会员编辑 -->
    <div id="dlgEdit-buttons">
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-ok" onclick="saveUser()" style="width:90px">保存</a>
        <a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-cancel" onclick="javascript:$('#dlgEdit').dialog('close')" style="width:90px">取消</a>
    </div>
	<!-- /会员编辑 -->

   <script type="text/javascript">
        var url;
        //添加会员对话窗
        function newUser(){
            $('#dlg').dialog('open').dialog('setTitle','添加数据字典');
            $('#fm').form('clear');
            url ="<?php echo U('Dictdata/dictAdd');?>";
        }

        //编辑会员对话窗
        function editUser(){
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $('#dlgEdit').dialog('open').dialog('setTitle','编辑会员');
                $('#fmEdit').form('load',row);
                url ="<?php echo U('Dictdata/dictEdit');?>";
				url+=url.indexOf('?') != -1 ? '&id='+row.dict_id : '?id='+row.dict_id;
                alert(url);
            }
        }

        //添加会员
        function addUser(){
            $('#fm').form('submit',{
                url: url,
                onSubmit: function(){
                    return $(this).form('validate');
                },
                success: function(result){
                	 var result=eval('('+result+')');
                    if (!result.status){
                        $.messager.confirm('错误提示',result.message,function(r){
                            $('#dlg').dialog('close');  
                        });
                    } else {
                        $('#dlg').dialog('close');        // close the dialog
                        $('#dict_list_dg').datagrid('reload');    // reload the user data
                    }
                }
            });
        }

        //编辑会员
        function saveUser(){
            $('#fmEdit').form('submit',{
                url: url,
                onSubmit: function(){
                    return $(this).form('validate');
                },
                success: function(result){
                	 var result=eval('('+result+')');
                    if (!result.status){
                        $.messager.confirm('错误提示',result.message,function(r){
                            $('#dlgEdit').dialog('close');  
                        });
                    }else{
                        $('#dlgEdit').dialog('close');        // close the dialog
                        $('#dg').datagrid('reload');    // reload the user data
                    }
                }
            });
        }

        //删除会员
        function destroyUser(){
            var row = $('#dg').datagrid('getSelected');
            if (row){
                $.messager.confirm('删除提示','真的要删除此会员吗?删除将不能再恢复!',function(r)
				{
                    if (r){
                    	var durl='<?php echo U("Dictdata/dictDelete");?>';
                        $.post(durl,{id:row.dict_id,M:'m'},function(result){
                            if (result.status){
                                $('#dg').datagrid('reload');    // reload the user data
                            } else {
                                $.messager.alert('错误提示',result.message,'error');
                            }
                        },'json').error(function(data){
                            var info=eval('('+data.responseText+')');
                            $.messager.confirm('错误提示',info.message,function(r){
                                
                            });
                        });
                    }
                });
            }
        }

        //搜索
        function doSearch(value,name){
        	if(value==''){
        		$.messager.alert('搜索提示','搜索内容不能为空!','error');
        	}else{
        		var searchName=$('#searchBox').searchbox('getName');
        		var searchValue=$('#searchBox').searchbox('getValue');
        		switch(searchName){
        			case 'fname':
        				$('#dg').datagrid({ queryParams:{fname:searchValue}});
        				break;
        		}
        	}
        }

        //显示全部数据
        function showAll(){
        	$('#dg').datagrid({ queryParams:''});
        }

       

    </script>
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
    </style>
	
</body>