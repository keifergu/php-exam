<?php
namespace Home\Model;
use Think\Model;
class SubmitModel extends Model {
	protected $tableName = 'submit_exam';

	protected $_validate = array(
		array('num'));

}