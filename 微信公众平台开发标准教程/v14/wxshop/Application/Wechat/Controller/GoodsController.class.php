<?php
namespace Wechat\Controller;
/**
 * 商品控制器
 */
class GoodsController extends WechatController
{

	// 商品列表首页
	public function index()
	{
		$cates_id = I('id');
		$keys = I('keys');
		if(!$keys && !$cates_id)
		{
			$this->error('参数错误！');
		}

		if($cates_id)
		{
			$map['id'] = $cates_id;
			$goods_cates_info = M('goods_cates')->where($map)->find();
		}

		//查询商品列表
		unset($map);
		if($cates_id)
		{
			$map['goods_cates_id'] = $cates_id;
		}
		if($keys)
		{
			$map['name'] = array('like' , '%'.$keys.'%');
		}
		$map['status'] = array('gt' , 0);
		$goods_list = M('goods')->where($map)->select();

		// 处理商品数据
		$new_goods_list = array();
		for($i = 0 ; $i <count($goods_list) ; $i += 2)
		{
			$goods_info[0] = $goods_list[$i]?$goods_list[$i]:array();
			$goods_info[1] = $goods_list[$i+1]?$goods_list[$i+1]:array();
			$new_goods_list[] = $goods_info;
			unset($goods_info);
		}

		$this->assign('goods_list' , $new_goods_list);
		$this->assign('goods_cates_info' , $goods_cates_info);
		$this->assign('keys' , $keys);
		$this->meta_title = "商品列表";
		$this->display();
	}

	// 商品详情页
	public function detail()
	{
		$goods_id = I('id');
		if(!$goods_id)
		{
			$this->error('参数错误！');
		}

		$map['id'] = $goods_id;
		$goods_info = M('goods')->where($map)->find();

		$this->assign('goods_info' , $goods_info);
		$this->meta_title = "商品列表";
		$this->display();
	}

	//添加商品到用户的购物车
	public function addShopCar()
	{
		if(IS_POST)
		{
			$goods_id = I('goods_id');
			$uid = I('uid');
			if(!$goods_id || !$uid)
			{
				$this->ajaxReturn(array('status'=>0 , 'msg'=>'参数错误！'),'JSON');
			}

			$map['uid'] = $uid;
			$map['goods_id'] = $goods_id;
			$info = M('goods_shopcar')->where($map)->find();
			if($info === null)
			{
				$data = $map;
				$data['num'] = 1;
				M('goods_shopcar')->add($data);
			}
			else
			{
				M('goods_shopcar')->where($map)->setInc('num' , 1);
			}

			$this->ajaxReturn(array('status'=>1 , 'msg'=>'添加成功！'),'JSON');
		}
	}
}
