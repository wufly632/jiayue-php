<?php


namespace App\Controller;

use Hyperf\HttpServer\Contract\RequestInterface;
use Naixiaoxin\HyperfWechat\EasyWechat;
use Naixiaoxin\HyperfWechat\Helper;

class WechatController extends AbstractController
{
    public function test()
    {


        $app = EasyWechat::officialAccount();
        $app->server->push(function ($message) {
            return "欢迎关注 EasyWechat！";
        });
        // 一定要用Helper::Response去转换
        return Helper::Response($app->server->serve());
    }

    public function qrcode()
    {
        $app = EasyWechat::officialAccount();
        $wxlogin_url = "https://open.weixin.qq.com/connect/qrconnect?appid=" . $app->getId() . "&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_login&state={$state}#wechat_redirect";
    }


    /* *
     * 获取分享链接
     * 使用了 easywechat 没用过的自行看下官网文档 https://www.easywechat.com/
     * @user AarthiModoo
     * @time 2019-12-15 21:26:07
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function shareUrl(RequestInterface $request)
    {
        $url = $request->input('url'); // 当前页面url传过来
        $app = EasyWechat::officialAccount();
        $app->jssdk->setUrl($url);
        $APIs = ['updateAppMessageShareData', 'updateTimelineShareData', "onMenuShareTimeline", "onMenuShareAppMessage",];
        $data = $app->jssdk->buildConfig($APIs, $debug = true, $beta = false, $json = false);

        return $this->response->apiSuccess([
            'config' => $data,
        ]);
    }
}
