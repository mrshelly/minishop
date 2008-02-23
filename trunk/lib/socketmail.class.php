<?php
/**
 * 简介: Socket发送邮件
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-09-05 樊振兴 创建了本文件（类）
 * @example:
require_once('socketmail.class.php');
$myconfig = array(
		'client_name'	=> 'anonymous',				// 请求时的客户端名称
		'server_host'	=> 'smtp.example.com',				// smtp服务器domain或ip地址，例如smtp.163.com
		'server_port'	=> '25',					// smtp服务器服务端口默认25
		'request_auth'	=> TRUE,					// smtp服务器是否需要身份认证
		'user_name'		=> 'user',						// 认证使用的用户名
		'user_pass'		=> 'pass',						// 认证使用的密码
		'mail_from'		=> 'anonymous@example.com'	// 信件的发件人邮件地址
	);
$mysetup = array(
		'mail_charset'	=> 'UTF-8',					// 信件的字符编码格式
		'mail_encode'	=> 'base64'					// 信件的编码方式
	);

$mailhandle = new SocketMail($myconfig); // 初始化邮件
$mailhandle->setup($mysetup); // 邮件设置

$to = 'someone@example.com'; // 收件人地址
$mysubject = 'mail subject'; // 信件标题
$mymessage = 'mail message'; // 信件内容
$addonheader = "Mime-Version: 1.0\r\n"."Content-Type: text/html; charset=\"{$mysetup['mail_charset']}\"\r\n"; // 附加邮件头

if($mailhandle->mail($to,$mysubject,$mymessage,$addonheader))
{
	echo 'ok';
}

 * @comment

非认证形式的smtp过程
HELO [anonymous]
MAIL FROM: [anonymous@example.com]
RCPT TO: [someone@example.com]
DATA
Subject: [mail subject]
[message body]

.

QUIT

认证形式的esmtp过程

EHLO [anonymous]
AUTH LOGIN 
[base64(user)]
[base64(pass)]
MAIL FROM: [anonymous@example.com]
RCPT TO: [someone@example.com]
DATA
Subject: [mail subject]
[message body]

.

QUIT

 */
class SocketMail {
	// 基本配置
	var $config = array(
			'client_name'	=> 'anonymous',				// 请求时的客户端名称
			'server_host'	=> '127.0.0.1',				// smtp服务器domain或ip地址，例如smtp.163.com
			'server_port'	=> '25',					// smtp服务器服务端口默认25
			'request_auth'	=> FALSE,					// smtp服务器是否需要身份认证
			'user_name'		=> '',						// 认证使用的用户名
			'user_pass'		=> '',						// 认证使用的密码
			'mail_from'		=> 'anonymous@example.com'	// 信件的发件人邮件地址
		);

	// 邮件设置
	var $setup = array(
			'mail_charset'	=> 'UTF-8',					// 信件的字符编码格式
			'mail_encode'	=> 'base64'					// 信件的编码方式
		);

	// socket句柄
	var $fp = null;

	// socket超时
	var $timeOut =30;

	// 是否显示错误
	var $showError = false;

	// 错误
	var $errors = array();

	// sended
	var $sends = array();

	// log
	var $logcontent = '';

	// 消息
	var $msg = array(
		'err_socket'=>'socket connect error:',
		'err_conn'=>'server connect error',
		'err_helo'=>'helo error',
		'err_auth'=>'auth error',
		'err_auth_pw'=>'auth pass error',
		'err_rset'=>'rset error',
		'err_comm'=>'communicate error'
		);

	// 构造函数
	function SocketMail($config) {
		if(!empty($config) && is_array($config)) {
			array_key_exists('client_name',$config) && $this->config['client_name'] = $config['client_name'];
			array_key_exists('server_host',$config) && $this->config['server_host'] = $config['server_host'];
			array_key_exists('server_port',$config) && $this->config['server_port'] = $config['server_port'];
			array_key_exists('request_auth',$config) && $this->config['request_auth'] = $config['request_auth'];
			array_key_exists('user_name',$config) && $this->config['user_name'] = $config['user_name'];
			array_key_exists('user_pass',$config) && $this->config['user_pass'] = $config['user_pass'];
			array_key_exists('mail_from',$config) && $this->config['mail_from'] = $config['mail_from'];
		}
		if(PHP_VERSION < 5){
			register_shutdown_function(array($this, "__destruct"));
		}
		if($this->init()){
			return true;
		}else{
			return false;
		}
	}

	// 获取socket应答
	function getAnswer() {
		if(!$this->fp){
			return ;
		}
		return @fgets($this->fp, 512);
	}

	function sendRequest($cmd) {
		if(!$this->fp){
			return ;
		}
		$this->sends[]=$cmd;
		return @fputs($this->fp, $cmd);
	}

	// 显示错误，有参数时设置是否显示
	function showError() {
		if(func_num_args()>1)
		{
			$this->showError = func_get_arg(0);
			return true;
		}
		else
		{
			return $this->showError;
		}
	}

	// 输出返回信息log
	function dumpLog() {
		return $this->logcontent;
	}

	// 输出发送信息列表
	function dumpSends() {
		return $this->sends;
	}

	// 邮件配置
	function setup($setup) {
		if(!empty($setup) && is_array($setup)) {
			array_key_exists('mail_charset',$setup) && $this->config['mail_charset'] = $setup['mail_charset'];
			array_key_exists('mail_encode',$setup) && $this->config['mail_encode'] = $setup['mail_encode'];
		}
	}

	// 返回默认附加头数据
	function getDefaultAddonHeader($charset='UTF-8') {
		return "Mime-Version: 1.0\r\n"."Content-Type: text/html; charset=\"".(!empty($charset)?$charset:(isset($this->config['mail_charset'])?$this->config['mail_charset']:$this->setup['mail_charset']))."\"\r\n";
	}

	// 系统初始化
	function init() {
		if(!$this->fp = @fsockopen($this->config['server_host'],$this->config['server_port'], &$errno, &$errstr, $this->timeOut)){
			$this->errors[]= $this->msg['err_socket']."{$errstr} ( {$errno} ) ";
			$this->showError && trigger_error(array_pop($this->errors), E_USER_ERROR);
			return false;
		}
		 
		if(substr($respBytes=$this->getAnswer(),0,3)!='220'){
			$this->logcontent.=$respBytes."\n";
			$this->errors[]= $this->msg['err_conn'];
			$this->showError && trigger_error(array_pop($this->errors), E_USER_ERROR);
			return false;
		}

		if($this->config['request_auth']){
			$this->sendRequest("EHLO {$this->config['client_name']}\r\n");
			while($rt=strtolower($respBytes=$this->getAnswer())){
				if(strpos($rt,"-")!==3 || empty($rt)){
					$this->logcontent.=$respBytes."\n";
					break;
				}elseif(strpos($rt,"2")!==0){
					$this->logcontent.=$respBytes."\n";
					$this->errors[]= $this->msg['err_helo'];
					$this->showError && trigger_error(array_pop($this->errors), E_USER_WARNING);
					return false;
				}
			}

			$this->sendRequest("AUTH LOGIN \r\n");
			if(substr($respBytes=$this->getAnswer(),0,3) != '334'){
				$this->logcontent.=$respBytes."\n";
				$this->errors[]= $this->msg['err_auth'];
				$this->showError && trigger_error(array_pop($this->errors), E_USER_ERROR);
				return false;
			}

			$this->sendRequest(base64_encode($this->config['user_name'])."\r\n");
			if(substr($respBytes=$this->getAnswer(),0,3) != '334'){
				$this->logcontent.=$respBytes."\n";
				$this->errors[]= $this->msg['err_auth'];
				$this->showError && trigger_error(array_pop($this->errors), E_USER_ERROR);
				return false;
			}

			$this->sendRequest(base64_encode($this->config['user_pass'])."\r\n");
			if(substr($respBytes=$this->getAnswer(),0,3) != '235'){
				$this->logcontent.=$respBytes."\n";
				$this->errors[]= $this->msg['err_auth_pw'];
				$this->showError && trigger_error(array_pop($this->errors), E_USER_ERROR);
				return false;
			}
		}
		else
		{
			$this->sendRequest("HELO {$this->config['client_name']}\r\n");
			if(substr($respBytes=$this->getAnswer(),0,3) != '250'){
				$this->logcontent.=$respBytes."\n";
				$this->errors[]= $this->msg['err_auth'];
				$this->showError && trigger_error(array_pop($this->errors), E_USER_WARNING);
				return false;
			}
		}
		return true;
	}


	// 邮件发送方地址，有参数时设置发送方地址
	function mailFrom() {
		if(func_num_args()>1)
		{
			$this->config['mail_from'] = func_get_arg(0);
			return true;
		}
		else
		{
			return $this->config['mail_from'];
		}
	}

	// 发送邮件
	function mail($to, $subject, $message, $addonHeader = null, $addonParam = null) {
		if(!$this->fp){
			return ;
		}

		$this->sendRequest("RSET \r\n");
		if(substr($respBytes=$this->getAnswer(),0,3) != '250'){
			$this->logcontent.=$respBytes."\n";
			$this->errors[]= $this->msg['err_rset'];
			$this->showError && trigger_error(array_pop($this->errors), E_USER_ERROR);
			return false;
		}

		$this->sendRequest("MAIL FROM: <{$this->config['mail_from']}>\r\n");
		if(substr($respBytes=$this->getAnswer(),0,3) != '250'){
			$this->logcontent.=$respBytes."\n";
			$this->errors[]= $this->msg['err_comm'];
			$this->showError && trigger_error(array_pop($this->errors), E_USER_ERROR);
			return false;
		}

		$this->sendRequest("RCPT TO: <{$to}>\r\n");
		if(substr($respBytes=$this->getAnswer(),0,3) != '250'){
			$this->logcontent.=$respBytes."\n";
			$this->errors[]= $this->msg['err_comm'];
			$this->showError && trigger_error(array_pop($this->errors), E_USER_ERROR);
			return false;
		}

		$this->sendRequest("DATA\r\n");
		if(substr($respBytes=$this->getAnswer(),0,3) != '354'){
			$this->logcontent.=$respBytes."\n";
			$this->errors[]= $this->msg['err_comm'];
			$this->showError && trigger_error(array_pop($this->errors), E_USER_ERROR);
			return false;
		}


		$subject = str_replace("\n",' ',$subject);
		$sendmsg  = "Date: ".date("r")."\r\n";
		if(is_array($addonParam)){
			if(array_key_exists('head_from',$addonParam)){
				$sendmsg .= "From: {$addonParam['head_from']} \r\n";
			}else{
				$sendmsg .= "From: {$this->config['mail_from']} \r\n";
			}
			if(array_key_exists('head_to',$addonParam)){
				$sendmsg .= "To: {$addonParam['head_to']}\r\n";
			}else{
				$sendmsg .= "To: {$to}\r\n";
			}
		}else{
			$sendmsg .= "From: {$this->config['mail_from']} \r\n";
			$sendmsg .= "To: {$to}\r\n";
		}

		$sendmsg .= "Subject: =?{$this->setup['mail_charset']}?B?".base64_encode($subject)."?=\r\n";
		$sendmsg .= "X-mailer: Php SocketMail Sender by nickfan \r\n";
		//$sendmsg .= "Mime-Version: 1.0\r\n";

		!empty($addonHeader) && $sendmsg .= $addonHeader;

		if($this->setup['mail_encode']=='base64')
		{
			$sendmsg .= "Content-Transfer-Encoding: base64\r\n\r\n";
			$sendmsg .= chunk_split(base64_encode($message))."\r\n.\r\n";
		}
		else
		{
			$sendmsg .= $message."\r\n.\r\n";
		}

		$this->sendRequest($sendmsg);
		return true;
	}


	// 发送邮件
	function mailMulti($mailAddrs, $subject, $message, $addonHeader = null, $addonParam = null) {
		if(!$this->fp || !is_array($mailAddrs)){
			return ;
		}

		$this->sendRequest("RSET \r\n");
		if(substr($respBytes=$this->getAnswer(),0,3) != '250'){
			$this->logcontent.=$respBytes."\n";
			$this->errors[]= $this->msg['err_rset'];
			$this->showError && trigger_error(array_pop($this->errors), E_USER_ERROR);
			return false;
		}

		$this->sendRequest("MAIL FROM: <{$this->config['mail_from']}>\r\n");
		if(substr($respBytes=$this->getAnswer(),0,3) != '250'){
			$this->logcontent.=$respBytes."\n";
			$this->errors[]= $this->msg['err_comm'];
			$this->showError && trigger_error(array_pop($this->errors), E_USER_ERROR);
			return false;
		}

		foreach($mailAddrs as $mailaddr){
			$this->sendRequest("RCPT TO: <{$mailaddr}>\r\n");
			if(substr($respBytes=$this->getAnswer(),0,3) != '250'){
				$this->logcontent.=$respBytes."\n";
				$this->errors[]= $this->msg['err_comm'];
				$this->showError && trigger_error(array_pop($this->errors), E_USER_ERROR);
				return false;
			}
		}

		$this->sendRequest("DATA\r\n");
		if(substr($respBytes=$this->getAnswer(),0,3) != '354'){
			$this->logcontent.=$respBytes."\n";
			$this->errors[]= $this->msg['err_comm'];
			$this->showError && trigger_error(array_pop($this->errors), E_USER_ERROR);
			return false;
		}

		$subject = str_replace("\n",' ',$subject);
		$sendmsg  = "Date: ".date("r")."\r\n";
		if(is_array($addonParam)){
			if(array_key_exists('head_from',$addonParam)){
				$sendmsg .= "From: {$addonParam['head_from']} \r\n";
			}else{
				$sendmsg .= "From: {$this->config['mail_from']} \r\n";
			}
			if(array_key_exists('head_to',$addonParam)){
				$sendmsg .= "To: {$addonParam['head_to']}\r\n";
			}else{
				//$sendmsg .= "To: {$to}\r\n";
			}
		}else{
			$sendmsg .= "From: {$this->config['mail_from']} \r\n";
			//$sendmsg .= "To: {$to}\r\n";
		}
		$sendmsg .= "Subject: =?{$this->setup['mail_charset']}?B?".base64_encode($subject)."?=\r\n";
		$sendmsg .= "X-mailer: Php SocketMail Sender by nickfan \r\n";
		//$sendmsg .= "Mime-Version: 1.0\r\n";

		!empty($addonHeader) && $sendmsg .= $addonHeader;

		if($this->setup['mail_encode']=='base64')
		{
			$sendmsg .= "Content-Transfer-Encoding: base64\r\n\r\n";
			$sendmsg .= chunk_split(base64_encode($message))."\r\n.\r\n";
		}
		else
		{
			$sendmsg .= $message."\r\n.\r\n";
		}

		$this->sendRequest($sendmsg);
		return true;
	}

	function close() {
		if(!$this->fp){
			return ;
		}else{
			$this->sendRequest("QUIT\r\n");
			@fclose($this->fp);
		}
	}

	function __destruct() {
		$this->close();
	}

}

?>