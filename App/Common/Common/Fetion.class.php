<?php
namespace Common\Common;

class Fetion {
	protected $_username;		     //发送者手机号
	protected $_password;		     //飞信密码
	protected $_cookie    = '';
	protected $_uids      = array();
	protected $_csrfToten = null;
	public    $error      = '';
	
	public function __construct($username=null, $password=null){
		if(!$username || !$password){
			$this->_setError('用户信息不能为空');
			return false;
		}
		$this->_username = $username;
		$this->_password = $password;
		$this->_login();
	}
	
	public function __destruct(){
		$this->_logout();
	}
	
	/**
     * 向指定的手机号发送飞信
     * @param string $mobile 手机号(接收者)
     * @param string $message 短信内容
     * @return string
     */
    public function send($mobile, $message){
        if ($message === ''){
			$this->_setError('发现消息不能为空');
			return false;
		}
        //判断是给自己发还是给好友发
        if ($mobile == $this->_username){
            $result = $this->_toMyself($message);
        }else{
            $uid = $this->_getUid($mobile);
			if(strlen($uid) > 0){
				$result = $this->_toUid($uid, $message);
			}else{
				$this->_setError('请确认是否已添加对方为好友');
				return false;
			}
        }
        if(strstr($result, '成功') === false){
        	$this->_setError('消息发送失败，请重试');
        	return false;
        }
        return true;
    }
	
	private function _login(){
		$url = 'http://f.10086.cn/huc/user/space/login.do?m=submit&fr=space';
		$params = array(
			'mobilenum'=>$this->_username,
			'password'=>$this->_password
		);
		$data = $this->_post($url, $params, 'write');
		preg_match_all('/.*?\r\nSet-Cookie: (.*?);.*?/si', $data, $matches);
        if (isset($matches[1])){
            $this->_cookie = implode('; ', $matches[1]);
        }
		
		$url = 'http://f.10086.cn/im/login/cklogin.action';
		$this->_post($url);
	}
	
	private function _logout(){
		$url = 'http://f.10086.cn/im/index/logoutsubmit.action';
		return $this->_post($url);
	}
    
	/**
     * 获取飞信ID
     * @param string $mobile 手机号
     * @return string
     */
    private function _getUid($mobile){
        if (empty($this->_uids[$mobile])){
			$url = 'http://f.10086.cn/im/index/searchOtherInfoList.action';
			$params = array(
				'searchText' => $mobile
			);
            $result = $this->_post($url, $params);
            //匹配
            preg_match('/toinputMsg\.action\?touserid=(\d+)/si', $result, $matches);
			$this->_uids[$mobile] = isset($matches[1]) ? $matches[1] : '';
        }
        return $this->_uids[$mobile];
    }
    
 	/**
     * 获取csrfToken，给好友发飞信时需要这个字段
     * @param string $uid 飞信ID
     * @return string
     */
    private function _getCsrfToken($uid){
        if ($this->_csrfToten === null){
        	$url = 'http://f.10086.cn/im/chat/toinputMsg.action?touserid='.$uid;
            $result = $this->_post($url);
            preg_match('/name="csrfToken".*?value="(.*?)"/', $result, $matches);
			$this->_csrfToten = isset($matches[1]) ? $matches[1] : '';
        }
		return $this->_csrfToten;
    }
    
	/**
     * 向好友发送飞信
     * @param string $uid 飞信ID
     * @param string $message 短信内容
     * @return string
     */
    private function _toUid($uid, $message){
    	$url = 'http://f.10086.cn/im/chat/sendMsg.action?touserid='.$uid;
		$params = array(
			'msg'       => $message,
			'csrfToken' => $this->_getCsrfToken($uid)
		);
		return $this->_post($url, $params);
    }
    
	/**
     * 给自己发飞信
     * @param string $message
     * @return string
     */
    private function _toMyself($message){
		$url = 'http://f.10086.cn/im/user/sendMsgToMyselfs.action';
		$params = array(
			'msg' => $message
		);
		return $this->_post($url, $params);
    }
	
	private function _setError($error=null){
		if ($error) $this->error = $error;
	}
	
	/**
	 * 发送HTTP请求方法，目前只支持CURL发送请求
	 * @param  string $url    请求URL
	 * @param  array  $params 请求参数
	 * @param  string $method 请求方法GET/POST
	 * @return array  $data   响应数据
	 */
	private function _post($url, $params=array()){
		$curlPost = http_build_query($params);
		
		/* 初始化并执行curl请求 */
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		//启用时会将头文件的信息作为数据流输出
		curl_setopt($ch,CURLOPT_HEADER,1); 
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_POST,1);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$curlPost);
		//cookie设置
		curl_setopt($ch,CURLOPT_COOKIE,$this->_cookie);
		
		$data  = curl_exec($ch);
		$error = curl_error($ch);
		curl_close($ch);
		if($error){
			$this->_setError('请求发生错误：' . $error);
			return false;
		}
		return $data;
	}
}