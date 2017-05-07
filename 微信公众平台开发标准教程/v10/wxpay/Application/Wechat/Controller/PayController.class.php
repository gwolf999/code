<?php
namespace Wechat\Controller;
/**
 * 微信JS支付控制器
 */
class PayController extends WechatController
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

	public function index()
	{
		$this->checkUserWxLogin();						  // 检测用户是否授权登录并获取用户OpenId
		$tools = new \WxPayJsApi();						 // JS API所需参数处理类

		$openid = session('openid');						// 获取openid
		$input = new \WxPayUnifiedOrder();				  // 实例化订单创建对象
		$input->SetBody("test");							// 设置商品信息
		$input->SetAttach("test");
		$input->SetOut_trade_no(\WxPayConfig::MCHID.date("YmdHis"));//设置订单号
		$input->SetTotal_fee("1");						  // 设置实际支付金额
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag("test");
		// 设置通知回调接口
		$input->SetNotify_url("http://wechat.hello-orange.com/wxpay/index.php/Wechat/Pay/wxnotify");
		$input->SetTrade_type("JSAPI");					 // 设置微信支付类型
		$input->SetOpenid($openid);						 // 设置用户openid
		$order = \WxPayApi::unifiedOrder($input);		   // 使用微信接口处理类创建订单信息
		$this->assign('order' , $order);
		echo "<pre>";
		echo "<h2>订单信息：</h2>";
		print_r($order);
		$jsApiParameters = $tools->GetJsApiParameters($order);//根据订单信息生成JS API发起支付所需参数
        echo "<h2>JS API发起支付参数信息：</h2>";
		$this->assign('jsApiParameters' , $jsApiParameters);
		print_r(json_decode($jsApiParameters , true));

		$this->display();
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

	// 检查是否已经授权并引导授权方法
	private function checkUserWxLogin()
	{
		if(!session('openid'))						  // 判断是否已经成功授权
		{
			import("@.Lib.Wx.WxAuth");				  // 引入WxAuth类库
			$wx = new \WxAuth(APP_ID ,APP_SECRET);	  // 实例化对象
			$wx->setReturnUrl(U('Auth/getUserInfo'));   // 设置回调地址
			redirect($wx->createWxAuthUrl(1));		  // 构建静默授权地址并跳转
		}
	}
}
