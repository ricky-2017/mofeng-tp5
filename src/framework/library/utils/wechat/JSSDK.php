<?php
namespace mofeng\tp5\utils\wechat;

class JSSDK{
	private $appId;
	private $appSecret;
	private $url;
	
	public function __construct($appId, $appSecret, $url=null){
		$this->appId = $appId;
		$this->appSecret = $appSecret;
		$this->url = $url;
	}
	
	/**
	 * 获取签名包
	 */
	public function getSignPackage(){
		$jsapiTicket = WxApi::getJsApiTicket();
		
		if(empty($this->url)){//同步返回
			// 注意 URL 一定要动态获取，不能 hardcode.
			$protocol = (!empty($_SERVER ['HTTPS']) && $_SERVER ['HTTPS'] !== 'off' || $_SERVER ['SERVER_PORT'] == 443) ? "https://" : "http://";
			$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		}else{//异步返回(ajax请求)
			$url = $this->url;
		}
		
		$timestamp = time();
		$nonceStr = $this->createNonceStr();
		
		// 这里参数的顺序要按照 key 值 ASCII 码升序排序//
		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
		
		$signature = sha1($string);
		
		$signPackage = array(
				"appId" => $this->appId, 
				"nonceStr" => $nonceStr, 
				"timestamp" => $timestamp, 
				"url" => $url, 
				"signature" => $signature, 
				"rawString" => $string
		);
		return $signPackage;
	}
	
	/**
	 * 生成随机串
	 * @param number $length
	 * @return string
	 */
	private function createNonceStr($length = 16){
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for($i = 0; $i < $length; $i++){
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}
}

