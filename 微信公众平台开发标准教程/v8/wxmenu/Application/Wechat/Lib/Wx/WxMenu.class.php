<?php
class WxMenu
{

	private $access_token = null;

	public function __construct($appid , $appsecret)
	{
		if(!$appid || !$appsecret)
		{
			exit('Param Error!');
		}

		$this->access_token = self::getAccessTokenByAppInfo($appid , $appsecret);
	}

	// 获取access_token并缓存
	private static function getAccessTokenByAppInfo($appid , $appsecret)
	{
		if($appid && $appsecret)
		{
			// 查找本地是否存在缓存的access_token
			$cache_info = file_get_contents('./access_token.json');
			$cache_info_arr = json_decode($cache_info , true);
			// 查看本地缓存的access_token是否过期
			if((time() - $cache_info_arr['create_time']) < 3600)
			{
				return $cache_info_arr['access_token'];
			}
			else
			{
				// 通过微信公众平台接口获取access_token
				$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret;
				$return = file_get_contents($url);
				$access_token_arr = json_decode($return , true);
				$access_token_arr['create_time'] = time();
				// 把access_token写入到文件缓存
				file_put_contents('./access_token.json',json_encode($access_token_arr));
				return $access_token_arr['access_token'];
			}
		}
		return false;
	}

	public function getAccessToken()
	{
		return $this->access_token;
	}

	// 创建菜单
	public function createMenu($data)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$this->access_token);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$return = curl_exec($ch);
		if (curl_errno($ch)) {
			return curl_error($ch);
		}

		curl_close($ch);
		return $return;
	}

	//获取菜单
	public function getMenu(){
		$return = file_get_contents("https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".$this->access_token);
		return json_decode($return , true);
	}

	//删除菜单
	function deleteMenu(){
		$return = file_get_contents("https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".$this->access_token);
		return json_decode($return , true);
	}

}
