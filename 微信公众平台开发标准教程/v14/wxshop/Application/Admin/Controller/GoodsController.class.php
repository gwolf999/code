<?php
namespace Admin\Controller;
/**
 * 商品控制器
 */
class GoodsController extends AdminController
{
	// 商品分类列表
	public function index()
	{
		$map['status'] = array('gt' , -1);			//查看除删除状态外的分类列表
		$list = $this->lists(M('goods_cates'),$map , 'create_time desc');
		$this->assign('_list' , $list);				// 变量置换到模板
		$this->meta_title = "商品分类列表";
		$this->display();
	}

	// 商品分类编辑页面
	public function edit()
	{
		$id = I('id');
		if($id)										// 判断是新增还是编辑
		{
			$map['id'] = $id;						// 查询分类信息
			$info = M('goods_cates')->where($map)->find();
			$this->assign('info' , $info);			// 变量置换到模板
		}
		$this->meta_title = "编辑商品分类";
		$this->display();
	}

	// 保存新增/编辑分类数据
	public function update()
	{
		$id = I('id');
		$model = D('GoodsCates');					// 实例化自定义模型
		$data = $model->create();					// 自定义模型自动创建
		if(!$data)									// 表单参数验证
		{
			$this->error($model->getError());
		}
		if($id)										// 判断数据新增或者编辑
		{
			$map['id'] = $id;
			$model->where($map)->save();			// 编辑
		}
		else
		{
			$model->add();							// 新增
		}

		$this->success('保存成功!', U('Goods/Index'));
	}

	// 修改商品分类状态
	public function setStatus()
	{
		return parent::setStatus('goods_cates');
	}

	public function setGoodsStatus()
	{
		return parent::setStatus('goods');
	}

	// 商品分类列表
	public function goods_list()
	{
		$name = I('name');
		if($name != '')
		{
			$map['name'] = array('like' , '%'.$name.'%');
		}
		$map['status'] = array('gt' , -1);
		$list = $this->lists(M('goods'),$map , 'create_time desc');
		$this->assign('_list' , $list);
		$this->meta_title = "商品列表";
		$this->display();
	}

	public function goods_edit()
	{
		$id = I('id');
		if($id)
		{
			$map['id'] = $id;
			$info = M('goods')->where($map)->find();
			$this->assign('info' , $info);
		}

		unset($map);
		$map['status'] = array('gt' , 0);
		$goods_cates_list = M('goods_cates')->where($map)->select();
		$this->assign('goods_cates_list' , $goods_cates_list);
		$this->meta_title = "编辑商品详情";
		$this->display();
	}

	public function goods_update()
	{
		$id = I('id');
		$model = D('Goods');
		$data = $model->create();
		if(!$data)
		{
			$this->error($model->getError());
		}
		if($id)
		{
			$map['id'] = $id;
			$model->where($map)->save();
		}
		else
		{
			$model->add();
		}

		$this->success('保存成功!', U('Goods/goods_list'));
	}
}
