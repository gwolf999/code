<?php
// access_token获取类
class WxAccessToken
{
	private $access_token ;
	private $appid;
	private $appsecret;

	// 构造方法
	public function __construct($appid , $appsecret)
	{
		if(!$appid || !$appsecret)
		{
			exit('param error!');
		}
		$this->appid = $appid;
		$this->appsecret = $appsecret;
	}

	// 获取access_token返回值;
	private function getAccessTokenData()
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appid.'&secret='.$this->appsecret;
		return json_decode(file_get_contents($url) , true);
	}

	// 返回最新的access_token
	public function getAccessToken()
	{
		$a_t_data = $this->getAccessTokenData();
		return $a_t_data['access_token'];
	}

	// 数据库方式：获取最新的access_token
	public function getAccessTokenByDb()
	{
		$conn = Db::getMySQLconnect(MYSQL_HOST , MYSQL_USER , MYSQL_PWD);
		$conn->selectDb('wxtoken');

		// 获取最后一条数据
		$sql = "select * from db_access_token order by id desc";
		$re = $conn->query($sql);
		$res =  $conn->fetch_array($re);
		if(!$res)
		{
			// 获取最新的access_token
			$a_t_token = $this->getAccessTokenData();
			// 写入数据库
			$sql = "insert into db_access_token values ( null , '".$a_t_token['access_token']."' , ".time()." , '".date('Y-m-d')."' , 1)";
			$conn->query($sql);
			// 返回结果集
			return $a_t_token['access_token'];
		}
		else
		{
			// 判断token是否过期
			if((time() - $res['create_time']) > 3600)
			{
				// 获取最新的access_token
				$a_t_token = $this->getAccessTokenData();
				// 写入数据库
				$sql = "insert into db_access_token values ( null , '".$a_t_token['access_token']."' , ".time()." , '".date('Y-m-d')."' , 1)";
				$conn->query($sql);
				// 返回结果集
				return $a_t_token['access_token'];
			}
			else
			{
				//　直接返回数据库中的结果
				return $res['access_token'];
			}
		}
	}

	// 本地文件方式：获取最新的access_token
	public function getAccessTokenByFile()
	{
		// 读取本地的json文件
		$file_data = file_get_contents('./access_token.json');

		if(!$file_data)
		{
			// 获取最新的access_token
			$a_t_token = $this->getAccessTokenData();
			$a_t_token['create_time'] = time();
			file_put_contents('./access_token.json' ,json_encode($a_t_token));
			// 返回结果集
			return $a_t_token['access_token'];
		}
		else
		{
			// 转换为数组格式
			$file_data_arr = json_decode($file_data , true);
			// 判断token是否过期
			if((time() - $file_data_arr['create_time']) > 3600)
			{
				// 获取最新的access_token
				$a_t_token = $this->getAccessTokenData();
				$a_t_token['create_time'] = time();
				file_put_contents('./access_token.json' ,json_encode($a_t_token));
				// 返回结果集
				return $a_t_token['access_token'];
			}
			else
			{
				//　直接返回数据库中的结果
				return $file_data_arr['access_token'];
			}
		}
	}

	// 发送POST请求数据
	public function post_data($url , $data = null)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
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
?>
