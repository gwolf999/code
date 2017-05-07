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

	public function pushTpl()
	{
		import('@.Lib.Wx.WxTpl');
		$wx = new \WxTpl(APP_ID , APP_SECRET);

		$openid = 'o62gFwX4Up4vIhTNhn0pvJ2zfTrY';
		$tpl_id = 'WqusZ8w7Q3RzkbLoy1oddEY0HmNg3lMsFueV-GD6Ync';
		$url = 'http://www.baidu.com';
		$data = array(
			'first' => array('value'=>'申请的游戏退款已经生效'),
			'reason'=>array('value'=>'wang先生','color'=>'#173177'),
			'refund'=>array('value'=>'88.8元','color'=>'#173177'),
			'remark'=> array('value'=>'如没收到退款请联系客服：8888-88-88！')
		);

		dump(json_decode($wx->pushTpl($openid , $tpl_id , $url , $data),true));
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
				"url":"http://wechat.hello-orange.com/wxtools/index.php/Wechat/Index/index"
			}
		]}';
		import('@.Lib.Wx.WxMenu');
		$wx = new \WxMenu(APP_ID , APP_SECRET);
		dump($wx->createMenu($data));
	}
}
