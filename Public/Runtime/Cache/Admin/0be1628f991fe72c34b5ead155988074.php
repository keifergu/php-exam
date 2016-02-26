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
	<script type="text/javascript" src="/thinkphpcms/Public/static/js/xheditor/xheditor-1.2.2.min.js"></script> 
	<script type="text/javascript" src="/thinkphpcms/Public/static/js/xheditor/xheditor_lang/zh-cn.js"></script>
</head>
<body>
	<style type="text/css">
/*#fm{
            margin:0;
            padding:10px;
        }*/
        #fitem {
        	margin: 10px;
        	padding: 10px;
        }

        #optiontitle {
        	width: 600px;
        	height: 80px;
        }

        #option {
        	width: 600px;
        	height: 50px;
        }
    </style>
    <script type="text/javascript">
    	$(document).ready(function() {
    		getData();
    	});


    	function getData(){
    		$.post('index.php/admin/Optiondata/getOptionData',{
    			id:$("#hidden_option_id").val()
    		},function(data){
    			$("*[name=option_title]").val(data['title']);
    			$("#hidden_option_course").val(data['course_id']);
    			$("#hidden_option_type").val(data['type']);
    			$("#hidden_option_keyword").val(data['keyword']);
    			$("#answerserial").val(data['answer']);
    			$("#hidden_option_img").val(data['img']);
    			$('#option_t').combobox('setValue',data['type']);
    			var pType=$('#option_t').combobox('getValue'); 
		  // alert($('#option_t').combobox('getValue'));
		  switch(data['type']){
		  //多选
		  case '102':
		  $("*[name='optionE']").val(data['e']);
		  $("*[name='optionF']").val(data['f']);

		  //单选
		  case '101':
		  $("*[name='optionC']").val(data['c']);
		  $("*[name='optionD']").val(data['d']);
		  //判断
		  case '103': 
		  $("*[name='optionA']").val(data['a']);
		  $("*[name='optionB']").val(data['b']);
		  break;	
			//填空，问答
			case '104': break;
			case '105': break;
		}

		  //初始化答案
		  $("#answerserial").val(data['answer']);
		  //初始选项打勾
		  if(data['type']=='101'||data['type']=='103'){
		  	var answerid="answer"+data['answer'];
		  	$("#"+answerid).attr("checked","true");
		  }else if(data['type']=='102'){
		  	var tempans=data['answer'];
		  	for(var i=5;i>=0;i--){
		  		alert(tempans);
		  		if(parseInt(tempans/(1<<i))){
		  			var answerid="answer"+(i+1);
		  			$("#"+answerid).attr("checked","true");
		  			tempans=tempans-(1<<i);
		  		}
		  	}
		  }
		  onselect();
		  getCheckBoxState();
		});
}

  		// 声明全局数据
  		if(typeof OPTION_TYPE_ENUM == "undefined"){
  			　　var OPTION_TYPE_ENUM = {
  				SINGLE_OPTION:"101",
  				MULTI_OPTION:"102",
  				TRUE_FALSE_OPTION:"103",　　　　　　    
  			　　　　　　　  }
  		　　}

		//获取checkbox状态,得到最终的答案
		function getCheckBoxState()
		{
			var el=document.getElementsByTagName("input");
			var len=el.length;
			var val=0;
			var res=0;
			try{
				for (var i = 1; i <len; i++) {
					if (el[i].type=="checkbox" ) {
						if (el[i].checked==true) {
		   	 				//val=val+Number(el[i].value);
	                   					//alert(typeof(el[i].value)+el[i].value);
	                   					val=Number(el[i].value);
	                   					res=res+val;
	                   					//alert(val);
	                   				}
	                   			}
	                   			if (el[i].type=="radio"  ) {
	                   				if (el[i].checked==true){
	   	 					//val=Number(el[i].value);
	   	 					val=Number(el[i].value);
	   	 					res=val;
	   	 				}
	   	 			}
	   	 		}
	   	 		if (document.getElementById("answerserial"))
	   	 		{
	   	 			var target=document.getElementById("answerserial");
	   	 			target.value=res;
	   	 		}
   	    			//res=Number(val);
   	    		}
   	    		catch(err)
   	    		{
   	    			txt="There was an error on this page.\n\n";
   	    			txt+="Error description: " + err.message +i+ "\n\n";
   	    			txt+="Click OK to continue.\n\n";
   	    			alert(txt);
   	    		}  	 
   	    	}
   	    	function onselect()
   	    	{
			//获取题目类型
			var pType=$('#option_t').combobox('getValue');
			$("#hidden_option_type").val(pType);
			var optionNum;
			var target;
			var checkType;
			switch(pType)
			{
				case OPTION_TYPE_ENUM.SINGLE_OPTION:
				optionNum=4;
				checkType="radio";
				break;
				case OPTION_TYPE_ENUM.MULTI_OPTION:
				optionNum=6;
				checkType="checkbox";
				break;
				case OPTION_TYPE_ENUM.TRUE_FALSE_OPTION:
				optionNum=2;
				checkType="radio";
				break;
				default :
				optionNum=0;
				break;
			}
       		///控制页面html标签的显示
       		for (var i = 1; i <=6; i++) {
            //让所有选项出现
            target="hide"+i;
            showElement(target,true);
            target="hideanswer"+i;
            showElement(target,true);
            target="answer"+i;
            modifyCheckBoxArr(target,checkType);
        }

        for (var i = optionNum+1; i <=6; i++) {
        	//多余选项隐藏
        	target="hide"+i;
        	showElement(target,false);
        	target="hideanswer"+i;
        	showElement(target,false);
        }
    }
	//修改选项类型
	function modifyCheckBoxArr(targetid,arrVal)
	{	
		if (document.getElementById(targetid))
		{
			target=document.getElementById(targetid);
			target.type=arrVal;
		}
	}
	//控制显示
	function showElement(targetid,state)
	{
		if (document.getElementById(targetid))
		{
			target=document.getElementById(targetid);
			if (state==false && target.style.display=="") 
			{
				target.style.display="none"; 
			}
			if (state==true && target.style.display=="none")
			{
				target.style.display=""; 
			} 
		}
	}
</script>
<form id="fm" method="post" novalidate action="index.php?m=admin&c=optiondata&a=update" target='_self' >
	<table style="width: 700px" cellspacing="0" cellpadding="0">
		<tr>
			<th style="text-align: left;">
				<label>课程名称</label> 
				<input class="easyui-combobox" name="course_name" readonly="readonly"
				data-options="
				url:'<?php echo U('Dictdata/getDictNameJson?id=200');?>',
				method:'get',
				valueField:'type_id',
				textField:'type_name',
				panelHeight:'auto',
				value:201 
				">
			</th>
			<th style="text-align: left;">
				<label>题目类型</label> 
				<input class="easyui-combobox" name="option_type" id="option_t" readonly="readonly"
				data-options="
				url:'<?php echo U('Dictdata/getDictNameJson?id=100');?>',
				method:'get',
				valueField:'type_id',
				textField:'type_name',
				panelHeight:'auto',
				value:101,
				onSelect:onselect

				">
				<!-- value为设置默认的值 -->
			</th>
		</tr>
	</table>
	<table style="width: 700px" cellspacing="0" cellpadding="0">
		<tr>
			<td><label>题目</label></td>
			<td><textarea name="option_title" class="txtNewsContent" id="optiontitle"></textarea></td>
		</tr>
		<tr id="hide1">
			<td><label>选项A</label></td>
			<td><textarea name="optionA" class="txtNewsContent" id="option"></textarea></td>
		</tr>
		<tr id="hide2">
			<td><label>选项B</label></td>
			<td><textarea name="optionB" class="txtNewsContent" id="option"></textarea></td>
		</tr>
		<tr id="hide3">
			<td><label>选项C</label></td>
			<td><textarea name="optionC" class="txtNewsContent" id="option"></textarea></td>
		</tr>
		<tr id="hide4">
			<td><label>选项D</label></td>
			<td><textarea name="optionD" class="txtNewsContent" id="option"></textarea></td>
		</tr>
		<tr id="hide5">
			<td><label>选项E</label></td>
			<td><textarea name="optionE" class="txtNewsContent" id="option"></textarea></td>
		</tr>
		<tr id="hide6">
			<td><label>选项F</label></td>
			<td><textarea name="optionF" class="txtNewsContent" id="option"></textarea></td>
		</tr>
	</table>
	<table style="width: 700px" cellspacing="0" cellpadding="0">
		<tr>
			<td >
				<!-- 如果将radio都设置为一个名字则只能选择其中一个答案 --> 
				<span id="hideanswer1">
					<input type="checkbox" name="optionAnswer" id="answer1" value="1" onclick="getCheckBoxState()"/>
					<label id="lable-answer1" for="answer1">A</label>
				</span> 
			</td>
			<td>
				<span id="hideanswer2">
					<input type="checkbox" name="optionAnswer" id="answer2" value="2" onclick="getCheckBoxState()"/>
					<label id="lable-answer1" for="answer2">B</label>
				</span> 
			</td>
			<td >
				<span id="hideanswer3">
					<input type="checkbox" name="optionAnswer" id="answer3" value="4" onclick="getCheckBoxState()"/>
					<label id="lable-answer1" for="answer3">C</label>
				</span>
			</td>
			<td >
				<span id="hideanswer4">
					<input type="checkbox" name="optionAnswer" id="answer4" value="8" onclick="getCheckBoxState()"/>
					<label id="lable-answer1" for="answer4">D</label>
				</span>
			</td>
			<td>
				<span id="hideanswer5">
					<input type="checkbox" name="optionAnswer" id="answer5" value="16" onclick="getCheckBoxState()"/>
					<label id="lable-answer1" for="answer5">E</label>
				</span> 
			</td>
			<td >
				<span id="hideanswer6">
					<input type="checkbox" name="optionAnswer" id="answer6" value="32" onclick="getCheckBoxState()"/>
					<label id="lable-answer1" for="answer6">F</label>
				</span>
			</td>

		</tr>
	</table>
	

	<input id="answerserial" type="hidden" name="answerRes">  
	<input id="hidden_option_id" type="hidden" name="hidden_option_id" value="<?php echo ($optionid); ?>"> 
	<input id="hidden_option_type" type="hidden" name="hidden_option_type" > 
	<input id="hidden_option_course" type="hidden" name="hidden_option_course" value="<?php echo ($optioncourse); ?>">
	<input id="hidden_option_keyword" type="hidden" name="hidden_option_keyword"> 
	<input id="hidden_option_img" type="hidden" name="hidden_option_img">
	<table style="width: 700px" cellspacing="0" cellpadding="0">
		<tr>
			<th></th>
			<th><input type="submit" value="确认修改"></th>
		</tr>
	</table>
</form>
<span id="result"></span>
<script type="text/javascript">
	$('.txtNewsContent').xheditor({
		tools:'full',
		skin:'default',
		showBlocktag:false,	//
		internalScript:false,
		internalStyle:false,
		width:600,
		height:250,
		fullscreen:false,
		sourceMode:false,
		forcePtag:true,
		upImgUrl:"upload.php",
		upImgExt:"jpg,jpeg,gif,png"
	});

</script>
</body>