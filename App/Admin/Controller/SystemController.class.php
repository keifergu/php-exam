<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 后台管理模块
 * @author wangdong
 */
class SystemController extends CommonController{
	/**
	 * 操作日志列表
	 */
	public function logList($page=1, $rows=10, $search = array(), $sort = 'time', $order = 'desc'){
		if(IS_POST){
			$log_db = M('log');
			
			//搜索
			$where = array();
			foreach ($search as $k=>$v){
				if(!$v) continue;
				switch ($k){
					case 'username':
					case 'controller':
						$where[] = "`{$k}` = '{$v}'";
						break;
					case 'begin':
						if(!preg_match("/^\d{4}(-\d{2}){2}$/", $v)){
							unset($search[$k]);
							continue;
						}
						if($search['end'] && $search['end'] < $v) $v = $search['end'];
						$where[] = "`time` >= '{$v}'";
						break;
					case 'end':
						if(!preg_match("/^\d{4}(-\d{2}){2}$/", $v)){
							unset($search[$k]);
							continue;
						}
						if($search['begin'] && $search['begin'] > $v) $v = $search['begin'];
						$where[] = "`time` <= '{$v}'";
						break;
				}
			}
			$where = implode(' and ', $where);
			
			$limit=($page - 1) * $rows . "," . $rows;
			$total = $log_db->where($where)->count();
			$order = $sort.' '.$order;
			$list = $total ? $log_db->where($where)->order($order)->limit($limit)->select() : array();
    		$data = array('total'=>$total, 'rows'=>$list);
    		$this->ajaxReturn($data);
    	}else{
    		$menu_db = D('Menu');
    		$admin_db = D('Admin');
    		
    		$currentpos = $menu_db->currentPos(I('get.menuid'));  //栏目位置
    		$list = array();
    		$list['admin'] = $admin_db->order('lastlogintime DESC')->getField('username', true);
    		$list['module'] = $menu_db->order('c')->group('c')->getField('c', true);

    		$datagrid = array(
		        'options'     => array(
    				'title'   => $currentpos,
    				'url'     => U('System/logList', array('grid'=>'datagrid')),
    				'toolbar' => '#system_loglist_datagrid_toolbar',
    			),
		        'fields' => array(
		        	'用户名' => array('field'=>'username','width'=>20,'sortable'=>true),
		        	'模块'   => array('field'=>'controller','width'=>15,'sortable'=>true),
		        	'方法'   => array('field'=>'action','width'=>15,'sortable'=>true),
		        	'参数'   => array('field'=>'querystring','width'=>100,'formatter'=>'systemLogViewFormatter'),
		        	'时间'   => array('field'=>'time','width'=>30,'sortable'=>true),
		        	'IP'    => array('field'=>'ip','width'=>25,'sortable'=>true),
    			)
		    );
		    $this->assign('datagrid', $datagrid);
		    $this->assign('list', $list);
			$this->display('log_list');
    	}
	}
	
	/**
	 * 操作日志删除
	 */
	public function logDelete($week = 4) {
		$log_db = M('log');
		$start = time() - $week*7*24*3600;
		$d = date('Y-m-d', $start); 
		$where = "left(`time`, 10) <= '$d'";
		$result = $log_db->where($where)->delete();
		$result ? $this->success('删除成功') : $this->error('没有数据或已删除过了，请稍后再试');
	}
	
	
	/**
	 * 菜单列表
	 */
    public function menuList(){
    	$menu_db = D('Menu');
    	if(IS_POST){
    		if(S('system_menulist')){
    			$data = S('system_menulist');
    		}else{
    			$data = $menu_db->getTree();
    			S('system_menulist', $data);
    		}
    		$this->ajaxReturn($data);
    	}else{
    		$currentpos = $menu_db->currentPos(I('get.menuid'));  //栏目位置
    		$treegrid = array(
		        'options'       => array(
    				'title'     => $currentpos,
    				'url'       => U('System/menuList', array('grid'=>'treegrid')),
    				'idField'   => 'id',
    				'treeField' => 'name',
    				'toolbar'   => 'system_menulist_treegrid_toolbar',
    			),
		        'fields' => array(
		        	'排序'    => array('field'=>'listorder','width'=>20,'align'=>'center','formatter'=>'systemMenuOrderFormatter'),
		        	'菜单ID'  => array('field'=>'id','width'=>20,'align'=>'center'),
		        	'菜单名称' => array('field'=>'name','width'=>200),
		        	'管理操作' => array('field'=>'operateid','width'=>80,'align'=>'center','formatter'=>'systemMenuOperateFormatter'),
    			)
		    );
		    $this->assign('treegrid', $treegrid);
			$this->display('menu_list');
    	}
    }
    
    /**
     * 添加菜单
     */
	public function menuAdd($parentid = 0){
    	if(IS_POST){
    		$menu_db = D('Menu');
    		$data = I('post.info');
    		$data['display'] = $data['display'] ? '1' : '0';
    		$id = $menu_db->add($data);
    		if($id){
    			$menu_db->clearCatche();
    			$this->success('添加成功');
    		}else {
    			$this->error('添加失败');
    		}
    	}else{
    		$this->assign('parentid', $parentid);
			$this->display('menu_add');
    	}
    }
    
    /**
     * 编辑菜单
     */
    public function menuEdit($id = 0){
    	if(!$id) $this->error('未选择菜单');
    	$menu_db = D('Menu');
    	if(IS_POST){
    		$data = I('post.info');
    		$data['display'] = $data['display'] ? '1' : '0';
    		$result = $menu_db->where(array('id'=>$id))->save($data);
    		if($result){
    			$menu_db->clearCatche();
    			$this->success('修改成功');
    		}else {
    			$this->error('修改失败');
    		}
    	}else{
    		$data = $menu_db->where(array('id'=>$id))->find();
    		$this->assign('data', $data);
			$this->display('menu_edit');
    	}
    }
    
	/**
	 * 删除菜单
	 */
    function menuDelete($id = 0){
    	if($id && IS_POST){
    		$menu_db = D('Menu');
    		$result = $menu_db->where(array('id'=>$id))->delete();
    		if($result){
    			$menu_db->clearCatche();
    			$this->success('删除成功');
    		}else {
    			$this->error('删除失败');
    		}
    	}else{
			$this->error('删除失败');
    	}
    }
    
    /**
     * 菜单下拉框
     */
    public function public_menuSelectTree(){
    	if(S('system_public_menuselecttree')){
    		$data = S('system_public_menuselecttree');
    	}else {
    		$menu_db = D('Menu');
    		$data = $menu_db->getSelectTree();
    		$data = array(0=>array('id'=>0,'text'=>'作为一级菜单','children'=>$data));
    		S('system_public_menuselecttree', $data);
    	}
    	$this->ajaxReturn($data);
    }
    
	/**
	 * 菜单排序
	 */
	function menuOrder(){
		if(IS_POST) {
			$menu_db = D('Menu');
			foreach(I('post.order') as $id => $listorder) {
				$menu_db->where(array('id'=>$id))->save(array('listorder'=>$listorder));
			}
			$menu_db->clearCatche();
			$this->success('操作成功');
		} else {
			$this->error('操作失败');
		}
    }
    
    /**
     * 验证菜单名称是否已存在
     */
    public function public_menuNameCheck($name){
		if(I('post.default') == $name) $this->error('菜单名称相同');
		
        $menu_db = D('Menu');
        $exists = $menu_db->checkName($name);
        if ($exists) {
            $this->success('菜单名称存在');
        }else{
            $this->error('菜单名称不存在');
        }
    }
}
