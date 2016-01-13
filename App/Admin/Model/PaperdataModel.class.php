<?php
namespace Admin\Model;
use Think\Model;
class PaperdataModel extends Model{
	protected $tableName = 'paper_content';
	public function paperList($page, $rows, $courseid) {
		$limit = ($page - 1) * $rows . "," . $rows;
		$condition =array(
			'course_id' => $courseid
			);
		$list=$this->where ( $condition )->limit ( $limit )->select ();
		$total=$this->where ( $condition )->count ();
		if($total>0){
			foreach ($list as $key=>$value){
				foreach ($value as $key1=>$value1){
					$data[$key][$key1]=$value1;
				}
				$coursename = array ();
				$coursename=getDictName( $list [$key] ['course_id'] );
				$data [$key] ['course_id'] = $coursename [0] ['type_name'];
			}
			$result=array(
				'total' => $total,
				'rows' => $data
				);
		}else{
			$result=array(
				'total' =>0,
				'rows' => []
				);
		}
		return $result;
	}
}
?>