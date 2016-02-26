<?php
namespace Home\Logic;
class LoginLogic {
	public function loginCheck()
	{
		$studentID = I('session.student_id',null);
		return $studentID;
	}

	public function apiLoginCheck()
	{
		$studentID = I('session.student_id',null);
		if ($studentID == null) {
			$this->ajaxReturn(false);
		}
		return $studentID;
	}
}