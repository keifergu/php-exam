<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;

/**
 * 栏目相关模块
 * @author wangdong
 */
class CategoryController extends CommonController {
	/**
	 * 栏目管理
	 */
	public function categoryList(){
		if(IS_POST){
			if(S('category_categorylist')){
    			$data = S('category_categorylist');
    		}else{
    			$category_db = D('Category');
    			$data = $category_db->getTree();
    			S('category_categorylist', $data);
    		}
    		$this->ajaxReturn($data);
		}else{
			$menu_db = D('Menu');
			$currentpos = $menu_db->currentPos(I('get.menuid'));  //栏目位置
			$treegrid = array(
		        'options'       => array(
    				'title'     => $currentpos,
    				'url'       => U('Category/categoryList', array('grid'=>'treegrid')),
    				'idField'   => 'catid',
    				'treeField' => 'catname',
    				'toolbar'   => 'category_categorylist_treegrid_toolbar',
    			),
		        'fields' => array(
		        	'排序'    => array('field'=>'listorder','width'=>15,'align'=>'center','formatter'=>'categoryCategoryListOrderFormatter'),
		        	'栏目ID'  => array('field'=>'catid','width'=>25,'align'=>'center'),
		        	'栏目名称' => array('field'=>'catname','width'=>130),
		        	'栏目类型' => array('field'=>'type','width'=>30,'formatter'=>'categoryCategoryListTypeFormatter'),
		        	'描述'    => array('field'=>'description','width'=>80),
    				'状态'    => array('field'=>'ismenu','width'=>20,'formatter'=>'categoryCategoryListStateFormatter'),
		        	'管理操作' => array('field'=>'operateid','width'=>50,'align'=>'center','formatter'=>'categoryCategoryListOperateFormatter'),
    			)
		    );
		    $this->assign('treegrid', $treegrid);
			$this->display('category_list');
		}
	}
	
	/**
	 * 添加栏目
	 */
	public function categoryAdd(){
		if(IS_POST){
			$category_db = D('Category');
			$data = I('post.info');
    		$data['ismenu'] = $data['ismenu'] ? '1' : '0';
    		$id = $category_db->add($data);
    		if($id){
    			$category_db->clearCatche();
    			$this->success('添加成功');
    		}else {
    			$this->error('添加失败');
    		}
		}else{
			$this->assign('typelist', C('CONTENT_CATEGORY_TYPE'));
			$this->display('category_add');
		}
	}
	
	/**
	 * 编辑栏目
	 */
	public function categoryEdit($id){
		$category_db = D('Category');
		if(IS_POST){
			$data = I('post.info');
    		$data['ismenu'] = $data['ismenu'] ? '1' : '0';
    		$res = $category_db->where(array('catid'=>$id))->save($data);
    		if($res){
    			$category_db->clearCatche();
    			$this->success('操作成功');
    		}else {
    			$this->error('操作失败');
    		}
		}else{
			$info = $category_db->where(array('catid'=>$id))->find();
			$this->assign('info', $info);
			$this->assign('typelist', C('CONTENT_CATEGORY_TYPE'));
			$this->display('category_edit');
		}
	}
	
	/**
	 * 删除栏目
	 */
	public function categoryDelete($id = 0){
		if($id && IS_POST){
			$category_db = D('Category');
    		$result = $category_db->where(array('catid'=>$id))->delete();
    		if($result){
    			$category_db->clearCatche();
    			$this->success('删除成功');
    		}else {
    			$this->error('删除失败');
    		}
    	}else{
			$this->error('删除失败');
    	}
	}
	
	/**
	 * 栏目排序
	 */
	public function categoryOrder(){
		if(IS_POST) {
			$category_db = D('Category');
			foreach(I('post.order') as $id => $listorder) {
				$category_db->where(array('catid'=>$id))->save(array('listorder'=>$listorder));
			}
			$category_db->clearCatche();
			$this->success('操作成功');
		} else {
			$this->error('操作失败');
		}
	}
	
	/**
	 * 栏目下拉框
	 */
	public function public_categorySelect(){
		if(S('category_public_categoryselect')){
    		$data = S('category_public_categoryselect');
    	}else {
    		$category_db = D('Category');
    		$data = $category_db->getSelectTree();
    		$data = array(0=>array('id'=>0,'text'=>'作为一级栏目','children'=>$data));
    		S('category_public_categoryselect', $data);
    	}
    	$this->ajaxReturn($data);
	}
}