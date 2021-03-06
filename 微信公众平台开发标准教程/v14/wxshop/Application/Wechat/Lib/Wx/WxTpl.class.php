<?php
class WxTpl
{
	private $access_token = null;

	// 推送接口
	private $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=";

	private $kf_url = "https://api.weixin.qq.com/customservice/msgrecord/getmsglist?access_token=";

	public function __construct($appid , $appsecret)
	{
		if(!$appid || !$appsecret)
		{
			exit('Param Error!');
		}

		$this->getAccessTokenByAppInfo($appid ,$appsecret );
	}

	private function getAccessTokenByAppInfo($appid ,$appsecret)
	{
		$access_token_info = json_decode(file_get_contents('./tpl_access_token.json'),true);
		if($access_token_info['access_token'] && (time() - $access_token_info['create_time']) < 1800)
		{
			$this->access_token = $access_token_info['access_token'] ;
		}
		else
		{
			$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appid.'&secret='.$appsecret;
			$return = file_get_contents($url);
			$access_token_arr = json_decode($return , true);

			$data['access_token'] = $access_token_arr['access_token'];
			$data['create_time'] = time();
			file_put_contents('./tpl_access_token.json' , json_encode($data));

			$this->access_token = $access_token_arr['access_token'];
		}

	}

	// 创建菜单
	public function pushTpl($open_id , $tpl_id , $url = '', $data = array())
	{
		if(!$open_id || !$tpl_id)
		{
			return false;
		}

		$push_data['touser'] = $open_id;
		$push_data['template_id'] = $tpl_id;
		$push_data['url'] = $url;
		$push_data['topcolor'] = "#FF0000";
		$push_data['data'] = $data;

		return $this->sendData(json_encode($push_data));
	}

	public function getHistoryList($data)
	{
		return $this->getKfHistoryData($data);
	}

	private function sendData($data)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->url.$this->access_token);
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

	private function getKfHistoryData($data)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->kf_url.$this->access_token);
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

}
