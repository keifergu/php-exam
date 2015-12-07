<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 内容管理相关模块
 * @author wangdong
 * 
 * TODO
 * 带前缀的ACTION为控制权限用的，默认为view_
 * 后缀带_iframe的ACTION是在iframe中加载的，用于统一返回格式
 */
class ContentController extends CommonController {
	/**
	 * 对应权限如下：
	 * view 查看 | add 添加 | edit 编辑 | delete 删除 | order 排序
	 */
	public function _initialize(){
		parent::_initialize();
		//权限判断
		if(session('roleid') != 1 && ACTION_NAME != 'index' && strpos(ACTION_NAME,'public_')===false) {
			
			$category_priv_db = M('category_priv');
			$tmp = explode('_', ACTION_NAME);
			$action = $tmp[0];
			if(in_array($action, array('view', 'add', 'edit', 'delete', 'order'))) $action = 'view';
			
			$catid = intVal(I('get.catid', 0));
			$info = $category_priv_db->where(array('catid'=>$catid, 'is_admin'=>1, 'action'=>$action))->find();
			if(!$info){
				//兼容iframe加载返回
				if(IS_GET && (IS_AJAX || strpos(ACTION_NAME,'_iframe')!==false) ){
					exit('<style type="text/css">body{margin:0;padding:0}</style><div style="padding:6px;font-size:12px">您没有权限操作该项</div>');
				}else {
					$this->error('您没有权限操作该项');
				}
			}
		}
	}
	
	/**
	 * 内容管理首页
	 */
	public function index(){
		$menu_db = D('Menu');
		$currentpos = $menu_db->currentPos(I('get.menuid'));  //栏目位置
		$this->assign(currentpos, $currentpos);
		$this->display('index');
	}
	
	/**
	 * 介绍
	 */
	public function public_welcome(){
	    $this->show('显示内容管理介绍', 'utf-8');
	}
	
	/**
	 * 右侧栏目
	 */
	public function public_right(){
	    if(IS_POST){
	        if(S('content_public_right')){
    			$data = S('content_public_right');
    		}else{
    	        $category_db = D('Category');
    			$data = $category_db->getCatTree();
    			S('content_public_right', $data);
    		}
			$this->ajaxReturn($data);
		}else{
	        $this->display('right');
		}
	}
	
	/**
	 * 文章列表
	 */
	public function newsList($catid, $page=1, $rows=10, $search = array(), $sort = 'listorder', $order = 'desc'){
		if(IS_POST){
    		$news_db = M('news');
			
			//搜索
			$where = array("`catid` = {$catid}");
			foreach ($search as $k=>$v){
				if(!$v) continue;
				switch ($k){
					case 'title':
						$where[] = "`{$k}` like '%{$v}%'";
						break;
					case 'begin':
						if(!preg_match("/^\d{4}(-\d{2}){2}$/", $v)){
							unset($search[$k]);
							continue;
						}
						if($search['end'] && $search['end'] < $v) $v = $search['end'];
						$v = strtotime($v);
						$where[] = "`inputtime` >= '{$v}'";
						break;
					case 'end':
						if(!preg_match("/^\d{4}(-\d{2}){2}$/", $v)){
							unset($search[$k]);
							continue;
						}
						if($search['begin'] && $search['begin'] > $v) $v = $search['begin'];
						$v = strtotime($v);
						$where[] = "`inputtime` <= '{$v}'";
						break;
				}
			}
			$where = implode(' and ', $where);
			
			$limit=($page - 1) * $rows . "," . $rows;
			$total = $news_db->where($where)->count();
			$order = $sort.' '.$order;
			$field = array('id', 'listorder', 'title', 'username', 'FROM_UNIXTIME(updatetime, "%Y-%m-%d %H:%i:%s") as updatetime', 'FROM_UNIXTIME(inputtime, "%Y-%m-%d %H:%i:%s") as inputtime', 'status', 'id as operateid');
			$list = $total ? $news_db->field($field)->where($where)->order($order)->limit($limit)->select() : array();
    		$data = array('total'=>$total, 'rows'=>$list);
    		$this->ajaxReturn($data);
		}else{
			$datagrid = array(
		        'options'     => array(
    				'url'          => U('Content/newsList', array('grid'=>'datagrid', 'catid'=>$catid)),
    				'toolbar'      => '#content_newslist_datagrid_toolbar',
					'singleSelect' => false,
    			),
		        'fields' => array(
    				'选中'    => array('field'=>'ck', 'checkbox'=>true),
		        	'排序'     => array('field'=>'listorder','width'=>5,'formatter'=>'contentNewsListOrderFormatter'),
		        	'ID'      => array('field'=>'id','width'=>10,'sortable'=>true),
		        	'标题'     => array('field'=>'title','width'=>50,'sortable'=>true),
		        	'发布人'   => array('field'=>'username','width'=>20,'sortable'=>true),
		        	'更新时间' => array('field'=>'updatetime','width'=>20,'sortable'=>true,'formatter'=>'contentNewsListTimeFormatter'),
		        	'状态'    => array('field'=>'status','width'=>10,'sortable'=>true,'formatter'=>'contentNewsListStatusFormatter'),
		        	'管理操作' => array('field'=>'operateid','width'=>15,'formatter'=>'contentNewsListOperateFormatter')
    			)
		    );
		    $this->assign('datagrid', $datagrid);
			$this->assign('catid', $catid);
			$this->display('news_list');
		}
	}
	
	/**
	 * 添加文章
	 */
	public function add_news_iframe($catid){
		if(IS_POST){
			$data = I('post.info', array(), 'trim');
			if(!$data['title'] || !$data['content']) $this->error('请填写必填字段');
			$data['catid'] = $catid;
			//添加时间
			$data['inputtime'] = time();
			//转向链接判断
			if(!isset($data['islink'])) unset($data['url']);
			//状态
			$data['status'] = isset($data['status']) ? '1' : '0';
			
			//自动提取描述
			$data['description'] = $data['description'] ? strip_tags($data['description']) : strip_tags($data['content']);
			if($data['description']) $data['description'] = msubstr(str_replace(array("\n", "\r\n", ' ', '　'), '', strip_tags($data['description'])), 0, 100);
			
			//自动提取缩略图
			if(!$data['thumb']) {
				if(preg_match_all("/(src)=([\"|']?)([^ \"'>]+\.(gif|jpg|jpeg|bmp|png))\\2/i", $data['content'], $matches)) {
					$data['thumb'] = $matches[3][0];
				}
			}
			
			$news_db = M('news');
			$res = $news_db->add($data);
			
			$res ? $this->success('操作成功') : $this->error('操作失败');
		}else{
			$this->assign('catid', $catid);
			$this->display('news_add');
		}
	}
	
	/**
	 * 编辑文章
	 */
	public function edit_news_iframe($catid, $id){
		$news_db = M('news');
		if(IS_POST){
			$data = I('post.info', array(), 'trim');
			if(!$data['title'] || !$data['content']) $this->error('请填写必填字段');
			//编辑时间
			$data['updatetime'] = time();
			//转向链接判断
			if(!isset($data['islink'])){
				unset($data['url']);
				$data['islink'] = 0;
			}
			//状态
			$data['status'] = isset($data['status']) ? '1' : '0';
			
			//自动提取描述
			$data['description'] = $data['description'] ? strip_tags($data['description']) : strip_tags($data['content']);
			if($data['description']) $data['description'] = msubstr(str_replace(array("\n", "\r\n", ' ', '　'), '', strip_tags($data['description'])), 0, 100);
			
			//自动提取缩略图
			if(!$data['thumb']) {
				if(preg_match_all("/(src)=([\"|']?)([^ \"'>]+\.(gif|jpg|jpeg|bmp|png))\\2/i", $data['content'], $matches)) {
					$data['thumb'] = $matches[3][0];
				}
			}
			
			$news_db = M('news');
			$res = $news_db->where(array('catid'=>$catid, 'id'=>$id))->save($data);
			
			$res ? $this->success('操作成功') : $this->error('操作失败');
		}else{
			$info = $news_db->where(array('catid'=>$catid, 'id'=>$id))->find();
			$this->assign('info', $info);
			$this->assign('catid', $catid);
			$this->display('news_edit');
		}
	}
	
	/**
	 * 删除文章
	 */
	public function delete_news($catid){
		if(IS_POST){
			$news_db = M('news');
			$ids = I('post.ids', array());
			foreach($ids as $id) {
				$news_db->where(array('id'=>$id))->delete();
			}
			$this->success('操作成功');
		} else {
			$this->error('操作失败');
		}
	}
	
	/**
	 * 文章排序
	 */
	public function order_news($catid){
		if(IS_POST) {
			$news_db = M('news');
			foreach(I('post.order') as $id => $listorder) {
				$news_db->where(array('id'=>$id))->save(array('listorder'=>$listorder));
			}
			$this->success('操作成功');
		} else {
			$this->error('操作失败');
		}
	}
	
	
	/**
	 * 单页面
	 */
	public function page_iframe($catid){
		$page_db = M('page');
		if(IS_POST){
			$data = I('post.info');
			$data['updatetime'] = time();
			
			if($page_db->where(array('catid'=>$catid))->find()){
				$res = $page_db->where(array('catid'=>$catid))->save($data);
			}else{
				$data['catid'] = $catid;
				$res = $page_db->add($data);
			}
			$res ? $this->success('操作成功') : $this->error('操作失败');
		}else{
			$info = $page_db->where(array('catid'=>$catid))->find();
			$info['content'] = $info['content']; //将上传文件地址转成URL，防止无法显示
			$this->assign('info', $info);
			$this->assign('catid', $catid);
			$this->display('page');
		}
	}
}