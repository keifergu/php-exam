
jQuery(document).ready(function(){
 	var num = 1;
 	var oldAnswer = 0;
 	var totalNum = $("#hid_totalNum").val();
 	var paperID = $("#hid_paperID").val();
 	//showFrame();
 	getShowQuestion(num);
 	showNumberTag();
  	$("#btn_next").click(function() {
  		var num = parseInt($("#hid_nowNum").val())+1;
  		var nowAnswer = checkAnswer();
  		if (nowAnswer != oldAnswer) {  //检查选项是否发生改变
  			answer = convertAnswer(nowAnswer);
  			var status = submitAnswer(answer,paperID,num-1);
  		};
  		if (num-1 >= totalNum) {return;};
  		$("#hid_nowNum").val(num);
  		getShowQuestion(num);
  		getOldAnswer(paperID,num);
  	});
  	$("#btn_last").click(function(){
  		var num = parseInt($("#hid_nowNum").val())-1;
  		if (num+1 <= 1) {return;};
  		$("#hid_nowNum").val(num);
  		getShowQuestion(num);
  		getOldAnswer(paperID,num);
  	});
});
function showFrame () {
	for (var i = 1; i < 9; i++) {
		$("#div_span").append('<div class="custom-radio"><input name="answer" type="radio" id="input_'+i+'" value="'+i+'" hidefocus="true" class="gradient" style="outline: none; display: inline-block;"><label for="input_'+i+'" id="text_option_'+i+'"><p></p></label></div>');
		//$("#div_span").append('<label for="input_'+i+'" id="text_option_'+i+'"></label></div>');
		//$("#div_span").append('<input name="answer" type="radio" id="input_'+i+'" value="'+(1<<i)+'">');
		//$("#div_span").append('<label for="input_'+i+'" id="text_option_'+i+'"><p></p></label>');
	}
}
function getShowQuestion( num ){
	/*var num = $("#questionNum").val();
	var nextNum = parseInt(num)+1;*/
	$.post("index.php/home/index/question",
	{
		q:num,
		paperID:$("#hid_paperID").val()
	},
	function(data,status) {
		if (status == "success") {
			var classType = "rowRadio";
			var classList = "input_styled inlinelist";
			var inputType = "radio";
			switch(data.type){
				case "102":
					classType = "rowCheckbox";
					classList = "input_styled checklist";
					inputType = "checkbox";
					$("div.custom-radio").attr("class","custom-"+inputType);
					break;
				default:
					classType = "rowRadio";
					classList = "input_styled inlinelist";
					inputType = "radio";
					$("div.custom-checkbox").attr("class","custom-"+inputType);
					break;
			}
			$("#class_input").attr("class",classType);
			$("#class_input_list").attr("class",classList);
			$("#text_title").text(data.num+"."+data.title);
			for (var i = 1; i < 9; i++) {
				if (data != null && data.option[i-1]) {
					$("#input_"+i).show();
					$("#input_"+i).attr("type",inputType);
					$("#text_option_"+i).show();
					var option = String.fromCharCode(i+64);
					$("#text_option_"+i).html(option+"."+data["option"][i-1]);
				}else{
					$("#input_"+i).hide();
					$("#text_option_"+i).hide();
				}
			};
		};		
	});
	getOldAnswer($("#hid_paperID").val(),num);
}
function checkAnswer () {
	var answer = 0;
	for (var i = 1; i < 9; i++) {
		var checkedRes = $("#text_option_"+i).hasClass("checked");
		if (checkedRes) {
			answer += parseInt($("#input_"+i).val());
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
	return answer.join(";");
}
function getOldAnswer (paperID,num) {
	$.post("index.php/home/index/getOldAnswer",
  		{
  			num:num,
  			paperID:paperID
  		},
  		function(data,status) {
  			for (var i = 1; i < 9; i++) {
  				$("#text_option_"+i).removeClass("checked");
  				if(data != null && data.indexOf(i)!= -1){
  					$("#text_option_"+i).addClass("checked");
  				}
  			};
  		})
}
function showNumberTag () {
	var totalNum = parseInt($("#hid_totalNum").val());
	var pageNum  = parseInt(totalNum/4);
	var leftNum  = totalNum%4;
	var nowPage  = 1; 
	if (pageNum>=1) {
		showPage(1,4);
	}else{
		showPage(1,leftNum);
	}
	$(document).ready(function(){
		$("#page_num_last").click(function () {
			if (nowPage == 1) {
				;
			}else{
				nowPage--;
				showEachPage((nowPage-1)*4+1,4);
			};
		});
		$("#page_num_next").click(function () {
			if (nowPage == pageNum+1) {
				;
			}else if(nowPage == pageNum){
				nowPage++;
				showEachPage((nowPage-1)*4+1,leftNum);
			}else{
				nowPage++;
				showEachPage((nowPage-1)*4+1,4);
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
function showPage (startNum,num) {
	$("#page_num_next").before('<a href="#" class="btn btn-left btn-green" id="page_num_last"><span><<</span></a>');
	for (var i = 1; i <= num; i++) {
		var nowNum = startNum+i-1;
		$("#page_num_next").before('<a href="#" class="btn btn-acute" id="page_num_'+i+'" value="'+nowNum+'"><span id="span_page_'+i+'">'+nowNum+'</span></a>');
	};
}
function showEachPage(startNum,num) {
	for (var i = 1; i <= num; i++) {
		var nowNum = startNum+i-1;
		$("#page_num_"+i).show();
		$("#page_num_"+i).attr("value",nowNum);
		$("#span_page_"+i).text(nowNum);
	}
	for (var i = num+1; i <= 4; i++) {
		$("#page_num_"+i).hide();
	};
}