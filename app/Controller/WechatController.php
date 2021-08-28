<?php


namespace App\Controller;

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
        $wxlogin_url = "https://open.weixin.qq.com/connect/qrconnect?appid=".$app->getId()."&redirect_uri={$redirect_uri}&response_type=code&scope=snsapi_login&state={$state}#wechat_redirect";
    }
}
