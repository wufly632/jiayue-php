<?php

use HyperfExt\Auth\Contracts\AuthManagerInterface;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;

if (!function_exists('auth')) {
    /**
     * Auth认证辅助方法
     * @param string|null $guard
     * @return \HyperfExt\Auth\Contracts\GuardInterface|\HyperfExt\Auth\Contracts\StatefulGuardInterface|\HyperfExt\Auth\Contracts\StatelessGuardInterface
     */
    function auth(string $guard = null)
    {
        if (is_null($guard)) $guard = config('auth.default.guard');
        return make(AuthManagerInterface::class)->guard($guard);
    }
}

if (!function_exists('request')) {
    /**
     * 请求方法辅助方法
     * @return RequestInterface|mixed
     */
    function request()
    {
        return make(RequestInterface::class);
    }
}

if (!function_exists('response')) {
    /**
     * 响应方法辅助方法
     * @return ResponseInterface|mixed
     */
    function response()
    {
        return make(ResponseInterface::class);
    }
}
