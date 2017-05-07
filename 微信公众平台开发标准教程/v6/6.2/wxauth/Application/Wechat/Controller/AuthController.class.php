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
            if($this->saveUserInfo())
            {
                // 第六步：页面重定向到指定地址
                redirect(U('Index/ucenter'));
            }
        }
        else
        {
            $this->error('获取Code失败！，请稍后再试！');
        }
    }

    // 新增或者更新用户信息
    private function saveUserInfo()
    {
        // 查询member表中是否有关联openid的用户
        $map['openid'] = $this->userinfo['openid'];
        $member_info = M('member')->where($map)->find();
        if($member_info !== null)
        {
            // 已存在用户更新用户信息，如头像昵称等
            $data = $this->userinfo;
            M('member')->where($map)->save($data);
            return true;
        }
        else
        {
            // 新增基础用户信息
            $ucenter_data['username'] = 'wx'.date('Ymd').mt_rand(1000 , 9999);
            $ucenter_data['email'] = $ucenter_data['username']."@test.com";
            $ucenter_data['reg_time'] = time();
            $ucenter_data['reg_ip'] = get_client_ip(1);
            $ucenter_data['status'] = 1;
            $insertId = M('ucenter_member')->add($ucenter_data);

            // 新增用户其他信息
            if($insertId !== false)
            {
                $member_data = $this->userinfo;
                $member_data['uid'] = $insertId;
                $member_data['status'] = 1;
                $member_data['reg_time'] = time();
                $member_data['reg_ip'] = get_client_ip(1);
                M('member')->add($member_data);
                return true;
            }
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
