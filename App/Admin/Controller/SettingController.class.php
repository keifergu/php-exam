<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 系统设置相关模块
 * @author wangdong
 */
class SettingController extends CommonController {
	/**
	 * 站点设置
	 */
	public function site(){
		if(IS_POST){
			$setting_db = D('Setting');
			if(I('get.dosubmit')){
				$state = $setting_db->dosave($_POST['data']);
				$state ? $this->success('操作成功') : $this->error('操作失败');
			}else{
				if(S('setting_site')){
					$data = S('setting_site');
				}else{
					$data = $setting_db->getSetting();
					S('setting_site', $data);
				}
				$this->ajaxReturn($data);
			}
		}else {
			$menu_db = D('Menu');
			$currentpos = $menu_db->currentPos(I('get.menuid'));  //栏目位置
			$propertygrid = array(
				'options'     => array(
	    			'title'   => $currentpos,
	    			'url'     => U('Setting/site', array('grid'=>'propertygrid')),
	    			'toolbar' => 'setting_site_propertygrid_toolbar',
	    		)
			);
			$this->assign('propertygrid', $propertygrid);
			$this->display();
		}
	}
	
	/**
	 * 恢复出厂设置
	 */
	public function siteDefault(){
		if(IS_POST){
			$setting_db = D('Setting');
			if($setting_db->where('1')->count()){
				$state = $setting_db->where('1')->delete();
				if($state){
					$setting_db->clearCatche();
					$this->success('操作成功');
				}else{
					$this->error('操作失败');
				}
			}
			$this->success('操作成功');
		}
	}
}