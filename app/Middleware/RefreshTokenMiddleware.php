<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Common\Api\Response;
//use App\Constants\HttpCode;
//use App\Utils\ApiResponseTrait;
use App\Common\Api\Status;
use Exception;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Codec\Json;
use Hyperf\Utils\Context;
use HyperfExt\Jwt\Contracts\ManagerInterface;
use HyperfExt\Jwt\Exceptions\TokenExpiredException;
use HyperfExt\Jwt\JwtFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use stdClass;

class RefreshTokenMiddleware implements MiddlewareInterface
{
//    use ApiResponseTrait;

//    /**
//     * @Inject()
//     * @var \Hyperf\HttpServer\Contract\ResponseInterface
//     */
    protected $response;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject()
     * @var ManagerInterface
     */
    protected $manager;

    /**
     * @Inject()
     * @var JwtFactory
     */
    protected $jwtFactory;

    public function __construct(ContainerInterface $container)
    {
        $this->response = Context::get(ResponseInterface::class);
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $jwt = $this->jwtFactory->make();
        try {
            $jwt->checkOrFail();
        } catch (Exception $exception) {
            if (! $exception instanceof TokenExpiredException) {
                return $this->response->withStatus(200)->withAddedHeader('content-type', 'application/json')
                    ->withBody(new SwooleStream(Json::encode([
                        'code'=>40100,
                        'msg'=>$exception->getMessage(),
                        'data' => new stdClass(),
                    ])));
            }
            try {
                $token = $jwt->getToken();

                // 刷新token
                $new_token = $jwt->getManager()->refresh($token);

                // 解析token载荷信息
                $payload = $jwt->getManager()->decode($token, false, true);

                // 旧token加入黑名单
                $jwt->getManager()->getBlacklist()->add($payload);

                // 一次性登录，保证此次请求畅通
                auth($payload->get('guard') ?? config('auth.default.guard'))->onceUsingId($payload->get('sub'));

                return $handler->handle($request)->withHeader('authorization', 'bearer ' . $new_token);
            } catch (Exception $exception) {
                return $this->response->withStatus(200)->withAddedHeader('content-type', 'application/json')
                    ->withBody(new SwooleStream(Json::encode([
                        'code'=>40100,
                        'msg'=>$exception->getMessage(),
                        'data' => new stdClass(),
                    ])));
            }
        }

        return $handler->handle($request);
    }



}
