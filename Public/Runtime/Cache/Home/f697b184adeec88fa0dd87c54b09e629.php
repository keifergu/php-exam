<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <title><?php echo ($paper["paper_name"]); ?></title>

        <link    rel   ="stylesheet" href="//cdn.bootcss.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <script src ="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
        <script src ="//cdn.bootcss.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script src="/thinkphpcms/Public/App/Home/Index/js/exam.js"></script>
    </head>


<body>
    <div>
        <input type="hidden" id="hid_totalNum" value="<?php echo ($paper["question_num"]); ?>">
        <input type="hidden" id="hid_paperID" value="<?php echo ($paper["paper_id"]); ?>">
        <input type="hidden" id="hid_nowNum" value="1">
    </div>
    <div class="container">
        <div class="row">
            <div class="col-xs-12  col-sm-offset-4 col-md-offset-4" style="padding-left:4%;padding-right:4%">
                <div class="row">
                    <div class="col-xs-12" id="title" ></div>
                </div>
                <form class="row" id="option" >
              
                </form>
                <nav class="nav navbar-fixed-bottom">
                  <ul class="pager">
                    <li class=""><a href="#" id="btn-last">上一题</a></li>
                    <li><a href="#" id="btn-submit">交卷</a></li>    
                    <li><a href="#" id="btn-next">下一题</a></li>
                  </ul>
                  <div  style="text-align:center;margin-top:-2em;margin-bottom:-1em">
                         <ul class="pagination">
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
            </div>
    </div>
    </div>
    </div>
</body>
</html>