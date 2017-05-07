<?php
namespace Wechat\Controller;
/**
 * 用户控制器
 */
class UserController extends WechatController
{

	// 上传用户证件图片
	public function upload_cardimg()
	{
		import('@.Lib.Wx.WxJsSdk');
		$jssdk = new \WxJsSdk(APP_ID, APP_SECRET);			// 实例化对象
		$signPackage = $jssdk->GetSignPackage();			// 获取wx验证参数
		$this->assign('signPackage' , $signPackage);		// 变量置换
		$this->display();
	}

	// 下载图片到本地
	public function downloadImg()
	{
		if(IS_POST)
		{
			$media_id = I('media_id');							// 获取media_id
			if(!$media_id)										// 参数非空验证
			{
				$this->ajaxReturn(array('status'=> 0 ,'msg'=>'参数错误！'));
			}
			import('@.Lib.Wx.WxMenu');							// 引入处理类
			$wx = new \WxMenu(APP_ID, APP_SECRET);				// 实例化并获取access_token
			// 构建图片下载地址
			$pic_url = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token='.$wx->getAccessToken().'&media_id='.$media_id;
			$data_time = date('Y-m-d',time());					// 获取当日日期
			$dir = realpath('./')."/Uploads/Picture/".$data_time."/";
			if(!file_exists($dir))								// 判断目录是否存在
			{
				mkdir($dir,0777);
			}
			$time = time();
			$filename = 'wx_'.$time.rand(1000, 9000).'.jpg';	//定义图片文件名
			//将图片下载到项目服务器上
			$img = $this->getImage($pic_url,$dir,$filename);
			$img['today_time'] = $data_time;
			// 返回本地存储图片信息
			$this->ajaxReturn(array('status'=>1,'data'=>$img));
		}
	}

	// 下载远程文件到本地
	private function getImage($url,$save_dir='',$filename='',$type=0)
	{
		//根据URL获取远程文件
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_TIMEOUT, 500);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_URL, $url);

		$res = curl_exec($curl);
		curl_close($curl);
		// 把图片保存到指定目录下的指定文件
		file_put_contents($save_dir.$filename, $res);
		return array(
			'file_name'=>$filename,
			'save_path'=>$save_dir.$filename,
			'error'=>0
		);
	}
}
