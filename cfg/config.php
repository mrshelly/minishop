<?php
	if (! defined("IN_SYS")) exit("Access Denied");

	$siteCfg = array(
		'dbset'=>array(
			'host'=>'localhost',
			'user'=>'root',
			'pass'=>'',
			'db'=>'minishop',
		),
		'index'=>array(
			'mod'=>array(
				'api'=>array(
					'temp',												//测试用API模板
				),
				'act'=>array(
					'temp',												//测试用ACT模板
				),
			),
		),
		'people'=>array(
			'mod'=>array(
				'disp'=>array(
					'info',					//用户个人页面
				),
			),
		),
	);

?>