<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <title><?php echo ($paper["paper_name"]); ?></title>

        <link   rel ="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <script src ="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
        <script src ="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script src ="//cdn.bootcss.com/jquery-cookie/1.4.1/jquery.cookie.js"></script>
    </head>


<body>
    <div>
        <input type="hidden" id="hid_nowNum" value="1">
    </div>
    
        
<div class="container-fluid">
    <div class="row">
      <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container" ><div class="row">
            <div class="navbar-header col-xs-2">
                <div class="navbar-brand" style="margin-left:-1em">
               <a href="index.php?m=&a=user"><img alt="Brand" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAMAAAC7IEhfAAAA81BMVEX///9VPnxWPXxWPXxWPXxWPXxWPXxWPXz///9hSYT6+vuFc6BXPn37+vz8+/z9/f2LeqWMe6aOfqiTg6uXiK5bQ4BZQX9iS4VdRYFdRYJfSINuWI5vWY9xXJF0YJR3Y5Z4ZZd5ZZd6Z5h9apq0qcW1qsW1q8a6sMqpnLyrn76tocCvpMGwpMJoUoprVYxeRoJjS4abjLGilLemmbrDutDFvdLPx9nX0eDa1OLb1uPd1+Td2OXe2eXh3Ofj3+nk4Orl4evp5u7u7PLv7fPx7/T08vb08/f19Pf29Pj39vn6+fuEcZ9YP35aQn/8/P1ZQH5fR4PINAOdAAAAB3RSTlMAIWWOw/P002ipnAAAAPhJREFUeF6NldWOhEAUBRvtRsfdfd3d3e3/v2ZPmGSWZNPDqScqqaSBSy4CGJbtSi2ubRkiwXRkBo6ZdJIApeEwoWMIS1JYwuZCW7hc6ApJkgrr+T/eW1V9uKXS5I5GXAjW2VAV9KFfSfgJpk+w4yXhwoqwl5AIGwp4RPgdK3XNHD2ETYiwe6nUa18f5jYSxle4vulw7/EtoCdzvqkPv3bn7M0eYbc7xFPXzqCrRCgH0Hsm/IjgTSb04W0i7EGjz+xw+wR6oZ1MnJ9TWrtToEx+4QfcZJ5X6tnhw+nhvqebdVhZUJX/oFcKvaTotUcvUnY188ue/n38AunzPPE8yg7bAAAAAElFTkSuQmCC" height="28" width="28"></img></a>
                </div>
            </div> <!-- /.navbar-header -->
            <div class="col-xs-8" style="text-align:center;margin-top:0.6em;margin-left:-1em;">
              <h4>
              <span id="t_h"></span>
              <span id="t_m"></span>
              <span id="t_s"></span></h4>
            </div>
            <div class="col-xs-2"></div>
        </div></div><!-- /.container-fluid -->
      </nav>
    </div><!-- /.row -->
    <div class="row">
      <div class="col-xs-12  col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2" style="margin-top:4em">

                <div class="row">
                    <h4><div class="col-xs-12" id="title" ></div></h4>
                </div>

                <h4><form class="row" id="option" style="margin-top:8px;font-weight:400">

                </form></h4>
                <br><br><br><br><br><br><br>
                <nav class="nav navbar-fixed-bottom">
                  <ul class="pager" style="font-size:19px;margin-bottom:2.7em">
                    <li class=""><a href="#" id="btn-last">上一题</a></li>
                    <li><a href="#" id="btn-submit">交卷</a></li>    
                    <li><a href="#" id="btn-next">下一题</a></li>
                  </ul>
                </nav>
                <nav class="navbar navbar-default navbar-fixed-bottom" style="margin-bottom:-1.85em">
                      <div style="text-align:center;margin-top:-1.45em">
                             <ul class="pagination pagination-lg" style="">
                                   <li>
                                    <a href="#" id="page_num_last">
                                      <span>&laquo;</span>
                                  </a>
                                   </li>
                                   <li>
                                <a href="#" id="page_num_next">
                                    <span>&raquo;</span>
                                </a>
                                   </li>
                               </ul>
                       </div>
                </nav>
      </div><!-- /.cox-xs-12 -->
    </div><!-- ./row -->
</div><!-- /.container-fluid -->
<script type="text/javascript">
//获取保存试卷信息的cookie
var paperinfo = $.cookie('paperinfo'); 
paperinfo = JSON.parse(paperinfo.slice(6)); //由于thinkphp保存cookie的不规范,所以需要将前6个字符裁剪掉才为标准的json字符串
paperinfo.nowNum = 1;

paperinfo['start_time'] = unescape(paperinfo['start_time']).replace('+',' ').replace(/\-/g, "/");
paperinfo['total_time'] = unescape(paperinfo['total_time']);
alert(paperinfo['start_time']);
alert(paperinfo['total_time']);



  $(document).ready(function(){
    //加载主体框架
    showFrame();
    getShowQuestion(1);
    showNumberTag();

    var oldAnswer = 0;
    var paperID = paperinfo.paper_id;
    var totalNum = paperinfo.total_num;
      $("#btn-next").click(function() {
        var num = paperinfo.nowNum+1;
        var nowAnswer = checkAnswer();
        if (nowAnswer != oldAnswer) {  //检查选项是否发生改变
          answer = nowAnswer;
          var status = submitAnswer(answer,paperID,num-1);
        };
        if (num-1 >= totalNum) {
          alert('已经是最后一题,请提交答案');
          return;};
        paperinfo.nowNum = num;
        getShowQuestion(num);
      });
      $("#btn-last").click(function(){
        var num = paperinfo.nowNum-1;
        if (num+1 <= 1) {return;};
        paperinfo.nowNum = num;
        getShowQuestion(num);
      });
      $("#btn-submit").click(function () {
        var answer = checkAnswer();
        var status=submitAnswer(answer,paperID,paperinfo.nowNum);
        window.location.assign("index.php?m=&c=index&a=finish"+"&paperID="+paperID);
      });
  });

    function showNumberTag () {
    var totalNum = parseInt(paperinfo.total_num);
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
        }else{
          nowPage--;
          showEachPage((nowPage-1)*eachTagNum+1,eachTagNum);
        };
      });
      $("#page_num_next").click(function () {
        if (nowPage == pageNum+1 || leftNum == 0) {
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
        paperinfo.nowNum = parseInt(num);
      });
      $("#page_num_2").click(function () {
        var num = $("#page_num_2").attr("value");
        getShowQuestion(num);
        paperinfo.nowNum = parseInt(num);
      });
      $("#page_num_3").click(function () {
        var num = $("#page_num_3").attr("value");
        getShowQuestion(num);
        paperinfo.nowNum = parseInt(num);
      });
      $("#page_num_4").click(function () {
        var num = $("#page_num_4").attr("value");
        getShowQuestion(num);
        paperinfo.nowNum = parseInt(num);
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

  $(document).ready(function () {
    
    function getRTime(){
            var StartTime = new Date(paperinfo['start_time']); //开始时间
            var TotalTime = new Date('0000/00/00 '+paperinfo['total_time']);
            var NowTime = new Date();
            var totalMs = TotalTime.getHours()*1000*60*60+TotalTime.getMinutes()*1000*60+TotalTime.getSeconds()*1000;
            var t =totalMs - (NowTime.getTime() - StartTime.getTime());
            /*var d=Math.floor(t/1000/60/60/24);
            t-=d*(1000*60*60*24);
            var h=Math.floor(t/1000/60/60);
            t-=h*60*60*1000;
            var m=Math.floor(t/1000/60);
            t-=m*60*1000;
            var s=Math.floor(t/1000);*/

            //var d=Math.floor(t/1000/60/60/24);
            var h=Math.floor(t/1000/60/60%24);
            var m=Math.floor(t/1000/60%60);
            var s=Math.floor(t/1000%60);

            //document.getElementById("t_d").innerHTML = d + "天";
            document.getElementById("t_h").innerHTML = h + ":";
            document.getElementById("t_m").innerHTML = m + ":";
            document.getElementById("t_s").innerHTML = s ;
        }
    setInterval(getRTime,1000);
    
  })
  //showFrame ,show the input radio frame
  function showFrame () {
    for (var i = 1; i < 9; i++) {
      var labelID = "option-label-"+i;
      var inputID= "option-input-"+i;
      var innerHTML= '<div class="col-xs-12" style="margin-top:0.5em"><input type="radio" name="answer" class="col-xs-1" style="" value="'+(1<<i-1)+'" id="'+inputID+'"><label for="'+inputID+'" id="'+labelID+'" class="col-xs-11" style="padding-left:4px;padding-right:4px;font-weight:100;"></label></div>';
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
      paperID:paperinfo.paper_id
    },
    function(data,status) {
      if (status == "success") {
        var inputType={'102':'checkbox','101':'radio','103':'radio'} ;
        $("#option input").attr("type",inputType[data.type]);
        $("#option input").hide();
        $("#option label").hide();
        $("#title").hide();
        $("#title").text(data.num+"．"+data.title).fadeIn(500);
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
        getOldAnswer(paperinfo.paper_id,num);
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

</script>
</body>
</html>