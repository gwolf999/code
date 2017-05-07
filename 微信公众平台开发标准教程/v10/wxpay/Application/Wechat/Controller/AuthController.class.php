<?php
namespace Wechat\Controller;

/**
 * 首页控制器
 */
class AuthController extends WechatController
{

    private $userinfo = null;
    private $access_data = null;

    // 微信回调地址
    public function getUserInfo()
    {
        header('Content-type:text/html;charset=utf-8');
        // 第一步：获取微信回调的code值
        $code = I('code');
        if($code)
        {
            import("@.Lib.Wx.WxAuth");
            $wx = new \WxAuth(APP_ID ,APP_SECRET);
            // 第二步：根据code获取access_token;
            $this->access_data = $wx->getAccessTokenByCode($code);
            // 第三步：拉取用户信息
            $this->userinfo = $wx->getUserInfoByOpenID();
            // 第四步：缓存openid到session中去
            $this->cacheOpenID();
            // 第五步：新增/更新用户信息
            if(true)
            {
                // 第六步：页面重定向到指定地址
                redirect(U('Pay/index'));
            }
        }
        else
        {
            $this->error('获取Code失败！，请稍后再试！');
        }
    }

    // 把获取到的用户信息缓存到session
    private function cacheOpenID()
    {
        if($this->access_data !== null && $this->access_data['access_token'])
        {
            session('openid' , $this->access_data['openid']);   //缓存openid
            session('userinfo' , $this->userinfo);              //缓存用户信息
        }
        else
        {
            $this->error('缓存OpenID失败！');
        }
    }


}
