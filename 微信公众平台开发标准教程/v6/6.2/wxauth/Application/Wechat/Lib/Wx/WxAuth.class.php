<?php
// 微信网页授权类

class WxAuth
{
	// 用户微信唯一标识
	private $openid;
	private $access_token;

	// 开发者账号信息
	private $appid;
	private $appsecret;

	// 用户微信基本信和回调地址
	private $userinfo;
	private $returnurl;

	// 构造方法
	public function __construct($appid = '' , $appsecret = '')
	{
		if(!$appid || !$appsecret)
		{
			exit('Param Error!');
		}
		$this->appid = $appid;
		$this->appsecret = $appsecret;
	}

	// 设置回调地址
	// @param url:U(控制器/方法) http_type:网址类型0:http,1:https
	public function setReturnUrl($url , $http_type = 0)
	{
		if(!$url)
		{
			return false;
		}
		$this->returnurl =( $http_type ? 'https://' : 'http://' ) . $_SERVER['SERVER_NAME'] . $url ;
		return true;
	}

	// 获取跳转地址
	public function getReturnUrl()
	{
		return $thhis->returnurl;
	}

	// 创建授权地址
	public function createWxAuthUrl( $scope_type = 0 )
	{
		if(!$this->appid || !$this->returnurl)
		{
			return false;
		}
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?";
		$url .= "appid=".$this->appid;
		$url .= "&redirect_uri=".$this->returnurl;
		$url .= "&response_type=code";
		$url .= "&scope=". ( $scope_type == 0 ?	'snsapi_userinfo' : 'snsapi_base' );
		$url .= "&state=STATE#wechat_redirect";
		return $url;
	}

	// 根据Code获取access_token
	public function getAccessTokenByCode($code){
		 if(!isset($code))
		 {
			 return false;
		 }
		 $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid="
		 .$this->appid."&secret=".$this->appsecret."&code=".$code."&grant_type=authorization_code";
		 $data = $this->getDataByUrl($url);
		 switch ($data['errcode']) {
			 case '40029':
				 return false;
				 break;
		 }
		 $this->access_token = $data['access_token'];
		 $this->openid = $data['openid'];
		 return $data;
	}

	// 根据access_token和openid获取用户基本信息
	public function getUserInfoByOpenID()
	{
		if(!$this->access_token || !$this->openid)
		{
			return false;
		}
		$url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$this->access_token
		."&openid=".$this->openid."&lang=zh_CN";
		$data = $this->getDataByUrl($url);
		return $data;
	}

	/**
	* 刷新access_token
	**/
	public function refreshToken($refresh_token)
	{
		if(empty($refresh_token))
		{
			return false;
		}
		$url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token?appid='
		.$this->appid. '&grant_type=refresh_token&refresh_token=' . $refresh_token;
		$data = $this->getDataByUrl($url);
		switch ($data['errcode']) {
			case '40029':
				$this->error('验证失败');
				return false;
				break;
		}
		return $data;
	}

	// GET访问URL
	private function getDataByUrl($url)
	{
		if(!isset($url))
		{
			return false;
		}
		return json_decode(file_get_contents($url) , true);
	}
}
