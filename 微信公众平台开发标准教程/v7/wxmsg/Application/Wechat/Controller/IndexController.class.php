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
			// 自动回复文本消息
			if($this->data['MsgType'] == 'text')
			{
				// 根据用户输入的文本进行判断与回复
				if($this->data['Content'] == 'baidu')
				{
					$wx->replyText('地址：http://www.baidu.com');
				}
				elseif($this->data['Content'] == 'sina')
				{
					$wx->replyText('地址：http://www.sina.com.cn');
				}
				else
				{
					if($this->data['Content'] == 'yongforyou')
					{
						$wx->replyMusic(
							'yongforyou音乐',											   //标题
							'yongforyou描述',											   //描述
							'http://180.76.163.174/wxmsg/Muisc/YoungForYouMP3.mp3',		//普通音质音乐文件
							'http://180.76.163.174/wxmsg/Muisc/YoungForYouMP3.mp3',		//高品质音乐文件
							'VJNC9Q2aF0c4BybGRpEFprHm6JEnBR2b_u0BXuVLcwnSBzGSSxWoX6aT02VDU1bT'
						);
					}
					elseif($this->data['MsgType'] == 'text' && $this->data['Content'] == 'allnews')
					{
						// 多个图文信息
						$wx->replyNews(
							array(
								'测试文章标题1',
								'测试文章内容1',
								'http://www.baidu.com',
								'http://180.76.163.174/wxmsg/Muisc/logo.jpeg'
							),
							array(
								'测试文章标题2',
								'测试文章内容2',
								'http://www.baidu.com',
								'http://180.76.163.174/wxmsg/Muisc/logo.jpeg'
							),
							array(
								'测试文章标题3',
								'测试文章内容3',
								'http://www.baidu.com',
								'http://180.76.163.174/wxmsg/Muisc/logo.jpeg'
							)
						);
					}
					elseif($this->data['MsgType'] == 'text' && $this->data['Content'] == 'news')
					{
						// 单个图文信息
						$wx->replyNewsOnce(
								'测试文章标题',
								'测试文章内容',
								'http://www.baidu.com',
								'http://180.76.163.174/wxmsg/Muisc/logo.jpeg'
							);
					}
					else
					{
						$wx->replyText('收到的文本内容：'.$this->data['Content']);
					}
				}
			}
			elseif($this->data['MsgType'] == 'image')
			{
				$wx->replyImage($this->data['MediaId']);
			}
			elseif($this->data['MsgType'] == 'voice')
			{
				$wx->replyVoice($this->data['MediaId']);
			}
			elseif($this->data['MsgType'] == 'video' || $this->data['MsgType'] == 'shortvideo')
			{
				$wx->replyVideo($this->data['MediaId'] ,'标题' , '内容');
			}
			else
			{
				// 未知类型的消息响应
				exit('success');
			}
			//
			// // 音乐信息

			// // 图文信息
			// if($this->data['MsgType'] == 'text' && $this->data['Content'] == 'news')
			// {
			// 	$wx->replyNewsOnce(
			// 		'测试文章标题',
			// 		'测试文章内容',
			// 		'http://www.baidu.com',
			// 		'http://180.76.163.174/wxmsg/Muisc/logo.jpeg'
			// 	);
			// }
			// if($this->data['MsgType'] == 'text' && $this->data['Content'] == 'allnews')
			// {
			// 	$wx->replyNews(
			// 		array(
			// 			'测试文章标题1',
			// 			'测试文章内容1',
			// 			'http://www.baidu.com',
			// 			'http://180.76.163.174/wxmsg/Muisc/logo.jpeg'
			// 		),
			// 		array(
			// 			'测试文章标题2',
			// 			'测试文章内容2',
			// 			'http://www.baidu.com',
			// 			'http://180.76.163.174/wxmsg/Muisc/logo.jpeg'
			// 		),
			// 		array(
			// 			'测试文章标题3',
			// 			'测试文章内容3',
			// 			'http://www.baidu.com',
			// 			'http://180.76.163.174/wxmsg/Muisc/logo.jpeg'
			// 		)
			// 	);
			// }
			// // 自动回复图片消息
			// if($this->data['MsgType'] == 'image')
			// {
			// 	$wx->replyImage($this->data['MediaId']);
			// }
			// // 自动回复视频/小视频消息
			// if($this->data['MsgType'] == 'video')
			// {
			// 	$wx->replyVideo( $this->data['ThumbMediaId'] , $this->data['MediaId']);
			// }
			// // 自动回复语音消息
			// if($this->data['MsgType'] == 'voice')
			// {
			// 	$wx->replyVoice( $this->data['MediaId']);
			// }

			exit('success');
		}
	}

	public function showWxData()
	{
		header('Content-type:text/html;charset=utf-8');
		echo "<pre>";
		print_r(json_decode(file_get_contents('./wechat_data.json')));
		die;
	}

	public static function data2xml($xml, $data, $item = 'item')
	{
		foreach ($data as $key => $value) {
			/* 指定默认的数字key */
			is_numeric($key) && $key = $item;

			/* 添加子元素 */
			if(is_array($value) || is_object($value)){
				$child = $xml->addChild($key);
				self::data2xml($child, $value, $item);
			} else {
				if(is_numeric($value)){
					$child = $xml->addChild($key, $value);
				} else {
					$child = $xml->addChild($key);
					$node  = dom_import_simplexml($child);
					$cdata = $node->ownerDocument->createCDATASection($value);
					$node->appendChild($cdata);
				}
			}
		}
	}
}
