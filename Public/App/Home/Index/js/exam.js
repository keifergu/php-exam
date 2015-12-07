
jQuery(document).ready(function(){
 	var num = 1;
 	var oldAnswer = 0;
 	var totalNum = $("#hid_totalNum").val();
 	var paperID = $("#hid_paperID").val();
 	showFrame();
 	getShowQuestion(num);
 	showNumberTag();
  	$("#btn-next").click(function() {
  		var num = parseInt($("#hid_nowNum").val())+1;
  		var nowAnswer = checkAnswer();
  		if (nowAnswer != oldAnswer) {  //检查选项是否发生改变
  			answer = nowAnswer;
  			var status = submitAnswer(answer,paperID,num-1);
  		};
  		if (num-1 >= totalNum) {return;};
  		$("#hid_nowNum").val(num);
  		getShowQuestion(num);
  		//getOldAnswer(paperID,num);
  	});
  	$("#btn-last").click(function(){
  		var num = parseInt($("#hid_nowNum").val())-1;
  		if (num+1 <= 1) {return;};
  		$("#hid_nowNum").val(num);
  		getShowQuestion(num);
  		//getOldAnswer(paperID,num);
  	});
  	$("#btn-submit").click(function () {
  		var num = parseInt($("#hid_nowNum").val());
  		var answer = checkAnswer();
  		var status=submitAnswer(answer,paperID,num);
  		window.location.assign(" index.php?m=&c=index&a=finish");
  	});
});
//showFrame ,show the input radio frame
function showFrame () {
	for (var i = 1; i < 9; i++) {
		var labelID = "option-label-"+i;
		var inputID= "option-input-"+i;
		var innerHTML= '<div class="col-xs-12 "><input type="radio" name="answer" value="'+(1<<i-1)+'" id="'+inputID+'"><label for="'+inputID+'" id="'+labelID+'" style="margin-top:12px;font-weight:100"></label></div>';
		//var innerHTML=' <div><label id="'+labelID+'"><input type="radio"  name="answer" value="'+(1<<i-1)+'">1</label><div>';
		$("#option").append(innerHTML);
	}
}
function showFrameTag (startNum,num) {
	for (var i = num; i >= 1; i--) {
		var nowNum = startNum+i-1;
		//$("#page_num_next").before('<button type="button" class="btn btn-default" id="page_num_'+i+'" value="'+nowNum+'">'+nowNum+'</button>');
		$("#page_num_last").parents("li").after('<li><a href="#" id="page_num_'+i+'" value="'+nowNum+'">'+nowNum+'</a></li>');
	};
}
function getShowQuestion( num ){
	$.post("index.php/home/index/question",
	{
		q:num,
		paperID:$("#hid_paperID").val()
	},
	function(data,status) {
		if (status == "success") {
			var inputType={'102':'checkbox','101':'radio','103':'radio'} ;
			$("#option input").attr("type",inputType[data.type]);
			$("#option input").hide();
			$("#option label").hide();
			$("#title").hide();
			$("#title").text(data.num+"."+data.title).fadeIn(500);
			for (var i = 1; i < 9; i++) {
				if (data.option[i-1]) {
					$("#option-label-"+i).fadeIn(i*200);
					$("#option-input-"+i).fadeIn(i*200);
					var option = String.fromCharCode(i+64);
					$("#option-label-"+i).text(option+"."+data["option"][i-1]);
				}else{
					$("#option-label-"+i).hide();
					$("#option-input-"+i).hide();
				}
			};
			getOldAnswer($("#hid_paperID").val(),num);
		};		
	});
	
	
}
function checkAnswer () {
	var answer = 0;
	//alert($(":checked").val());
	for (var i = 1; i < 9; i++) {
		var checkedRes = $("#option-input-"+i).prop("checked");
		if (checkedRes) {
			answer += parseInt($("#option-input-"+i).val());
		};
	};
	return answer;
}
function submitAnswer (answer,paperID,num) {
	$.post("index.php/home/index/submit",
  		{
  			num:num,
  			paperID:paperID,
  			answer:answer
  		},
  		function(data,status) {
  			return data;
  		});
}
function convertAnswer(argument) { //将单个数字解包为数组
	var answer = new Array();
	var res = 0;
	for (var i = 7; i >= 0; i--) {
		res = argument/(1<<i);
		if (res >= 1) {
			argument %= (1<<i);
			answer.push(i+1);
			if (argument == 0) {break;};
		};
	};
	return answer;
}
function getOldAnswer (paperID,num) {
	$.post("index.php/home/index/getOldAnswer",
  		{
  			num:num,
  			paperID:paperID
  		},
  		function(data,status) {
  			$("#option input:checked").prop("checked",false);    
  			data = convertAnswer(data);
  			for (var i = 0; i < data.length; i++) {
  				$("#option-input-"+data[i]).prop("checked",true);
  				$("#option-input-"+data[i]).show();
  			};
  		})
}
function showNumberTag () {
	var totalNum = parseInt($("#hid_totalNum").val());
	var eachTagNum = 4;
	var pageNum  = parseInt(totalNum/eachTagNum);  //the resalut is float,we need an intenger
	var leftNum  = totalNum%eachTagNum;
	var nowPage  = 1; 
	if (pageNum>=1) {
		showFrameTag(1,eachTagNum);
	}else{
		showFrameTag(1,leftNum);
	}
	$(document).ready(function(){
		$("#page_num_last").click(function () {
			if (nowPage == 1) {
				;
			}else{
				nowPage--;
				showEachPage((nowPage-1)*eachTagNum+1,eachTagNum);
			};
		});
		$("#page_num_next").click(function () {
			if (nowPage == pageNum+1 || leftNum == 0) {
				;
			}else if(nowPage == pageNum){
				nowPage++;
				showEachPage((nowPage-1)*eachTagNum+1,leftNum);
			}else{
				nowPage++;
				showEachPage((nowPage-1)*eachTagNum+1,eachTagNum);
			};
		});
		$("#page_num_1").click(function () {
			var num = $("#page_num_1").attr("value");
			getShowQuestion(num);
			$("#hid_nowNum").val(num)
		});
		$("#page_num_2").click(function () {
			var num = $("#page_num_2").attr("value");
			getShowQuestion(num);
			$("#hid_nowNum").val(num)
		});
		$("#page_num_3").click(function () {
			var num = $("#page_num_3").attr("value");
			getShowQuestion(num);
			$("#hid_nowNum").val(num)
		});
		$("#page_num_4").click(function () {
			var num = $("#page_num_4").attr("value");
			getShowQuestion(num);
			$("#hid_nowNum").val(num)
		});
	});
}

function showEachPage(startNum,num) {
	for (var i = 1; i <= num; i++) {
		var nowNum = startNum+i-1;
		$("#page_num_"+i).show();
		$("#page_num_"+i).attr("value",nowNum);
		$("#page_num_"+i).text(nowNum);
	}
	for (var i = num+1; i <= 4; i++) {
		$("#page_num_"+i).hide();
	};
}