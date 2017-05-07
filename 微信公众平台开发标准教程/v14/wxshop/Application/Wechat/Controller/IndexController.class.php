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
		// $this->checkUserWxLogin();
		// 检索置顶商品
		$map['status'] = array('gt' , 0);
		$map['is_top'] = 1;
		$top_goods_info = M('goods')->where($map)->find();

		// 检索所有商品分类
		unset($map);
		$map['status'] = array('gt' , 0);
		$goods_cates_list = M('goods_cates')->where($map)->select();

		// 基于分页的商品查询
		unset($map);
		$map['status'] = array('gt' , 0);
		$row = 4;
		$page_count = M('goods')->where($map)->count();
		$total_page =ceil( $page_count / $row );
		$goods_list = M('goods')->where($map)->limit(' 0 , '.$row)->order('create_time desc')->select();

		// 商品数据组装
		$new_goods_list = array();
		for($i = 0 ; $i <count($goods_list) ; $i += 2)
		{
			$goods_info[0] = $goods_list[$i]?$goods_list[$i]:array();
			$goods_info[1] = $goods_list[$i+1]?$goods_list[$i+1]:array();
			$new_goods_list[] = $goods_info;
			unset($goods_info);
		}

		// 模板变量置换
		$this->assign('goods_list' , $new_goods_list);
		$this->assign('goods_cates_list' , $goods_cates_list);
		$this->assign('top_goods_info' , $top_goods_info);
		$this->assign('total_page' , $total_page);
		$this->meta_title = "首页";
		$this->display();
	}

	//分页获取数据
	public  function getPage()
	{
		if(IS_POST)
		{
			$now_page = I('page');
			$map['status'] = array('gt' , 0);
			$row = 4;
			$offset = ($now_page - 1) * $row;
			$goods_list = M('goods')->where($map)->limit($offset.', '.$row)->order('create_time desc')->select();

			$new_goods_list = array();
			for($i = 0 ; $i <count($goods_list) ; $i += 2)
			{
				$goods_info[0] = $goods_list[$i]?$goods_list[$i]:array();
				$goods_info[1] = $goods_list[$i+1]?$goods_list[$i+1]:array();
				$new_goods_list[] = $goods_info;
				unset($goods_info);
			}

			$this->assign('goods_list' , $new_goods_list);
			$this->display('Index/get_page');
		}
		else
		{
			exit('');
		}
	}

	// 购车车页面
	public function shopcar()
	{
		// $this->checkUserWxLogin();
		$map['uid'] = $this->uid ? $this->uid : 1;
		$list = M('goods_shopcar')->where($map)->select();
		if($list !== null)
		{
			$total_price = 0;
			$total_num = 0;
			foreach ($list as $key => $value)
			{
				unset($map);
				$map['id'] = $value['goods_id'];
				$goods_info = M('goods')->field('name,price,img_id')->where($map)->find();
				$list[$key]['goods_info'] = $goods_info;
				$list[$key]['all_price'] = $goods_info['price'] * $value['num'];
				$total_price = $total_price + $list[$key]['all_price'];
				$total_num = $total_num + $value['num'];
				unset($goods_info);
			}

			$this->assign('total_price' , $total_price);
			$this->assign('total_num' , $total_num);
			$this->assign('list' , $list);
		}

		$this->meta_title = "购物车";
		$this->display();
	}

	// 更新数据库购物车信息
	public function updateShoppcar()
	{
		if(IS_POST)
		{
			$map['uid'] = I('uid');
			$map['goods_id'] = I('goods_id');
			$data['num'] = I('num');
			M('goods_shopcar')->where($map)->save($data);
		}
	}

	// 更新数据库购物车信息
	public function delCarGoodsInfo()
	{
		if(IS_POST)
		{
			$map['uid'] = I('uid');
			$map['goods_id'] = I('goods_id');
			M('goods_shopcar')->where($map)->delete();
		}
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

	public function showList()
	{
		import('@.Lib.Wx.WxTpl');
		$wx = new \WxTpl(APP_ID , APP_SECRET);

		$start_time = strtotime(date('2016-09-27'));
		$end_time = strtotime(date('2016-09-28'));
		$send_data = array(
			'starttime'=>$start_time,
			'endtime' => $end_time,
			'msgid' => 1,
			'number' => 1000,
		);

		dump($wx->getHistoryList(json_encode($send_data)));
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
				"url":"http://wechat.hello-orange.com/wxshop/index.php/Wechat/Index/index"
			},
			{
				"type":"view",
				"name":"订单",
				"url":"http://wechat.hello-orange.com/wxshop/index.php/Wechat/Order/order_list"
			},
			{
				"type":"view",
				"name":"用户",
				"url":"http://wechat.hello-orange.com/wxshop/index.php/Wechat/User/index"
			}
		]}';
		import('@.Lib.Wx.WxMenu');
		$wx = new \WxMenu(APP_ID , APP_SECRET);
		dump($wx->createMenu($data));
	}
}
