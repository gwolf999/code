<?php
namespace Wechat\Controller;
/**
 * 商品控制器
 */
class OrderController extends WechatController
{
	// 初始化方法
	protected function _initialize(){
		parent::_initialize();
		$this->importWxLibs();	  //引入微信支付类文件
	}

	private function importWxLibs()
	{
		// JS支付处理类
		import('@.Lib.WxPay.WxPayJsApi');
		// 通知处理类
		import('@.Lib.WxPay.WxPayNotify');
		import('@.Lib.WxPay.WxApi');
	}

	// 商品列表首页
	public function index()
	{
		//构建订单并初始化支付
		$this->checkUserWxLogin();						  // 检测用户是否授权登录并获取用户OpenId
		//下订单
		$ids = I('ids');
		if(!$ids)
		{
			$this->error('参数错误！');
		}

		$ids_arr = explode(',' , $ids);
		if(!empty($ids_arr))
		{
			// 获取实际支付价格
			$goods_info = $this->getPayGoodsInfo($ids_arr);
			if(!$goods_info['pay_price'])
			{
				$this->error('请勿重复下单！');
			}
			$this->assign('goods_info' , $goods_info);

			$order_number = date('YmdHis').mt_rand(1000,999);
			$data['order_number'] = $order_number;
			$data['uid'] = $this->uid?$this->uid:1;
			$data['pay_price'] = $goods_info['pay_price'];
			$data['status'] = 1;
			$data['create_time'] = time();

			$info = M('member_address')->where('uid='.$data['uid'])->find();
			$this->assign('info' , $info);

			$order_id =M('order')->add($data);
			if($order_id !== false)
			{
				foreach ($goods_info['car_infos'] as $key => $value) {
					$order_data['goods_id'] = $value['goods_id'];
					$order_data['order_id'] = $order_id;
					$order_data['goods_num'] = $value['num'];
					$order_data['goods_price'] = $value['goods_price'];
					$order_data['status'] = 1;
					$order_data['create_time'] = time();
					M('order_goods')->add($order_data);
					unset($order_data);
				}
			}
			unset($map);
			$ids = rtrim($ids , ',');
			$map['id'] = array('in' ,$ids );
			M('goods_shopcar')->where($map)->delete();
		}

		// 构建支付信息
		$tools = new \WxPayJsApi();						 // JS API所需参数处理类
		$openid = session('openid');						// 获取openid
		$input = new \WxPayUnifiedOrder();				  // 实例化订单创建对象
		$input->SetBody("支付价格：".$goods_info['pay_price']);							// 设置商品信息
		$input->SetAttach("购物车商品订单");
		$input->SetOut_trade_no($order_number);//设置订单号
		$input->SetTotal_fee("1");						  // 设置实际支付金额
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag("购物车商品订单");
		// 设置通知回调接口
		$input->SetNotify_url("http://wechat.hello-orange.com/wxshop/index.php/Wechat/Order/wxnotify");
		$input->SetTrade_type("JSAPI");					 // 设置微信支付类型
		$input->SetOpenid($openid);						 // 设置用户openid
		$order = \WxPayApi::unifiedOrder($input);		   // 使用微信接口处理类创建订单信息
		$this->assign('order' , $order);
		$jsApiParameters = $tools->GetJsApiParameters($order);//根据订单信息生成JS API发起支付所需参数
		$this->assign('jsApiParameters' , $jsApiParameters);
		$this->meta_title = "订单支付";
		$this->display();
	}

	// 结算实际支付金额
	private function getPayGoodsInfo($arr)
	{
		if($arr)
		{
			$pay_price = 0;
			$car_infos = array();
			foreach ($arr as $key => $value)
			{
				if($value)
				{
					// 在购物车检索数据
					$map['id'] = $value;
					$car_info = M('goods_shopcar')->where($map)->find();
					unset($map);
					// 检索商品数据
					$map['id'] = $car_info['goods_id'];
					$goods_info = M('goods')->where($map)->find();
					$goods_price = $goods_info['price'];
					$goods_num = $car_info['num'];
					// 计算实际支付价格
					$pay_price = $pay_price + ($goods_price * $goods_num);
					$pay_num = $pay_num + $goods_num;

					$car_info['goods_price'] = $goods_price;
					$car_infos[] = $car_info;
					unset($car_info);
				}
			}
			//返回实际支付价格，商品数量和商品的id合集
			return array('pay_price'=>$pay_price , 'pay_num'=>$pay_num , 'car_infos'=>$car_infos);
		}

		return false;
	}

	// 通知处理
	public function wxnotify()
	{
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
		if($xml)
		{
			// 转换ml数据为数组
			$result = \WxPayResults::Init($xml);
			// 写入log日志
			$data['content'] = 'setup1:'.json_encode($result);
			M('logs')->add($data);
			// 订单数据验证
			$input = new \WxPayOrderQuery();
			$input->SetTransaction_id($result['transaction_id']);
			$result = \WxPayApi::orderQuery($input);
			// 写入log日志
			$data['content'] = 'setup2:'.json_encode($result);
			M('logs')->add($data);

			// 验证是否通过订单验证
			if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
			{
				$data['content'] = 'setup3:'.json_encode(array('info'=>'修改订单状态！'));
				M('logs')->add($data);

				// 订单处理
				$map['order_number'] = $result['out_trade_no'];
				$data['is_pay'] = 1;
				$data['pay_time'] = time();
				M('order')->where($map)->save($data);

				// 构建回复参数
				$return_notify['return_code'] = "SUCCESS";
				$return_notify['appid'] = $result['appid'];
				$return_notify['nonce_str'] = $result['nonce_str'];
				$return_notify['prepay_id'] = $result['prepay_id'];
				$return_notify['result_code'] = "SUCCESS";
				$return_notify['sign'] = $result['sign'];

				// 回复通知
				$data = new \WxPayResults();
				$data->FromArray($return_notify);
				exit($data->ToXml());
			}
		}
		else
		{
			exit('FAIL');
		}
	}

	// 用户个人订单列表
	public function order_list()
	{
		$map['uid'] = $this->uid ? $this->uid : 1;
		$map['status'] = array('gt' , 0);
		$list = M('order')->where($map)->order('create_time desc')->select();
		$this->assign('_list' , $list);
		$this->mate_title = "我的订单";
		$this->display();
	}

	// 删除用户订单信息
	public function delOrder()
	{
		if(IS_POST)
		{
			$map['id'] = I('order_id');
			$data['status'] = -1;
			$result = M('order')->where($map)->save($data);
			if($result !== false)
			{
				$this->ajaxReturn(array('status'=>1),'JSON');
			}
			$this->ajaxReturn(array('status'=>0),'JSON');
		}
	}
	// 确认用户收货信息
	public function confirmOrder()
	{
		if(IS_POST)
		{
			$map['id'] = I('order_id');
			$data['is_receipt'] = 1;
			$data['receipt'] = time();
			$result = M('order')->where($map)->save($data);
			if($result !== false)
			{
				$this->ajaxReturn(array('status'=>1),'JSON');
			}
			$this->ajaxReturn(array('status'=>0),'JSON');
		}
	}

}
