<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <title>用户中心</title>

        <link    rel="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <script src ="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
        <script src ="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    </head>

<body>
    <div class="container">
        <div class="row">
            <div>
                  <!-- Nav tabs -->
                  <ul class="nav nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab">试卷信息</a></li>
                            <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">我的信息</a></li>
                            <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab">设置</a></li>
                  </ul>

                  <!-- Tab panes -->
                  <div class="tab-content" style="padding:0.5em">
                    <!-- 个人的试卷信息 -->
                            <div role="tabpanel" class="tab-pane active" id="home">
                            <span>正在进行的考试</span>
                                <div class="list-group">
                                    <?php if(is_array($testData)): foreach($testData as $key=>$vo): ?><a href="<?php echo ($vo["examUrl"]); ?>" class="list-group-item"><?php echo ($vo["testName"]); ?></a><?php endforeach; endif; ?>
                                </div>
                            <span>完成的试卷</span>
                                <div class="list-group">
                                    <?php if(is_array($finishPaper)): foreach($finishPaper as $key=>$vo): ?><a href="#" class="list-group-item"><?php echo ($vo["testName"]); ?></a><?php endforeach; endif; ?>
                                </div>
                            </div>

                    <!-- 用户中心 -->
                            <div role="tabpanel" class="tab-pane" id="profile">
                                <ul>
                                    <li><p>姓名：<?php echo ($user["name"]); ?></p></li>
                                    <li>
                                        <p>课程：</p>
                                        <ul>
                                            <?php if(is_array($user['course'])): foreach($user['course'] as $key=>$vo): ?><li><?php echo ($vo); ?></li><?php endforeach; endif; ?>
                                        </ul>
                                    </li>
                                </ul>
                            </div>

                    <!-- 设置 -->
                            <div role="tabpanel" class="tab-pane" id="settings">
                                    <div class="btn-group" role="group">
                                    <!-- 下拉按钮组 -->
                                        <div class="btn-group"> 
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                       添加课程 <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <?php if(is_array($course)): foreach($course as $k=>$vo): ?><li><a href="#"  value="<?php echo ($k); ?>" onclick="setCourse(<?php echo ($k); ?>)"><?php echo ($vo); ?></a></li><?php endforeach; endif; ?>
                                            </ul>
                                        </div>
                                    <!-- 删除按钮 -->
                                        <button type="button" class="btn btn-default" onclick="removeCourse()"><span>删除</span></button>
                                    </div>
                                    <!-- 课程显示 -->
                                        <div class="input-group">
                                            <?php if(is_array($user['course'])): foreach($user['course'] as $k=>$vo): ?><div class="checkbox">
                                                    <label><input type="checkbox" value="<?php echo ($k); ?>" name="courseCheck"><?php echo ($vo); ?></label>
                                                </div><?php endforeach; endif; ?>
                                        </div>
                            </div>
                  </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    function setCourse(course){
        $.post("index.php/home/index/set",
        {
            type:'course',
            data:course
        },
        function(data,status) {
            if (status == 'success' && data != false) {
                location.reload(true);
            }else if(data == false){
                alert("添加失败");
            }
        });
    };
    function removeCourse(){
        var courseVal = new Array();
        $("input[name='courseCheck']:checked").each(function(){
            //alert(this.value);
            courseVal.push(this.value); 
        });
       $.post("index.php/home/index/remove",
        {
            type:'course',
            data:courseVal
        },
        function(data,status) {
            if (status == 'success' && data != false) {
                location.reload(true);
            }else if(data == false){
                alert("添加失败");
            };
        });
    };
    $('#navTab li:eq(0)').click(function (e) {
      e.preventDefault();
      $(this).tab('show');
    });
    $('#navTab li:eq(1)').click(function (e) {
      e.preventDefault();
      $(this).tab('show');
    });
    $('#navTab li:eq(2)').click(function (e) {
      e.preventDefault();
      $(this).tab('show');
    });
    </script>
</body>
</html>