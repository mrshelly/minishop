<?php
/**
 * 简介: 页面处理相关函数
 * 
 * @author 樊振兴(nick)<nickfan81@gmail.com> 
 * @history 2000-01-01 樊振兴 创建了本文件（类）
 */

/**
 * 函数说明: utf8编码格式字符串截取
 * 
 * @author 樊振兴(nick)<nickfan81@gmail.com> 
 * @history 2006-08-25 樊振兴 添加了本方法
 * @param string str 字符串
 * @param int start 起始点
 * @param int lenth 截取长度
 * @return string 
 */
function subStrUTF8($str, $start, $lenth){
				$len = strlen($str);
				$r = array();
				$n = 0;
				$m = 0;
				for($i = 0; $i < $len; $i++){
								$x = substr($str, $i, 1);
								$a = base_convert(ord($x), 10, 2);
								$a = substr('00000000' . $a, -8);
								if ($n < $start){
												if (substr($a, 0, 1) == 0){
												}elseif (substr($a, 0, 3) == 110){
																$i += 1;
												}elseif (substr($a, 0, 4) == 1110){
																$i += 2;
												}
												$n++;
								}else{
												if (substr($a, 0, 1) == 0){
																$r[] = substr($str, $i, 1);
												}elseif (substr($a, 0, 3) == 110){
																$r[] = substr($str, $i, 2);
																$i += 1;
												}elseif (substr($a, 0, 4) == 1110){
																$r[] = substr($str, $i, 3);
																$i += 2;
												}else{
																$r[] = '';
												}
												if (++$m >= $lenth){
																break;
												}
								}
				}
				return implode('', $r);
}

/**
 * 函数说明: GB2312编码格式字符串截取
 * 
 * @author 樊振兴(nick)<nickfan81@gmail.com> 
 * @history 2006-08-25 樊振兴 添加了本方法
 * @param string str 字符串
 * @param int start 起始点
 * @param int len 截取长度
 * @return string 
 */
function subStrGB($str, $start, $len = null){
				$totlelength = strlen($str);

				if ($len == null) $len = $totlelength;
				if ($len == 0) return "";
				if ($len >= $totlelength && $start == 0) return $str;
				if ($start > $totlelength) return "";

				if ($start < 0){
								if (abs($start) >= $totlelength) $start = 0;
								else $start = $totlelength - abs($start);
				}

				if ($start > 0){
								$i = $start-1;
								$flag = -1;
								while ($i >= 0){
												if (ord(substr($str, $i, 1)) > 160){
																$flag = -1 * $flag;
												}else break;
												$i--;
								}
								if($flag == 1){
												$start = $start - 1;
												$len++;
								}
				}

				$str = substr($str, $start);
				$totlelength = strlen($str);

				if ($len < 0) $len = $totlelength - abs($len);
				if ($len <= 0) return "";
				$i = min($len, $totlelength);
				$i--;
				$flag = -1;
				while ($i >= 0){
								if (ord(substr($str, $i, 1)) > 160){
												$flag = -1 * $flag;
								}else break;
								$i--;
				}
				if($flag == 1)$len = $len-1;
				$subit = substr($str, 0, $len);
				return $subit;
}

/**
 * 函数说明: 按字节位数字符串截取(utf-8为3位，GB2312为2位)
 * 
 * @author 樊振兴(nick)<nickfan81@gmail.com> 
 * @history 2006-08-25 樊振兴 添加了本方法
 * @param string string 字符串
 * @param int start 起始点
 * @param int len 截取长度
 * @param int byte 字节位数(utf-8为3位，GB2312为2位)
 * @return string 
 */
function subStrBite($string, $start, $len, $byte = 3){
				$str = "";
				$count = 1;
				$str_len = strlen($string);
				for ($i = 0; $i < $str_len; $i++){
								if (($count + 1 - $start) > $len){ 
												// $str  .= "...";
												break;
								}elseif ((ord(substr($string, $i, 1)) <= 128) && ($count < $start)){
												$count++;
								}elseif ((ord(substr($string, $i, 1)) > 128) && ($count < $start)){
												$count = $count + 2;
												$i = $i + $byte-1;
								}elseif ((ord(substr($string, $i, 1)) <= 128) && ($count >= $start)){
												$str .= substr($string, $i, 1);
												$count++;
								}elseif ((ord(substr($string, $i, 1)) > 128) && ($count >= $start)){
												$str .= substr($string, $i, $byte);
												$count = $count + 2;
												$i = $i + $byte-1;
								}
				}
				return $str;
}

/**
 * 函数说明: 截取字符串
 * 
 * @author 樊振兴(nick)<nickfan81@gmail.com> 
 * @history 2006-08-25 樊振兴 添加了本方法
 * @param string title 字符串
 * @param int length 截取长度
 * @param string etc 截取后附加字符
 * @param string enc 字符编码
 * @return string 
 */
function subString($str = '', $start = 0, $length = 0, $enc = 'utf-8', $etc = '...'){
				if ($length == 0)
								return '';
				$enc = strtolower($enc);
				$enc_length = $enc == 'utf-8'?3:2;

				if(extension_loaded('mbstring')){
								$newstr = mb_substr($str, $start, $length, $enc);
								$strlen = mb_strlen($str, $enc);
								$newstrlen = mb_strlen($newstr, $enc);
				}elseif($enc == 'utf-8'){
								$strlen = strlen($str);

								$r = array();
								$n = 0;
								$m = 0;
								for($i = 0; $i < $strlen; $i++){
												$x = substr($str, $i, 1);
												$a = base_convert(ord($x), 10, 2);
												$a = substr('00000000' . $a, -8);
												if ($n < $start){
																if (substr($a, 0, 1) == 0){
																}elseif (substr($a, 0, 3) == 110){
																				$i += 1;
																}elseif (substr($a, 0, 4) == 1110){
																				$i += 2;
																}
																$n++;
												}else{
																if (substr($a, 0, 1) == 0){
																				$r[] = substr($str, $i, 1);
																}elseif (substr($a, 0, 3) == 110){
																				$r[] = substr($str, $i, 2);
																				$i += 1;
																}elseif (substr($a, 0, 4) == 1110){
																				$r[] = substr($str, $i, 3);
																				$i += 2;
																}else{
																				$r[] = '';
																}
																if (++$m >= $length){
																				break;
																}
												}
								}
								$newstr = implode('', $r);
								$newstrlen = strlen($newstr);
				}else{
								$string = "";
								$count = 1;
								$strlen = strlen($str);
								for ($i = 0; $i < $strlen; $i++){
												if (($count + 1 - $start) > $length){ 
																// $str  .= "...";
																break;
												}elseif ((ord(substr($str, $i, 1)) <= 128) && ($count < $start)){
																$count++;
												}elseif ((ord(substr($str, $i, 1)) > 128) && ($count < $start)){
																$count = $count + 2;
																$i = $i + $enc_length-1;
												}elseif ((ord(substr($str, $i, 1)) <= 128) && ($count >= $start)){
																$string .= substr($str, $i, 1);
																$count++;
												}elseif ((ord(substr($str, $i, 1)) > 128) && ($count >= $start)){
																$string .= substr($str, $i, $enc_length);
																$count = $count + 2;
																$i = $i + $enc_length-1;
												}
								}
								$newstr = $string;
								$newstrlen = strlen($newstr);
				}
				return ($strlen > $newstrlen) ? $newstr . $etc : $str;
}

/**
 * 函数说明: 截取文件Mime类型
 * 
 * @author 樊振兴(nick)<nickfan81@gmail.com> 
 * @history 2006-08-25 樊振兴 添加了本方法
 * @param string field 文件域名称
 * @param int index 如果是多文件则获取指定索引的文件的Mime类型
 * @return string /bool(false)
 */
function getFileType($field , $index = 0){
				if(isset($_FILES[$field]) && !empty($_FILES[$field]['type'])){
								if(!is_array($_FILES[$field]['type'])){
												switch($_FILES[$field]['type']){
																case 'image/gif':
																				return 'gif';
																case 'image/jpeg':
																				return 'jpg';
																case 'image/png':
																				return 'png';
																case 'image/bmp':
																				return 'bmp';
																case 'image/x-portable-pixmap':
																				return 'ppm';
																case 'image/x-png':
																				return 'png';
																case 'image/pjpeg':
																				return 'jpg';
																case 'image/tiff':
																				return 'tif';
																case 'image/x-icon':
																				return 'ico';
																case 'image/svg+xml':
																				return 'svg';
																case 'image/vnd.wap.wbmp':
																				return 'wbmp';
																case 'application/x-shockwave-flash':
																				return 'swf';
																case 'text/plain':
																				return 'txt';
																case 'text/css':
																				return 'css';
																case 'text/html':
																				return 'html';
																case 'application/xml':
																				return 'xml';
																case 'application/zip':
																				return 'zip';
																case 'application/rar':
																				return 'rar';
																case 'text/vnd.wap.wml':
																				return 'wml';
																case 'application/xhtml+xml':
																				return 'html';
																case 'application/xslt+xml':
																				return 'xslt';
																case 'application/xml-dtd':
																				return 'dtd';
																case 'application/rdf+xml':
																				return 'rdf';
																case 'application/msword':
																				return 'doc';
																case 'application/x-gzip':
																				return 'gz';
																case 'application/x-tar':
																				return 'tar';
																case 'audio/midi':
																				return 'mid';
																case 'audio/mpeg':
																				return 'mp3';
																case 'audio/x-wav':
																				return 'wav';
																case 'application/ogg':
																				return 'ogg';
																case 'video/mpeg':
																				return 'mpg';
																case 'video/quicktime':
																				return 'mov';
																case '':
																				return false;
																default:
																				return false;
												}
								}else{
												switch($_FILES[$field]['type'][$index]){
																case 'image/gif':
																				return 'gif';
																case 'image/jpeg':
																				return 'jpg';
																case 'image/png':
																				return 'png';
																case 'image/bmp':
																				return 'bmp';
																case 'image/x-portable-pixmap':
																				return 'ppm';
																case 'image/x-png':
																				return 'png';
																case 'image/pjpeg':
																				return 'jpg';
																case 'image/tiff':
																				return 'tif';
																case 'image/x-icon':
																				return 'ico';
																case 'image/svg+xml':
																				return 'svg';
																case 'image/vnd.wap.wbmp':
																				return 'wbmp';
																case 'application/x-shockwave-flash':
																				return 'swf';
																case 'text/plain':
																				return 'txt';
																case 'text/css':
																				return 'css';
																case 'text/html':
																				return 'html';
																case 'application/xml':
																				return 'xml';
																case 'application/zip':
																				return 'zip';
																case 'application/rar':
																				return 'rar';
																case 'text/vnd.wap.wml':
																				return 'wml';
																case 'application/xhtml+xml':
																				return 'html';
																case 'application/xslt+xml':
																				return 'xslt';
																case 'application/xml-dtd':
																				return 'dtd';
																case 'application/rdf+xml':
																				return 'rdf';
																case 'application/msword':
																				return 'doc';
																case 'application/x-gzip':
																				return 'gz';
																case 'application/x-tar':
																				return 'tar';
																case 'audio/midi':
																				return 'mid';
																case 'audio/mpeg':
																				return 'mp3';
																case 'audio/x-wav':
																				return 'wav';
																case 'application/ogg':
																				return 'ogg';
																case 'video/mpeg':
																				return 'mpg';
																case 'video/quicktime':
																				return 'mov';
																case '':
																				return false;
																default:
																				return false;
												}
								}
				}else{
								return false;
				}
}

function getImageType($srcFile) {
	$data = @GetImageSize($srcFile);

	if($data===false){
		return false;
	}else{
		switch($data[2]){
				case 1:
						return 'gif';
						break;
				case 2:
						return 'jpg';
						break;
				case 3:
						return 'png';
						break;
				case 4:
						return 'swf';
						break;
				case 5:
						return 'psd';
						break;
				case 6:
						return 'bmp';
						break;
				case 7:
						return 'tiff';
						break;
				case 8:
						return 'tiff';
						break;
				case 9:
						return 'jpc';
						break;
				case 10:
						return 'jp2';
						break;
				case 11:
						return 'jpx';
						break;
				case 12:
						return 'jb2';
						break;
				case 13:
						return 'swc';
						break;
				case 14:
						return 'iff';
						break;
				case 15:
						return 'wbmp';
						break;
				case 16:
						return 'xbm';
						break;
				default:
								return false;

		}
	}
}
/**
 * 函数说明: 截取文件后缀名
 * 
 * @author 樊振兴(nick)<nickfan81@gmail.com> 
 * @history 2006-08-25 樊振兴 添加了本方法
 * @param string field 文件域名称
 * @param int index 如果是多文件则获取指定索引的文件的后缀名
 * @return string /bool(false)
 */
function getPostfix($field, $index = 0){
				if(isset($_FILES[$field]) && !empty($_FILES[$field]['name'])){
								if(!is_array($_FILES[$field]['name'])){
												$file_name = $_FILES[$field]['name'];
												$point_pos = strrpos($file_name, '.');
												if($point_pos !== false){
																return substr($file_name, $point_pos + 1);
												}else{
																return '';
												}
								}else{
												$file_name = $_FILES[$field]['name'][$index];
												$point_pos = strrpos($file_name, '.');
												if($point_pos !== false){
																return substr($file_name, $point_pos + 1);
												}else{
																return '';
												}
								}
				}else{
								return false;
				}
}

/**
 * 函数说明: 获取上传文件大小
 * 
 * @author 樊振兴(nick)<nickfan81@gmail.com> 
 * @history 2006-08-25 樊振兴 添加了本方法
 * @param string field 文件域名称
 * @return int 
 */
function getFileSizeTotal($field){
				if(isset($_FILES[$field]) && !empty($_FILES[$field]['name'])){
								if(!is_array($_FILES[$field]['name'])){
												return sprintf("%u", filesize($_FILES[$field]['tmp_name']));
								}else{
												$total = 0;
												for($i = 0;$i < count($_FILES[$field]['name']);$i++){
																$total += filesize($_FILES[$field]['tmp_name'][$i]);
												}
												return $total;
								}
				}else{
								return false;
				}
}

/**
 * 函数说明: 获取上传文件个数
 * 
 * @author 樊振兴(nick)<nickfan81@gmail.com> 
 * @history 2006-08-25 樊振兴 添加了本方法
 * @param string field 文件域名称
 * @return int 
 */
function getFileCount($field){
				if(isset($_FILES[$field]) && !empty($_FILES[$field]['name'])){
								if(!is_array($_FILES[$field]['name'])){
												return 1;
								}else{
												$total = 0;
												for($i = 0;$i < count($_FILES[$field]['name']);$i++){
																if(!empty($_FILES[$field]['name'][$i])){
																				$total++;
																}
												}
												return $total;
								}
				}else{
								return false;
				}
}

/**
 * 函数说明: 文件字节显示字符串
 * 
 * @author 樊振兴(nick)<nickfan81@gmail.com> 
 * @history 2006-08-25 樊振兴 添加了本方法
 * @param int filesize 文件字节数
 * @return string 
 */
function getSizeDisp($filesize){
				if($filesize >= 1073741824){
								//$filesize = round($filesize / 1073741824 * 100) / 100 . " GB";
								return sprintf("%.2fGB", $filesize/1073741824);
				}elseif($filesize >= 1048576){
								//$filesize = round($filesize / 1048576 * 100) / 100 . " MB";
								return sprintf("%.2fMB", $filesize/1048576);
				}elseif($filesize >= 1024){
								//$filesize = round($filesize / 1024 * 100) / 100 . " KB";
								return sprintf("%.2fKB", $filesize/1024);
				}else{
								return $filesize . " Bytes";
				}
}

/**
 * 函数说明: 获取分页数组
 * 
 * @author 樊振兴(nick)<nickfan81@gmail.com> 
 * @history 2006-08-25 樊振兴 添加了本方法
 * @param int perpage 每页显示数量
 * @return array 
 */
function getPageArgs($perpage){
				if(!isset($_GET['page']) || $_GET['page'] <= 1){
								$page = 1;
								$startline = 0;
				}else{
								$page = intval($_GET['page']);
								$startline = ($_GET['page']-1) * $perpage;
				}
				$pageNum['pagenum'] = $page;
				$pageNum['startline'] = $startline;
				return $pageNum;
}

/**
 * 函数说明: 获取分页显示代码
 * 
 * @author 樊振兴(nick)<nickfan81@gmail.com> 
 * @history 2006-08-25 樊振兴 添加了本方法
 * @param int num 条数
 * @param int perpage 每页显示数量
 * @param int curr_page 当前页数
 * @param string mpurl 分页地址
 * @return string 
 */
function getMultiPage($num, $prePage, $currPage, $mpurl){
		$multipage='';
				if($num > $prePage){
								$page = 7;
								$offset = 2;
								$pages = ceil($num / $prePage);

								$from = $currPage - $offset; 
								$to = $currPage + $page - $offset - 1;

								$prePage = (($currPage-1)<=0)?1:($currPage-1);
								$nextPage = (($currPage+1)>=$pages)?$pages:($currPage+1);

								if($page > $pages){
												$from = 1;
												$to = $pages;
								}else{
												if($from < 1){
																$to = $currPage + 1 - $from;
																$from = 1;
																if(($to - $from) < $page && ($to - $from) < $pages){
																				$to = $page;
																}
												}else{
													if($to > $pages){
																$from = $currPage - $pages + $to;
																$to = $pages;
																if(($to - $from) < $page && ($to - $from) < $pages){
																				$from = $pages - $page + 1;
																}
													}
												}
								}

								$fwd_back = '';

								$fwd_back = '';
								$fwd_back .= "<ul>";
								$fwd_back .= "<li><a href=\"".str_replace('{page}',1,$mpurl)."\">第一页</a></li>";
								$fwd_back .= "<li><a href=\"".(((int)$currPage>1)?str_replace('{page}',$prePage,$mpurl):"#")."\">上一页</a></li>";
								for($i = $from; $i < $to; $i++){
									if($i != $currPage){
										$fwd_back .= "<li><a href=\"".(str_replace('{page}',$i,$mpurl))."\">".$i."</a></li>";
									}else{
										$fwd_back .= "<li><span id=\"multiCurrPage\">".$i."</span></li>";
									}
								}
								if($pages > $page){
									if($pages != $currPage){
										$fwd_back .= "<li>...</li>";
										$fwd_back .= "<li><a href=\"".(str_replace('{page}',$pages,$mpurl))."\">".$pages."</a></li>";
									}else{
										$fwd_back .= "<li><span id=\"multiCurrPage\">".$pages."</span></li>";
									}

									$fwd_back .= "<li><a href=\"".(((int)$currPage < (int)$pages)?(str_replace('{page}',$nextPage,$mpurl)):("#"))."\">下一页</a></li>";
									$fwd_back .= "<li><a href=\"".(str_replace('{page}',$pages,$mpurl))."\">最后页</a></li>";
								}else {
									if($pages != $currPage){
										$fwd_back .= "<li><a href=\"".(str_replace('{page}',$pages,$mpurl))."\">".$pages."</a></li>";
									}else{
										$fwd_back .= "<li><span id=\"multiCurrPage\">".$pages."</span></li>";
									}

									$fwd_back .= "<li><a href=\"".(((int)$currPage < (int)$pages)?(str_replace('{page}',$nextPage,$mpurl)):("#"))."\">下一页</a></li>";
									$fwd_back .= "<li><a href=\"".(str_replace('{page}',$pages,$mpurl))."\">最后页</a></li>";
								}
								$fwd_back .= "</ul>";
								$multipage = $fwd_back;
				}
			return $multipage;
}

/**
 * 函数说明: 获取分页显示代码
 * 
 * @author 樊振兴(nick)<nickfan81@gmail.com> 
 * @history 2006-08-25 樊振兴 添加了本方法
 * @param int num 页数
 * @param int perpage 每页显示数量
 * @param int curr_page 当前页数
 * @param string mpurl 分页地址
 * @return string 
 */
function getMultiPageEx($num, $perpage, $curr_page, $mpurl,$rpmnt='&page=',$rtrm='& '){
				if($num > $perpage){
								$page = 10;
								$offset = 2;
								$pages = ceil($num / $perpage); //get pages
								$curr_page<1 && $curr_page=1;
								$curr_page>$pages && $curr_page=$pages;
								$from = $curr_page - $offset; //minus offset
								$to = $curr_page + $page - $offset - 1;
								if($page > $pages){
												$from = 1;
												$to = $pages;
								}else{
												if($from < 1){
																$to = $curr_page + 1 - $from;
																$from = 1;
																if(($to - $from) < $page && ($to - $from) < $pages){
																				$to = $page;
																}
												}elseif($to > $pages){
																$from = $curr_page - $pages + $to;
																$to = $pages;
																if(($to - $from) < $page && ($to - $from) < $pages){
																				$from = $pages - $page + 1;
																}
												}
								}
								$mpstr = isset($rtrm)?rtrim($mpurl,$rtrm):$mpurl;
								$fwd_back = '';
								$fwd_back .= '<ul class="multipage">';
								if($curr_page>1){
									$fwd_back .= '<li class="multipage_first"><a href="'.$mpstr.$rpmnt.'1">|&lt;</a></li>';
									$fwd_back .= '<li class="multipage_prev"><a href="'.$mpstr.$rpmnt.($curr_page-1).'">&lt;</a></li>';
								}
								for($i = $from; $i <= $to; $i++){
									if($i != $curr_page){
										$fwd_back .= '<li class="multipage_num"><a href="'.$mpstr.$rpmnt.$i.'">'.$i.'</a></li>';
									}else{
										$fwd_back .= '<li class="multipage_cur"><span>'.$i.'</span></li>';
									}
								}
								if($pages > $page){
									if($curr_page!=$pages){
										$fwd_back .= '<li class="multipage_ellip">...</li>';
										$fwd_back .= '<li class="multipage_next"><a href="'.$mpstr.$rpmnt.($curr_page+1).'">&gt;</a></li>';
										$fwd_back .= '<li class="multipage_last"><a href="'.$mpstr.$rpmnt.$pages.'">'.$pages.'&gt;|</a></li>';
									}
								}else{
									if($curr_page!=$pages){
										$fwd_back .= '<li class="multipage_next"><a href="'.$mpstr.$rpmnt.($curr_page+1).'">&gt;</a></li>';
										$fwd_back .= '<li class="multipage_last"><a href="'.$mpstr.$rpmnt.$pages.'">&gt;|</a></li>';
									}
								}
								$fwd_back .= '<li class="multipage_stats"><span>'.$curr_page.'/'.$pages.' ('.$num.')</span></li>';
								$fwd_back .= '</ul>';
								$multipage = $fwd_back;
								return $multipage;
				}
}// end of function getMultiPageEx

/**
 * 函数说明: 发送一个XML的HTTP header头信息
 * 
 * @author 樊振兴(nick)<nickfan81@gmail.com> 
 * @history 2006-08-25 樊振兴 添加了本方法
 * @param string argument0 数据内容
 * @param string argument1 字符编码
 * @return void 
 */
function respXML(){
				$thisargs = func_get_args();
				$responsecontent = isset($thisargs[0]) && $thisargs[0] != ''?$thisargs[0]:null;
				$charset = isset($thisargs[1]) && $thisargs[1] != ''?$thisargs[1]:'utf-8';
				header("Content-Type: application/xml; charset=" . $charset);
				header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
				header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
				header("Cache-Control: no-store, no-cache, must-revalidate");
				header("Cache-Control: post-check=0, pre-check=0", false);
				header("Pragma: no-cache");
				if(isset($responsecontent)){
								print $responsecontent;
				}
}

/**
 * 函数说明: 发送一个HTML的HTTP header头信息
 * 
 * @author 樊振兴(nick)<nickfan81@gmail.com> 
 * @history 2006-08-25 樊振兴 添加了本方法
 * @param string argument0 数据内容
 * @param string argument1 字符编码
 * @return void 
 */
function respHTML(){
				$thisargs = func_get_args();
				$responsecontent = isset($thisargs[0]) && $thisargs[0] != ''?$thisargs[0]:null;
				$charset = isset($thisargs[1]) && $thisargs[1] != ''?$thisargs[1]:'utf-8';
				header("Content-Type: text/html; charset=" . $charset);
				if($responsecontent != null){
								print $responsecontent;
				}
}

/**
 * 函数说明: 设置一个XML文件头
 * 
 * @author 樊振兴(nick)<nickfan81@gmail.com> 
 * @history 2006-08-25 樊振兴 添加了本方法
 * @param string argument0 字符编码
 * @return string 
 */
function setXMLHeader(){
				$thisargs = func_get_args();
				$charset = isset($thisargs[0]) && $thisargs[0] != ''?$thisargs[0]:'utf-8';
				return "<?xml version=\"1.0\" encoding=\"" . $charset . "\" ?>\n\n";
}

/**
 * 函数说明: 设置一个XML文件根元素
 * 
 * @author 樊振兴(nick)<nickfan81@gmail.com> 
 * @history 2006-08-25 樊振兴 添加了本方法
 * @param string rootname 根元素名称
 * @param string content 内容
 * @return string 
 */
function setXMLRoot($rootname = 'root', $content){
				return "<" . $rootname . ">" . $content .= "</" . $rootname . ">";
}

/**
 * 函数说明: 设置一个XML文件数组树结构
 * 
 * @author 樊振兴(nick)<nickfan81@gmail.com> 
 * @history 2006-08-25 樊振兴 添加了本方法
 * @param array array_haystack 数组
 * @return string 
 */
function setXMLTree($array_haystack){
				if(is_array($array_haystack)){
								$content = "";
								foreach($array_haystack as $key => $value){
												$content .= "<" . $key . ">" . (is_array($value)?"" . setXMLTree($value):$value) . "</" . $key . ">";
								}
								return $content;
				}
}

/**
 * 函数说明: 设置一个XML文件结构
 * 
 * @author 樊振兴(nick)<nickfan81@gmail.com> 
 * @history 2006-08-25 樊振兴 添加了本方法
 * @return string 
 */
function setXMLStruct(){
				$thisargs = func_get_args();
				switch (func_num_args()){
								case 3:
												return setXMLHeader($thisargs[0]) . setXMLRoot($thisargs[1], setXMLTree($thisargs[2]));
												break;
								case 2:
												return setXMLRoot($thisargs[1], setXMLTree($thisargs[2]));
												break;
								case 1:
												return setXMLTree($thisargs[2]);
												break;
				}
}

/**
 * javascript alert
 * 
 * @name alert
 * @author nickfan<nickfan81@gmail.com> 
 * @last nickfan<nickfan81@gmail.com>
 * @update 2006/01/06 13:41:47
 * @version 0.1
 * @param string /array  $msg
 * @return javascript alrt message
 */
function respAlert($msg){
				if(is_string($msg) && ($msg != '' || !empty($msg))){
								$thismsg = $msg;
				}elseif(is_array($msg) && !empty($msg)){
								$thismsg = implode("\\n", $msg);
				}else{
								$thismsg = "";
				}
				echo "<script>window.alert('" . $thismsg . "');</script>";
}

/**
 * javascript alert
 * 
 * @name alert
 * @author nickfan<nickfan81@gmail.com> 
 * @last nickfan<nickfan81@gmail.com>
 * @update 2006/01/06 13:41:47
 * @version 0.1
 * @param string /array  $msg
 * @return javascript alrt message
 */
function respBack($msg = null){
	if(!empty($msg)){
		if(is_string($msg) && ($msg != '' || !empty($msg))){
			$thismsg = $msg;
		}elseif(is_array($msg) && !empty($msg)){
			$thismsg = implode("\\n", $msg);
		}else{
			$thismsg = "";
		}
		echo "<script type=\"text/javascript\">window.alert('" . $thismsg . "');history.back();</script>";
	}else{
		echo "<script type=\"text/javascript\">history.back();</script>";
	}
}

/**
 * javascript close window
 * 
 * @name close
 * @author nickfan<nickfan81@gmail.com> 
 * @last nickfan<nickfan81@gmail.com>
 * @update 2006/01/06 13:41:47
 * @version 0.1
 * @param none 
 * @return javascript close the window
 */
function respClose($msg = null){
	if(!empty($msg)){
		if(is_string($msg) && ($msg != '' || !empty($msg))){
			$thismsg = $msg;
		}elseif(is_array($msg) && !empty($msg)){
			$thismsg = implode("\\n", $msg);
		}else{
			$thismsg = "";
		}
		echo "<script type=\"text/javascript\">window.alert('" . $thismsg . "');window.close();</script>";
	}else{
		echo "<script type=\"text/javascript\">window.close();</script>";
	}
}

/**
 * page redirect
 * 
 * @name redirect
 * @author nickfan<nickfan81@gmail.com> 
 * @last nickfan<nickfan81@gmail.com>
 * @update 2006/01/06 13:41:47
 * @version 0.1
 * @method Multi Params:
 * @param string $url (default blank page)
 * @param string $method header/refresh/location/page (default=refresh)
 * @param string $frame blank/top/self/parent/[userdefine] (default=self)
 */
function respRedirect(){
				$thisargs = func_get_args();
				$thisurl = isset($thisargs[0])?$thisargs[0]:(isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:"about:blank");
				$thismethod = isset($thisargs[1])?$thisargs[1]:'refresh';
				$thistime = isset($thisargs[2])?intval($thisargs[2]):0;
				$thisframe = isset($thisargs[3]) && is_string($thisargs[3]) && ($thisargs[3] != '' || !empty($thisargs[3]))?$thisargs[3]:'self';
				$thistarget = in_array($thisframe, array('blank', 'top', 'self', 'parent'))?"_" . $thisframe:$thisframe;
				switch($thismethod){
								case 'header':
												header("Location:" . $thisurl);
												break;
								case 'refresh':
												echo "<meta http-equiv=\"Window-target\" content=\"" . $thistarget . "\"><meta http-equiv=\"Refresh\" content=\"" . $thistime . "; url=" . $thisurl . "\">";
												break;
								case 'location':
												echo "<script type=\"text/javascript\">top.window['" . $thisframe . "'].location.href='" . $thisurl . "';</script>";
												break;
								case 'page':
												echo "<script type=\"text/javascript\">if (top.location !== self.location){top.location=self.location;} location.href = '" . $thisurl . "';</script>";
												break;
								default:
												echo "<meta http-equiv=\"Window-target\" content=\"" . $thistarget . "\"><meta http-equiv=\"Refresh\" content=\"" . $thistime . "; url=" . $thisurl . "\">";
				}
}

/**
 * page reload
 * 
 * @name reloadOpener
 * @author nickfan<nickfan81@gmail.com> 
 * @last nickfan<nickfan81@gmail.com>
 * @update 2006/01/06 14:21:48
 * @version 0.1
 */
function respReloadOpener(){
				echo "<script>";
				echo "window.opener.location.reload();";
				echo "</script>";
}

/**
 * get Current File Path String
 * 
 * @name getCurrentFilePath
 * @author nickfan<nickfan81@msn.com> 
 * @last nickfan<nickfan81@msn.com>
 * @update 2006/01/09 08:11:14
 * @version 0.1
 * @return string Current File Path String
 */
function getCurFilePath(){
				return dirname(eregi_replace("//", "/", $_SERVER['SCRIPT_FILENAME'])) . "/";
}

/**
 * get Current Web Path String
 * 
 * @name getCurrentWebPath
 * @author nickfan<nickfan81@msn.com> 
 * @last nickfan<nickfan81@msn.com>
 * @update 2006/01/09 08:11:14
 * @version 0.1
 * @return string Current Web Path String
 */
function getCurWebPath(){
				if(isset($_SERVER)){
								$file_uri = isset($_SERVER['PHP_SELF']) ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
								$protocol = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on' ? 'https' : 'http';
								$host = $_SERVER['HTTP_HOST'];
								$port = $_SERVER['SERVER_PORT'];
								$server_path = dirname($_SERVER['PHP_SELF']) . '/'; 
								// $server_path = substr($file_uri, 0, strrpos($file_uri, '/') + 1);
				}elseif(isset($HTTP_SERVER_VARS)){
								$file_uri = isset($HTTP_SERVER_VARS['PHP_SELF']) ? $HTTP_SERVER_VARS['PHP_SELF'] : $HTTP_SERVER_VARS['SCRIPT_NAME'];
								$protocol = isset($HTTP_SERVER_VARS['HTTPS']) && $HTTP_SERVER_VARS['HTTPS'] == 'on' ? "https" : "http";
								$host = $HTTP_SERVER_VARS['HTTP_HOST'];
								$port = $HTTP_SERVER_VARS['SERVER_PORT'];
								$server_path = dirname($HTTP_SERVER_VARS['PHP_SELF']) . '/'; 
								// $server_path = substr($file_uri, 0, strrpos($file_uri, '/') + 1);
				}

				if($protocol == 'https'){
								return $protocol . '://' . $host . ($port == '443' ? '' : ':' . $port) . $server_path;
				}else{
								return $protocol . '://' . $host . ($port == '80' ? '' : ':' . $port) . $server_path;
				}
}

/**
 * calculate what's new path should be
 * 
 * @name changePath
 * @author nickfan<nickfan81@msn.com> 
 * @last nickfan<nickfan81@msn.com>
 * @update 2006/01/09 08:11:14
 * @version 0.1
 * @param string the path need to be parsed
 * @param string the relative path string
 * @return string the parsed path string
 * @usage changePath("http://www.example.com/temp/examplepath/exampath2","../../../images/") returns http://www.example.com/images
 */
function reRelPath($path, $relativePath){
				if(preg_match("/\*|\?|\"|\<|\>|\|/", $path) || preg_match("/\*|\?|\"|\<|\>|\|/", $relativePath)){
								return false;
				}

				$path = str_replace('\\', '/', $path);
				$path = strrpos($path, '/') + 1 == strlen($path) ? substr($path, 0, -1) : $path;
				preg_match("/^([a-zA-Z0-9._]+:\/\/[^\/]+\/|[a-zA-Z0-9._]+:\/|\/)(.+)/i", $path, $parses);
				$parses_arr = explode('/', $parses[2]);

				$relativePath = str_replace('\\', '/', $relativePath);
				$relativePath = strrpos($relativePath, '/') + 1 == strlen($relativePath) ? substr($relativePath, 0, -1) : $relativePath;

				$relative_arr = explode('/', $relativePath);

				foreach($relative_arr as $curcmd){
								switch($curcmd){
												case '..':
																if(sizeof($parses_arr) >= 1){
																				array_pop($parses_arr);
																}
																break;
												case '.':
																break;
												default:
																$parses_arr[] = $curcmd;
																break;
								}
				}
				return $parses[1] . (sizeof($parses_arr) >= 1 ? implode("/", $parses_arr) : "");
}

/**
 * search content
 * 
 * @name search
 * @author ustb 
 * @last ustb
 * @update 2006/01/06 14:49:04
 * @version 0.1
 * @param character $keywords 
 * @param string $con (OR/AND default="OR")
 * @param string $method (like/exact)
 * @param string $field 
 */
function setSearchSQL($keyword, $con, $method, $field){
				$tmp = '';
				$keyword = split("[ \t\r\n\,]+", $keyword);
				$num_word = count($keyword); //统计关键字个?
				$num = count($field);
				if($con == ''){
								$con = "OR";
				}
				if($method == "like"){ // 模糊查找
								for($i = 0; $i < $num; $i++){
												$i < $num-1?$condition = $con:$condition = null;
												$tmp .= " {$field[$i]} $method '%" . join("%' $con {$field[$i]} $method '%", $keyword) . "%' $condition";
								}
				}elseif($method == "exact"){ // 精确查找
								for($j = 0; $j < $num; $j++){
												$j < $num-1?$condition = $con:$condition = null;
												$tmp .= " instr({$field[$j]},'" . join("')!=0 $con instr({$field[$j]},'", $keyword) . "')!=0 $condition";
								}
				}
				return $tmp;
}


function respCDATA($value){
				$value = is_array($value) ?
				array_map('respCDATA', $value) :
				('<![CDATA['.$value.']]>');
				return $value;
}

/**
 * response info and action
 * 
 * @name respInfo
 * @author nickfan<nickfan81@gmail.com> 
 * @last nickfan<nickfan81@gmail.com>
 * @update 2006/02/14 13:50:08
 * @method Multi Params:
 * @param mixed $paramarray 
 * @method Array:
 * @example 1
 * 
 * $reinfarr1=array(
 *        'msg'=>'Google',
 *        'url'=>'http://www.google.com/',
 *        'style'=>'text',
 *        'time'=>5,
 *        'state'=>1,
 *        'response'=>'html',
 *        'header'=>1,
 *        'textarray'=>NULL,
 *        'custarray'=>NULL,
 *        'temple'=>NULL,
 *        'relpacearray'=>NULL,
 *        'type'=>'location',
 *        'frame'=>'self'
 * );
 * respInfo($reinfarr1);
 * @example 2
 * 
 * $reinfarr2=array(
 *        'msg'=>array('google is <!--[/trg/]--> ','and this is my temp test'),
 *        'url'=>array(
 *        array('text'=>'google','url'=>'http://www.google.com/'),
 *        array('text'=>'yahoo','url'=>'http://www.yahoo.com/'),
 *        array('text'=>'close me ','type'=>'close'),
 *        array('text'=>'go back ','type'=>'back'),
 *        array('text'=>'noting ','type'=>'stand'),
 *        array('text'=>'baidu','url'=>'http://www.baidu.com/')
 *        ),
 *        'style'=>'text',
 *        'time'=>10,
 *        'state'=>1,
 *        'response'=>'html',
 *        'header'=>1,
 *        'textarray'=>array('notice_default'=>"系统将在 <!--[/time/]--> 秒内执行默认动作："),
 *        'custarray'=>array('trg'=>'good'),
 *        'temple'=>NULL,
 *        'relpacearray'=>NULL,
 *        'type'=>'location',
 *        'frame'=>'self'
 * );
 * respInfo($reinfarr2);
 */
function respInfo($paramarray){ 
// $thisargs = func_get_args();
// $paramarray=isset($thisargs[0])?$thisargs[0]:NULL;
/* init input variables */
/*
	string/array    msg             ''                                              default:    ''
	string/array    url             HTTP_REFERER/about:blank                        default:    HTTP_REFERER/about:blank
	string          style           script/text                                     default:    'text'
	int             time            [integer]                                       default:    3
	int             state           1/0                                             default:    1
	string          response        html/xml/serialize/json                                        default:    'html'
	int             header          1/0                                             default:    1
	string          charset         utf-8/iso8859-1/gb2312/gbk/big5                 default:    'utf-8'
	array           textarray       [array]                                         default:    NULL
	array           custarray       [array]                                         default:    NULL
	string          temple          [string]                                        default:    NULL
	array           relpacearray    [array]                                         default:    NULL
	string          type            header/back/close/page/location/refresh/stand   default:    'location'
	string          frame           self/blank/top/parent/[string]                  default:    'self'
	string          exscript        javascript                                      default:    ''    
*/

	$msg = array_key_exists('msg', $paramarray)?$paramarray['msg']:'';
	$url = array_key_exists('url', $paramarray)?(!empty($paramarray['url'])?$paramarray['url']:(isset($_SERVER["HTTP_REFERER"])?$_SERVER["HTTP_REFERER"]:"about:blank")):(isset($_SERVER["HTTP_REFERER"])?$_SERVER["HTTP_REFERER"]:"about:blank");
	$style = array_key_exists('style', $paramarray)?$paramarray['style']:'text';
	$time = array_key_exists('time', $paramarray)?intval($paramarray['time']):3;
	$state = array_key_exists('state', $paramarray)?$paramarray['state']:'1';
	$response = array_key_exists('response', $paramarray)?$paramarray['response']:'html';
	$header = array_key_exists('header', $paramarray)?intval($paramarray['header']):0;
	$charset = array_key_exists('charset', $paramarray) && $paramarray['charset'] != ''?$paramarray['charset']:'utf-8';
	$textarray = array_key_exists('textarray', $paramarray) && is_array($paramarray['textarray']) && !empty($paramarray['textarray'])?$paramarray['textarray']:null;
	$custarray = array_key_exists('custarray', $paramarray) && is_array($paramarray['custarray']) && !empty($paramarray['custarray'])?$paramarray['custarray']:null;
	$temple = array_key_exists('temple', $paramarray)?$paramarray['temple']:null;
	$relpacearray = array_key_exists('relpacearray', $paramarray) && is_array($paramarray['relpacearray']) && !empty($paramarray['relpacearray'])?$paramarray['relpacearray']:null;
	$layout_w = array_key_exists('layout_w', $paramarray)?$paramarray['layout_w']:'580px';
	$layout_h = array_key_exists('layout_h', $paramarray)?$paramarray['layout_w']:'auto';

	if($response=='json'){
		echo @json_encode($paramarray);
		exit;
	}elseif($response=='sz'||$response=='serialize'){
		echo serialize($paramarray);
		exit;
	}

	/* if url is string */
	$type = array_key_exists('type', $paramarray)?$paramarray['type']:'location';
	$frame = array_key_exists('frame', $paramarray) && is_string($paramarray['frame']) && ($paramarray['frame'] != '' || !empty($paramarray['frame']))?$paramarray['frame']:'self';
	$target = in_array($frame, array('blank', 'top', 'self', 'parent'))?"_" . $frame:$frame;
	$exscript = array_key_exists('exscript', $paramarray)?$paramarray['exscript']:'';

	/* include custarray variables */
	if(!empty($custarray)){
					extract($custarray, EXTR_SKIP);
	}

	/* set currenttextarray */
	$currenttextarray = array(
		'notice_default' => " the page will execute the default action in <!--[/time/]--> second(s) ",
		'text_location' => "[ redirect ]",
		'notice_location' => " the page will redirect in <!--[/time/]--> second(s),click here to redirect manually: ",
		'text_close' => "[ close ]",
		'notice_close' => " the page will close in <!--[/time/]--> second(s),click here to close manually: ",
		'text_refresh' => "[ redirect ]",
		'notice_refresh' => " the page will redirect in <!--[/time/]--> second(s),click here to redirect manually: ",
		'text_page' => "[ redirect ]",
		'notice_page' => " the page will redirect in <!--[/time/]--> second(s),click here to redirect manually: ",
		'text_stand' => "[ redirect ]",
		'notice_stand' => " click here to redirect manually: ",
		'text_back' => "[ go back ]",
		'notice_back' => " the page will go back in <!--[/time/]--> second,click here to go back manually: "
	);
	/* merge the default array and incoming array */
	if(!empty($textarray)){
		$currenttextarray = array_merge($currenttextarray, $textarray);
	}

	/* set thistextarray */
	$thistextarray = array();
	foreach($currenttextarray as $textkey => $textvalue){
		$thistextarray[$textkey] = preg_replace('/<!--\[\/([\w]+)\/\]-->/e', '$\\1', $textvalue);
	}

	/* set thismsg */
	switch($style){
		case 'script':
			/* set thismsg */
			if(is_string($msg) && ($msg != '' || !empty($msg))){
				$msg = preg_replace('/<!--\[\/([\w]+)\/\]-->/e', '$\\1', $msg);
				$thismsg = addslashes($msg);
			}elseif(is_array($msg) && !empty($msg)){
				$thismsg = array();
				foreach($msg as $msg_key => $msg_val){
					$thismsg[$msg_key] = preg_replace('/<!--\[\/([\w]+)\/\]-->/e', '$\\1', $msg_val);
				}
				$thismsg = implode("\\n", array_map("addslashes", $thismsg));
			}else{
				$thismsg = "";
			}
			break;
		case 'text':
		default:
			/* set thismsg */
			if(is_string($msg) && ($msg != '' || !empty($msg))){
				$thismsg = preg_replace('/<!--\[\/([\w]+)\/\]-->/e', '$\\1', $msg);
			}elseif(is_array($msg) && !empty($msg)){
				$thismsg = array();
				foreach($msg as $msg_key => $msg_val){
					$thismsg[$msg_key] = preg_replace('/<!--\[\/([\w]+)\/\]-->/e', '$\\1', $msg_val);
				}
				$thismsg = implode("<br />", $thismsg);
			}else{
				$thismsg = "";
			}
	}

	/* set thistemple */
	if(!empty($temple)){
		$thistemple = $temple;
	}else{
		/**
		 * predefine replacement
		 * <!--[/framecheck/]-->
		 * <!--[/thismsg/]-->
		 * <!--[/linkstr/]-->
		 * <!--[/actionstr/]-->
		 */
		$thistemple = <<<TEMPLE
<!--[/framecheck/]-->
<style type="text/css">
.linkcurrent, #currentaction
{
	color:#ff6600;font-weight:bold; font-size: 12px;
}
.linkdefault, #defaultaction
{
	color:#333;font-weight:bolder; font-size: 12px; 
}

A:visited
{
	font-size: 12px;
	color:#215dc6;
	font-weight:normal;
	TEXT-DECORATION:none;
}
A:active
{
	font-size: 12px;
	color:#215dc6;
	font-weight:bolder;
	TEXT-DECORATION:none;
}
A:hover
{
	font-size: 12px;
	color:#428eff;
	font-weight:bold;
	TEXT-DECORATION:none;
	border-bottom:1px dashed;
}
A:link
{
	font-size: 12px;
	color:#215dc6;
	font-weight:normal;
	TEXT-DECORATION:none;
}

body{font-size: 12px;font-family: Tahoma, Verdana, sans-serif, Arial;TEXT-ALIGN: center;color:#333;background-color:#fff;}
*,body{margin:0 auto;padding:0;}
#noticeinfo{}
#noticecenter{margin-top:58px; border:1px #B3DEF4 solid; background:#F7FCFE; width:{$layout_w}; height:{$layout_h}; padding:8px;}
#noticecenter p{line-height:1.8em;}
</style>

<div id="noticeinfo">
<div id="noticecenter">
<p><!--[/thismsg/]--></p>
<p><!--[/linkstr/]--></p>
</div>
<!--[/actionstr/]-->
TEMPLE;
	} //end of set thistemple

	switch($response){
		case 'xml':
			$forwardsstr = "";
			if(!is_array($url)){
				$forwardsstr .= "<forward id=\"0\"><url><![CDATA[" . $url . "]]></url><text><![CDATA[]]></text><type>" . $type . "</type><frame>" . $frame . "</frame></forward>";
			}else{
				foreach($url as $key => $val){
					$forwardsstr .= "<forward id=\"" . $key . "\">";
					foreach($val as $ikey => $ival){
						switch($ikey){
							case "url":
									$forwardsstr .= "<url><![CDATA[" . (!empty($ival)?$ival:(isset($_SERVER["HTTP_REFERER"])?$_SERVER["HTTP_REFERER"]:"about:blank")) . "]]></url>";
								break;
							case "text":
									$forwardsstr .= "<text><![CDATA[" . $ival . "]]></text>";
								break;
							case "type":
									$forwardsstr .= "<type>" . $ival . "</type>";
								break;
							case "frame":
									$forwardsstr .= "<frame>" . $ival . "</frame>";
								break;
						}
					}
					$forwardsstr .= "</forward>";
				}
			}
			$responsearray = array(
				"state" => $state,
				"msg" => "<![CDATA[" . $thismsg . "]]>",
				"time" => $time,
				"style" => $style,
				"forwards" => $forwardsstr
			);

			if($header == 1){
				respXML(null, $charset);
			}
			echo setXMLHeader($charset) . setXMLRoot('response', setXMLTree($responsearray));
			exit;
		break;

		case 'script':
				if($header == 1){
					header("Content-Type: text/script; charset=".$charset);
				}
				echo $exscript;
				exit;
			break;
		case 'html':
		default:
				if(!is_array($url)){
					if($type == 'header'){
						header("Location:" . $url);
						exit;
						return;
					}

					switch($style){
						case "script":
								switch($type){
									case 'header':
											header("Location:" . $url);
											exit;
											return;
										break;
									case 'back':
											$actionstr = "<script type=\"text/javascript\">" . $exscript . " history.back();</script>";
										break;
									case 'close':
											$actionstr = "<script type=\"text/javascript\">" . $exscript . " window.close();</script>";
										break;
									case 'page':
											$actionstr = "<script type=\"text/javascript\">" . $exscript . " if (top.location !== self.location){top.location=self.location;} location.href = '" . $url . "';</script>";
										break;
									case 'location':
											$actionstr = ($frame != 'self') ? "<script type=\"text/javascript\">" . $exscript . " top.window['" . $frame . "'].location.href='" . $url . "';</script>" : "<script type=\"text/javascript\">self.location.href='" . $url . "';</script>";
										break;
									case 'refresh':
											$actionstr = "<meta http-equiv=\"Window-target\" content=\"" . $target . "\" /><meta http-equiv=\"Refresh\" content=\"0; url=" . $url . "\" />";
										break;
									case 'stand':
									default:
											$actionstr = "<script type=\"text/javascript\">" . $exscript . "</script>";
										break;
								}

								if($header == 1){
									respHTML(null, $charset);
								}
								if(!empty($thismsg)){
									echo "<script type=\"text/javascript\">window.alert('" . $thismsg . "');</script>";
								}
								echo $actionstr;
								exit;
							break;
						case "text":
						default:

								switch($type){
									case 'location':
											$framecheck = "";
											$linkstr = ($frame != 'self') ? $thistextarray['notice_location'] . "<a href=\"javascript:" . $exscript . "top.window['" . $frame . "'].location.href='" . $url . "';\" name=\"currentaction\" id=\"currentaction\" target=\"" . $target . "\" class=\"linkcurrent\">" . $thistextarray['text_location'] . "</a> <script type=\"text/javascript\">document.getElementById('currentaction').focus();</script>" : $thistextarray['notice_location'] . "<a href=\"javascript:" . $exscript . "self.location.href='" . $url . "';\" name=\"currentaction\" id=\"currentaction\" target=\"" . $target . "\" class=\"linkcurrent\">" . $thistextarray['text_location'] . "</a> <script type=\"text/javascript\">document.getElementById('currentaction').focus();</script>";
											$actionstr = ($frame != 'self') ? "<script type=\"text/javascript\">setTimeout(\"" . $exscript . "top.window['" . $frame . "'].location.href='" . $url . "';\",1000*" . $time . ");</script>" : "<script type=\"text/javascript\">setTimeout(\"" . $exscript . "self.location.href='" . $url . "';\",1000*" . $time . ");</script>"; 
											// $actionstr.="<meta http-equiv=\"Window-target\" content=\"".$target."\"><meta http-equiv=\"Refresh\" content=\"".$time."; url=".$url."\">";
										break;
									case 'close':
											$framecheck = "";
											$linkstr = ($frame != 'self') ? $thistextarray['notice_close'] . "<a href=\"javascript:" . $exscript . "top.window['" . $frame . "'].close();\" name=\"currentaction\" id=\"currentaction\" class=\"linkcurrent\">" . $thistextarray['text_close'] . "</a> <script type=\"text/javascript\">document.getElementById('currentaction').focus();</script>" : $thistextarray['notice_close'] . "<a href=\"javascript:" . $exscript . "self.close();\" name=\"currentaction\" id=\"currentaction\" class=\"linkcurrent\">" . $thistextarray['text_close'] . "</a> <script type=\"text/javascript\">document.getElementById('currentaction').focus();</script>";
											$actionstr = ($frame != 'self') ? "<script type=\"text/javascript\">setTimeout(\"" . $exscript . "top.window['" . $frame . "'].close();\",1000*" . $time . ");</script>" : "<script type=\"text/javascript\">setTimeout(\"" . $exscript . "self.close();\",1000*" . $time . ");</script>";
										break;
									case 'refresh':
											$framecheck = "";
											$linkstr = $thistextarray['notice_refresh'] . "<a href=\"" . $url . "\" name=\"currentaction\" id=\"currentaction\" target=\"" . $target . "\" class=\"linkcurrent\">" . $thistextarray['text_refresh'] . "</a> <script type=\"text/javascript\">document.getElementById('currentaction').focus();</script>";
											$actionstr = "<meta http-equiv=\"Window-target\" content=\"" . $target . "\"><meta http-equiv=\"Refresh\" content=\"" . $time . "; url=" . $url . "\">";
										break;
									case 'page':
											$framecheck = ""; 
											// $framecheck="<script type=\"text/javascript\">if (top.location !== self.location) top.location=self.location;</script>";
											$linkstr = $thistextarray['notice_page'] . "<a href=\"javascript:" . $exscript . " var pageredirect=function(){ if (top.location !== self.location){top.location=self.location;} location.href = '" . $url . "'; return ; }; pageredirect();\" name=\"currentaction\" id=\"currentaction\" target=\"" . $target . "\" class=\"linkcurrent\">" . $thistextarray['text_page'] . "</a> <script type=\"text/javascript\">document.getElementById('currentaction').focus();</script>";
											$actionstr = "<script type=\"text/javascript\">setTimeout(\"" . $exscript . "if (top.location !== self.location) top.location=self.location;location.href='" . $url . "';\",1000*" . $time . ");</script>";
										break;
									case 'stand':
											$framecheck = "";
											$linkstr = empty($url)?"":$thistextarray['notice_stand'] . "<a href=\"" . $url . "\" name=\"currentaction\" id=\"currentaction\" target=\"" . $target . "\" class=\"linkcurrent\">" . $thistextarray['text_stand'] . "</a> <script type=\"text/javascript\">document.getElementById('currentaction').focus();</script>";
											$actionstr = "";
										break;
									case 'back':
									default:
											$framecheck = "";
											$linkstr = $thistextarray['notice_back'] . "<a href=\"javascript:" . $exscript . " history.go(-1);\" name=\"currentaction\" id=\"currentaction\" class=\"linkcurrent\">" . $thistextarray['text_back'] . "</a> <script type=\"text/javascript\">document.getElementById('currentaction').focus();</script>";
											$actionstr = "<script type=\"text/javascript\">setTimeout(\"" . $exscript . "history.back()\",1000*" . $time . ");</script>";
										break;
								}

								if($header == 1){
										respHTML(null, $charset);
								}
								if(!empty($relpacearray)){
										$thisreplacement = $relpacearray;
								}
								$thisreplacement = array(
									'<!--[/framecheck/]-->' => $framecheck,
									'<!--[/thismsg/]-->' => $thismsg,
									'<!--[/linkstr/]-->' => $linkstr,
									'<!--[/actionstr/]-->' => $actionstr
								);
								$outputcontent = str_replace(array_keys($thisreplacement), array_values($thisreplacement), $thistemple);
								echo $outputcontent;
							break;
					}
					exit;
					return;
				}else{
					switch($style){
						case "script":
								if(isset($url[0])){
									$cur_text = array_key_exists('text', $url[0]) && !empty($url[0]['text'])?$url[0]['text']:'';
									$cur_url = (array_key_exists('url', $url[0]) && !empty($url[0]['url'])?$url[0]['url']:(isset($_SERVER["HTTP_REFERER"])?$_SERVER["HTTP_REFERER"]:"about:blank"));
									$cur_type = array_key_exists('type', $url[0]) && !empty($url[0]['type'])?$url[0]['type']:'location';
									$cur_frame = array_key_exists('frame', $url[0]) && is_string($url[0]['frame']) && ($url[0]['frame'] != '' || !empty($url[0]['frame']))?$url[0]['frame']:'self';
									$cur_target = in_array($cur_frame, array('blank', 'top', 'self', 'parent'))?"_" . $cur_frame:$cur_frame;
									$cur_exscript = array_key_exists('exscript', $url[0]) && !empty($url[0]['exscript'])?$url[0]['exscript']:'';
									switch($cur_type){
										case 'header':
												header("Location:" . $cur_url);
												exit;
												return;
											break;
										case 'close':
												$actionstr = ($cur_frame != 'self') ? "<script type=\"text/javascript\">" . $cur_exscript . "top.window['" . $cur_frame . "'].close();</script>" : "<script type=\"text/javascript\">" . $cur_exscript . "self.close();</script>";
											break;
										case 'page':
												$actionstr = "<script type=\"text/javascript\">" . $cur_exscript . "if (top.location !== self.location){top.location=self.location;} location.href = '" . $cur_url . "';</script>";
											break;
										case 'location':
												$actionstr = ($cur_frame != 'self') ? "<script type=\"text/javascript\">" . $cur_exscript . "top.window['" . $cur_frame . "'].location.href='" . $cur_url . "';</script>" : "<script type=\"text/javascript\">" . $cur_exscript . "self.location.href='" . $cur_url . "';</script>";
											break;
										case 'refresh':
												$actionstr = "<meta http-equiv=\"Window-target\" content=\"" . $cur_target . "\"><meta http-equiv=\"Refresh\" content=\"0; url=" . $cur_url . "\">";
											break;
										case 'stand':
												$actionstr = "";
											break;
										case 'back':
										default:
												$actionstr = "<script type=\"text/javascript\">" . $cur_exscript . "history.back();</script>";
											break;
									}
								}else{
									$actionstr = "<script type=\"text/javascript\">history.back();</script>";
								}

								if($header == 1){
									respHTML(null, $charset);
								}
								if(!empty($thismsg)){
									echo "<script type=\"text/javascript\">window.alert('" . $thismsg . "');</script>";
								}
								echo $actionstr;
								exit;
							break;
						case "text":
						default:
								$link_arr = array();

								if(isset($url[0])){
									$def_text = array_key_exists('text', $url[0]) && !empty($url[0]['text'])?$url[0]['text']:' ';
									$def_url = (array_key_exists('url', $url[0]) && !empty($url[0]['url'])?$url[0]['url']:(isset($_SERVER["HTTP_REFERER"])?$_SERVER["HTTP_REFERER"]:"about:blank"));
									$def_type = array_key_exists('type', $url[0]) && !empty($url[0]['type'])?$url[0]['type']:'location';
									$def_frame = array_key_exists('frame', $url[0]) && is_string($url[0]['frame']) && ($url[0]['frame'] != '' || !empty($url[0]['frame']))?$url[0]['frame']:'self';
									$def_target = in_array($def_frame, array('blank', 'top', 'self', 'parent'))?"_" . $def_frame:$def_frame;
									$def_exscript = array_key_exists('exscript', $url[0]) && !empty($url[0]['exscript'])?$url[0]['exscript']:'';
									switch($def_type){
										case 'header':
												header("Location:" . $def_url);
												exit;
												return;
											break;
										case 'close':
												$framecheck = "";
												$actionstr = ($def_frame != 'self') ? "<script type=\"text/javascript\">setTimeout(\"" . $def_exscript . "top.window['" . $def_frame . "'].close();\",1000*" . $time . ");</script>" : "<script type=\"text/javascript\">setTimeout(\"" . $def_exscript . "self.close();\",1000*" . $time . ");</script>";
												$link_arr[] = $thistextarray['notice_default'];
											break;
										case 'page':
												$framecheck = ""; 
												// $framecheck="<script type=\"text/javascript\">if (top.location !== self.location) top.location=self.location;</script>";
												$actionstr = "<script type=\"text/javascript\">setTimeout(\"" . $def_exscript . "if(top.location !== self.location){top.location=self.location;} location.href='" . $def_url . "';\",1000*" . $time . ");</script>";
												$link_arr[] = $thistextarray['notice_default'];
											break;
										case 'location':
												$framecheck = "";
												$actionstr = ($def_frame != 'self') ? "<script type=\"text/javascript\">setTimeout(\"" . $def_exscript . "top.window['" . $def_frame . "'].location.href='" . $def_url . "';\",1000*" . $time . ");</script>" : "<script type=\"text/javascript\">setTimeout(\"" . $def_exscript . "self.location.href='" . $def_url . "';\",1000*" . $time . ");</script>";
												$link_arr[] = $thistextarray['notice_default'];
											break;
										case 'refresh':
												$framecheck = "";
												$actionstr = "<meta http-equiv=\"Window-target\" content=\"" . $def_target . "\"><meta http-equiv=\"Refresh\" content=\"" . $time . "; url=" . $def_url . "\">";
												$link_arr[] = $thistextarray['notice_default'];
											break;
										case 'stand':
												$framecheck = "";
												$actionstr = "";
											break;
										case 'back':
										default:
												$framecheck = "";
												$actionstr = "<script type=\"text/javascript\"> setTimeout(\"" . $def_exscript . "history.back()\",1000*" . $time . ");</script>";
												$link_arr[] = $thistextarray['notice_default'];
											break;
									}
								}else{
									$framecheck = "";
									$actionstr = "<script type=\"text/javascript\">setTimeout(\"history.back()\",1000*" . $time . ");</script>";
								}

								foreach($url as $url_key => $url_val){
									$cur_text = array_key_exists('text', $url_val) && !empty($url_val['text'])?$url_val['text']:' ';
									$cur_url = (array_key_exists('url', $url_val) && !empty($url_val['url'])?$url_val['url']:(isset($_SERVER["HTTP_REFERER"])?$_SERVER["HTTP_REFERER"]:"about:blank"));
									$cur_type = array_key_exists('type', $url_val) && !empty($url_val['type'])?$url_val['type']:'location';
									$cur_frame = array_key_exists('frame', $url_val) && is_string($url_val['frame']) && ($url_val['frame'] != '' || !empty($url_val['frame']))?$url_val['frame']:'self';
									$cur_target = in_array($cur_frame, array('blank', 'top', 'self', 'parent'))?"_" . $cur_frame:$cur_frame;
									$cur_exscript = array_key_exists('exscript', $url_val) && !empty($url_val['exscript'])?$url_val['exscript']:'';
									if($url_key == 0){
										switch($cur_type){
											case 'location':
													$link_arr[] = ($cur_frame != 'self') ? "<a href=\"javascript:" . $cur_exscript . "top.window['" . $cur_frame . "'].location.href='" . $cur_url . "';\" name=\"defaultaction\" id=\"defaultaction\" class=\"linkdefault\">" . $cur_text . "</a> <script type=\"text/javascript\">document.getElementById('defaultaction').focus();</script>" : "<a href=\"javascript:" . $cur_exscript . "self.location.href='" . $cur_url . "';\" name=\"defaultaction\" id=\"defaultaction\" class=\"linkdefault\">" . $cur_text . "</a> <script type=\"text/javascript\">document.getElementById('defaultaction').focus();</script>";
												break;
											case 'close':
													$link_arr[] = ($cur_frame != 'self') ? "<a href=\"javascript:" . $cur_exscript . "top.window['" . $cur_frame . "'].close();\" name=\"defaultaction\" id=\"defaultaction\" class=\"linkdefault\">" . $cur_text . "</a> <script type=\"text/javascript\">document.getElementById('defaultaction').focus();</script>" : "<a href=\"javascript:" . $cur_exscript . "self.close();\" name=\"defaultaction\" id=\"defaultaction\" class=\"linkdefault\">" . $cur_text . "</a> <script type=\"text/javascript\">document.getElementById('defaultaction').focus();</script>";
												break;
											case 'refresh':
													$link_arr[] = "<a href=\"" . $cur_url . "\" name=\"defaultaction\" id=\"defaultaction\" target=\"" . $cur_target . "\" class=\"linkdefault\">" . $cur_text . "</a> <script type=\"text/javascript\">document.getElementById('defaultaction').focus();</script>";
												break;
											case 'page':
													$link_arr[] = "<a href=\"javascript:" . $cur_exscript . " var pageredirect=function(){ if (top.location !== self.location){top.location=self.location;} location.href = '" . $cur_url . "'; return ; }; pageredirect();\" name=\"defaultaction\" id=\"defaultaction\" target=\"" . $cur_target . "\" class=\"linkdefault\">" . $cur_text . "</a> <script type=\"text/javascript\">document.getElementById('defaultaction').focus();</script>";
												break;
											case 'stand':
													$link_arr[] = "<a href=\"" . $cur_url . "\" name=\"defaultaction\" id=\"defaultaction\" target=\"" . $cur_target . "\" class=\"linkdefault\">" . $cur_text . "</a> <script type=\"text/javascript\">document.getElementById('defaultaction').focus();</script>";
												break;
											case 'back':
											default:
													$link_arr[] = "<a href=\"javascript:" . $cur_exscript . " history.back();\" name=\"defaultaction\" id=\"defaultaction\" class=\"linkdefault\">" . $cur_text . "</a> <script type=\"text/javascript\">document.getElementById('defaultaction').focus();</script>";
												break;
										}
									}else{
										switch($cur_type){
											case 'location':
													$link_arr[] = ($cur_frame != 'self') ? "<a href=\"javascript:" . $cur_exscript . "top.window['" . $cur_frame . "'].location.href='" . $cur_url . "';\" name=\"currentaction\" id=\"currentaction\" class=\"linkcurrent\">" . $cur_text . "</a>" : "<a href=\"javascript:" . $cur_exscript . "self.location.href='" . $cur_url . "';\" name=\"currentaction\" id=\"currentaction\" class=\"linkcurrent\">" . $cur_text . "</a>";
												break;
											case 'close':
													$link_arr[] = ($cur_frame != 'self') ? "<a href=\"javascript:" . $cur_exscript . "top.window['" . $cur_frame . "'].close();\" name=\"currentaction\" id=\"currentaction\" class=\"linkcurrent\">" . $cur_text . "</a>" : "<a href=\"javascript:" . $cur_exscript . "self.close();\" name=\"currentaction\" id=\"currentaction\" class=\"linkcurrent\">" . $cur_text . "</a>";
												break;
											case 'refresh':
													$link_arr[] = "<a href=\"" . $cur_url . "\" name=\"currentaction\" id=\"currentaction\" target=\"" . $cur_target . "\" class=\"linkcurrent\">" . $cur_text . "</a>";
												break;
											case 'page':
													$link_arr[] = "<a href=\"javascript:" . $cur_exscript . " var pageredirect=function(){ if (top.location !== self.location){top.location=self.location;} location.href = '" . $cur_url . "'; return ; }; pageredirect();\" name=\"currentaction\" id=\"currentaction\" target=\"" . $cur_target . "\" class=\"linkcurrent\">" . $cur_text . "</a>";
												break;
											case 'stand':
													$link_arr[] = "<a href=\"" . $cur_url . "\" name=\"currentaction\" id=\"currentaction\" target=\"" . $cur_target . "\" class=\"linkcurrent\">" . $cur_text . "</a>";
												break;
											case 'back':
											default:
													$link_arr[] = "<a href=\"javascript:" . $cur_exscript . " history.back();\" name=\"currentaction\" id=\"currentaction\" class=\"linkcurrent\">" . $cur_text . "</a>";
												break;
										}
									}
								}
								$linkstr = implode("<br /><br />", $link_arr);
								if($header == 1){
									respHTML(null, $charset);
								}
								if(!empty($relpacearray)){
									$thisreplacement = $relpacearray;
								}
								$thisreplacement = array(
									'<!--[/framecheck/]-->' => $framecheck,
									'<!--[/thismsg/]-->' => $thismsg,
									'<!--[/linkstr/]-->' => $linkstr,
									'<!--[/actionstr/]-->' => $actionstr
								);
								$outputcontent = str_replace(array_keys($thisreplacement), array_values($thisreplacement), $thistemple);
								echo $outputcontent;
							break;
					}
				}
			break;
	}
	/* response action end */
	exit;
	return;
} // end of function respInfo


/**
 * 函数说明: 设置一个XML文件结构
 * 
 * @author 谢立<mrshelly@hotmail.com> 
 * @history 2007-01-15 12:44:53
 * @var 输入变量值
 * @block 变量类型
 * @return 返回按变量类型 处理后的变量.
 */

function filteVar($var, $block) {
	$var = str_replace("\\\"","\"",$var);
	$var = str_replace("\\\'","\"",$var);
	switch($block){
		case	"title"	:
				$var = escape(strip_tags(nl2br(trim($var))));
			break;
		case	"content"	:
				$var=escape(wp_rel_nofollow(RemoveXSS(nl2br(trim($var)))));
			break;
		case	"id"	:
				$var = (int)$var;
			break;
		case	"sid"	:
				if(!preg_match("/^[\d]+_[0-9a-f]*$/is",$var)){
					$var = "";
				}
			break;
	}
	return $var;
}


/**
 * 函数说明: 简单排序一个二维数组(指定 key)
 * 
 * @author 谢立<mrshelly@hotmail.com> 
 * @history 2007-05-19 10:21:05
 * @array 入参 可变数组(&)
 * @key   入能 指定按哪个key排序
 * @return 返回按key 排序后的数组, 由 原 $array 返回,不带 key
 * 例 : 取得 系统推荐TAG时,可以指定排序方式 
 *      getArraySorted(&$siteCommonData['seekpai.system.sysdigtags'],'priv');
 */

function getArraySorted($array,$key){
	if(is_array($array) && count($array)>0 && array_key_exists($key,$array[array_shift(array_keys($array))])){
		usort($array, create_function('$a,$b', 'return ($a[\''.$key.'\'] < $b[\''.$key.'\']) ?-1:(($a[\''.$key.'\'] > $b[\''.$key.'\'])?1:0);'));
	}

}
?>