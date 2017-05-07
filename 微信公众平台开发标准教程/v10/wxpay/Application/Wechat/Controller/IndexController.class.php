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
		import('@.Lib.Wx.WxJsSdk');
		$jssdk = new \WxJsSdk(APP_ID, APP_SECRET);			// 实例化对象
		$signPackage = $jssdk->GetSignPackage();			// 获取wx验证参数
		$this->assign('signPackage' , $signPackage);		// 变量置换
		$this->display();
	}

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
				"url":"http://wechat.hello-orange.com/wxpay/index.php/Wechat/Index/index"
			}
		]}';
		import('@.Lib.Wx.WxMenu');
		$wx = new \WxMenu(APP_ID , APP_SECRET);
		dump($wx->createMenu($data));
	}
}
