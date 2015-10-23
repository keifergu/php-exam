<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
use Think\Log;
class OptiondataController extends CommonController
{
    function optionList($page = 1, $rows = 10, $sort = 'dictid', $order = 'asc' ,$courseid = '',$typeid = '')
    {
        // var_dump($courseid);
        // var_dump($typeid);
        if (IS_POST && $courseid !='' && $typeid !='')
        {

            $option_db=M('Optiondata');
            $total = $option_db->count();
            $order = $sort.' '.$order;
            $limit = ($page - 1) * $rows . "," . $rows;
            $condition['course']=$courseid;
            $condition['type']=$typeid;
            $list = $option_db->where($condition)->limit($limit)->select();
            // if ($courseid=='' && $typeid=='') {
            //     $list = $option_db->limit($limit)->select();
            // }
            // var_dump($option_db->getLastSql());
            $arr= object_array($list);
            $total=count($arr);
            if ($total>0) {
                foreach ($arr as $key=>$value)
                {
                    foreach ($value as $key1=>$value1)
                    {
                        $data[$key][$key1]=$value1;
                    }
                    $coursename=array();
                    $coursename=object_array(getDictName($arr[$key]["course"]));
                    $data[$key]["coursename"]=$coursename[0]["type_name"];
                    $coursename=object_array(getDictName($arr[$key]["type"]));
                    $data[$key]["typename"]=$coursename[0]["type_name"];
                }
                $data2 = array('total'=>$total, 'rows'=>$data);//这句话可以控制显示的内容
            }
            else
            {
                $data2 = array('total'=>0, 'rows'=>[]);
            }
            echo json_encode($data2);
        }
        else 
        {

            $this->display('option_index');
        }
    }
    function optionAdd()
    {
        if (IS_POST)
        {
           
        }
        else
        {
           $this->display('option_add');
        }
    }

}
?>