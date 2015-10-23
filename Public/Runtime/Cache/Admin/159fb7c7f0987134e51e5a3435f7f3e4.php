<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="utf-8">
<meta name="author" content="wangdong">
<title><?php echo C('SYSTEM_NAME');?></title>
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
<script type="text/javascript" src="/thinkphpcms/Public/static/js/formvalidator/formValidator.min.js"></script>
<script type="text/javascript" src="/thinkphpcms/Public/static/js/formvalidator/formValidatorRegex.js"></script>
<script type="text/javascript">initConfig_setting.theme = 'App';</script>
</head>
<body class="easyui-layout">

<!-- 头部 -->
<div id="toparea" data-options="region:'north',border:false,height:40">
	<div id="topmenu" class="easyui-panel" data-options="fit:true,border:false">
		<a class="logo"><?php echo C('SYSTEM_NAME');?></a>
		<ul class="nav">
			<?php if(is_array($menuList)): foreach($menuList as $key=>$menu): ?><li><a <?php if(($menu["id"]) == "1"): ?>class="focus"<?php endif; ?> href="javascript:;" onclick="getLeft(<?php echo ($menu["id"]); ?>,'<?php echo ($menu["name"]); ?>', this)"><?php echo ($menu["name"]); ?></a></li><?php endforeach; endif; ?>
		</ul>
		<ul class="nav-right">
			<li>
				<span>您好！ <?php echo ($userInfo["username"]); ?> [<?php echo ($userInfo["rolename"]); ?>] | <a href="javascript:logout();">[退出]</a></span> | 
				<select id="themeswitchcombobox" class="easyui-combobox" data-options="editable:false,panelHeight:'auto',onChange:onChangeTheme,formatter:themeInit">
					<option value='default'>Default</option>
					<option value='gray'>Gray</option>
					<option value='bootstrap'>Bootstrap</option>
					<option value='metro'>Metro</option>
				</select>
			</li>
		</ul>
	</div>
</div>

<!-- 左侧菜单 -->
<div id="leftarea" data-options="iconCls:'icons-other-house',region:'west',title:'加载中...',split:true,width:190">
	<div id="leftmenu" class="easyui-accordion" data-options="fit:true,border:false"></div>
</div>

<!-- 内容 -->
<div id="mainarea" data-options="region:'center'">
    <div id="pagetabs" class="easyui-tabs" data-options="tabPosition:'bottom',fit:true,border:false,plain:false">
    	<div title="后台首页" href="<?php echo U('Index/public_main');?>" data-options="cache:false"></div>
    </div>
</div>

<!-- 右键菜单 -->
<div id="rightmenu" class="easyui-menu" data-options="onClick:rightMenuHandler">
	<div data-options="name:'home',iconCls:'icons-application-application_home'">访问前台</div>
	<div class="menu-sep"></div>
	<div data-options="name:'refresh',iconCls:'icons-arrow-arrow_refresh'">刷新后台</div>
	<div data-options="name:'cache',iconCls:'icons-other-plugin'">更新缓存</div>
	<div data-options="name:'bug',iconCls:'icons-bug-bug'">提交缺陷</div>
	<div class="menu-sep"></div>
	<div data-options="name:'exit'">退出登录</div>
</div>

<script type="text/javascript">
$(function(){
	getLeft(1, '我的面板');
	//初始化右键菜单
	$(document).bind('contextmenu',function(e){
		e.preventDefault();
		$('#rightmenu').menu('show', {
			left: e.pageX,
			top: e.pageY
		});
	});
	$.messager.show({			//登录默认提示
		title:'登录提示',
		msg:'您好！<?php echo ($userInfo["username"]); ?> 欢迎回来！<br/>最后登录时间：<?php if($userInfo['lastlogintime']): echo (date('Y-m-d H:i:s',$userInfo["lastlogintime"])); else: ?>-<?php endif; ?><br/>最后登录IP：<?php echo ($userInfo["lastloginip"]); ?>',
		timeout:5000,
		showType:'slide'
	});
});
//右键菜单点击事件
function rightMenuHandler(item){
	if(!item.name) return;
	switch(item.name){
		case 'home':
			window.open('<?php echo U('Home/Index/index');?>');
			break;
		case 'refresh': //刷新后台
			window.location.href = window.location.href;
			break;
		case 'cache': //更新缓存
			$.post('<?php echo U('Index/public_clearCatche');?>', function(data){
				var msgType = data.status ? 'info' : 'error';
				$.messager.alert('提示信息', data.info, msgType);
			}, 'json');
			break;
		case 'bug': //提交缺陷
			$.messager.alert('提示信息', '请发邮件到531381545@qq.com提交缺陷，谢谢！', 'info');
			break;
		case 'exit': //退出登录
			logout();
			break;
	}
}
//主题内容初始化
function themeInit(row){
	if(row.value == theme) row.selected = true;
	var opts = $('#themeswitchcombobox').combobox('options');
	return row[opts.textField];
}
//切换主题
function onChangeTheme(theme){
	$('link[rel*=style][title]').each(function(i){
		this.disabled = true;
		if (this.getAttribute('title') == theme) this.disabled = false;
	});
	$('iframe').contents().find('link[rel*=style][title]').each(function(i){
		this.disabled = true;
		if (this.getAttribute('title') == theme) this.disabled = false;
	});
	$.cookie('theme', theme, {path:'/', expires:3650});
}
//退出登录
function logout(){
	$.messager.confirm('提示信息', '确定要退出登录吗？', function(result){
		if(result) window.location.href = '<?php echo U('Index/logout');?>';
	});
}
//显示左侧栏目
function getLeft(menuid, title, that){
	//加个判断，防止多次点击重复加载
	var options = $('body').layout('panel', 'west').panel('options');
	if(title == options.title) return false;
	//开始获取左侧栏目
	$.ajax({
		type: 'POST',
		url: '<?php echo U('Index/public_menuLeft');?>',
		data: {menuid: menuid},
		cache: false,
		beforeSend: function(){
			removeLeft();
			//更新标题名称
			$('body').layout('panel', 'west').panel({title: title});
			var loading = '<div class="panel-loading">Loading...</div>';
			$("#leftmenu").accordion("add", {content: loading});
		},
		success: function(data){
			removeLeft();
			//左侧内容更新
			$.each(data, function(i, menu) {
				var content = '';
				if(menu.son){
					var treedata = $.toJSON(menu.son);
					content = '<ul class="easyui-tree" data-options=\'data:' + treedata + ',animate:true,lines:true,onClick:function(node){openUrl(node.url, node.text)}\'></ul>';
				}
				$("#leftmenu").accordion("add", {title: menu.name, content: content, iconCls:'icons-folder-folder_go'});
			});
		}
	});
	//默认选中头部菜单
	if(that){
		$('#topmenu .nav li').each(function(){
			$(this).children().removeClass('focus');
		})
		$(that).addClass('focus');
	}
}
//移除左侧菜单
function removeLeft(stop){
	var pp = $("#leftmenu").accordion("panels");
	$.each(pp, function(i, n) {
		if(n){
			var t = n.panel("options").title;
			$("#leftmenu").accordion("remove", t);
		}
    });
	var pp = $('#leftmenu').accordion('getSelected');
    if(pp) {
        var t = pp.panel('options').title;
        $('#leftmenu').accordion('remove', t);
    }
    if(!stop) removeLeft(true)//发现执行两次才能彻底移除
}
//显示打开内容
function openUrl(url, title){
	if($('#pagetabs').tabs('exists', title)){
		$('#pagetabs').tabs('select', title);
	}else{
		$('#pagetabs').tabs('add',{
			title: title,
			href: url,
			closable: true,
			cache: false
		});
	}
}
//防止登录超时
setInterval(function(){
	$.get('<?php echo U('Index/public_sessionLife');?>');
}, 600000);
</script>

</body>
</html>