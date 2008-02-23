<?php

	//用户统一入口
	if (! defined("IN_SYS")) define('IN_SYS', true);

	/* 获基本 系统GET变量 */
		$outType=isset($_GET['o'])?trim($_GET['o']):'';
		$mod=isset($_GET['mod'])?trim($_GET['mod']):'default';

	/* 初始化 */
		chdir("./");
		$rootPath=".";
		require_once $rootPath."/cfg/config.php";
		require_once $rootPath."/init.php";

	/* 调用 */
		switch($mod){
			case	"api"		:
				$apiMod=isset($_GET['api'])?trim($_GET['api']):'';
				if (!in_array($apiMod, $siteCfg['mod']['api'])){
					exit($retOut(array('ret'=>'err', 'msg'=>'api part error!')));
				}
				if(!file_exists($rootPath."/api/".$apiMod.".php")){
					exit($retOut(array('ret'=>'err', 'msg'=>'api part error!')));
				}
				require $rootPath."/api/".$apiMod.".php";
				break;
			case	"act"		:
				$actMod=isset($_GET['act'])?trim($_GET['act']):'';
				if (!in_array($actMod, $siteCfg['mod']['act'])){
					exit($retOut(array('ret'=>'err', 'msg'=>'act part error!')));
				}
				if(!file_exists($rootPath."/act/".$actMod.".php")){
					exit($retOut(array('ret'=>'err', 'msg'=>'act part error!')));
				}
				require $rootPath."/api/".$actMod.".php";
				break;
			case	"disp"		:
				$dispMod=isset($_GET['disp'])?trim($_GET['disp']):'';
				if (!in_array($dispMod, $siteCfg['people']['mod']['disp'])){
					exit($retOut(array('ret'=>'err', 'msg'=>'disp part error!')));
				}
				if(!file_exists($rootPath."/template/people/".$dispMod.".php")){
					exit($retOut(array('ret'=>'err', 'msg'=>'disp part error!')));
				}
				require $rootPath."/template/people/".$dispMod.".php";
				break;

			default	:
				require $rootPath."/default.php";
		}

?>