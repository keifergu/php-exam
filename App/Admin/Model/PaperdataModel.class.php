<?php
namespace Admin\Model;
use Think\Model;
class PaperdataModel extends Model{
	protected $tableName = 'paper_content';
	function test(){
		$data=array(
			'paper_name'=>rand(1,99),
			'course_id'=>'201',
			);
		$this->add($data);
	}
}
?>