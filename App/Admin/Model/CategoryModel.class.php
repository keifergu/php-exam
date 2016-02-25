<?php
namespace Admin\Model;
use Think\Model;

class CategoryModel extends Model{
	protected $tableName = 'category';
	protected $pk        = 'catid';
	
	//获取栏目列表
	public function getTree($parentid = 0){
		$field = array('catid','`catname`','type','description','ismenu','listorder','`catid` as `operateid`');
		$order = '`listorder` ASC,`catid` DESC';
		$data = $this->field($field)->where(array('parentid'=>$parentid))->order($order)->select();
		if (is_array($data)){
			foreach ($data as &$arr){
				$arr['children'] = $this->getTree($arr['catid']);
			}
		}else{
			$data = array();
		}
		return $data;
	}
	
	//栏目下拉列表
	public function getSelectTree($parentid = 0){
		$field = array('`catid` as `id`','`catname` as `text`');
		$order = '`listorder` ASC,`id` DESC';
		$data = $this->field($field)->where(array('parentid'=>$parentid))->order($order)->select();
		if (is_array($data)){
			foreach ($data as &$arr){
				$arr['children'] = $this->getSelectTree($arr['id']);
			}
		}else{
			$data = array();
		}
		return $data;
	}
	
	//内容管理右侧导航
	public function getCatTree($parentid = 0){
		$field = array('catid as id','`catname` as `text`','type', 'url');
		$order = '`listorder` ASC,`id` DESC';
		$data = $this->field($field)->where(array('parentid'=>$parentid, 'ismenu'=>1, 'type'=>array('NEQ',2)))->order($order)->select();
		if (is_array($data)){
			foreach ($data as $k=>&$arr){
				$arr['children'] = $this->getCatTree($arr['id']);
				
				//等easyui自定义图标bug修复后在这里设置自定义图标
//				if(!is_array($arr['children']) || empty($arr['children']) ){
//				    //$arr['iconCls'] = 'icon-add';
//				}
			}
		}else{
			$data = array();
		}
		return $data;
	}
	
	//清除栏目相关缓存
	public function clearCatche(){
		S('category_categorylist', null);
		S('category_public_categoryselect', null);
		S('content_public_right', null);
	}
}