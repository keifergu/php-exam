<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 后台管理通用模块
 * @author wangdong
 */
class IndexController extends CommonController {
    /**
	 * 后台首页
	 */
	public function index(){
	    $admin_db      = D('Admin');
	    $menu_db       = D('Menu');
	    
	    $userid = session('userid');
		$userInfo = $admin_db->getUserInfo($userid);    //获取用户基本信息
		$menuList = $menu_db->getMenu();                //头部菜单列表
		
		$this->assign('userInfo', $userInfo);
		$this->assign('menuList', $menuList);
		$this->display('index');
	}
	
	/**
	 * 用户登录
	 */
    public function login(){
    	if (I('get.dosubmit')){
            $admin_db = D('Admin');
            
			$username = I('post.username', '', 'trim') ? I('post.username', '', 'trim') : $this->error('用户名不能为空', HTTP_REFERER);
			$password = I('post.password', '', 'trim') ? I('post.password', '', 'trim') : $this->error('密码不能为空', HTTP_REFERER);
			//验证码判断
			$code = I('post.code', '', 'trim') ? I('post.code', '', 'trim') : $this->error('请输入验证码', HTTP_REFERER);
			if(!check_verify($code, 'admin')) $this->error('验证码错误！', HTTP_REFERER);
			
			if($admin_db->login($username, $password)){
			    $this->success('登录成功', U('Index/index'));
			}else{
			    $this->error($admin_db->error, HTTP_REFERER);
			}
    	}else {
    		$this->display();
    	}
    }
    
    /**
	 * 退出登录
	 */
    public function logout() {
		session('userid', null);
		session('roleid', null);
		cookie('username', null);
		cookie('userid', null);
		
		$this->success('安全退出！', U('Index/login'));
	}
    
    /**
	 * 验证码
	 */
	public function code(){
        $verify = new \Think\Verify();
        $verify->useCurve = true;
        $verify->useNoise = false;
        $verify->bg = array(255, 255, 255);
        
		if (I('get.code_len')) $verify->length = intval(I('get.code_len'));
		if ($verify->length > 8 || $verify->length < 2) $verify->length = 4;
		
		if (I('get.font_size')) $verify->fontSize = intval(I('get.font_size'));
		
		if (I('get.width')) $verify->imageW = intval(I('get.width'));
		if ($verify->imageW <= 0) $verify->imageW = 130;
		
		if (I('get.height')) $verify->imageH = intval(I('get.height'));
		if ($verify->imageH <= 0) $verify->imageH = 50;

        $verify->entry('admin');
	}
	
    /**
     * 左侧菜单
     */
	public function public_menuLeft($menuid = 0) {
	    $menu_db = D('Menu');
		$datas = array();
		$list = $menu_db->getMenu($menuid);
		foreach ($list as $k=>$v){
			$datas[$k]['name'] = $v['name'];
			$son_datas = $menu_db->getMenu($v['id']);
			foreach ($son_datas as $k2=>$v2){
				$datas[$k]['son'][$k2]['text'] = $v2['name'];
				$datas[$k]['son'][$k2]['id']   = $v2['id'];
				$datas[$k]['son'][$k2]['url'] = U($v2['c'].'/'.$v2['a'].'?menuid='.$v2['id'].'&'.$v2['data']);
			}
		}
		$this->ajaxReturn($datas);
	}
	
	/**
	 * 后台欢迎页
	 */
	public function public_main(){
	    $admin_db      = D('Admin');
	    $userid = session('userid');
		$userInfo = $admin_db->getUserInfo($userid);    //获取用户基本信息
		
	    $sysinfo = \Admin\Common\Sysinfo::getinfo();
		$os = explode(' ', php_uname());
		//网络使用状况
		$net_state = null;
		if ($sysinfo['sysReShow'] == 'show' && false !== ($strs = @file("/proc/net/dev"))){
			for ($i = 2; $i < count($strs); $i++ ){
				preg_match_all( "/([^\s]+):[\s]{0,}(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)\s+(\d+)/", $strs[$i], $info );
				$net_state.="{$info[1][0]} : 已接收 : <font color=\"#CC0000\"><span id=\"NetInput{$i}\">".$sysinfo['NetInput'.$i]."</span></font> GB &nbsp;&nbsp;&nbsp;&nbsp;已发送 : <font color=\"#CC0000\"><span id=\"NetOut{$i}\">".$sysinfo['NetOut'.$i]."</span></font> GB <br />";
			}
		}
		
		$this->assign('userInfo', $userInfo);
		$this->assign('sysinfo',$sysinfo);
		$this->assign('os',$os);
		$this->assign('net_state',$net_state);
		$this->display('main');
	}
	
	/**
	 * 更新后台缓存
	 */
	public function public_clearCatche(){
	    $list = dict('', 'Cache');
		if(is_array($list) && !empty($list)){
			foreach ($list as $modelName=>$funcName){
				D($modelName)->$funcName();
			}
		}
		$this->success('操作成功');
	}
	
    /**
     * 防止登录超时
     */
	public function public_sessionLife(){
		$userid = session('userid');
	}
}