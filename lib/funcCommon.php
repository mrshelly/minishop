<?php
/**
 * 简介: 常用函数
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-08-25 樊振兴 创建了本文件（类）
 */

/* ******************** 特定函数区 开始 ******************** */

/**
 * 函数说明: 获得指定数组键所对应的值
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-08-25 樊振兴 添加了本方法
 * @param		array array 指定数组
 * @param		string index 指定键值
 * @return		mixed
 */
function getArrIdxVal($array, $index){
				return $array[$index];
} // End of function getArrIdxVal

/**
 * 函数说明: 返回 g_infoArray全局数组的指定位置内容
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-08-25 樊振兴 添加了本方法
 * @param		string module 所属模块
 * @param		string code 所属操作
 * @param		string key 所属结果
 * @return		string
 */
function getInfMsg($module, $code, $key){
				global $gInfoArray;
				if(!array_key_exists($module,$gInfoArray)){
					return 'unknown_module:'.$module;
				}elseif(!array_key_exists($code,$gInfoArray[$module])){
					return 'unknown_code:'.$code;
				}elseif(!array_key_exists($key,$gInfoArray[$module][$code])){
					$pos = strpos($key, ' ');
					if($pos===false){
						return 'unknown_key:'.$key;
					}else{
						$key=substr($key,0,$pos);
						$module = 'ICE';
						$code = 'system';
						if(!array_key_exists($key,$gInfoArray[$module][$code])){
							return 'unknown_key:'.$key;
						}else{
							return $gInfoArray[$module][$code][$key];
						}
					}
				}else{
					return $gInfoArray[$module][$code][$key];
				}
} // End of function getInfMsg

/**
 * 函数说明: 返回 $map_arr所指定名称的全局数组的指定位置内容
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-08-25 樊振兴 添加了本方法
 * @param		string field_val 字段值
 * @param		string field_key 字段键
 * @param		string map_arr 映射的全局数组的名称
 * @return		string
 */
function getMapDisp($field_val, $field_key, $map_arr){
				global $$map_arr;
				$the_map = $$map_arr;
				if(in_array($field_key, array_keys($the_map))){
								if(count($the_map[$field_key]) > 2){
												if(in_array($field_val, array_keys($the_map[$field_key][2]))){
																return $the_map[$field_key][2][$field_val];
												}
								}
				}else{
								return '';
				}
} // End of function getMapDisp


/**
 * 函数说明: 返回 TimeStamp的显示数据
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-08-25 樊振兴 添加了本方法
 * @param		string field_val 字段值
 * @param		string format_str 显示格式字符串
 * @return		string
 */
function getTSDisp($field_val, $format_str){
	return $field_val!='0'?date($format_str,$field_val):0;
} // End of function getMapDisp

/**
 * 函数说明: 自定义异常处理函数
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-08-25 樊振兴 添加了本方法
 * @param		object exception 异常对象
 * @return		void
 */
function myExceptionHandler($exception){
				global $gSet;
				$errorinfo = array('msg' => $exception->getMessage(),
								'style' => 'text',
								'type' => 'back',
								'time' => 5,
								'frame' => 'top',
								'textarray' => $gSet['respInfo'],
								'state' => 0
								);
				respInfo($errorinfo);
}

/**
 * 函数说明: 自定义错误处理函数
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-08-25 樊振兴 添加了本方法
 * @param		object exception 异常对象
 * @return		void
 */
function myErrorHandler($number, $string, $file, $line, $context){
				$error = "=========\nPHP ERROR\n=========\n";
				$error .= "Time : [" . date("Y-m-d H:i:s") . "]\n";
				$error .= "Number: [$number]\n";
				$error .= "String: [$string]\n";
				$error .= "File:   [$file]\n";
				$error .= "Line:   [$line]\n";
				$error .= "Context:\n" . print_r($context, true) . "\n\n";
				global $gCfg;
				global $gSet;
				error_log($error, 3, $gCfg['error_log_file']);
				$errorinfo = array('msg' => nl2br($error),
								'style' => 'text',
								'type' => 'back',
								'time' => 5,
								'frame' => 'top',
								'textarray' => $gSet['respInfo'],
								'state' => 0
								);
				respInfo($errorinfo);
}

/**
 * 函数说明: 返回Assoc数组指定Key对应值为Val的行。
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-09-18 樊振兴 添加了本方法
 * @param		array(assoc) assoc 输入数组
 * @param		key string 指定Key
 * @param		val mixed 指定Val
 * @return		array(assoc)
 */
function AssocColVal($assoc,$key,$val) {
	$ret_arr = array();
	foreach($assoc as $rowkey=>$row){
		if(array_key_exists($key, $row) && $row[$key]==$val){
			$ret_arr[$rowkey]=$row;
			break;
		}
	}
	return $ret_arr;
}

/**
 * 函数说明: 返回Assoc数组指定Key对应值为Val的总数。
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-09-18 樊振兴 添加了本方法
 * @param		array(assoc) assoc 输入数组
 * @param		key string 指定Key
 * @param		val mixed 指定Val
 * @return		int
 */
function AssocColValCount($assoc,$key,$val){
	$ret_cnt=0;
	foreach($assoc as $rowkey=>$row){
		if(array_key_exists($key, $row) && $row[$key]==$val){
			$ret_cnt++;
		}
	}
	return $ret_cnt;
}

/**
 * 函数说明: 返回Assoc数组指定Key对应值为Val的行Key数组。
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-09-18 樊振兴 添加了本方法
 * @param		array(assoc) assoc 输入数组
 * @param		key string 指定Key
 * @param		val mixed 指定Val
 * @return		array
 */
function AssocColValRowKeys($assoc,$key,$val) {
	$ret_arr = array();
	foreach($assoc as $rowkey=>$row){
		if(array_key_exists($key, $row) && $row[$key]==$val){
			$ret_arr[]=$rowkey;
		}
	}
	return $ret_arr;
}

/**
 * 函数说明: 返回Assoc数组指定Key对应值为Val的指定ret_key的数组。
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-09-18 樊振兴 添加了本方法
 * @param		array(assoc) assoc 输入数组
 * @param		key string 指定Key
 * @param		val mixed 指定Val
 * @param		key string 指定ret_key
 * @return		array
 */
function AssocColValColKey($assoc, $key, $val, $ret_key){
	$ret_arr = array();
	foreach($assoc as $rowkey=>$row){
		if(array_key_exists($key, $row) && $row[$key]==$val && array_key_exists($ret_key, $row)){
			$ret_arr[$rowkey]=$row[$ret_key];
		}
	}
	return $ret_arr;
}

/**
 * 函数说明: 返回Assoc数组指定Key对应值为Val的指定ret_keys的数组。
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-09-18 樊振兴 添加了本方法
 * @param		array(assoc) assoc 输入数组
 * @param		key string 指定Key
 * @param		val mixed 指定Val
 * @param		ret_keys array 指定ret_key组
 * @return		array
 */
function AssocColValColKeys($assoc, $key, $val, $ret_keys){
	$ret_arr = array();
	foreach($assoc as $rowkey=>$row){
		if(array_key_exists($key, $row) && $row[$key]==$val){
			foreach($ret_keys as $ret_key){
				if(array_key_exists($ret_key, $row)){
					$ret_arr[$rowkey][$ret_key]=$row[$ret_key];
				}
			}
		}
	}
	return $ret_arr;
}

/**
 * 函数说明: 返回Assoc数组指定Key的列。
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-09-18 樊振兴 添加了本方法
 * @param		array(assoc) assoc 输入数组
 * @param		key string 指定Key
 * @return		array
 */
function AssocColKey($assoc, $key){
	$ret_arr = array();
	foreach($assoc as $rowkey=>$row){
		if(array_key_exists($key, $row)){
			$ret_arr[$rowkey]=$row[$key];
		}
	}
	return $ret_arr;
}

/**
 * 函数说明: 删除Assoc数组指定Key对应值为Val的行。
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-09-18 樊振兴 添加了本方法
 * @param		array(assoc) assoc 输入数组
 * @param		key string 指定Key
 * @param		val mixed 指定Val
 * @param		key string 指定ret_key
 * @return		array
 */
function AssocColValUnsetRow($assoc, $key, $val){
	$ret_arr = array();
	foreach($assoc as $rowkey=>$row){
		if(array_key_exists($key, $row) && $row[$key]!=$val){
			$ret_arr[$rowkey]=$row;
		}
	}
	return $ret_arr;
}

/**
 * 函数说明: 删除Assoc数组指定Key对应值为Vals数组中任意值的行。
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-09-19 樊振兴 添加了本方法
 * @param		array(assoc) assoc 输入数组
 * @param		key string 指定Key
 * @param		vals array 指定Vals数组
 * @param		key string 指定ret_key
 * @return		array
 */
function AssocColValUnsetRows($assoc, $key, $vals){
	$ret_arr = array();
	foreach($assoc as $rowkey=>$row){
		if(array_key_exists($key, $row) && !in_array($row[$key], $vals)){
			$ret_arr[$rowkey]=$row;
		}
	}
	return $ret_arr;
}


/**
 * 函数说明: 删除Assoc数组重复的行。
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-09-19 樊振兴 添加了本方法
 * @param		array(assoc) assoc 输入数组
 * @return		array(assoc) assoc 返回数组
 */
function AssocUnique($assoc) {
	$ser_arr = array();
	$uns_arr = array();
	$ser_arr = @array_map('serialize', $assoc);
	$ser_arr = array_unique($ser_arr);
	$uns_arr = array_map('unserialize', $ser_arr);
	foreach ($uns_arr as $key => $row){
	if (!is_array($row)) { unset($uns_arr[$key]); }
	}
	return $uns_arr;
}

/**
 * 函数说明: 从Assoc数组中取出指定的键重建数组。
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-09-19 樊振兴 添加了本方法
 * @param		array(assoc) assoc 输入数组
 * @param		array keys 需要提取的键名组成的数组
 * @return		array(assoc) assoc 返回数组
 */
function AssocRowKeys($assoc, $keys){
	$ret_arr = array();
	foreach($assoc as $rowkey=>$row){
		foreach($keys as $key)
		{
			if(array_key_exists($key, $row)){
				$ret_arr[$rowkey][$key]=$row[$key];
			}
		}
	}
	return $ret_arr;
}


/**
 * 函数说明: 返回Assoc数组指定Key所有值统计结果的数组。
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-09-18 樊振兴 添加了本方法
 * @param		array(assoc) assoc 输入数组
 * @param		key string 指定Key
 * @return		array
 */
function AssocColKeyValCount($assoc,$key) {
	$ret_arr = array();
	foreach($assoc as $rowkey=>$row){
		if(array_key_exists($key, $row)){
			if(array_key_exists($row[$key],$ret_arr)){
				$ret_arr[$row[$key]]++;
			}else{
				$ret_arr[$row[$key]]=1;
			}
		}
	}
	return $ret_arr;
}


/**
 * 函数说明: 返回Array数组所有值统计结果的数组。
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-09-18 樊振兴 添加了本方法
 * @param		array(assoc) assoc 输入数组
 * @param		key string 指定Key
 * @return		array
 */
function ArrayValCount($array) {
	$ret_arr = array();
	foreach($assoc as $val){
		if(array_key_exists($val, $ret_arr)){
			$ret_arr[$val]++;
		}else{
			$ret_arr[$val]=1;
		}
	}
	return $ret_arr;
}

/* ******************** 特定函数区 结束 ******************** */



/* ******************** 通用函数区 开始 ******************** */

/**
 * 函数说明: 转义一个字符串用于 mysql_query 
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @param		string str 需要转义的字符串
 * @return		string
 */
function escape($str){
	return mysql_escape_string($str);
}
function escape_recursive ( $arr ){
	if (is_array($arr)){
		return array_map('escape_recursive', $arr);
	}
	return mysql_escape_string($arr);
}

function nl2br_recursive ( $arr ){
	if (is_array($arr)){
		return array_map('nl2br_recursive', $arr);
	}
	return str_replace(array("\r\n", "\n", "\r"), "<br/>", $arr);
}
/**
 * filter the quotes
 * 
 * @name quote
 * @author nickfan<nickfan81@gmail.com> 
 * @last nickfan<nickfan81@gmail.com>
 * @update 2006/01/06 13:48:03
 * @version 0.1
 * @param string /array
 * @return string /array
 */
function quote($value){
				$value = is_array($value) ?
				array_map('quote', $value) :
				addslashes($value);

				return $value;
}

/**
 * 函数说明: 发送Content-Type的HTTP Header头
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-08-25 樊振兴 添加了本方法
 * @param		string charset 编码类型默认utf-8
 * @param		string doctype 内容的格式默认html
 * @return		void
 */
function setHeaderType($charset = 'utf-8', $doctype = 'html'){
	switch($doctype){
		case 'htm':
		case 'html':
				@header('Content-Type: text/html; charset=' . $charset);
			break;
		case 'xml':
				@header('Content-Type: application/xml; charset=' . $charset);
			break;
		case 'js':
		case 'script':
		case 'javascript':
				@header('Content-Type: text/javascript; charset=' . $charset);
				//@header('Content-Type: application/x-javascript; charset=' . $charset);
			break;
		case 'css':
		case 'style':
				@header('Content-Type: text/css; charset=' . $charset);
			break;
		case 'txt':
		case 'text':
				@header('Content-Type: text/plain; charset=' . $charset);
			break;
		default:
			@header('Content-Type: ' . $doctype . '; charset=' . $charset);
	}
}

/**
 * 函数说明: 判断是否已发送指定名称域的文件
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-08-25 樊振兴 添加了本方法
 * @param		string item 文件域的名称(id/name)
 * @return		bool
 */
function issetFile($item){
				if(isset($_FILES[$item]) && !empty($_FILES[$item]['name'])){
								if(!is_array($_FILES[$item]['name'])){
												return true;
								}else{
												$isset = false;
												for($i = 0;$i < count($_FILES[$item]['name']);$i++){
																if(!empty($_FILES[$item]['name'][$i])){
																				$isset = true;
																}
												}
												return $isset;
								}
				}else{
								return false;
				}
}

/**
 * 函数说明: 返回非空的参数数据，如果数据为空则返回参数中设定的默认值
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-08-25 樊振兴 添加了本方法
 * @param		mixed item 数据值
 * @param		mixed default 替代的默认值
 * @return		bool
 */
function reParam($item = 0, $default = null){
				return isset($item) && ($item !== '' || !empty($item)) ? $item : $default;
}

/**
 * 函数说明: 返回非空的REQUEST参数数据，如果数据为空则返回参数中设定的默认值
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-08-25 樊振兴 添加了本方法
 * @param		mixed item 数据值
 * @param		mixed default 替代的默认值
 * @return		bool
 */
function reRequest($item = 0, $default = null){
				return isset($_REQUEST[$item]) && ($_REQUEST[$item] !== '' || !empty($_REQUEST[$item])) ? $_REQUEST[$item] : $default;
}

/**
 * 函数说明: 返回非空的POST参数数据，如果数据为空则返回参数中设定的默认值
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-08-25 樊振兴 添加了本方法
 * @param		mixed item 数据值
 * @param		mixed default 替代的默认值
 * @return		bool
 */
function rePost($item = 0, $default = null){
				return isset($_POST[$item]) && ($_POST[$item] !== '' || !empty($_POST[$item])) ? $_POST[$item] : $default;
}

/**
 * 函数说明: 返回非空的GET参数数据，如果数据为空则返回参数中设定的默认值
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-08-25 樊振兴 添加了本方法
 * @param		mixed item 数据值
 * @param		mixed default 替代的默认值
 * @return		bool
 */
function reGet($item = 0, $default = null){
				return isset($_GET[$item]) && ($_GET[$item] !== '' || !empty($_GET[$item])) ? $_GET[$item] : $default;
}

/**
 * 函数说明: 返回非空的COOKIE参数数据，如果数据为空则返回参数中设定的默认值
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-08-25 樊振兴 添加了本方法
 * @param		mixed item 数据值
 * @param		mixed default 替代的默认值
 * @return		bool
 */
function reCookie($item = 0, $default = null){
				return isset($_COOKIE[$item]) && ($_COOKIE[$item] !== '' || !empty($_COOKIE[$item])) ? $_COOKIE[$item] : $default;
}

/**
 * 函数说明: 返回非空的SERVER参数数据，如果数据为空则返回参数中设定的默认值
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-08-25 樊振兴 添加了本方法
 * @param		mixed item 数据值
 * @param		mixed default 替代的默认值
 * @return		bool
 */
function reServer($item = 0, $default = null){
				return isset($_SERVER[$item]) && ($_SERVER[$item] !== '' || !empty($_SERVER[$item])) ? $_SERVER[$item] : $default;
}

/**
 * 函数说明: 返回非空的SESSION参数数据，如果数据为空则返回参数中设定的默认值
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-08-25 樊振兴 添加了本方法
 * @param		mixed item 数据值
 * @param		mixed default 替代的默认值
 * @return		bool
 */
function reSession($item = 0, $default = null){
				return isset($_SESSION[$item]) && ($_SESSION[$item] !== '' || !empty($_SESSION[$item])) ? $_SESSION[$item] : $default;
}

/**
 * 函数说明: 返回非空的ENV参数数据，如果数据为空则返回参数中设定的默认值
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-08-25 樊振兴 添加了本方法
 * @param		mixed item 数据值
 * @param		mixed default 替代的默认值
 * @return		bool
 */
function reEnv($item = 0, $default = null){
				return isset($_ENV[$item]) && ($_ENV[$item] !== '' || !empty($_ENV[$item])) ? $_ENV[$item] : $default;
}

/**
 * 函数说明: 返回一个指定长度的随机字符串
 * 
 * @author 樊振兴(nick)<nickfan81@gmail.com> 
 * @history 2006-08-25 樊振兴 添加了本方法
 * @param int len 字符串长度
 * @return string 
 */
function reRandStr($len = 3){
				$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
				mt_srand((double)microtime() * 1000000 * getmypid());
				$outstr = "";
				while(strlen($outstr) < $len)
				$outstr .= substr($chars, (mt_rand() % strlen($chars)), 1);
				return $outstr;
} // end of function reRandStr

/**
 * 函数说明: 返回一个GUID
 * 
 * @author 樊振兴(nick)<nickfan81@gmail.com> 
 * @history 2007-07-04 樊振兴 添加了本方法
 * @return string 
 */
function reGUID(){
	list($usec, $sec) = explode(" ",microtime());
	$curtm=$sec.substr($usec,2,3);
	$svname= isset($_ENV['COMPUTERNAME'])?$_ENV['COMPUTERNAME']:'localhost';
	$svip = isset($_SERVER['SERVER_ADDR'])?$_SERVER['SERVER_ADDR']:'127.0.0.1';
	$tmp= rand(0,1)?'-':'';
	$randstr =$tmp.rand(1000,9999).rand(1000,9999).rand(1000,9999).rand(100,999).rand(100,999);
	$cstr = $svname.'/'.$svip.':'.$curtm.':'.$randstr;
	$md5cstr = strtolower(md5($cstr));
	return substr($md5cstr, 0, 8).'-'.substr($md5cstr, 8, 4).'-'.substr($md5cstr, 12, 4).'-'.substr($md5cstr, 16, 4).'-'.substr($md5cstr, 20);
} // end of function reGUID

/**
 * 函数说明: 代码访问控制
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-08-25 樊振兴 添加了本方法
 * 				2006-09-06 樊振兴 修改添加charset功能
 * @param		mixed acceptRoleLabels 允许的角色标记（包含允许角色标记的数组或'*'表示所有）
 * @param		mixed deniedRoleLabels 禁止的角色标记（包含禁止角色标记的数组或null表示无）
 * @param		string currRoleLabel 当前用户的角色标记（通常使用isset($_SESSION['user_label'])?$_SESSION['user_label']:'guest'或isset($_COOKIE['uuid'])?$_COOKIE['uuid']:'guest' 作为传入参数）
 * @param		string deniedInfo 被拒绝访问时返回的信息内容默认Access Denied
 * @param		string redirectUrl 被拒绝访问时转向的地址,'back'表示返回前一页；'close'表示关闭
 * @param		string frameset 被拒绝访问时转向地址所在框架,'self'表示当前框架，'page'表示整页
 * @param		string charset 系统字符编码默认utf-8
 * @return		bool
 */
function isAccess($acceptRoleLabels = '*' , $deniedRoleLabels = null, $currRoleLabel = 'guest', $deniedInfo = "Access Denied", $redirectUrl = 'back', $frameset = 'self', $charset = 'utf-8'){
				if(!empty($deniedRoleLabels)){
								if(in_array($currRoleLabel, $deniedRoleLabels)){
												if(!headers_sent()){ header('Content-Type: text/html; charset='.$charset); }else{ echo '<meta http-equiv="Content-Type" content="text/html; charset='.$charset.'" />'; }
												if(!empty($deniedInfo)){
													echo "<script type=\"text/javascript\">window.alert('" . $deniedInfo . "');</script>";
												}
												if(!empty($redirectUrl)){
																if($redirectUrl == 'back'){
																				echo "<script type=\"text/javascript\"> if (document.referer){ location.href=escape(document.referer);}else{history.back();}</script>";
																}elseif($redirectUrl == 'close'){
																				echo "<script type=\"text/javascript\"> window.close();</script>";
																}else{
																				if($frameset == 'page'){
																								echo "<script type=\"text/javascript\">if (top.location !== self.location){top.location=self.location;} location.href = '" . $redirectUrl . "';</script>";
																				}else{
																								echo "<script type=\"text/javascript\">top.window['" . $frameset . "'].location.href='" . $redirectUrl . "';</script>";
																				}
																}
																exit;
																return false;
												}
												exit;
												return false;
								}
				}else{
								if($acceptRoleLabels != '*'){
												if(!in_array($currRoleLabel, $acceptRoleLabels)){
																if(!headers_sent()){ header('Content-Type: text/html; charset='.$charset); }else{ echo '<meta http-equiv="Content-Type" content="text/html; charset='.$charset.'" />'; }
																if(!empty($deniedInfo)){
																	echo "<script type=\"text/javascript\">window.alert('" . $deniedInfo . "');</script>";
																}
																if(!empty($redirectUrl)){
																				if($redirectUrl == 'back'){
																								echo "<script type=\"text/javascript\"> if (document.referer){ location.href=escape(document.referer);}else{history.back();}</script>";
																				}elseif($redirectUrl == 'close'){
																								echo "<script type=\"text/javascript\"> window.close();</script>";
																				}else{
																								if($frameset == 'page'){
																												echo "<script type=\"text/javascript\">if (top.location !== self.location){top.location=self.location;} location.href = '" . $redirectUrl . "';</script>";
																								}else{
																												echo "<script type=\"text/javascript\">top.window['" . $frameset . "'].location.href='" . $redirectUrl . "';</script>";
																								}
																				}
																				exit;
																				return false;
																}
																exit;
																return false;
												}
								}
				}
} // End of function isAccess

/**
 * 函数说明: 把数组数据转化为实体代码
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-08-25 樊振兴 添加了本方法
 * @param		mixed var 数组数据
 * @return		string
 */
function encArr($var){
				if (is_array($var)){
								$code = 'array(';
								foreach ($var as $key => $value){
												$code .= "'$key'=>" . encArr($value) . ',';
								}
								$code = chop($code, ','); //remove unnecessary coma
								$code .= ')';
								return $code;
				}else{
								if (is_string($var)){
												return "'" . $var . "'";
								}elseif (is_bool($var)){
												return ($var ? 'TRUE' : 'FALSE');
								}elseif (is_numeric($var)){
												return $var;
								}elseif (is_null($var)){
												return 'NULL';
								}elseif (is_object($var)){
												return "''";
								}else{
												return "''";
								}
				}
}

/**
 * 函数说明: 把数组数据转化为实体代码存储的文件
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-08-25 樊振兴 添加了本方法
 * @param		array array 数组数据
 * @param		string name 新数组的变量名称
 * @param		string path 新数组文件的路径 (文件实际路径为path/name.php文件)
 * @return		bool(true)
 */
function genArrFile($array, $name, $path = ''){
				$filename = $path . $name . '.php';
				if(PHP_VERSION >= 5.0){
								if(file_exists($filename)){
												if(is_writable($filename)){
																file_put_contents($filename, "<?php\n" . '$' . $name . ' = ' . preg_replace("/\,\n( *)\)/", "\n\\1)", var_export($array, true)) . ";\n// echo '<pre>'.print_r($" . $name . ",true).'</pre>';\n?>");
																return true;
												}else{
																trigger_error('write array file failed.', E_USER_ERROR);
												}
								}else{
												file_put_contents($filename, "<?php\n" . '$' . $name . ' = ' . preg_replace("/\,\n( *)\)/", "\n\\1)", var_export($array, true)) . ";\n// echo '<pre>'.print_r($" . $name . ",true).'</pre>';\n?>");
												return true;
								}
				}elseif(PHP_VERSION >= 4.2){
								if(file_exists($filename)){
												if(is_writable($filename)){
																if (!$filehandle = fopen($filename, 'wb')){
																				trigger_error('open array file failed.', E_USER_ERROR);
																}

																if (fwrite($filehandle, "<?php\n" . '$' . $name . ' = ' . preg_replace("/\,\n( *)\)/", "\n\\1)", var_export($array, true)) . ";\n// echo '<pre>'.print_r($" . $name . ",true).'</pre>';\n?>") === false){
																				trigger_error('write array file failed.', E_USER_ERROR);
																}
																fclose($filehandle);
																return true;
												}else{
																trigger_error('array file write failed.', E_USER_ERROR);
												}
								}else{
												if (!$filehandle = fopen($filename, 'wb')){
																trigger_error('open array file failed.', E_USER_ERROR);
												}

												if (fwrite($filehandle, "<?php\n" . '$' . $name . ' = ' . preg_replace("/\,\n( *)\)/", "\n\\1)", var_export($array, true)) . ";\n// echo '<pre>'.print_r($" . $name . ",true).'</pre>';\n?>") === false){
																trigger_error('write array file failed.', E_USER_ERROR);
												}
												fclose($filehandle);
												return true;
								}
				}else{
								if(file_exists($filename)){
												if(is_writable($filename)){
																if (!$filehandle = fopen($filename, 'wb')){
																				trigger_error('open array file failed.', E_USER_ERROR);
																}

																if (fwrite($filehandle, "<?php\n" . '$' . $name . ' = ' . encArr($array) . ";\n// echo '<pre>'.print_r($" . $name . ",true).'</pre>';\n?>") === false){
																				trigger_error('write array file failed.', E_USER_ERROR);
																}
																fclose($filehandle);
																return true;
												}else{
																trigger_error('array file write failed.', E_USER_ERROR);
												}
								}else{
												if (!$filehandle = fopen($filename, 'wb')){
																trigger_error('open array file failed.', E_USER_ERROR);
												}

												if (fwrite($filehandle, "<?php\n" . '$' . $name . ' = ' . encArr($array) . ";\n// echo '<pre>'.print_r($" . $name . ",true).'</pre>';\n?>") === false){
																trigger_error('write array file failed.', E_USER_ERROR);
												}
												fclose($filehandle);
												return true;
								}
				}
} // End of function genArrFile


/**
 * 函数说明: 经过javascript的escape函数转编码的字符串还原
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-08-25 樊振兴 添加了本方法
 * @param		string str 被javascript escape过的字符串
 * @param		string charset unescape后目标字符的编码格式默认utf-8
 * @return		string 被解码的字符串
 */
function unEscape($str, $charset = 'UTF-8'){
				$charset = strtoupper($charset);
				$str = rawurldecode($str);
				preg_match_all("/(?:%u.{4})|.+/", $str, $r);
				$ar = $r[0];
				foreach($ar as $k => $v){
								if(substr($v, 0, 2) == "%u" && strlen($v) == 6)
												$ar[$k] = iconv("UCS-2", $charset, pack("H4", substr($v, -4)));
				}
				return join("", $ar);
}

/**
 * 函数说明: 转换数组中的数据整合为utf8编码格式的字符串
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-08-25 樊振兴 添加了本方法
 * @param		array ar 数组数据
 * @return		string utf8编码格式的字符串
 */
function toUTF8($ar){
				$c = '';
				foreach($ar as $val){
								$val = intval(substr($val, 2), 16);
								if($val < 0x7F){ // 0000-007F
												$c .= chr($val);
								}elseif($val < 0x800){ // 0080-0800
												$c .= chr(0xC0 | ($val / 64));
												$c .= chr(0x80 | ($val % 64));
								}else{ // 0800-FFFF
												$c .= chr(0xE0 | (($val / 64) / 64));
												$c .= chr(0x80 | (($val / 64) % 64));
												$c .= chr(0x80 | ($val % 64));
								}
				}
				return $c;
}

/**
 * 函数说明: 对unicode方式encode的字符串解码
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-08-25 樊振兴 添加了本方法
 * @param		string str 已编码的字符串
 * @param		string charcode 字符编码默认utf-8
 * @return		string 解码后的字符串
 */
function uniDecode($str, $charcode = 'utf-8'){
				if(is_string($str)){
								$text = preg_replace_callback("/%u[0-9A-Za-z]{4}/", "toUTF8", $str);
								$thisres = mb_convert_encoding($text, $charcode, 'utf-8');
								return str_replace("%0D%0A", "\\n", $thisres);
				}elseif(is_array($str)){
								$resarr = array();
								foreach($str as $strkey => $strval){
												$thistext = preg_replace_callback("/%u[0-9A-Za-z]{4}/", "toUTF8", $strval);
												$thisres = mb_convert_encoding($thistext, $charcode, 'utf-8');
												$resarr[$strkey] = $thisres;
								}
								return $resarr;
				}
}


/**
 * 函数说明: 以utf8编码格式对已编码的 URL 字符串进行解码
 * @author		樊振兴(nick)<nickfan81@gmail.com>
 * @history
 * 				2006-08-25 樊振兴 添加了本方法
 * @param		string source 已编码的字符串
 * @return		string 解码后的字符串
 */
function utf8_raw_urldecode($source){
				if(is_array($source) || is_object($source)){
								return array_map(__FUNCTION__, $source);
				}else{
								$decodedStr = "";
								$pos = 0;
								$len = strlen ($source);
								while ($pos < $len){
												$charAt = substr ($source, $pos, 1);
												if ($charAt == '%'){
																$pos++;
																$charAt = substr ($source, $pos, 1);
																if ($charAt == 'u'){ 
																				// we got a unicode character
																				$pos++;
																				$unicodeHexVal = substr ($source, $pos, 4);
																				$unicode = hexdec ($unicodeHexVal);
																				$entity = "&#" . $unicode . ';';
																				$decodedStr .= utf8_encode ($entity);
																				$pos += 4;
																}else{ 
																				// we have an escaped ascii character
																				$hexVal = substr ($source, $pos, 2);
																				$decodedStr .= chr (hexdec ($hexVal));
																				$pos += 2;
																}
												}else{
																$decodedStr .= $charAt;
																$pos++;
												}
								}
								return $decodedStr;
				}
}

/* ******************** 通用函数区 结束 ******************** */


/* 获取用户间关系
 * initudata 主动联系人数据
 * passuuid 被动联系人uuid
*/
function getUsersRel($initudata,$passuuid){
	if($passuuid=='0' || $initudata['uuid']=='0'){
		return 8;/* 游客 */
	}else if($passuuid>100000 && $passuuid<100010){
		return 0;/* 管理员 */
	}else if($initudata['uuid']==$passuuid){
		return 1;/* 本人 */
	}else{
		if(isset($initudata['white']) && in_array($passuuid,$initudata['white'])){
			return 2;/* 白名单 */
		}
		if(isset($initudata['family']) && in_array($passuuid,$initudata['family'])){
			return 3;/* 家人 */
		}
		if(isset($initudata['friend']) && in_array($passuuid,$initudata['friend'])){
			return 4;/* 好友 */
		}
		if(isset($initudata['contact']) && in_array($passuuid,$initudata['contact'])){
			return 6;/* 朋友 */
		}
		if(isset($initudata['black']) && in_array($passuuid,$initudata['black'])){
			return 9;/* 黑名单 */
		}
		return 7;/* 登录用户 */
	}
}

/**
 * 函数说明: 构建目录树
 * 
 * @author 樊振兴(nick)<nickfan81@gmail.com> 
 * @history 2006-08-25 樊振兴 添加了本方法
 * @param string baseDir 基路径需带尾部/
 * @param string path 需要添加的子路径串（如: subdir/subsubdir/subsubsubdir/）
 * @param string chmod 默认chmod为0777
 * @return void 
 */
function dirBuild($baseDir, $path, $chmod = null){
				$ch = !is_null($chmod)?$chmod:0777;
				$parts = explode("/", $path);
				$subDir = "";

				foreach($parts as $part){
								if(!is_dir($baseDir . $subDir . $part))
												mkdir($baseDir . $subDir . $part, $ch);
												@chmod($baseDir . $subDir . $part, $ch);
								$subDir .= $part . "/";
				}
}


/**
 * 函数说明: 附加内容到文件末尾
 * 
 * @author 樊振兴(nick)<nickfan81@gmail.com> 
 * @history 2006-09-13 樊振兴 添加了本方法
 * @param string file 要写入的文件
 * @param string content 要写入的内容
 * @return bool 
 */
function appendFileContent($file, $content) {
		if(file_exists($file)){
						if(is_writable($file)){
										if (!$filehandle = fopen($file, 'ab')){
														trigger_error('open file failed.', E_USER_ERROR);
										}

										if (fwrite($filehandle, $content) === false){
														trigger_error('write file failed.', E_USER_ERROR);
										}
										fclose($filehandle);
										return true;
						}else{
										trigger_error('file write failed.', E_USER_ERROR);
						}
		}else{
						if (!$filehandle = fopen($file, 'ab')){
										trigger_error('open file failed.', E_USER_ERROR);
						}

						if (fwrite($filehandle,  $content) === false){
										trigger_error('write file failed.', E_USER_ERROR);
						}
						fclose($filehandle);
						return true;
		}
}


/**
 * 函数说明: 获取客户端IP
 * 
 * @author 樊振兴(nick)<nickfan81@gmail.com> 
 * @history 2006-08-25 樊振兴 添加了本方法
 * @return string 
 */
function getClientIP(){
	global $_SERVER;
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')){
					$onlineip = getenv('HTTP_CLIENT_IP');
	}elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')){
					$onlineip = getenv('HTTP_X_FORWARDED_FOR');
	}elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')){
					$onlineip = getenv('REMOTE_ADDR');
	}elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')){
					$onlineip = $_SERVER['REMOTE_ADDR'];
	}
	return preg_replace("/^([\d\.]+).*/", "\\1", $onlineip);
}

/**
 * 函数说明: 记录用户动作
 * 输入数组为：
 array(
 'ts'=>‘动作时间’,	[可选]
 'ip'=>'客户端ip',	[可选]
 'stp'=>'执行元类型默认u(用户)',	[可选]
 'sid'=>'执行元ID默认执行用户uuid',	[可选]
 'act'=>'执行动作',					参见http://svn.seekpai.com:8080/wiki/pages/viewpage.action?pageId=334
 'ttp'=>'目标对象类型',		范围:		'u'(用户),						'p'(图片),						's'(相册)
 'tid'=>'目标对象标识',		例子:		'100001',						'100001_1102a554478'	'100083_11107ea6f1a'
 'srcurl'=>'执行来源url',	[可选]
 'refer'=>'执行引用url',	[可选]
 'extra'=>'附属信息数组',	[可选]	空或自定义数组
 'tkname'=>缓存名默认log2007063123这样的格式（后面的日期为ts指定的日期加小时）
 'timeout'=>缓存过期时间 默认90000(25小时)
 )
 * 例子1：
logAction(array(
'act'=>'vw',
'ttp'=>'p',
'tid'=>'100001_1102a554478',
));
 * 例子2：
logAction(array(
'ts'=>time(),
'ip'=>getClientIP(),
'stp'=>'u',
'sid'=>$uuid,
'act'=>'vw',
'ttp'=>'p',
'tid'=>$picid,
'srcurl'=>$reqURI,
'refer'=>$srvRefer,
'extra'=>'',
'tkname'=>'log'.date("YmdH",time()),
'timeout'=>90000,
));
 * @author 樊振兴(nick)<nickfan81@gmail.com> 
 * @history 2007-07-02 樊振兴 添加了本方法
 * @return bool 
 */
function logAction($paramarray) {
	if(count(array_diff(array('act','ttp','tid'), array_keys($paramarray))) > 0){
		return false;
	}
	global $is_BOT;
	if(!isset($is_BOT)){
		 $is_BOT=NULL;
		global $_SERVER;
		$ua= isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:null;
		if(!empty($ua)){
			$botpattern_arr=array(
			'/Baiduspider/',
			'/Yahoo\!\ Slurp/',
			'/YodaoBot/',
			'/Sogou\ web\ spider/',
			'/Yahoo\!\ Slurp China/',
			'/Googlebot/',
			'/^BlackWidow/',
			'/^Bot\ mailto:craftbot@yahoo.com/',
			'/^ChinaClaw/',
			'/^Custo/',
			'/^DISCo/',
			'/^Download\ Demon/',
			'/^eCatch/',
			'/^EirGrabber/',
			'/^EmailSiphon/',
			'/^EmailWolf/',
			'/^Express\ WebPictures/',
			'/^ExtractorPro/',
			'/^EyeNetIE/',
			'/^FlashGet/',
			'/^GetRight/',
			'/^GetWeb!/',
			'/^Go!Zilla/',
			'/^Go-Ahead-Got-It/',
			'/^GrabNet/',
			'/^Grafula/',
			'/^HMView/',
			'/HTTrack/i',
			'/^Image\ Stripper/',
			'/^Image\ Sucker/',
			'/Indy\ Library/i',
			'/^InterGET/',
			'/^Internet\ Ninja/',
			'/^JetCar/',
			'/^JOC\ Web\ Spider/',
			'/^larbin/',
			'/^LeechFTP/',
			'/^Mass\ Downloader/',
			'/^MIDown\ tool/',
			'/^Mister\ PiX/',
			'/^Navroad/',
			'/^NearSite/',
			'/^NetAnts/',
			'/^NetSpider/',
			'/^Net\ Vampire/',
			'/^NetZIP/',
			'/^Octopus/',
			'/^Offline\ Explorer/',
			'/^Offline\ Navigator/',
			'/^PageGrabber/',
			'/^Papa\ Foto/',
			'/^pavuk/',
			'/^pcBrowser/',
			'/^RealDownload/',
			'/^ReGet/',
			'/^SiteSnagger/',
			'/^SmartDownload/',
			'/^SuperBot/',
			'/^SuperHTTP/',
			'/^Surfbot/',
			'/^tAkeOut/',
			'/^Teleport\ Pro/',
			'/^VoidEYE/',
			'/^Web\ Image\ Collector/',
			'/^Web\ Sucker/',
			'/^WebAuto/',
			'/^WebCopier/',
			'/^WebFetch/',
			'/^WebGo\ IS/',
			'/^WebLeacher/',
			'/^WebReaper/',
			'/^WebSauger/',
			'/^Website\ eXtractor/',
			'/^Website\ Quester/',
			'/^WebStripper/',
			'/^WebWhacker/',
			'/^WebZIP/',
			'/^Wget/',
			'/^Widow/',
			'/^WWWOFFLE/',
			'/^Xaldon\ WebSpider/',
			'/^Zeus/',
			);
			foreach($botpattern_arr as $botpattern){
				if (preg_match($botpattern, $ua)) {
					$is_BOT = 1;
					break;
				}
			}
		}
	}
	if($is_BOT==1){
		return false;
	}

	$retArr =array();
	// timestamp
	if(isset($paramarray['ts'])){
		$retArr['ts']=intval($paramarray['ts']);
	}else{
		$retArr['ts']=time();
	}

	// ip地址
	if(isset($paramarray['ip'])){
		$retArr['ip']=$paramarray['ip'];
	}else{
		global $_SERVER;
		$onlineip='';
		if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')){
						$onlineip = getenv('HTTP_CLIENT_IP');
		}elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')){
						$onlineip = getenv('HTTP_X_FORWARDED_FOR');
		}elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')){
						$onlineip = getenv('REMOTE_ADDR');
		}elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')){
						$onlineip = $_SERVER['REMOTE_ADDR'];
		}
		$retArr['ip']=preg_replace("/^([\d\.]+).*/", "\\1", $onlineip);
	}

	// stp
	if(isset($paramarray['stp'])){
		$retArr['stp']=$paramarray['stp'];
	}else{
		$retArr['stp'] = 'u';
	}

	// sid
	if(isset($paramarray['sid'])){
		$retArr['sid']=$paramarray['sid'];
	}else{
		global $uuid;
		$retArr['sid']=isset($uuid)?$uuid:0;
	}

	$retArr['act']=strtolower(strval($paramarray['act']));
	$retArr['ttp']=strtolower(strval($paramarray['ttp']));
	$retArr['tid']=strtolower(strval($paramarray['tid']));

	// srcurl
	if(isset($paramarray['srcurl'])){
		$retArr['srcurl']=$paramarray['srcurl'];
	}else{
		global $reqURI;
		if(isset($reqURI)){
			$retArr['srcurl'] = $reqURI;
		}else{
			global $_SERVER;
			$retArr['srcurl'] = php_sapi_name() == 'cli'? '': ( (isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ? 'https://' . $_SERVER['HTTP_HOST'] . ($_SERVER['SERVER_PORT'] == '443' ? '' : ':' . $_SERVER['SERVER_PORT']) : 'http://' . $_SERVER['HTTP_HOST'] . ($_SERVER['SERVER_PORT'] == '80' ? '' : ':' . $_SERVER['SERVER_PORT'])) . (isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:($_SERVER['SCRIPT_NAME'] . isset($_SERVER['QUERY_STRING'])?'?' . $_SERVER['QUERY_STRING']:'')) );
		}
	}

	// refer
	if(isset($paramarray['refer'])){
		$retArr['refer']=$paramarray['refer'];
	}else{
		global $srvRefer;
		if(isset($srvRefer)){
			$retArr['refer'] = $srvRefer;
		}else{
			global $_SERVER;
			$retArr['refer'] = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:null;
		}
	}

	// extra
	if(isset($paramarray['extra']) && !empty($paramarray['extra'])){
		$retArr['extra']=is_array($paramarray['extra'])?serialize($paramarray['extra']):strval($paramarray['extra']);
	}else{
		$retArr['extra']='';
	}
	$sepstr=isset($paramarray['sep'])?strval($paramarray['sep']):"[^]";
	$logline=$retArr['ts'].$sepstr.$retArr['ip'].$sepstr.$retArr['stp'].$sepstr.$retArr['sid'].$sepstr.$retArr['act'].$sepstr.$retArr['ttp'].$sepstr.$retArr['tid'].$sepstr.$retArr['srcurl'].$sepstr.$retArr['refer'].$sepstr.$retArr['extra'];
	global $SKPInst,$instservercfg,$MEMinst_log;
	// 引入InstSrv实例服务类
	!defined('INSTSRV') && @include(CLASS_FILEPATH . 'InstSrv.php');
	// 初始化Inst服务实例
	!isset($SKPInst) && $SKPInst = InstSrv::getInstance($instservercfg);
	$MEMinst_log = $SKPInst->getMEMInst('log');

	$logArr=array();
	$tkname=isset($paramarray['tkname'])?strval($paramarray['tkname']):'log'.date("YmdH",$retArr['ts']);
	$ttimeout=isset($paramarray['timeout'])?intval($paramarray['timeout']):90000;
	$cacheobjArr=$MEMinst_log->get(array($tkname));
	if(!empty($cacheobjArr)){ // cache没有过期
		$logArr=$cacheobjArr[$tkname];
		
	}
	$logArr[]=$logline;
	$MEMinst_log->set($tkname,$logArr,false,$ttimeout); // memcache缓存
	@$MEMinst_log->close();
	//@file_put_contents(DATA_FILEPATH.'mylog.log','tkname:'.$tkname."\n".'logline:'.$logline."\n\n",FILE_APPEND);//-->debug
	return true;
}

/**
 * 函数说明：登录用户活动时，记录log，每天记录一个log，后台程序去分析
 *			windows环境不记录
 * 			记录在 /data/log/active_日期.log下
 * 
 * @author 罗杰
 * @param	array $content: 需要记录的内容是一个数组格式{'u'=>uuid, 'a'=>actionid}
 * 			actionid	int，可能的值如下
 */

function logActive($content) {
	if (PHP_OS != 'WINNT') {
		return file_put_contents('/data/log/active_'.date('Ymd').'.log', 
								 $content['u']."\t". $content['a']. "\t". date('H:i:s'). "\t". getClientIP(). "\n",
								 FILE_APPEND);
	}
	else {
		return true;
	}
}

/**
 * 函数说明：任何用户查看 photos，photo，sets。对目标进行计数log，后台程序去分析，写数据库
 *			windows环境不记录
 * 			记录在 /data/log/count_日期小时十分.log
 * 
 * @author 罗杰
 * @param	array $content: 需要记录的内容是一个数组格式{'t'=>counttype, 'i'=>id}
 * 			counttype	string，可能值如下
 * 						u	访问user的photos页
 * 						p 	访问图片单页
 * 						s	访问相册页
 */

function logCount($content) {
	//暂时不记录
	/*
	if (PHP_OS != 'WINNT') {
		return file_put_contents('/data/log/count_'.substr(date('YmdHi'), 0, -1).'.log', 
								 $content['t']."\t". $content['i']. "\n",
								 FILE_APPEND);
	}
	else {
		return true;
	}
	*/
}
	
?>