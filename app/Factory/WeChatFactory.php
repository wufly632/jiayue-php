<?php


declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Factory;

use App\Common\Api\Response;
use App\Common\Api\Status;
use App\Exception\BusinessException;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\ServiceContainer;
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Guzzle\CoroutineHandler;
use Hyperf\Guzzle\HandlerStackFactory;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\RedirectResponse;

class WeChatFactory
{
    const OFFICIAL_ACCOUNT = 'officialAccount';

    const MINI_PROGRAM = 'miniProgram';

    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var
     */
    protected $config;

    /**
     * @var Response
     */
    protected $response;

    /**
     * @var RequestInterface
     */
    protected $request;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->config = $this->container->get(ConfigInterface::class);
        $this->response = $this->container->get(Response::class);
        $this->request = $this->container->get(RequestInterface::class);
    }

    public function make($source, $class = self::OFFICIAL_ACCOUNT)
    {
        $config = $this->config->get('wechat');
        if (! $config[$source]) {
            throw new \Exception(Status::ERR_SYS, '配置不存在');
        }

        $config = $config[$source];

        /** @var ServiceContainer $app */
        $app = Factory::$class($config);

        // 设置 HttpClient，当前设置没有实际效果，在数据请求时会被 guzzle_handler 覆盖，但不保证 EasyWeChat 后面会修改这里。
        $config = $app['config']->get('http', []);
        $config['handler'] = $this->container->get(HandlerStackFactory::class)->create();
        $app->rebind('http_client', new Client($config));

        // 重写 Handler
        $app['guzzle_handler'] = new CoroutineHandler();

        // 设置缓存
        $app['cache'] = $this->container->get(RedisCache::class);

        // 设置 OAuth 授权的 Guzzle 配置
        AbstractProvider::setGuzzleOptions([
            'http_errors' => false,
            'handler' => HandlerStack::create(new CoroutineHandler()),
        ]);

        return $app;
    }

    /**
     * 初始化Request.
     * @return ServiceContainer
     */
    public function initRequest(ServiceContainer $container)
    {
        $container['request']->query = new ParameterBag($this->request->all());
        return $container;
    }

    /**
     * 页面重定向.
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function redirect(RedirectResponse $redirectResponse)
    {
        $url = $redirectResponse->headers->get('Location');

        return $this->response->redirect($url);
    }
}
