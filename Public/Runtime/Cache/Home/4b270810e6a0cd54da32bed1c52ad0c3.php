<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>试卷详情</title>
<link    rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script src ="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
<script src ="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
 
<body>
    <div class="container">
        <div class="row">
            <div class="col-sm-10 col-sm-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading" style="text-align:center"><?php echo ($paper['paper_name']); ?></div>
                    <div class="panel-body" style="">
                        <ul>
                            <li><p>所属课程：<?php echo ($paper["course_name"]); ?></p></li>
                            <li><p>试题总分：<?php echo ($paper["total_grade"]); ?></p></li>
                            <li><p>试卷题数：<?php echo ($paper["question_num"]); ?></p></li>
                            <li><p>考试时间：<?php echo ($paper["test_time"]); ?></p></li>
                            <li><p>截止日期：<?php echo ($paper["deadline"]); ?></p></li>
                        </ul> 
                    </div>
                    <div class="panel-footer" style="text-align:center">
                        <a class="btn btn-default" href="<?php echo ($paper["url"]); ?>" role="button">开始考试</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
</html>