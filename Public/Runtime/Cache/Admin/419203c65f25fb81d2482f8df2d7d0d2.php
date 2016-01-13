<?php if (!defined('THINK_PATH')) exit();?><div class="easyui-panel" data-options="fit:true,title:'<?php echo ($currentpos); ?>',border:false">
<script type="text/javascript">
$(function(){
	$('#admin_public_editinfo_form_submit').click(function()
			{$('#admin_public_editinfo_form').submit()});
	$.formValidator.initConfig({formID:"admin_public_editinfo_form",onSuccess:adminPublicEditinfoFormSubmit,submitAfterAjaxPrompt:'有数据正在异步验证，请稍等...',inIframe:true});
	$("#admin_public_editinfo_form_realname").formValidator({onShow:"请输入真实姓名",onFocus:"真实姓名应该为2-20位之间"}).inputValidator({min:2,max:20,empty:{leftEmpty:false,rightEmpty:false,emptyError:"姓名两边不能有空格"},onError:"真实姓名应该为2-20位之间"});
	$("#admin_public_editinfo_form_email").formValidator({onShow:"请输入E-mail",onFocus:"请输入E-mail"}).regexValidator({regExp:"email",dataType:"enum",onError:"E-mail格式错误"}).ajaxValidator({
		type : "post",
		url : "<?php echo U('Admin/public_checkEmail');?>",
		data : {email: function(){return $("#admin_public_editinfo_form_email").val()}, default: '<?php echo ($info["email"]); ?>'},
		datatype : "json",
		async:'false',
		success : function(data){
			var json = $.parseJSON(data);
            return json.status == 1 ? false : true;
		},
		onError : "该邮箱已经存在",
		onWait : "请稍候..."
	});
})
function adminPublicEditinfoFormSubmit(){
	$.post('<?php echo U('Admin/public_editInfo');?>', $("#admin_public_editinfo_form").serialize(), function(res)
			{
		if(!res.status){
			$.messager.alert('提示信息', res.info, 'error');
		}else{
			$.messager.alert('提示信息', res.info, 'info');
		}
	})
}
</script>
<form id="admin_public_editinfo_form">
<table cellspacing="10">
	<tr>
		<td width="90">用户名：</td>
		<td><?php echo ($info["username"]); ?></td>
		<td></td>
	</tr>
	<tr>
		<td>最后登录时间</td> 
		<td><?php if(($info["lastlogintime"]) > "0"): echo (date('Y-m-d H:i:s',$info["lastlogintime"])); else: ?>-<?php endif; ?></td>
		<td></td>
	</tr>
	<tr>
		<td>最后登录IP</td> 
		<td><?php echo ((isset($info["lastloginip"]) && ($info["lastloginip"] !== ""))?($info["lastloginip"]):'-'); ?></td>
		<td></td>
	</tr>
	<tr>
		<td>真实姓名</td>
		<td><input id="admin_public_editinfo_form_realname" type="text" name="info[realname]" value="<?php echo ($info["realname"]); ?>" style="width:180px;height:22px" /></td>
		<td><div id="admin_public_editinfo_form_realnameTip"></div></td>
	</tr>
	<tr>
		<td>E-mail：</td>
		<td><input id="admin_public_editinfo_form_email" type="text" name="info[email]" value="<?php echo ($info["email"]); ?>" style="width:180px;height:22px" /></td>
		<td><div id="admin_public_editinfo_form_emailTip"></div></td>
	</tr>
	<tr>
		<td colspan="3"><a id="admin_public_editinfo_form_submit" class="easyui-linkbutton">提交</a></td>
	</tr>
</table>
</form>
</div>