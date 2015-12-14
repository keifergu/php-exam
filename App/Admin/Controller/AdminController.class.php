<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 后台管理员相关模块
 * @author wangdong
 */
class AdminController extends CommonController {
	/**
	 * 修改个人信息
	 */
	public function public_editInfo($info = array()){
		$userid = session('userid');
		$admin_db = D('Admin');
		if (IS_POST){
			$fields = array('email','realname');
			foreach ($info as $k=>$value) {
				if (!in_array($k, $fields)){
					unset($info[$k]);
				}
			}
			$state = $admin_db->where(array('userid'=>$userid))->save($info);
			$state ? $this->success('修改成功') : $this->error('修改失败');
		}
		else 
		{
			$menu_db = D('Menu');
			$currentpos = $menu_db->currentPos(I('get.menuid'));  //栏目位置
			$info = $admin_db->where(array('userid'=>$userid))->find();
			
			$this->assign('info',$info);
	    	$this->assign(currentpos, $currentpos);
			$this->display('edit_info');
		}
	}
	
	/**
	 * 修改密码
	 */
	public function public_editPwd(){
		$userid = session('userid');
		$admin_db = D('Admin');
		if(IS_POST){
			$info = $admin_db->where(array('userid'=>$userid))->field('password,encrypt')->find();
			if(password(I('post.old_password'), $info['encrypt']) !== $info['password'] ) $this->error('旧密码输入错误');
			if(I('post.new_password')) {
				$state = $admin_db->editPassword($userid, I('post.new_password'));
				if(!$state) $this->error('密码修改失败');
			}
			$this->success('密码修改该成功,请使用新密码重新登录', U('Index/logout'));
		}else{
			$menu_db = D('Menu');
			$currentpos = $menu_db->currentPos(I('get.menuid'));  //栏目位置
			$info = $admin_db->where(array('userid'=>$userid))->find();
			
			$this->assign('info',$info);
		    $this->assign(currentpos, $currentpos);
			$this->display('edit_password');
		}
	}
	
	
	/**
	 * 管理员管理
	 */
	public function memberList($page = 1, $rows = 10, $sort = 'userid', $order = 'asc'){
		if(IS_POST){
			$admin_db = D('Admin');
			$total = $admin_db->count();
			$order = $sort.' '.$order;
			$limit = ($page - 1) * $rows . "," . $rows;
			$list = $admin_db->table(C('DB_PREFIX').'admin A')->join(C('DB_PREFIX').'admin_role AR on AR.roleid = A.roleid')->field("A.userid,A.username,A.lastloginip,A.email,A.realname,AR.rolename,FROM_UNIXTIME(A.lastlogintime, '%Y-%m-%d %H:%i:%s') as lastlogintime")->order($order)->limit($limit)->select();
			$data = array('total'=>$total, 'rows'=>$list);
			$this->ajaxReturn($data);
		}else{
			$menu_db = D('Menu');
			$currentpos = $menu_db->currentPos(I('get.menuid'));  //栏目位置
			$datagrid = array(
		        'options'     => array(
    				'title'   => $currentpos,
    				'url'     => U('Admin/memberList', array('grid'=>'datagrid')),
    				'toolbar' => 'admin_memberlist_datagrid_toolbar',
    			),
		        'fields' => array(
		        	'用户名'      => array('field'=>'username','width'=>15,'sortable'=>true),
		        	'所属角色'    => array('field'=>'rolename','width'=>15,'sortable'=>true),
		        	'最后登录IP'  => array('field'=>'lastloginip','width'=>15,'sortable'=>true),
		        	'最后登录时间' => array('field'=>'lastlogintime','width'=>15,'sortable'=>true,'formatter'=>'adminMemberListTimeFormatter'),
		        	'E-mail'     => array('field'=>'email','width'=>25,'sortable'=>true),
		        	'真实姓名'    => array('field'=>'realname','width'=>15,'sortable'=>true),
		        	'管理操作'    => array('field'=>'userid','width'=>15,'formatter'=>'adminMemberListOperateFormatter'),
    			)
		    );
		    $this->assign('datagrid', $datagrid);
		    $this->ajaxReturn($datagrid);
		    $this->display('member_list');
		}
	}
	
	/**
	 * 添加管理员
	 */
	public function memberAdd(){
		if(IS_POST){
			$admin_db = D('Admin');
			$data = I('post.info');
			if($admin_db->where(array('username'=>$data['username']))->field('username')->find()){
				$this->error('管理员名称已经存在');
			}
			$passwordinfo = password($data['password']);
			$data['password'] = $passwordinfo['password'];
			$data['encrypt'] = $passwordinfo['encrypt'];

    		$id = $admin_db->add($data);
    		if($id){
    			$this->success('添加成功');
    		}else {
    			$this->error('添加失败');
    		}
		}else{
			$admin_role_db = D('AdminRole');
			$rolelist = $admin_role_db->where(array('disabled'=>'0'))->getField('roleid,rolename', true);
			$this->assign('rolelist', $rolelist);
			$this->display('member_add');
		}
	}
	
	/**
	 * 编辑管理员
	 */
	public function memberEdit($id){
		$admin_db = D('Admin');
		if(IS_POST){
			if($id == '1') $this->error('该用户不能被修改');
			$data = I('post.info');
			if($data['password']){
				$passwordinfo = password($data['password']);
				$data['password'] = $passwordinfo['password'];
				$data['encrypt'] = $passwordinfo['encrypt'];
			}else{
				unset($data['password']);
			}
    		$result = $admin_db->where(array('userid'=>$id))->save($data);
    		if($result){
    			$this->success('修改成功');
    		}else {
    			$this->error('修改失败');
    		}
		}else{
			$admin_role_db = D('AdminRole');
			$info = $admin_db->getUserInfo($id);
			$rolelist = $admin_role_db->where(array('disabled'=>'0'))->getField('roleid,rolename', true);
			$this->assign('info', $info);
			$this->assign('rolelist', $rolelist);
			$this->display('member_edit');
		}
	}
	
	/**
	 * 删除管理员
	 */
	public function memberDelete($id){
		if($id == '1') $this->error('该用户不能被删除');
		$admin_db = D('Admin');
		$result = $admin_db->where(array('userid'=>$id))->delete();
		if ($result){
			$this->success('删除成功');
		}else {
			$this->error('删除失败');
		}
	}
	
	
	/**
	 * 角色管理
	 */
	public function roleList($page = 1, $rows = 10, $sort = 'listorder', $order = 'asc'){
		if(IS_POST){
			$admin_role_db = D('AdminRole');
			$total = $admin_role_db->count();
			$order = $sort.' '.$order;
			$limit = ($page - 1) * $rows . "," . $rows;
			$list = $admin_role_db->field('*,roleid as id')->order($order)->limit($limit)->select();
			$data = array('total'=>$total, 'rows'=>$list);
			$this->ajaxReturn($data);
		}else{
			$menu_db = D('Menu');
			$currentpos = $menu_db->currentPos(I('get.menuid'));  //栏目位置
			$datagrid = array(
		        'options'     => array(
    				'title'   => $currentpos,
    				'url'     => U('Admin/roleList', array('grid'=>'datagrid')),
    				'toolbar' => 'admin_rolelist_datagrid_toolbar',
    			),
		        'fields' => array(
		        	'排序'     => array('field'=>'listorder','width'=>5,'align'=>'center','formatter'=>'adminRoleListOrderFormatter'),
		        	'ID'       => array('field'=>'roleid','width'=>5,'align'=>'center','sortable'=>true),
		        	'角色名称'  => array('field'=>'rolename','width'=>15,'sortable'=>true),
		        	'角色描述'  => array('field'=>'description','width'=>25),
		        	'状态'     => array('field'=>'disabled','width'=>15,'sortable'=>true,'formatter'=>'adminRoleListStateFormatter'),
		        	'管理操作'  => array('field'=>'id','width'=>15,'formatter'=>'adminRoleListOperateFormatter'),
    			)
		    );
		    $this->assign('datagrid', $datagrid);
			$this->display('role_list');
			dump("输出陈宫");
		}
	}
	
	/**
	 * 添加角色
	 */
	public function roleAdd(){
		if(IS_POST){
			$admin_role_db = D('AdminRole');
			$data = I('post.info');
			if($admin_role_db->where(array('rolename'=>$data['rolename']))->field('rolename')->find()){
				$this->error('角色名称已存在');
			}
    		$id = $admin_role_db->add($data);
    		if($id){
    			$this->success('添加成功');
    		}else {
    			$this->error('添加失败');
    		}
		}else{
			$this->display('role_add');
		}
	}
	
	/**
	 * 编辑角色
	 */
	public function roleEdit($id){
		$admin_role_db = D('AdminRole');
		if(IS_POST){
			$data = I('post.info');
    		$id = $admin_role_db->where(array('roleid'=>$id))->save($data);
    		if($id){
    			$this->success('修改成功');
    		}else {
    			$this->error('修改失败');
    		}
		}else{
			$info = $admin_role_db->where(array('roleid'=>$id))->find();
			$this->assign('info', $info);
			$this->display('role_edit');
		}
	}
	
	/**
	 * 删除管理员
	 */
	public function roleDelete($id) {
		if($id == '1') $this->error('该角色不能被删除');
		$admin_role_db = D('AdminRole');
		$result = $admin_role_db->where(array('roleid'=>$id))->delete();
		if ($result){
			$this->success('删除成功');
		}else {
			$this->error('删除失败');
		}
	}
	
	/**
	 * 角色排序
	 */
	public function roleOrder(){
		if(IS_POST) {
			$admin_role_db = D('AdminRole');
			foreach(I('post.order') as $roleid=>$listorder) {
				$admin_role_db->where(array('roleid'=>$roleid))->save(array('listorder'=>$listorder));
			}
			$this->success('操作成功');
		} else {
			$this->error('操作失败');
		}
    }
    
	/**
	 * 权限设置
	 */
	public function rolePermission($id){
		if(IS_POST) {
			$menu_db = D('Menu');
			if (I('get.dosubmit')){
				$admin_role_priv_db = M('admin_role_priv');
				$admin_role_priv_db->where(array('roleid'=>$id))->delete();
				$menuids = explode(',', I('post.menuids'));
				$menuids = array_unique($menuids);
				if(!empty($menuids)){
					$menuList = array();
					$menuinfo = $menu_db->field(array('id','c','a','data'))->select();
					foreach ($menuinfo as $v) $menuList[$v['id']] = $v;
					foreach ($menuids as $menuid){
						$info = $menuList[$menuid];
						$info['roleid'] = $id;
						$admin_role_priv_db->add($info);
					}
				}
				$this->success('权限设置成功');
			//获取列表数据
			}else{
				$data = $menu_db->getRoleTree(0, $id);
				$this->ajaxReturn($data);
			}
		} else {
			$this->assign('id', $id);
			$this->display('role_permission');
		}
    }
	
	
	/**
	 * 验证邮箱是否存在
	 */
	public function public_checkEmail($email = 0){
		if (I('post.default') == $email) {
            $this->error('邮箱相同');
        }
        $admin_db = D('Admin');
        $exists = $admin_db->where(array('email'=>$email))->field('email')->find();
        if ($exists) {
            $this->success('邮箱存在');
        }else{
            $this->error('邮箱不存在');
        }
	}
	
	/**
	 * 验证密码
	 */
	public function public_checkPassword($password = 0){
		$userid = session('userid');
		$admin_db = D('Admin');
		$info = $admin_db->where(array('userid'=>$userid))->field('password,encrypt')->find();
		if (password($password, $info['encrypt']) == $info['password'] ) {
			$this->success('验证通过');
		}else {
			$this->error('验证失败');
		}
	}
	
	/**
	 * 验证用户名
	 */
	public function public_checkName($name){
		if (I('post.default') == $name) {
            $this->error('用户名相同');
        }
        $admin_db = D('Admin');
        $exists = $admin_db->where(array('username'=>$name))->field('username')->find();
        if ($exists) {
            $this->success('用户名存在');
        }else{
            $this->error('用户名不存在');
        }
	}
	
	/**
	 * 验证角色名称是否存在
	 */
	public function public_checkRoleName($rolename){
		if (I('post.default') == $rolename) {
            $this->error('角色名称相同');
        }
        $admin_role_db = D('AdminRole');
        $exists = $admin_role_db->where(array('rolename'=>$rolename))->field('rolename')->find();
        if ($exists) {
            $this->success('角色名称存在');
        }else{
            $this->error('角色名称不存在');
        }
	}
}