<?php

	/* 动作处理不允许直接调用 */
		if (! defined("IN_SYS")) exit($retOut(array('ret'=>'err', 'msg'=>"Access Denied")));


	/* 输出 */
		/*
		$outArray=array(
			'ret'=>'ok',
			'msg'=>'成功',
		);

		echo $retOut($outArray);
		*/

	// 分页设置

		$req_pp=100;
		$req_p = isset($_GET['p'])?intval($_GET['p']):1;
		$start=($req_p-1)*$req_pp;

	/* 初始化数据库实例 */
		$db = new ezSQL_mysql($siteCfg['dbset']['user'], $siteCfg['dbset']['pass'], $siteCfg['dbset']['db'], $siteCfg['dbset']['host']);

	/* 组织SQL语句 */

		require_once $rootPath."/template/default.php";

	// 关闭数据库连接
		//$db->close();

		exit;
?>