<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<head>
<meta charset="utf-8">
<meta name="author" content="">
<meta name="keywords" content="">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no,maximum-scale=1,">
<title><?php echo ($paper["paper_name"]); ?></title>

<link rel   ="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script src ="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src ="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="/thinkphpcms/Public/App/Home/Index/js/exam.js"></script>
<link rel="stylesheet" type="text/css" href="/thinkphpcms/Public/App/Home/Index/css/windows8.css">
</head>
 
<body>

<input type="hidden" id="hid_totalNum" value="<?php echo ($paper["question_num"]); ?>">
<input type="hidden" id="hid_paperID" value="<?php echo ($paper["paper_id"]); ?>">
<input type="hidden" id="hid_nowNum" value="1">
  <div class="containter-fluid">
    <div id="title" class="col-xs-12">
    </div>
    <button onclick="asd()">w</button>
    <form id="option" class="">

    </form>
    
    <div class="btn-group btn-group-default col-xs-12" role="group" >
      <button type="button" class="btn btn-default" id="btn-last"> Last</button>
      <button type="button" class="btn btn-default" id="btn-submit">Submit</button>
      <button type="button" class="btn btn-default" id="btn-next">Next</button>
    </div>      

    <div class="btn-group btn-group-default col-xs-12" role="group" >
      <button type="button" class="btn btn-default" id="page_num_last">
        <span class="glyphicon glyphicon-triangle-left" aria-hidden="true"  ></span>
      </button>
      <button type="button" class="btn btn-default " id="page_num_next">
        <span class="glyphicon glyphicon-triangle-right" aria-hidden="true"></span>
      </button> 
    </div>
</body>
</html>