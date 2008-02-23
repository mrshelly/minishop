<?php
/**
 *版权所有 2006 南京搜拍信息技术有限公司
 *Copyright 2006 Nanjing Seekpai Information Technologies Ltd
 *搜拍公司秘密  Seekpai Confidential Proprietary
 *
 * 简介:			代码样例
 * @author		刘阳
 * @history		2007-11-15 刘阳 创建了本文件（类）
 */

/**
 * 函数说明
 * @author		刘阳
 * @history		2007-11-15 作者 创建了本方法
 * @param		string $localclsid 本地类别编号
 * @return		string
 */

 function getLocalCateName($localclsid) {
	 global $db;
	 $reqSql="select clsid,clsname from localcate where clsid='".$localclsid."'";
	 $resRow=$db->get_row($reqSql,ARRAY_A);
	 if(!empty($resRow)) {
		 return $resRow['clsname'];
	 }
	 return '';
 }
?>