<?php
namespace Admin\Controller;

/**
 * 图集控制器
 */
class PhotosController extends AdminController
{

    // 图集首页
    public function index()
    {
        $map['status'] = array('gt' , -1);
        $list = $this->lists(M('photos') , $map , 'create_time desc');
        $this->assign('_list' , $list);
        $this->meta_title = "图集管理列表";
        $this->display();
    }

    // 更改记录状态
    public function setPhotosStatus()
    {
        return parent::setStatus('photos');
    }

    // 图集编辑界面
    public function edit(){
        $id = I('id');
        if($id)
        {
            // 查询已经存在的信息
            $this->assign('info' ,M('photos')->where(array('id'=>$id))->find());
        }
        $this->meta_title = "编辑图集信息";
        $this->display();
    }

    // 保存图集信息
    public function update()
    {
        if(IS_POST)
        {
            $id = I('id');              //通过id判断是新增还是编辑
            $model = D('Photos');       // 实例化自定义模型
            $data = $model->create();   //自动创建数据
            if(!$data)
            {
                //展示自定义模型返回的错误信息
                $this->error($model->getError());
            }

            if($id)
            {
                // 更新信息
                $model->where(array('id'=>$id))->save();
            }
            else
            {
                // 新增信息
                $model->add();
            }

            // 执行完毕跳转到列表页
            $this->success('保存成功！' ,U('Photos/index'));
        }
    }

}
