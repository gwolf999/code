<?php
namespace Wechat\Controller;
use Think\Controller;
/**
 */
class WechatController extends Controller
{
	protected $uid = null;
	// 初始化方法
	protected function _initialize(){
        // 根据openid获取用户基础信息
		if(session('openid'))
		{
			$map['openid'] = session('openid');
			$user_info = M('member')->where($map)->find();
			$this->assign('user_info' , $user_info);
			$this->uid = $user_info['uid'];
		}
	}

	// 检查是否已经授权并引导授权方法
	protected function checkUserWxLogin()
	{
		if(!session('openid')) 	 	 	 	 	 	  // 判断是否已经成功授权
		{
			import("@.Lib.Wx.WxAuth"); 	 	 	 	  // 引入WxAuth类库
			$wx = new \WxAuth(APP_ID ,APP_SECRET); 	  // 实例化对象
			$wx->setReturnUrl(U('Auth/getUserInfo'));   // 设置回调地址
			redirect($wx->createWxAuthUrl(0)); 	 	  // 构建静默授权地址并跳转
		}
	}
}
