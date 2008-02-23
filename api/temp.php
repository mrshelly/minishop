<?php
	/* 简单API数据处理模板 mrshelly@hotmail.com */

	/* 不允许直接调用 */
		if (! defined("IN_SYS")) exit($retOut(array('ret'=>'err', 'msg'=>"Access Denied")));

	/* 初始化数据库实例 */
		$db = new ezSQL_mysql($siteCfg['dbset']['user'], $siteCfg['dbset']['pass'], $siteCfg['dbset']['db'], $siteCfg['dbset']['host']);

	/* 组织SQL语句 */
		$sql = "SELECT id FROM table1 LIMIT 1;";
		$result=$db->query($sql) or die($retOut(array('ret'=>'err','msg'=>$db->last_error)));

	/* 输出 */
		$outArray=array(
			'ret'=>'ok',
			'msg'=>'成功',
		);

		echo $retOut($outArray);
		exit;
?>