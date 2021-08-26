<?php


namespace App\Controller;



use EasyWeChat\Factory;

class WechatController extends AbstractController
{
    public function test()
    {
        $config = [
            'app_id' => 'wxe637ff42b3b710d1',
            'secret' => '24985d07c2e689946758673673b0efdb',
            'token' => 'wufly',
            'response_type' => 'array',
            //...
        ];

        $app = Factory::officialAccount($config);

        $response = $app->server->serve();

// 将响应输出
        $response->send();exit; // Laravel 里请使用：return $response;
    }
}
