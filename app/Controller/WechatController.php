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
}
