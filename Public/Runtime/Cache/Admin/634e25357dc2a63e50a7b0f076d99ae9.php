<?php if (!defined('THINK_PATH')) exit();?><style type="text/css">
#index_public_main .bar {border:1px solid #999999; background:#FFFFFF; height:5px; font-size:2px; width:89%; margin:2px 0 5px 0;padding:1px;overflow: hidden;}
#index_public_main .bar_1 {border:1px dotted #999999; background:#FFFFFF; height:5px; font-size:2px; width:89%; margin:2px 0 5px 0;padding:1px;overflow: hidden;}
#index_public_main .barli_red{background:#ff6600; height:5px; margin:0px; padding:0;}
#index_public_main .barli_blue{background:#0099FF; height:5px; margin:0px; padding:0;}
#index_public_main .barli_green{background:#36b52a; height:5px; margin:0px; padding:0;}
#index_public_main .barli_1{background:#999999; height:5px; margin:0px; padding:0;}
#index_public_main .barli{background:#36b52a; height:5px; margin:0px; padding:0;}
</style>
<div id="index_public_main" class="easyui-panel" data-options="fit:true,title:'后台首页',border:false">
	<table width="100%" cellspacing="5" border="0">
		<tr>
			<!-- 个人信息 -->
			<td width="50%" valign="top">
				<div class="panel">
					<div class="panel-header">
						<div class="panel-title">我的个人信息</div>
					</div>
					<div class="panel-body" style="padding:8px;line-height:1.8">
						您好，<?php echo ($userInfo["username"]); ?><br />
						所属角色：<?php echo ($userInfo["rolename"]); ?> <br />
						最后登录时间：<?php if($userInfo['lastlogintime'] > 0): echo (date('Y-m-d H:i:s',$userInfo["lastlogintime"])); endif; ?><br />
						最后登录IP：<?php echo ($userInfo["lastloginip"]); ?> <br />
					</div>
				</div>
			</td>
			<!-- 安全提示 -->
			<td width="50%" valign="top">
				<div class="panel">
					<div class="panel-header">
						<div class="panel-title">安全提示</div>
					</div>
					<div class="panel-body" style="padding:8px;line-height:1.8">
						<?php if(is_writeable(SITE_DIR . DS . 'Libs')): ?>建议设置Libs目录权限为<?php if(IS_WIN): ?>只读<?php else: ?>655<?php endif; ?>  <br /><?php endif; ?>
						<?php if(APP_DEBUG): ?>网站上线后建议关闭DEBUG调试模式 <br /><?php endif; ?>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<!-- 服务器参数 -->
			<td valign="top">
				<div class="panel">
					<div class="panel-header">
						<div class="panel-title">服务器参数</div>
					</div>
					<div class="panel-body" style="padding:8px;line-height:1.8">
						服务器域名/IP地址：<?php echo ($_SERVER['SERVER_NAME']); ?>(<?php if(DIRECTORY_SEPARATOR == '/'): echo ($_SERVER['SERVER_ADDR']); else: echo @gethostbyname($_SERVER['SERVER_NAME']); endif; ?>) <br />
						服务器标识：<?php if($sysinfo['win_n'] != ''): echo ($sysinfo["win_n"]); else: echo @php_uname(); endif; ?> <br />
						服务器操作系统：<?php echo ($os["0"]); ?> &nbsp;内核版本：<?php if(DIRECTORY_SEPARATOR == '/'): echo ($os["2"]); else: echo ($os["1"]); endif; ?><br />
						服务器解译引擎：<?php echo ($_SERVER['SERVER_SOFTWARE']); ?> <br />
						服务器语言：<?php echo getenv("HTTP_ACCEPT_LANGUAGE");?> <br />
						服务器端口：<?php echo ($_SERVER['SERVER_PORT']); ?> <br />
						服务器主机名：<?php if(DIRECTORY_SEPARATOR == '/'): echo ($os["1"]); else: echo ($os["2"]); endif; ?> <br />
						管理员邮箱：<?php echo ($_SERVER['SERVER_ADMIN']); ?> <br />
						绝对路径：<?php echo SITE_DIR;?> <br />
						上传文件最大限制（upload_max_filesize）：<?php echo get_cfg_var('upload_max_filesize');?> <br />
					</div>
				</div>
			</td>
			<!-- 服务器实时数据 -->
			<td valign="top">
				<div class="panel">
					<div class="panel-header">
						<div class="panel-title">服务器实时数据</div>
					</div>
					<div class="panel-body" style="padding:8px;line-height:1.8">
						<?php if(($sysinfo["sysReShow"]) == "show"): ?>服务器当前时间：<span><?php echo ($sysinfo["stime"]); ?></span> <br />
						服务器已运行时间：<span><?php echo ($sysinfo["uptime"]); ?></span> <br />
						总空间：<?php echo ($sysinfo["DiskTotal"]); ?>&nbsp;GB &nbsp;&nbsp;&nbsp;&nbsp;<span title="显示的是网站所在的目录的可用空间，非服务器上所有磁盘之可用空间！">可用空间</span>： <font color='#CC0000'><span><?php echo ($sysinfo["freeSpace"]); ?></span></font>&nbsp;GB<br />
						CPU型号 [<?php echo ($sysinfo["cpu"]["num"]); ?>核]：<?php echo ($sysinfo["cpu"]["model"]); ?> <br />
						内存使用状况：物理内存：共<font color='#CC0000'><?php echo ($sysinfo["TotalMemory"]); ?></font>, 已用<font color='#CC0000'><span><?php echo ($sysinfo["UsedMemory"]); ?></span></font>, 空闲<font color='#CC0000'><span><?php echo ($sysinfo["FreeMemory"]); ?></span></font>, 使用率<span><?php echo ($sysinfo["memPercent"]); ?></span> <br />
						<div class="bar"><div class="barli_green" style="width:<?php echo ($sysinfo["memPercent"]); ?>">&nbsp;</div> </div>
						<?php if($sysinfo['CachedMemory'] > 0): ?>Cache化内存为 <span><?php echo ($sysinfo["CachedMemory"]); ?></span>, 使用率<span><?php echo ($sysinfo["memCachedPercent"]); ?></span> %	| Buffers缓冲为  <span><?php echo ($sysinfo["Buffers"]); ?></span>
							<div class="bar"><div class="barli_blue" style="width:<?php echo ($sysinfo["barmemCachedPercent"]); ?>">&nbsp;</div></div>
							真实内存使用 <span><?php echo ($sysinfo["memRealUsed"]); ?></span>, 真实内存空闲<span><?php echo ($sysinfo["memRealFree"]); ?></span>, 使用率<span><?php echo ($sysinfo["memRealPercent"]); ?></span> %
							<div class="bar_1"><div class="barli_1" style="width:<?php echo ($sysinfo["barmemRealPercent"]); ?>">&nbsp;</div></div><?php endif; ?>
						<?php if($sysinfo['TotalSwap'] > 0): ?>SWAP区：共<?php echo ($sysinfo["TotalSwap"]); ?>, 已使用<span><?php echo ($sysinfo["swapUsed"]); ?></span>, 空闲<span><?php echo ($sysinfo["swapFree"]); ?></span>, 使用率<span><?php echo ($sysinfo["swapPercent"]); ?></span> %
							<div class="bar"><div class="barli_red" style="width:<?php echo ($sysinfo["barswapPercent"]); ?>">&nbsp;</div> </div><?php endif; ?>
						系统平均负载：<span><?php echo ($sysinfo["loadAvg"]); ?></span>
						<?php else: ?>
						无法获取当前服务器实时数据<?php endif; ?>
					</div>
				</div>
			</td>
		</tr>
		<tr>
			<!-- 网络使用状况 -->
			<td valign="top">
				<div class="panel">
					<div class="panel-header">
						<div class="panel-title">网络使用状况</div>
					</div>
					<div class="panel-body" style="padding:8px;line-height:1.8">
						<?php if(($net_state) != ""): echo ($net_state); ?>
						<?php else: ?>
						无法获取当前服务器网络使用信息<?php endif; ?>
					</div>
				</div>
			</td>
			<!-- 系统说明 -->
			<td valign="top">
				<div class="panel">
					<div class="panel-header">
						<div class="panel-title">系统说明</div>
						<div class="panel-tool"></div>
					</div>
					<div class="panel-body" style="padding:8px;line-height:1.8">
						当前系统版本：<?php echo C('SYSTEM_VERSION');?><br />
						ThinkPHP：<?php echo (THINK_VERSION); ?><br />
						jQuery EasyUI：1.3.5<br />
						jQuery formValidator：4.1.3<br />
					</div>
				</div>
			</td>
		</tr>
	</table>
</div>