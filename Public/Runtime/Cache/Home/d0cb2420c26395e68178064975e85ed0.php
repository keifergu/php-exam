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
<title>用户中心</title>

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
<script src="/thinkphpcms/Public/ui-cream/js/jquery.customInput.js"></script>
<script type="text/javascript" src="/thinkphpcms/Public/ui-cream/js/custom.js"></script>
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
      <div class="tabs_framed styled">
        <div class="inner">
          <ul class="tabs clearfix active_bookmark1">
              <li class="active"><a href="#exam" data-toggle="tab">考试信息</a></li>
              <li><a href="#user" data-toggle="tab">用户中心</a></li>
          </ul>

          <div class="tab-content clearfix">
            <div class="tab-pane fade in active" id="exam">
                <!--place your content here-->
                <div class="tagcloud style3 margin-10">
                    <a href="#" class="tag-link-7" hidefocus="true" style="outline: none;"><span>当前考试科目</span></a>
                </div>
                <div class="list-group">
                  <?php if(is_array($testData)): foreach($testData as $key=>$vo): ?><a href="<?php echo ($vo["examUrl"]); ?>" class="list-group-item"><?php echo ($vo["testName"]); ?></a><?php endforeach; endif; ?>
                </div>
                <div class="tagcloud style3 margin-10">
                    <a href="#" class="tag-link-7" hidefocus="true" style="outline: none;"><span>Finish Paper</span></a>
                </div>
                <div class="list-group">
                  <?php if(is_array($finishPaper)): foreach($finishPaper as $key=>$vo): ?><a href="" class="list-group-item"><?php echo ($vo["testName"]); ?></a><?php endforeach; endif; ?>
                </div>
            </div>
            <div class="tab-pane fade" id="user">
                <!--place your content here-->
            
          </div>
        </div>
    </div>
  </div>
</body>
</html>