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
      <div class="widget-container widget_login styled boxed boxed-cream">
      <div class="inner">
            <div class="jumbotron">
              <p>欢迎进入考试系统</p> 
              <p>请登录账号</p> 
            </div>
        <form  method="post" class="loginform">
            <div class="field_text">
                <label for="user_login2" class="label_title">账号</label>
                <input type="text" name="username" id="user_login2" value="" placeholder="账号" />
                <span class="input_icon input_email"></span>
            </div>
            <div class="field_text">
                <label for="user_pass2" class="label_title">密码</label>
                <input type="password" name="password" id="user_pass2" value="" placeholder="密码" />
                <span class="input_icon input_pass"></span>
            </div>
            <div class="rowRemember clearfix">
                <div class="forgetmenot input_styled checklist">
                    <div class="rowCheckbox checkbox-middle">
                        <input type="checkbox" name="remember2" id="remember2" value="" checked>
                        <label for="remember2">记住密码？</label>
                    </div>
                </div>
                <span class="forget_password"><a href="#">Forgot Password?</a></span>
            </div>
            <div class="rowSubmit">
                <span class="btn btn-hover">
                    <input type="submit" name="login-submit2" id="login-submit2" value="登录" />
                </span>
            </div>
        </form>
    </div>
</div>
  	
    </div>
  </div>
</body>
</html>