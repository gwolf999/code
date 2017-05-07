<?php
namespace Wechat\Controller;
/**
 * 首页控制器
 */
class IndexController extends WechatController
{

    // 通过refresh_token获取access_token
    public function getNewAccessToken()
    {
        // 存储在服务器的refresh_token
        $refreshToken = 'm2SBRxMjEE0IAR3d-g432qmuGv68YxmOAU5fgQsdkMitsGh1dwG5zrwvwOCPxru8esSWbipt4vGGwBI150L6FnBHk4Ab0Qxw7BdRcOKwZO4';
        import("@.Lib.Wx.WxAuth");
        $wx = new \WxAuth(APP_ID ,APP_SECRET);
        echo "<pre>";
        dump($wx->refreshToken($refreshToken));
    }

    // 获取授权地址
    public function getAuthURL()
    {
        import("@.Lib.Wx.WxAuth");                  // 引入Lib目录下的WxAuth类库
        $wx = new \WxAuth(APP_ID ,APP_SECRET);      // 实例化WxAuth类
        $wx->setReturnUrl(U('Auth/getUserInfo'));   // 设置回调地址
        echo $wx->createWxAuthUrl(1);               // 获取用户访问授权地址信息
    }

    // 用户个人信息页
    public function ucenter()
    {
        // 微信登录授权验证
        $this->checkUserWxLogin();
        // 从session缓存或者数据库中查询出用户基本信息
        $this->assign('info' , session('userinfo'));
        $this->display();
    }

    // 用户登录唯一入口
    public function login()
    {
        // 监测是否已经授权
        $this->checkUserWxLogin();
        // 跳转到用户个人中心页面
        $this->redirect('Index/ucenter');
    }

    // 检查是否已经授权并引导授权方法
    private function checkUserWxLogin()
    {
        if(!session('openid'))                          // 判断是否已经成功授权
        {
            import("@.Lib.Wx.WxAuth");                  // 引入WxAuth类库
            $wx = new \WxAuth(APP_ID ,APP_SECRET);      // 实例化对象
            $wx->setReturnUrl(U('Auth/getUserInfo'));   // 设置回调地址
            redirect($wx->createWxAuthUrl(1));          // 构建静默授权地址并跳转
        }
    }


    // 服务器配置入口
    public function index()
    {
        $token = 'weixin';
        import("@.Lib.Wx.WxBase");
        $wx = new \WxBase(APP_ID , APP_SECRET , $token);
        $wx->init();
    }


}
