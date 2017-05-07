<?php
namespace Wechat\Controller;
/**
 * 首页控制器
 */
class IndexController extends WechatController
{

	// 验证Token值，
	private $token = "weixin";

	// 微信服务器发送的消息
	private $data = null;

	// 验证签名是否正确
	public function token_auth($token)
	{
		/* 获取数据 */
		$data = array($_GET['timestamp'], $_GET['nonce'], $token);
		$sign = $_GET['signature'];

		/* 对数据进行字典排序 */
		sort($data, SORT_STRING);

		/* 生成签名 */
		$signature = sha1(implode($data));
		return $signature === $sign;
	}

	// 配置服务器Token，微信请求数据入口
	public function configToken()
	{
		// 服务器签名验证
		if(!$this->token_auth($this->token))
		{
			exit('error');
		}

		// 判断是的Token验证还是用户消息转发
		if($_GET['echostr']){
			exit($_GET['echostr']);
		}
		else
		{
			// 数据包接收
			$xml  = file_get_contents("php://input");
			// 引入PHP消息处理类库并实例化
			import("@.Lib.Wx.WxBase");
			$wx = new \WxBase();
			// 转换XML格式为数组格式
			$data = $wx::xml2data($xml);
			$wx->setData($data);
			// 保存数据到data属性
			$this->data = $data;
			// 把接收到的数据写入本地文件
			file_put_contents('./wechat_data.json', json_encode($data));

			exit('success');
		}
	}

	//首页
	public function index()
	{
		// 检测用户是否登录注册
		$this->checkUserWxLogin();
		$this->display();
	}

	// 检查是否已经授权并引导授权方法
	private function checkUserWxLogin()
	{
		if(!session('openid')) 	 	 	 	 	 	  // 判断是否已经成功授权
		{
			import("@.Lib.Wx.WxAuth"); 	 	 	 	  // 引入WxAuth类库
			$wx = new \WxAuth(APP_ID ,APP_SECRET); 	  // 实例化对象
			$wx->setReturnUrl(U('Auth/getUserInfo'));   // 设置回调地址
			redirect($wx->createWxAuthUrl(0)); 	 	  // 构建静默授权地址并跳转
		}
	}

	// 查找周围的充电桩
	public function search()
	{
		import('@.Lib.Wx.WxJsSdk');
		$jssdk = new \WxJsSdk(APP_ID, APP_SECRET);			// 实例化对象
		$signPackage = $jssdk->GetSignPackage();			// 获取wx验证参数
		$this->assign('signPackage' , $signPackage);		// 变量置换
		$this->display();
	}

	// 注册充电桩地址
	public function reg()
	{
		$this->display();
	}

	// 获取附近的充电桩地址列表
	public function getAddressList()
	{
		if(IS_POST)
		{
			$lat = I('lat');
			$lng = I('lng');
			if(!$lat || !$lng )
			{
				$this->ajaxReturn(array('status' => 0));
			}

			import("@.Lib.Geohash.Geohash");
			$geohash = new \Geohash();
			$hash = $geohash->encode($lat, $lng);
			$prefix = substr($hash, 0, 5);
			//取出相邻八个区域
			$neighbors = $geohash->neighbors($prefix);
			array_push($neighbors, $prefix);

			$str = "";
			foreach ($neighbors as $key => $value)
			{
				$map['geohash'] = array('like' , $value."%");
				$map['status'] = array('gt' , 0);
				$find_list = M('address')->where($map)->select();
				foreach ($find_list as $k => $v) {
					$str .=
					"<div class='address_list'>
					<p class='address_content'>所在位置：".$v['address']."</p>
					<p class='address_distance'>距离".$this->getDistance($lat , $lng , $v['lat'] , $v['lng'])."
					</p></div>";
				}
			}

			$this->ajaxReturn(array('msg'=>1 , 'data'=>$str));

		}
	}

	// 获取两个经纬度之间的距离，单位米
	public function getDistance($lat1, $lng1, $lat2, $lng2)
 	{
	 	 $earthRadius = 6367000; //approximate radius of earth in meters

	 	 $lat1 = ($lat1 * pi() ) / 180;
	 	 $lng1 = ($lng1 * pi() ) / 180;

	 	 $lat2 = ($lat2 * pi() ) / 180;
	 	 $lng2 = ($lng2 * pi() ) / 180;

	 	 $calcLongitude = $lng2 - $lng1;
	 	 $calcLatitude = $lat2 - $lat1;
	 	 $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
	 	 $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
	 	 $calculatedDistance = $earthRadius * $stepTwo;

		 $num = round($calculatedDistance);
		 if($num < 1000)
		 {
			 $num = $num . '米';
		 }
		 else
		 {
			 $num = ($num / 1000).'公里';
		 }
		 return $num;
	 }

	// 保存充电桩地址
	public function saveAddress()
	{
		if(IS_POST)
		{
			$lat = I('lat');
			$lng = I('lng');
			$address = I('address');
			if(!$lat || !$lng || !$address)
			{
				$this->ajaxReturn(array('status' => 0));
			}

			import("@.Lib.Geohash.Geohash");
			$geohash = new \Geohash();
			$hash = $geohash->encode($lat, $lng);
			if($hash)
			{
				$data['lat'] = $lat;
				$data['openid'] = session('openid')?session('openid'):0;
				$data['lng'] = $lng;
				$data['geohash'] = $hash;
				$data['address'] = $address;
				$data['status'] = 1;
				$data['create_time'] = time();
				M('address')->add($data);
			}

			$this->ajaxReturn(array('status'=>1,'data'=>$str));
		}
	}



	// 查看消息数据
	public function showWxData()
	{
		header('Content-type:text/html;charset=utf-8');
		echo "<pre>";
		print_r(json_decode(file_get_contents('./wechat_data.json')));
		die;
	}

	// 创建/查看/删除 菜单
	public function menu()
	{
		$data = '{
		"button":[
			{
				"type":"view",
				"name":"首页",
				"url":"http://wechat.hello-orange.com/wxlbs/index.php/Wechat/Index/index"
			}
		]}';
		import('@.Lib.Wx.WxMenu');
		$wx = new \WxMenu(APP_ID , APP_SECRET);
		dump($wx->createMenu($data));
	}
}
