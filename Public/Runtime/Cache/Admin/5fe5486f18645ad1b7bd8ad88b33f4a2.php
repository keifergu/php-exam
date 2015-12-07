<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<title>提示信息</title>
<script type="text/javascript" src="/thinkphpcms/Public/static/js/jquery.min.js"></script>
<script type="text/javascript" src="/thinkphpcms/Public/static/js/jquery.cookie.js"></script>
<script type="text/javascript" src="/thinkphpcms/Public/static/js/jquery.json.min.js"></script>
<script type="text/javascript" src="/thinkphpcms/Public/static/js/easyui/jquery.easyui.min.js"></script>
<script type="text/javascript" src="/thinkphpcms/Public/static/js/easyui/locale/easyui-lang-zh_CN.js"></script>
<script type="text/javascript" src="/thinkphpcms/Public/static/js/easyui/jquery.edatagrid.js"></script>
<script type="text/javascript" src="/thinkphpcms/Public/static/js/easyui/datagrid-detailview.js"></script>
<script type="text/javascript" src="public/xheditor/xheditor-1.2.1.min.js" ></script>
<link rel="stylesheet" type="text/css" href="/thinkphpcms/Public/static/css/icons.css" />
<link rel="stylesheet" type="text/css" href="/thinkphpcms/Public/static/js/easyui/themes/default/easyui.css" title="default" />
<link rel="stylesheet" type="text/css" href="/thinkphpcms/Public/static/js/easyui/themes/gray/easyui.css" title="gray" />
<link rel="stylesheet" type="text/css" href="/thinkphpcms/Public/static/js/easyui/themes/bootstrap/easyui.css" title="bootstrap" />
<link rel="stylesheet" type="text/css" href="/thinkphpcms/Public/static/js/easyui/themes/metro/easyui.css" title="metro" />
<link rel="stylesheet" type="text/css" href="/thinkphpcms/Public/static/css/admin/default.css" title="default" />
<link rel="stylesheet" type="text/css" href="/thinkphpcms/Public/static/css/admin/gray.css" title="gray" />
<link rel="stylesheet" type="text/css" href="/thinkphpcms/Public/static/css/admin/bootstrap.css" title="bootstrap" />
<link rel="stylesheet" type="text/css" href="/thinkphpcms/Public/static/css/admin/metro.css" title="metro" />
<link rel="stylesheet" type="text/css" href="/thinkphpcms/Public/static/js/easyui/themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="/thinkphpcms/Public/static/js/easyui/themes/icon.css">
<link rel="stylesheet" type="text/css" href="/thinkphpcms/Public/static/js/easyui/demo/demo.css">
<script type="text/javascript">
var theme = $.cookie('theme') || 'bootstrap';	//全局变量
$(document).ready(function(){
	$('link[rel*=style][title]').each(function(i){
		this.disabled = true;
		if (this.getAttribute('title') == theme) this.disabled = false;
	});
});
</script>
<style type="text/css">
a{color:#08c;text-decoration:none}
a:hover,a:focus{color:#005580;text-decoration:underline}
</style>
</head>
<body>
<div class="easyui-window" title="提示信息" style="width:380px;height:200px" data-options="iconCls:'',modal:false,resizable:false,collapsible:false,minimizable:false,maximizable:false,closable:false">
<div style="font-size:18px;text-align:center;margin-top:40px">
	<?php if(isset($message)): ?><p class="success"><?php echo ($message); ?></p>
	<?php else: ?>
		<p class="error"><?php echo ($error); ?></p><?php endif; ?>
	<p style="font-size:12px">
		页面自动 <a id="href" href="<?php echo ($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo ($waitSecond); ?></b>
	</p>
</div>
</div>
<script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time <= 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();
</script>
</body>
</html>