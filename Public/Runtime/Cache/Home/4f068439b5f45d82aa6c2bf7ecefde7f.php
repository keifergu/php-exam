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
<title>试卷详情</title>

<!-- main JS libs -->
<script src="/thinkphpcms/Public/ui-cream/js/libs/modernizr.min.js"></script>
<script src="/thinkphpcms/Public/ui-cream/js/libs/jquery-1.10.0.js"></script>
<script src="/thinkphpcms/Public/ui-cream/js/libs/jquery-ui.min.js"></script>
<script src="/thinkphpcms/Public/ui-cream/js/libs/bootstrap.min.js"></script>

<!-- Style CSS -->
<link href="/thinkphpcms/Public/ui-cream/css/bootstrap.css" media="screen" rel="stylesheet">
<link href="/thinkphpcms/Public/ui-cream/style.css" media="screen" rel="stylesheet">

<!-- scripts -->
<script src="/thinkphpcms/Public/ui-cream/js/general.js"></script>

<!-- Include all needed stylesheets and scripts here -->
<script type="text/javascript" src="/thinkphpcms/Public/ui-cream/js/jquery.powerful-placeholder.min.js"></script>
<!--[if lt IE 9]><script src="js/respond.min.js"></script><![endif]-->
<!--[if gte IE 9]>
<style type="text/css">
    .gradient {filter: none !important;}
</style>
<![endif]-->
</head>
 
<body>
  <div class="body_wrap">
  <div class="container">
	    <div class="row">
            <div class="col-sm-10 col-sm-offset-1">

                <!-- pricing-->
                <div class="pricing_box price_style1 clearfix">
                    <ul>

                        <li class="price_col">
                            <!--pricing item-->
                            <div class="price_item">
                                <div class="inner">
                                    <div class="badge style2"></div>
                                    <div class="price_col_head">
                                        <span class="price"><span>试卷详情</span></span>
                                    </div>
                                    <div class="price_col_body clearfix">
                                        <div class="price_body_inner">
                                            <div class="price_body_top">
                                                <span><h5><?php echo ($paper['paper_name']); ?></h5></span>
                                            </div>
                                            <ul >
                                            	<p>所属课程：<?php echo ($paper["course_name"]); ?></p>
                                                <p>试题总分：<?php echo ($paper["total_grade"]); ?></p>
                                                <p>试卷题数：<?php echo ($paper["question_num"]); ?></p>
                                                <p>考试时间：<?php echo ($paper["test_time"]); ?></p>
                                                <p>截止日期：<?php echo ($paper["deadline"]); ?></p>
                                                <br><br>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="price_col_foot">
                                        <div class="sign_up"><a href="<?php echo ($paper["url"]); ?>" class="btn"><span>开始考试</span></a></div>
                                    </div>
                                </div>
                            </div>
                            <!--/ pricing-item -->
                        </li>

                    </ul>
                </div>
                <!--/ pricing-->
            </div>
        </div>
        </div>
  </div>
</body>
</html>