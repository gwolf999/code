<?php

class WxBase
{

	/**
	 * 消息类型常量
	 */
	const MSG_TYPE_TEXT		= 'text';
	const MSG_TYPE_IMAGE	  = 'image';
	const MSG_TYPE_VOICE	  = 'voice';
	const MSG_TYPE_VIDEO	  = 'video';
	const MSG_TYPE_SHORTVIDEO = 'shortvideo';
	const MSG_TYPE_LOCATION	= 'location';
	const MSG_TYPE_LINK		= 'link';
	const MSG_TYPE_MUSIC	  = 'music';
	const MSG_TYPE_NEWS		= 'news';
	const MSG_TYPE_EVENT	  = 'event';

	/**
	 * 事件类型常量
	 */
	const MSG_EVENT_SUBSCRIBE	= 'subscribe';
	const MSG_EVENT_UNSUBSCRIBE = 'unsubscribe';
	const MSG_EVENT_SCAN		= 'SCAN';
	const MSG_EVENT_LOCATION	= 'LOCATION';
	const MSG_EVENT_CLICK		= 'CLICK';
	const MSG_EVENT_VIEW		= 'VIEW';

	private $data = array();
	private $app_id  = '';
	private $app_secret = '';
	private $token = '';


	public function __construct($app_id , $app_secret , $token)
	{
		if(!$app_id || !$app_secret || !$token)
		{
			exit('Param Error!');
		}

		$this->app_id = $app_id;
		$this->app_secret = $app_secret;
		$this->token = $token;

		$this->init();
	}

	private function init()
	{
		if(!$this->token_auth($this->token))
		{
			exit('error');
		}

		if($_GET['echostr']){
			exit($_GET['echostr']);
		}
		else
		{
			$xml  = file_get_contents("php://input");
			$data = self::xml2data($xml);
			// 加密解密相关
			$this->data = $data;

			file_put_contents('./wechat_data.json', json_encode($data));

			// 判断用户是否触发了关注事件
			if($this->data['Event'] == self::MSG_EVENT_SUBSCRIBE)
			{
				$this->replyText('感谢关注测试公众号！');
			}
			else
			{
				$this->replyText('发送的数据为：'. $this->data['Content']);
			}
		}
	}

	// 自动回复
	/**
	  * * 响应微信发送的信息（自动回复）
	  * @param  array  $content 回复信息，文本信息为string类型
	  * @param  string $type	消息类型
	  */
	 public function response($content, $type = self::MSG_TYPE_TEXT){
		 /* 基础数据 */
		 $data = array(
			 'ToUserName'	=> $this->data['FromUserName'],
			 'FromUserName' => $this->data['ToUserName'],
			 'CreateTime'	=> time(),
			 'MsgType'	  => $type,
		 );

		 /* 按类型添加额外数据 */
		 $content = call_user_func(array(self, $type), $content);
		 if($type == self::MSG_TYPE_TEXT || $type == self::MSG_TYPE_NEWS){
			 $data = array_merge($data, $content);
		 } else {
			 $data[ucfirst($type)] = $content;
		 }

		//  //安全模式，加密消息内容
		//  if(self::$msgSafeMode){
		//	  $data = self::generate($data);
		//  }

		 /* 转换数据为XML */
		 $xml = new \SimpleXMLElement('<xml></xml>');
		 self::data2xml($xml, $data);
		 exit($xml->asXML());
	 }

	 /**
	  * 回复文本消息
	  * @param  string $text	回复的文字
	  */
	 public function replyText($text){
		 return $this->response($text, self::MSG_TYPE_TEXT);
	 }

	 /**
	  * 回复图片消息
	  * @param  string $media_id 图片ID
	  */
	 public function replyImage($media_id){
		 return $this->response($media_id, self::MSG_TYPE_IMAGE);
	 }

	 /**
	  * 回复语音消息
	  * @param  string $media_id 音频ID
	  */
	 public function replyVoice($media_id){
		 return $this->response($media_id, self::MSG_TYPE_VOICE);
	 }

	 /**
	  * 回复视频消息
	  * @param  string $media_id	视频ID
	  * @param  string $title		视频标题
	  * @param  string $discription 视频描述
	  */
	 public function replyVideo($media_id, $title, $discription){
		 return $this->response(func_get_args(), self::MSG_TYPE_VIDEO);
	 }

	 /**
	  * 回复音乐消息
	  * @param  string $title		  音乐标题
	  * @param  string $discription	音乐描述
	  * @param  string $musicurl		音乐链接
	  * @param  string $hqmusicurl	 高品质音乐链接
	  * @param  string $thumb_media_id 缩略图ID
	  */
	 public function replyMusic($title, $discription, $musicurl, $hqmusicurl, $thumb_media_id){
		 return $this->response(func_get_args(), self::MSG_TYPE_MUSIC);
	 }

	 /**
	  * 回复图文消息，一个参数代表一条信息
	  * @param  array  $news	图文内容 [标题，描述，URL，缩略图]
	  * @param  array  $news1  图文内容 [标题，描述，URL，缩略图]
	  * @param  array  $news2  图文内容 [标题，描述，URL，缩略图]
	  *				...	 ...
	  * @param  array  $news9  图文内容 [标题，描述，URL，缩略图]
	  */
	 public function replyNews($news, $news1, $news2, $news3){
		 return $this->response(func_get_args(), self::MSG_TYPE_NEWS);
	 }

	 /**
	  * 回复一条图文消息
	  * @param  string $title		文章标题
	  * @param  string $discription 文章简介
	  * @param  string $url		 文章连接
	  * @param  string $picurl	  文章缩略图
	  */
	 public function replyNewsOnce($title, $discription, $url, $picurl){
		 return $this->response(array(func_get_args()), self::MSG_TYPE_NEWS);
	 }

	/**
	 * 构造文本信息
	 * @param  string $content 要回复的文本
	 */
	private static function text($content){
		$data['Content'] = $content;
		return $data;
	}

	/**
	 * 构造图片信息
	 * @param  integer $media 图片ID
	 */
	private static function image($media){
		$data['MediaId'] = $media;
		return $data;
	}

	/**
	 * 构造音频信息
	 * @param  integer $media 语音ID
	 */
	private static function voice($media){
		$data['MediaId'] = $media;
		return $data;
	}

	/**
	 * 构造视频信息
	 * @param  array $video 要回复的视频 [视频ID，标题，说明]
	 */
	private static function video($video){
		$data = array();
		list(
			$data['MediaId'],
			$data['Title'],
			$data['Description'],
		) = $video;

		return $data;
	}

	/**
	 * 构造音乐信息
	 * @param  array $music 要回复的音乐[标题，说明，链接，高品质链接，缩略图ID]
	 */
	private static function music($music){
		$data = array();
		list(
			$data['Title'],
			$data['Description'],
			$data['MusicUrl'],
			$data['HQMusicUrl'],
			$data['ThumbMediaId'],
		) = $music;

		return $data;
	}

	/**
	 * 构造图文信息
	 * @param  array $news 要回复的图文内容
	 * [
	 *	  0 => 第一条图文信息[标题，说明，图片链接，全文连接]，
	 *	  1 => 第二条图文信息[标题，说明，图片链接，全文连接]，
	 *	  2 => 第三条图文信息[标题，说明，图片链接，全文连接]，
	 * ]
	 */
	private static function news($news){
		$articles = array();
		foreach ($news as $key => $value) {
			list(
				$articles[$key]['Title'],
				$articles[$key]['Description'],
				$articles[$key]['Url'],
				$articles[$key]['PicUrl']
			) = $value;

			if($key >= 9) break; //最多只允许10条图文信息
		}
		$data['ArticleCount'] = count($articles);
		$data['Articles']	 = $articles;

		return $data;
	}


	// 验证签名是否正确
	private static function token_auth($token)
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

	private static function xml2data($xml)
	{
		$xml = new \SimpleXMLElement($xml);

		if(!$xml){
			throw new \Exception('非法XXML');
		}

		$data = array();
		foreach ($xml as $key => $value) {
			$data[$key] = strval($value);
		}

		return $data;
	}

    private static function data2xml($xml, $data, $item = 'item')
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
