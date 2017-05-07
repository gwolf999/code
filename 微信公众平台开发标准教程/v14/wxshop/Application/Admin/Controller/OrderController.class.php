<?php
namespace Admin\Controller;
/**
 * 订单控制器
 */
class OrderController extends AdminController
{
	// 订单列表
	public function index()
	{

		$map['status'] = array('gt' , -1);// 查询出所有未删除的订单
		$list = $this->lists(M('order'),$map , 'create_time desc');
		$this->assign('_list' , $list);//变量置换
		$this->meta_title = "订单列表";
		$this->display();
	}

	// 订单详情页
	public function detail()
	{
		$id = I('id');
		if($id)
		{
			$map['id'] = $id;
			$info = M('order')->where($map)->find();//查询订单信息
			unset($map);
			$map['order_id'] = $id;					// 查询订单所属的商品
			$goods_list = M('order_goods')->where($map)->select();
			foreach ($goods_list as $key => $value)
			{
				// 查询订单的商品信息
				$goods_list[$key]['name'] = M('goods')->where('id='.$value['goods_id'])->getField('name');
				$goods_list[$key]['all_price'] = $value['goods_num'] * $value['goods_price'];
			}
			// 查询用户的发货信息
			$uinfo = M('member_address')->where('uid='.$info['uid'])->find();
			$this->assign('uinfo' , $uinfo);
			$this->assign('goods_list' , $goods_list);
			$this->assign('info' , $info);
		}
		$this->meta_title = "订单详情";
		$this->display();
	}

	// 执行发货操作
	public function update()
	{
		if(IS_POST)
		{
			$id = I('id');// 获取订单id
			$ship_number = I('ship_number');// 获取发货物流单号
			$is_ship = I('is_ship');	// 获取是否发货状态
			if($is_ship && $ship_number == '')
			{
				$this->error('请输入快递物流单号！');
			}
			$map['id'] = $id;
			$data['is_ship'] = $is_ship;
			$data['ship_number'] = $ship_number;
			M('order')->where($map)->save($data);// 更新

			$this->success('保存成功！',U('Order/index'));
		}
	}

	// 更新订单记录状态
	public function setStatus()
	{
		return parent::setStatus('order');
	}

}
