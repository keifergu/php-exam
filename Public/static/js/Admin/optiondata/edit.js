if(typeof OPTION_TYPE_ENUM == "undefined"){
	　　var OPTION_TYPE_ENUM = {
		SINGLE_OPTION:"101",
		MULTI_OPTION:"102",
		TRUE_FALSE_OPTION:"103",　　　　　　    
	　　}
}


$(function() {
	getData();
});
function getData(){
	$.post('index.php?m=admin&c=Optiondata&a=getOptionData',{
		id:$("#hidden_option_id").val()
	},function(data){
		$("*[name=option_title]").val(data['title']);
		$("#hidden_option_course").val(data['course_id']);
		$("#hidden_option_type").val(data['type']);
		$("#answerserial").val(data['answer']);
		$('#option_t').combobox('setValue',data['type']);
		var pType=$('#option_t').combobox('getValue'); 
		switch(data['type']){
			case '102':
			$("*[name='optionE']").val(data['e']);
			$("*[name='optionF']").val(data['f']);
			$("*[name='optionG']").val(data['g']);
			$("*[name='optionH']").val(data['h']);
			case '101':
			$("*[name='optionC']").val(data['c']);
			$("*[name='optionD']").val(data['d']);
			case '103': 
			$("*[name='optionA']").val(data['a']);
			$("*[name='optionB']").val(data['b']);
			break;	
		}
		$("#answerserial").val(data['answer']);
		if(data['type']=='101'||data['type']=='103'){
			var answerid="answer"+data['answer'];
			$("#"+answerid).attr("checked","true");
		}else if(data['type']=='102'){
			var tempans=data['answer'];
			for(var i=7;i>=0;i--){
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
							val=Number(el[i].value);
							res=res+val;	
						}
					}
					if (el[i].type=="radio"  ) {
						if (el[i].checked==true){
							val=Number(el[i].value);
							res=val;
						}
					}
				}
				if (document.getElementById("answerserial")){
					var target=document.getElementById("answerserial");
					target.value=res;
				}
			}
			catch(err)
			{
				txt="There was an error on this page.\n\n";
				txt+="Error description: " + err.message +i+ "\n\n";
				txt+="Click OK to continue.\n\n";
				alert(txt);
			}  	 
		}
		function onselect(){
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
			optionNum=8;
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
       		for (var i = 1; i <=8; i++) {
           		 //让所有选项出现
           		 target="hide"+i;
           		 showElement(target,true);
           		 target="hideanswer"+i;
           		 showElement(target,true);
           		 target="answer"+i;
           		 modifyCheckBoxArr(target,checkType);
           		}

           		for (var i = optionNum+1; i <=8; i++) {
        		//多余选项隐藏
        		target="hide"+i;
        		showElement(target,false);
        		target="hideanswer"+i;
        		showElement(target,false);
        	}
        }
		//修改选项类型
		function modifyCheckBoxArr(targetid,arrVal){	
			if (document.getElementById(targetid)){
				target=document.getElementById(targetid);
				target.type=arrVal;
			}
		}
	//控制显示
	function showElement(targetid,state){
		if (document.getElementById(targetid)){
			target=document.getElementById(targetid);
			if (state==false && target.style.display=="") {
				target.style.display="none"; 
			}
			if (state==true && target.style.display=="none"){
				target.style.display=""; 
			} 
		}
	}