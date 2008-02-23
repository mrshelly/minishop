<?php
	/* CURL 类定义 自 cn.php.net/manual/ */
		class	CURL{
			var	$callback = false;
			var	$curl_ch;
			var	$getHeader=0;

			function	setCallback($func_name){
				$this->callback = $func_name;
			}

			function	doInit(){
				if(!$this->curl_ch){
					$this->curl_ch=curl_init();
				}
			}

			function	doEnd(){
				if($this->curl_ch){
					curl_close($this->curl_ch);
				}
			}

			function	setOption($part,$val){
				switch($part){
					case	CURLOPT_HEADER	:
						$this->getHeader = $val;
						break;
				}

				curl_setopt($this->curl_ch, $part, $val);
			}

			function	doRequest($method, $url, $vars){
				$this->setOption(CURLOPT_URL, $url);
				$this->setOption(CURLOPT_HEADER,$this->getHeader);
				$this->setOption(CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
				$this->setOption(CURLOPT_FOLLOWLOCATION,1);
				$this->setOption(CURLOPT_RETURNTRANSFER,1);

				if($method == "POST"){
					$this->setOption(CURLOPT_POST,true);
					$this->setOption(CURLOPT_POSTFIELDS,$vars);
				}

				$data = curl_exec($this->curl_ch);
				if($data){
					if($this->callback){
						$callback= $this->callback;
						$this->callback = false;
						return call_user_func($callback, $data);
					}else{
						return $data;
					}
				}else{
					return curl_error($this->curl_ch);
				}
			}

			function	get($url){
				return $this->doRequest('GET', $url, 'NULL');
			}

			function	post($url, $vars){
				return $this->doRequest('POST', $url, $vars);
			}
		}
?>