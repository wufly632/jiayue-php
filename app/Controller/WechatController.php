<?php


namespace App\Controller;

use App\Common\Api\Status;
use App\Model\User;
use App\Model\WechatUser;
use EasyWeChat\BasicService\Application;
use EasyWeChat\Factory;
use Hyperf\DbConnection\Db;
use Hyperf\Guzzle\ClientFactory;
use Hyperf\HttpServer\Contract\RequestInterface;
use HyperfExt\Jwt\Contracts\JwtFactoryInterface;
use Naixiaoxin\HyperfWechat\EasyWechat;
use Naixiaoxin\HyperfWechat\Helper;


class WechatController extends AbstractController
{
    /**
     * @var \Hyperf\Guzzle\ClientFactory
     */
    private $clientFactory;

    public function __construct(ClientFactory $clientFactory)
    {
        $this->clientFactory = $clientFactory;
    }

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
        $APIs = ['updateAppMessageShareData', 'updateTimelineShareData', 'onMenuShareAppMessage','onMenuShareTimeline', 'onMenuShareQQ', 'onMenuShareQZone'];
        $data = $app->jssdk->buildConfig($APIs, $debug = false, $beta = false, $json = false);

        return $this->response->apiSuccess([
            'config' => $data,
        ]);
    }

    public function login(RequestInterface $request)
    {
        $code = $request->input('code');

        if (!$code) {
            return $this->response->apiError(new Status(Status::ERR_AUTH));
        }
        try {

            // $options 等同于 GuzzleHttp\Client 构造函数的 $config 参数
            $options = [];
            // $client 为协程化的 GuzzleHttp\Client 对象
            $client = $this->clientFactory->create($options);
            $data = $client->get('http://localhost:5555/wechat/code?code='.$code);
            if ($data->getStatusCode() === 200) {
                $res = json_decode($data->getBody()->getContents());
                if ($res->code === 20000) {
                    Db::beginTransaction();
                    $openId = $res->data->openid;
                    $wechatUser =  WechatUser::query()->where(['openid' => $openId])->first();
                    if (!$wechatUser) {
                        $user = User::create([
                            'nickname' => $res->data->nickname,
                        ]);
                        $wechatUser = WechatUser::query()
                            ->create([
                                'openid' => $openId,
                                'user_id' => $user->id,
                                'nickname' => $res->data->nickname,
                                'sex' => $res->data->sex,
                            ]);
                    } else {
                        $user = $wechatUser->user;
                    }
                    Db::commit();
                    $token = auth()->login($user);
                    return $this->response->apiSuccess([
                            'accessToken' => $token,
                            'tokenType' => 'bearer',
                            'expireIn' => make(JwtFactoryInterface::class)->make()->getPayloadFactory()->getTtl()
                        ]
                    );
                }
            }

        } catch (\Exception $exception) {
            Db::rollBack();
            var_dump($exception->getMessage());
//            $this->response->apiError(new Status(Status::ERR_AUTH));
        }
        return $this->response->apiError(new Status(Status::ERR_AUTH));
    }
}
