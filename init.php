<?php
	/* 创建输出函数 $retOut
	 * 根据GET 变量 $_GET['o'] 定义 
	 * o=jssz json 输出
	 * o=debug 调试输出
	 * 默认 直接输出
	 */
	 switch($outType){
		 case	'jssz':
			$retOut=create_function('$object', 'return "var retSZ=".json_encode($object).";";');
			header('Content-Type: text/javascript; charset=utf-8');
			break;
		 case	'debug':
			$retOut=create_function('$object', 'return print_r($object,true);');
			header('Content-Type: text/html; charset=utf-8');
			break;
		 default:
			$retOut=create_function('$object', 'return $object["msg"];');
			header('Content-Type: text/html; charset=utf-8');
			break;
	 }

	include_once $rootPath."/lib/shared/ez_sql_core.php";
	include_once $rootPath."/lib/mysql/ez_sql_mysql.php";

?>