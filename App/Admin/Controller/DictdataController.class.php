<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
use Think\Log;
/**
 * 后台管理员相关模块
 * @author wangdong
 */
class DictdataController extends CommonController 
{
    public function dictList($page = 1, $rows = 10, $sort = 'dictid', $order = 'asc'){
        if(IS_POST){
            $dict_db = D('Dictdata');
            $total = $dict_db->count();
            $order = $sort.' '.$order;
            $limit = ($page - 1) * $rows . "," . $rows;
            $field=array('dict_id','fname','value1','value2');
            $list = $dict_db->field($field)->limit($limit)->select();
            $data = array('total'=>$total, 'rows'=>$list);
            echo json_encode($data);
        }else
        {
            $this->display('dict_list');
            
        }
    }
    
    /**
     * 添加管理员
     */
    public function dictAdd(){
       // if(IS_POST){
            $dict_db = D('Dictdata');
            $data['fname'] = $_REQUEST['fname'];
            $data['value1']= $_REQUEST['value1'];
            $data['value2'] = $_REQUEST['value2'];
            $id = $dict_db->add($data);
            if($id){
                $res['message']='添加成功';
                $res['status']=true;
                //$this->success('添加成功');
            }else {
                $res['message']='添加失败';
                $res['status']=false;
                //$this->error('添加失败');
            }
            $this->ajaxreturn($res);
    }
    
    /**
     * 编辑管理员
     */
    public function dictEdit($id)
    {
        $dict_db = D('Dict');
        if(IS_POST)
        {
            //if($id == '1') $this->error('该用户不能被修改');
            $data['fname'] = $_REQUEST['fname'];
            $data['value1']= $_REQUEST['value1'];
            $data['value2'] = $_REQUEST['value2'];
            $result = $dict_db->where(array('dict_id'=>$id))->save($data);
            if($result){
                $res['message']='更新成功';
                $res['status']=true;
                //$this->success('添加成功');
            }else {
                $res['message']='更新失败';
                $res['status']=false;
                //$this->error('添加失败');
            }
            $this->ajaxreturn($res);
        }
       else
        {
        }
    }
    
    /**
     * 删除数据字典
     */
    public function dictDelete($id)
    {
        $dict_db = D('Dict');
        $data['id'] = $id;
        $result = $dict_db->where(array('dict_id'=>$data['id']))->delete();
        if ($result){
            $this->success('删除成功');
        }else {
            $this->error('删除失败');
        }
    }    
    public function checkName($name){
        $dict_db = D('Dictdata');
       $exists=$dict_db->where(array('fname'=>$name))->field('fname')->find();
     if ($exists) {
            $this->success('别名存在');
        }else{
            $this->error('别名不存在');
        }
        
    }
    public function getFirstDict(){
        $dict_db=D('Dictdata');
        $field=array('dict_id','fname');
        $exists=$list = $dict_db->field($field)->where(array('value1'=>5))->select();
        if($exists){
            echo json_encode($list);
        }else 
        {
            $res['message']='更新失败';
            $res['status']=false;
            echo($res);
        }
       
    }
    function getDictName($id)
    {
        $dict_db=D('Dictdata');
        // $pid=mysql_real_escape_string($id);
        // $rs = mysql_query("select * from data_dict where type_id='$pid'");
        // $result = array();
        // while($row = mysql_fetch_object($rs))
        // {
        //     array_push($result, $row);
        // }
        $field=array('type_id','type_name');
        $condition['type_id']=$id;
        $result=$dict_db->field($field)->where($condition)->select();
        return $result;
    }
    function getDictNameJson($id)
    {
        $dict_db=D('Dictdata');
        $field=array('type_id','type_name');
        $condition['belong_type']=$id;
        $result=$dict_db->field($field)->where($condition)->select();
        echo json_encode($result);
    }
}