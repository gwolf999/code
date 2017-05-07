<?php
class WxBase
{
	// 消息类型常量（接收/回复）
	const MSG_TYPE_TEXT		= 'text';				// 文本
	const MSG_TYPE_IMAGE	  = 'image';			// 图片
	const MSG_TYPE_VOICE	  = 'voice';			// 声音
	const MSG_TYPE_VIDEO	  = 'video';			// 视频
	const MSG_TYPE_MUSIC	  = 'music';			// 音乐
	const MSG_TYPE_NEWS		= 'news';				// 图文
	// 消息类型常量（只接收）
	const MSG_TYPE_SHORTVIDEO = 'shortvideo';		// 短视频（只接收）
	const MSG_TYPE_LOCATION	= 'location';			// 地理位置（只接收）
	const MSG_TYPE_LINK		= 'link';				// 链接 （只接收）
	// 服务器接收到的XML消息
	private $data = array();
	// 构造方法
	public function __construct()
	{
	}
	// 设置服务器接收到的XML消息
	public function setData($data)
	{
		$this->data = $data;
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

	public static function xml2data($xml)
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
