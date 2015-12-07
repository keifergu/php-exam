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
<title>考试</title>

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
<!--[if lt IE 9]><script src="js/respond.min.js"></script><![endif]-->
<!--[if gte IE 9]>
<style type="text/css">
    .gradient {filter: none !important;}
</style>
<![endif]-->

</head>
 
<body>
<input type="hidden" id="questionType" value="<?php echo ($question['type']); ?>">
<input type="hidden" id="questionNum" value="<?php echo ($num); ?>">
<input type="hidden" id="paperID" value="<?php echo ($paperID); ?>">
  <div class="body_wrap">
  <div class="container">
  	<br><br>
  		<h6><div id="text_title"><?php echo ($question["num"]); ?>.<?php echo ($question["title"]); ?></div></h6>
    	<form method="post" action="<?php echo ($url["next"]); ?>" id="form_0">

	  		<?php switch($question['type']): case "101": case "103": ?><div class="input_styled inlinelist">
						<div class="rowRadio">
							<?php if(is_array($question['option'])): $i = 0; $__LIST__ = $question['option'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($i == $question['old_answer']) ): ?><input name="answer" type="radio" id="input_<?php echo ($i); ?>" value="<?php echo ($i); ?>"  checked>
									<label for="input_<?php echo ($i); ?>" id="text_option_<?php echo ($i); ?>"><p><?php echo ($key); ?>.<?php echo ($vo); ?></p></label>
								<?php else: ?>
			    					<input name="answer" type="radio" id="input_<?php echo ($i); ?>" value="<?php echo ($i); ?>"  >
									<label for="input_<?php echo ($i); ?>" id="text_option_<?php echo ($i); ?>"><p><?php echo ($key); ?>.<?php echo ($vo); ?></p></label><?php endif; endforeach; endif; else: echo "" ;endif; ?>
						</div>
					</div><?php break;?>
				
				<?php case "102": ?><div class="input_styled checklist">
						<div class="rowCheckbox">
							<?php if(is_array($question['option'])): $i = 0; $__LIST__ = $question['option'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(in_array(($i), is_array($question['old_answer'])?$question['old_answer']:explode(',',$question['old_answer']))): ?><input name="answer" type="checkbox" id="input_<?php echo ($i); ?>" value="<?php echo ($i); ?>" checked>
									<label for="input_<?php echo ($i); ?>" id="text_option_<?php echo ($i); ?>"><?php echo ($key); ?>.<?php echo ($vo); ?></label>
								<?php else: ?>
									<input name="answer" type="checkbox" id="input_<?php echo ($i); ?>" value="<?php echo ($i); ?>">
									<label for="input_<?php echo ($i); ?>" id="text_option_<?php echo ($i); ?>"><?php echo ($key); ?>.<?php echo ($vo); ?></label><?php endif; endforeach; endif; else: echo "" ;endif; ?>
						</div>
					</div><?php break;?>
				
				<?php default: ?>	<p>题目不存在</p><?php endswitch;?>
				
			<?php switch($url['status']): case "first": ?><span class="btn btn-right"><input type="submit" id="submit" value="下一题" /></span><?php break;?>
				<?php case "end": ?><a href="<?php echo ($url["last"]); ?>" class="btn btn-left"><span>上一题</span></a>
					<span class="btn btn-hover" hidefocus="true" style="outline: none;"><input type="submit" value="交卷" /></span><?php break;?>
				<?php default: ?>
					<a href="<?php echo ($url["last"]); ?>" class="btn btn-left"><span>上一题</span></a>
					<span class="btn btn-right"><input type="submit" id="submit" value="下一题" /></span><?php endswitch;?>

  		</form>
  	<button id="form11">ss</button>
  </div>
  
  </div>
  <script type="text/javascript">
 $(document).ready(function(){
  	$("#submit").click(function() {

  		/*$.post("index.php/home/index/question",
  		{
  			q:$("#questionNum").val();
  			paperID:$("#paperID").val();
  		}
  		function(data,status) {
  			alert(data+"\n"+status);
  		}
  		);*/
  	});
  	$("#form11").click(function(){
  		var num = $("#questionNum").val();
  		var nextNum = parseInt(num)+1;
  		$.post("index.php/home/index/question",
  		{
  			q:nextNum,
  			paperID:$("#paperID").val()
  		},
  		function(data,status) {
  			if (status == "success") {
  				$("#questionNum").val(nextNum);
	  			$("#text_title").text(data.num+"."+data.title);
	  			for (var i = 1; i < 9; i++) {
	  				var option = String.fromCharCode(i+64);
	  				$("#text_option_"+i).html(option+"."+data["option"][i-1]);		
	  			};
  			};
  			
  		});
  	});

});
  </script>
</body>
</html>