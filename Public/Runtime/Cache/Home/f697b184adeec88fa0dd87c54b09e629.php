<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<!--[if lt IE 7 ]><html lang="en" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]><html lang="en" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]><html lang="en" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]><html lang="en" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html lang="en" class="no-js"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<meta name="author" content="">
<meta name="keywords" content="">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?php echo ($paper["paper_name"]); ?></title>

<!-- main JS libs -->
<script src="/thinkphpcms/Public/ui-cream/js/libs/modernizr.min.js"></script>
<!-- <script src="http://cdn.bootcss.com/jquery/1.10.0/jquery.min.js"></script> -->
<script src="/thinkphpcms/Public/ui-cream/js/libs/jquery-1.10.0.min.js"></script>
<script src="/thinkphpcms/Public/ui-cream/js/libs/jquery-ui.min.js"></script>
<script src="/thinkphpcms/Public/ui-cream/js/libs/bootstrap.min.js"></script>

<!-- Style CSS -->
<link href="/thinkphpcms/Public/ui-cream/css/bootstrap.min.css" media="screen" rel="stylesheet">
<link href="/thinkphpcms/Public/ui-cream/style.css" media="screen" rel="stylesheet">

<!-- scripts -->
<script src="/thinkphpcms/Public/ui-cream/js/general.js"></script>

<!-- Include all needed stylesheets and scripts here -->
<script src="/thinkphpcms/Public/ui-cream/js/jquery.customInput.js"></script>
<script src="/thinkphpcms/Public/ui-cream/js/progressbar.js"></script>
<script src="/thinkphpcms/Public/App/Home/Index/js/exam.js"></script>
<!--[if lt IE 9]><script src="j
s/respond.min.js"></script><![endif]-->
<!--[if gte IE 9]>
<style type="text/css">
    .gradient {filter: none !important;}
</style>
<![endif]-->

</head>
 
<body>
<input type="hidden" id="hid_totalNum" value="<?php echo ($paper["question_num"]); ?>">
<input type="hidden" id="hid_paperID" value="<?php echo ($paper["paper_id"]); ?>">
<input type="hidden" id="hid_testTime" value="<?php echo ($paper["test_time"]); ?>">
<input type="hidden" id="hid_nowNum" value="1">
<div class="body_wrap">
<div class="container">
  <br><br>
  <h6><div id="text_title"></div></h6>
    <div class="input_styled inlinelist" id="class_input_list">
		<div class="rowRadio" id="class_input">
		<div id="div_span">
		<?php
 for ($i=1; $i < 9; $i++) { echo '<input name="answer" type="radio" id="input_'.$i.'" value="'.pow(2,$i-1).'">'; echo '<label for="input_'.$i.'" id="text_option_'.$i.'"><p></p></label>'; } ?> 
		</div>
		<a href="#" class="btn btn-left" id="btn_last"><span>上一题</span></a>
		<a href="#" class="btn btn-right" id="btn_next"><span>下一题</span></a>
	</div>
	<div id="page_tag">
		
		<a href="#" class="btn btn-right btn-green" id="page_num_next"><span>>></span></a>
	</div>
	<!-- <button onclick="showEachPage(1,4)">check</button> -->
</div>

</div>

</body>
</html>