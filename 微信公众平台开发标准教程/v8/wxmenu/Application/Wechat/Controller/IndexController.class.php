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

			// CLICK事件处理
			if($this->data['MsgType'] == 'event' && $this->data['Event'] == 'CLICK')
			{
				if($this->data['EventKey'] == 'news')
				{
					// 单个图文信息
					$wx->replyNewsOnce(
						'测试文章标题',
						'测试文章内容',
						'http://www.baidu.com',
						'http://180.76.163.174/wxmsg/Muisc/logo.jpeg'
					);
				}
				if($this->data['EventKey'] == 'music')
				{
					$wx->replyMusic(
						'yongforyou音乐',											   //标题
						'yongforyou描述',											   //描述
						'http://180.76.163.174/wxmsg/Muisc/YoungForYouMP3.mp3',		//普通音质音乐文件
						'http://180.76.163.174/wxmsg/Muisc/YoungForYouMP3.mp3',		//高品质音乐文件
						'VJNC9Q2aF0c4BybGRpEFprHm6JEnBR2b_u0BXuVLcwnSBzGSSxWoX6aT02VDU1bT'
					);
				}
			}

			//View事件处理
			if($this->data['MsgType'] == 'event' && $this->data['Event'] == 'VIEW')
			{
				// 读取本地文件存储的访问量统计数据
				$view_info = file_get_contents('./website_pv_count.json');
				$new_arr['url'] = $this->data['EventKey'];
				$view_arr = json_decode($view_info , true);
				// 若存在则pv访问量增加1，若不存在则写入默认值
				if($view_info !== false)
				{
					$new_arr['pv'] = $view_arr['pv'] + 1;
				}
				else
				{
					$new_arr['pv'] = 1;
				}
				// 把访问量统计写入到本地
				file_put_contents('./website_pv_count.json', json_encode($new_arr));
			}

			// 扫码事件处理
			if($this->data['MsgType'] == 'event' && $this->data['Event'] == 'scancode_waitmsg')
			{
				if($this->data['EventKey'] == 'show_success' )
				{
					$wx->replyText('扫码成功！');
				}
			}

			exit('success');
		}
	}

	// 查看网址访问量
	public function showWebSitePv()
	{
		header('Content-type:text/html;charset=utf-8');
		echo "<pre>";
		print_r(json_decode(file_get_contents('./website_pv_count.json')));
		die;
	}

	//获取access_token
	public function showAccessToken()
	{
		import('@.Lib.Wx.WxMenu');
		$wx = new \WxMenu(APP_ID , APP_SECRET);
		dump($wx->getAccessToken());
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
				"name":"百度",
				"url":"http://www.baidu.com/"
			},
			{
				"type":"view",
				"name":"新浪",
				"url":"http://www.sina.com.cn/"
			},
			{
				"type":"view",
				"name":"网易",
				"url":"http://www.163.com/"
			},
		]}';
		import('@.Lib.Wx.WxMenu');
		$wx = new \WxMenu(APP_ID , APP_SECRET);
		dump($wx->createMenu($data));
	}

	// 创建/查看/删除 菜单
	public function myMenu()
	{
		// $data = '{
		// "button":[
		// 	{
		// 		"type":"click",
		// 		"name":"新闻",
		// 		"key":"news"
		// 	},
		// 	{
		// 		"type":"click",
		// 		"name":"音乐",
		// 		"key":"music"
		// 	}
		// ]}';
		$data = '{
		"button":[
			{
				"type":"view",
				"name":"查看新闻",
				"url":"http://www.baidu.com"
			}
		]}';
		import('@.Lib.Wx.WxMenu');
		$wx = new \WxMenu(APP_ID , APP_SECRET);
		dump($wx->createMenu($data));
	}

	// 获取当前公众号的菜单规则
	public function getWxMenu()
	{
		import('@.Lib.Wx.WxMenu');
		$wx = new \WxMenu(APP_ID , APP_SECRET);
		dump($wx->getMenu($data));
	}

	// 删除当前公众号的菜单规则
	public function delWxMenu()
	{
		import('@.Lib.Wx.WxMenu');
		$wx = new \WxMenu(APP_ID , APP_SECRET);
		dump($wx->deleteMenu($data));
	}
}
