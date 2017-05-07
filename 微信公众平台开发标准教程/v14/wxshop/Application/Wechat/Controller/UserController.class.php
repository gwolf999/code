<?php
namespace Wechat\Controller;
/**
 * 用户控制器
 */
class UserController extends WechatController
{

	// 商品列表首页
	public function index()
	{
		$this->checkUserWxLogin();
		$this->assign('info' , session('userinfo'));
		$this->meta_title = "个人中心";
		$this->display();
	}

	// 用户收货信息编辑页
	public function info()
	{
		$this->checkUserWxLogin();
		$uid = $this->uid?$this->uid:1;
		$map['uid'] = $uid;
		$info = M('member_address')->where($map)->find();
		$this->assign('info' , $info);
		$this->meta_title = "个人中心";
		$this->display();
	}

	public function update()
	{
		if(IS_POST)
		{
			$uid = $this->uid?$this->uid:1;
			$name = I('name');
			$mobile = I('mobile');
			$address = I('address');
			if(!$name || !$mobile || !$address)
			{
				$this->ajaxReturn(array('status' => 0 ,'msg'=>'参数错误！'),'JSON');
			}

			$map['uid'] = $uid;
			$info = M('member_address')->where($map)->find();
			$data['name'] = $name;
			$data['mobile'] = $mobile;
			$data['address'] = $address;
			if($info !== null)
			{
				M('member_address')->where($map)->save($data);
			}
			else
			{
				$data['uid'] = $uid;
				$data['status'] = 1;
				$data['create_time'] = time();
				M('member_address')->add($data);
			}

			$this->ajaxReturn(array('status' => 1 ,'msg'=>'保存成功！'),'JSON');
 		}
	}
}
