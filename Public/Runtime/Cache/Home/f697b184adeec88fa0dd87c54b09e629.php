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
      <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container" ><div class="row">
            <div class="navbar-header col-xs-2">
                <div class="navbar-brand" style="margin-left:-1em">
               <a href="index.php?m=&a=user"><img alt="Brand" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACgAAAAoCAMAAAC7IEhfAAAA81BMVEX///9VPnxWPXxWPXxWPXxWPXxWPXxWPXz///9hSYT6+vuFc6BXPn37+vz8+/z9/f2LeqWMe6aOfqiTg6uXiK5bQ4BZQX9iS4VdRYFdRYJfSINuWI5vWY9xXJF0YJR3Y5Z4ZZd5ZZd6Z5h9apq0qcW1qsW1q8a6sMqpnLyrn76tocCvpMGwpMJoUoprVYxeRoJjS4abjLGilLemmbrDutDFvdLPx9nX0eDa1OLb1uPd1+Td2OXe2eXh3Ofj3+nk4Orl4evp5u7u7PLv7fPx7/T08vb08/f19Pf29Pj39vn6+fuEcZ9YP35aQn/8/P1ZQH5fR4PINAOdAAAAB3RSTlMAIWWOw/P002ipnAAAAPhJREFUeF6NldWOhEAUBRvtRsfdfd3d3e3/v2ZPmGSWZNPDqScqqaSBSy4CGJbtSi2ubRkiwXRkBo6ZdJIApeEwoWMIS1JYwuZCW7hc6ApJkgrr+T/eW1V9uKXS5I5GXAjW2VAV9KFfSfgJpk+w4yXhwoqwl5AIGwp4RPgdK3XNHD2ETYiwe6nUa18f5jYSxle4vulw7/EtoCdzvqkPv3bn7M0eYbc7xFPXzqCrRCgH0Hsm/IjgTSb04W0i7EGjz+xw+wR6oZ1MnJ9TWrtToEx+4QfcZJ5X6tnhw+nhvqebdVhZUJX/oFcKvaTotUcvUnY188ue/n38AunzPPE8yg7bAAAAAElFTkSuQmCC" height="28" width="28"></img></a>
                </div>
            </div> <!-- /.navbar-header -->
            <div class="col-xs-8" style="text-align:center;margin-top:0.6em;margin-left:-1em;"><h4>00:00:00</h4></div>
            <div class="col-xs-2"></div>
        </div></div><!-- /.container-fluid -->
      </nav>
    </div><!-- /.row -->
    <div class="row">
      <div class="col-xs-12  col-sm-10 col-sm-offset-1 col-md-8 col-md-offset-2" style="padding-left:4%;padding-right:4%;margin-top:4em">

                <div class="row">
                    <h4><div class="col-xs-12" id="title" ></div></h4>
                </div>

                <h4><form class="row" id="option" style="margin-top:8px;font-weight:400">

                </form></h4>
                <br><br><br><br><br><br><br>
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

</body>
</html>