<?php if (!defined('THINK_PATH')) exit();?><div class="easyui-panel" data-options="fit:true,title:'<?php echo ($currentpos); ?>',border:false">
<script type="text/javascript">
$(function(){
	$('#admin_public_editpwd_form_submit').click(function(){$('#admin_public_editpwd_form').submit()});
	$.formValidator.initConfig({formID:"admin_public_editpwd_form",onSuccess:adminPublicEditpwdFormSubmit,submitAfterAjaxPrompt:'有数据正在异步验证，请稍等...',inIframe:true});
	$("#admin_public_editpwd_form_old_password").formValidator({onShow:"不修改密码请留空",onFocus:"密码应该为6-20位之间"}).inputValidator({min:6,max:20,empty:{leftEmpty:false,rightEmpty:false,emptyError:"密码两边不能有空格"},onError:"密码应该为6-20位之间"}).ajaxValidator({
		type : "post",
		url : "<?php echo U('Admin/public_checkPassword');?>",
		data : {password: function(){return $("#admin_public_editpwd_form_old_password").val()}},
		datatype : "json",
		async:'false',
		success : function(data){
			var json = $.parseJSON(data);
            return json.status == 1 ? true : false;
		},
		onError : "旧密码输入错误",
		onWait : "请稍候..."
	});
	$("#admin_public_editpwd_form_new_password").formValidator({onShow:"不修改密码请留空",onFocus:"密码应该为6-20位之间"}).inputValidator({min:6,max:20,empty:{leftEmpty:false,rightEmpty:false,emptyError:"密码两边不能有空格"},onError:"密码不能为空,请确认"});
	$("#admin_public_editpwd_form_new_pwdconfirm").formValidator({onShow:"不修改密码请留空",onFocus:"请输入确认密码"}).compareValidator({desID:"admin_public_editpwd_form_new_password",operateor:"=",onError:"输入两次密码不一致"});
})
function adminPublicEditpwdFormSubmit(){
	$.post('<?php echo U('Admin/public_editPwd');?>', $("#admin_public_editpwd_form").serialize(), function(res){
		if(!res.status){
			$.messager.alert('提示信息', res.info, 'error');
		}else{
			$.messager.confirm('提示信息', res.info, function(result){
				if(result) window.location.href = res.url;
			});
		}
	})
}
</script>
<form id="admin_public_editpwd_form">
<table cellspacing="10">
	<tr>
		<td width="90">用户名：</td>
		<td colspan="2"><?php echo ($info["username"]); ?> (真实姓名： <?php echo ($info["realname"]); ?>)</td>
	</tr>
	<tr>
		<td>E-mail：</td>
		<td><?php echo ($info["email"]); ?></td>
		<td></td>
	</tr>
	<tr>
		<td>旧密码：</td>
		<td><input id="admin_public_editpwd_form_old_password" name="old_password" type="password" style="width:180px;height:22px" /></td>
		<td><div id="admin_public_editpwd_form_old_passwordTip"></div></td>
	</tr>
	<tr>
		<td>新密码：</td>
		<td><input id="admin_public_editpwd_form_new_password" name="new_password" type="password" style="width:180px;height:22px" /></td>
		<td><div id="admin_public_editpwd_form_new_passwordTip"></td>
	</tr>
	<tr>
		<td>重复新密码：</td>
		<td><input id="admin_public_editpwd_form_new_pwdconfirm" type="password" style="width:180px;height:22px" /></td>
		<td><div id="admin_public_editpwd_form_new_pwdconfirmTip"></td>
	</tr>
	<tr>
		<td colspan="3"><a id="admin_public_editpwd_form_submit" class="easyui-linkbutton">提交</a></td>
	</tr>
</table>
</form>
</div>