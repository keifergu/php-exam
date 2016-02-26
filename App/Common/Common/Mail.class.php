<?php
namespace Common\Common;

class Mail{
	private $host;               //主机
	private $port;               //端口一般为25
	private $from;               //发信人
	private $user;               //SMTP帐号
	private $pass;               //密码
	private $func        = null; //当前邮件发送方法
	private $cmdFunc     = null; //当前邮件命令发送方式
	private $state       = true; //邮件命令返回状态
	public  $error       = null; //错误记录
	public  $debug       = false;//开启调试
	public  $mailformat  = 0;    //邮件格式 0=普通文本 1=html邮件
	
	public function __construct($host, $port, $user, $pass, $debug = false){
		$host = gethostbyname($host);
		$this->host  = $host;
		$this->port  = $port;
		$this->from  = $user;
		$this->user  = base64_encode($user);
		$this->pass  = base64_encode($pass);
		$this->debug = $debug;
		if(function_exists('socket_create')){
			$this->func = 'socket_create';
			$this->cmdFunc = '_socketCmd';
			$this->showDebug('检测函数 '.$this->func.' 通过');
		}elseif(function_exists('fsockopen')){
			$this->func = 'fsockopen';
			$this->cmdFunc = '_fsocketopenCmd';
			$this->showDebug('检测函数 '.$this->func.' 通过');
		}else {
			$this->showDebug('当前环境不支持发送邮件', true);
		}
	}
	
	//是否开启HTML格式
	function isHTML(){
		$this->mailformat = 1;
	}
	
	//发送方法
	public function send($to='', $subject='', $body=''){
		if(!$this->func) return false;
		if(!$to || !$subject || !$body){
			$this->showDebug('收信人信息不全', true);
			return false;
		}
		$this->to      = $to;      //收信人
		$this->subject = $subject; //邮件主题
		$this->body    = $body;    //邮件内容
		
		$result = false;
		switch ($this->func){
			case 'socket_create':
				$result = $this->_socketSend();
				break;
			case 'fsockopen':
				$result = $this->_fsocketopenSend();
				break;
			default:
				$this->showDebug('调用方法不存在', true);
				return false;
		}
		return $result;
	}
	
	//调试输出
	private function showDebug($txt, $saveError = false){
		if($saveError) $this->error = $txt;	//记录到错误
		if($this->debug) echo "<p>{$txt}</p>\n";
	}
	
	// socket_create函数发送
	private function _socketSend(){
		$this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if($this->socket){
			$this->showDebug("创建SOCKET:".socket_strerror(socket_last_error()));
		}else{
			$this->showDebug('初始化失败，请检查您的网络连接和参数', true);
			return false;
		}
		$conn = socket_connect($this->socket, $this->host, $this->port);
		if($conn){
			$this->showDebug("创建SOCKET连接:".socket_strerror(socket_last_error()));
		}else{
			$this->showDebug('初始化失败，请检查您的网络连接和参数', true);
			return false;
		}
		$this->showDebug("服务器应答：<font color=#cc0000>".socket_read($this->socket, 1024)."</font>");
		return $this->_doSend();
	}
	
	// fsockopen函数发送
	private function _fsocketopenSend(){
		$this->socket = fsockopen($this->host, $this->port, $errno, $errstr, 60); 
		if($this->socket){
			$this->showDebug("创建SOCKET连接:".$this->host.':'.$this->port);
		}else{
			$this->showDebug('初始化失败，请检查您的网络连接和参数'.$errstr, true);
			return false;
		}
		stream_set_blocking($this->socket, true);
		return $this->_doSend();
	}
	
	//邮件发送过程
	private function _doSend(){
		$all = array();
		array_push($all, "From:".$this->from."\r\n");
		array_push($all, "To:".$this->to."\r\n");
		array_push($all, "Subject:=?utf-8?B?".base64_encode($this->subject)."?=\r\n");
		//邮件类型 html或文本
		if($this->mailformat==1){
		    array_push($all, "Content-Type: text/html;\r\n");
		}else{
		    array_push($all, "Content-Type: text/plain;\r\n");
		}
		array_push($all, "charset: utf-8\r\n");
		//告诉浏览器信件内容进过了base64编码，最后必须要发一组\r\n产生一个空行,表示头部信息发送完毕
		array_push($all, "Content-Transfer-Encoding: base64\r\n\r\n");
		array_push($all, base64_encode($this->body));
		
		$all = implode('', $all);
		
		
		//以下是和服务器会话
		$this->{$this->cmdFunc}("EHLO HELO\r\n");
		if(!$this->state) return false;
		
		// fsockopen需要单独处理
		while ($this->func == 'fsockopen') {
			$lastmessage = fgets($this->socket, 512);
			if ( (substr($lastmessage,3,1) != "-")  or  (empty($lastmessage)) )
			break;
		}
		$this->{$this->cmdFunc}("AUTH LOGIN\r\n");
		if(!$this->state) return false;
		
		$this->{$this->cmdFunc}($this->user."\r\n");
		if(!$this->state) return false;
		
		$this->{$this->cmdFunc}($this->pass."\r\n", '235', 'smtp 认证失败');
		if(!$this->state) return false;

		$this->{$this->cmdFunc}("MAIL FROM:<".$this->from.">\r\n", '250', '邮件发送失败');
		if(!$this->state) return false;
		
		$this->{$this->cmdFunc}("RCPT TO:<".$this->to.">\r\n", '250', '邮件发送失败');
		if(!$this->state) return false;
		
		$this->{$this->cmdFunc}("DATA\r\n", '354', '邮件发送失败');
		if(!$this->state) return false;
		
		$this->{$this->cmdFunc}($all."\r\n.\r\n", '250', '邮件发送失败');
		if(!$this->state) return false;
		
		$this->{$this->cmdFunc}("QUIT\r\n");
		if(!$this->state) return false;
		
		return true;
	}
	
	//socket_create函数发送命令
	private function _socketCmd($send, $checkCode = '', $error=''){
		socket_write($this->socket, $send, strlen($send));
		$this->showDebug("客户机命令：".$send);
		$lastmessage = socket_read($this->socket, 1024);
		$this->showDebug("服务器应答：<font color=#cc0000>".$lastmessage."</font>");
		if($checkCode && strpos($lastmessage, $checkCode) === false){
			$this->showDebug($error, true);
			$this->state = false;
		}
	}
	
	//fsockopen函数命令发送
	private function _fsocketopenCmd($send, $checkCode = '', $error=''){
		fputs($this->socket, $send);
		$this->showDebug("客户机命令：".$send);
		$lastmessage = fgets($this->socket, 512);
		$this->showDebug("服务器应答：<font color=#cc0000>".$lastmessage."</font>");
		if($checkCode && strpos($lastmessage, $checkCode) === false){
			$this->showDebug($error, true);
			$this->state = false;
		}
	}
	
	//关闭socket
	public function __destruct(){
		switch ($this->func){
			case 'socket_create':
				socket_close($this->socket);
				break;
			case 'fsockopen':
				fclose($this->socket);
				break;
		}
	}
}